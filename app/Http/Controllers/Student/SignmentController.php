<?php

namespace App\Http\Controllers\Student;

use App\Application\Course;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class SignmentController extends Controller
{
    //签到页面-首页
    public function index(Course $course)
    {
        $student_no = session('userInfo')['no'];//获取学号
        $signment = DB::table('signments')->where('course_no', $course->no)->where('student_no', $student_no)->first();//获得学生课程签到记录
        if ($signment) {
            $sign = str_split($signment->sign_data);
            //计算出勤率
            $_1_cnt = 0;//出勤次数
            $_0_cnt = 0;//缺勤次数
            $cnt = count($sign);//签到总次数
            for ($i = 0; $i < $cnt; $i++) {
                if ($sign[$i] == 1) $_1_cnt++;
                else if ($sign[$i] == 0) $_0_cnt++;
            }
        }//找不到相应签到记录
        else {
            $sign = null;
            $_1_cnt = 0;//出勤次数
            $_0_cnt = 0;//缺勤次数
        }
        return view('student.signment.index', compact('course', 'sign', '_1_cnt', '_0_cnt'));
    }
}
