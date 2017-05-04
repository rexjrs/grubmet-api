<?php

use App\Tokens;

class Helpers
{
	// Used for generating login token
    public static function generateRandomString($length = 60) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    // Used to check tokens for checkUserToken function
    public static function checkToken($user_id, $token){
        $checkToken = Tokens::where('user_id', $user_id)->where('token',$token)->first();
        if(!$checkToken){
            return true;
        }else{
            return false;
        }
    }

    // Used to check headers
    public static function checkAPIKey($request){
        if(!isset($request->headers->all()["authorization"])){
            return false;
        }else if(str_replace(['"','[',']'], "", json_encode($request->headers->all()["authorization"])) != 'jkh89sdf87bjkrgknl234jksdf09sdkl235lksaf90safkjl23'){
            return false;
        }else{
            return true;
        }
    }

    // Used to check tokens sent in the headers
    public static function checkUserToken($request){
        if(!isset($request->headers->all()['token'])){
            return false;
        }else if(self::checkToken($request->get('user_id'), str_replace(['"','[',']'], "", json_encode($request->headers->all()["token"])))){
            return false;
        }else{
            return true;
        }
    }
}