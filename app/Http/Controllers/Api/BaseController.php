<?php

namespace App\Http\Controllers\Api;
use app\Http\Controllers\Controller as Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    public function sendResponse($result, $message){ // fun sends theResponse to user with result and massage
       $response = [


        'success' => true,
        'data' => $result, //the date that comes from the database is stored  in result variable
        'message' =>$message
        //succes is Key and true is  value
       ] ;


       return response()->json( $response, 200); // 200 is http protocol // send data to user as json

    }

    public function sendError($error, $errorMessage=[], $code = 404){
        $response = [


         'success' => false,
        //  'data' => $error,
         'message' =>$error

        ];
        if(!empty($errorMessage))
        {
            $response['data']=$errorMessage;
        }
        return response()->json($response, $code);
}
}
