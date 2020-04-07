<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>课程评分管理系统|课程欢迎页面</title>
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
                <div class="layui-card-header">数据统计</div>
                <div class="layui-card-body ">
                    <ul class="layui-row layui-col-space10 layui-this x-admin-carousel x-admin-backlog">
                        <li class="layui-col-md2 layui-col-xs6">
                            <a href="javascript:;" class="x-admin-backlog-body">
                                <h3>文章数</h3>
                                <p>
                                    <cite>66</cite></p>
                            </a>
                        </li>
                        <li class="layui-col-md2 layui-col-xs6">
                            <a href="javascript:;" class="x-admin-backlog-body">
                                <h3>会员数</h3>
                                <p>
                                    <cite>12</cite></p>
                            </a>
                        </li>
                        <li class="layui-col-md2 layui-col-xs6">
                            <a href="javascript:;" class="x-admin-backlog-body">
                                <h3>回复数</h3>
                                <p>
                                    <cite>99</cite></p>
                            </a>
                        </li>
                        <li class="layui-col-md2 layui-col-xs6">
                            <a href="javascript:;" class="x-admin-backlog-body">
                                <h3>商品数</h3>
                                <p>
                                    <cite>67</cite></p>
                            </a>
                        </li>
                        <li class="layui-col-md2 layui-col-xs6">
                            <a href="javascript:;" class="x-admin-backlog-body">
                                <h3>文章数</h3>
                                <p>
                                    <cite>67</cite></p>
                            </a>
                        </li>
                        <li class="layui-col-md2 layui-col-xs6 ">
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
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">课程学生</div>
                <div class="layui-card-body ">
                    <table class="layui-table">
                        <tbody>
                        <tr>
                            <th>学生</th>
                        </tr>
                        @foreach($students as $student)
                            <tr>
                                <th>{{$student->name}}</th>
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
        <div class="layui-col-md12">
            <blockquote class="layui-elem-quote layui-quote-nm">感谢layui,百度Echarts,jquery,本系统由x-admin提供技术支持。</blockquote>
        </div>
    </div>
</div>
</body>

</html>