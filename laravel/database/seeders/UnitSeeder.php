<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitSeeder extends Seeder
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
                'unit_name' => 'Box',
                'unit_code' => 'BOX',
            ],
            [
                'unit_name' => 'Pieces',
                'unit_code' => 'PCS',
            ],
            [
                'unit_name' => 'Other',
                'unit_code' => 'OTHER'
            ]
        ];

        DB::table('units')->insert($dataToInsert);
    }
}
