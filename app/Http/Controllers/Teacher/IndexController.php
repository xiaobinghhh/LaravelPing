<?php

namespace App\Http\Controllers\Teacher;

use App\Application\Course;
use App\Application\Teacher;
use App\Application\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    //教师首页
    public function index()
    {
        $user_no = session('userInfo')['no'];
        $teacherInfo = Teacher::where('no', $user_no)->first();
        session(['teacherInfo' => $teacherInfo]);
        return view('teacher.index');
    }

    //教师欢迎页面
    public function welcome()
    {
        //得到session中的教师号
        $tech_no = session('userInfo')['no'];
        //获取教师的课程
        $teacher = Teacher::where('no', '=', $tech_no)->first();
        $courses = $teacher->courses()->get();
        return view('teacher.welcome', compact('courses'));
    }
}
