<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Users;
use App\Tokens;

class UsersController extends Controller
{
    // ============================= Helper Functions =============================
    // Testing
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

    public function normalLogin(Request $request){
        // Get Input
        $username = $request->get('username');
        $password = $request->get('password');
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
        if(!$username){
            $message["message"] = "Missing username";
            return response()->json($message,400);
        }else if(!$password){
            $message["message"] = "Missing password";
            return response()->json($message,400);
        }else{
            $findUser = Users::where('username',$username)->where('password',$password)->first();
            if(!$findUser){
                $message["message"] = "Incorrect username or password supplied";
                return response()->json($message,400);
            }else{
                $checkToken = Tokens::where('user_id', $findUser['id']);
                if($checkToken){
                    Tokens::where('user_id',$findUser['id'])->delete();
                }
                $token = $this->generateRandomString();
                Tokens::create([
                    "user_id" => $findUser['id'],
                    "token" => $token
                ]);
                $message["status"] = "ok";
                $message["message"] = "Login successful";
                $message["response"] = [
                    "token" => $token,
                ];
                return response()->json($message,200);
            }
        }
    }

    public function socialLogin(Request $request){
        // Get Input
        $social_id = $request->get('social_id');
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
        if(!$social_id){
            $message["message"] = "Missing social id";
            return response()->json($message,400);
        }else{
            $checkSocial = Users::where('social_id',$social_id)->first();
            if(!$checkSocial){
                $message["message"] = "Account is not registered";
                return response()->json($message,400);
            }else{
                $checkToken = Tokens::where('user_id', $checkSocial['id']);
                if($checkToken){
                    Tokens::where('user_id',$checkSocial['id'])->delete();
                }
                $token = $this->generateRandomString();
                Tokens::create([
                    "user_id" => $checkSocial['id'],
                    "token" => $token
                ]);
                $message["status"] = "ok";
                $message["message"] = "Login successful";
                $message["response"] = [
                    "token" => $token,
                ];
                return response()->json($message,200);
            }
        }
    }

    public function registerNormal(Request $request){
        // Get Input
        $username = $request->get('username');
        $password = $request->get('password');
        $email = $request->get('email');
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
    	if(!$email){
    		$message["message"] = "Missing email";
    		return response()->json($message,400);
    	}else if(!$username){
            $message["message"] = "Missing username";
            return response()->json($message,400);
        }else if(!$password){
            $message["message"] = "Missing password";
            return response()->json($message,400);
        }else{
            $checkUser = Users::where('username',$username)->first();
            if(!$checkUser){
                Users::create([
                    "username" => $username,
                    "email" => $email,
                    "password" => $password,
                    "account_type" => "normal"
                ]);
                $message["status"] = "ok";
                $message["message"] = "Normal account created successfully";
                $message["response"] = [
                    "username" => $username,
                    "password" => $password,
                    "email" => $email,
                    "account_type" => "normal"
                ];
                return response()->json($message,200);
            }else{
                $message["message"] = "This username has been taken";
                return response()->json($message,400);
            }
    	}
    }

    public function registerSocial(Request $request){
        // Get Input
        $firstname = $request->get('firstname');
        $lastname = $request->get('lastname');
        $account = $request->get('account_type');
        $social_id = $request->get('social_id');
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
        if(!$firstname){
            $message["message"] = "Missing first name";
            return response()->json($message,400);
        }else if(!$lastname){
            $message["message"] = "Missing last name";
            return response()->json($message,400);
        }else if(!$account){
            $message["message"] = "Missing account type";
            return response()->json($message,400);
        }else if(!$social_id){
            $message["message"] = "Missing social id";
            return response()->json($message,400);
        }else{
            $checkID = Users::where('social_id', $social_id)->first();
            if(!$checkID){
                if($account == "google"){
                    Users::create([
                        "firstname" => $firstname,
                        "lastname" => $lastname,
                        "social_id" => $social_id,
                        "account_type" => $account
                    ]);
                    $message["status"] = "ok";
                    $message["message"] = "Google account created successfully";
                    $message["response"] = [
                        "firstname" => $firstname,
                        "lastname" => $lastname,
                        "social_id" => $social_id,
                        "account_type" => $account
                    ];
                    return response()->json($message,200);
                }else if($account == "facebook"){
                    Users::create([
                        "firstname" => $firstname,
                        "lastname" => $lastname,
                        "social_id" => $social_id,
                        "account_type" => $account
                    ]);
                    $message["status"] = "ok";
                    $message["message"] = "Facebook account created successfully";
                    $message["response"] = [
                        "firstname" => $firstname,
                        "lastname" => $lastname,
                        "social_id" => $social_id,
                        "account_type" => $account
                    ];
                    return response()->json($message,200);
                }else{
                    $message["message"] = "Incorrect account type supplied";
                    return response()->json($message,400);
                }
            }else{
                $message["message"] = "This social media id is already registered";
                return response()->json($message,400);
            }
        }
    }
}
