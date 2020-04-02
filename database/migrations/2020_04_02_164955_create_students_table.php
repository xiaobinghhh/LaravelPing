<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->increments('id');
            $table->string('no',11)->unique();//学生学号
            $table->string('name',20);//学生姓名
            $table->string('college')->nullable($value = true);//学院
            $table->string('major')->nullable($value = true);//专业
            $table->string('grade')->nullable($value = true);//年级
            $table->string('class')->nullable($value = true);//班级
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('students');
    }
}
