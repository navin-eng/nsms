<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\Account;
use Illuminate\Http\Request;

class BankAccountController extends Controller
{
    public function index()
    {
        $bankAccounts = BankAccount::with('account')->get();
        // Get all accounts that are assets, to link them.
        // Assuming AccountGroup has a type 'Assets' or similar. 
        $ledgerAccounts = Account::whereHas('accountGroup', function ($q) {
            $q->where('type', 'Assets');
        })->get();

        return view('accounting.banks.index', compact('bankAccounts', 'ledgerAccounts'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'account_id'     => 'required|exists:accounts,id',
            'bank_name'      => 'required|string|max:255',
            'account_name'   => 'required|string|max:255',
            'account_number' => 'required|string|max:255|unique:bank_accounts,account_number',
            'branch'         => 'nullable|string|max:255',
            'ifsc_code'      => 'nullable|string|max:255',
            'is_active'      => 'nullable|boolean'
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        BankAccount::create($data);
        return back()->with('success', 'Bank Account added successfully.');
    }

    public function update(Request $request, BankAccount $bank)
    {
        $data = $request->validate([
            'account_id'     => 'required|exists:accounts,id',
            'bank_name'      => 'required|string|max:255',
            'account_name'   => 'required|string|max:255',
            'account_number' => 'required|string|max:255|unique:bank_accounts,account_number,' . $bank->id,
            'branch'         => 'nullable|string|max:255',
            'ifsc_code'      => 'nullable|string|max:255',
            'is_active'      => 'nullable|boolean'
        ]);

        $data['is_active'] = $request->boolean('is_active', false);

        $bank->update($data);
        return back()->with('success', 'Bank Account updated successfully.');
    }

    public function destroy(BankAccount $bank)
    {
        $bank->delete();
        return back()->with('success', 'Bank Account deleted successfully.');
    }

    public function reconciliation(Request $request)
    {
        $bankAccounts = BankAccount::with('account')->get();
        $selectedBank = null;
        $unclearedItems = collect();

        if ($request->filled('bank_id')) {
            $selectedBank = BankAccount::with('account')->findOrFail($request->bank_id);
            if ($selectedBank->account) {
                // Get uncleared journal entry items for this bank's ledger account
                $unclearedItems = \App\Models\JournalEntryItem::with('journalEntry')
                    ->where('account_id', $selectedBank->account_id)
                    ->where('is_reconciled', false)
                    ->orderBy('created_at')
                    ->get();
            }
        }

        return view('accounting.banks.reconciliation', compact('bankAccounts', 'selectedBank', 'unclearedItems'));
    }

    public function reconcile(Request $request)
    {
        $request->validate([
            'item_ids' => 'required|array',
            'item_ids.*' => 'exists:journal_entry_items,id'
        ]);

        \App\Models\JournalEntryItem::whereIn('id', $request->item_ids)
            ->update([
                'is_reconciled' => true,
                'reconciled_at' => now(),
            ]);

        return back()->with('success', count($request->item_ids) . ' transactions reconciled successfully.');
    }
}
