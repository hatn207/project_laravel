<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Seo extends Model
{
    //
    protected $table = 'seo';
    protected $fillable = ['title', 'description', 'keywords'];

    public function seoable()
    {
        return $this->morphTo();
    }
}
