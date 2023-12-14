<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        DB::table('categories')->insert(
            [
                [
                    'name' => 'Z18-24',
                    'order' => '1',
                    'gender' => 'F',
                    'age_start' => '18',
                    'age_end' => '24'
                ],
                [
                    'name' => 'Z25-29',
                    'order' => '2',
                    'gender' => 'F',
                    'age_start' => '25',
                    'age_end' => '29'
                ],     
                [
                    'name' => 'Z30-34',
                    'order' => '3',
                    'gender' => 'F',
                    'age_start' => '30',
                    'age_end' => '34'
                ],
                [
                    'name' => 'Z35-39',
                    'order' => '4',
                    'gender' => 'F',
                    'age_start' => '35',
                    'age_end' => '39'
                ],
                [
                    'name' => 'Z40-44',
                    'order' => '5',
                    'gender' => 'F',
                    'age_start' => '40',
                    'age_end' => '44'
                ],
                [
                    'name' => 'Z45-49',
                    'order' => '6',
                    'gender' => 'F',
                    'age_start' => '45',
                    'age_end' => '49'
                ],
                [
                    'name' => 'Z50-54',
                    'order' => '7',
                    'gender' => 'F',
                    'age_start' => '50',
                    'age_end' => '54'
                ],
                [
                    'name' => 'Z55-59',
                    'order' => '8',
                    'gender' => 'F',
                    'age_start' => '55',
                    'age_end' => '59'
                ],
                [
                    'name' => 'Z50-54',
                    'order' => '9',
                    'gender' => 'F',
                    'age_start' => '50',
                    'age_end' => '54'
                ],
                [
                    'name' => 'Z55-59',
                    'order' => '10',
                    'gender' => 'F',
                    'age_start' => '55',
                    'age_end' => '59'
                ],
                [
                    'name' => 'Z60-64',
                    'order' => '11',
                    'gender' => 'F',
                    'age_start' => '60',
                    'age_end' => '64'
                ],
                [
                    'name' => 'Z65-69',
                    'order' => '12',
                    'gender' => 'F',
                    'age_start' => '65',
                    'age_end' => '69'
                ],
                [
                    'name' => 'Z70+',
                    'order' => '13',
                    'gender' => 'F',
                    'age_start' => '70',
                    'age_end' => '99'
                ],
                [
                    'name' => 'M18-24',
                    'order' => '21',
                    'gender' => 'M',
                    'age_start' => '18',
                    'age_end' => '24'
                ],
                [
                    'name' => 'M25-29',
                    'order' => '22',
                    'gender' => 'M',
                    'age_start' => '25',
                    'age_end' => '29'
                ],     
                [
                    'name' => 'M30-34',
                    'order' => '23',
                    'gender' => 'M',
                    'age_start' => '30',
                    'age_end' => '34'
                ],
                [
                    'name' => 'M35-39',
                    'order' => '24',
                    'gender' => 'M',
                    'age_start' => '35',
                    'age_end' => '39'
                ],
                [
                    'name' => 'M40-44',
                    'order' => '25',
                    'gender' => 'M',
                    'age_start' => '40',
                    'age_end' => '44'
                ],
                [
                    'name' => 'M45-49',
                    'order' => '26',
                    'gender' => 'M',
                    'age_start' => '45',
                    'age_end' => '49'
                ],
                [
                    'name' => 'M50-54',
                    'order' => '27',
                    'gender' => 'M',
                    'age_start' => '50',
                    'age_end' => '54'
                ],
                [
                    'name' => 'M55-59',
                    'order' => '28',
                    'gender' => 'M',
                    'age_start' => '55',
                    'age_end' => '59'
                ],
                [
                    'name' => 'M50-54',
                    'order' => '29',
                    'gender' => 'M',
                    'age_start' => '50',
                    'age_end' => '54'
                ],
                [
                    'name' => 'M55-59',
                    'order' => '30',
                    'gender' => 'M',
                    'age_start' => '55',
                    'age_end' => '59'
                ],
                [
                    'name' => 'M60-64',
                    'order' => '31',
                    'gender' => 'M',
                    'age_start' => '60',
                    'age_end' => '64'
                ],
                [
                    'name' => 'M65-69',
                    'order' => '32',
                    'gender' => 'M',
                    'age_start' => '65',
                    'age_end' => '69'
                ],
                [
                    'name' => 'M70+',
                    'order' => '33',
                    'gender' => 'M',
                    'age_start' => '70',
                    'age_end' => '99'
                ],

            ]
        );
    
    }
}
