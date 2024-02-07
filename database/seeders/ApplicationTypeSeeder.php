<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApplicationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        DB::table('application_types')->insert(
            ['application_type_name'=>'SCHOOL FEES LOAN APPLICATION',
             'application_type_slug'=>'SCHOOL_FEES_LOAN_APPLICATION',
             'application_category'=>'LOANS'
        ]);


        DB::table('application_types')->insert(
            ['application_type_name'=>'CHANGE IN MEMBER CONTRIBUTION',
             'application_type_slug'=>'CHANGE_IN_MEMBER_CONTRIBUTION',
             'application_category'=>'CONTRIBUTIONS'
        ]);



        DB::table('application_types')->insert(
            ['application_type_name'=>'DEPOSIT FORM',
             'application_type_slug'=>'DEPOSIT_FORM',
             'application_category'=>'DEPOSITS'
        ]);


        DB::table('application_types')->insert(
            ['application_type_name'=>'ACCRUED BENEFIT APPLICATION FORM',
             'application_type_slug'=>'ACCRUED_BENEFIT_APPLICATION_FORM',
             'application_category'=>'ACCRUED BENEFITS'
        ]);


        DB::table('application_types')->insert(
            ['application_type_name'=>'LOAN APPLICATION FORM',
             'application_type_slug'=>'LOAN_APPLICATION_FORM',
             'application_category'=>'LOANS'
        ]);


        DB::table('application_types')->insert(
            ['application_type_name'=>'REFUND APPICATION FORM',
             'application_type_slug'=>'REFUND_APPLICATION_FORM',
             'application_category'=>'LOANS'
        ]);


        DB::table('application_types')->insert(
            ['application_type_name'=>'LOAN_APPLICATION',
             'application_type_slug'=>'LOAN_APPLICATION',
             'application_category'=>'LOANS'
        ]);

        DB::table('application_types')->insert(
            ['application_type_name'=>'EARLY SETTLEMENT FORM',
             'application_type_slug'=>'EARLY_SETTLEMENT_FORM',
             'application_category'=>'EARLY SETTLEMENT'
        ]);


        DB::table('application_types')->insert(
            ['application_type_name'=>'LOAN_APPLICATION',
             'application_type_slug'=>'LOAN_APPLICATION',
             'application_category'=>'LOANS'
        ]);


        DB::table('application_types')->insert(
            ['application_type_name'=>'HAPPY BIRTHDAY LOAN APPLICATION FORM',
             'application_type_slug'=>'HAPPY_BIRTHDAY_APPLICATION_FORM',
             'application_category'=>'OTHERS'
        ]);


        DB::table('application_types')->insert(
            ['application_type_name'=>'OTHERS',
             'application_type_slug'=>'OTHERS',
             'application_category'=>'OTHERS'
        ]);

        DB::table('application_types')->insert(
            ['application_type_name'=>'CAR LOANS',
             'application_type_slug'=>'CAR_LOANS',
             'application_category'=>'CAR LOANS'
        ]);


        DB::table('application_types')->insert(
            ['application_type_name'=>'FOUNDERS DAY LOANS',
             'application_type_slug'=>'FOUNDERS_DAY_APPLICATION_FORM',
             'application_category'=>'FOUNDERS DAY LOAN'
        ]);

        DB::table('application_types')->insert(
            ['application_type_name'=>'CHRISTMAS LOANS',
             'application_type_slug'=>'CHRISTMAS_APPLICATION_FORM',
             'application_category'=>'CHRISTMAS LOAN'
        ]);

        DB::table('application_types')->insert(
            ['application_type_name'=>'EASTER LOANS',
             'application_type_slug'=>'EASTER_APPLICATION_FORM',
             'application_category'=>'EASTER LOAN'
        ]);

        DB::table('application_types')->insert(
            ['application_type_name'=>'EMERGENCY LOANS',
             'application_type_slug'=>'EMERGENCY_APPLICATION_FORM',
             'application_category'=>'EMERGENCY LOAN'
        ]);
    }
}
