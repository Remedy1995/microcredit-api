<?php

namespace App\Http\Controllers;

use App\Models\ApplicationDetails;
use Exception;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AllApprovedUnsettledLoans extends Controller
{
    //
    public function AllSchoolFeesLoans(Request $request)
    {
        try {
            $applications = ApplicationDetails::with([
                'user' => function ($query) {
                    $query->select('id', 'firstname', 'lastname', 'phone');
                },
                'schoolfeesloan' => function ($query) {
                    $query->where(['approval_status' => "COMPLETED"]);
                }
            ])->get();

            $applications = $applications->filter(function ($application) {
                return $application->schoolfeesloan !== null;
            });
            if ($applications) {
                //filter applications which have loan settlement status as not completed

                $filterApplications  = $applications->filter(function ($query) {
                    return $query->schoolfeesloan->loan_settlement_status !== 'COMPLETED';
                });

                return response()->json($filterApplications->values()->all(), 200);
            }
        } catch (Exception $error) {
            return response()->json([
                'status' => false,
                'message' => $error->getMessage()
            ], 500);
        }
    }


    public function FilterFeesLoansBasedTime(Request $request)
    {

        $fromdate =   Carbon::createFromFormat('Y-d-m', $request->from_date)->startOfDay();
        $todate =  Carbon::createFromFormat('Y-d-m', $request->to_date)->endOfDay();
        try {
            $applications = ApplicationDetails::with([
                'user' => function ($query) {
                    $query->select('id', 'firstname', 'lastname', 'phone');
                },
                'schoolfeesloan' => function ($query) {
                    $query->where(['approval_status' => "COMPLETED"]);
                }
            ])->whereBetween('updated_at', [$fromdate, $todate])->get();

            $applications = $applications->filter(function ($application) {
                return $application->schoolfeesloan !== null;
            });

            if ($applications) {
                //filter applications which have loan settlement status as not completed
                $filterApplications  = $applications->filter(function ($query) {
                    return $query->schoolfeesloan->loan_settlement_status !== 'COMPLETED';
                });

                return response()->json($filterApplications->values()->all(), 200);
            }
        } catch (Exception $error) {
            return response()->json([
                'status' => false,
                'message' => $error->getMessage()
            ], 500);
        }
    }
}
