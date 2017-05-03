<?php

class Helpers
{
	public static function test(){
		return "hellow";
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
}