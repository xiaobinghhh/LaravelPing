<?php

namespace App\Application;

use Illuminate\Database\Eloquent\Model;

class StudentReport extends Model
{
    //定义关联的数据表
    protected $table = 'student_report';
    public $timestamps = false;


    //多个提交对应一次作业
    public function report()
    {
        return $this->belongsTo('App\Application\Report', 'report_course_id', 'id');
    }
}
