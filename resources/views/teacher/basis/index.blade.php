<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>课程评分管理系统|评分项</title>
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
    <link href="{{asset('statics/bootstrap3-editable/css/bootstrap-editable.css')}}">
    <script src="{{asset('statics/bootstrap3-editable/js/bootstrap-editable.min.js')}}" type="text/javascript"></script>
    {{--引入layui与xadmin的js--}}
    <script src="{{asset('template/lib/layui/layui.js')}}" charset="utf-8"></script>
    <script type="text/javascript" src="{{asset('template/js/xadmin.js')}}"></script>
</head>
<body>

<div class="container" z-index="-1">
    <!--新增评分项按钮-->
    <div class="row" style="padding: 10px">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding: 10px">
            <a class="btn btn-primary" href="{{url("course/".$course->no."/basis/add")}}">新增评分项</a>
        </div>
    </div>
@include('partials.errors')
@include('partials.success')
<!--表格-->
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <table id="basisTable" z-index="-1"></table>
        </div>
    </div>
</div>
</body>
<script type="text/javascript">
    $('#basisTable').bootstrapTable({
        undefinedText: '-',
        striped: true,
        sortable: false,
        sortOrder: "asc",
        pagination: true,
        showRefresh: true,
        search: true,
        pageNumber: 1,
        pageSize: 10,
        columns: [
            {
                field: 'basis_id',
                title: 'ID'
            }, {
                field: 'basis_name',
                title: '评分项',
                formatter: function (value, item, index) {
                    if (item.basis_name === 'signment') {
                        return '<span class="label label-default">签到</span>';
                    } else if (item.basis_name === 'homework') {
                        return '<span class="label label-default">作业</span>';
                    } else if (item.basis_name === 'report') {
                        return '<span class="label label-default">报告</span>';
                    } else if (item.basis_name === 'final_exam') {
                        return '<span class="label label-default">期末考试</span>';
                    }
                }
            }, {
                field: 'basis_weight',
                title: '评分权重',
                editable: {
                    mode: "inline",
                    type: "text"
                }
            }, {
                title: "操作",
                formatter: function (value, row, index) {
                    return '<button class="btn btn-danger btn-sm" onclick="delBasis(\'' + row.basis_id + '\')">删除</button>';
                }
            }],
        url: "{{url("course/".$course->no."/basis/list")}}",
        onEditableSave: function (field, row, oldvalue, $el) {
            $.ajax({
                type: "post",
                url: "{{url("course/".$course->no."/basis/edit")}}",
                data: row,
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                success: function (data) {
                    //表格刷新
                    $("#basisTable").bootstrapTable('refresh');
                    if (data.flag === 1) {
                        layui.use('layer', function () {
                            var layer = layui.layer;
                            layer.msg(data.msg, {icon: 1});

                        });
                    } else {
                        layui.use('layer', function () {
                            var layer = layui.layer;
                            layer.msg(data.msg, {icon: 2});
                        });
                    }
                },
                error: function () {
                    $("#basisTable").bootstrapTable('refresh');
                    layui.use('layer', function () {
                        var layer = layui.layer;
                        layer.msg("请求出错，请重试", {icon: 2});
                    });
                },
            });
        }
    });

    //删除评分项
    function delBasis(id) {
        layer.confirm('确认要删除该评分项吗？', {
            btn: ['确定', '取消']
        }, function () {
            $.post("{{url('course/'.$course->no.'/basis')}}/" + id, {
                '_method': 'delete',
                '_token': '{{csrf_token()}}'
            }, function (data) {
                if (data.status === 0) {
                    layui.use('layer', function () {
                        var layer = layui.layer;
                        layer.msg(data.msg, {icon: 1});
                    });
                } else {
                    layui.use('layer', function () {
                        var layer = layui.layer;
                        layer.msg(data.msg, {icon: 2});
                    });
                }
                $("#basisTable").bootstrapTable('refresh');
            })
        }, function () {
        });
    };
</script>
</html>