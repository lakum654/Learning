<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Post extends Model
{
    use SoftDeletes;
    
    protected $fillable = ['title','desc','user_id','like'];
    protected $dates = ['deleted_at'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function users(){
        return $this->belongsToMany(User::class,'favorite_post');
    }

    public function comments(){
        return $this->hasMany(Comment::class)->orderBy('created_at','desc');
    }
}
