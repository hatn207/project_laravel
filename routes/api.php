<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['prefix' => '/v1', 'namespace' => 'Api\V1', 'as' => 'api.'], function () {
    // authentication
    Route::middleware('auth:api')->group(function () {
        
        Route::resource('rss-article', 'RssArticleController', ['except' => ['create']]);
        Route::resource('category', 'CategoryController', ['except' => ['create']]);
        Route::resource('article', 'ArticleController', ['except' => []]);
        Route::resource('tag', 'TagController', ['except' => []]);
        Route::get('article/search/{id}', 'ArticleController@search')->name('article.search');
        // Route::resource('trend', 'TrendController', ['except' => []]);
    });
    Route::resource('rss', 'RssController', ['except' => ['edit']]);
    Route::resource('trend', 'TrendController', ['except' => []]);
    //app
    Route::get('app-index', 'AppController@index');
    Route::get('app-category-articles/{slug}', 'AppController@categoryArticles');
    Route::get('app-tag-articles/{slug}', 'AppController@tagArticles');
    Route::get('app-navigation', 'AppController@navigation');
    Route::get('app-article-detail/{slug}', 'AppController@articleDetail');
    Route::get('app-popular-articles', 'AppController@popularArticles');
    Route::get('app-popular-tags', 'AppController@popularTags');
    Route::get('app-most-comments', 'AppController@mostComments');

    // todo
    Route::resource('comment', 'CommentController', ['except' => []]);
    

});