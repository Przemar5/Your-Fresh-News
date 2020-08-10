<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rating extends Model
{
    use SoftDeletes;

    // Table name
    protected $table = 'ratings';

    // Soft delete
    // protected $softDelete = true;

    // Primary key
    public $primaryKey = 'id';

    // Timestamps
    public $timestamps = true;


    public function articles()
    {
    	return $this->belongsToMany('App\Article', 'article_user_rating')->withPivot('user_id');
    }

    public function comments()
    {
        return $this->belongsToMany('App\Comment', 'comment_user_rating')->withPivot('user_id');
    }

    public function articleUsers()
    {
        return $this->belongsToMany('App\User', 'article_user_rating')->withPivot('article_id');
    }

    public function articleRated()
    {
        return $this->belongsToMany('App\Article', 'article_user_rating')->withPivot('user_id');
    }

    public function commentUsers()
    {
        return $this->belongsToMany('App\User', 'comment_user_rating')->withPivot('article_id');
    }

    public function commentRated()
    {
        return $this->belongsToMany('App\Comment', 'comment_user_rating')->withPivot('user_id');
    }
}
