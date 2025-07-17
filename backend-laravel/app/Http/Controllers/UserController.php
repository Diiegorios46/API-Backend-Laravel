<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Contracts\Service\Attribute\Required;
use app\Models\User;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function register(Request $request){

    $name = $request->input("name",null);
    $surname = $request->input("surname",null);
    $email = $request->input("email",null);
    $nick = $request->input("nick",null);
    $password = $request->input("password",null);

    $user = [
        "name" => $name,
        "surname" => $surname,
        "email" => $email,
        "nick" => $nick,
        "password" => $password
    ];

    // elimina los espacios en blanco
    $user_trim = array_map("trim",$user);
    $user_trim["password"] = $password;

    $validator = Validator::make($user_trim, [
        "name" => "required|string|max:100",
        "surname" => "required|string|max:100",
        "email" => "required|email|unique:users",
        "nick" => "required|unique:users",
        "password" => "required"
    ]);

    $error = [
        "status" => "error" , 
        "message" => "Los datos no son validos",
        "error" => $validator->errors()
    ];

    if($validator->fails()){
        return response()->json($error);
    }else{
        return response()->json([
                "user" => $user_trim
            ]);
        }
    }



}
