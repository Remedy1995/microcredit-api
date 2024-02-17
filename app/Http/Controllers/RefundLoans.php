<?php

namespace App\Http\Controllers;

use App\Models\Refunds;
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
                 'user_id'=>$request->user()->id
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
}
