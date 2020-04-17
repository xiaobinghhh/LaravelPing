<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentFinalExamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_final_exam', function (Blueprint $table) {
            $table->increments('id');
            $table->string('student_no', 11);//学号
            $table->string('course_no', 11);//课程号
            $table->string('final_exam_basis')->nullable($value = true);//期末试卷依据
            $table->unsignedTinyInteger('final_exam_score')->default('0')->nullable($value = true);//期末考试成绩
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_final_exam');
    }
}
