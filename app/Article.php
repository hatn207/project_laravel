<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $table = 'articles';

    const STATUS_DELETE = 0;

    const STATUS_ACTIVE = 1;

    const STATUS_PRIVATE = 2;

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    public function tags() 
    {
        return $this->belongsToMany('App\Tag', 'article_tag', 'article_id', 'tag_id');
    }

    public function trends()
    {
        return $this->morphToMany('App\Trend', 'trendable');
    }

    public function seo()
    {
        return $this->morphMany('App\Seo', 'seoable');
    }

}
