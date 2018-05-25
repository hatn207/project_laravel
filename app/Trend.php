<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Trend extends Model
{
    //
    protected $table = 'trends';
    protected $fillable = ['content', 'slug'];

    const STATUS_ACTIVE = 1;
    
    const STATUS_DELETE = 0;

    /**
     * Get all of the posts that are assigned this tag.
     */
    public function articles()
    {
        return $this->morphedByMany('App\Article', 'trendable');
    }

    /**
     * Get all of the videos that are assigned this tag.
     */
    public function rssarticles()
    {
        return $this->morphedByMany('App\RssArticle', 'trendable')->where('status', RssArticle::DEFAULT_STATUS);
    }

    // delete
    function delete_trend($id){
        $trend = Trend::find($id);
        $trend->status = self::STATUS_DELETE;
        $trend->save();

        $trend->articles()->sync([]);
        $trend->rssarticles()->sync([]);
        
        return true;
    }
}
