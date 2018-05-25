<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Trend;
use App\RssArticle;
use App\Article;

class TrendController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        // $trends = Trend::where('status', Trend::STATUS_ACTIVE)->get();
        // $trend = Trend::find(1);
        // $trend_articles = $trend->rssarticles()->get();

        $trends = Trend::latest()
            ->where('status', Trend::STATUS_ACTIVE)
            ->withCount('articles')
            ->withCount('rssarticles')
            ->paginate(15);

        return $trends;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validate
        $this->validate($request, [
            'content' => 'required|string',
        ]);
        
        // save
        $trend = new Trend;
        $trend->content = $request->input('content');
        $trend->slug = str_slug($request->input('content'));
        $trend->save();

        return response()->json([
            'msg' => 'Tạo thành công!'
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        //
        $paginate_item = 5;

        if (strpos($slug, '.article')) {
            $slug = str_replace( '.article', '', $slug);
            $trend = Trend::where('status', Trend::STATUS_ACTIVE)->where('slug', $slug)->first();
            $articles = $trend->articles()->where('status', '<>' ,Article::STATUS_DELETE)->orderBy('id', 'desc')->paginate($paginate_item);
        }

        if (strpos($slug, '.rssarticle')) {
            $slug = str_replace( '.rssarticle', '', $slug);
            $trend = Trend::where('status', Trend::STATUS_ACTIVE)->where('slug', $slug)->first();
            $articles = $trend->rssarticles()->orderBy('id', 'desc')->paginate($paginate_item);
        }
        
        return $articles;

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //delete
        $trend = new Trend;
        $trend->delete_trend($id);

        return response()->json([
            'msg' => 'Thành công!'
        ], 200);
    }
}
