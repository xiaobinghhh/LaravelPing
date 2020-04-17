<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->increments('id');
            $table->string('report_id', 11)->unique();//报告id
            $table->string('course_no', 11);//课程id
            $table->string('name');//报告名称
            $table->text('description');//报告详情
            $table->string('src')->nullable($value = true);//报告文件
            $table->date('start_at');//报告开始日期
            $table->date('end_at');//报告结束日期
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reports');
    }
}
