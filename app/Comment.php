<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments';

    const STATUS_ACTIVE = 1;
    
    const STATUS_DELETE = 0;

    public function article()
    {
        return $this->belongsTo('App\Article');
    }
}

