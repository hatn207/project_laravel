<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Category;
use App\Article;
use App\Tag;
use App\Comment;
use App\Seo;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

use Analytics;
use Spatie\Analytics\Period;

use Carbon;

class AppController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        // //thong ke tung thang ke tu 1 nam truoc den nay
        // $analyticsData = Analytics::performQuery(
        //     Period::years(1),
        //     'ga:sessions',
        //     [
        //         'metrics' => 'ga:sessions, ga:pageviews',
        //         'dimensions' => 'ga:yearMonth'
        //     ]
        // );

        // $startDate = Carbon::now()->subYear();
        // $endDate = Carbon::now();

        // $date = Period::create($startDate, $endDate);

        // // truy xuất dữ liệu khách truy cập và số lần truy cập trang cho ngày hiện tại và bảy ngày qua
        // $analyticsData2 = Analytics::fetchVisitorsAndPageViews(Period::days(7));

        // // tìm nạp các trang được truy cập nhiều nhất cho hôm nay và tuần trước
        // $analyticsData3 = Analytics::fetchMostVisitedPages(Period::days(7));


        // // var_dump($date);
        // var_dump($analyticsData3);
        // // var_dump($analyticsData->rows);
        // die();
        // return $analyticsData;

        $paginate_item = 7;
        // $category = Category::where('status', Category::STATUS_ACTIVE)->first();
        $articles = Article::where('status', Article::STATUS_ACTIVE)
        ->with('category')
        ->withCount('comments')
        ->orderBy('id', 'desc')
        ->paginate($paginate_item);


        return $articles;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $slug
     * @return \Illuminate\Http\Response
     */
    public function categoryArticles($slug)
    {
        $paginate_item = 7;
        $category = Category::where('slug', $slug)->where('status', Category::STATUS_ACTIVE)->first();
        $articles = $category->articles()->where('status', Article::STATUS_ACTIVE)->with('comments')->orderBy('id', 'desc')->paginate($paginate_item);

        return compact('category' ,'articles');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $slug
     * @return \Illuminate\Http\Response
     */
    public function tagArticles($slug)
    {
        //get tag
        $tag = Tag::where('slug', $slug)->where('status', Tag::STATUS_ACTIVE)->first();
        //get tag article
        $paginate_item = 7;
        $tag_articles = $tag->articles()->with('category')->withCount('comments')->where('status', Article::STATUS_ACTIVE)->orderBy('id', 'desc')->paginate($paginate_item);

        return compact('tag', 'tag_articles');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function navigation()
    {
        //get all category
        $categories = Category::where('status', Category::STATUS_ACTIVE)->get();

        return $categories;
    }
    
    /**
     * Display a article detail
     *
     * @return \Illuminate\Http\Response
     */
    public function articleDetail($slug)
    {
        //get all category
        $article = Article::where('slug', $slug)->with('comments')->with('tags')->where('status', Article::STATUS_ACTIVE)->first();
        $seo = $article->seo()->first();
        
        if ($article) {
            // update view
            $article->view += 1;
            $article->save();
            // category
            $category = $article->category()->first();
            // related article 
            $related = Article::where('category_id', $category->id)->where('slug', '<>', $slug)->with('comments')->where('status', Article::STATUS_ACTIVE)->limit(4)->get();
        }
        return compact('article', 'related', 'category', 'seo');
    }

    /**
     * Display a popular articles
     *
     * @return \Illuminate\Http\Response
     */
    public function popularArticles()
    {
        //get popular article 
        $article = Article::where('status', Article::STATUS_ACTIVE)->withCount('comments')->with('category')->orderBy('view', 'desc')->limit(5)->get();
        
        return $article;
    }

    /**
     * Display a most comments
     *
     * @return \Illuminate\Http\Response
     */
    public function mostComments()
    {
        //get most comments
        $article = Article::Has('comments')->withCount('comments')->with('category')->orderBy('comments_count', 'desc')->where('status', Article::STATUS_ACTIVE)->limit(5)->get();

        return $article;
    }

    /**
     * Display a popular tags
     *
     * @return \Illuminate\Http\Response
     */
    public function popularTags()
    {
        //get tags
        $tags = Tag::withCount(['articles' => function ($query) {
                $query->where('status', Article::STATUS_ACTIVE);
            }])
            ->where('status', Tag::STATUS_ACTIVE)
            ->orderBy('articles_count', 'desc')
            ->get();

        return $tags;
    }
}
