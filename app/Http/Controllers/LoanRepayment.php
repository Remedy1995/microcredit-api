<?php

namespace App\Http\Controllers;

use App\Imports\ExcelImportClass;
use App\Models\CarLoans;
use App\Models\ChristmasLoan;
use App\Models\EasterLoans;
use App\Models\FoundersDayLoan;
use App\Models\HappyBirthdayLoan;
use App\Models\LoanApplication;
use App\Models\SchoolFeesLoan;
use App\Utilities\RecordTransactions;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LoanRepayment extends Controller
{

    //

    public function getUserLoanMonthlyRepayments(Request $request)
    {
        $employee_code = $request->employee_code;
        try {
            $userLoanRepayments = \App\Models\LoanRepayments::where('employee_code', $employee_code)->orderBy('date','desc')->get();
            if (count($userLoanRepayments) < 1) {
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
                'data' => $userLoanRepayments
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => false,
                'message' => 'Error in fetching data',
                'errors' => $error->getMessage()
            ], 500);
        }
    }




    public function LoanRepayments(Request $request)
    {
        try {

            $request->validate(
                [
                    'file' => 'required|mimes:xls,xlsx,csv|max:10240',
                    'type_of_loan_taken' => 'required'
                ]
            );

            $file = $request->file('file');
            $import = new ExcelImportClass;
            $data = Excel::toCollection($import, $file);

            $checkMonths = \App\Models\LoanRepayments::select('created_at')->get();
            //check if we have already uploaded loan repayments for the month
            $hasUploadedRepayments = RecordTransactions::CheckPaidContributions($checkMonths);
            // return $hasUploadedContributions;
            if ($hasUploadedRepayments === 1) {
                return response()->json([
                    'status' => false,
                    'message' => 'Sorry you have already uploaded loan repayments for the month'
                ], 405);
            }

            $data = $data[0]->toArray();

            $excelData = RecordTransactions::FormatExcelData($data);
            $convertData = json_decode($excelData, true);
            //return ($convertData);
            foreach ($convertData as $row) {
                //return $data;
                $employeeCode = $row['employee_code'] ?? null;
                $employeeAmount = $row['Principal_amount'] ?? null;
                $monthlyRepayment = $row['monthly_repayment_amount'] ?? null;

                //return $employeeCode;
                if ($employeeCode===null || !$employeeAmount || $monthlyRepayment) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Incorrect Loan Repayment File uploaded should include  monthly Repayment Amount or employee code data'
                    ], 405);
                }
                $loanRepayments = \App\Models\LoanRepayments::create([
                    'employee_code' => $employeeCode,
                    'Principal_amount' => $employeeAmount,
                    'monthly_repayment_amount' => $monthlyRepayment,
                ]);
            }
            if ($loanRepayments) {
                //after we have recorded loan repayment amount let update the loan oustanding balance for the loan
                foreach ($convertData as $row) {
                    $employeeCode = $row['employee_code'] ?? null;
                    $employeeAmount = $row['Principal_amount'] ?? null;
                    $monthlyRepayment = $row['monthly_repayment_amount'] ?? null;

                    //after the user makes a move to settle loans we make it active so that admin can approve it
                    if ($request->type_of_loan_taken === 'HAPPY_BIRTHDAY_APPLICATION_FORM') {
                        $happybirthdayApplication = HappyBirthdayLoan::where(['w_f_no' => $employeeCode])->first();
                        $happybirthdayApplication->settled_loan_amount = $happybirthdayApplication->settled_loan_amount + $monthlyRepayment;
                        $happybirthdayApplication->oustanding_loan_balance = $happybirthdayApplication->total_loan_amount_payable - $happybirthdayApplication->settled_loan_amount <= 0 ? 0.00 : $happybirthdayApplication->total_loan_amount_payable - $happybirthdayApplication->settled_loan_amount;
                        $happybirthdayApplication->loan_settlement_status = $happybirthdayApplication->oustanding_loan_balance <= 0 ? 'COMPLETED' : 'NOT-COMPLETED';
                    } else if ($request->type_of_loan_taken === 'LOAN_APPLICATION_FORM') {
                        $loanApplication = LoanApplication::where(['w_f_no' => $employeeCode])->first();
                        $loanApplication->settled_loan_amount = $loanApplication->settled_loan_amount + $monthlyRepayment;
                        $loanApplication->oustanding_loan_balance = $loanApplication->total_loan_amount_payable - $loanApplication->settled_loan_amount <= 0 ? 0.00 : $loanApplication->total_loan_amount_payable - $loanApplication->settled_loan_amount;
                        $loanApplication->loan_settlement_status = $loanApplication->oustanding_loan_balance <= 0 ? 'COMPLETED' : 'NOT-COMPLETED';
                    } else if ($request->type_of_loan_taken === 'SCHOOL_FEES_LOAN_APPLICATION') {
                        $SchoolFeesApplication = SchoolFeesLoan::where(['w_f_no' => $employeeCode])->first();
                        $SchoolFeesApplication->settled_loan_amount = $SchoolFeesApplication->settled_loan_amount + $monthlyRepayment;
                        $SchoolFeesApplication->oustanding_loan_balance = $SchoolFeesApplication->total_loan_amount_payable - $SchoolFeesApplication->settled_loan_amount <= 0 ? 0.00 : $SchoolFeesApplication->total_loan_amount_payable - $SchoolFeesApplication->settled_loan_amount;
                        $SchoolFeesApplication->loan_settlement_status = $SchoolFeesApplication->oustanding_loan_balance <= 0 ? 'COMPLETED' : 'NOT-COMPLETED';
                    } else if ($request->type_of_loan_taken === 'CAR_LOANS') {
                        $carLoanApplication = CarLoans::where(['w_f_no' => $employeeCode])->first();
                        $carLoanApplication->settled_loan_amount = $carLoanApplication->settled_loan_amount + $monthlyRepayment;
                        $carLoanApplication->oustanding_loan_balance = $carLoanApplication->total_loan_amount_payable - $carLoanApplication->settled_loan_amount <= 0 ? 0.00 : $carLoanApplication->total_loan_amount_payable - $carLoanApplication->settled_loan_amount;
                        $carLoanApplication->loan_settlement_status = $carLoanApplication->oustanding_loan_balance <= 0 ? 'COMPLETED' : 'NOT-COMPLETED';
                    } else if ($request->type_of_loan_taken === 'FOUNDERS_DAY_APPLICATION_FORM') {
                        $foundersDayApplication = FoundersDayLoan::where(['w_f_no' => $employeeCode])->first();
                        $foundersDayApplication->settled_loan_amount = $foundersDayApplication->settled_loan_amount + $monthlyRepayment;
                        $foundersDayApplication->oustanding_loan_balance = $foundersDayApplication->total_loan_amount_payable - $foundersDayApplication->settled_loan_amount <= 0 ? 0.00 : $foundersDayApplication->total_loan_amount_payable - $foundersDayApplication->settled_loan_amount;
                        $foundersDayApplication->loan_settlement_status = $foundersDayApplication->oustanding_loan_balance <= 0 ? 'COMPLETED' : 'NOT-COMPLETED';
                    } else if ($request->type_of_loan_taken === 'CHRISTMAS_APPLICATION_FORM') {
                        $christmasApplication = ChristmasLoan::where(['w_f_no' => $employeeCode])->first();
                        $christmasApplication->settled_loan_amount = $christmasApplication->settled_loan_amount + $monthlyRepayment;
                        $christmasApplication->oustanding_loan_balance = $christmasApplication->total_loan_amount_payable - $christmasApplication->settled_loan_amount <= 0 ? 0.00 : $christmasApplication->total_loan_amount_payable - $christmasApplication->settled_loan_amount;
                        $christmasApplication->loan_settlement_status = $christmasApplication->oustanding_loan_balance <= 0 ? 'COMPLETED' : 'NOT-COMPLETED';
                    } else if ($request->type_of_loan_taken === 'EASTER_APPLICATION_FORM') {
                        $EasterApplication = EasterLoans::where(['w_f_no' => $employeeCode])->first();
                        $EasterApplication->settled_loan_amount = $EasterApplication->settled_loan_amount + $monthlyRepayment;
                        $EasterApplication->oustanding_loan_balance = $EasterApplication->total_loan_amount_payable - $EasterApplication->settled_loan_amount <= 0 ? 0.00 : $EasterApplication->total_loan_amount_payable - $EasterApplication->settled_loan_amount;
                        $EasterApplication->loan_settlement_status = $EasterApplication->oustanding_loan_balance <= 0 ? 'COMPLETED' : 'NOT-COMPLETED';
                    }
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Loan Monthly Repayments has been uploaded successfully'
                ], 201);
            }

        } catch (\Exception $error) {
            return response()->json([
                'status' => false,
                'message' => 'Error in recording monthly contributions',
                'errors' => $error->getMessage()
            ], 500);
        }
    }

    public function viewAllLoanRepayments(Request $request)
    {
        $loanrepayments = \App\Models\LoanRepayments::orderBy('created_at', 'desc')->get();
        return response()->json($loanrepayments, 200);
    }
}
