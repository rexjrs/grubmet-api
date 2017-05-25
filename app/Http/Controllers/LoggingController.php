<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Request;
use App\Users;
use App\Tokens;
use App\Logging;
use Helpers;

class LoggingController extends Controller
{
    public function addLog(Request $request){
        // Get Input
        $image = $request->get('image');
        $type = $request->get('type');
        $date = $request->get('date');
        $desc = $request->get('desc');
        $cals = $request->get('cals');
        if(!$desc){
            $desc = "";
        }
        if(!$cals){
        	$cals = 0;
        }
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
        }else if(!$type){
            $message["message"] = "Missing type";
            return response()->json($message,400);
        }else if(!$date){
            $message["message"] = "Missing date";
            return response()->json($message,400);
        }

        $loggingString = Helpers::mealID();
        $imageConverted = base64_decode($image);
        $image_name= $loggingString.'.jpeg';
        $path = public_path() . "/images/" . $image_name;
        file_put_contents($path, $imageConverted);

        Logging::create([
            "image" => $image_name,
            "type" => $type,
            "date" => $date,
            "description" => $desc,
            "cals" => $cals
        ]);
        $message["status"] = "ok";
        $message["message"] = "Logged Uploaded";
        $message["response"] = [
            "image" => $image_name,
        ];
        return response()->json($message,200);
    }
}
