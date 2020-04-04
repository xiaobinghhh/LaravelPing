<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>课程评分管理系统|欢迎页面</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport"
          content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi"/>
    <link rel="shortcut icon" href="{{asset('template/favicon.ico')}}" type="image/x-icon"/>
    <link rel="stylesheet" href="{{asset('template/css/font.css')}}">
    <link rel="stylesheet" href="{{asset('template/css/xadmin.css')}}">
</head>
<body>
<div class="x-body layui-anim layui-anim-up" style="top:0px">
    <blockquote class="layui-elem-quote">欢迎老师:
        <span class="x-red">{{session('teacherInfo')['name']}}</span>！当前时间:{{now()->toDateTimeString('Y-m-d H:i:s')}}
    </blockquote>
    <fieldset class="layui-elem-field">
        <legend>数据统计</legend>
        <div class="layui-field-box">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <div class="layui-carousel x-admin-carousel x-admin-backlog" lay-anim="" lay-indicator="inside"
                             lay-arrow="none" style="width: 100%; height: 90px;">
                            <div carousel-item="">
                                <ul class="layui-row layui-col-space10 layui-this">
                                    <li class="layui-col-xs2">
                                        <a href="javascript:;" class="x-admin-backlog-body">
                                            <h3>文章数</h3>
                                            <p>
                                                <cite>66</cite></p>
                                        </a>
                                    </li>
                                    <li class="layui-col-xs2">
                                        <a href="javascript:;" class="x-admin-backlog-body">
                                            <h3>会员数</h3>
                                            <p>
                                                <cite>12</cite></p>
                                        </a>
                                    </li>
                                    <li class="layui-col-xs2">
                                        <a href="javascript:;" class="x-admin-backlog-body">
                                            <h3>回复数</h3>
                                            <p>
                                                <cite>99</cite></p>
                                        </a>
                                    </li>
                                    <li class="layui-col-xs2">
                                        <a href="javascript:;" class="x-admin-backlog-body">
                                            <h3>商品数</h3>
                                            <p>
                                                <cite>67</cite></p>
                                        </a>
                                    </li>
                                    <li class="layui-col-xs2">
                                        <a href="javascript:;" class="x-admin-backlog-body">
                                            <h3>文章数</h3>
                                            <p>
                                                <cite>67</cite></p>
                                        </a>
                                    </li>
                                    <li class="layui-col-xs2">
                                        <a href="javascript:;" class="x-admin-backlog-body">
                                            <h3>文章数</h3>
                                            <p>
                                                <cite>6766</cite></p>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </fieldset>
    <fieldset class="layui-elem-field">
        <legend>系统通知</legend>
        <div class="layui-field-box">
            <table class="layui-table" lay-skin="line">
                <tbody>
                <tr>
                    <td>
                        <a class="x-a" href="javascript:;">新版LaravelPing 2.0上线了</a>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </fieldset>
    <fieldset class="layui-elem-field">
        <legend>课程列表</legend>
        <div class="layui-field-box">
            <table class="layui-table">
                <tbody>
                <tr>
                    <th style="font-weight: bold">课程名称</th>
                    <td style="font-weight: bold">开始时间</td>
                    <td style="font-weight: bold">结束时间</td>
                    <td style="font-weight: bold">地点</td>
                    <td style="font-weight: bold">学分</td>
                    <td style="font-weight: bold">课时</td>
                    <td></td>
                </tr>
                @foreach($courses as $course)
                    <tr>
                        <th>{{$course->name}}</th>
                        <td>{{$course->begin_at}}</td>
                        <td>{{$course->end_at}}</td>
                        <td>{{$course->place}}</td>
                        <td>{{$course->credit}}</td>
                        <td>{{$course->period}}</td>
                        <td><a href="javascript:;" class="x-a">进入课程</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </fieldset>
    <fieldset class="layui-elem-field">
        <legend>开发团队</legend>
        <div class="layui-field-box">
            <table class="layui-table">
                <tbody>
                <tr>
                    <th>开发者</th>
                    <td>小冰</td>
                </tr>
                <tr>
                    <th>技术</th>
                    <td>Laravel5.5
                        <a href="https://learnku.com/docs/laravel/5.5" class='x-a' target="_blank">中文文档</a></td>
                </tr>
                </tbody>
            </table>
        </div>
    </fieldset>
    <blockquote class="layui-elem-quote layui-quote-nm">感谢layui,百度Echarts,jquery,本系统由x-admin提供技术支持。</blockquote>
</div>
</body>
</html>