<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Address;
use App\Users;
use App\Tokens;

class Address extends Controller
{
    // ============================= Helper Functions =============================

    public function __construct()
    {
        $this->authkey = 'jkh89sdf87bjkrgknl234jksdf09sdkl235lksaf90safkjl23';
    }

    include 'Helper.php';

    // ============================= Base API Structure =============================

    public function exampleApi(Request $request){
        // Get Input

        // Check API Key
        if(!$this->checkAPIKey($request)){
            return response()->json("Authorization token error",401); 
        }
        // Default Response
        $message = [
            "status" => "fail",
            "message" => "API error",
            "response" => ""
        ];
        // Validate Params

        // Check Token
        if(!$this->checkUserToken($request)){
            return response()->json("User Token error",401); 
        }
        // Run API Function without User Token

    }

    // ============================= API Functions =============================
}
