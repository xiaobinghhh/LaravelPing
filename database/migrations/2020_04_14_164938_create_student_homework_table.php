<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentHomeworkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_homework', function (Blueprint $table) {
            $table->increments('id');
            $table->string('homework_course_id', 11);//作业-课程id，对应作业表的自增主键
            $table->string('student_no', 11);//学生号
            $table->string('src')->nullable($value = true);//作业提交文件
            $table->string('commit_desc')->nullable($value = true);//作业提交描述
            $table->unsignedTinyInteger('homework_score')->default('0')->nullable($value = true);//作业成绩
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_homework');
    }
}
