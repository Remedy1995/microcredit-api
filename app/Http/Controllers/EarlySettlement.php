<?php

namespace App\Http\Controllers;

use App\Models\CarLoans;
use App\Models\ChristmasLoan;
use App\Models\EasterLoans;
use App\Models\EmergencyLoans;
use App\Models\FoundersDayLoan;
use App\Models\HappyBirthdayLoan;
use App\Models\LoanApplication;
use App\Models\LongTermLoan;
use App\Models\OtherLoans;
use App\Models\SchoolFeesLoan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EarlySettlement extends Controller
{
    //

    public function UserSettleLoans(Request $request)
    {

        $userData = Validator::make(
            $request->all(),
            [
                'amount_paid' => 'required',
                'month' => 'required',
                'id' => 'required',
                'type_of_loan_taken' => 'required'

            ]
        );

        if ($userData->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $userData->errors()
            ], 401);
        }
        $early_settlement_id = $request->id;
        $application = \App\Models\EarlySettlement::where(function ($query) use ($early_settlement_id, $request) {
            $query->where('school_fees_detail_id', $early_settlement_id)
                ->where('type_of_loan_taken', $request->type_of_loan_taken)
                ->orWhere('happy_birthday_detail_id', $early_settlement_id)
                ->where('type_of_loan_taken', $request->type_of_loan_taken)
                ->orWhere('loan_detail_id', $early_settlement_id)
                ->where('type_of_loan_taken', $request->type_of_loan_taken)
                ->orWhere('founders_day_detail_id', $early_settlement_id)
                ->where('type_of_loan_taken', $request->type_of_loan_taken)
                ->orWhere('car_detail_id', $early_settlement_id)
                ->where('type_of_loan_taken', $request->type_of_loan_taken)
                ->orWhere('christmas_detail_id', $early_settlement_id)
                ->where('type_of_loan_taken', $request->type_of_loan_taken)
                ->orWhere('easter_detail_id', $early_settlement_id)
                ->where('type_of_loan_taken', $request->type_of_loan_taken)
                ->orWhere('emergency_detail_id', $early_settlement_id)
                ->where('type_of_loan_taken', $request->type_of_loan_taken)
                ->orWhere('other_detail_id', $early_settlement_id)
                ->where('type_of_loan_taken', $request->type_of_loan_taken)
                ->orWhere('long_detail_id', $early_settlement_id)
                ->where('type_of_loan_taken', $request->type_of_loan_taken);
        })
            ->first();
        if ($application == null) {
            return response()->json([
                'status' => false,
                'message' => 'Application id not found',
            ], 401);
        }

        $application->amount_paid = $request->amount_paid;
        $application->month = $request->month;

        if ($application->save()) {
            //after the user makes a move to settle loans we make it active so that admin can approve it
            if ($request->type_of_loan_taken === 'HAPPY_BIRTHDAY_APPLICATION_FORM') {
                $happybirthdayApplication = HappyBirthdayLoan::where(['application_name' => $request->type_of_loan_taken, 'id' => $early_settlement_id])->first();
                $happybirthdayApplication->loan_settlement_status = 'IN-PROGRESS';
                $happybirthdayApplication->save();
            } else if ($request->type_of_loan_taken === 'LOAN_APPLICATION_FORM') {
                $loanApplication = LoanApplication::where(['application_name' => $request->type_of_loan_taken, 'id' => $early_settlement_id])->first();
                $loanApplication->loan_settlement_status = 'IN-PROGRESS';
                $loanApplication->save();
            } else if ($request->type_of_loan_taken === 'SCHOOL_FEES_LOAN_APPLICATION') {
                $SchoolFeesApplication = SchoolFeesLoan::where(['application_name' => $request->type_of_loan_taken, 'id' => $early_settlement_id])->first();
                $SchoolFeesApplication->loan_settlement_status = 'IN-PROGRESS';
                $SchoolFeesApplication->save();
            } else if ($request->type_of_loan_taken === 'CAR_LOANS') {
                $carLoanApplication = CarLoans::where(['application_name' => $request->type_of_loan_taken, 'id' => $early_settlement_id])->first();
                $carLoanApplication->loan_settlement_status = 'IN-PROGRESS';
                $carLoanApplication->save();
            } else if ($request->type_of_loan_taken === 'FOUNDERS_DAY_APPLICATION_FORM') {
                $foundersDayApplication = FoundersDayLoan::where(['application_name' => $request->type_of_loan_taken, 'id' => $early_settlement_id])->first();
                $foundersDayApplication->loan_settlement_status = 'IN-PROGRESS';
                $foundersDayApplication->save();
            } else if ($request->type_of_loan_taken === 'CHRISTMAS_APPLICATION_FORM') {
                $christmasApplication = ChristmasLoan::where(['application_name' => $request->type_of_loan_taken, 'id' => $early_settlement_id])->first();
                $christmasApplication->loan_settlement_status = 'IN-PROGRESS';
                $christmasApplication->save();
            } else if ($request->type_of_loan_taken === 'EASTER_APPLICATION_FORM') {
                $EasterApplication = EasterLoans::where(['application_name' => $request->type_of_loan_taken, 'id' => $early_settlement_id])->first();
                $EasterApplication->loan_settlement_status = 'IN-PROGRESS';
                $EasterApplication->save();
            } else if ($request->type_of_loan_taken === 'EMERGENCY_APPLICATION_FORM') {
                $EmergencyApplication = EmergencyLoans::where(['application_name' => $request->type_of_loan_taken, 'id' => $early_settlement_id])->first();
                $EmergencyApplication->loan_settlement_status = 'IN-PROGRESS';
                $EmergencyApplication->save();
            } else if ($request->type_of_loan_taken === 'OTHER_APPLICATION_FORM') {
                $OtherApplication = OtherLoans::where(['application_name' => $request->type_of_loan_taken, 'id' => $early_settlement_id])->first();
                $OtherApplication->loan_settlement_status = 'IN-PROGRESS';
                $OtherApplication->save();
            }
         else if ($request->type_of_loan_taken === 'LONG_TERM_APPLICATION_FORM') {
            $LongTermApplication = LongTermLoan::where(['application_name' => $request->type_of_loan_taken, 'id' => $early_settlement_id])->first();
            $LongTermApplication->loan_settlement_status = 'IN-PROGRESS';
            $LongTermApplication->save();
        }
            return response()->json([
                'status' => true,
                'message' => 'You have successfully initiated Early Settlement for your loan applied.Wait for Approval',
            ], 200);
        }
    }



    public function showUserLoansRefunds(Request $request)
    {
        try {
            $userRefunds = \App\Models\EarlySettlement::where(['user_id' => $request->user()->id])->with(['LoanApplication', 'HappyBirthdayLoan', 'SchoolFeesLoan', 'CarLoan', 'FoundersDayLoan', 'ChristmasLoan', 'EasterLoan', 'EmergencyLoan', 'OtherLoan','LongTermLoan'])->orderBy('created_at', 'desc')->get();

            if (count($userRefunds) < 1) {
                return response()->json([
                    'status' => false,
                    'message' => 'You have no Refunds',
                    'data' => []
                ], 200);
            }


            $filteredArray = [];
            foreach ($userRefunds as $application) {
                if ($application->happybirthdayloan) {
                    $filteredArray[] =
                        $application->happybirthdayloan->toArray();
                }
                if ($application->loanapplication) {
                    $filteredArray[] = $application->loanapplication->toArray();
                }
                if ($application->schoolfeesloan) {
                    $filteredArray[] = $application->schoolfeesloan->toArray();
                }
                if ($application->CarLoan) {
                    $filteredArray[] = $application->CarLoan->toArray();
                }
                if ($application->FoundersDayLoan) {
                    $filteredArray[] = $application->FoundersDayLoan->toArray();
                }
                if ($application->ChristmasLoan) {
                    $filteredArray[] = $application->ChristmasLoan->toArray();
                }
                if ($application->EasterLoan) {
                    $filteredArray[] = $application->EasterLoan->toArray();
                }
                if ($application->EmergencyLoan) {
                    $filteredArray[] = $application->EmergencyLoan->toArray();
                }
                if ($application->OtherLoan) {
                    $filteredArray[] = $application->OtherLoan->toArray();
                }
                if ($application->LongTermLoan) {
                    $filteredArray[] = $application->LongTermLoan->toArray();
                }
            }
            //check application that has not totally been settled
            $convertObject = collect($filteredArray);

            $checkUserRefunds = $convertObject->filter(function ($query) {
                return $query["loan_settlement_status"] === "COMPLETED" && $query['application_status'] === 'APPROVED';
            });

            return response()->json($checkUserRefunds->values()->all(), 200);


        } catch (\Exception $error) {
            return response()->json([
                'status' => false,
                'message' => $error->getMessage()
            ], 500);
        }
    }















    public function showUserUnsettledAppliedLoans(Request $request)
    {
        try {
            $userAppliedLoans = \App\Models\EarlySettlement::where(['user_id' => $request->user()->id])->with(['LoanApplication', 'HappyBirthdayLoan', 'SchoolFeesLoan', 'CarLoan', 'FoundersDayLoan', 'ChristmasLoan', 'EasterLoan', 'EmergencyLoan', 'OtherLoan','LongTermLoan'])->orderBy('created_at', 'desc')->get();

            if (count($userAppliedLoans) < 1) {
                return response()->json([
                    'status' => false,
                    'message' => 'You have no Pending Loans to Settle',
                    'data' => []
                ], 200);
            }


            $filteredArray = [];
            foreach ($userAppliedLoans as $application) {
                if ($application->happybirthdayloan) {
                    $filteredArray[] =
                        $application->happybirthdayloan->toArray();
                }
                if ($application->loanapplication) {
                    $filteredArray[] = $application->loanapplication->toArray();
                }
                if ($application->schoolfeesloan) {
                    $filteredArray[] = $application->schoolfeesloan->toArray();
                }
                if ($application->CarLoan) {
                    $filteredArray[] = $application->CarLoan->toArray();
                }
                if ($application->FoundersDayLoan) {
                    $filteredArray[] = $application->FoundersDayLoan->toArray();
                }
                if ($application->ChristmasLoan) {
                    $filteredArray[] = $application->ChristmasLoan->toArray();
                }
                if ($application->EasterLoan) {
                    $filteredArray[] = $application->EasterLoan->toArray();
                }
                if ($application->EmergencyLoan) {
                    $filteredArray[] = $application->EmergencyLoan->toArray();
                }
                if ($application->OtherLoan) {
                    $filteredArray[] = $application->OtherLoan->toArray();
                }
                if ($application->LongTermLoan) {
                    $filteredArray[] = $application->LongTermLoan->toArray();
                }
            }
            //check application that has not totally been settled
            $convertObject = collect($filteredArray);

            $checkUnsettledLoans = $convertObject->filter(function ($query) {
                return $query["loan_settlement_status"] !== "COMPLETED" && $query['application_status'] === 'APPROVED';
            });

            return response()->json($checkUnsettledLoans->values()->all(), 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => false,
                'message' => $error->getMessage()
            ], 500);
        }
    }


    public function ApproveEarlyStatementFormForHappyBirthday(Request $request)
    {
        try {
            $HappyBirthDayData = Validator::make(
                $request->all(),
                [
                    'application_status' => 'required',
                    'approval_status' => 'required',
                    'amount_paid' => 'required'
                ]
            );

            if ($HappyBirthDayData->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $HappyBirthDayData->errors()
                ], 401);
            }


            $happybirthday_id = $request->route('happy_birthday_loan');
            $application_status = $request->application_status;
            $approval_status = $request->approval_status;
            $comment = $request->comment;
            $effective_date_of_payment = $request->effective_date_of_payment;
            $amount_paid = $request->amount_paid;


            $application = \App\Models\HappyBirthdayLoan::where('id', $happybirthday_id)->first();

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
                //after early Settlement for happy birthday application has been approved let update these data in our database
                //'loan_settlement_status',
                //'total_loan_amount_payable',
                // 'settled_loan_amount',
                // 'oustanding_loan_balance',
                //  'amount_paid'
                //settled loan amount will be computations of cash amounts to offset applied loan

                if ($application_status === 'APPROVED') {
                    $application->settled_loan_amount = $application->settled_loan_amount + $amount_paid;
                    $application->oustanding_loan_balance = $application->total_loan_amount_payable - $application->settled_loan_amount <= 0 ? 0.00 : $application->total_loan_amount_payable - $application->settled_loan_amount;
                    $application->loan_settlement_status = $application->oustanding_loan_balance <= 0 ? 'COMPLETED' : 'NOT-COMPLETED';
                    $application->refund_amount = ($application->settled_loan_amount > $application->total_loan_amount_payable) ? $application->settled_loan_amount - $application->total_loan_amount_payable : $application->refund_amount;

                    if ($application->save()) {
                        //record this trnasaction as part of loan repayments
                        $loanRepayments = \App\Models\LoanRepayments::create([
                            'employee_code' => $application->w_f_no,
                            'Principal_amount' => $application->principal_amount,
                            'monthly_repayment_amount' => $application->monthly_repayment_amount,
                            'type_of_loan_taken' => $application->application_name,
                            'loan_payment_type' => 'BANK',
                            'amount_paid'=> $amount_paid
                        ]);
                        return response()->json([
                            'status' => true,
                            'message' => 'Happy Birthday Application has been successfully closed'
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




    public function ApproveEarlyStatementFormForLoanApplication(Request $request)
    {
        try {
            $LoanData = Validator::make(
                $request->all(),
                [
                    'application_status' => 'required',
                    'approval_status' => 'required',
                    'amount_paid' => 'required'
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
            $application_status = $request->application_status;
            $approval_status = $request->approval_status;
            $comment = $request->comment;
            $effective_date_of_payment = $request->effective_date_of_payment;
            $amount_paid = $request->amount_paid;


            $application = LoanApplication::where('id', $loan_id)->first();

            if (!$application) {
                return response()->json([
                    'status' => false,
                    'message' => 'Application id not found'
                ], 422);
            } else {
                $application->application_status = $application_status;
                $application->loan_approval_status = $approval_status;
                $application->comment = $comment;
                $application->effective_date_of_payment = $effective_date_of_payment;
                //after early Settlement for happy birthday application has been approved let update these data in our database
                //'loan_settlement_status',
                //'total_loan_amount_payable',
                // 'settled_loan_amount',
                // 'oustanding_loan_balance',
                //  'amount_paid'
                //settled loan amount will be computations of cash amounts to offset applied loan

                if ($application_status === 'APPROVED') {
                    $application->settled_loan_amount = $application->settled_loan_amount + $amount_paid;
                    $application->oustanding_loan_balance = $application->total_loan_amount_payable - $application->settled_loan_amount <= 0 ? 0.00 : $application->total_loan_amount_payable - $application->settled_loan_amount;
                    $application->loan_settlement_status = $application->oustanding_loan_balance <= 0 ? 'COMPLETED' : 'NOT-COMPLETED';
                    $application->refund_amount = ($application->settled_loan_amount > $application->total_loan_amount_payable) ? $application->settled_loan_amount - $application->total_loan_amount_payable : $application->refund_amount;
                    if ($application->save()) {
                          //record this trnasaction as part of loan repayments
                          $loanRepayments = \App\Models\LoanRepayments::create([
                            'employee_code' => $application->w_f_no,
                            'Principal_amount' => $application->principal_amount,
                            'monthly_repayment_amount' => $application->monthly_repayment_amount,
                            'type_of_loan_taken' => $application->application_name,
                            'loan_payment_type' => 'BANK',
                            'amount_paid'=> $amount_paid
                        ]);
                        return response()->json([
                            'status' => true,
                            'message' => 'Loan Application has been successfully closed'
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



    public function ApproveEarlyStatementFormForSchoolFeesLoan(Request $request)
    {
        try {
            $SchoolFeesData = Validator::make(
                $request->all(),
                [
                    'application_status' => 'required',
                    'approval_status' => 'required',
                    'amount_paid' => 'required'
                ]
            );

            if ($SchoolFeesData->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $SchoolFeesData->errors()
                ], 401);
            }


            $schoolfees_id = $request->route('schoolfees');
            $application_status = $request->application_status;
            $approval_status = $request->approval_status;
            $comment = $request->comment;
            $effective_date_of_payment = $request->effective_date_of_payment;
            $amount_paid = $request->amount_paid;


            $application = SchoolFeesLoan::where('id', $schoolfees_id)->first();

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
                //after early Settlement for happy birthday application has been approved let update these data in our database
                //'loan_settlement_status',
                //'total_loan_amount_payable',
                // 'settled_loan_amount',
                // 'oustanding_loan_balance',
                //  'amount_paid'
                //settled loan amount will be computations of cash amounts to offset applied loan

                if ($application_status === 'APPROVED') {
                    $application->settled_loan_amount = $application->settled_loan_amount + $amount_paid;
                    $application->oustanding_loan_balance = $application->total_loan_amount_payable - $application->settled_loan_amount <= 0 ? 0.00 : $application->total_loan_amount_payable - $application->settled_loan_amount;
                    $application->loan_settlement_status = $application->oustanding_loan_balance <= 0 ? 'COMPLETED' : 'NOT-COMPLETED';
                    $application->refund_amount = ($application->settled_loan_amount > $application->total_loan_amount_payable) ? $application->settled_loan_amount - $application->total_loan_amount_payable : $application->refund_amount;

                    if ($application->save()) {
                        //let record the amount inside the loans repayment table after it has been approved
                        $loanRepayments = \App\Models\LoanRepayments::create([
                            'employee_code' => $application->w_f_no,
                            'Principal_amount' => $application->principal_amount,
                            'monthly_repayment_amount' => $application->monthly_repayment_amount,
                            'type_of_loan_taken' => $application->application_name,
                            'loan_payment_type' => 'BANK',
                            'amount_paid'=> $amount_paid
                        ]);

                        return response()->json([
                            'status' => true,
                            'message' => 'School Fees Application has been successfully closed'
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



    public function ApproveEarlyStatementFormForCarLoan(Request $request)
    {
        try {
            $CarLoanData = Validator::make(
                $request->all(),
                [
                    'application_status' => 'required',
                    'approval_status' => 'required',
                    'amount_paid' => 'required'
                ]
            );

            if ($CarLoanData->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $CarLoanData->errors()
                ], 401);
            }


            $carloan_id = $request->route('carloan');
            $application_status = $request->application_status;
            $approval_status = $request->approval_status;
            $comment = $request->comment;
            $effective_date_of_payment = $request->effective_date_of_payment;
            $amount_paid = $request->amount_paid;


            $application = CarLoans::where('id', $carloan_id)->first();

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
                //after early Settlement for happy birthday application has been approved let update these data in our database
                //'loan_settlement_status',
                //'total_loan_amount_payable',
                // 'settled_loan_amount',
                // 'oustanding_loan_balance',
                //  'amount_paid'
                //settled loan amount will be computations of cash amounts to offset applied loan

                if ($application_status === 'APPROVED') {
                    $application->settled_loan_amount = $application->settled_loan_amount + $amount_paid;
                    $application->oustanding_loan_balance = $application->total_loan_amount_payable - $application->settled_loan_amount <= 0 ? 0.00 : $application->total_loan_amount_payable - $application->settled_loan_amount;
                    $application->loan_settlement_status = $application->oustanding_loan_balance <= 0 ? 'COMPLETED' : 'NOT-COMPLETED';
                    $application->refund_amount = ($application->settled_loan_amount > $application->total_loan_amount_payable) ? $application->settled_loan_amount - $application->total_loan_amount_payable : $application->refund_amount;
                    if ($application->save()) {
                          //record this trnasaction as part of loan repayments
                          $loanRepayments = \App\Models\LoanRepayments::create([
                            'employee_code' => $application->w_f_no,
                            'Principal_amount' => $application->principal_amount,
                            'monthly_repayment_amount' => $application->monthly_repayment_amount,
                            'type_of_loan_taken' => $application->application_name,
                            'loan_payment_type' => 'BANK',
                            'amount_paid'=> $amount_paid
                        ]);
                        return response()->json([
                            'status' => true,
                            'message' => 'Car Loan Application has been successfully closed'
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




    public function ApproveEarlyStatementFormForFoundersDayLoan(Request $request)
    {
        try {
            $FoundersDayLoanData = Validator::make(
                $request->all(),
                [
                    'application_status' => 'required',
                    'approval_status' => 'required',
                    'amount_paid' => 'required'
                ]
            );

            if ($FoundersDayLoanData->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $FoundersDayLoanData->errors()
                ], 401);
            }


            $foundersday_id = $request->route('foundersday_loan');
            $application_status = $request->application_status;
            $approval_status = $request->approval_status;
            $comment = $request->comment;
            $effective_date_of_payment = $request->effective_date_of_payment;
            $amount_paid = $request->amount_paid;


            $application = FoundersDayLoan::where('id', $foundersday_id)->first();

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
                //after early Settlement for happy birthday application has been approved let update these data in our database
                //'loan_settlement_status',
                //'total_loan_amount_payable',
                // 'settled_loan_amount',
                // 'oustanding_loan_balance',
                //  'amount_paid'
                //settled loan amount will be computations of cash amounts to offset applied loan

                if ($application_status === 'APPROVED') {
                    $application->settled_loan_amount = $application->settled_loan_amount + $amount_paid;
                    $application->oustanding_loan_balance = $application->total_loan_amount_payable - $application->settled_loan_amount <= 0 ? 0.00 : $application->total_loan_amount_payable - $application->settled_loan_amount;
                    $application->loan_settlement_status = $application->oustanding_loan_balance <= 0 ? 'COMPLETED' : 'NOT-COMPLETED';
                    $application->refund_amount = ($application->settled_loan_amount > $application->total_loan_amount_payable) ? $application->settled_loan_amount - $application->total_loan_amount_payable : $application->refund_amount;
                    if ($application->save()) {
                          //record this trnasaction as part of loan repayments
                          $loanRepayments = \App\Models\LoanRepayments::create([
                            'employee_code' => $application->w_f_no,
                            'Principal_amount' => $application->principal_amount,
                            'monthly_repayment_amount' => $application->monthly_repayment_amount,
                            'type_of_loan_taken' => $application->application_name,
                            'loan_payment_type' => 'BANK',
                            'amount_paid'=> $amount_paid
                        ]);
                        return response()->json([
                            'status' => true,
                            'message' => 'Founders Day Loan Application has been successfully closed'
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





    public function ApproveEarlyStatementFormForChristmasLoan(Request $request)
    {
        try {
            $ChristmasData = Validator::make(
                $request->all(),
                [
                    'application_status' => 'required',
                    'approval_status' => 'required',
                    'amount_paid' => 'required'
                ]
            );

            if ($ChristmasData->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $ChristmasData->errors()
                ], 401);
            }


            $christmas_id = $request->route('christmas_loan');
            $application_status = $request->application_status;
            $approval_status = $request->approval_status;
            $comment = $request->comment;
            $effective_date_of_payment = $request->effective_date_of_payment;
            $amount_paid = $request->amount_paid;


            $application = \App\Models\ChristmasLoan::where('id', $christmas_id)->first();

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
                //after early Settlement for happy birthday application has been approved let update these data in our database
                //'loan_settlement_status',
                //'total_loan_amount_payable',
                // 'settled_loan_amount',
                // 'oustanding_loan_balance',
                //  'amount_paid'
                //settled loan amount will be computations of cash amounts to offset applied loan

                if ($application_status === 'APPROVED') {
                    $application->settled_loan_amount = $application->settled_loan_amount + $amount_paid;
                    $application->oustanding_loan_balance = $application->total_loan_amount_payable - $application->settled_loan_amount <= 0 ? 0.00 : $application->total_loan_amount_payable - $application->settled_loan_amount;
                    $application->loan_settlement_status = $application->oustanding_loan_balance <= 0 ? 'COMPLETED' : 'NOT-COMPLETED';
                    $application->refund_amount = ($application->settled_loan_amount > $application->total_loan_amount_payable) ? $application->settled_loan_amount - $application->total_loan_amount_payable : $application->refund_amount;
                    if ($application->save()) {
                          //record this trnasaction as part of loan repayments
                          $loanRepayments = \App\Models\LoanRepayments::create([
                            'employee_code' => $application->w_f_no,
                            'Principal_amount' => $application->principal_amount,
                            'monthly_repayment_amount' => $application->monthly_repayment_amount,
                            'type_of_loan_taken' => $application->application_name,
                            'loan_payment_type' => 'BANK',
                            'amount_paid'=> $amount_paid
                        ]);
                        return response()->json([
                            'status' => true,
                            'message' => 'Christmas Loan Application has been successfully closed'
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


    public function ApproveEarlyStatementFormForEasterLoan(Request $request)
    {
        try {
            $EasterData = Validator::make(
                $request->all(),
                [
                    'application_status' => 'required',
                    'approval_status' => 'required',
                    'amount_paid' => 'required'
                ]
            );

            if ($EasterData->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $EasterData->errors()
                ], 401);
            }


            $easter_id = $request->route('easter_loan');
            $application_status = $request->application_status;
            $approval_status = $request->approval_status;
            $comment = $request->comment;
            $effective_date_of_payment = $request->effective_date_of_payment;
            $amount_paid = $request->amount_paid;


            $application = \App\Models\EasterLoans::where('id', $easter_id)->first();

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
                //after early Settlement for happy birthday application has been approved let update these data in our database
                //'loan_settlement_status',
                //'total_loan_amount_payable',
                // 'settled_loan_amount',
                // 'oustanding_loan_balance',
                //  'amount_paid'
                //settled loan amount will be computations of cash amounts to offset applied loan

                if ($application_status === 'APPROVED') {
                    $application->settled_loan_amount = $application->settled_loan_amount + $amount_paid;
                    $application->oustanding_loan_balance = $application->total_loan_amount_payable - $application->settled_loan_amount <= 0 ? 0.00 : $application->total_loan_amount_payable - $application->settled_loan_amount;
                    $application->loan_settlement_status = $application->oustanding_loan_balance <= 0 ? 'COMPLETED' : 'NOT-COMPLETED';
                    $application->refund_amount = ($application->settled_loan_amount > $application->total_loan_amount_payable) ? $application->settled_loan_amount - $application->total_loan_amount_payable : $application->refund_amount;
                    if ($application->save()) {
                          //record this trnasaction as part of loan repayments
                          $loanRepayments = \App\Models\LoanRepayments::create([
                            'employee_code' => $application->w_f_no,
                            'Principal_amount' => $application->principal_amount,
                            'monthly_repayment_amount' => $application->monthly_repayment_amount,
                            'type_of_loan_taken' => $application->application_name,
                            'loan_payment_type' => 'BANK',
                            'amount_paid'=> $amount_paid
                        ]);
                        return response()->json([
                            'status' => true,
                            'message' => 'Easter Loan Application has been successfully closed'
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




    public function ApproveEarlyStatementFormForEmergencyLoan(Request $request)
    {
        try {
            $EmergencyData = Validator::make(
                $request->all(),
                [
                    'application_status' => 'required',
                    'approval_status' => 'required',
                    'amount_paid' => 'required'
                ]
            );

            if ($EmergencyData->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $EmergencyData->errors()
                ], 401);
            }


            $emergency_id = $request->route('emergency_loan');
            $application_status = $request->application_status;
            $approval_status = $request->approval_status;
            $comment = $request->comment;
            $effective_date_of_payment = $request->effective_date_of_payment;
            $amount_paid = $request->amount_paid;


            $application = \App\Models\EmergencyLoans::where('id', $emergency_id)->first();

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
                //after early Settlement for happy birthday application has been approved let update these data in our database
                //'loan_settlement_status',
                //'total_loan_amount_payable',
                // 'settled_loan_amount',
                // 'oustanding_loan_balance',
                //  'amount_paid'
                //settled loan amount will be computations of cash amounts to offset applied loan

                if ($application_status === 'APPROVED') {
                    $application->settled_loan_amount = $application->settled_loan_amount + $amount_paid;
                    $application->oustanding_loan_balance = $application->total_loan_amount_payable - $application->settled_loan_amount <= 0 ? 0.00 : $application->total_loan_amount_payable - $application->settled_loan_amount;
                    $application->loan_settlement_status = $application->oustanding_loan_balance <= 0 ? 'COMPLETED' : 'NOT-COMPLETED';
                    $application->refund_amount = ($application->settled_loan_amount > $application->total_loan_amount_payable) ? $application->settled_loan_amount - $application->total_loan_amount_payable : $application->refund_amount;
                    if ($application->save()) {
                          //record this trnasaction as part of loan repayments
                          $loanRepayments = \App\Models\LoanRepayments::create([
                            'employee_code' => $application->w_f_no,
                            'Principal_amount' => $application->principal_amount,
                            'monthly_repayment_amount' => $application->monthly_repayment_amount,
                            'type_of_loan_taken' => $application->application_name,
                            'loan_payment_type' => 'BANK',
                            'amount_paid'=> $amount_paid
                        ]);
                        return response()->json([
                            'status' => true,
                            'message' => 'Emergency Loan Application has been successfully closed'
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


    public function ApproveEarlyStatementFormForOtherLoan(Request $request)
    {
        try {
            $OtherData = Validator::make(
                $request->all(),
                [
                    'application_status' => 'required',
                    'approval_status' => 'required',
                    'amount_paid' => 'required'
                ]
            );

            if ($OtherData->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $OtherData->errors()
                ], 401);
            }


            $other_id = $request->route('other_loan');
            $application_status = $request->application_status;
            $approval_status = $request->approval_status;
            $comment = $request->comment;
            $effective_date_of_payment = $request->effective_date_of_payment;
            $amount_paid = $request->amount_paid;


            $application = \App\Models\OtherLoans::where('id', $other_id)->first();

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
                //after early Settlement for happy birthday application has been approved let update these data in our database
                //'loan_settlement_status',
                //'total_loan_amount_payable',
                // 'settled_loan_amount',
                // 'oustanding_loan_balance',
                //  'amount_paid'
                //settled loan amount will be computations of cash amounts to offset applied loan

                if ($application_status === 'APPROVED') {
                    $application->settled_loan_amount = $application->settled_loan_amount + $amount_paid;
                    $application->oustanding_loan_balance = $application->total_loan_amount_payable - $application->settled_loan_amount <= 0 ? 0.00 : $application->total_loan_amount_payable - $application->settled_loan_amount;
                    $application->loan_settlement_status = $application->oustanding_loan_balance <= 0 ? 'COMPLETED' : 'NOT-COMPLETED';
                    $application->refund_amount = ($application->settled_loan_amount > $application->total_loan_amount_payable) ? $application->settled_loan_amount - $application->total_loan_amount_payable : $application->refund_amount;
                    if ($application->save()) {
                          //record this trnasaction as part of loan repayments
                          $loanRepayments = \App\Models\LoanRepayments::create([
                            'employee_code' => $application->w_f_no,
                            'Principal_amount' => $application->principal_amount,
                            'monthly_repayment_amount' => $application->monthly_repayment_amount,
                            'type_of_loan_taken' => $application->application_name,
                            'loan_payment_type' => 'BANK',
                            'amount_paid'=> $amount_paid
                        ]);
                        return response()->json([
                            'status' => true,
                            'message' => 'Other Loan Application has been successfully closed'
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


    public function ApproveEarlyStatementFormForLongTermLoan(Request $request)
    {
        try {
            $LongTermData = Validator::make(
                $request->all(),
                [
                    'application_status' => 'required',
                    'approval_status' => 'required',
                    'amount_paid' => 'required'
                ]
            );

            if ($LongTermData->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $LongTermData->errors()
                ], 401);
            }


            $longterm_id = $request->route('long_term');
            $application_status = $request->application_status;
            $approval_status = $request->approval_status;
            $comment = $request->comment;
            $effective_date_of_payment = $request->effective_date_of_payment;
            $amount_paid = $request->amount_paid;


            $application = \App\Models\LongTermLoan::where('id', $longterm_id)->first();

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
                //after early Settlement for happy birthday application has been approved let update these data in our database
                //'loan_settlement_status',
                //'total_loan_amount_payable',
                // 'settled_loan_amount',
                // 'oustanding_loan_balance',
                //  'amount_paid'
                //settled loan amount will be computations of cash amounts to offset applied loan

                if ($application_status === 'APPROVED') {
                    $application->settled_loan_amount = $application->settled_loan_amount + $amount_paid;
                    $application->oustanding_loan_balance = $application->total_loan_amount_payable - $application->settled_loan_amount <= 0 ? 0.00 : $application->total_loan_amount_payable - $application->settled_loan_amount;
                    $application->loan_settlement_status = $application->oustanding_loan_balance <= 0 ? 'COMPLETED' : 'NOT-COMPLETED';
                    $application->refund_amount = ($application->settled_loan_amount > $application->total_loan_amount_payable) ? $application->settled_loan_amount - $application->total_loan_amount_payable : $application->refund_amount;
                    if ($application->save()) {
                          //record this trnasaction as part of loan repayments
                          $loanRepayments = \App\Models\LoanRepayments::create([
                            'employee_code' => $application->w_f_no,
                            'Principal_amount' => $application->principal_amount,
                            'monthly_repayment_amount' => $application->monthly_repayment_amount,
                            'type_of_loan_taken' => $application->application_name,
                            'loan_payment_type' => 'BANK',
                            'amount_paid'=> $amount_paid
                        ]);
                        return response()->json([
                            'status' => true,
                            'message' => 'Long Term Loan Application has been successfully closed'
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
