<?php

namespace App\Http\Controllers;

use App\Models\ApplicationDetails;
use App\Models\EarlySettlement;
use App\Utilities\AccruedInterests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChristmasLoan extends Controller
{

    public function show(Request $request)
    {
        $christmas_id = $request->route('christmas_loan');
        $application = \App\Models\EarlySettlement::select('early_settlement_form.*', 'christmas_loan.*')
        ->join('christmas_loan', 'christmas_loan.id', '=', 'early_settlement_form.christmas_detail_id')
        ->where('christmas_loan.id', $christmas_id)
        ->get();
       // $application = \App\Models\ChristmasLoan::with('earlySettlement')->where('id', $christmas_id)->first();
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
        return \App\Models\ChristmasLoan::all();
    }



    public function store(Request $request)
    {
        try {
            $ChristmasLoanData = Validator::make(
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

            if ($ChristmasLoanData->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $ChristmasLoanData->errors()
                ], 401);
            }
            ///return $request->all();
            $application = \App\Models\ApplicationTypes::where('application_type_slug', 'CHRISTMAS_APPLICATION_FORM')->first();
            $ChristmasLoan = \App\Models\ChristmasLoan::create([
                'application_id' => $application->id,
                'application_name' => 'CHRISTMAS_APPLICATION_FORM',
                'principal_amount' => $request->principal_amount,
                'principal_interest' => $request->principal_interest,
                'monthly_repayment_amount' => $request->monthly_repayment_amount,
                'number_of_months' => $request->number_of_months,
                'effective_date_of_payment' => $request->effective_date_of_payment,
                // 'phoneNumber' => $request->phoneNumber,
                // 'dob' => $request->dob,
                'w_f_no' => $request->user()->employee_code,
                'application_status' => 'IN-PROGRESS',
                'approval_status' => 'PENDING',
                'comment' => $request->comment,
                'loan_settlement_status' => 'NOT-COMPLETED',
                'total_loan_amount_payable' => $request->principal_interest,
                // 'settled_loan_amount' => $request->settled_loan_amount,
                'oustanding_loan_balance' => $request->principal_interest
            ]);



            if ($ChristmasLoan) {
                $applicationDetails = ApplicationDetails::create([
                    'user_id' => $request->user()->id,
                    'christmas_detail_id' => $ChristmasLoan->id,
                ]);

                if ($applicationDetails) {
                    //create a record for early settlement form
                    $earlySettleLoan = EarlySettlement::create([
                        'user_id' => $request->user()->id,
                        'christmas_detail_id' => $ChristmasLoan->id,
                        'type_of_loan_taken' => 'CHRISTMAS_APPLICATION_FORM'
                    ]);

                    if ($earlySettleLoan) {
                        return response()->json([
                            'status' => true,
                            'message' => 'Christmas Loan Application has been created successfully'
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
            $ChristmasData = Validator::make(
                $request->all(),
                [
                    'application_status' => 'required',
                    'approval_status' => 'required'
                ]
            );

            if ($ChristmasData->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $ChristmasData->errors()
                ], 401);
            }


            $christmas_id = $request->route('christmas_loan');
            $application_status = $request->application_status;
            $approval_status = $request->approval_status;
            $comment = $request->comment;
            $effective_date_of_payment = $request->effective_date_of_payment;


            $application = \App\Models\ChristmasLoan::where('id', $christmas_id)->first();

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
                        'message' => 'Christmas loan Application has been successfully closed'
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
