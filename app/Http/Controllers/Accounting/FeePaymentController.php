<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\FeeInvoice;
use App\Models\FeePayment;
use App\Models\JournalEntry;
use App\Models\JournalEntryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FeePaymentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'fee_invoice_id' => 'required|exists:fee_invoices,id',
            'account_id' => 'required|exists:accounts,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string',
            'reference_number' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $invoice = FeeInvoice::findOrFail($request->fee_invoice_id);
        
        $balance = $invoice->total_amount - $invoice->paid_amount;
        if ($request->amount > $balance) {
            return back()->with('error', 'Payment amount cannot exceed the balance due.');
        }

        $arAccount = Account::where('code', '1200')->first(); // AR - Students
        if (!$arAccount) {
            return back()->with('error', 'Accounts Receivable not found. Please check Chart of Accounts.');
        }

        DB::beginTransaction();
        try {
            // Create Journal Entry
            $journal = JournalEntry::create([
                'entry_date' => $request->payment_date,
                'reference' => 'PAY-' . Str::random(6),
                'description' => "Payment received for Invoice #{$invoice->id} via {$request->payment_method}",
            ]);

            // Debit Cash/Bank
            JournalEntryItem::create([
                'journal_entry_id' => $journal->id,
                'account_id' => $request->account_id,
                'type' => 'debit',
                'amount' => $request->amount,
            ]);

            // Credit AR
            JournalEntryItem::create([
                'journal_entry_id' => $journal->id,
                'account_id' => $arAccount->id,
                'type' => 'credit',
                'amount' => $request->amount,
            ]);

            // Record Payment
            $receiptNumber = 'REC-' . strtoupper(Str::random(8));
            FeePayment::create([
                'fee_invoice_id' => $invoice->id,
                'payment_method' => $request->payment_method,
                'amount' => $request->amount,
                'payment_date' => $request->payment_date,
                'reference_number' => $request->reference_number,
                'receipt_number' => $receiptNumber,
                'notes' => $request->notes,
                'journal_entry_id' => $journal->id,
                'account_id' => $request->account_id,
            ]);

            // Update Invoice
            $invoice->paid_amount += $request->amount;
            if ($invoice->paid_amount >= $invoice->total_amount) {
                $invoice->status = 'Paid';
            } else {
                $invoice->status = 'Partial';
            }
            $invoice->save();

            DB::commit();

            return back()->with('success', "Payment of Rs. {$request->amount} recorded successfully. Receipt No: {$receiptNumber}");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error processing payment: ' . $e->getMessage());
        }
    }

    public function destroy(FeePayment $payment)
    {
        // Reversal of payment requires reversing the journal entry and updating invoice
        DB::beginTransaction();
        try {
            $invoice = $payment->invoice;
            $invoice->paid_amount -= $payment->amount;
            if ($invoice->paid_amount <= 0) {
                $invoice->status = 'Unpaid';
                $invoice->paid_amount = 0;
            } else {
                $invoice->status = 'Partial';
            }
            $invoice->save();

            // Reverse journal entry (or just delete it, typically reversing is better for auditing, but deleting is simpler for small systems)
            if ($payment->journal_entry_id) {
                JournalEntry::where('id', $payment->journal_entry_id)->delete();
            }

            $payment->delete();

            DB::commit();
            return back()->with('success', 'Payment reversed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error reversing payment: ' . $e->getMessage());
        }
    }
}
