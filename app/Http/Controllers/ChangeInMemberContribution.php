<?php

namespace App\Http\Controllers;

use App\Models\ChangeInMemberContributions;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChangeInMemberContribution extends Controller
{

    public function showChangeInMemberContributionById(Request $request)
    {

        $change_in_contribution_id = $request->route('id');

        $application = ChangeInMemberContributions::where('id', $change_in_contribution_id)->first();

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
    public function MakeChangeMemberContribution(Request $request)
    {

        try {

            //before we make a change in member contribution request let check whether there is an existing change in membership request  for the user

            $checkExistingRequest = ChangeInMemberContributions::where(['approval_status' => 'PENDING', 'user_id' => $request->user()->id])->first();

            if ($checkExistingRequest) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'Sorry you have a pending change in member request approval'
                    ], 403
                );
            }


            $userData = Validator::make(
                $request->all(),
                [
                    'effective_date_of_contribution' => 'required',
                    'monthly_amount_contribution' => 'required',
                    
                ]
            );

            if ($userData->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $userData->errors()
                ], 401);
            }
            $changeInMemberContributions = ChangeInMemberContributions::create([
                'user_id' => $request->user()->id,
                'application_status' => 'IN-PROGRESS',
                'approval_status' => 'PENDING',
                'monthly_amount_contribution' => $request->monthly_amount_contribution,
                'effective_date_of_contribution' => $request->effective_date_of_contribution,
                'employee_code' => $request->user()->employee_code
            ]);


            if ($changeInMemberContributions) {
                return response()->json([
                    'status' => true,
                    'message' => 'Change in Member Contributions Submitted Successfully wait for Approval'
                ], 201);


            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }


    public function GetPendingChangeInMemberContributions()
    {

        try {
            $applications = ChangeInMemberContributions::with('user')->get();

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


    public function ApproveChangeInMemberApplication(Request $request)
    {

        try {
            $ChangeInMemberContribution = Validator::make(
                $request->all(),
                [
                    'application_status' => 'required',
                    'approval_status' => 'required',
                    'user_id' =>'required'
                ]
            );

            if ($ChangeInMemberContribution->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $ChangeInMemberContribution->errors()
                ], 401);
            }

            $ChangeInMemberContributionId = $request->route('id');
            $application_status = $request->application_status;
            $approval_status = $request->approval_status;
            $comment = $request->comment;
            $application = ChangeInMemberContributions::where('id', $ChangeInMemberContributionId)->first();

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
                    //after the admin approves let update the existing amount contribution for the user in the database
                    $user_id = $request->user_id;
                    $userapplication = User::where('id', $user_id)->first();  
                    //return $userapplication; 
                    $userapplication->monthly_amount_contribution = $request->monthly_amount_contribution;
                    if ($userapplication->save()) {
                        return response()->json([
                            'status' => true,
                            'message' => 'Change In Member Application has been successfully closed'
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