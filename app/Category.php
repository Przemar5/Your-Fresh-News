<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    // Table name
    protected $table = 'categories';

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
		'name', 'slug',
	];


    public function articles($column, $order = 'ASC')
    {
        $id = $this->id;
        return \App\Article::whereHas('categories', function ($query) use ($id) {
            $query->where('category_id', $id);
        })->orderBy($column, $order)->get();
    }

    public function articlesPaginated($count, $column, $order)
    {
        $id = $this->id;
        return \App\Article::whereHas('categories', function ($query) use ($id) {
            $query->where('category_id', $id);
        })->orderBy($column, $order)->paginate($count);
    }
}
