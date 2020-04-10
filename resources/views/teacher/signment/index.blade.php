<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>课程评分管理系统|签到评分</title>
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
    {{--引入bootstrap-table--}}
    <script src="{{asset('statics/bootstrap-table/dist/bootstrap-table.js')}}" type="text/javascript"></script>
    <script src="{{asset('statics/bootstrap-table/dist/locale/bootstrap-table-zh-CN.js')}}"
            type="text/javascript"></script>
    <link href="{{asset('statics/bootstrap-table/dist/bootstrap-table.css')}}" rel="stylesheet">
    <script src="{{asset('statics/bootstrap-table/dist/extensions/editable/bootstrap-table-editable.js')}}"
            type="text/javascript"></script>
    {{--引入x-editable-develop--}}
    <script src="{{asset('statics/bootstrap3-editable/js/bootstrap-editable.js')}}" type="text/javascript"></script>
    {{--引入layui与xadmin的js--}}
    <script src="{{asset('template/lib/layui/layui.js')}}" charset="utf-8"></script>
    <script type="text/javascript" src="{{asset('template/js/xadmin.js')}}"></script>

</head>
<body>

<div class="container" z-index="-1">
    <!--新的签到按钮-->
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding: 10px">
            <a class="btn btn-primary" href="{{url("course/".$course->no."/signment_add")}}">新的签到</a>
        </div>
    </div>
    <!--签到计分提示-->
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <i style="color: red;">*</i>
            <span style="color: grey;">(计分方式：缺勤0分，出勤100分。按签到次数求均值为签到所得分)</span>
        </div>
    </div>
    <!--表格-->
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <table id="SignTable" z-index="-1" dataclasses="table" data-undefined-text="-" data-striped="true"
                   data-sort-order="asc" data-sort-stable="true" data-pagination="true" data-page-number="1"
                   data-page-size="10" data-search="true"></table>
        </div>
    </div>
</div>
</body>
<script type="text/javascript">
    $(document).ready(function () {
        //使用ajax加载动态列的
        var columns = [];
        $.ajax({
            url: "{{url("course/".$course->no."/signment_columns")}}",
            async: true,
            success: function (returnValue) {
                //异步获取要动态生成的列
                var arr = JSON.parse(returnValue);
                $.each(arr, function (i, item) {
                    if (item.colname.search('sign_data') !== -1) {
                        columns.push({"field": item.colname, "title": item.colalias, "width": 100, "editable": true});
                    } else {
                        columns.push({"field": item.colname, "title": item.colalias, "width": 100, "sortable": true});
                    }
                });
                $('#SignTable').bootstrapTable('destroy').bootstrapTable({
                    columns: columns,
                    url: "{{url("course/".$course->no."/signment_list")}}",
                    onEditableSave: function (field, row, oldvalue, $el) {
                        $.ajax({
                            type: "post",
                            url: "{{url("course/".$course->no."/signment_edit")}}",
                            data: row,
                            dataType: 'json',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                            },
                            success: function (data) {
                                if (data.flag === 1) {
                                    layui.use('layer', function () {
                                        var layer = layui.layer;
                                        layer.msg(data.msg, {icon: 1});
                                        //表格刷新
                                        $("#SignTable").bootstrapTable('refresh');
                                    });
                                } else {
                                    layui.use('layer', function () {
                                        var layer = layui.layer;
                                        layer.msg(data.msg, {icon: 2});
                                        $("#SignTable").bootstrapTable('refresh');
                                    });
                                }
                            },
                            error: function () {
                                layui.use('layer', function () {
                                    var layer = layui.layer;
                                    layer.msg("请求出错，请重试", {icon: 2});
                                    $("#SignTable").bootstrapTable('refresh');
                                });
                            },
                        });
                    }
                });
            }
        });
    });
</script>
</html>