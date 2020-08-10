<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArticleCategory extends Pivot
{
    use SoftDeletes;
    
    // Table name
    protected $table = 'article_category';


    public function article()
    {
    	return $this->belongsTo('App\Article', 'article_id');
    }
    
    public function category()
    {
    	return $this->belongsTo('App\Category', 'category_id');
    }
}
