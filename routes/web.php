<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
    
//     return view('welcome');
// });

Auth::routes();

// Route::any('/{all}', 'HomeController@index')->name('home')->where(['all' => '.*']);


Route::group(['middleware' => ['auth'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('rss/{all}', 'RssController@index')->name('rss.index')->where(['all' => '.*']);
    Route::get('rss-article/{all}', 'RssArticleController@index')->name('rss_article.index')->where(['all' => '.*']);
    Route::get('category/{all}', 'CategoryController@index')->name('category.index')->where(['all' => '.*']);
    Route::get('article/{all}', 'ArticleController@index')->name('article.index')->where(['all' => '.*']);
    Route::get('trend/{all}', 'TrendController@index')->name('trend.index')->where(['all' => '.*']);
});

Route::get('/{all}', 'HomeController@index')->name('home')->where(['all' => '.*']);