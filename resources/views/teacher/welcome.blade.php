<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>课程评分管理系统|欢迎页面</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport"
          content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi"/>
    <link rel="shortcut icon" href="{{asset('/favicon.ico')}}" type="image/x-icon"/>
    <link rel="stylesheet" href="{{asset('template/css/font.css')}}">
    <link rel="stylesheet" href="{{asset('template/css/xadmin.css')}}">
</head>
<body>
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-body ">
                    <blockquote class="layui-elem-quote">欢迎老师:
                        <span class="x-red">{{session('teacherInfo')['name']}}</span>！当前时间:{{now()->toDateTimeString('Y-m-d H:i:s')}}
                    </blockquote>
                </div>
            </div>
        </div>
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">课程列表</div>
                <div class="layui-card-body ">
                    <table class="layui-table">
                        <thead>
                        <tr>
                            <th>课程名称</th>
                            <th>开始时间</th>
                            <th>结束时间</th>
                            <th>地点</th>
                            <th>学分</th>
                            <th>课时</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($courses as $course)
                            <tr>
                                <td>{{$course->name}}</td>
                                <td>{{$course->begin_at}}</td>
                                <td>{{$course->end_at}}</td>
                                <td>{{$course->place}}</td>
                                <td>{{$course->credit}}</td>
                                <td>{{$course->period}}</td>
                                <td>
                                    <a href="{{url('/course/'.$course->no)}}" class="layui-btn layui-btn-fluid"
                                       target="_parent">进入课程</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">开发团队</div>
                <div class="layui-card-body ">
                    <table class="layui-table">
                        <tbody>
                        <tr>
                            <th>开发者</th>
                            <td>小冰</td>
                        </tr>
                        <tr>
                            <th>技术</th>
                            <td>Laravel5.5
                                <a href="https://learnku.com/docs/laravel/5.5" style="color: #0d6aad" target="_blank">中文文档</a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <style id="welcome_style"></style>
        <div class="layui-col-md12">
            <blockquote class="layui-elem-quote layui-quote-nm">感谢layui,百度Echarts,jquery,本系统由x-admin提供技术支持。</blockquote>
        </div>
    </div>
</div>

</body>
</html>