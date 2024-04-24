<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    # A post belongs to a user
    # Use this method to get the owner of the post
    public function user(){
        return $this->belongsTo(User::class)->withTrashed();
    }

    # Use this method to get the name of the categories under a post
    public function categoryPost(){
        return $this->hasMany(CategoryPost::class);
    }

    # Use this method to get all the comments of the post
    public function comments(){
        return $this->hasMany(Comment::class);
    }

    # Use this to get the likes of the post
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    # Return TRUE  if the AUTH user already like the post
    public function isLiked()
    {
        return $this->likes()->where('user_id', Auth::user()->id)->exists();
    }
}
