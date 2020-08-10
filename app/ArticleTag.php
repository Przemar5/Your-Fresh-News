<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ArticleTag extends Pivot
{
    use SoftDeletes;

    // Table name
    protected $table = 'article_tag';


    public function article()
    {
    	return $this->belongsTo('App\Article', 'article_id');
    }
    
    public function tag()
    {
    	return $this->belongsTo('App\Tag', 'tag_id');
    }
}
