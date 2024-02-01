<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestDb extends Controller
{
    //

    public function  TestDbConnection(Request $request){
        try {
            DB::connection()->getPdo();
            return "Successfully connected to the database.";
        } catch (\Exception $e) {
            return "Could not connect to the database. Error: " . $e->getMessage();
        }
    }
}
