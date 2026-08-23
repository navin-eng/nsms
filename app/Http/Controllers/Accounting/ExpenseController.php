<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Vendor;
use App\Models\Account;
use App\Models\JournalEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::with(['vendor', 'expenseAccount', 'paymentAccount'])->orderByDesc('expense_date')->get();
        return view('accounting.expenses.index', compact('expenses'));
    }

    public function create()
    {
        $vendors = Vendor::where('is_active', true)->orderBy('name')->get();
        $expenseAccounts = Account::whereHas('accountGroup', function($q) {
            $q->where('type', 'Expenses');
        })->get();
        $paymentAccounts = Account::whereHas('accountGroup', function($q) {
            $q->where('type', 'Assets'); // or specific bank/cash group
        })->get();

        return view('accounting.expenses.create', compact('vendors', 'expenseAccounts', 'paymentAccounts'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'expense_date' => 'required|date',
            'reference' => 'required|string|unique:expenses,reference',
            'vendor_id' => 'nullable|exists:vendors,id',
            'expense_account_id' => 'required|exists:accounts,id',
            'payment_account_id' => 'required|exists:accounts,id',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,paid',
        ]);

        DB::transaction(function () use ($data) {
            // If it's paid, we generate a Journal Entry immediately
            $journalEntryId = null;
            
            if ($data['status'] === 'paid') {
                $journal = JournalEntry::create([
                    'entry_date' => $data['expense_date'],
                    'reference' => 'JRN-' . $data['reference'],
                    'description' => 'Auto-generated for Expense: ' . ($data['description'] ?? $data['reference']),
                ]);

                // Debit Expense Account
                $journal->items()->create([
                    'account_id' => $data['expense_account_id'],
                    'type' => 'debit',
                    'amount' => $data['amount'],
                ]);

                // Credit Payment Account (Bank/Cash)
                $journal->items()->create([
                    'account_id' => $data['payment_account_id'],
                    'type' => 'credit',
                    'amount' => $data['amount'],
                ]);
                
                $journalEntryId = $journal->id;
            }

            $data['journal_entry_id'] = $journalEntryId;
            Expense::create($data);
        });

        return redirect()->route('accounting.expenses.index')->with('success', 'Expense recorded successfully.');
    }
}
