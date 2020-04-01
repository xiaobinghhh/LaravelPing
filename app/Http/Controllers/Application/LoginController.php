<?php

namespace App\Http\Controllers\Application;

use Illuminate\Http\Request;
use Gregwar\Captcha\CaptchaBuilder;
use Gregwar\Captcha\PhraseBuilder;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    //系统登录页
    public function login()
    {
        return view('login');
    }

    public function captcha($tmp)
    {
        $phrase=new PhraseBuilder;
        //设置验证码位数
        $code=$phrase->build(4);
        //生成验证码图片的Builder对象，配置相应属性
        $builder=new CaptchaBuilder($code,$phrase);
        //设置背景颜色
        $builder->setBackgroundColor(220,210,230);
        $builder->setMaxAngle(25);
        $builder->setMaxBehindLines(0);
        $builder->setMaxFrontLines(0);
        //可以设置图片宽高及字体
        $builder->build($width=150,$height=50,$font=null);
        //获取验证码内容
        $phrase=$builder->getPhrase();
        //把内容存入session
        \Session::flash('code',$phrase);
        //生成图片
        header("Cache-Control: no-cache, must revalidate");
        header("Content-Type:image/jpeg");
        $builder->output();
    }
}
