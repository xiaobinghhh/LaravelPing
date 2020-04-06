<?php

namespace App\Http\Controllers\Teacher;

use App\Application\Course;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CourseController extends Controller
{
    //课程欢迎页
    public function welcome(Course $course)
    {
        return view('teacher.course_welcome', compact('courses'));
    }
}

