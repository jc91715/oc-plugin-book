<?php namespace Jc91715\Book\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class AddFiledChapterTable extends Migration
{
    public function up()
    {
        Schema::table('jc91715_book_chapters', function(Blueprint $table) {
            $table->unsignedInteger('user_id')->nullable();
            $table->longText('history_content')->nullable();
            $table->longText('history_html')->nullable();
            $table->string('slug')->default('');
            $table->string('state')->default('');
            $table->dateTime('claim_time')->nullable();
        });
    }

    public function down()
    {

    }
}
