<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>课程评分管理系统|学生-报告列表</title>
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
@include('partials.errors')
@include('partials.success')
<!--表格-->
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <table id="HomeworkTable" z-index="-1" dataclasses="table" data-undefined-text="-" data-striped="true"
                   data-sort-order="asc" data-sort-stable="true" data-pagination="true" data-page-number="1"
                   data-page-size="10" data-search="true">
                <thead>
                <tr>
                    <th>报告</th>
                    <th>报告描述</th>
                    <th>报告文件</th>
                    <th>剩余天数</th>
                    <th>完成状态</th>
                    <th>提交文件</th>
                    <th>完成描述</th>
                    <th data-sortable="false">操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach($data as $k=>$v)
                    <tr>
                        <td>{{$v[0]['report_name']}}</td>
                        <td>{{$v[0]['report_desc']}}</td>
                        @if($v[0]['report_src'])
                            <td><a href="{{$v[0]['report_src']}}">{{$v[0]['report_name']}}</a></td>
                        @else
                            <td>-</td>
                        @endif
                        <td>{{$v[0]['time_left']}}</td>
                        <td>
                            @if($v[0]['status']=='已完成')
                                <span class="label label-success">{{$v[0]['status']}}</span>
                            @else
                                <span class="label label-default">{{$v[0]['status']}}</span>
                            @endif
                        </td>
                        @if($v[0]['src'])
                            <td><a href="{{$v[0]['src']}}">{{session('studentInfo')['name']}}
                                    _{{$v[0]['report_name']}}</a>
                            </td>
                        @else
                            <td>-</td>
                        @endif
                        @if($v[0]['commit_desc'])
                            <td>{{$v[0]['commit_desc']}}</td>
                        @else
                            <td>-</td>
                        @endif
                        @if($v[0]['status']=='已完成')
                            <td>
                                <a class="btn btn-xs btn-success"
                                   href="{{url('student/course/'.$course->no.'/report/'.$v[0]['report_id'].'/edit')}}">
                                    修改</a>
                            </td>
                        @else
                            <td>
                                <a class="btn btn-xs btn-primary"
                                   href="{{url('student/course/'.$course->no.'/report/'.$v[0]['report_id'].'/commit')}}">
                                    提交</a>
                            </td>
                        @endif

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
        $('#HomeworkTable').bootstrapTable();
    });
</script>
</html>