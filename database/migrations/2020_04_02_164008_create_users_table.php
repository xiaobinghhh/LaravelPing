<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');//主键
            $table->string('no', 11)->unique();//学号、工号
            $table->string('password');//密码，varchar(255),不能为null
            $table->enum('gender', [1, 2])->default('1');//性别，1=男，2=女
            $table->unsignedTinyInteger('age');//年龄
            $table->string('mobile', 11)->nullable($value = true);//手机号，varchar（11）
            $table->string('email', 50)->nullable($value = true);//邮箱，varchar（50）
            $table->string('avatar')->nullable($value = true);//头像图片地址
            $table->enum('type', [1, 2])->default('1');//账号类型，1=学生，2=老师
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
