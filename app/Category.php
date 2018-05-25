<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    const STATUS_ACTIVE = 1;
    
    const STATUS_DELETE = 0;

    public function articles()
    {
        return $this->hasMany('App\Article');
    }

    public function comments()
    {
        return $this->hasManyThrough(
            'App\Comment', 'App\Article',
            'category_id', 'article_id', 'id'
        );
    }

    // delete
    function delete_cate($id){
        $category = Category::find($id);
        $category->status = self::STATUS_DELETE;
        $category->save();
        
        return true;
    }
}
