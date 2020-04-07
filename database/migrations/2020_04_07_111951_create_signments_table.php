<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('signments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('student_no', 11);//学号
            $table->string('course_no', 11);//课程号
            $table->string('sign_data')->nullable($value = true);//签到状态
            $table->unsignedTinyInteger('sign_score')->default('0')->nullable($value = true);//签到成绩
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('signments');
    }
}
