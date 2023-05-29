<?php

use App\Models\GeneralSetting;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // seed basic data to table
        $settingElemetns = [
            'company_name' => 'Productify',
            'compnay_tagline' => 'Production Management System',
            'email_address' => 'support@codeshape.net',
            'phone_number' => '0170000000',
            'address' => 'Mirpur-10, Dhaka-1216, Bangladesh',
            'currency_name' => 'USD',
            'currency_symbol' => '$',
            'currency_position' => 'left',
            'timezone' => 'America/New_York',
            'purchase_code_prefix' => 'PUR-',
            'processing_code_prefix' => 'PRO-',
            'finished_code_prefix' => 'FIN-',
            'transferred_code_prefix' => 'TRA-',
            'starting_purchase_code' => '1',
            'logo' => 'dark-logo.png',
            'small_logo' => 'small-dark-logo.png',
            'dark_logo' => 'white-logo.png',
            'small_dark_logo' => 'white-small-logo.png',
            'favicon' => 'favicon.ico',
            'copyright' => 'Copyright © 2020 Productify All rights reserved'
        ];
        foreach ($settingElemetns as $key => $value) {
            GeneralSetting::create([
                'key' => $key,
                'display_name' => ucwords(str_replace("_", " ", $key)),
                'value' => $value
            ]);
        }
    }
}