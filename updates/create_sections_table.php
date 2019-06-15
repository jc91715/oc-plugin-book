<?php namespace Jc91715\Book\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateSectionsTable extends Migration
{
    public function up()
    {
        Schema::create('jc91715_book_sections', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');

            $table->unsignedInteger('chapter_id')->index()->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->integer('doc_id')->unsigned()->index()->nullable();

            $table->string('slug')->default('');
            $table->string('state')->default('');

            $table->longText('origin')->nullable();
            $table->longText('origin_html')->nullable();
            $table->longText('content')->nullable();
            $table->longText('content_html')->nullable();
            $table->longText('history_content')->nullable();
            $table->longText('history_html')->nullable();





//            $table->dateTime('claim_time')->nullable();


            $table->integer('parent_id')->unsigned()->index()->nullable();
            $table->integer('nest_left')->nullable();
            $table->integer('nest_right')->nullable();
            $table->integer('nest_depth')->nullable();

            $table->softDeletes();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jc91715_book_sections');
    }
}
