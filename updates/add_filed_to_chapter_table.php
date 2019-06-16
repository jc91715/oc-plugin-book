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
            $table->dateTime('claim_time')->nullable();//没有用

            $table->boolean('if_total_translate')->default(true);//是否整块翻译

            $table->integer('section_number')->default(0);//分块数量
            $table->integer('translate_section_number')->default(0);//已翻译分块数量
        });
    }

    public function down()
    {

    }
}
