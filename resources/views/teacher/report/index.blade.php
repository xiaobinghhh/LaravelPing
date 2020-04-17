<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>课程评分管理系统|报告列表</title>
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport"
          content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi"/>
    <meta http-equiv="Cache-Control" content="no-siteapp"/>

    {{--引入bootstrap--}}
    <link href="{{asset('statics/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet"/>
    <script src="{{asset('statics/bootstrap/js/jquery.js')}}"></script>
    <script src="{{asset('statics/bootstrap/js/bootstrap.min.js')}}"></script>
    {{--引入bootstrap-table--}}
    <script src="{{asset('statics/bootstrap-table/dist/bootstrap-table.js')}}" type="text/javascript"></script>
    <script src="{{asset('statics/bootstrap-table/dist/locale/bootstrap-table-zh-CN.js')}}"
            type="text/javascript"></script>
    <link href="{{asset('statics/bootstrap-table/dist/bootstrap-table.css')}}" rel="stylesheet">
    <script src="{{asset('statics/bootstrap-table/dist/extensions/editable/bootstrap-table-editable.js')}}"
            type="text/javascript"></script>
    {{--引入layui与xadmin的js--}}
    <script src="{{asset('template/lib/layui/layui.js')}}" charset="utf-8"></script>
    <script type="text/javascript" src="{{asset('template/js/xadmin.js')}}"></script>
</head>
<body>

<div class="container" z-index="-1">
    <!--发布报告按钮-->
    <div class="row" style="padding: 10px">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding: 10px">
            <a class="btn btn-primary" href="{{url("course/".$course->no."/report/create")}}">发布报告</a>
        </div>
    </div>
@include('partials.errors')
@include('partials.success')
<!--表格-->
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <table id="reportTable" z-index="-1" dataclasses="table" data-undefined-text="-" data-striped="true"
                   data-sort-order="asc" data-sort-stable="true" data-pagination="true" data-page-number="1"
                   data-page-size="10" data-search="true">
                <thead>
                <tr>
                    <th>报告</th>
                    <th>报告描述</th>
                    <th>报告文件</th>
                    <th>开始日期</th>
                    <th>结束日期</th>
                    <th data-sortable="false">操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach($reports as $report)
                    <tr>
                        <td>{{$report->name}}</td>
                        <td>{{$report->description}}</td>
                        @if($report->src)
                            <td><a href="{{$report->src}}">{{$report->name}}</a></td>
                        @else
                            <td>无</td>
                        @endif
                        <td>{{$report->start_at}}</td>
                        <td>{{$report->end_at}}</td>
                        <td>
                            <a class="btn btn-xs btn-success"
                               href="{{url('course/'.$course->no.'/report/'.$report->report_id.'/edit')}}">
                                <i class="fa fa-times-circle fa-lg"></i>修改</a>
                            <button type="button" class="btn btn-xs btn-danger"
                                    onclick="delete_report({{ $report->id }})">
                                <i class="fa fa-times-circle fa-lg"></i>
                                删除
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
<script type="text/javascript">
    $(document).ready(function () {
        $('#reportTable').bootstrapTable();
    });

    /*报告-删除*/
    function delete_report(id) {
        layer.confirm('确认要删除该报告吗？', {
            btn: ['确定', '取消']
        }, function () {
            $.post("{{url('course/'.$course->no.'/report')}}/" + id, {
                '_method': 'delete',
                '_token': '{{csrf_token()}}'
            }, function (data) {
                if (data.status === 0) {
                    layui.use('layer', function () {
                        location.href = location.href;
                        var layer = layui.layer;
                        layer.msg(data.msg, {icon: 1});

                    });
                } else {
                    layui.use('layer', function () {
                        var layer = layui.layer;
                        layer.msg(data.msg, {icon: 2});
                    });
                }
            })
        }, function () {
        });
    }
</script>
</html>