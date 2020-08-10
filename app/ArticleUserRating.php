<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArticleUserRating extends Model
{
    use SoftDeletes;
    
    // Table name
    protected $table = 'article_user_rating';

    // Timestamps
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'article_id', 'rating_id', 'user_id'
    ];


    public function article()
    {
    	return $this->belongsTo('App\Article', 'article_id');
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
