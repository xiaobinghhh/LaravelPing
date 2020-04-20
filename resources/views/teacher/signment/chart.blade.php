<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>课程评分管理系统|签到统计</title>
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
            <a type="button" class="btn btn-default" onclick="history.go(-1)"><span
                        class="glyphicon glyphicon-chevron-left"></span>返回</a>
        </div>
    </div>
@include('partials.errors')
<!-- 图表容器 DOM -->
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div id="column_chart" style="min-width: 400px;height:400px;"></div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div id="line_chart" style="min-width: 400px;height:400px;"></div>
        </div>
    </div>

    <!-- 引入 highcharts.js -->
    <script src="{{asset('statics/highcharts/highcharts.js')}}"></script>
    <script src="{{asset('statics/highcharts/highcharts-3d.js')}}"></script>
    <script src="{{asset('statics/highcharts/highcharts-more.js')}}"></script>
    <script src="{{asset('statics/highcharts/modules/exporting.js')}}"></script>
    <script src="{{asset('statics/highcharts/themes/grid.js')}}"></script>
    <script>
        Highcharts.setOptions({
            lang: {
                contextButtonTitle: "图表导出菜单",
                decimalPoint: ".",
                downloadJPEG: "下载JPEG图片",
                downloadPDF: "下载PDF文件",
                downloadPNG: "下载PNG文件",
                downloadSVG: "下载SVG文件",
                drillUpText: "返回 {series.name}",
                loading: "加载中",
                months: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
                noData: "没有数据",
                numericSymbols: ["千", "兆", "G", "T", "P", "E"],
                printChart: "打印图表",
                resetZoom: "恢复缩放",
                resetZoomTitle: "恢复图表",
                shortMonths: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                thousandsSep: ",",
                weekdays: ["星期一", "星期二", "星期三", "星期四", "星期五", "星期六", "星期天"]
            },
            global: {
                timezoneOffset: +8 * 60  // +8 时区修正方法
            }
        });
        //获取后台数据
        $.ajax({
            type: 'post',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            url: '{{url('course/'.$course->no.'/signment_chart_data')}}',
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
                        text: '\"{{$course->name}}\" 签到统计'   //图表标题
                    },
                    xAxis: {                            //X轴标签
                        categories: category
                    },
                    yAxis: {                            //设置Y轴
                        title: {
                            text: '人数'
                        }
                    },
                    credits: {                          //右下角文本不显示
                        enabled: false
                    },
                    series: column_series
                })
                //新建折线图表
                {{--var line_series = JSON.parse(json['line_series']);--}}
                {{--new Highcharts.Chart({--}}
                {{--    chart: {--}}
                {{--        renderTo: 'line_chart',           //图表放置的容器，关联DIV#id--}}
                {{--        type: 'line',--}}
                {{--        reflow: true                    //自适应div的大小--}}
                {{--    },--}}
                {{--    title: {--}}
                {{--        text: '\"{{$course->name}}\" 出勤率变化'   //图表标题--}}
                {{--    },--}}
                {{--    xAxis: {                            //X轴标签--}}
                {{--        categories: category--}}
                {{--    },--}}
                {{--    yAxis: {                            //设置Y轴--}}
                {{--        title: {--}}
                {{--            text: '百分比'--}}
                {{--        }--}}
                {{--    },--}}
                {{--    credits: {                          //右下角文本不显示--}}
                {{--        enabled: false--}}
                {{--    },--}}
                {{--    series: line_series--}}
                {{--})--}}
            },
        });
    </script>
</div>
</body>
</html>