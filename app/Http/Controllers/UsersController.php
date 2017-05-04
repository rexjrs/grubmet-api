<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Users;
use App\Tokens;
use App\Meals;
use Helpers;

class UsersController extends Controller
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
        
        // Output
        // $message = [
        //     "status" => "ok",
        //     "message" => "",
        //     "response" => ""
        // ];
        return response()->json($message,200);
    }

    // ============================= API Functions =============================

    public function uploadProfilePic(Request $request){
        // Get Input
        $user_id = $request->get('user_id');
        $image = $request->get('profile_image');
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
        }else if(!$image){
            $message["message"] = "Missing image";
            return response()->json($message,400);
        }
        // Check Token
        if(!Helpers::checkUserToken($request)){
            return response()->json("User Token error",401); 
        }
        // Run API Function
        $profileString = Helpers::mealID();
        $imageConverted = base64_decode($image);
        $image_name= $profileString.'.jpeg';
        $path = public_path() . "/images/" . $image_name;
        file_put_contents($path, $imageConverted);

        Users::where('id',$user_id)->update([
            "profile_image" => $image_name
        ]);
        $message["status"] = "ok";
        $message["message"] = "Image Uploaded";
        $message["response"] = [
            "image" => $image_name,
        ];
        return response()->json($message,200);
    }

    public function addProfile(Request $request){
        // Get Input
        $user_id = $request->get('user_id');
        $email = $request->get('email');
        $first_name = $request->get('first_name');
        $last_name = $request->get('last_name');
        $dob = $request->get('dob');
        $gender = $request->get('gender');
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
        }else if(!$email){
            $message["message"] = "Missing email";
            return response()->json($message,400);
        }else if(!$first_name){
            $message["message"] = "Missing first name";
            return response()->json($message,400);
        }else if(!$last_name){
            $message["message"] = "Missing last name";
            return response()->json($message,400);
        }else if(!$dob){
            $message["message"] = "Missing dob";
            return response()->json($message,400);
        }else if(!$gender){
            $message["message"] = "Missing gender";
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
        Users::where('id',$user_id)->update([
            "email" => $email,
            "firstname" => $first_name,
            "lastname" => $last_name,
            "dob" => $dob,
            "gender" => $gender,
            "phone" => $phone
        ]);
        // Output
        $message = [
            "status" => "ok",
            "message" => "Profile added",
            "response" => ""
        ];
        return response()->json($message,200);
    }

    public function editProfile(Request $request){
        // Get Input
        $user_id = $request->get('user_id');
        $phone = $request->get('phone');
        $email = $request->get('email');
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
        }else if(!$phone){
            $message["message"] = "Missing phone number";
            return response()->json($message,400);
        }else if(!$email){
            $message["message"] = "Missing email";
            return response()->json($message,400);
        }
        // Check Token
        if(!Helpers::checkUserToken($request)){
            return response()->json("User Token error",401); 
        }
        // Run API Function
        Users::where('id',$user_id)->update([
            "phone" => $phone,
            "email" => $email
        ]);
        // Output
        $message = [
            "status" => "ok",
            "message" => "Phone number updated",
            "response" => ""
        ];
        return response()->json($message,200);
    }

    public function normalLogin(Request $request){
        // Get Input
        $username = $request->get('username');
        $password = $request->get('password');
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
                $token = Helpers::generateRandomString();
                Tokens::create([
                    "user_id" => $findUser['id'],
                    "token" => $token
                ]);
                $message["status"] = "ok";
                $message["message"] = "Login successful";
                $message["response"] = [
                    "token" => $token,
                    "user_id" => $findUser['id']
                ];
                return response()->json($message,200);
            }
        }
    }

    public function socialLogin(Request $request){
        // Get Input
        $social_id = $request->get('social_id');
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
                $token = Helpers::generateRandomString();
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


    // ======================= For Alice's App =============================

    public function mealLog(Request $request){
        // Get Input
        $image = $request->get('image');
        $mealType = $request->get('mealType');
        $date = $request->get('date');
        // Default Response
        $message = [
            "status" => "fail",
            "message" => "API error",
            "response" => ""
        ];
        // Validate Params
        if(!$image){
            $message["message"] = "Missing image";
            return response()->json($message,400);
        }else if(!$mealType){
            $message["message"] = "Missing mealType";
            return response()->json($message,400);
        }else if(!$date){
            $message["message"] = "Missing date";
            return response()->json($message,400);
        }

        if($mealType != "snack"){
            $checkMeal = Meals::where('date',$date)->where('meal',$mealType)->first();
            if($checkMeal){
                Meals::where('date',$date)->where('meal',$mealType)->delete();
            }
        }

        $mealstring = Helpers::mealID();
        $imageConverted = base64_decode($image);
        $image_name= $mealstring.'.jpeg';
        $path = public_path() . "/images/" . $image_name;
        file_put_contents($path, $imageConverted);

        Meals::create([
            "image" => $image_name,
            "meal" => $mealType,
            "date" => $date
        ]);
        $message["status"] = "ok";
        $message["message"] = "Image Uploaded";
        $message["response"] = [
            "image" => $image_name,
        ];
        return response()->json($message,200);
    }

    public function getDay(Request $request){
        $date = $request->get('date');
        // Default Response
        $message = [
            "status" => "fail",
            "message" => "API error",
            "response" => ""
        ];
        // Validate Params
        if(!$date){
            $message["message"] = "Missing date";
            return response()->json($message,400);
        }
        $mealbreakfast = Meals::where('date',$date)->where('meal','breakfast')->pluck('image');
        $meallunch = Meals::where('date',$date)->where('meal','lunch')->pluck('image');
        $mealdinner = Meals::where('date',$date)->where('meal','dinner')->pluck('image');
        $mealsnacks = Meals::where('date',$date)->where('meal','snack')->get();
        if(!$mealbreakfast){
            $mealbreakfast = "";
        }
        if(!$meallunch){
            $meallunch = "";
        }
        if(!$mealdinner){
            $mealdinner = "";
        }
        $message["status"] = "ok";
        $message["message"] = "Fetched daily meals";
        $message["response"] = [
            "breakfast" => $mealbreakfast,
            "lunch" => $meallunch,
            "dinner" => $mealdinner,
            "snacks" => $mealsnacks
        ];
        return response()->json($message,200);
    }
}
