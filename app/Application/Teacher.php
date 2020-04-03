<?php

namespace App\Application;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    //定义关联的数据表
    protected $table = 'teachers';

    //教师与课程的一对多关联
    public function courses()
    {
        return $this->hasMany('App\Application\Course', 'teacher_no', 'no');
    }
}
