<?php namespace Jc91715\Book\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class AddSoftFiledToDocChapterTable extends Migration
{
    public function up()
    {
        Schema::table('jc91715_book_docs', function(Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('jc91715_book_chapters', function(Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down()
    {

    }
}
