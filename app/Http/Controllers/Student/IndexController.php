<?php

namespace App\Http\Controllers\Student;

use App\Application\Course;
use App\Application\Student;
use App\Application\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class IndexController extends Controller
{
    //学生首页
    public function index()
    {
        $user_no = session('userInfo')['no'];
        $studentInfo = Student::where('no', $user_no)->first();
        session(['studentInfo' => $studentInfo]);
        return view('student.index');
    }

    //学生欢迎页面
    public function welcome()
    {
        //得到session中的学生学号
        $stu_no = session('userInfo')['no'];
        //获取教师的课程
        $student = Student::where('no', '=', $stu_no)->first();
        if ($student) {
            $courses = $student->courses()->get();
            return view('student.welcome', compact('courses'));
        } else {
            return redirect('student/logout');
        }
    }

    //退出登录
    public function logout()
    {
        session(['userInfo' => null]);
        return redirect(url('/login'));
    }

    //修改密码
    public function changePass()
    {
        //接受到修改密码请求
        if (Input::method() == 'POST') {
            $rules = [
                'password_o' => 'required|between:4,18',
                'password' => 'required|between:4,18|confirmed',
            ];
            $message = [
                'password_o.required' => '旧密码 不能为空',
                'password_o.between' => '旧密码 必须介于 4 - 18 个字符之间',
            ];
            $validator = Validator::make(Input::all(), $rules, $message);
            //输入有效性验证
            //失败
            if ($validator->fails()) {
                return back()->withErrors($validator);
            } //成功
            else {
                $user_id = session('userInfo')['no'];
                $user = User::where('no', $user_id)->first();
                if (Hash::check(Input::get('password_o'), $user->password)) {
                    $user->password = bcrypt(Input::get('password'));
                    $user->update();
                    return back()->withSuccess('密码修改成功');
                } else {
                    return back()->withErrors('旧密码错误');
                }
            }
        } else {
            return view('password');
        }
    }

    //我的课程
    public function course(Course $course)
    {
        //依赖注入，传入课程信息
        return view('student.course_home', compact('course'));
    }
}
