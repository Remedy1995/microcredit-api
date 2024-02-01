<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {

        DB::table('users')->insert(
            [
                'firstname' => 'Japhet',
                'lastname' => 'Adjetey',
                'occupation' => 'Software Developer',
                'address' => 'Kasoa Nyanyano',
                'phone' => '00543661399',
                'dob' => '12-12-1988',
                'email' => 'admin@maritime.com',
                'password' => Hash::make('12345678'),
                'role' => 'Admin',
                 'employee_code'=>'Q1234568',
                'next_of_kin_name' => 'Not Applicable',
                'next_of_kin_phone' => 'Not Applicable',
                'monthly_amount_contribution' => 'Not Applicable',
                'effective_date_of_contribution'=>Carbon::now()
            ]

        );

        //
    }
}
