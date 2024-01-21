<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;
    use SoftDeletes;

    public const AVATAR_PATH = '/images/avatars/';
    public const DEFAULT_AVATAR = 'nophoto.png';

    public $avatarPath = '/images/avatars/';
    public $defaultAvatar = 'nophoto.png';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'login', 'name', 'surname', 'email', 'password', 'avatar', 'info',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Soft delete
    // protected $softDelete = true;
    

    // public function sendEmailVerificationNotification()
    // {
    //     $this->notify(new \App\Notifications\CustomVerifyEmail);
    // }


    public static function writers()
    {
        return self::whereHas('roles', function ($query) {
            $query->where('name', 'writer');
        })->get();
    }

    public function articles()
    {
        return $this->hasMany('App\Article');
    }

    public function comments()
    {
        return $this->hasMany('App\Comment', 'user_comment', 'user_id', 'comment_id');
    }

    public function image()
    {
        return $this->belongsTo('App\Image', 'user_image', 'user_id', 'image_id');
    }

    public function roles()
    {
        return $this->belongsToMany('App\Role', 'user_role');
    }

    public function hasRoles()
    {
        $id = $this->id;
        return \App\Role::whereHas('users', function ($query) use ($id) {
            $query->where('user_id', $id);
        })->get();
    }

    public function is($role)
    {
        $id = $this->id;
        return ! \App\Role::whereHas('users', function ($query) use ($id) {
            $query->where('user_id', $id);
        })->where('name', $role)->get()->isEmpty();
    }

    // public function avatar()
    // {
    //     $filePath = $this->avatarPath . $this->avatar;
    //     return \Illuminate\Support\Facades\Storage::disk('local')->url($this->avatar);
    // }

    public function avatar()
    {
        return \Illuminate\Support\Facades\Storage::disk('assets')->url('avatars/' . $this->avatar);
        // return env('APP_URL') . '/public/images/avatars/' . $this->avatar;
    }

    public function articleRatings()
    {
        return $this->belongsToMany('App\Rating', 'article_user_rating')->withPivot('article_id');
    }

    public function articleRated()
    {
        return $this->belongsToMany('App\Article', 'article_user_rating')->withPivot('rating_id');
    }

    public function commentRatings()
    {
        return $this->belongsToMany('App\Rating', 'comment_user_rating')->withPivot('comment_id');
    }

    public function commentRated()
    {
        return $this->belongsToMany('App\Comment', 'comment_user_rating')->withPivot('rating_id');
    }

    // public function commentRatings()
    // {
    //     return $this->belongsToMany('App\Rating', 'comment_user_rating', 'user_id', 'rating_id');
    // }
}
