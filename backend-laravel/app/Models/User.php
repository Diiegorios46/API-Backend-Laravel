<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = "users";

    // estos son los datos que tiene el objeto 
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

    // estos campos no se muestran en el json 
    protected $hidden = [
        "password",
        "updated_at",
    ];

    
}
