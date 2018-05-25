<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article_Tag extends Model
{
    protected $table = 'article_tag';

    const STATUS_ACTIVE = 1;
    
    const STATUS_DELETE = 0;
}
