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
            ['id' => 1, 'parent_id' => null, 'code' => '1000', 'name' => 'Nakit ve Nakit Benzerleri', 'type' => Account::TYPE_ASSET, 'is_system' => 0],
            ['id' => 2, 'parent_id' => 1, 'code' => '1010', 'name' => 'Kasa', 'type' => Account::TYPE_ASSET, 'is_system' => 0],
            ['id' => 3, 'parent_id' => 1, 'code' => '1020', 'name' => 'Banka Hesapları', 'type' => Account::TYPE_ASSET, 'is_system' => 1],
            ['id' => 4, 'parent_id' => null, 'code' => '1100', 'name' => 'Alacak Hesapları', 'type' => Account::TYPE_ASSET, 'is_system' => 1],
            ['id' => 5, 'parent_id' => null, 'code' => '1200', 'name' => 'Stoklar', 'type' => Account::TYPE_ASSET, 'is_system' => 0],
            ['id' => 6, 'parent_id' => null, 'code' => '1500', 'name' => 'Duran Varlıklar', 'type' => Account::TYPE_ASSET, 'is_system' => 0],

            ['id' => 7, 'parent_id' => null, 'code' => '2000', 'name' => 'Ticari Borçlar', 'type' => Account::TYPE_LIABILITY, 'is_system' => 0],
            ['id' => 8, 'parent_id' => null, 'code' => '2100', 'name' => 'Ödenecek Vergiler', 'type' => Account::TYPE_LIABILITY, 'is_system' => 1],
            ['id' => 9, 'parent_id' => null, 'code' => '2200', 'name' => 'Gider Tahakkukları', 'type' => Account::TYPE_LIABILITY, 'is_system' => 0],

            ['id' => 10, 'parent_id' => null, 'code' => '3000', 'name' => 'Ödenmiş Sermaye', 'type' => Account::TYPE_EQUITY, 'is_system' => 0],
            ['id' => 11, 'parent_id' => null, 'code' => '3100', 'name' => 'Geçmiş Yıl Kârları', 'type' => Account::TYPE_EQUITY, 'is_system' => 0],

            ['id' => 12, 'parent_id' => null, 'code' => '4000', 'name' => 'Satış Gelirleri', 'type' => Account::TYPE_REVENUE, 'is_system' => 1],
            ['id' => 13, 'parent_id' => null, 'code' => '4100', 'name' => 'Kargo Gelirleri', 'type' => Account::TYPE_REVENUE, 'is_system' => 1],
            ['id' => 14, 'parent_id' => null, 'code' => '4900', 'name' => 'Satış İskontoları', 'type' => Account::TYPE_REVENUE, 'is_system' => 1],
            ['id' => 15, 'parent_id' => null, 'code' => '4910', 'name' => 'Satış İadeleri', 'type' => Account::TYPE_REVENUE, 'is_system' => 1],

            ['id' => 16, 'parent_id' => null, 'code' => '5000', 'name' => 'Satılan Malın Maliyeti', 'type' => Account::TYPE_EXPENSE, 'is_system' => 0],
            ['id' => 17, 'parent_id' => null, 'code' => '5100', 'name' => 'Kargo Giderleri', 'type' => Account::TYPE_EXPENSE, 'is_system' => 0],
            ['id' => 18, 'parent_id' => null, 'code' => '5200', 'name' => 'Faaliyet Giderleri', 'type' => Account::TYPE_EXPENSE, 'is_system' => 0],
            ['id' => 19, 'parent_id' => 18, 'code' => '5210', 'name' => 'Maaş ve Ücretler', 'type' => Account::TYPE_EXPENSE, 'is_system' => 0],
            ['id' => 20, 'parent_id' => 18, 'code' => '5220', 'name' => 'Kira Gideri', 'type' => Account::TYPE_EXPENSE, 'is_system' => 0],
            ['id' => 21, 'parent_id' => 18, 'code' => '5230', 'name' => 'Pazarlama ve Reklam Giderleri', 'type' => Account::TYPE_EXPENSE, 'is_system' => 0],
            ['id' => 22, 'parent_id' => null, 'code' => '5900', 'name' => 'Banka ve Ödeme Sistemi Komisyonları', 'type' => Account::TYPE_EXPENSE, 'is_system' => 0],
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
