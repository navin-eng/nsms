<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AccountGroup;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index()
    {
        $groups = AccountGroup::orderBy('type')->orderBy('name')->get();
        $accounts = Account::with('accountGroup')->orderBy('code')->get();
        return view('backend.pages.sms.finance.accounting.accounts.index', compact('accounts', 'groups'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'account_group_id' => 'required|exists:account_groups,id',
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:accounts,code',
            'description' => 'nullable|string'
        ]);

        Account::create($data);

        return back()->with('success', 'Account created successfully.');
    }

    public function update(Request $request, Account $account)
    {
        if ($account->is_default) {
            return back()->with('error', 'Cannot modify a core system account.');
        }

        $data = $request->validate([
            'account_group_id' => 'required|exists:account_groups,id',
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:accounts,code,' . $account->id,
            'description' => 'nullable|string'
        ]);

        $account->update($data);

        return back()->with('success', 'Account updated successfully.');
    }

    public function destroy(Account $account)
    {
        if ($account->is_default) {
            return back()->with('error', 'Cannot delete a core system account.');
        }

        if (\App\Models\JournalEntryItem::where('account_id', $account->id)->exists()) {
            return back()->with('error', 'Cannot delete an account that has existing transactions in the ledger.');
        }

        $account->delete();
        return back()->with('success', 'Account deleted successfully.');
    }
}
