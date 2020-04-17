<?php

namespace App\Application;

use Illuminate\Database\Eloquent\Model;

class StudentFinalExam extends Model
{
    //定义关联的数据表
    protected $table = 'student_final_exam';
    public $timestamps = false;
}
