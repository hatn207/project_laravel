<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Article;
use App\Category;
use App\RssArticle;
use App\Tag;
use App\Trend;
use App\Seo;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Display a listing search of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function search($id, Request $request)
    {
        $param  = $request->all();
        $status = $param['statusSelected'];
        $paginate_item = 10;
        $whereStatus = [['status', $status]];
        // status
        if ($status == 0) {
            $whereStatus = [['status', '<>' , Article::STATUS_DELETE]];
        }
        //get articles of category
        if ($id == 0) {
            $articles = Article::latest()
                ->where($whereStatus)
                ->orderBy('id', 'desc')
                ->paginate($paginate_item);
        } else {
            $articles = Category::find($id)->articles()->where($whereStatus)->orderBy('id', 'desc')->paginate($paginate_item);
        }
        
        //get all category
        $categories = Category::where('status', Category::STATUS_ACTIVE)->orderBy('id', 'desc')->get();

        return compact('articles', 'categories');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //get all category
        $categories = Category::where('status', Category::STATUS_ACTIVE)->get();

        //get all tag
        $tags = Tag::where('status', Tag::STATUS_ACTIVE)->get();

        // autocomplete
        $tag = [];
        foreach ($tags as $key => $value) {
            $tag[$value->name] = $value->name;
        }

        return compact('categories', 'tag');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validate all
        $this->validate($request, [
            'website_name' => 'required|string',
            'title' => 'required|string',
            'headword' => 'required',
            'image'  => 'required'
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
            $path_save = $path_root . $name;
            \Image::make($image)->save(public_path($path_save));
            $param['thumb'] = $path_save;
            $param['image'] = $path_save;
        } else {
            $param['thumb'] = RssArticle::noimage();
            $param['image'] = RssArticle::noimage();
        }

        // save to table articles
        $article = new Article();
        $article->website_name = $param['website_name'];
        $article->website_url = $param['website_url'];
        $article->title = $param['title'];
        $article->headword = $param['headword'];
        $article->thumb = $param['thumb'];
        $article->image = $param['image'];
        $article->slug = str_slug($param['title']);
        $article->writer = \Auth::id(); // to do
        $article->status = $param['status'];
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

        // save table seo
        $seo = new Seo();
        $seo->title = $param['titleSeo'] ? : 'Trang tin tức sức khỏe';
        $seo->description = $param['descriptionSeo'] ? : 'Trang tin tức sức khỏe';
        $seo->keywords = $param['keywordsSeo'] ? : 'Trang tin tức sức khỏe';
        $seo->alt = $param['altSeo'] ? : 'Trang tin tức sức khỏe';
        $article->seo()->save($seo);

        return response()->json([
            'msg' => 'success'
        ], 200);
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
        $article = Article::findOrFail($id);
        $article->seo = $article->seo()->first();
        $categories = Category::where('status', Category::STATUS_ACTIVE)->get();

        //get all tag
        $tags = Tag::where('status', Tag::STATUS_ACTIVE)->get();

        // autocomplete
        $tag = [];
        foreach ($tags as $key => $value) {
            $tag[$value->name] = $value->name;
        }

        //selected tags
        $selected_tags = [];
        $article_tags = $article->tags()->get();

        foreach ($article_tags as $key => $value) {
            $selected_tags[] = $value->name;
        }

        return compact('article', 'categories', 'tag', 'selected_tags');
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
        if (!file_exists($path_root)) {
            mkdir($path_root, 666, true);
        }
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
            $path_save = $path_root . $name;
            \Image::make($image)->save(public_path($path_save));
            $param['thumb'] = $path_save;
            $param['image'] = $path_save;
        }

        // save to table articles
        $article = Article::findOrFail($id);
        $article->website_name = $param['website_name'];
        $article->website_url = $param['website_url'];
        $article->title = $param['title'];
        $article->headword = $param['headword'];
        $article->thumb = $param['thumb'];
        $article->image = $param['image'];
        $article->pub_date = $param['pub_date'];
        $article->slug = str_slug($param['title']);
        $article->writer = \Auth::id(); // to do
        $article->status = $param['status'];
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

        // save table seo
        $seo = Seo::findOrFail($param['seo']['id']);
        $seo->title = $param['seo']['title'] ? : 'Trang tin tức sức khỏe';
        $seo->description = $param['seo']['description'] ? : 'Trang tin tức sức khỏe';
        $seo->keywords = $param['seo']['keywords'] ? : 'Trang tin tức sức khỏe';
        $seo->alt = $param['seo']['alt'] ? : 'Trang tin tức sức khỏe';
        $seo->figcaption = $param['seo']['title'] ? : 'Trang tin tức sức khỏe';
        $article->seo()->save($seo);


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
        $article = Article::find($id);
        $article->status = Article::STATUS_DELETE;
        $article->save();

        $article->trends()->sync([]);
        
        return response()->json([
            'msg' => 'Thành công!'
        ], 200);
    }
}
