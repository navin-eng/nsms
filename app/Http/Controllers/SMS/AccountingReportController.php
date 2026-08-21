<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AccountGroup;
use App\Models\JournalEntry;
use App\Models\JournalEntryItem;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AccountingReportController extends Controller
{
    public function daybook(Request $request)
    {
        $date = $request->input('date', date('Y-m-d'));
        
        $entries = JournalEntry::with(['items.account'])
            ->whereDate('entry_date', $date)
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('backend.pages.sms.finance.accounting.daybook', compact('entries', 'date'));
    }

    public function ledger(Request $request)
    {
        $accounts = Account::orderBy('name')->get();
        $selectedAccount = $request->input('account_id');
        $startDate = $request->input('start_date', date('Y-m-01'));
        $endDate = $request->input('end_date', date('Y-m-d'));

        $items = collect();
        $openingBalance = 0;
        $runningBalance = 0;

        if ($selectedAccount) {
            $account = Account::with('accountGroup')->find($selectedAccount);
            $isDebitNormal = in_array($account->accountGroup->type, ['Assets', 'Expenses']);

            // Calculate Opening Balance (all transactions before start_date)
            $openingDebits = JournalEntryItem::where('account_id', $selectedAccount)
                ->whereHas('journalEntry', function($q) use ($startDate) {
                    $q->whereDate('entry_date', '<', $startDate);
                })
                ->where('type', 'debit')
                ->sum('amount');
                
            $openingCredits = JournalEntryItem::where('account_id', $selectedAccount)
                ->whereHas('journalEntry', function($q) use ($startDate) {
                    $q->whereDate('entry_date', '<', $startDate);
                })
                ->where('type', 'credit')
                ->sum('amount');

            $openingBalance = $isDebitNormal ? ($openingDebits - $openingCredits) : ($openingCredits - $openingDebits);
            $runningBalance = $openingBalance;

            // Get transactions in range
            $items = JournalEntryItem::with('journalEntry')
                ->where('account_id', $selectedAccount)
                ->whereHas('journalEntry', function($q) use ($startDate, $endDate) {
                    $q->whereBetween('entry_date', [$startDate, $endDate]);
                })
                ->get()
                ->sortBy(function($item) {
                    return $item->journalEntry->entry_date->format('Y-m-d') . '_' . $item->journalEntry->created_at;
                });
        }

        return view('backend.pages.sms.finance.accounting.ledger', compact(
            'accounts', 'selectedAccount', 'startDate', 'endDate', 'items', 'openingBalance', 'runningBalance'
        ));
    }

    public function balanceSheet(Request $request)
    {
        $asOfDate = $request->input('date', date('Y-m-d'));

        // To calculate the balance sheet, we need the sum of all accounts grouped by type up to the $asOfDate.
        $groups = AccountGroup::with('accounts')->get();
        
        $report = [
            'Assets' => [],
            'Liabilities' => [],
            'Equity' => [],
            'Income' => [],
            'Expenses' => [],
        ];

        $totals = [
            'Assets' => 0,
            'Liabilities' => 0,
            'Equity' => 0,
            'Income' => 0,
            'Expenses' => 0,
        ];

        foreach ($groups as $group) {
            $groupType = $group->type;
            $isDebitNormal = in_array($groupType, ['Assets', 'Expenses']);
            
            $groupBalance = 0;
            $accountBalances = [];

            foreach ($group->accounts as $account) {
                $debits = JournalEntryItem::where('account_id', $account->id)
                    ->whereHas('journalEntry', function($q) use ($asOfDate) {
                        $q->whereDate('entry_date', '<=', $asOfDate);
                    })
                    ->where('type', 'debit')
                    ->sum('amount');
                    
                $credits = JournalEntryItem::where('account_id', $account->id)
                    ->whereHas('journalEntry', function($q) use ($asOfDate) {
                        $q->whereDate('entry_date', '<=', $asOfDate);
                    })
                    ->where('type', 'credit')
                    ->sum('amount');
                    
                $balance = $isDebitNormal ? ($debits - $credits) : ($credits - $debits);
                
                if ($balance != 0) {
                    $accountBalances[] = [
                        'name' => $account->name,
                        'balance' => $balance
                    ];
                    $groupBalance += $balance;
                }
            }

            if ($groupBalance != 0) {
                $report[$groupType][] = [
                    'group_name' => $group->name,
                    'accounts' => $accountBalances,
                    'total' => $groupBalance
                ];
                $totals[$groupType] += $groupBalance;
            }
        }

        // Net Income rolls into Equity
        $netIncome = $totals['Income'] - $totals['Expenses'];
        $totalEquityAndLiabilities = $totals['Liabilities'] + $totals['Equity'] + $netIncome;

        return view('backend.pages.sms.finance.accounting.balance_sheet', compact(
            'report', 'totals', 'asOfDate', 'netIncome', 'totalEquityAndLiabilities'
        ));
    }
}
