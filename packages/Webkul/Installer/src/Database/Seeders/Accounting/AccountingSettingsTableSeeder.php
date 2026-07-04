<?php

namespace Webkul\Installer\Database\Seeders\Accounting;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccountingSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($parameters = [])
    {
        DB::table('accounting_settings')->delete();

        $defaultMappings = [
            'accounts_receivable_account' => 4,
            'cash_bank_account'           => 3,
            'sales_revenue_account'       => 12,
            'shipping_revenue_account'    => 13,
            'sales_discount_account'      => 14,
            'sales_refund_account'        => 15,
            'tax_payable_account'         => 8,
        ];

        foreach ($defaultMappings as $name => $value) {
            DB::table('accounting_settings')->insert([
                'name'       => $name,
                'value'      => $value,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
