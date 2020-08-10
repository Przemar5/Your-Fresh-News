<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Image extends Model
{
    use SoftDeletes;
    
    // Table name
    protected $table = 'images';

    // Soft delete
    // protected $softDelete = true;

    // Primary key
    public $primaryKey = 'id';

    // Timestamps
    public $timestamps = true;


    public function articles()
    {
    	return $this->belongsToMany('App\Article', 'article_image', 'image_id', 'article_id');
    }
}
