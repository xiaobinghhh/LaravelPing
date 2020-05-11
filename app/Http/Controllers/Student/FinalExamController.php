<?php

namespace App\Http\Controllers\Student;

use App\Application\Course;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class FinalExamController extends Controller
{
    //期末考试-页面
    public function index(Course $course)
    {
        $student_no = session('userInfo')['no'];//获取学号
        $students = DB::table('student_final_exam')->where('course_no', $course->no)->orderBy('final_exam_score','desc')->get();
        $rank = 0;//排名
        foreach ($students as $student) {
            $rank++;
            if ($student->student_no == $student_no) break;
        }
        $rank_str = $rank . "/" . count($students);
        $final_exam = $students->where('student_no', $student_no)->first();//获得当前学生期末考试记录
        if ($final_exam) {
            $final_exam_score = $final_exam->final_exam_score;
        }//找不到考试记录
        else {
            $final_exam_score = 0;
        }
        return view('student.final_exam.index', compact('course', 'rank_str', 'final_exam_score'));
    }
}
