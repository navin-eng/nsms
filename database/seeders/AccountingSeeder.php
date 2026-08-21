<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\AccountGroup;
use Illuminate\Database\Seeder;

class AccountingSeeder extends Seeder
{
    public function run(): void
    {
        $groups = [
            ['name' => 'Current Assets', 'type' => 'Assets'],
            ['name' => 'Fixed Assets', 'type' => 'Assets'],
            ['name' => 'Current Liabilities', 'type' => 'Liabilities'],
            ['name' => 'Long Term Liabilities', 'type' => 'Liabilities'],
            ['name' => 'Equity', 'type' => 'Equity'],
            ['name' => 'Operating Income', 'type' => 'Income'],
            ['name' => 'Other Income', 'type' => 'Income'],
            ['name' => 'Operating Expenses', 'type' => 'Expenses'],
            ['name' => 'Administrative Expenses', 'type' => 'Expenses'],
        ];

        foreach ($groups as $group) {
            AccountGroup::firstOrCreate(['name' => $group['name']], $group);
        }

        // Standard accounts
        $currentAssets = AccountGroup::where('name', 'Current Assets')->first();
        $operatingIncome = AccountGroup::where('name', 'Operating Income')->first();
        $adminExpenses = AccountGroup::where('name', 'Administrative Expenses')->first();

        $accounts = [
            // Assets
            ['account_group_id' => $currentAssets->id, 'name' => 'Cash in Hand', 'code' => '1001', 'is_default' => true],
            ['account_group_id' => $currentAssets->id, 'name' => 'Bank Account', 'code' => '1002', 'is_default' => true],
            ['account_group_id' => $currentAssets->id, 'name' => 'Accounts Receivable - Students', 'code' => '1200', 'is_default' => true],

            // Income
            ['account_group_id' => $operatingIncome->id, 'name' => 'Fee Income', 'code' => '4000', 'is_default' => true],
            ['account_group_id' => $operatingIncome->id, 'name' => 'Discount Given', 'code' => '4050', 'is_default' => true], // Contra revenue
            ['account_group_id' => $operatingIncome->id, 'name' => 'Fines and Penalties', 'code' => '4100', 'is_default' => true],

            // Expenses
            ['account_group_id' => $adminExpenses->id, 'name' => 'Salary Expense', 'code' => '5000', 'is_default' => true],
            ['account_group_id' => $adminExpenses->id, 'name' => 'Utility Expense', 'code' => '5100', 'is_default' => true],
        ];

        foreach ($accounts as $account) {
            Account::firstOrCreate(['name' => $account['name']], $account);
        }
    }
}
