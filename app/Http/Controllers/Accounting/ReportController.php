<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\AccountGroup;
use App\Models\JournalEntryItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function trialBalance(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $accounts = Account::with('accountGroup')
            ->select('accounts.*', 
                DB::raw("SUM(CASE WHEN journal_entry_items.type = 'debit' THEN journal_entry_items.amount ELSE 0 END) as total_debit"),
                DB::raw("SUM(CASE WHEN journal_entry_items.type = 'credit' THEN journal_entry_items.amount ELSE 0 END) as total_credit")
            )
            ->join('journal_entry_items', 'accounts.id', '=', 'journal_entry_items.account_id')
            ->join('journal_entries', 'journal_entry_items.journal_entry_id', '=', 'journal_entries.id')
            ->whereBetween('journal_entries.entry_date', [$startDate, $endDate])
            ->groupBy('accounts.id')
            ->havingRaw('total_debit > 0 OR total_credit > 0')
            ->get();
            
        return view('accounting.reports.trial_balance', compact('accounts', 'startDate', 'endDate'));
    }

    public function incomeStatement(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $accounts = Account::with('accountGroup')
            ->select('accounts.*', 
                DB::raw("SUM(CASE WHEN journal_entry_items.type = 'debit' THEN journal_entry_items.amount ELSE 0 END) as total_debit"),
                DB::raw("SUM(CASE WHEN journal_entry_items.type = 'credit' THEN journal_entry_items.amount ELSE 0 END) as total_credit")
            )
            ->join('journal_entry_items', 'accounts.id', '=', 'journal_entry_items.account_id')
            ->join('journal_entries', 'journal_entry_items.journal_entry_id', '=', 'journal_entries.id')
            ->whereBetween('journal_entries.entry_date', [$startDate, $endDate])
            ->whereHas('accountGroup', function($q) {
                $q->whereIn('type', ['Income', 'Expenses']);
            })
            ->groupBy('accounts.id')
            ->havingRaw('total_debit > 0 OR total_credit > 0')
            ->get();

        $incomeAccounts = $accounts->filter(function($a) { return $a->accountGroup->type === 'Income'; });
        $expenseAccounts = $accounts->filter(function($a) { return $a->accountGroup->type === 'Expenses'; });

        $totalIncome = $incomeAccounts->sum('total_credit') - $incomeAccounts->sum('total_debit');
        $totalExpenses = $expenseAccounts->sum('total_debit') - $expenseAccounts->sum('total_credit');
        $netIncome = $totalIncome - $totalExpenses;

        return view('accounting.reports.income_statement', compact('incomeAccounts', 'expenseAccounts', 'totalIncome', 'totalExpenses', 'netIncome', 'startDate', 'endDate'));
    }

    public function balanceSheet(Request $request)
    {
        $date = $request->input('date', Carbon::now()->format('Y-m-d'));

        // Balance sheet is cumulative up to the given date
        $accounts = Account::with('accountGroup')
            ->select('accounts.*', 
                DB::raw("SUM(CASE WHEN journal_entry_items.type = 'debit' THEN journal_entry_items.amount ELSE 0 END) as total_debit"),
                DB::raw("SUM(CASE WHEN journal_entry_items.type = 'credit' THEN journal_entry_items.amount ELSE 0 END) as total_credit")
            )
            ->join('journal_entry_items', 'accounts.id', '=', 'journal_entry_items.account_id')
            ->join('journal_entries', 'journal_entry_items.journal_entry_id', '=', 'journal_entries.id')
            ->where('journal_entries.entry_date', '<=', $date)
            ->groupBy('accounts.id')
            ->havingRaw('total_debit > 0 OR total_credit > 0')
            ->get();

        $assetAccounts = $accounts->filter(function($a) { return $a->accountGroup->type === 'Assets'; });
        $liabilityAccounts = $accounts->filter(function($a) { return $a->accountGroup->type === 'Liabilities'; });
        $equityAccounts = $accounts->filter(function($a) { return $a->accountGroup->type === 'Equity'; });

        // Calculate Net Income for Retained Earnings
        $incomeAccounts = $accounts->filter(function($a) { return $a->accountGroup->type === 'Income'; });
        $expenseAccounts = $accounts->filter(function($a) { return $a->accountGroup->type === 'Expenses'; });
        $totalIncome = $incomeAccounts->sum('total_credit') - $incomeAccounts->sum('total_debit');
        $totalExpenses = $expenseAccounts->sum('total_debit') - $expenseAccounts->sum('total_credit');
        $retainedEarnings = $totalIncome - $totalExpenses;

        $totalAssets = $assetAccounts->sum('total_debit') - $assetAccounts->sum('total_credit');
        $totalLiabilities = $liabilityAccounts->sum('total_credit') - $liabilityAccounts->sum('total_debit');
        $totalEquity = $equityAccounts->sum('total_credit') - $equityAccounts->sum('total_debit');

        return view('accounting.reports.balance_sheet', compact('assetAccounts', 'liabilityAccounts', 'equityAccounts', 'totalAssets', 'totalLiabilities', 'totalEquity', 'retainedEarnings', 'date'));
    }
}
