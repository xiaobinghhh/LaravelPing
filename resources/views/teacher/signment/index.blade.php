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

</head>
<body>
<div class="container" z-index="-1">
    <!--表格-->
    <div class="row">
        <div class="col-lg-offset-1 col-lg-11   col-md-offset-1 col-md-11 col-sm-offset-1 col-sm-11 col-xs-offset-2 col-xs-10">
            <table id="SignTable" z-index="-1" dataclasses="table" data-undefined-text="-" data-striped="true"
                   data-sort-order="asc" data-sort-stable="true" data-pagination="true" data-page-number="1"
                   data-page-size="10" data-search="true"></table>
        </div>
    </div>
</div>

</body>
<script type="text/javascript">
    $(document).ready(function () {
        $('#SignTable').bootstrapTable({
            columns: [
                {
                    field: 'student_no',
                    title: '学号'
                }, {
                    field: 'student_name',
                    title: '学生姓名'
                }, {
                    field: 'sign_score',
                    title: '签到成绩',
                    editable: {
                        type: "text"
                    }
                }],
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
                    success: function (data, status) {
                        if (status == "success") {
                            alert('提交数据成功');
                        }
                    },
                    error: function () {
                        alert('编辑失败');
                    },
                    complete: function () {
                        alert('完成');
                    }
                });
            }
        });
    });
</script>
</html>