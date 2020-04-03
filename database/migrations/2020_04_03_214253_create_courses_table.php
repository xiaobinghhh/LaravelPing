<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('no', 11)->unique();//课程号
            $table->string('name', 20);//课程名称
            $table->string('teacher_no', 11);//教师号
            $table->date('begin_at');//课程开始日期
            $table->date('end_at');//课程结束日期
            $table->string('place')->nullable($value = true);//上课地点
            $table->unsignedTinyInteger('credit')->nullable($value = true);//学分
            $table->unsignedTinyInteger('period')->nullable($value = true);//课时
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('courses');
    }
}
