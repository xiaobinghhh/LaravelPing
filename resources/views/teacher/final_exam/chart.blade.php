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
            <a type="button" class="btn btn-default" href="{{url('course/'.$course->no.'/final_exam_ping')}}"><span
                        class="glyphicon glyphicon-chevron-left"></span>返回</a>
        </div>
    </div>
@include('partials.errors')
<!-- 图表容器 DOM -->
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div id="top_chart" style="min-width: 400px;height:400px;"></div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div id="score_range_chart" style="min-width: 400px;height:400px;"></div>
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
            url: '{{url('course/'.$course->no.'/final_exam_chart_data')}}',
            success: function (data) {
                var j_data = JSON.parse(data);//获取图表数据
                //绘制柱状图
                Highcharts.chart('top_chart', {
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: '{{$course->name}} 期末成绩柱状图'
                    },
                    credits: {
                        enabled: false
                    },
                    xAxis: {
                        type: 'category'
                    },
                    yAxis: {
                        title: {
                            text: '分数'
                        },
                        max: 100
                    },
                    legend: {
                        enabled: false
                    },
                    plotOptions: {
                        series: {
                            borderWidth: 0,
                            dataLabels: {
                                enabled: true,
                                format: '{point.y}分'
                            }
                        }
                    },
                    tooltip: {
                        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}分</b><br/>'
                    },
                    series: [{
                        name: '期末成绩',
                        colorByPoint: true,
                        data: j_data['scores']
                    }],
                });
                //绘制扇形图
                var chart = {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false
                };
                var title = {
                    text: '{{$course->name}} 期末考试各分数段人数比例'
                };
                var tooltip = {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                };
                var plotOptions = {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                            style: {
                                color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                            }
                        }
                    }
                };
                var credits = {                          //右下角文本不显示
                    enabled: false
                };
                var series = [{
                    type: 'pie',
                    name: '人数占比',
                    //获得各分数段的成绩占比
                    data: j_data['range_data'],
                }];
                var pie_json = {};
                pie_json.chart = chart;
                pie_json.title = title;
                pie_json.tooltip = tooltip;
                pie_json.series = series;
                pie_json.credits = credits;
                pie_json.plotOptions = plotOptions;
                $('#score_range_chart').highcharts(pie_json);
            },
        });
    </script>
</div>
</body>
</html>