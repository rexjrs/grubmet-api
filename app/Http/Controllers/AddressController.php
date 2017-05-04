<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Address;
use App\Users;
use App\Tokens;
use Helpers;

class AddressController extends Controller
{
    // ============================= Construct =============================

    public function __construct()
    {

    }

    // ============================= Base API Structure =============================

    public function exampleApi(Request $request){
        // Get Input

        // Check API Key
        if(!Helpers::checkAPIKey($request)){
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
        if(!Helpers::checkUserToken($request)){
            return response()->json("User Token error",401); 
        }
        // Run API Function

    }

    // ============================= API Functions =============================

    public function newAddress(Request $request){
        // Get Input
        $user_id = $request->get('user_id');
        $first_name = $request->get('first_name');
        $last_name = $request->get('last_name');
        $line_one = $request->get('line_one');
        $line_two = $request->get('line_two');
        $city = $request->get('city');
        $country = $request->get('country');
        $zip_code = $request->get('zip_code');
        $phone = $request->get('phone');
        // Check API Key
        if(!Helpers::checkAPIKey($request)){
            return response()->json("Authorization token error",401); 
        }
        // Default Response
        $message = [
            "status" => "fail",
            "message" => "API error",
            "response" => ""
        ];
        // Validate Params
        if(!$user_id){
            $message["message"] = "Missing user id";
            return response()->json($message,400);
        }else if(!$first_name){
            $message["message"] = "Missing fist name";
            return response()->json($message,400);
        }else if(!$last_name){
            $message["message"] = "Missing last name";
            return response()->json($message,400);
        }else if(!$line_one){
            $message["message"] = "Missing line_one";
            return response()->json($message,400);
        }else if(!$city){
            $message["message"] = "Missing city";
            return response()->json($message,400);
        }else if(!$country){
            $message["message"] = "Missing country";
            return response()->json($message,400);
        }else if(!$zip_code){
            $message["message"] = "Missing zip code";
            return response()->json($message,400);
        }else if(!$phone){
            $message["message"] = "Missing phone number";
            return response()->json($message,400);
        }
        // Check Token
        if(!Helpers::checkUserToken($request)){
            return response()->json("User Token error",401); 
        }
        // Run API Function
        Address::create([
            "user_id" => $user_id,
            "first_name" => $first_name,
            "last_name" => $last_name,
            "line_one" => $line_one,
            "line_two" => $line_two,
            "city" => $city,
            "country" => $country,
            "zip_code" => $zip_code,
            "phone" => $phone
        ]);

        $message = [
            "status" => "ok",
            "message" => "Address was added",
            "response" => ""
        ];
        return response()->json($message,200);
    }
}
