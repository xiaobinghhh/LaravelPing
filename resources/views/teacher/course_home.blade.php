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
    <ul class="layui-nav left fast-add" lay-filter="">
        <li class="layui-nav-item">
            <a href="javascript:;">评分项</a>
            <dl class="layui-nav-child"> <!-- 二级菜单 -->
                <dd><a onclick="xadmin.add_tab('评分项','{{url('course/'.$course->no.'/basis')}}')"><i class="iconfont">&#xe6ae;</i>设置</a>
                </dd>
            </dl>
        </li>
    </ul>
    <ul class="layui-nav right" lay-filter="">
        <li class="layui-nav-item">
            <a href="javascript:;">{{session('teacherInfo')['name']}}</a>
            <dl class="layui-nav-child"> <!-- 二级菜单 -->
                <dd><a onclick="xadmin.open('修改密码','{{url('teacher/changePass')}}',500,400,false)">修改密码</a></dd>
                <dd><a href="{{url('teacher/logout')}}">退出</a></dd>
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
                <a href="javascript:;">
                    <i class="iconfont left-nav-li" lay-tips="学生签到">&#xe6b8;</i>
                    <cite>学生签到</cite>
                    <i class="iconfont nav_right">&#xe697;</i></a>
                <ul class="sub-menu">
                    <li>
                        <a onclick="xadmin.add_tab('签到评分','{{url("course/".$course->no."/signment_ping")}}',true)">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>签到评分</cite></a>
                    </li>
                    <li>
                        <a onclick="xadmin.add_tab('签到依据','{{url('course/'.$course->no.'/signment_file')}}',true)">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>签到依据</cite></a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript:;">
                    <i class="iconfont left-nav-li" lay-tips="课程作业">&#xe6b8;</i>
                    <cite>课程作业</cite>
                    <i class="iconfont nav_right">&#xe697;</i></a>
                <ul class="sub-menu">
                    <li>
                        <a onclick="xadmin.add_tab('作业列表','{{url("course/".$course->no."/homework")}}',true)">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>作业列表</cite></a>
                    </li>
                    <li>
                        <a onclick="xadmin.add_tab('作业评分','{{url("course/".$course->no."/homework_ping")}}',true)">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>作业评分</cite></a>
                    </li>
                    <li>
                        <a onclick="xadmin.add_tab('作业文件','{{url("course/".$course->no."/homework_file")}}',true)">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>作业文件</cite></a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript:;">
                    <i class="iconfont left-nav-li" lay-tips="课程报告">&#xe6b8;</i>
                    <cite>课程报告</cite>
                    <i class="iconfont nav_right">&#xe697;</i></a>
                <ul class="sub-menu">
                    <li>
                        <a onclick="xadmin.add_tab('报告列表','{{url("course/".$course->no."/report")}}',true)">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>报告列表</cite></a>
                    </li>
                    <li>
                        <a onclick="xadmin.add_tab('报告评分','{{url("course/".$course->no."/report_ping")}}',true)">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>报告评分</cite></a>
                    </li>
                    <li>
                        <a onclick="xadmin.add_tab('报告文件','{{url("course/".$course->no."/report_file")}}',true)">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>报告文件</cite></a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript:;">
                    <i class="iconfont left-nav-li" lay-tips="期末考试">&#xe6b8;</i>
                    <cite>期末考试</cite>
                    <i class="iconfont nav_right">&#xe697;</i></a>
                <ul class="sub-menu">
                    <li>
                        <a onclick="xadmin.add_tab('期末评分','{{url("course/".$course->no."/final_exam_ping")}}')">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>期末评分</cite></a>
                    </li>
                    <li>
                        <a onclick="xadmin.add_tab('期末试卷','{{url("course/".$course->no."/final_exam_file")}}')">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>期末试卷</cite></a>
                    </li>
                </ul>
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
                <i class="layui-icon">&#xe68e;</i>课程首页
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
                <iframe src='{{url('/course/'.$course->id.'/welcome')}}' frameborder="0" scrolling="yes"
                        class="x-iframe"></iframe>
            </div>
        </div>
        <div id="tab_show"></div>
    </div>
</div>
<!-- 右侧主体结束 -->
<!-- 中部结束 -->
<script>
    function back_home() {
        var tabtitle = $(".layui-tab-title li");
        $.each(tabtitle, function () {
            parent.element.tabDelete('xbs_tab', $(this).attr("lay-id"));
        })
        window.location.href = "{{url('teacher/index')}}";
    };

</script>
</body>
</html>