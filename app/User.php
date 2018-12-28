<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Topic;
use App\Post;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function ownsTopic(Topic $topic){
        return $this->id === $topic->user->id;
    }

    public function ownsPost(Post $post){
        return $this->id === $post->user->id;
    }

    public function hasLikedPost(Post $post){
        return $post->likes->where('user_id',$this->id)->count() === 1;
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function instagram(){
        return $this->hasOne(Instagram::class, 'user_id', 'id');
    }

}
