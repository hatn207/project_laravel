<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('website_name');
            $table->string('website_url')->nullable();
            $table->string('title');
            $table->text('headword');
            $table->string('thumb');
            $table->string('image');
            $table->string('slug')->nullable();
            $table->string('pub_date')->nullable();
            $table->integer('writer')->default(0);
            $table->integer('view')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->integer('category_id');
            $table->integer('rss_article_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
}
