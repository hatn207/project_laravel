<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRssArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rss_articles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('website_name');
            $table->string('website_url');
            $table->string('title');
            $table->text('headword');
            $table->string('thumb');
            $table->string('image');
            $table->string('pub_date');
            $table->string('slug')->nullable();
            $table->integer('rss_id');
            $table->integer('writer')->default(0);
            $table->tinyInteger('type_xml');
            $table->tinyInteger('status')->default(0);
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
        Schema::dropIfExists('rss_articles');
    }
}
