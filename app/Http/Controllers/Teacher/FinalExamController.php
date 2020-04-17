<?php

namespace App\Http\Controllers\Teacher;

use App\Application\Course;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FinalExamController extends Controller
{
    //首页
    public function index(Course $course)
    {
        return view('teacher.final_exam.index', compact('course'));
    }

    public function list(Course $course)
    {
        //该课程的同学
        $students = $course->students()->get();
        $data = array();
        //遍历期末考试成绩表，获取每个同学的期末成绩记录
        foreach ($students as $student) {
            $_data = array(
                'student_no' => $student->no,
                'student_name' => $student->name,
            );
            //获得该生该课程期末成绩记录
            $final_exam = $student->final_exam()->where('course_no', '=', $course->no)->first();
            //期末试卷依据不为空
            if ($final_exam->final_exam_basis != null) {
                $_data['final_exam_basis'] = $final_exam->final_exam_basis;
            }
            $_data['final_exam_score'] = $final_exam->final_exam_score;
            array_push($data, $_data);
        }
        return json_encode($data);
    }
}
