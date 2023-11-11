<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlatformSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dataToInsert = [
            [
                'platform_name' => 'Website',
                'platform_code' => 'WEBSITE',
            ],
            [
                'platform_name' => 'Shopee',
                'platform_code' => 'SHOPEE',
            ],
            [
                'platform_name' => 'Tokopedia',
                'platform_code' => 'TOKOPEDIA',
            ],
            [
                'platform_name' => 'Lazada',
                'platform_code' => 'LAZADA',
            ],
            [
                'platform_name' => 'Blibli',
                'platform_code' => 'BLIBLI',
            ],
            [
                'platform_name' => 'Other',
                'platform_code' => 'OTHER',
            ],
        ];

        DB::table('platforms')->insert($dataToInsert);
    }
}
