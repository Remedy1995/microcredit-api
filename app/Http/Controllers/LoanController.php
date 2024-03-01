<?php

namespace App\Http\Controllers;

use App\Models\ApplicationDetails;
use App\Models\EarlySettlement;
use App\Models\LoanApplication;
use App\Models\TotalLoansProfit;
use App\Utilities\AccruedInterests;
use App\Utilities\FormsGuard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoanController extends Controller
{

    public function show(Request $request)
    {

        $loanapplication_id = $request->route('loan');

        $application = LoanApplication::with('earlySettlement')->where('id', $loanapplication_id)->first();
        $application = \App\Models\EarlySettlement::select('early_settlement_form.*', 'loan_application.*')
        ->join('loan_application', 'loan_application.id', '=', 'early_settlement_form.loan_detail_id')
        ->where('loan_application.id', $loanapplication_id)
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
    public function index()
    {
        return LoanApplication::all();
    }

    public function store(Request $request)
    { {


            try {
                $LoanData = Validator::make(
                    $request->all(),
                    [
                        'principal_amount' => 'required',
                        'principal_interest' => 'required',
                        'monthly_repayment_amount' => 'required',
                        'number_of_months' => 'required',
                        'loan_term' => 'required'
                    ]
                );

                if ($LoanData->fails()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'validation error',
                        'errors' => $LoanData->errors()
                    ], 401);
                }


                $checkApplicationStatus = FormsGuard::CheckExistingApplicationInProgress($request->user()->id,'LOAN_APPLICATION_FORM');

                if($checkApplicationStatus){
                    return response()->json([
                        'status'=> false,
                        'message'=>'Sorry you cannot create another form you already have a pending loan form in progress'
                    ],400);
                }
                ///return $request->all();
                $application = \App\Models\ApplicationTypes::where('application_type_slug', 'LOAN_APPLICATION_FORM')->first();
                $LoanApplication = LoanApplication::create([
                    'application_id' => $application->id,
                    'application_name' => 'LOAN_APPLICATION_FORM',
                    'principal_amount' => $request->principal_amount,
                    'principal_interest' => $request->principal_interest,
                    'monthly_repayment_amount' => $request->monthly_repayment_amount,
                    'number_of_months' => $request->number_of_months,
                    'effective_date_of_payment' => $request->effective_date_of_payment,
                    'w_f_no' => $request->user()->employee_code,
                    'loan_term' => $request->loan_term,
                    'application_status' => 'IN-PROGRESS',
                    'loan_approval_status' => 'PENDING',
                    'comment' => $request->comment,
                    'loan_settlement_status' => 'NOT-COMPLETED',
                    'total_loan_amount_payable' => $request->principal_interest,
                    // 'settled_loan_amount' => $request->settled_loan_amount,
                    'oustanding_loan_balance' => $request->principal_interest
                ]);



                if ($LoanApplication) {
                    $applicationDetails = ApplicationDetails::create([
                        'user_id' => $request->user()->id,
                        'loan_detail_id' => $LoanApplication->id,
                    ]);

                    if ($applicationDetails) {

                        $earlySettleLoan = EarlySettlement::create([
                            'user_id' => $request->user()->id,
                            'loan_detail_id' => $LoanApplication->id,
                            'type_of_loan_taken' => 'LOAN_APPLICATION_FORM'
                        ]);

                        if ($earlySettleLoan) {
                            return response()->json([
                                'status' => true,
                                'message' => 'Loan Application has been created successfully'
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
    }




    public function update(Request $request)
    {
        try {

            $LoanData = Validator::make(
                $request->all(),
                [
                    'application_status' => 'required',
                    'loan_approval_status' => 'required'
                ]
            );

            if ($LoanData->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $LoanData->errors()
                ], 401);
            }


            $loan_id = $request->route('loan');
            $loan_application_status = $request->application_status;
            $loan_approval_status = $request->loan_approval_status;
            $loan_comment = $request->comment;
            $effective_date_of_payment = $request->effective_date_of_payment;


            $application = LoanApplication::where('id', $loan_id)->first();

            if (!$application) {
                return response()->json([
                    'status' => false,
                    'message' => 'Application id not found'
                ], 422);
            } else {
                $application->application_status = $loan_application_status;
                $application->loan_approval_status = $loan_approval_status;
                $application->comment = $loan_comment;
                $application->effective_date_of_payment = $effective_date_of_payment;
                if ($application->save()) {
                    $loans_from_profit = floatval($application->principal_interest) - floatval($application->principal_amount);
                    //accumulate total loans from profit
                    AccruedInterests::TotalProfitLoans($loans_from_profit);
                    return response()->json([
                        'status' => true,
                        'message' => 'Loan Application has been successfully closed'
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
