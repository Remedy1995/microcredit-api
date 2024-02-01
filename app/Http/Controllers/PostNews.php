<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class PostNews extends Controller
{

    public function fetchResults(Request $request)
    {
        try{
            $fetchData = News::orderBy('created_at', 'desc')->take(3)->get();
            return response()->json($fetchData, 200);
        }
        catch(\Exception $error){
            return response()->json([
                'status' => false,
                'message' => $error->getMessage()
            ], 500);
        }
       
    }


    //
    public function PostNews(Request $request)
    {

        try {
            $newsValidation = Validator::make(
                $request->all(),
                [
                    'postTitle' => 'required',
                    'postDescription' => 'required'
                ]);


            if ($newsValidation->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $newsValidation->errors()
                ], 401);
            }         
            $createNews = News::create([
                'postTitle' => $request->postTitle,
                'postDescription' => $request->postDescription
            ]);

            if ($createNews) {
                return response()->json([
                    'status' => true,
                    'message' => 'News has been successfully created'
                ], 201);
            }

        } catch (\Exception $error) {
            return response()->json([
                'status' => false,
                'message' => $error->getMessage()
            ], 500);
        }
    }

}
