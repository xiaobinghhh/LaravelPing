<?php

namespace App\Http\Controllers\Teacher;

use App\Application\Course;
use App\Application\Signment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class SignmentController extends Controller
{
    //签到评分
    public function ping(Course $course)
    {
        //返回签到评分首页
        return view('teacher.signment.index', compact('course'));
    }

    //签到列表
    public function list(Course $course)
    {
        $students = $course->students()->get();
        $sign_list = array();

        foreach ($students as $student) {
            $sign = array(
                'student_no' => $student->no,
                'student_name' => $student->name,
                'sign_score' => $student->signments()->where('course_no', '=', $course->no)->first()->sign_score,
            );
            array_push($sign_list, $sign);
        }
        return json_encode($sign_list);
    }

    public function edit(Course $course)
    {

    }

}
