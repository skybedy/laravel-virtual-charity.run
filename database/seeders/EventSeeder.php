<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
            'distance' => '5000',
            'date_start' => '2023-01-01',
            'date_end' => '2024-02-29',
        ]);

        DB::table('events')->insert([
            'name' => '10km',
            'second_name' => null,
            'distance' => '10000',
            'date_start' => '2023-01-01',
            'date_end' => '2024-02-29',
        ]);

        DB::table('events')->insert([
            'name' => '21km',
            'second_name' => 'půlmaraton',
            'distance' => '21097',
            'date_start' => '2023-01-01',
            'date_end' => '2024-02-29',
        ]);

    }
}
