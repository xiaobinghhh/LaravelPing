<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBasisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('basis', function (Blueprint $table) {
            $table->increments('id');
            $table->string('course_no', 11);//评分项所属课程号
            $table->enum('name', ['signment', 'homework', 'report', 'final_exam'])->unique();//评分项标注
            $table->string('cn_name');//评分项名称
            $table->unsignedTinyInteger('weight')->default('25');//评分权重
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('basis');
    }
}
