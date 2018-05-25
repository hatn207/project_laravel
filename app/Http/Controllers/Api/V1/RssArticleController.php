<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\RssArticle;
use App\Category;
use App\Article;
use App\Tag;
use App\Trend;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RssArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rssArticles = RssArticle::latest()
            ->where('status', RssArticle::DEFAULT_STATUS)
            ->paginate(10);
            
        return $rssArticles;
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
        //
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $rssArticle = RssArticle::findOrFail($id);
        $categories = Category::where('status', Category::STATUS_ACTIVE)->get();

        //get all tag
        $tags = Tag::where('status', Tag::STATUS_ACTIVE)->get();

        // autocomplete
        $tag = [];
        foreach ($tags as $key => $value) {
            $tag[$value->name] = $value->name;
        }

        return compact('rssArticle', 'categories', 'tag');
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
        // validate all
        $this->validate($request, [
            'website_name' => 'required|string',
            'title' => 'required|string'
        ]);
        $param = $request->all();
        
        // upload image
        $path_root = '/images/admin/articles/';
        $image = $param['image'];
        $rule_extention = ['png', 'jpg', 'jpeg'];
        
        if ($param['image_change_flg']) {
            $image_extention = explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            // validate image
            if (!in_array($image_extention, $rule_extention)) {
                return response()->json([
                    'image' => ['Chỉ cho phép tải tệp có đuôi là: '. implode(', ', $rule_extention)]
                ], 422);
            }
            $name = time() . '.' . $image_extention;
        } else {
            $name = time() . '-' . basename($image);
        }
        $path_save = $path_root . $name;
        \Image::make($image)->save(public_path($path_save));
        $param['thumb'] = $path_save;
        $param['image'] = $path_save;

        // update status rss article
        $rssArticle = RssArticle::findOrFail($id);
        $rssArticle->status = RssArticle::STATUS_PUBLIC;
        $rssArticle->save();
        //delete trends rss article
        $rssArticle->trends()->sync([]);

        // save to table articles
        $article = new Article;
        $article->website_name = $param['website_name'];
        $article->website_url = $param['website_url'];
        $article->title = $param['title'];
        $article->headword = $param['headword'];
        $article->thumb = $param['thumb'];
        $article->image = $param['image'];
        $article->slug = str_slug($param['title']);
        $article->pub_date = $param['pub_date'];
        $article->writer = \Auth::id(); // to do
        $article->status = Article::STATUS_ACTIVE;
        $article->category_id = $param['category_id'];
        $article->save();

        // add tags
        $tag_names = $param['selected_tags'];
        $tag_ids = [];

        foreach ($tag_names as $tag_name) {
            $tag = Tag::firstOrCreate(['name' => $tag_name, 'slug' => str_slug($tag_name)]);
            if ($tag) {
                $tag_ids[] = $tag->id;
            }
        }
        $article->tags()->sync($tag_ids);


        // search all trend
        $trends = Trend::where('status', Trend::STATUS_ACTIVE)->get();
        $trend_ids = [];
        foreach($trends as $trend){
            if ((strpos(strtolower($param['title']), strtolower($trend->content)) || strpos(strtolower($param['headword']), strtolower($trend->content))) !== false) {
                $trend_ids[] = $trend->id;
            }
        }

        // save table trendables
        $article->trends()->sync($trend_ids);

        return response()->json([
            'msg' => 'success'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
