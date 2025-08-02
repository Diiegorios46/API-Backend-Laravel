<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $table = "articles";

    protected $fillable = [
        "title" , 
        "content" ,  
        "user_id" , 
        "image" ,
        "created_at" ,
        "updated_at" 
    ];

    // especificar relaciones
    public function user(){
        return $this->belongsTo("App\Models\User","user_id");
    }

}
