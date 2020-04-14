<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHomeworksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('homeworks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('homework_id', 11)->unique();//作业id
            $table->string('course_no', 11);//课程id
            $table->string('name');//作业名称
            $table->text('description');//作业详情
            $table->string('src')->nullable($value = true);//作业文件
            $table->date('start_at');//作业开始日期
            $table->date('end_at');//作业结束日期
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('homeworks');
    }
}
