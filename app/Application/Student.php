<?php

namespace App\Application;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    //定义关联的数据表
    protected $table = 'students';

    //学生和课程多对多，但是查询出来的数据只有课程里面相关的数据，想要获取课程的成绩数据还要再次查询
    public function courses()
    {
        return $this->belongsToMany('App\Application\Course', 'student_course', 'student_no', 'course_no', 'no', 'no');
    }

    //学生和签到一对多
    public function signments()
    {
        //外键就是不是自己表的键
        return $this->hasMany('App\Application\Signment', 'student_no', 'no');
    }

}
