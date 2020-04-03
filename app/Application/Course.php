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
}
