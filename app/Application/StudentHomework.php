<?php

namespace App\Application;

use Illuminate\Database\Eloquent\Model;

class StudentHomework extends Model
{
    //定义关联的数据表
    protected $table = 'student_homework';
    public $timestamps = false;

    //多个提交对应一次作业
    public function homework()
    {
        return $this->belongsTo('App\Application\Homework', 'homework_course_id', 'id');
    }
}
