<?php

namespace Webkul\Installer\Database\Seeders\Accounting;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Webkul\Accounting\Models\Account;

class AccountingAccountsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($parameters = [])
    {
        DB::table('accounting_accounts')->delete();

        $defaultAccounts = [
            ['id' => 1, 'parent_id' => null, 'code' => '1000', 'name' => 'Cash and Cash Equivalents', 'type' => Account::TYPE_ASSET, 'is_system' => 0],
            ['id' => 2, 'parent_id' => 1, 'code' => '1010', 'name' => 'Cash on Hand', 'type' => Account::TYPE_ASSET, 'is_system' => 0],
            ['id' => 3, 'parent_id' => 1, 'code' => '1020', 'name' => 'Bank Accounts', 'type' => Account::TYPE_ASSET, 'is_system' => 1],
            ['id' => 4, 'parent_id' => null, 'code' => '1100', 'name' => 'Accounts Receivable', 'type' => Account::TYPE_ASSET, 'is_system' => 1],
            ['id' => 5, 'parent_id' => null, 'code' => '1200', 'name' => 'Inventory', 'type' => Account::TYPE_ASSET, 'is_system' => 0],
            ['id' => 6, 'parent_id' => null, 'code' => '1500', 'name' => 'Fixed Assets', 'type' => Account::TYPE_ASSET, 'is_system' => 0],

            ['id' => 7, 'parent_id' => null, 'code' => '2000', 'name' => 'Accounts Payable', 'type' => Account::TYPE_LIABILITY, 'is_system' => 0],
            ['id' => 8, 'parent_id' => null, 'code' => '2100', 'name' => 'Tax Payable', 'type' => Account::TYPE_LIABILITY, 'is_system' => 1],
            ['id' => 9, 'parent_id' => null, 'code' => '2200', 'name' => 'Accrued Liabilities', 'type' => Account::TYPE_LIABILITY, 'is_system' => 0],

            ['id' => 10, 'parent_id' => null, 'code' => '3000', 'name' => "Owner's Equity", 'type' => Account::TYPE_EQUITY, 'is_system' => 0],
            ['id' => 11, 'parent_id' => null, 'code' => '3100', 'name' => 'Retained Earnings', 'type' => Account::TYPE_EQUITY, 'is_system' => 0],

            ['id' => 12, 'parent_id' => null, 'code' => '4000', 'name' => 'Sales Revenue', 'type' => Account::TYPE_REVENUE, 'is_system' => 1],
            ['id' => 13, 'parent_id' => null, 'code' => '4100', 'name' => 'Shipping Revenue', 'type' => Account::TYPE_REVENUE, 'is_system' => 1],
            ['id' => 14, 'parent_id' => null, 'code' => '4900', 'name' => 'Sales Discounts', 'type' => Account::TYPE_REVENUE, 'is_system' => 1],
            ['id' => 15, 'parent_id' => null, 'code' => '4910', 'name' => 'Sales Refunds', 'type' => Account::TYPE_REVENUE, 'is_system' => 1],

            ['id' => 16, 'parent_id' => null, 'code' => '5000', 'name' => 'Cost of Goods Sold', 'type' => Account::TYPE_EXPENSE, 'is_system' => 0],
            ['id' => 17, 'parent_id' => null, 'code' => '5100', 'name' => 'Shipping Expense', 'type' => Account::TYPE_EXPENSE, 'is_system' => 0],
            ['id' => 18, 'parent_id' => null, 'code' => '5200', 'name' => 'Operating Expenses', 'type' => Account::TYPE_EXPENSE, 'is_system' => 0],
            ['id' => 19, 'parent_id' => 18, 'code' => '5210', 'name' => 'Salaries and Wages', 'type' => Account::TYPE_EXPENSE, 'is_system' => 0],
            ['id' => 20, 'parent_id' => 18, 'code' => '5220', 'name' => 'Rent Expense', 'type' => Account::TYPE_EXPENSE, 'is_system' => 0],
            ['id' => 21, 'parent_id' => 18, 'code' => '5230', 'name' => 'Marketing and Advertising', 'type' => Account::TYPE_EXPENSE, 'is_system' => 0],
            ['id' => 22, 'parent_id' => null, 'code' => '5900', 'name' => 'Bank and Payment Gateway Fees', 'type' => Account::TYPE_EXPENSE, 'is_system' => 0],
        ];

        foreach ($defaultAccounts as &$account) {
            $account['opening_balance'] = 0;
            $account['is_active'] = 1;
            $account['created_at'] = now();
            $account['updated_at'] = now();
        }

        DB::table('accounting_accounts')->insert($defaultAccounts);
    }
}
