<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>课程评分管理系统|老师</title>
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport"
          content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi"/>
    <meta http-equiv="Cache-Control" content="no-siteapp"/>

    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon"/>
    <link rel="stylesheet" href="{{asset('template/css/font.css')}}">
    <link rel="stylesheet" href="{{asset('template/css/xadmin.css')}}">
    <script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
    <script src="{{asset('template/lib/layui/layui.js')}}" charset="utf-8"></script>
    <script type="text/javascript" src="{{asset('template/js/xadmin.js')}}"></script>

</head>
<body>
<!-- 顶部开始 -->
<div class="container">
    <div class="logo"><a href="{{url('/teacher/index')}}">课程评分管理系统</a></div>
    <ul class="layui-nav left" lay-filter="">
        <li class="layui-nav-item">
            <a href="javascript:;">评分依据</a>
            <dl class="layui-nav-child"> <!-- 二级菜单 -->
                <dd><a onclick="iframe.location='{{url('teacher/changePass')}}'"><i class="iconfont">&#xe6ae;</i>设置</a>
                </dd>
            </dl>
        </li>
    </ul>
    <ul class="layui-nav right" lay-filter="">
        <li class="layui-nav-item">
            <a href="javascript:;">{{session('teacherInfo')['name']}}</a>
            <dl class="layui-nav-child"> <!-- 二级菜单 -->
                <dd><a onclick="x_admin_show('修改密码','{{url('teacher/changePass')}}',500,400)">修改密码</a></dd>
                {{--                <dd><a onclick="iframe.location='{{url('teacher/changePass')}}'">修改密码</a></dd>--}}
                <dd><a href="{{url('teacher/logout')}}">退出</a></dd>
            </dl>
        </li>
        <li class="layui-nav-item to-index"><a href="/">前台首页</a></li>
    </ul>

</div>
<!-- 顶部结束 -->
<!-- 中部开始 -->

<!-- 右侧主体开始 -->
<div class="page-content" style="left: 0px;">
    <div class="layui-tab-content" style="top: 0px;">
        <div class="layui-tab-item layui-show">
            <iframe name="iframe" src='{{url('/teacher/welcome')}}' frameborder="0" scrolling="yes"
                    class="x-iframe"></iframe>
        </div>
    </div>
</div>
</div>
<div class="page-content-bg"></div>
<!-- 右侧主体结束 -->
<!-- 中部结束 -->
<!-- 底部开始 -->
<div class="footer">
    <div class="copyright">Copyright ©2020 221600440_小冰</div>
</div>


<!-- 底部结束 -->
<script>

</script>
</body>
</html>