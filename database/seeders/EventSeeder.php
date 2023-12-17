<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('events')->insert([
            'name' => '5km',
            'second_name' => null,
            'length' => '5000',
            'date_start' => '2023-11-01',
            'date_end' => '2023-12-31'
        ]);

        DB::table('events')->insert([
            'name' => '10km',
            'second_name' => null,
            'length' => '10000',
            'date_start' => '2023-11-01',
            'date_end' => '2023-12-31'
        ]);

        DB::table('events')->insert([
            'name' => '21km',
            'second_name' => 'půlmaraton',
            'length' => '21097',
            'date_start' => '2023-11-01',
            'date_end' => '2023-12-31'
        ]);


    }
}
