<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use SoftDeletes;

    public const COVER_IMAGE_PATH = '/images/cover_images/';
    public const DEFAULT_COVER_IMAGE = 'no-image.png';
    public const DEFAULT_COVER_IMAGE_DESCRIPTION = 'Cover image';
    
    // Table name
    protected $table = 'articles';

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
        'title', 'slug', 'body', 'categories'
    ];


    public static function byCategory($name)
    {
        return Article::whereHas('categories', function($query) use ($name) {
            $query->where('name', $name);
        });
    }

    public function baseComments()
    {
        return \App\Comment::where('article_id', $this->id)
            ->whereNull('parent_comment_id');
    }

    public function baseCommentsWithTrashed()
    {
        return \App\Comment::withTrashed()
            ->where('article_id', $this->id)
            ->whereNull('parent_comment_id')
            ->where(function ($query) {
                $query->whereNotNull('deleted_at')->whereHas('subcomments');
            })
            ->orWhereNull('deleted_at')->get();
    }

    public function categories()
    {
    	return $this->belongsToMany('App\Category', 'article_category', 'article_id', 'category_id');
    }

    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    public function image()
    {
        return $this->belongsToMany('App\Image', 'article_image', 'article_id', 'image_id');
    }

    public function cover()
    {
        $id = $this->id;
        return \App\Image::whereHas('articles', function ($query) use ($id) {
            $query->where('article_id', $id);
        })->first();
    }

    public function coverPath()
    {
        $coverFilename = $this->cover() ? $this->cover()->path : self::DEFAULT_COVER_IMAGE;

        return env('APP_URL') . self::COVER_IMAGE_PATH . $coverFilename;
    }

    public function tags()
    {
        return $this->belongsToMany('App\Tag', 'article_tag', 'article_id', 'tag_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    // public function cover()
    // {
    //     if ($image = $this->image()->first()) {
    //         $coverImage = Image::find($image->id);
    //         $filePath = '/images/cover_images/' . $coverImage->path;
    //         $coverImage->src = \Illuminate\Support\Facades\Storage::disk('local')->url($filePath);

    //         return $coverImage;
    //     }
    // }

    // article_user_rating
    public function ratedBy()
    {
        return $this->belongsToMany('App\User', 'article_user_rating')->withPivot('rating_id');
    }

    public function ratings()
    {
        return $this->belongsToMany('App\Rating', 'article_user_rating')->withPivot('user_id');
    }

    public function likedBy($id)
    {
        return (bool) \App\ArticleUserRating::where('article_id', $this->id)
            ->where('user_id', $id)
            ->where('rating_id', Rating::where('type', 'like')->first()->id)->first();
    }

    public function dislikedBy($id)
    {
        return (bool) \App\ArticleUserRating::where('article_id', $this->id)
            ->where('user_id', $id)
            ->where('rating_id', Rating::where('type', 'dislike')->first()->id)->first();
    }

    // likes and dislikes
    public function likes()
    {
        return \App\ArticleUserRating::where('article_id', $this->id)
            ->where('rating_id', Rating::where('type', 'like')->first()->id);
    }

    public function dislikes()
    {
        return \App\ArticleUserRating::where('article_id', $this->id)->where('rating_id', Rating::where('type', 'dislike')->first()->id);
    }
}
