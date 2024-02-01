<?php

namespace App\Http\Controllers;

use App\Models\EarlySettlement;
use Illuminate\Support\Facades\DB;
use App\Models\ApplicationDetails;
use Illuminate\Http\Request;

class AllApplications extends Controller
{



    //



    public function AllPendingEarlySettlementApprovals(Request $request)
    {

        try {
            $applications = EarlySettlement::with([
                'user' => function ($query) {
                    $query->select('id', 'firstname', 'lastname', 'phone');
                },
                'schoolfeesloan' => function ($query) {
                    $query->where('loan_settlement_status', 'IN-PROGRESS');
                },
                'happybirthdayloan' => function ($query) {
                    $query->where('loan_settlement_status', 'IN-PROGRESS');
                },
                'loanapplication' => function ($query) {
                    $query->where('loan_settlement_status', 'IN-PROGRESS');
                },
                'carloan' => function ($query) {
                    $query->where('loan_settlement_status', 'IN-PROGRESS');
                },
                'christmasloan' => function ($query) {
                    $query->where('loan_settlement_status', 'IN-PROGRESS');
                },
                'foundersdayloan' => function ($query) {
                    $query->where('loan_settlement_status', 'IN-PROGRESS');
                },
                'easterloan' => function ($query) {
                    $query->where('loan_settlement_status', 'IN-PROGRESS');
                },

            ])->get();

            $filteredApplications = $applications->filter(function ($application) {
                return $application->schoolfeesloan || $application->happybirthdayloan || $application->loanapplication
                    || $application->carloan || $application->christmasloan || $application->foundersdayloan || $application->easterloan;
            });

            $data = $filteredApplications->map(function ($application) {
                return [
                    'amount_paid' => $application->amount_paid,
                    'month_paid' => $application->month,
                    'user_id' => $application->user->id,
                    'happy_birthday_detail_id' => optional($application->happybirthdayloan)->id,
                    'easter_detail_id' => optional($application->easterloan)->id,
                    'founders_day_detail_id' => optional($application->foundersdayloan)->id,
                    'christmas_detail_id' => optional($application->christmasloan)->id,
                    'loan_detail_id' => optional($application->loanapplication)->id,
                    'school_fees_detail_id' => optional($application->schoolfeesloan)->id,
                    'car_detail_id' => optional($application->carloan)->id,
                    'user' => $application->user,
                    'schoolfeesloan' => $application->schoolfeesloan,
                    'happybirthdayloan' => $application->happybirthdayloan,
                    'loanapplication' => $application->loanapplication,
                    'carloan' => $application->carloan,
                    'christmasloan' => $application->christmasloan,
                    'easterloan' => $application->easterloan,
                    'foundersdayloan' => $application->foundersdayloan
                ];
            });

            return response()->json($data->values()->all(), 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function AllPendingApprovals(Request $request)
    {


        try {
            $applications = ApplicationDetails::with([
                'user' => function ($query) {
                    $query->select('id', 'firstname', 'lastname', 'phone');
                },
                'schoolfeesloan' => function ($query) {
                    $query->where('application_status', 'IN-PROGRESS');
                },
                'happybirthdayloan' => function ($query) {
                    $query->where('application_status', 'IN-PROGRESS');
                },
                'loanapplication' => function ($query) {
                    $query->where('application_status', 'IN-PROGRESS');
                },
                'carloan' => function ($query) {
                    $query->where('application_status', 'IN-PROGRESS');
                },
                'foundersdayloan' => function ($query) {
                    $query->where('application_status', 'IN-PROGRESS');
                },
                'christmasloan' => function ($query) {
                    $query->where('application_status', 'IN-PROGRESS');
                },
                'easterloan' => function ($query) {
                    $query->where('application_status', 'IN-PROGRESS');
                }
            ])->get();
            $filteredApplications = $applications->filter(function ($application) {
                return $application->schoolfeesloan || $application->happybirthdayloan || $application->loanapplication || $application->carloan
                    || $application->foundersdayloan || $application->christmasloan || $application->easterloan;
            });

            $data = $filteredApplications->map(function ($application) {
                return [
                    'user_id' => $application->user->id,
                    'happy_birthday_detail_id' => optional($application->happybirthdayloan)->id,
                    'loan_detail_id' => optional($application->loanapplication)->id,
                    'school_fees_detail_id' => optional($application->schoolfeesloan)->id,
                    'car_detail_id' => optional($application->carloan)->id,
                    'christmas_detail_id' => optional($application->christmasloan)->id,
                    'founders_day_detail_id' => optional($application->foundersdayloan)->id,
                    'easter_detail_id' => optional($application->easterloan)->id,
                    'user' => $application->user,
                    'schoolfeesloan' => $application->schoolfeesloan,
                    'happybirthdayloan' => $application->happybirthdayloan,
                    'loanapplication' => $application->loanapplication,
                    'carloan' => $application->carloan,
                    'foundersdayloan' => $application->foundersdayloan,
                    'christmasloan' => $application->christmasloan,
                    'easterloan' => $application->easterloan
                ];
            });

            return response()->json($data->values()->all(), 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }


    public function AllApplicationApprovals(Request $request)
    {
        try {
            $applications = ApplicationDetails::with([
                'user' => function ($query) {
                    $query->select('id', 'firstname', 'lastname', 'phone');
                }
            ])->with([
                'schoolfeesloan' => function ($query) {
                    $query->where('approval_status', "COMPLETED");
                },
                'happybirthdayloan' => function ($query) {
                    $query->where('approval_status', "COMPLETED");
                },
                'loanapplication' => function ($query) {
                    $query->where('loan_approval_status', "COMPLETED");
                },
                'carloan' => function ($query) {
                    $query->where('approval_status', "COMPLETED");
                },
                'foundersdayloan' => function ($query) {
                    $query->where('approval_status', "COMPLETED");
                },
                'christmasloan' => function ($query) {
                    $query->where('approval_status', "COMPLETED");
                },
                'easterloan' => function ($query) {
                    $query->where('approval_status', "COMPLETED");
                }
            ])->get();
            foreach ($applications as $key => $application) {
                if (
                    $application->schoolfeesloan == null && $application->happybirthdayloan == null && $application->loanapplication == null
                    &&  $application->carloan == null && $application->foundersdayloan == null && $application->christmasloan == null && $application->easterloan
                ) {
                    unset($applications[$key]);
                }
            }
            if ($applications) {
                return response()->json($applications->values()->all(), 200);
            } else {
                return response()->json($applications, 404);
            }
        } catch (\Exception $error) {
            return response()->json([
                'status' => false,
                'message' => $error->getMessage()
            ], 500);
        }
    }



    public function userApplicationHistory(Request $request)
    {
        try {
            $userapplications = ApplicationDetails::where(['user_id' => $request->user()->id])->with(['user', 'happybirthdayloan', 'schoolfeesloan', 'loanapplication', 'carloan', 'foundersdayloan', 'christmasloan', 'easterloan'])->orderBy('created_at', 'desc')->get();
            return response()->json($userapplications, 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => false,
                'message' => $error->getMessage()
            ], 500);
        }
    }
}
