<?php

namespace App\Application;

use Illuminate\Database\Eloquent\Model;

class Homework extends Model
{
    //定义关联的数据表
    protected $table = 'homeworks';
    public $timestamps = false;

    //多个作业对应一门课程
    public function course()
    {
        return $this->belongsTo('App\Application\Course', 'course_no', 'no');
    }
}
