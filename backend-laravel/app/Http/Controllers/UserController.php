<?php

namespace App\Http\Controllers;

use App\Helpers\JwtAuth;

use Illuminate\Http\Request;
use Symfony\Contracts\Service\Attribute\Required;
use App\Models\User;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;
use LDAP\Result;

class UserController extends Controller
{
    public function messageError($validator)
    {
        $error = [
            "status" => "error" , 
            "message" => "Los datos no son validos",
            "error" => $validator->errors()
        ];
        
        return $error;
    }

    public function register(Request $request)
    {

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

        $error = $this->messageError($validator);

        if($validator->fails()){
            return response()->json($error);
        }else{

            $pwd = password_hash($user_trim["password"] , PASSWORD_BCRYPT , ["cost" => 5]);

            $user = new User;

            $user->name = $user_trim["name"];
            $user->surname = $user_trim["surname"];
            $user->email = $user_trim["email"];
            $user->nick = $user_trim["nick"];
            $user->password = $pwd;


            $user->save();

            return response()->json([
                    "status" => "success" ,
                    "message" => "usuario registrado" , 
                    "user" => $user_trim
                ]);
            }
    }
    
    public function login(Request $request) 
    {

        //crear una instancia de JWT
        $jwtAuth = new JwtAuth();

        //recoger los datos del usuario
        $email = $request->input("email",null);
        $password = $request->input("password",null);
        $getUser = $request->input("getUser" , null);

        $user = [ "email" => $email,"password" => $password];
        
        $validator = Validator::make($user, [
            "email" => "required|email",
            "password" => "required"
        ]);
        
        $message = $this->messageError($validator);

        if($validator->fails()){
            
            return response()->json($message);

        }else{

            //busca por bd al usuario
            $user = User::where([
                "email" => $email
            ])->first();

            $pwdVerify = password_verify($password,$user->password);
            
            if($pwdVerify){

                if(is_null($getUser)){
                    return $jwtAuth->auth($user); 
                }else{
                     return $jwtAuth->auth($user,true); 
                }

            }

            return response()->json([
                    "status" => "success" ,
                    "message" => "usuario logeado",
            ]);
            
        }
        
    }

    public function update(Request $request)
    {
        
        // obtener los datos del usuario autenticado
        $identity = $request->user;

        $name = $request->input("name" , $identity->name);
        $surname = $request->input("surname" , $identity->surname);
        $email = $request->input("email" , $identity->email);
        $nick = $request->input("nick" , $identity->nick);
        $bio = $request->input("bio" , $identity->bio);

        //array para validar
        $userToUpdate = [
            "name" => $name,
            "surname" => $surname,
            "email" => $email,
            "nick" => $nick,
            "bio" => $bio
        ];

        // asigna null si no existe la constraseña
        $password = $request->input("password" , null);

        if(!is_null($password)){
            $pwd = password_hash($password , PASSWORD_BCRYPT , ["cost" , 5]);

            $userToUpdate["password"] = $pwd;
        }

        // validar los datos
        $validator = validator::make($userToUpdate , [ 
            "name" => "required" , 
            "surname" => "required|alpha" , 
            "email" => "required|email|unique:users,email," . $identity->sub,
            "nick" => "required|unique:users,nick," . $identity->sub,
        ]);

        if($validator->fails()){
            $data = [
                "status" => "error" , 
                "message" => "los datos no se han validado correctamente",
                "error" => $validator->errors()
            ];
        }else{
                        
            try {

                $userUpdated = User::where("id", $identity->sub)->update($userToUpdate);

                $data = [
                    "status" => "success",
                    "user" => $userToUpdate
                ];

            } catch (\Exception $e) {

                $data = [
                    "status" => "error",
                    "message" => "Ocurrió un error al actualizar el usuario.",
                ];
                
            }
            
        }

        return response()->json([$data]);
        
    }

    public function profile(Request $request, $id)
    {
        try {

            $user = User::where("id", $id)->first();

            if (!$user) {
                return response()->json([
                    "status" => "error",
                    "message" => "El usuario no existe en la base de datos"
                ], 404);
            }

            // hace el makeHidden porque podria dar null si no encuentra al user
            $user->makeHidden(["password", "updated_at"]);

            return response()->json([
                "status" => "success",
                "user" => $user
            ]);
            
        } catch (\Exception $e) {

            return response()->json([
                "status" => "error",
                "message" => "Ocurrió un error al obtener el perfil",
            ], 500);

        }
    }

    public function upload(Request $request)
    {

        // sacar la imagen enviada por el cliente
        $image = $request->file("file0");

        // sacar los datos del usuario autenticado
        $identity = $request->user;

        // validar archivos
        $validator = Validator::make($request->all() , [
            "file0" => "required|image|mimes:jpg,jpeg,gif,png"
        ]);

        // comprobar que la validacion es buena
        if($image && empty($validator->fails())){
            
            //generar un nombre para el archivo
            $image_name = time() . $image->getClientOriginalName();
            
            // guardar la imagen en el disco
            \Storage::disk("avatars")->put($image_name , \File::get($image));

            // buscar el usuario por su id 
            $user = User::find($identity->sub);

            // si ya tiene avatar borrralo
            if($user && $user->avatar && \Storage::disk("avatars")->exists($user->avatar)){
                \Storage::disk("avatars")->delete($user->avatar);
            }
            // actualiza el avatar del usaurio
            if($user){
                $user->avatar = $image_name;
                $user->save();
            }else{
                $data = [
                    "status" => "error" , 
                    "message" => "Usuario no encontrado",
                ];
            }
        

            $data = [
                "status" => "success" , 
                "image" => $identity
            ];

        }else{

            $data = [
                "status" => "error" , 
                "image" => "N"
            ];

        }




        return response()->json($data);
    }

    public function avatar($file){
        $disk = \Storage::disk("avatars");

        if($disk->exists($file)){
            $path = $disk->path($file);
            return response()->file($path);

        }else{
            return response()->json([
                "status" => "error" ,
                "message" => "imagen no encontrada"
            ]);
        }
    }

    
}
