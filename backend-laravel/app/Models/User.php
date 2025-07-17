<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = "users";

    protected $fillable = [
    "name" , 
    "surname" ,  
    "nick" , 
    "email" ,
    "avatar" ,
    "bio" ,
    "password" , 
    "created_at" ,
    "updated_at" 
    ];

    
}
