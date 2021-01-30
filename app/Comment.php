<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Comment extends Model
{
    protected $softDelete = true;
    protected $fillable = ['post_id','comment','user_id'];

    public function post(){
        return $this->belongsTo(Post::class,'post_id','id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
}
