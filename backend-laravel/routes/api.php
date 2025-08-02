<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\JwtMiddleware;
use App\Http\Controllers\ArticleController;

// rutas publicas de usuarios
Route::post("/user/register" , [UserController::class, "register"]);
Route::post("/user/login" , [UserController::class, "login"]);
Route::get("/user/profile/{id}" , [UserController::class, "profile"]);
Route::get("/user/avatar/{file}" , [UserController::class, "avatar"]);


// rutas publicas de articulos
Route::get("/article/items/{page}" , [ArticleController::class, "articles"]);
Route::get("/article/item/{id}" , [ArticleController::class, "article"]);
Route::get("/article/search/{searchString}" , [ArticleController::class, "search"]);

Route::get("/article/user/{user_id}" , [ArticleController::class, "articleByUser"]);
Route::get("/article/poster/{file}" , [ArticleController::class, "poster"]);


Route::middleware([JwtMiddleware::class])->group(function () {

    // rutas para usuarios
    Route::put("/user/update" , [UserController::class, "update"]);
    // Tuve que configurar en filesystems para guardar en una carpeta el avatar 
    Route::post("/user/upload" , [UserController::class, "upload"]);

    // rutas para articulos
    Route::post("/article/save" , [ArticleController::class , "save"]);
    Route::post("/article/upload/{id}" , [ArticleController::class, "upload"]);
    Route::delete("/article/delete/{id}" , [ArticleController::class, "delete"]);
    Route::put("/article/update/{id}" , [ArticleController::class, "update"]);


});