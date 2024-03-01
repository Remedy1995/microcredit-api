<?php

namespace App\Http\Controllers;

use App\Models\CarLoans;
use App\Models\ChristmasLoan;
use App\Models\EasterLoans;
use App\Models\EmergencyLoans;
use App\Models\FoundersDayLoan;
use App\Models\HappyBirthdayLoan;
use App\Models\LoanApplication;
use App\Models\OtherLoans;
use App\Models\Refunds;
use App\Models\SchoolFeesLoan;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Throwable;

class RefundLoans extends Controller
{
    //

    public function RequestRefunds(Request $request)
    {


        try {
            $validateData = Validator::make(
                $request->all(),
                [
                    'type_of_loan_refunds' => 'required',
                    'employee_code' => 'required',
                    'refund_amount' => 'required',
                    'application_id' => 'required'
                ]
            );

            if ($validateData->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validateData->errors()
                ], 422);
            }
            $saveRequest = Refunds::create([
                'type_of_loan_refunds' => $request->type_of_loan_refunds,
                'employee_code' => $request->employee_code,
                'refund_amount' => $request->refund_amount,
                'application_id' => $request->application_id,
                'refund_status' => 'IN-PROGRESS',
                'user_id' => $request->user()->id
            ]);

            if ($saveRequest) {
                return response()->json([
                    'status' => true,
                    'message' => 'Refund application has been successfully requested.Wait for approval'
                ], 201);
            }
        } catch (\Exception $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage()
            ], 500);
        }
    }



    public function GetAllRequestedRefunds(Request $request)
    {
        try {
            $applications = Refunds::where('refund_status', 'IN-PROGRESS')->with('user')->orderBy('created_at', 'desc')->get();
            return response()->json([
                'data' => $applications
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }


    public function approveRequestedReFunds(Request $request)
    {

        try {
            //search
            $validator = Validator::make(
                $request->all(),
                [
                    'application_type' => 'required',
                    'refund_id' => 'required',
                    'application_id' => 'required',
                    'approval_status' => 'required'
                ]
            );

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()
                ], 422);
            }

            $updateRefunds = Refunds::where('id', $request->refund_id)->first();
            if ($updateRefunds) {
                if ($request->approval_status === 'APPROVED') {
                    $updateRefunds->refund_status = $request->approval_status;
                    $updateRefunds->save();
                    //first let search in the loan tables using the specified application type
                    if ($request->application_type === 'HAPPY_BIRTHDAY_APPLICATION_FORM') {
                        $happybirthdayApplication = HappyBirthdayLoan::where(['application_name' => $request->application_type, 'id' => $request->application_id])->first();
                        $happybirthdayApplication->refund_amount = 0.00;
                        $happybirthdayApplication->settled_loan_amount = $happybirthdayApplication->total_loan_amount_payable;
                        $happybirthdayApplication->save();
                    } else if ($request->application_type === 'LOAN_APPLICATION_FORM') {
                        $loanApplication = LoanApplication::where(['application_name' => $request->application_type, 'id' => $request->application_id])->first();
                        $loanApplication->refund_amount = 0.00;
                        $loanApplication->settled_loan_amount = $loanApplication->total_loan_amount_payable;
                        $loanApplication->save();
                    } else if ($request->application_type === 'SCHOOL_FEES_LOAN_APPLICATION') {
                        $SchoolFeesApplication = SchoolFeesLoan::where(['application_name' => $request->application_type, 'id' => $request->application_id])->first();
                        $SchoolFeesApplication->refund_amount = 0.00;
                        $SchoolFeesApplication->settled_loan_amount = $SchoolFeesApplication->total_loan_amount_payable;
                        $SchoolFeesApplication->save();
                    } else if ($request->application_type === 'CAR_LOANS') {
                        $carLoanApplication = CarLoans::where(['application_name' => $request->application_type, 'id' => $request->application_id])->first();
                        $carLoanApplication->settled_loan_amount = $carLoanApplication->total_loan_amount_payable;
                        $carLoanApplication->refund_amount = 0.00;
                        $carLoanApplication->save();
                    } else if ($request->application_type === 'FOUNDERS_DAY_APPLICATION_FORM') {
                        $foundersDayApplication = FoundersDayLoan::where(['application_name' => $request->application_type, 'id' => $request->application_id])->first();
                        $foundersDayApplication->refund_amount = 0.00;
                        $foundersDayApplication->settled_loan_amount = $foundersDayApplication->total_loan_amount_payable;
                        $foundersDayApplication->save();
                    } else if ($request->application_type === 'CHRISTMAS_APPLICATION_FORM') {
                        $christmasApplication = ChristmasLoan::where(['application_name' => $request->application_type, 'id' => $request->application_id])->first();
                        $christmasApplication->refund_amount = 0.00;
                        $christmasApplication->settled_loan_amount = $christmasApplication->total_loan_amount_payable;
                        $christmasApplication->save();
                    } else if ($request->application_type === 'EASTER_APPLICATION_FORM') {
                        $EasterApplication = EasterLoans::where(['application_name' => $request->application_type, 'id' => $request->application_id])->first();
                        $EasterApplication->refund_amount = 0.00;
                        $EasterApplication->settled_loan_amount = $EasterApplication->total_loan_amount_payable;
                        $EasterApplication->save();
                    } else if ($request->application_type === 'EMERGENCY_APPLICATION_FORM') {
                        $EmergencyApplication = EmergencyLoans::where(['application_name' => $request->application_type, 'id' => $request->application_id])->first();
                        $EmergencyApplication->refund_amount = 0.00;
                        $EmergencyApplication->settled_loan_amount = $EmergencyApplication->total_loan_amount_payable;
                        $EmergencyApplication->save();
                    } else if ($request->application_type === 'OTHER_APPLICATION_FORM') {
                        $OtherApplication = OtherLoans::where(['application_name' => $request->application_type, 'id' => $request->application_id])->first();
                        $OtherApplication->refund_amount = 0.00;
                        $OtherApplication->settled_loan_amount = $OtherApplication->total_loan_amount_payable;
                        $OtherApplication->save();
                    }
                    return response()->json([
                        'status' => true,
                        'message' => 'You have successfully approved the refunds'
                    ], 200);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'You have successfully aborted the request'
                    ], 200);
                }
            }
        } catch (Exception $error) {
            return response()->json([
                'status' => true,
                'message' => $error->getMessage()
            ], 500);
        }
    }
}
