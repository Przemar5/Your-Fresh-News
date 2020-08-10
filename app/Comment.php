<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;

    // Table name
    protected $table = 'comments';

    // Soft delete
    // protected $softDelete = true;

    // Primary key
    public $primaryKey = 'id';

    // Timestamps
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'body'
    ];


    public function article()
    {
        return $this->belongsTo('App\Article');
    }

    public function subcomments()
    {
        return $this->hasMany('App\Comment', 'parent_comment_id');
    }

    public function parent()
    {
        return $this->belongsTo('App\Comment');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function defaultAvatar()
    {
        $user = new \App\User();
        return '/storage/' . $user->avatarPath . $user->defaultAvatar;
    }

    // comment_user_rating
    public function ratedBy()
    {
        return $this->belongsToMany('App\User', 'comment_user_rating')->withPivot('rating_id');
    }

    public function ratings()
    {
        return $this->belongsToMany('App\Rating', 'comment_user_rating')->withPivot('user_id');
    }

    public function likedBy($id)
    {
        return (bool) \App\CommentUserRating::where('comment_id', $this->id)
            ->where('user_id', $id)
            ->where('rating_id', Rating::where('type', 'like')->first()->id)->first();
    }

    public function dislikedBy($id)
    {
        return (bool) \App\CommentUserRating::where('comment_id', $this->id)
            ->where('user_id', $id)
            ->where('rating_id', Rating::where('type', 'dislike')->first()->id)->first();
    }

    // likes and dislikes
    public function likes()
    {
        return \App\CommentUserRating::where('comment_id', $this->id)
            ->where('rating_id', Rating::where('type', 'like')->first()->id);
    }

    public function dislikes()
    {
        return \App\CommentUserRating::where('comment_id', $this->id)->where('rating_id', Rating::where('type', 'dislike')->first()->id);
    }
}
