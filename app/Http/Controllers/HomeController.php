<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Category;
use App\Article;
use App\Tag;
use App\Comment;
use App\Seo;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = [];
        $data['title'] = 'Trang tin tức sức khỏe';
        $data['descriptionOG'] = '';
        $data['imageOG'] = '';
        $data['urlOG'] = $request->url();

        if (!empty($request->segment(2))) {
            $slug = $request->segment(2);
            //get seo
            $article = Article::where('slug', $slug)->where('status', Article::STATUS_ACTIVE)->first();
            $seo = $article->seo()->first();
            $data['title'] = $seo->title;
            $data['descriptionOG'] = $article->headword;
            $data['imageOG'] = $article->image;
            $data['urlOG'] = $request->url();
        }
        
        $now = Carbon::now('Asia/Ho_Chi_Minh');
        $data['day'] = 'NGÀY ' . $now->day;
        $data['date_time'] = ' THÁNG ' . $now->month . ' NĂM ' . $now->year;
       
        return view('app.index', $data);
    }
}
