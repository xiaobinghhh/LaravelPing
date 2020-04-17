<?php

namespace App\Application;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    //定义关联的数据表
    protected $table = 'reports';
    public $timestamps = false;

    //多个作业对应一门课程
    public function course()
    {
        return $this->belongsTo('App\Application\Course', 'course_no', 'no');
    }

    //作业和作业提交一对多
    public function commits()
    {
        //外键就是不是自己表的键
        return $this->hasMany('App\Application\StudentReport', 'report_course_id', 'id');
    }
}
