<html>
<head>
    <meta charset="UTF-8">
    <title>课程评分管理系统|学生-提交报告</title>
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport"
          content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi"/>
    <meta http-equiv="Cache-Control" content="no-siteapp"/>

    <script type="text/javascript" src="{{asset('template/js/jquery.min.js')}}"></script>

    {{--引入bootstrap--}}
    <link href="{{asset('statics/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet"/>
    {{--    <script src="{{asset('statics/bootstrap/js/jquery.js')}}"></script>--}}
    <script src="{{asset('statics/bootstrap/js/bootstrap.min.js')}}"></script>

    <style type="text/css">
        label.error {
            font-size: small;
            font-weight: bold;
            color: red;
        }
    </style>

</head>
<body>

<!--主体 开始-->
<div class="container" z-index="-1">
    <div class="row" style="padding: 10px">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding: 10px">
            <a type="button" class="btn btn-default" href="{{url("student/course/".$course->no."/report")}}"><span
                        class="glyphicon glyphicon-chevron-left"></span>返回</a>
        </div>
    </div>
    @include('partials.errors')
    <div class="row" style="padding: 10px">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <form action="{{url("student/course/".$course->no."/report/".$report->report_id."/commit")}}"
                  method="post"
                  id="form_report_commit"
                  enctype="multipart/form-data" style="padding-top: 10px">
                {{csrf_field()}}
                <table class="table">
                    <tbody>
                    <tr>
                        <th>报告名称</th>
                        <td>
                            <div class="col-sm-6">
                                <input type="text" size="16" class="form-control" value="{{$report->name}}" readonly>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>报告描述</th>
                        <td>
                            <div class=" col-sm-6">
                                <textarea class="form-control" rows="4" readonly>{{$report->description}}</textarea>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>剩余天数</th>
                        <td>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" value="{{$days}}" readonly>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>报告文件</th>
                        @if($report->src!=null&&$report->src!='')
                            <td>
                                <div class="col-sm-6">
                                    附件：<a href="{{$report->src}}">{{$report->name}}</a>
                                </div>
                            </td>
                        @else
                            <td>
                                <div class="col-sm-6">
                                    无文件
                                </div>
                            </td>
                        @endif
                    </tr>
                    <tr>
                        <th>提交文件</th>
                        <td>
                            <div class="col-sm-6">
                                <label class="sr-only" for="inputfile">选择</label>
                                <input type="file" id="inputfile" name="commit_src">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th><i style="color: red"></i>提交描述</th>
                        <td>
                            <div class="col-sm-6">
                                <textarea class="form-control" name="commit_desc" rows="4"></textarea>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th></th>
                        <td>
                            <div class="col-sm-6">
                                <input type="submit" class="btn btn-primary" value="提交">
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </form>
        </div>
    </div>

</div>
<!--主体 结束-->

</body>

{{--引入jquery.validation--}}
<script type="text/javascript" src="{{asset('statics/jquery.validation/jquery.validate.js')}}"></script>
<script type="text/javascript" src="{{asset('statics/jquery.validation/additional-methods.js')}}"></script>
<script type="text/javascript" src="{{asset('statics/jquery.validation/validate-methods.js')}}"></script>
<script type="text/javascript" src="{{asset('statics/jquery.validation/messages_zh.js')}}"></script>

<script type="text/javascript">
    $("#form_report_commit").validate({
        rules: {
            commit_desc: {
                required: true,
            }
        },
        messages: {
            commit_desc: {
                required: "请为完成本次报告做些描述",
            }
        },
    });
</script>
</html>
