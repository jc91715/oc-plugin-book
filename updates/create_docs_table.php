<?php namespace Jc91715\Book\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateDocsTable extends Migration
{
    public function up()
    {
        Schema::create('jc91715_book_docs', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name')->default('');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jc91715_book_docs');
    }
}
