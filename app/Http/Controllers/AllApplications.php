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
                'emergencyloan' => function ($query) {
                    $query->where('loan_settlement_status', 'IN-PROGRESS');
                },
                'otherloan' => function ($query) {
                    $query->where('loan_settlement_status', 'IN-PROGRESS');
                },
                'longtermloan' => function ($query) {
                    $query->where('loan_settlement_status', 'IN-PROGRESS');
                },

            ])->orderBy('created_at', 'desc')->get();

            $filteredApplications = $applications->filter(function ($application) {
                return $application->schoolfeesloan  || $application->happybirthdayloan || $application->loanapplication
                    || $application->carloan || $application->christmasloan || $application->foundersdayloan ||
                    $application->easterloan || $application->emergencyloan  || $application->otherloan || $application->longtermloan;
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
                    'emergency_detail_id' => optional($application->emergencyloan)->id,
                    'other_detail_id' => optional($application->otherloan)->id,
                    'long_detail_id' => optional($application->longtermloan)->id,
                    'user' => $application->user,
                    'schoolfeesloan' => $application->schoolfeesloan,
                    'happybirthdayloan' => $application->happybirthdayloan,
                    'loanapplication' => $application->loanapplication,
                    'carloan' => $application->carloan,
                    'christmasloan' => $application->christmasloan,
                    'easterloan' => $application->easterloan,
                    'foundersdayloan' => $application->foundersdayloan,
                    'emergencyloan' => $application->emergencyloan,
                    'otherloan' => $application->otherloan,
                    'longtermloan' => $application->longtermloan,

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
                },
                'emergencyloan' => function ($query) {
                    $query->where('application_status', 'IN-PROGRESS');
                },
                'otherloan' => function ($query) {
                    $query->where('application_status', 'IN-PROGRESS');
                },
                'longtermloan' => function ($query) {
                    $query->where('application_status', 'IN-PROGRESS');
                }
            ])->orderBy('created_at', 'desc')->get();
            $filteredApplications = $applications->filter(function ($application) {
                return $application->schoolfeesloan || $application->happybirthdayloan || $application->loanapplication || $application->carloan
                    || $application->foundersdayloan || $application->christmasloan
                    || $application->easterloan || $application->emergencyloan ||  $application->otherloan  ||  $application->longtermloan;
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
                    'emergency_detail_id' => optional($application->emergencyloan)->id,
                    'other_detail_id' => optional($application->otherloan)->id,
                    'long_detail_id' => optional($application->longtermloan)->id,
                    'user' => $application->user,
                    'schoolfeesloan' => $application->schoolfeesloan,
                    'happybirthdayloan' => $application->happybirthdayloan,
                    'loanapplication' => $application->loanapplication,
                    'carloan' => $application->carloan,
                    'foundersdayloan' => $application->foundersdayloan,
                    'christmasloan' => $application->christmasloan,
                    'easterloan' => $application->easterloan,
                    'emergencyloan' => $application->emergencyloan,
                    'otherloan' => $application->otherloan,
                    'longtermloan' => $application->longtermloan
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
                },
                'emergencyloan' => function ($query) {
                    $query->where('approval_status', "COMPLETED");
                },
                'otherloan' => function ($query) {
                    $query->where('approval_status', "COMPLETED");
                },
                'longtermloan' => function ($query) {
                    $query->where('approval_status', "COMPLETED");
                }
            ])->orderBy('created_at', 'desc')->get();
            foreach ($applications as $key => $application) {
                if (
                    $application->schoolfeesloan == null && $application->happybirthdayloan == null && $application->loanapplication == null
                    &&  $application->carloan == null && $application->foundersdayloan == null && $application->christmasloan == null
                    && $application->easterloan == null && $application->emergencyloan == null && $application->otherloan == null
                    && $application->longtermloan == null
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
            $userapplications = ApplicationDetails::where(['user_id' => $request->user()->id])->with(['user', 'happybirthdayloan', 'schoolfeesloan', 'loanapplication', 'carloan', 'foundersdayloan', 'christmasloan', 'easterloan', 'emergencyloan', 'otherloan','longtermloan'])->orderBy('created_at', 'desc')->get();
            return response()->json($userapplications, 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => false,
                'message' => $error->getMessage()
            ], 500);
        }
    }


    public function UserRefundAmount(Request $request)
    {
        try {
            $userapplications = ApplicationDetails::where(['user_id' => $request->user()->id])->with([
                'happybirthdayloan'  => function ($query) {
                    $query->where('refund_amount', '<>', '0.00');
                },
                'schoolfeesloan' => function ($query) {
                    $query->where('refund_amount', '<>', '0.00');
                },
                'loanapplication' => function ($query) {
                    $query->where('refund_amount', '<>', '0.00');
                },
                'carloan'  => function ($query) {
                    $query->where('refund_amount', '<>', '0.00');
                },
                'foundersdayloan' => function ($query) {
                    $query->where('refund_amount', '<>', '0.00');
                },
                'christmasloan' => function ($query) {
                    $query->where('refund_amount', '<>', '0.00');
                },
                'easterloan' => function ($query) {
                    $query->where('refund_amount', '<>', '0.00');
                },
                'emergencyloan' => function ($query) {
                    $query->where('refund_amount', '<>', '0.00');
                },
                'otherloan' => function ($query) {
                    $query->where('refund_amount', '<>', '0.00');
                },
                'longtermloan' => function ($query) {
                    $query->where('refund_amount', '<>', '0.00');
                },
            ])->orderBy('created_at', 'desc')->get();

            $trimArray = [];
            //trim data which has null values
            foreach ($userapplications as $userapp) {
                if ($userapp->schoolfeesloan) {
                    $trimArray[] = $userapp->schoolfeesloan;
                }
                if ($userapp->happybirthdayloan) {
                    $trimArray[] = $userapp->happybirthdayloan;
                }
                if ($userapp->loanapplication) {
                    $trimArray[] = $userapp->loanapplication;
                }
                if ($userapp->carloan) {
                    $trimArray[] = $userapp->carloan;
                }
                if ($userapp->foundersdayloan) {
                    $trimArray[] = $userapp->foundersdayloan;
                }
                if ($userapp->christmasloan) {
                    $trimArray[] = $userapp->christmasloan;
                }
                if ($userapp->easterloan) {
                    $trimArray[] = $userapp->easterloan;
                }
                if ($userapp->emergencyloan) {
                    $trimArray[] = $userapp->emergencyloan;
                }
                if ($userapp->otherloan) {
                    $trimArray[] = $userapp->otherloan;
                }
                if ($userapp->longtermloan) {
                    $trimArray[] = $userapp->longtermloan;
                }
            }

            $filterArray = collect($trimArray)->filter(function ($application) {
                return   $application->refund_amount > 0.00;
            });

            $sumCallback = function ($accumulator, $currentValue) {
                return   $accumulator + $currentValue->refund_amount;
            };
            // Reduce the array to calculate the sum
            $sum = array_reduce($filterArray->values()->all(), $sumCallback, 0);

            return response()->json(
                [
                    'status' => true,
                    'data' => $sum
                ],
                200
            );
        } catch (\Exception $error) {
            return response()->json([
                'status' => false,
                'message' => $error->getMessage()
            ], 500);
        }
    }
}
