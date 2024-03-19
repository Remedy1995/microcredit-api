<?php

namespace App\Http\Controllers;

use App\Models\CurrentInterestRates;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class AllInterestRates extends Controller
{
    //

    public function index(Request $request)
    {

        try {
            $interestRates = CurrentInterestRates::all();
            return response()->json([
                'status' => true,
                'data' => $interestRates
            ], 200);
        } catch (Exception $error) {
            return response()->json([
                'status' => false,
                'message' => $error->getMessage()
            ], 500);
        }
    }




    public function store(Request $request)
    {


        try {
            $AllInterestData = Validator::make(
                $request->all(),
                [
                    'application_type_name' => 'required',
                    'interest_duration' => 'required',
                    'interest_rates' => 'required'
                ]
            );

            if ($AllInterestData->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $AllInterestData->errors()
                ], 401);
            }

            ///return $request->all();

            $AllInterestsRate = \App\Models\AllInterestRates::create([
                'application_type_name' => $request->application_type_name,
                'interest_duration' => $request->interest_duration,
                'interest_rates' => $request->interest_rates
            ]);


            if ($AllInterestsRate) {
                //record all interest rates after let update the current interest rate for each application
                $findInterestRateForApplication = CurrentInterestRates::where('application_type_name', $request->application_type_name)->first();
                //if the interest rate has not been sent for a particular application make a new entry else update the existing interest for the application
                if (!$findInterestRateForApplication) {
                    CurrentInterestRates::create([
                        'application_type_name' => $request->application_type_name,
                        'interest_duration' => $request->interest_duration,
                        'interest_rates' => $request->interest_rates
                    ]);
                } else {
                    $findInterestRateForApplication->application_type_name = $request->application_type_name;
                    $findInterestRateForApplication->interest_duration = $request->interest_duration;
                    $findInterestRateForApplication->interest_rates = $request->interest_rates;
                    $findInterestRateForApplication->save();
                }
                return response()->json([
                    'status' => true,
                    'message' => 'Interest Rate for ' . $AllInterestsRate->application_type_name . ' has been set successfully'
                ], 201);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
