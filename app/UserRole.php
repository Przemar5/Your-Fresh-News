<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UserRole extends Model
{
    use SoftDeletes;

    // Table name
    protected $table = 'user_image';


    public function user()
    {
    	return $this->belongsTo('App\User', 'user_id');
    }

    public function role()
    {
    	return $this->belongsTo('App\Role', 'role_id');
    }
}
