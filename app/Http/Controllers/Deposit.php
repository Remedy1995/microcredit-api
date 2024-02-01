<?php

namespace App\Http\Controllers;

use App\Models\AccruedBenefits;
use App\Models\Deposits;
use App\Models\TotalAccruedBenefits;
use App\Models\TotalCumulativeSavings;
use App\Utilities\AccruedInterests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class Deposit extends Controller
{
    //



    public function ApproveUserDeposit(Request $request)
    {

        try {
            $deposit = Validator::make(
                $request->all(),
                [
                    'application_status' => 'required',
                    'approval_status' => 'required',
                    'employee_code' => 'required',
                    'paymentAmount' => 'required'
                ]
            );

            if ($deposit->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $deposit->errors()
                ], 401);
            }

            $depositId = $request->route('id');
            $application_status = $request->application_status;
            $approval_status = $request->approval_status;
            $comment = $request->comment;
            $application = Deposits::where('id', $depositId)->first();

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
                    //after the admin approves the deposit data let add the deposited amount to the user balance
                            $interestRate = 7.5;
                            $interest = AccruedInterests::CalculateInterest(floatval($request->paymentAmount), $interestRate);
                            $subTotalAmount = AccruedInterests::CalculateSubTotal(floatval($request->paymentAmount), $interest);
                            //compute total cumulative savings
                            AccruedInterests::TotalAccumulation($request->paymentAmount);
                            $computeBenefits = new AccruedBenefits([
                                'employee_code' => $request->employee_code,
                                'Principal_amount' => $request->paymentAmount,
                                'interest_rate' => $interestRate,
                                'interest_amount' => $interest,
                                'sub_total_amount' => $subTotalAmount,
                            ]);
                             $computeBenefits->save();
                            //search if a user has a total accrued benefit information if not create else update existing data
                            $searchEmployeeCode = TotalAccruedBenefits::where('employee_code',$request->employee_code)->first();    
                            if ($searchEmployeeCode) {
                                $searchEmployeeCode->total_accrued_benefits_amount = $searchEmployeeCode->total_accrued_benefits_amount + $computeBenefits->sub_total_amount;
                                $searchEmployeeCode->save();
                            } else {
                                TotalAccruedBenefits::create([
                                    'employee_code' => $computeBenefits->employee_code,
                                    'total_accrued_benefits_amount' => $subTotalAmount
                                ]);
                            
        
                        }
                        return response()->json([
                            'status' => true,
                            'message' => 'You have finally approved user deposits'
                        ], 201);

            }
        }
        
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function showDepositsById(Request $request)
    {
        $deposit_id = $request->route('id');
        $application = Deposits::where('id', $deposit_id)->first();

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

    public function MakeDeposit(Request $request)
    {
        try {
            $depositData = Validator::make(
                $request->all(),
                [
                    'paymentAmount',
                    // 'reciept_url',
                    'paymentType',
                    'user_id',
                    'file' => 'required|mimes:pdf'
                ]
            );

            if ($depositData->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $depositData->errors()
                ], 401);
            }

            //image url

            // $response = Http::post('https://jubelsusu-70y9.onrender.com/user/user-image-upload', [
            //     'file' => $request->file('file')
            // ]);

            // if ($response) {
            //     $fileUrl =   $response->json();

            // }
            //check whether user has already submitted deposit data

            $checkDepositData = Deposits::where(['application_status' => 'IN-PROGRESS', 'user_id' => $request->user()->id])->first();

            if ($checkDepositData) {
                return response()->json([
                    'status' => false,
                    'message' => 'You have already submitted deposits information Please wait for approval'
                ], 400);
            }


            $DepositData = Deposits::create([
                'paymentAmount' => $request->paymentAmount,
                'reciept_url' => $request->reciept_url,
                'paymentType' => $request->paymentType,
                'user_id' => $request->user()->id,
                'application_status' => 'IN-PROGRESS',
                'approval_status' => 'PENDING',
                'employee_code' => $request->user()->employee_code,
                'reciept_number' => $request->reciept_number
            ]);

            if ($DepositData) {
                return response()->json([
                    'status' => true,
                    'message' => 'You have successfully made deposits wait for final approval'
                ], 201);



            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function GetAllPendingDeposits()
    {
        try {
            $applications = Deposits::with('user')->get();

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
   
}
