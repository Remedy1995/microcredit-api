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

        DB::table('users')->insert([
            [
                'firstname' => 'Appiah',
                'lastname' => 'Prosper',
                'occupation' => 'Software Developer',
                'address' => 'Kasoa Nyanyano',
                'phone' => '0244565656',
                'dob' => '12-12-1988',
                'email' => 'appiahprosper@maritime.com',
                'password' => Hash::make('12345678'),
                'role' => 'user',
                'granted_access'=>1,
                 'employee_code'=>'AA0034',
                'next_of_kin_name' => 'Not Applicable',
                'next_of_kin_phone' => 'Not Applicable',
                'monthly_amount_contribution' => 'Not Applicable',
                'effective_date_of_contribution'=>Carbon::now()
            ],
        ]);
        DB::table('users')->insert([
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
                'granted_access'=>1,
                 'employee_code'=>'Q1234568',
                'next_of_kin_name' => 'Not Applicable',
                'next_of_kin_phone' => 'Not Applicable',
                'monthly_amount_contribution' => 'Not Applicable',
                'effective_date_of_contribution'=>Carbon::now()
            ],
            // [
            //     'firstname' => 'Appiah',
            //     'lastname' => 'Prosper',
            //     'occupation' => 'Software Developer',
            //     'address' => 'Kasoa Nyanyano',
            //     'phone' => '0244565656',
            //     'dob' => '12-12-1988',
            //     'email' => 'appiahprosper@maritime.com',
            //     'password' => Hash::make('12345678'),
            //     'role' => 'user',
            //     'granted_access'=>1,
            //      'employee_code'=>'AA0034',
            //     'next_of_kin_name' => 'Not Applicable',
            //     'next_of_kin_phone' => 'Not Applicable',
            //     'monthly_amount_contribution' => 'Not Applicable',
            //     'effective_date_of_contribution'=>Carbon::now()
            // ],        )
            ]);
            DB::table('users')->insert([
            [
                'firstname' => 'Amissah Joseph',
                'lastname' => 'Prosper',
                'occupation' => 'Software Developer',
                'address' => 'Kasoa Nyanyano',
                'phone' => '0244565656',
                'dob' => '12-12-1988',
                'email' => 'amissahjoseph@maritime.com',
                'password' => Hash::make('12345678'),
                'role' => 'user',
                'granted_access'=>1,
                 'employee_code'=>'AA0046P',
                'next_of_kin_name' => 'Not Applicable',
                'next_of_kin_phone' => 'Not Applicable',
                'monthly_amount_contribution' => 'Not Applicable',
                'effective_date_of_contribution'=>Carbon::now()
            ],
        ]);
        DB::table('users')->insert([
            [
                'firstname' => 'Wilson Regina',
                'lastname' => 'Nartey',
                'occupation' => 'Software Developer',
                'address' => 'Kasoa Nyanyano',
                'phone' => '0244565656',
                'dob' => '12-12-1988',
                'email' => 'wilson-narteyjoseph@maritime.com',
                'password' => Hash::make('12345678'),
                'role' => 'user',
                'granted_access'=>1,
                 'employee_code'=>'AA0107',
                'next_of_kin_name' => 'Not Applicable',
                'next_of_kin_phone' => 'Not Applicable',
                'monthly_amount_contribution' => 'Not Applicable',
                'effective_date_of_contribution'=>Carbon::now()
            ],
        ]);

        DB::table('users')->insert([
            [
                'firstname' => 'Amanor',
                'lastname' => 'Jemima',
                'occupation' => 'Software Developer',
                'address' => 'Kasoa Nyanyano',
                'phone' => '0244565656',
                'dob' => '12-12-1988',
                'email' => 'amanorjemima@maritime.com',
                'password' => Hash::make('12345678'),
                'role' => 'user',
                'granted_access'=>1,
                 'employee_code'=>'AA0134',
                'next_of_kin_name' => 'Not Applicable',
                'next_of_kin_phone' => 'Not Applicable',
                'monthly_amount_contribution' => 'Not Applicable',
                'effective_date_of_contribution'=>Carbon::now()
            ],
        ]);
        DB::table('users')->insert([
            [
                'firstname' => 'Nsiah',
                'lastname' => 'Rita',
                'occupation' => 'Software Developer',
                'address' => 'Kasoa Nyanyano',
                'phone' => '0244565656',
                'dob' => '12-12-1988',
                'email' => 'nsiahrita@maritime.com',
                'password' => Hash::make('12345678'),
                'role' => 'user',
                'granted_access'=>1,
                 'employee_code'=>'AF0088',
                'next_of_kin_name' => 'Not Applicable',
                'next_of_kin_phone' => 'Not Applicable',
                'monthly_amount_contribution' => 'Not Applicable',
                'effective_date_of_contribution'=>Carbon::now()
            ],
        ]);
        DB::table('users')->insert([
            [
                'firstname' => 'Enyonam Anderson',
                'lastname' => 'Agbolosoo',
                'occupation' => 'Software Developer',
                'address' => 'Kasoa Nyanyano',
                'phone' => '0244565656',
                'dob' => '12-12-1988',
                'email' => 'enyonamagbolosooanderson@maritime.com',
                'password' => Hash::make('12345678'),
                'role' => 'user',
                'granted_access'=>1,
                 'employee_code'=>'AP0056',
                'next_of_kin_name' => 'Not Applicable',
                'next_of_kin_phone' => 'Not Applicable',
                'monthly_amount_contribution' => 'Not Applicable',
                'effective_date_of_contribution'=>Carbon::now()
            ],
        ]);
        DB::table('users')->insert([
            [
                'firstname' => 'Kpodo',
                'lastname' => 'Joseph',
                'occupation' => 'Software Developer',
                'address' => 'Kasoa Nyanyano',
                'phone' => '0244565656',
                'dob' => '12-12-1988',
                'email' => 'kpodojoseph@maritime.com',
                'password' => Hash::make('12345678'),
                'role' => 'user',
                'granted_access'=>1,
                 'employee_code'=>'TEE012',
                'next_of_kin_name' => 'Not Applicable',
                'next_of_kin_phone' => 'Not Applicable',
                'monthly_amount_contribution' => 'Not Applicable',
                'effective_date_of_contribution'=>Carbon::now()
            ],
        ]);
        DB::table('users')->insert([
            [
                'firstname' => 'Addo Kpakpo',
                'lastname' => 'Edgar Ni',
                'occupation' => 'Software Developer',
                'address' => 'Kasoa Nyanyano',
                'phone' => '0244565656',
                'dob' => '12-12-1988',
                'email' => 'addoedgarniikpakpo@maritime.com',
                'password' => Hash::make('12345678'),
                'role' => 'user',
                'granted_access'=>1,
                 'employee_code'=>'TME018',
                'next_of_kin_name' => 'Not Applicable',
                'next_of_kin_phone' => 'Not Applicable',
                'monthly_amount_contribution' => 'Not Applicable',
                'effective_date_of_contribution'=>Carbon::now()
            ],
        ]);
        DB::table('users')->insert([
            [
                'firstname' => 'Obeng',
                'lastname' => 'Adamu Christopher',
                'occupation' => 'Software Developer',
                'address' => 'Kasoa Nyanyano',
                'phone' => '0244565656',
                'dob' => '12-12-1988',
                'email' => 'obengadamuchristopher@maritime.com',
                'password' => Hash::make('12345678'),
                'role' => 'user',
                'granted_access'=>1,
                 'employee_code'=>'TME051',
                'next_of_kin_name' => 'Not Applicable',
                'next_of_kin_phone' => 'Not Applicable',
                'monthly_amount_contribution' => 'Not Applicable',
                'effective_date_of_contribution'=>Carbon::now()
            ],
        ]);
        DB::table('users')->insert([
            [
                'firstname' => 'Amanie Mark',
                'lastname' => 'Kwadwo',
                'occupation' => 'Software Developer',
                'address' => 'Kasoa Nyanyano',
                'phone' => '0244565656',
                'dob' => '12-12-1988',
                'email' => 'amaniemarkkwadwo@maritime.com',
                'password' => Hash::make('12345678'),
                'role' => 'user',
                'granted_access'=>1,
                 'employee_code'=>'TMEP02',
                'next_of_kin_name' => 'Not Applicable',
                'next_of_kin_phone' => 'Not Applicable',
                'monthly_amount_contribution' => 'Not Applicable',
                'effective_date_of_contribution'=>Carbon::now()
            ],
        ]);
        DB::table('users')->insert([
            [
                'firstname' => 'Joshua',
                'lastname' => 'Seckou Atsu',
                'occupation' => 'Software Developer',
                'address' => 'Kasoa Nyanyano',
                'phone' => '0244565656',
                'dob' => '12-12-1988',
                'email' => 'seckoujosephatsu@maritime.com',
                'password' => Hash::make('12345678'),
                'role' => 'user',
                'granted_access'=>1,
                 'employee_code'=>'TNS006',
                'next_of_kin_name' => 'Not Applicable',
                'next_of_kin_phone' => 'Not Applicable',
                'monthly_amount_contribution' => 'Not Applicable',
                'effective_date_of_contribution'=>Carbon::now()
            ],
        ]);      //
    }
}
