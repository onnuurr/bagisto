<?php

namespace Webkul\Installer\Database\Seeders\Accounting;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run($parameters = [])
    {
        $this->call(AccountingAccountsTableSeeder::class, false, ['parameters' => $parameters]);

        $this->call(AccountingSettingsTableSeeder::class, false, ['parameters' => $parameters]);
    }
}
