<?php

namespace App\Application;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    //定义关联的数据表
    protected $table = 'courses';

    //多个课程对应一个老师
    public function teacher()
    {
        return $this->belongsTo('App\Application\Teacher', 'teacher_no', 'no');
    }

    //课程和学生多对多
    public function students()
    {
        return $this->belongsToMany('App\Application\Student', 'student_course', 'course_no', 'student_no', 'no', 'no');
    }

    //课程和作业一对多
    public function homeworks()
    {
        return $this->hasMany('App\Application\Homework', 'course_no', 'no');
    }

    //课程和报告一对多
    public function reports()
    {
        return $this->hasMany('App\Application\Report', 'course_no', 'no');
    }

    //课程和评分依据一对多
    public function basis()
    {
        return $this->hasMany('App\Application\Basis','course_no','no');
    }
}
