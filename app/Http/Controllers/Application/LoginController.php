<?php

namespace App\Http\Controllers\Application;

use App\Application\User;
use Illuminate\Http\Request;
use Gregwar\Captcha\CaptchaBuilder;
use Gregwar\Captcha\PhraseBuilder;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    //系统登录页
    public function login()
    {
        return view('login');
    }

    public function captcha($tmp)
    {
        $phrase = new PhraseBuilder;
        //设置验证码位数
        $code = $phrase->build(4);
        //生成验证码图片的Builder对象，配置相应属性
        $builder = new CaptchaBuilder($code, $phrase);
        //设置背景颜色
        $builder->setBackgroundColor(220, 210, 230);
        $builder->setMaxAngle(25);
        $builder->setMaxBehindLines(0);
        $builder->setMaxFrontLines(0);
        //可以设置图片宽高及字体
        $builder->build($width = 150, $height = 50, $font = null);
        //获取验证码内容
        $phrase = $builder->getPhrase();
        //把内容存入session
        \Session::flash('code', $phrase);
        //生成图片
        header("Cache-Control: no-cache, must revalidate");
        header("Content-Type:image/jpeg");
        $builder->output();
    }

    public function doLogin(Request $request)
    {
        //接受表单数据
        $input = $request->except('_token');
        //进行表单验证
        $rules = [
            'no' => 'required|regex:/^\d{8}$/',
            'password' => 'required|between:4,18|alpha_dash',
        ];
        //自定义提示信息
        $msgs = [
            'no.required' => '学号/工号不能为空',
            'no.regex' => '学号工号应为8位数字',
        ];
        $validator = Validator::make($input, $rules, $msgs);
        //获取验证码
        $captcha = strtoupper($request->input('captcha'));
        $code = strtoupper($request->session()->get('code'));
        //验证码正确
        if ($code == $captcha) {
            //用户信息验证
            //验证失败
            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            } //验证通过
            else {
                //继续进行用户身份验证
                $user = User::where('no', $request->input('no'))->first();
                if ($user == null || !Hash::check($request->input('password'), $user->password)) {
                    return back()->withErrors('用户名或密码错误');
                }
                //将用户信息写入session中
                session(['user' => $user]);
                //根据用户角色跳转到相应系统主页
                switch ($user->type) {
                    //学生
                    case 1:
                        return redirect(url('student/index'));
                        break;
                    //老师
                    case 2:
                        return redirect(url('teacher/index'));
                        break;
                }
            }
        } //验证码错误
        else {
            return back()->withErrors('验证码错误')->withInput();
        }
    }
}
