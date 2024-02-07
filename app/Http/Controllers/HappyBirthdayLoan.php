<?php

namespace App\Http\Controllers;

use App\Models\ApplicationDetails;
use App\Models\EarlySettlement;
use App\Models\TotalLoansProfit;
use App\Utilities\AccruedInterests;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;

class HappyBirthdayLoan extends Controller
{

    public function show(Request $request)
    {

        $happybirthday_id = $request->route('happy_birthday_loan');

       // $application = \App\Models\HappyBirthdayLoan::with('earlySettlement')->where('id', $happybirthday_id)->first();

       $application = \App\Models\EarlySettlement::select('early_settlement_form.*', 'happy_birthday_loan.*')
       ->join('happy_birthday_loan', 'happy_birthday_loan.id', '=', 'early_settlement_form.happy_birthday_detail_id')
       ->where('happy_birthday_loan.id', $happybirthday_id)
       ->get();
       if (!$application) {
            return response()->json([
                'status' => false,
                'data' => [],
                'message' => 'No data found'
            ], 404);
        }
        return response()->json([
            'status' => true,
            'data' => $application,
            'message' => 'Successful'
        ], 200);
    }
    //
    public function index(Request $request)
    {
        return \App\Models\HappyBirthdayLoan::all();
    }



    public function store(Request $request)
    {


        try {
            $HappyBirthdayData = Validator::make(
                $request->all(),
                [
                    'principal_amount' => 'required',
                    'principal_interest' => 'required',
                    'monthly_repayment_amount' => 'required',
                    'number_of_months' => 'required',
                    // 'effective_date_of_payment' => 'required',
                    // 'phoneNumber' => 'required',
                    // 'dob' => 'required',
                    // 'w_f_no' => 'required'
                ]
            );

            if ($HappyBirthdayData->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $HappyBirthdayData->errors()
                ], 401);
            }

            ///return $request->all();
            $application = \App\Models\ApplicationTypes::where('application_type_slug', 'HAPPY_BIRTHDAY_APPLICATION_FORM')->first();
            $happybirthdayLoan = \App\Models\HappyBirthdayLoan::create([
                'application_id' => $application->id,
                'application_name' => 'HAPPY_BIRTHDAY_APPLICATION_FORM',
                'principal_amount' => $request->principal_amount,
                'principal_interest' => $request->principal_interest,
                'monthly_repayment_amount' => $request->monthly_repayment_amount,
                'number_of_months' => $request->number_of_months,
                'effective_date_of_payment' => $request->effective_date_of_payment,
                // 'phoneNumber' => $request->phoneNumber,
                // 'dob' => $request->dob,
               'w_f_no' => $request->user()->employee_code,
               //'w_f_no' => 'TNS006',
                'application_status' => 'IN-PROGRESS',
                'approval_status' => 'PENDING',
                'comment' => $request->comment,
                'loan_settlement_status' => 'NOT-COMPLETED',
                'total_loan_amount_payable' => $request->principal_interest,
                // 'settled_loan_amount' => $request->settled_loan_amount,
                'oustanding_loan_balance' => $request->principal_interest
            ]);



            if ($happybirthdayLoan) {
                $applicationDetails = ApplicationDetails::create([
                    'user_id' => $request->user()->id,
                    'happy_birthday_detail_id' => $happybirthdayLoan->id,
                ]);

                if ($applicationDetails) {
                    //create a record for early settlement form
                    $earlySettleLoan = EarlySettlement::create([
                        'user_id' => $request->user()->id,
                        'happy_birthday_detail_id' => $happybirthdayLoan->id,
                        'type_of_loan_taken' => 'HAPPY_BIRTHDAY_APPLICATION_FORM'
                    ]);

                    if ($earlySettleLoan) {
                        return response()->json([
                            'status' => true,
                            'message' => 'Happy Birthday Loan Application has been created successfully'
                        ], 201);
                    }
                }
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }




    public function update(Request $request)
    {
        try {
            $HappyBirthDayData = Validator::make(
                $request->all(),
                [
                    'application_status' => 'required',
                    'approval_status' => 'required'
                ]
            );

            if ($HappyBirthDayData->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $HappyBirthDayData->errors()
                ], 401);
            }


            $happybirthday_id = $request->route('happy_birthday_loan');
            $application_status = $request->application_status;
            $approval_status = $request->approval_status;
            $comment = $request->comment;
            $effective_date_of_payment = $request->effective_date_of_payment;


            $application = \App\Models\HappyBirthdayLoan::where('id', $happybirthday_id)->first();

            if (!$application) {
                return response()->json([
                    'status' => false,
                    'message' => 'Application id not found'
                ], 422);
            } else {
                $application->application_status = $application_status;
                $application->approval_status = $approval_status;
                $application->comment = $comment;
                $application->effective_date_of_payment = $effective_date_of_payment;

                if ($application->save()) {
                    $loans_from_profit = floatval($application->principal_interest) - floatval($application->principal_amount);
                    //accumulate total loans from profit
                    AccruedInterests::TotalProfitLoans($loans_from_profit);

                    return response()->json([
                        'status' => true,
                        'message' => 'Happy Birthday Application has been successfully closed'
                    ], 200);
                }
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
