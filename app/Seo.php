<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Seo extends Model
{
    //
    protected $table = 'seo';
    protected $fillable = ['title', 'description', 'keywords', 'figcaption'];

    public function seoable()
    {
        return $this->morphTo();
    }
}
