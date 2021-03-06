<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>课程评分管理系统|期末评分</title>
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
    <script src="{{asset('statics/bootstrap-table/dist/bootstrap-table.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('statics/bootstrap-table/dist/locale/bootstrap-table-zh-CN.js')}}"
            type="text/javascript"></script>
    <link href="{{asset('statics/bootstrap-table/dist/bootstrap-table.min.css')}}" rel="stylesheet">
    <script src="{{asset('statics/bootstrap-table/dist/extensions/editable/bootstrap-table-editable.min.js')}}"
            type="text/javascript"></script>
    <script src="{{asset('statics/bootstrap-table/dist/extensions/export/bootstrap-table-export.min.js')}}"></script>{{--导出扩展--}}
    {{--引入x-editable-develop--}}
    <link href="{{asset('statics/bootstrap3-editable/css/bootstrap-editable.css')}}">
    <script src="{{asset('statics/bootstrap3-editable/js/bootstrap-editable.min.js')}}" type="text/javascript"></script>

    {{--引入tableExport.jquery.plugin--}}
    {{--在客户端保存生成的导出文件--}}
    <script src="{{asset('statics/tableExport/libs/FileSaver/FileSaver.min.js')}}"></script>
    {{--以XLSX（Excel 2007+ XML格式）格式导出表（SheetJS）--}}
    <script src="{{asset('statics/tableExport/libs/js-xlsx/xlsx.core.min.js')}}"></script>
    {{--无论期望的格式如何，最后都包含 tableexport.jquery.plugin（不是tableexport）--}}
    <script src="{{asset('statics/tableExport/tableExport.js')}}"></script>
    {{--引入layui与xadmin的js--}}
    <script src="{{asset('template/lib/layui/layui.js')}}" charset="utf-8"></script>
    <script type="text/javascript" src="{{asset('template/js/xadmin.js')}}"></script>

</head>
<body>
<div class="container" z-index="-1">
    <!--图表按钮-->
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding: 10px">
            <a class="btn btn-success" href="{{url("course/".$course->no."/final_exam_chart")}}">图表</a>
        </div>
    </div>
    @include('partials.errors')
    <div class="row" style="padding: 10px">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <table id="FinalExamTable" class="table table-bordered"></table>
        </div>
    </div>
</div>
</body>

<script>
    $(document).ready(function () {
        $('#FinalExamTable').bootstrapTable({
            //导出
            showExport: true,
            exportTypes: ['csv', 'sql', 'doc', 'excel', 'xlsx'],  //导出文件类型
            exportOptions: {//导出设置
                ignoreColumn: [0],
                fileName: '{{$course->name}} 作业成绩表',//下载文件名称
            },
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
                    field: 'student_no',
                    title: '学生学号'
                }, {
                    field: 'student_name',
                    title: '学生姓名'
                }, {
                    field: 'final_exam_score',
                    title: '期末成绩',
                    editable: {
                        type: "text"
                    }
                }],
            url: "{{url("course/".$course->no."/final_exam_list")}}",
            onEditableSave: function (field, row, oldvalue, $el) {
                $.ajax({
                    type: "post",
                    url: "{{url("course/".$course->no."/final_exam_edit")}}",
                    data: row,
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    success: function (data) {
                        //表格刷新
                        $("#FinalExamTable").bootstrapTable('refresh');
                        if (data.flag !== 0) {
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
                        $("#FinalExamTable").bootstrapTable('refresh');
                        layui.use('layer', function () {
                            var layer = layui.layer;
                            layer.msg("请求出错，请重试", {icon: 2});
                        });
                    },
                });
            }
        });
    });
</script>

</html>