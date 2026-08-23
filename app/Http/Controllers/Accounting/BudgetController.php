<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Budget;
use App\Models\Account;
use Illuminate\Http\Request;

class BudgetController extends Controller
{
    public function index()
    {
        $budgets = Budget::with('account')->orderBy('start_date', 'desc')->get();
        
        // Calculate Actuals for each budget
        $budgets->transform(function($budget) {
            $actual = \App\Models\JournalEntryItem::where('account_id', $budget->account_id)
                        ->whereHas('journalEntry', function($q) use ($budget) {
                            $q->whereBetween('entry_date', [$budget->start_date, $budget->end_date]);
                        })
                        ->where('type', 'debit') // expenses increase by debit
                        ->sum('amount');
            
            $budget->actual = $actual;
            $budget->remaining = $budget->amount - $actual;
            $budget->usage_percent = $budget->amount > 0 ? min(100, round(($actual / $budget->amount) * 100, 2)) : 0;
            return $budget;
        });

        // Get expense accounts for creating budgets
        $expenseAccounts = Account::whereHas('accountGroup', function($q) {
            $q->where('type', 'Expenses');
        })->get();

        return view('accounting.budgets.index', compact('budgets', 'expenseAccounts'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'fiscal_year' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        Budget::create($data);
        return back()->with('success', 'Budget set successfully.');
    }

    public function update(Request $request, Budget $budget)
    {
        $data = $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'fiscal_year' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $budget->update($data);
        return back()->with('success', 'Budget updated successfully.');
    }

    public function destroy(Budget $budget)
    {
        $budget->delete();
        return back()->with('success', 'Budget deleted successfully.');
    }
}
