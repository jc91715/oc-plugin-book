<?php namespace Jc91715\Book\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class AddFiledToDocTable extends Migration
{
    public function up()
    {
        Schema::table('jc91715_book_docs', function(Blueprint $table) {
            $table->boolean('if_need_translate')->default(true);
        });
    }

    public function down()
    {
        Schema::table('jc91715_book_docs', function(Blueprint $table) {
            $table->dropColumn('if_need_translate');
        });
    }
}
