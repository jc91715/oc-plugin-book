<?php namespace Jc91715\Book\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateVideosTable extends Migration
{
    public function up()
    {
        Schema::create('jc91715_book_videos', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('title')->nullable();
            $table->string('link')->nullable();
            $table->boolean('if_display')->default(0);
            $table->integer('sort_order')->default(100)->nullable();
            $table->integer('videoable_id')->nullable();
            $table->string('videoable_type')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jc91715_book_videos');
    }
}
