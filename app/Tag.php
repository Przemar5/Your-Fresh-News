<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use SoftDeletes;

    // Table name
    protected $table = 'tags';

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


    public function articles()
    {
        return $this->belongsToMany('App\Article', 'article_tag', 'article_id', 'tag_id');
    }

    public function articlesBy($column, $order = 'ASC')
    {
        $id = $this->id;
        return \App\Article::whereHas('tags', function ($query) use ($id) {
            $query->where('tag_id', $id);
        })->orderBy($column, $order)->get();
    }

    public function articlesPaginatedBy($count, $column, $order)
    {
        $id = $this->id;
        return \App\Article::whereHas('tags', function ($query) use ($id) {
            $query->where('tag_id', $id);
        })->orderBy($column, $order)->paginate($count);
    }
}
