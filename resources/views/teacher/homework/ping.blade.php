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
    <!--表格-->
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            {{--课程布置了作业--}}
            @if(count($homeworks))
                @foreach($homeworks as $homework)
                    <table class="HomeworkPingTable" z-index="-1" dataclasses="table" data-undefined-text="-"
                           data-striped="true" data-sort-order="asc" data-sort-stable="true" data-pagination="true"
                           data-page-number="1" data-page-size="10" data-search="true">
                        <thead>
                        <tr>
                            <th>学号</th>
                            <th>学生姓名</th>
                            <th>提交情况</th>
                            <th>提交说明</th>
                            <th>得分</th>
                        </tr>
                        </thead>

                    </table>
                    <br>
                @endforeach
            @else
                <p>课程还没布置作业</p>
            @endif
        </div>
    </div>
</div>
</body>
<script type="text/javascript">
    $(".HomeworkPingTable").bootstrapTable();
</script>
</html>