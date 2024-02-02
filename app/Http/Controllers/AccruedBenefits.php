<?php

namespace App\Http\Controllers;

use App\Models\RequestAccruedBenefits;
use App\Models\TotalAccruedBenefits;
use App\Models\TotalCumulativeSavings;
use App\Models\TotalLoansProfit;
use App\Utilities\AccruedInterests;
use App\Utilities\RecordTransactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AccruedBenefits extends Controller
{
    //

    public function showAccruedBenefitsWithdrawalById(Request $request)
    {
        $request_accrued_benefits_id = $request->route('id');
        $application = RequestAccruedBenefits::where('id', $request_accrued_benefits_id)->first();

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

    public function getUserAccruedBenefits(Request $request)
    {
        $employee_code = trim($request->employee_code);
        try {
            $benefits = TotalAccruedBenefits::where('employee_code', $employee_code)->first();

            if (!$benefits) {
                return response()->json(
                    [
                        'status' => false,
                        'data' => 'Sorry your employee code does not exist',
                    ],
                    200
                );
            } else {

                //make further computations the interest shared and the interest capitalised by the employee
                //$interestShared = AccruedInterests::InterestShared()


                return response()->json([
                    'status' => true,
                    'data' => $benefits,
                    'message' => 'Successful'
                ], 200);
            }
        } catch (\Exception $error) {
            return response()->json([
                'status' => false,
                'error' => $error->getMessage()
            ]);
        }
    }




    public function CalculateUserAccruedBenefit(Request $request)
    {

        try {
            //get current total loans from profit within the specific month
            $formatCurrentDate = RecordTransactions::FormatDate();
            $TotalProfitLoans = TotalLoansProfit::whereYear('created_at', '=', $formatCurrentDate[0])
                ->whereMonth('created_at', '=', $formatCurrentDate[1])->first();
            $loansProfit =  $TotalProfitLoans === null ? 0 :  floatval($TotalProfitLoans->total_loans_profit);
            //get total individual savings
            $total_individual_savings = TotalAccruedBenefits::where('employee_code', $request->user()->employee_code)->first();
            $individual_savings = $total_individual_savings == null ? 0 : floatval($total_individual_savings->total_accrued_benefits_amount);
            //total_contributions
            $all_total_contributions = TotalCumulativeSavings::where('id', 1)->first();
            $total_contributions =  $all_total_contributions == null ? 0 : floatval($all_total_contributions->total_cumulative_savings);
            //interest calculated
            $calculate_interests = AccruedInterests::CalculateInterest($loansProfit, $individual_savings, $total_contributions);
            //return $calculate_interests;
            //total accrued benefits = interests + total individual savings
            $total_accrued_benefits = $calculate_interests + $individual_savings;
            //return $total_accrued_benefits;
            return response()->json([
                'status' => true,
                'data' => round($total_accrued_benefits,2),
                'message' => 'Successful'
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => false,
                'error' => $error->getMessage()
            ]);
        }
    }


    public function RequestAccruedBenefitsWithdrawal(Request $request)
    {
        try {
            $AccruedBenefitsData = Validator::make(
                $request->all(),
                [
                    'employee_code',
                    'amount_to_withdraw',
                    'amount_in_words'
                ]
            );

            if ($AccruedBenefitsData->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $AccruedBenefitsData->errors()
                ], 401);
            }
            //the user can only make a request to withdraw accrued benefits based on the following conditions
            //1.If the user has enough accumulated funds to withdraw
            //2.If the user has no pending requests to withdraw accrued benefits meaning if application status is not in progress


            //check whether employee has enough funds and can withdraw a requested fund
            $checkFunds = TotalAccruedBenefits::where('employee_code', $request->user()->employee_code)->first();

            if (!$checkFunds) {
                return response()->json([
                    'status' => false,
                    'message' => 'Sorry you have no funds to withdraw at the moment'
                ], 422);
            }
            if ($checkFunds->total_accrued_benefits_amount < $request->amount_to_withdraw) {
                return response()->json([
                    'status' => false,
                    'message' => 'Sorry you have insufficient accrued benefits to withdraw you cannot withdraw requested amount'
                ], 422);
            }

            //check if employer has a pending request to withdraw funds
            $checkEligibility = RequestAccruedBenefits::where(['application_status' => 'IN-PROGRESS', 'user_id' => $request->user()->id])->first();
            if ($checkEligibility) {
                return response()->json([
                    'status' => false,
                    'message' => 'Sorry you already have an outstanding request to withdraw your accrued benefits. Wait for the process to be completed'
                ], 422);
            }


            $requestAccruedBenefits = RequestAccruedBenefits::create([
                'employee_code' => $request->user()->employee_code,
                'amount_to_withdraw' => $request->amount_to_withdraw,
                'amount_in_words' => $request->amount_in_words,
                'application_status' => 'IN-PROGRESS',
                'approval_status' => 'PENDING',
                'user_id' => $request->user()->id,
                'comment' => $request->comment
            ]);

            if ($requestAccruedBenefits) {
                return response()->json([
                    'status' => true,
                    'message' => 'You have successfully requested to withdraw your accrued benefits'
                ], 201);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }


    public function GetAllPendingAccruedBenefitWithdrawals()
    {
        try {
            $applications = RequestAccruedBenefits::with('user')->get();

            $filterApplications = $applications->filter(function ($query) {
                return $query->approval_status !== "COMPLETED";
            });
            return response()->json($filterApplications->values()->all(), 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => false,
                'message' => $error->getMessage()
            ], 500);
        }
    }


    public function ApproveRequestForAccruedBenefitsWithdrawal(Request $request)
    {

        try {
            $RequestAccruedBenefitsWithdrawal = Validator::make(
                $request->all(),
                [
                    'application_status' => 'required',
                    'approval_status' => 'required',
                    'employee_code' => 'required',
                    'amount_to_withdraw' => 'required'
                ]
            );

            if ($RequestAccruedBenefitsWithdrawal->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $RequestAccruedBenefitsWithdrawal->errors()
                ], 401);
            }

            $RequestAccruedBenefitsWithdrawalId = $request->route('id');
            $application_status = $request->application_status;
            $approval_status = $request->approval_status;
            $comment = $request->comment;
            $application = RequestAccruedBenefits::where('id', $RequestAccruedBenefitsWithdrawalId)->first();

            if (!$application) {
                return response()->json([
                    'status' => false,
                    'message' => 'Application id not found'
                ], 422);
            } else {
                //update the data
                $application->application_status = $application_status;
                $application->approval_status = $approval_status;
                $application->comment = $comment;

                if ($application->save()) {
                    //after the admin approves let make the deduction in the database
                    $updateTotalBalance = TotalAccruedBenefits::where('employee_code', $request->employee_code)->first();
                    $updateTotalBalance->total_accrued_benefits_amount = $updateTotalBalance->total_accrued_benefits_amount - $request->amount_to_withdraw;
                    if ($updateTotalBalance->save()) {
                        //let update the total balance after balance has been withdrawn
                        AccruedInterests::TotalAccumulation(- ($request->amount_to_withdraw));
                        return response()->json([
                            'status' => true,
                            'message' => 'You have successfully closed this request'
                        ], 200);
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
