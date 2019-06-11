<?php namespace Jc91715\Book\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateChaptersTable extends Migration
{
    public function up()
    {
        Schema::create('jc91715_book_chapters', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');

            $table->string('title')->nullable();
            $table->longText('origin')->nullable();
            $table->longText('origin_html')->nullable();
            $table->longText('content')->nullable();
            $table->longText('content_html')->nullable();
            $table->integer('doc_id')->unsigned()->index()->nullable();

            $table->integer('parent_id')->unsigned()->index()->nullable();
            $table->integer('nest_left')->nullable();
            $table->integer('nest_right')->nullable();
            $table->integer('nest_depth')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jc91715_book_chapters');
    }
}
