<?php

namespace App\Http\Controllers;

use App\Models\AccruedBenefits;
use App\Models\TotalAccruedBenefits;
use App\Models\TotalCumulativeSavings;
use App\Utilities\RecordTransactions;
use Carbon\Carbon;
use App\Imports\ExcelImportClass;
use App\Models\MonthlyContributions as ModelsMonthlyContributions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Utilities\AccruedInterests;

class MonthlyContributions extends Controller
{



    public function showContributionById(Request $request)
    {

        $contribution_id = $request->route('id');

        $application =  \App\Models\MonthlyContributions::where('id', $contribution_id)->first();

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

    public function getUserContributions(Request $request)
    {
        $employee_code = $request->employee_code;
        try {
            $usercontributions = \App\Models\MonthlyContributions::where('employee_code', $employee_code)->get();
            if (count($usercontributions) < 1) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'Sorry user employee code does not exist'
                    ],
                    400
                );
            }

            return response()->json([
                'status' => true,
                'data' => $usercontributions
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => false,
                'message' => 'Error in fetching data',
                'errors' => $error->getMessage()
            ], 500);
        }
    }

    public function monthlyContributions(Request $request)
    {
        try {

            $request->validate(
                [
                    'file' => 'required|mimes:xls,xlsx,csv|max:10240'
                ]
            );

            $file = $request->file('file');
            $import = new ExcelImportClass;
            $data = Excel::toCollection($import, $file);

            $checkMonths = \App\Models\MonthlyContributions::select('created_at')->get();
            //check if we have already uploaded monthly contributions for the month
            $hasUploadedContributions = RecordTransactions::CheckPaidContributions($checkMonths);
           // return $hasUploadedContributions;
            if ($hasUploadedContributions === 1) {
                return response()->json([
                    'status' => false,
                    'message' => 'Sorry you have already uploaded monthly contributions for the month'
                ], 405);
            }

            $data = $data[0]->toArray();

            // Remove rows with all null values and where "monthly_amount_contribution" is not an integer
            $excelData = RecordTransactions::FormatExcelData($data);
            $convertData = json_decode($excelData, true);
            //return ($convertData);
            $myArray = [];
            foreach ($convertData as $row) {
                //return $data;
                $employeeCode = $row['employee_code'] ?? null;
                $employeeAmount = $row['monthly_amount_contribution'] ?? null;

                array_push($myArray, $employeeAmount);
                //let compute total contributions received

                $total_contributions = array_reduce($myArray, function ($mycarry, $item) {
                    return $mycarry + $item;
                }, 0);

                //return $employeeCode;
                if (!$employeeCode || !$employeeAmount) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Incorrect Contribution File should include  monthly contribution or employee code data'
                    ], 405);
                }
                $monthlyContributions = \App\Models\MonthlyContributions::create([
                    'monthly_amount_contribution' => $employeeAmount,
                    'employee_code' => $employeeCode
                ]);
            }
            if ($monthlyContributions) {
                //compute total cumulative savings for all contributions
                AccruedInterests::TotalAccumulation($total_contributions);

                //after monthly contributions has been uploaded
                //let record principal amount and employee code for each employee
                foreach ($convertData as $row) {
                    $employee_code = $row['employee_code'] ?? null;
                    $employeeAmount = floatval($row['monthly_amount_contribution']);
                    //$interestRate = 7.5;
                    //
                    //$interest = AccruedInterests::CalculateInterest($employeeAmount,$interestRate);
                    //$subTotalAmount = AccruedInterests::CalculateSubTotal($employeeAmount, $interest);
                    $computeBenefits = new AccruedBenefits([
                        'employee_code' => $employee_code,
                        'Principal_amount' => $employeeAmount,
                        // 'interest_rate' => $interestRate,
                        //'interest_amount' => $interest,
                        // 'sub_total_amount' => $subTotalAmount,
                    ]);

                    $recordAccruedBenefits = $computeBenefits->save();
                    //after we compute the subtotal amount we calculate the total amount in a different table
                    //check if employee code exists
                    $searchEmployeeCode = TotalAccruedBenefits::where('employee_code', $computeBenefits->employee_code)->first();
                    //return $searchEmployeeCode;

                    if ($searchEmployeeCode) {
                        $searchEmployeeCode->total_accrued_benefits_amount += $computeBenefits->Principal_amount;
                        $searchEmployeeCode->save();
                    } else {
                        TotalAccruedBenefits::create([
                            'employee_code' => $computeBenefits->employee_code,
                            'total_accrued_benefits_amount' => $computeBenefits->Principal_amount
                        ]);
                    }
                }
                return response()->json([
                    'status' => true,
                    'message' => 'Monthly Contributions for this month has been successfully uploaded'
                ], 201);
            }


            // Assume $excelResponse contains the provided Excel response

            // Access the first array within the response

        } catch (\Exception $error) {
            return response()->json([
                'status' => false,
                'message' => 'Error in recording monthly contributions',
                'errors' => $error->getMessage()
            ], 500);
        }
    }

    public function viewAllContribution(Request $request)
    {
        $contributions = \App\Models\MonthlyContributions::orderBy('created_at', 'desc')->get();
        return response()->json($contributions, 200);
    }



    // private function TotalAccumulation($totalBalance)
    // {
    //     $total_accumulation = TotalCumulativeSavings::where('id', 1)->first();
    //     if ($total_accumulation) {
    //         $total_accumulation->total_cumulative_savings = $total_accumulation->total_cumulative_savings + $totalBalance;
    //         $total_accumulation->save();
    //     }
    // }
}
