<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_report', function (Blueprint $table) {
            $table->increments('id');
            $table->string('report_course_id', 11);//报告-课程id，对应报告表的自增主键
            $table->string('student_no', 11);//学生号
            $table->string('src')->nullable($value = true);//报告提交文件
            $table->string('commit_desc')->nullable($value = true);//报告提交描述
            $table->unsignedTinyInteger('report_score')->default('0')->nullable($value = true);//报告成绩
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_report');
    }
}
