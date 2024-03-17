<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{


    public function currentUser(Request $request)
    {

        $data = $request->user();
        return response()->json([
            'status' => true,
            'data' => $data
        ], 200);
    }
    public function PendingUsers()
    {
        try {
            $users = User::where('granted_access', 0)->get();
            return response()->json($users, 200);
        } catch (Exception $error) {
            return response()->json([
                'status' => false,
                'data' => $error->getMessage()
            ]);
        }
    }



    public function ApprovedUsers()
    {
        try {
            $users = User::where('granted_access', 1)->orderBy('created_at','desc')->get();
            return response()->json($users, 200);
        } catch (Exception $error) {
            return response()->json([
                'status' => false,
                'data' => $error->getMessage()
            ]);
        }
    }

    public function show(Request $request)
    {
        try {

            $user_id = $request->route('id');
            if (!$user_id) {
                return response()->json(
                    [
                        'status' => false,
                        'data' => [],
                        'message' => 'User id does not exists'
                    ],
                    404
                );
            }

            $SpecificUserData = User::where('id', $user_id)->first();
            if (!$SpecificUserData) {
                return response()->json(
                    [
                        'status' => false,
                        'data' => [],
                        'message' => 'No results found'
                    ],
                    404
                );
            }
            return response()->json(
                [
                    'status' => true,
                    'data' => $SpecificUserData,
                    'message' => 'Successful'
                ],
                201
            );
        } catch (\Exception $error) {
            return response()->json(
                [
                    'status' => false,
                    'message' => $error->getMessage()
                ],
                500
            );
        }
    }





    //
    public function store(Request $request)
    {
        try {
            $validateUser = Validator::make(
                $request->all(),
                [
                    'firstname' => 'required',
                    'lastname' => 'required',
                    'occupation' => 'required',
                    'address' => 'required',
                    'phone' => 'required',
                    'dob' => 'required',
                    'email' => 'required',
                    'password' => 'required'
                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            } else {
                //if user email does not already exist register
                $user_email = User::where('email', $request->email)->first();
                if (!$user_email) {
                    $newUser = User::create([
                        'firstname' => $request->firstname,
                        'lastname' => $request->lastname,
                        'occupation' => $request->occupation,
                        'address' => $request->address,
                        'phone' => $request->phone,
                        'dob' => $request->dob,
                        'email' => $request->email,
                        'password' => Hash::make($request->password),
                        'role' => 'user',
                        'next_of_kin_name' => $request->next_of_kin_name,
                        'next_of_kin_phone' => $request->next_of_kin_phone,
                        'monthly_amount_contribution' => $request->monthly_amount_contribution,
                        'employee_code' => $request->employee_code,
                        'effective_date_of_contribution' => Carbon::now()
                    ]);

                    if ($newUser) {
                        return response()->json([
                            'status' => true,
                            'message' => 'Congratulations You have successfully signed up proceed to login with email and password',
                            'token' => $newUser->createToken('api-token')->plainTextToken,
                            'user' => $newUser
                        ], 201);
                    }
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'Sorry email address has already been taken'
                    ], 422);
                }
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }


    public function loginUser(Request $request)
    {
        try {
            $validateUser = Validator::make(
                $request->all(),
                [
                    'email' => 'required',
                    'password' => 'required'
                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $login_credentials = $request->only('email', 'password');

            if (Auth::attempt($login_credentials)) {
                $user = Auth::user();
                if ($user->granted_access === 0) {
                    return response()->json(
                        [
                            'status' => false,
                            'message' => 'You have not been authorised to use this application.Please contact Administrator.'
                        ],
                        403
                    );
                } else {
                    $token = $user->createToken('api-token')->plainTextToken;
                    return response()->json(
                        [
                            'status' => true,
                            'user' => $user,
                            'token' => $token,
                            'message' => 'You have successfuly logged in'
                        ],
                        200
                    );
                }
            } else {
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'incorrect User Credentials provided'
                    ],
                    200
                );
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            $userData = Validator::make(
                $request->all(),
                [
                    'firstname' => 'required',
                    'lastname' => 'required',
                    'occupation' => 'required',
                    'address' => 'required',
                    'phone' => 'required',
                    'dob' => 'required',
                    'email' => 'required',
                ]
            );

            if ($userData->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $userData->errors()
                ], 401);
            }
            $user_id = $request->route('id');
            $application = User::where('id', $user_id)->first();
            if (!$application) {
                return response()->json([
                    'status' => false,
                    'message' => 'Application id not found'
                ], 422);
            } else {
                $application->firstname = $request->firstname;
                $application->lastname = $request->lastname;
                $application->occupation = $request->occupation;
                $application->address = $request->address;
                $application->phone = $request->phone;
                $application->email = $request->email;
                $application->next_of_kin_name = $request->next_of_kin_name;
                $application->next_of_kin_phone = $request->next_of_kin_phone;
                $application->monthly_amount_contribution = $request->monthly_amount_contribution;
                $application->employee_code = $request->employee_code;
                $application->employee_code = $request->employee_code;
                if ($application->save()) {
                    return response()->json([
                        'status' => true,
                        'message' => 'User data has been successfully updated'
                    ], 201);
                }
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }


    public function Permission(Request $request)
    {
        try {
            $userData = Validator::make(
                $request->all(),
                [
                    'granted_access' => 'required',
                    'role' => 'required'
                ]
            );

            if ($userData->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $userData->errors()
                ], 401);
            }
            $user_id = $request->route('id');
            $application = User::where('id', $user_id)->first();
            if (!$application) {
                return response()->json([
                    'status' => false,
                    'message' => 'Application id not found'
                ], 422);
            } else {
                $application->granted_access = $request->granted_access;
                $application->role = $request->role;
                if ($application->save()) {
                    return response()->json([
                        'status' => true,
                        'message' => 'Permissions has been successfully set'
                    ], 201);
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
