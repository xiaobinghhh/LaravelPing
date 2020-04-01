<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>评分管理系统|登录</title>
	<meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />

    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="{{asset('template/css/font.css')}}">
	<link rel="stylesheet" href="{{asset('template/css/xadmin.css')}}">
    <script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
    <script src="{{asset('template/lib/layui/layui.js')}}" charset="utf-8"></script>
    <script type="text/javascript" src="{{asset('template/js/xadmin.js')}}"></script>

</head>
<body class="login-bg">
    
    <div class="login layui-anim layui-anim-up">
        <div class="message">欢迎使用评分管理系统</div>
        <div id="darkbannerwrap"></div>
        
        <form method="post" class="layui-form" >
            <input name="username" placeholder="学号/工号"  type="text" lay-verify="required" class="layui-input" >
            <hr class="hr15">
            <input name="password" lay-verify="required" placeholder="密码"  type="password" class="layui-input">
            <hr class="hr15">
            <input name="captcha" style="width: 150px;float:left;" lay-verify="required" placeholder="验证码"  type="text" class="layui-input">
            <a onclick="javascript:re_captcha();" style="float: right">
                <img src="{{url('/code/captcha/1')}}" id="127ddf0de5a04167a9e427d883690ff6">
            </a>
            <hr class="hr15">
            <input value="登录" lay-submit lay-filter="login" style="width:100%;" type="submit">
            <hr class="hr20" >
        </form>
    </div>

    <script>
        $(function  () {
            layui.use('form', function(){
              var form = layui.form;
              // layer.msg('玩命卖萌中', function(){
              //   //关闭后的操作
              //   });
              //监听提交
              form.on('submit(login)', function(data){
                // alert(888)
                layer.msg(JSON.stringify(data.field),function(){
                    location.href='index.html'
                });
                return false;
              });
            });
        })

        function re_captcha() {
            $url="{{url('/code/captcha')}}";
            $url=$url+"/"+Math.random();
            document.getElementById("127ddf0de5a04167a9e427d883690ff6").src=$url;
        }

        
    </script>

    
    <!-- 底部结束 -->
</body>
</html>