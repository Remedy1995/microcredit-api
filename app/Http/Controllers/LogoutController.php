<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LogoutController extends Controller
{
    //

    public function Logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

    }
}


