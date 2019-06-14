<?php namespace Jc91715\Book\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateUserChapterTable extends Migration
{
    public function up()
    {
        Schema::create('jc91715_book_user_chapters', function(Blueprint $table) {
           $table->integer('user_id')->nullable();
           $table->integer('chapter_id')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jc91715_book_user_chapters');
    }
}
