<?php namespace Jc91715\Book\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class AddFiledToUserChapterTable extends Migration
{
    public function up()
    {
        Schema::table('jc91715_book_user_chapters', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('userable_id')->nullable();
            $table->string('userable_type')->default('');


            $table->string('state')->default('')->comment('该用户进行到的最终状态');
            $table->dateTime('claim_time')->nullable()->comment('认领时间');
            $table->dateTime('submit_to_review_time')->nullable()->comment('提交审核时间');
            $table->unsignedInteger('review_id')->nullable()->comment('审核者id');
            $table->dateTime('review_time')->nullable()->comment('审阅时间');

            $table->dateTime('re_translating_time')->nullable();
            $table->dateTime('improving_time')->nullable();

            $table->Text('extra')->nullable()->comment('记录这个翻译的相关操作历史');
            $table->timestamps();
        });
    }

    public function down()
    {

    }
}
