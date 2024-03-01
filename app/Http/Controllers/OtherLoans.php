<?php

namespace App\Http\Controllers;

use App\Models\ApplicationDetails;
use App\Models\EarlySettlement;
use App\Utilities\AccruedInterests;
use App\Utilities\FormsGuard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class OtherLoans extends Controller
{
    //
    public function show(Request $request)
    {

        $otherLoanid = $request->route('other_loan');

       // $application = \App\Models\CarLoans::with('earlySettlement')->where('id', $carLoanid)->first();
       $application = \App\Models\EarlySettlement::select('early_settlement_form.*', 'other_loans.*')
       ->join('other_loans', 'other_loans.id', '=', 'early_settlement_form.other_detail_id')
       ->where('other_loans.id', $otherLoanid)
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
        return \App\Models\OtherLoans::all();
    }



    public function store(Request $request)
    {


        try {
            $otherLoanData = Validator::make(
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

            if ($otherLoanData->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $otherLoanData->errors()
                ], 401);
            }

            $checkApplicationStatus = FormsGuard::CheckExistingApplicationInProgress($request->user()->id,'OTHER_APPLICATION_FORM');

            if($checkApplicationStatus){
                return response()->json([
                    'status'=> false,
                    'message'=>'Sorry you cannot create another form you already have a pending loan form in progress'
                ],400);
            }
            ///return $request->all();
            $application = \App\Models\ApplicationTypes::where('application_type_slug', 'OTHER_APPLICATION_FORM')->first();
            $OtherLoan = \App\Models\OtherLoans::create([
                'application_id' => $application->id,
                'application_name' => 'OTHER_APPLICATION_FORM',
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



            if ($OtherLoan) {
                $applicationDetails = ApplicationDetails::create([
                    'user_id' => $request->user()->id,
                    'other_detail_id' => $OtherLoan->id,
                ]);

                if ($applicationDetails) {
                    //create a record for early settlement form
                    $earlySettleLoan = EarlySettlement::create([
                        'user_id' => $request->user()->id,
                        'other_detail_id' => $OtherLoan->id,
                        'type_of_loan_taken' => 'OTHER_APPLICATION_FORM'
                    ]);

                    if ($earlySettleLoan) {
                        return response()->json([
                            'status' => true,
                            'message' => 'Other Loan Application has been created successfully'
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
            $OtherLoanData = Validator::make(
                $request->all(),
                [
                    'application_status' => 'required',
                    'approval_status' => 'required'
                ]
            );

            if ($OtherLoanData->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $OtherLoanData->errors()
                ], 401);
            }


            $otherLoan_id = $request->route('other_loan');
            $application_status = $request->application_status;
            $approval_status = $request->approval_status;
            $comment = $request->comment;
            $effective_date_of_payment = $request->effective_date_of_payment;


            $application = \App\Models\OtherLoans::where('id', $otherLoan_id)->first();

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
                        'message' => 'Other Loan Application has been successfully closed'
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
