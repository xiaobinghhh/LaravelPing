<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>课程评分管理系统|我的期末考试页面</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport"
          content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi"/>
    <link rel="shortcut icon" href="{{asset('/favicon.ico')}}" type="image/x-icon"/>
    <link rel="stylesheet" href="{{asset('template/css/font.css')}}">
    <link rel="stylesheet" href="{{asset('template/css/xadmin.css')}}">
    <!-- jQuery -->
    <script type="text/javascript" charset="utf8" src="{{asset('statics/bootstrap/js/jquery.js')}}"></script>

    <style>
        table > tbody > tr > td {
            text-align: center;
        }
    </style>
</head>
<body>
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">数据统计</div>
                <div class="layui-card-body ">
                    <ul class="layui-row layui-col-space10 layui-this x-admin-carousel x-admin-backlog">
                        <li class="layui-col-md2 layui-col-xs6">
                            <a href="javascript:;" class="x-admin-backlog-body">
                                <h3>期末成绩</h3>
                                <p>
                                    <cite>{{$final_exam_score}}</cite></p>
                            </a>
                        </li>
                        <li class="layui-col-md2 layui-col-xs6">
                            <a href="javascript:;" class="x-admin-backlog-body">
                                <h3>期末排名</h3>
                                <p>
                                    <cite>{{$rank_str}}</cite></p>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
</body>

</html>