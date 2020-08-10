<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UserImage extends Pivot
{
    use SoftDeletes;

    // Table name
    protected $table = 'user_image';


    public function user()
    {
    	return $this->belongsTo('App\User', 'user_id');
    }
    
    public function image()
    {
    	return $this->belongsTo('App\Image', 'image_id');
    }
}
