<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ArticleImage extends Pivot
{
    use SoftDeletes;

    // Table name
    protected $table = 'article_image';


    public function article()
    {
    	return $this->belongsTo('App\Article', 'article_id');
    }
    
    public function image()
    {
    	return $this->belongsTo('App\Image', 'image_id');
    }
}
