<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>课程评分管理系统|报告评分</title>
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
    <script src="{{asset('statics/bootstrap-table/dist/extensions/export/bootstrap-table-export.min.js')}}"></script>{{--导出扩展--}}
    {{--引入x-editable-develop--}}
    <link href="{{asset('statics/bootstrap3-editable/css/bootstrap-editable.css')}}">
    <script src="{{asset('statics/bootstrap3-editable/js/bootstrap-editable.js')}}" type="text/javascript"></script>
    {{--引入tableExport.jquery.plugin--}}
    {{--在客户端保存生成的导出文件--}}
    <script src="{{asset('statics/tableExport/libs/FileSaver/FileSaver.min.js')}}"></script>
    {{--以XLSX（Excel 2007+ XML格式）格式导出表（SheetJS）--}}
    <script src="{{asset('statics/tableExport/libs/js-xlsx/xlsx.core.min.js')}}"></script>
    {{--无论期望的格式如何，最后都包含 tableexport.jquery.plugin（不是tableexport）--}}
    <script src="{{asset('statics/tableExport/tableExport.js')}}"></script>
    {{--引入layui的js--}}
    <script src="{{asset('template/lib/layui/layui.js')}}" charset="utf-8"></script>

</head>
<body>

<div class="container" z-index="-1">
    <!--图表按钮-->
    @if(count($reports))
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding: 10px">
                <a class="btn btn-success" href="{{url("course/".$course->no."/report_chart")}}">图表</a>
            </div>
        </div>
@endif
    <!--表格-->
    <div class="row" style="padding: 10px">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            {{--课程布置了报告--}}
            @if(count($reports))
                <table id="reportPingTable" z-index="-1"
                       data-detail-formatter="detailFormatter">
                </table>
            @else
                <div class="jumbotron">
                    <h3>该课程还没有报告！</h3>
                    <p><a onclick="parent.xadmin.add_tab('报告列表','{{url("course/".$course->no."/report")}}',true)"
                          class="btn btn-primary btn-lg" role="button">前往发布</a>
                    </p>
                </div>
            @endif
        </div>
    </div>

</div>
</body>
<script type="text/javascript">
    $(document).ready(function () {
        //使用ajax加载动态列的
        var columns = [];
        $.ajax({
            url: "{{url("course/".$course->no."/report_ping_columns")}}",
            async: true,
            success: function (returnValue) {
                //异步获取要动态生成的列
                var arr = JSON.parse(returnValue);
                var regNumber = /\d+/; //验证0-9的任意数字最少出现1次。
                $.each(arr, function (i, item) {
                    //出现数字，说明是报告提交列
                    if (regNumber.test(item.colname)) {
                        columns.push({
                            "field": item.colname,
                            "title": item.colalias,
                            "editable": {
                                mode: "inline",
                                validate: function (value) { //字段验证
                                    if (!$.trim(value)) {
                                        return '不能为空';
                                    }
                                }
                            },
                            "sortable": true
                        });
                    } else {
                        columns.push({"field": item.colname, "title": item.colalias, "sortable": true});
                    }
                });
                $('#reportPingTable').bootstrapTable('destroy').bootstrapTable({
                    detailView: true,
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
                    columns: columns,
                    url: "{{url("course/".$course->no."/report_ping_list")}}",
                    onEditableSave: function (field, row, oldvalue, $el) {
                        $.ajax({
                            type: "post",
                            url: "{{url("course/".$course->no."/report_ping_edit")}}",
                            data: row,
                            dataType: 'json',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                            },
                            success: function (data) {
                                //表格刷新
                                $("#reportPingTable").bootstrapTable('refresh');
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
                                $("#reportPingTable").bootstrapTable('refresh');
                                layui.use('layer', function () {
                                    var layer = layui.layer;
                                    layer.msg("请求出错，请重试", {icon: 2});
                                });
                            },
                        });
                    }
                });
            }
        });
    });

    function detailFormatter(index, row) {
        var html = [];
        html.push('<table style="padding:20px;">');
        $.each(row, function (key, value) {
            //展开详情中的数据
            if (key.search('report_commit_details_') !== -1) {
                if (value[2]) {
                    if (value[1] !== "无文件") {
                        html.push('<tr style="padding: 10px">' +
                            '<td style="padding: 10px"><b>' + value[0] + '</b></td>' +
                            '<td style="padding: 10px">提交文件：</td>' +
                            '<td style="padding: 10px"><a href="' + value[1] + '">文件</a></td>' +
                            '<td style="padding: 10px">提交说明：</td>' +
                            '<td style="padding: 10px">' + value[2] + '</td>' +
                            '</tr>'
                        );
                    } else {
                        html.push(
                            '<tr style="padding: 10px">' +
                            '<td style="padding: 10px"><b>' + value[0] + '</b></td>' +
                            '<td style="padding: 10px">提交文件：</td>' +
                            '<td style="padding: 10px">' + value[1] + '</td>' +
                            '<td style="padding: 10px">提交说明：</td>' +
                            '<td style="padding: 10px">' + value[2] + '</td>' +
                            '</tr>');
                    }
                } else {
                    html.push(
                        '<tr style="padding: 10px">' +
                        '<td style="padding: 10px"><b>' + value[0] + '<b></td>' +
                        '<td style="padding: 10px">' + value[1] + '</td>' +
                        '</tr>');
                }
            }
        });
        html.push('</table>');
        return html.join('');
    }
</script>
</html>