<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Reply extends Model
{
    protected $softDelete = true;


    public function comment(){
        return $this->belongsTo(Comment::class,'comment_id','id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
}
