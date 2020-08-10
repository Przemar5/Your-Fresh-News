<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CommentUserRating extends Model
{
    use SoftDeletes;

    // Table name
    protected $table = 'comment_user_rating';

    // Timestamps
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'comment_id', 'rating_id', 'user_id'
    ];


    public function comment()
    {
    	return $this->belongsTo('App\Comment', 'comment_id');
    }
    
    public function rating()
    {
    	return $this->belongsTo('App\Rating', 'rating_id');
    }
    
    public function user()
    {
    	return $this->belongsTo('App\User', 'user_id');
    }
}
