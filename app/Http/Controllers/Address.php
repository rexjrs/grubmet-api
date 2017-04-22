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

    public function generateRandomString($length = 60) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function checkToken($user_id, $token){
        $checkToken = Tokens::where('user_id', $user_id)->where('token',$token)->first();
        if(!$checkToken){
            return false;
        }else{
            return true;
        }
    }

    public function checkAPIKey($request){
        if(!isset($request->headers->all()["authorization"])){
            return false;
        }else if(str_replace(['"','[',']'], "", json_encode($request->headers->all()["authorization"])) != $this->authkey){
            return false;
        }else{
            return true;
        }
    }

    public function checkUserToken($request){
        if(!isset($request->headers->all()['token'])){
            return false;
        }else if($this->checkToken($user_id, str_replace(['"','[',']'], "", json_encode($request->headers->all()["token"])))){
            return false;
        }else{
            return true;
        }
    }

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
