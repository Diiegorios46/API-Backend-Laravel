<?php

// php artisan make:middleware JwtMiddleware
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Helpers\JwtAuth;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    public function handle(Request $request, Closure $next): Response
    {
        // obtener token del encabezado
        $token = $request->header("Authorization");
        
        // instanciar helper
        $jwtAuth = new JwtAuth();

        // verificar el token
        if(!is_null($token)){
            $checkToken = $jwtAuth->checkToken($token);
        }else{
            return response()->json([
                "status" => "error",
                "message" => "No has enviado la cabecera de autenticacion"
            ]);
        }

        // vincular el objeto a la request
        if($checkToken){
            
            $request->user = $jwtAuth->checkToken($token , true);

            return $next($request);
        }else{
            return response()->json([
                "status" => "error",
                "message" => "Login incorrecto"
            ]);
        }
        
    }
}
