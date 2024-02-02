<?php

namespace App\Http\Controllers;

use App\Models\ApplicationDetails;
use App\Models\EarlySettlement;
use App\Models\SchoolFeesLoan as ModelsSchoolFeesLoan;
use App\Models\TotalLoansProfit;
use App\Utilities\AccruedInterests;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class SchoolFeesLoan extends Controller
{
    //
    public function show(Request $request)
    {
        $schoolfees_id = $request->route('school_fees_loan');
        $application = \App\Models\SchoolFeesLoan::with('earlySettlement')->where('id', $schoolfees_id)->first();
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

    public function store(Request $request)
    {
        try {
            $schoolFeesData = Validator::make(
                $request->all(),
                [
                    'principal_amount' => 'required',
                    'principal_interest' => 'required',
                    'monthly_repayment_amount' => 'required',
                    'number_of_months' => 'required',
                    // 'effective_date_of_payment' => 'required',
                    'name_of_ward' => 'required',
                    // 'class_level' => 'required',
                    // 'w_f_no' => 'required'
                ]
            );

            if ($schoolFeesData->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $schoolFeesData->errors()
                ], 401);
            }

            ///return $request->all();
            //search for application type id
            $application = \App\Models\ApplicationTypes::where('application_type_slug', 'SCHOOL_FEES_LOAN_APPLICATION')->first();

            $school_fees = \App\Models\SchoolFeesLoan::create([
                'application_id' => $application->id,
                'application_name' => 'SCHOOL_FEES_LOAN_APPLICATION',
                'principal_amount' => $request->principal_amount,
                'principal_interest' => $request->principal_interest,
                'monthly_repayment_amount' => $request->monthly_repayment_amount,
                'number_of_months' => $request->number_of_months,
                'effective_date_of_payment' => $request->effective_date_of_payment,
                'name_of_ward' => $request->name_of_ward,
                // 'class_level' => $request->class_level,
                'w_f_no' => $request->user()->employee_code,
                'application_status' => 'IN-PROGRESS',
                'approval_status' => 'PENDING',
                'comment' => $request->comment,
                'loan_settlement_status' => 'NOT-COMPLETED',
                'total_loan_amount_payable' => $request->principal_interest,
                // 'settled_loan_amount'=>$request->settled_loan_amount,
                'oustanding_loan_balance' => $request->principal_interest
            ]);



            if ($school_fees) {

                $applicationDetails = ApplicationDetails::create([
                    'user_id' => $request->user()->id,
                    'school_fees_detail_id' => $school_fees->id,
                ]);

                if ($applicationDetails) {
                    $earlySettlement = EarlySettlement::create([
                        'user_id' => $request->user()->id,
                        'school_fees_detail_id' => $school_fees->id,
                        'type_of_loan_taken' => 'SCHOOL_FEES_LOAN_APPLICATION'
                    ]);
                    if ($earlySettlement) {
                        return response()->json([
                            'status' => true,
                            'message' => 'School Fees Loan Application has been created successfully'
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
            $schoolfeesData = Validator::make(
                $request->all(),
                [
                    'application_status' => 'required',
                    'approval_status' => 'required'
                ]
            );

            if ($schoolfeesData->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $schoolfeesData->errors()
                ], 401);
            }


            $schoolfees_id = $request->route('school_fees_loan');
            $application_status = $request->application_status;
            $approval_status = $request->approval_status;
            $comment = $request->comment;
            $effective_date_of_payment = $request->effective_date_of_payment;


            $application = \App\Models\SchoolFeesLoan::where('id', $schoolfees_id)->first();
            // return $application;
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
                //compute all profit from loans

                if ($application->save()) {

                    $loans_from_profit = floatval($application->principal_interest) - floatval($application->principal_amount);
                    //accumulate loans from profit
                    AccruedInterests::TotalProfitLoans($loans_from_profit);
                    //if the loan application is approved let compute for the loans profit
                    // $loan_application = TotalLoansProfit::where('id',1)->first();
                    return response()->json([
                        'status' => true,
                        'message' => 'School Fees Application has been successfully closed'
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
