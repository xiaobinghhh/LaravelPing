<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>课程评分管理系统|作业图表</title>
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport"
          content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi"/>
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <meta name="_token" content="{{ csrf_token() }}"/>

    {{--引入bootstrap--}}
    <link href="{{asset('statics/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet"/>
    <script src="{{asset('statics/bootstrap/js/jquery.js')}}"></script>
    <script src="{{asset('statics/bootstrap/js/bootstrap.min.js')}}"></script>
    {{--引入layui与xadmin的js--}}
    <script src="{{asset('template/lib/layui/layui.js')}}" charset="utf-8"></script>
    <script type="text/javascript" src="{{asset('template/js/xadmin.js')}}"></script>

</head>
<body>

<div class="container" z-index="-1">
    <div class="row" style="padding: 10px">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding: 10px">
            <a type="button" class="btn btn-default" href="{{url('course/'.$course->no.'/homework_ping')}}"><span
                        class="glyphicon glyphicon-chevron-left"></span>返回</a>
        </div>
    </div>
@include('partials.errors')
<!-- 图表容器 DOM -->
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div id="column_chart" style="min-width: 400px;height:400px;"></div>
        </div>
    </div>

    <!-- 引入 highcharts.js -->
    <script src="{{asset('statics/highcharts/highcharts.js')}}"></script>
    <script src="{{asset('statics/highcharts/highcharts-3d.js')}}"></script>
    <script src="{{asset('statics/highcharts/highcharts-more.js')}}"></script>
    <script src="{{asset('statics/highcharts/modules/exporting.js')}}"></script>
    <script src="{{asset('statics/highcharts/themes/grid.js')}}"></script>
    {{--    引入自己highchart全局配置js--}}
    <script src="{{asset('statics/highcharts/my_setoption.js')}}"></script>
    <script>
        //获取后台数据
        $.ajax({
            type: 'post',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            url: '{{url('course/'.$course->no.'/homework_chart_data')}}',
            success: function (data) {
                var json = JSON.parse(data);
                var category = JSON.parse(json['xtext']);//X轴文本
                //新建柱状图表
                var column_series = JSON.parse(json['column_series']);
                new Highcharts.Chart({
                    chart: {
                        renderTo: 'column_chart',           //图表放置的容器，关联DIV#id
                        type: 'column',                  //柱状图
                        reflow: true                    //自适应div的大小
                    },
                    title: {
                        text: '\"{{$course->name}}\" 各次作业完成统计'   //图表标题
                    },
                    xAxis: {                            //X轴标签
                        categories: category
                    },
                    yAxis: {                            //设置Y轴
                        title: {
                            text: '人数'
                        },
                        allowDecimals: false
                    },
                    tooltip: {
                        valueSuffix: '人'
                    },
                    credits: {                          //右下角文本不显示
                        enabled: false
                    },
                    series: column_series
                })
            },
        });
    </script>
</div>
</body>
</html>