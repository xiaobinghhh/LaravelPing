<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>课程评分管理系统|{{$course->name}}</title>
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport"
          content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi"/>
    <meta http-equiv="Cache-Control" content="no-siteapp"/>

    <link rel="shortcut icon" href="{{asset('/favicon.ico')}}" type="image/x-icon"/>
    <link rel="stylesheet" href="{{asset('template/css/font.css')}}">
    <link rel="stylesheet" href="{{asset('template/css/xadmin.css')}}">
    <script type="text/javascript" src="{{asset('template/js/jquery.min.js')}}"></script>
    <script src="{{asset('template/lib/layui/layui.js')}}" charset="utf-8"></script>
    <script type="text/javascript" src="{{asset('template/js/xadmin.js')}}"></script>

</head>
<body>
<!-- 顶部开始 -->
<div class="container">
    <div class="logo"><a href="javascript:;" onclick="back_home()">课程评分管理系统</a></div>
    <div class="left_open">
        <a><i title="展开左侧栏" class="iconfont">&#xe699;</i></a>
    </div>
    <ul class="layui-nav right" lay-filter="">
        <li class="layui-nav-item">
            <a href="javascript:;">{{session('studentInfo')['name']}}</a>
            <dl class="layui-nav-child"> <!-- 二级菜单 -->
                <dd><a onclick="xadmin.open('修改密码','{{url('student/changePass')}}',600,600,false)">修改密码</a></dd>
                <dd><a onclick="logout()">退出</a></dd>
            </dl>
        </li>
        <li class="layui-nav-item to-index"><a href="/">前台首页</a></li>
    </ul>

</div>
<!-- 顶部结束 -->
<!-- 中部开始 -->
<!-- 左侧菜单开始 -->
<div class="left-nav">
    <div id="side-nav">
        <ul id="nav">
            <li>
                <a onclick="xadmin.add_tab('我的签到','{{url("student/course/".$course->no."/signment")}}',true)">
                    <i class="iconfont left-nav-li" lay-tips="我的签到">&#xe6b8;</i>
                    <cite>我的签到</cite></a>
            </li>
            <li>
                <a onclick="xadmin.add_tab('我的作业','{{url("student/course/".$course->no."/homework")}}',true)">
                    <i class="iconfont left-nav-li" lay-tips="我的作业">&#xe6a2;</i>
                    <cite>我的作业</cite></a>
            </li>
            <li>
                <a onclick="xadmin.add_tab('我的报告','{{url("student/course/".$course->no."/report")}}',true)">
                    <i class="iconfont left-nav-li" lay-tips="我的报告">&#xe705;</i>
                    <cite>我的报告</cite></a>
            </li>
            <li>
                <a onclick="xadmin.add_tab('我的期末考试','{{url("student/course/".$course->no."/final_exam")}}',true)">
                    <i class="iconfont left-nav-li" lay-tips="我的期末考试">&#xe74e;</i>
                    <cite>我的期末考试</cite></a>
            </li>
        </ul>
    </div>
</div>
<!-- <div class="x-slide_left"></div> -->
<!-- 左侧菜单结束 -->
<!-- 右侧主体开始 -->
<div class="page-content">
    <div class="layui-tab tab" lay-filter="xbs_tab" lay-allowclose="false">
        <ul class="layui-tab-title">
            <li class="home">
                <i class="layui-icon">&#xe68e;</i>{{$course->name}}
            </li>
        </ul>
        <div class="layui-unselect layui-form-select layui-form-selected" id="tab_right">
            <dl>
                <dd data-type="this">关闭当前</dd>
                <dd data-type="other">关闭其它</dd>
                <dd data-type="all">关闭全部</dd>
            </dl>
        </div>
        <div class="layui-tab-content">
            <div class="layui-tab-item layui-show">
                <iframe src='{{url('student/course/'.$course->id.'/welcome')}}' frameborder="0" scrolling="yes"
                        class="x-iframe"></iframe>
            </div>
        </div>
        <div id="tab_show"></div>
    </div>
</div>
<!-- 右侧主体结束 -->
<!-- 中部结束 -->

</body>
<script>
    function back_home() {
        var tabtitle = $(".layui-tab-title li");
        $.each(tabtitle, function () {
            parent.element.tabDelete('xbs_tab', $(this).attr("lay-id"));
        })
        window.location.href = "{{url('student/index')}}";
    };

    function logout() {
        var tabtitle = $(".layui-tab-title li");
        $.each(tabtitle, function () {
            parent.element.tabDelete('xbs_tab', $(this).attr("lay-id"));
        })
        window.location.href = "{{url('teacher/logout')}}";
    }
</script>
</html>