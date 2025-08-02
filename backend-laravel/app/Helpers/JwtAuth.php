<?php

namespace App\Helpers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use DomainException;

class JwtAuth{

    // clave secreta
    private $key ;
    // iniciarlizar clave secreta en un constructor
    public function __construct()
    {
        $this->key = "clave-privada-api-backend-key";
    }

    // generar token con jwt para el user auth
    public function auth($user, $getUser = null){

        $signup = false;

        if(is_object($user)){
            $signup = true;
        }

        if($signup){

            $payload = [
                "sub" => $user->id,
                "email" => $user->email , 
                "name" => $user->name , 
                "surname" => $user->surname ,
                "nick" => $user->nick ,
                "bio" => $user->bio , 
                "avatar" => $user->avatar , 
                "created_at" => $user->created_at , 
                "iat" => time(),
                "exp" => time() + (30 * 24 * 60 * 60)
            ];

            $jwt = JWT::encode($payload , $this->key , "HS256");

            if(is_null($getUser)){
                $data = $jwt;
            }else{
                $user_decoded = JWT::decode($jwt , new Key($this->key, "HS256"));

                $data = $user_decoded;
            }

        }else{
            $data = [
                "status" => "error" , 
                "message" => "login fallido"
            ];
        }

        return $data;

    }
    // verificar la validez de un token 
    public function checkToken($jwt , $getIdentify = false){
        
        $auth = false;
        $decoded = false;

        try {
            $decoded = JWT::decode($jwt , new Key($this->key , "HS256"));
        } catch (\UnexpectedValueException $e) {
            $auth = false;
        } catch(\DomainException $e){
            $auth = false;
        }
        
        if($decoded && !empty($decoded) && is_object($decoded) && isset($decoded->sub)){
            $auth = true;
        }

        if($getIdentify){
            return $decoded ;
        }

        return $auth;

    }

}