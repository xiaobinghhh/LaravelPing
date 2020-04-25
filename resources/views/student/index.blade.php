<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>课程评分管理系统|学生</title>
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
<div class="container">
    <div class="logo"><a href="{{url('student/index')}}">课程评分管理系统</a></div>
    <ul class="layui-nav right" lay-filter="">
        <li class="layui-nav-item">
            <a href="javascript:;">{{session('studentInfo')['name']}}</a>
            <dl class="layui-nav-child"> <!-- 二级菜单 -->
                <dd><a onclick="xadmin.open('修改密码','{{url('student/changePass')}}',600,600,false)">修改密码</a></dd>
                <dd><a href="{{url('student/logout')}}">退出</a></dd>
            </dl>
        </li>
        <li class="layui-nav-item to-index"><a href="/">前台首页</a></li>
    </ul>

</div>
<!-- 顶部结束 -->
<!-- 中部开始 -->

<!-- 右侧主体开始 -->
<div class="page-content" style="left: 0px">
    <div class="layui-tab tab" lay-filter="xbs_tab" lay-allowclose="false">
        <div class="layui-tab-content" style="top: 0px">
            <div class="layui-tab-item layui-show">
                <iframe src='{{url('student/welcome')}}' frameborder="0" scrolling="yes" class="x-iframe"></iframe>
            </div>
        </div>
        <div id="tab_show"></div>
    </div>
</div>
<!-- 右侧主体结束 -->
<!-- 中部结束 -->

<!-- 底部结束 -->
</body>
</html>