<?php

namespace App\Application;

use Illuminate\Database\Eloquent\Model;

class StudentCourse extends Model
{
    //定义关联的数据表
    protected $table = 'student_course';

    //学生
    public function student()
    {
        return $this->hasOne(Student::class, 'no', 'student_no');
    }

    //课程
    public function course()
    {
        return $this->hasOne(Course::class, 'no', 'course_no');
    }

}
