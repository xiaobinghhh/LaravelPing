<html>
<head>
    <meta charset="UTF-8">
    <title>课程评分管理系统|发布报告</title>
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
            <a type="button" class="btn btn-default" href="{{url("course/".$course->no."/report")}}"><span
                        class="glyphicon glyphicon-chevron-left"></span>返回</a>
        </div>
    </div>
    @include('partials.errors')
    <div class="row" style="padding: 10px">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <form action="{{url("course/".$course->id."/report")}}" method="post" id="form_report_add"
                  enctype="multipart/form-data" style="padding-top: 10px">
                {{csrf_field()}}
                <table class="table">
                    <tbody>
                    <tr>
                        <th><i style="color: red;">*</i>报告名称</th>
                        <td>
                            <div class="col-sm-6">
                                <input type="text" size="16" name="report_name" class="form-control"
                                       placeholder="报告名称可以写16个字">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th><i style="color: red;">*</i>开始日期</th>
                        <td>
                            <div class="col-sm-6">
                                <input type="text" placeholder="选择日期" class="form-control"
                                       name="start_at" id="start_at" readonly>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th><i style="color: red;">*</i>结束日期</th>
                        <td>
                            <div class="col-sm-6">
                                <input type="text" placeholder="选择日期" class="form-control"
                                       name="end_at" id="end_at" readonly>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th><i style="color: red;">*</i>报告描述</th>
                        <td>
                            <div class="col-sm-6">
                                <textarea name="report_desc" class="form-control" rows="4"></textarea>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>报告文件</th>
                        <td>
                            <div class="col-sm-6">
                                <label class="sr-only" for="inputfile">选择</label>
                                <input type="file" id="inputfile" name="report_file">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th></th>
                        <td>
                            <div class="col-sm-6">
                                <input type="submit" class="btn btn-primary" value="发布">
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

{{--引入datetimepicker--}}
<script type="text/javascript"
        src="{{asset('statics/datetimepicker/js/bootstrap-datetimepicker.min.js')}}"></script>
<script type="text/javascript"
        src="{{asset('statics/datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js')}}"></script>
<link href="{{asset('statics/datetimepicker/css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet">

{{--引入jquery.validation--}}
<script type="text/javascript" src="{{asset('statics/jquery.validation/jquery.validate.js')}}"></script>
<script type="text/javascript" src="{{asset('statics/jquery.validation/additional-methods.js')}}"></script>
<script type="text/javascript" src="{{asset('statics/jquery.validation/validate-methods.js')}}"></script>
<script type="text/javascript" src="{{asset('statics/jquery.validation/messages_zh.js')}}"></script>

<script type="text/javascript">
    $('#start_at').datetimepicker({
        minView: "month",//设置只显示到月份
        format: 'yyyy-mm-dd',
        language: "zh-CN",
        autoclose: true,

    });

    $('#end_at').datetimepicker({
        minView: "month",//设置只显示到月份
        format: 'yyyy-mm-dd',
        language: "zh-CN",
        autoclose: true,
    });

    $.validator.methods.compareDate = function (value, element, param) {
        var startDate = $(param).val();
        var date1 = new Date(startDate).getTime();
        var date2 = new Date(value).getTime();
        return date1 < date2;
    };

    $("#form_report_add").validate({
        rules: {
            report_name: {
                required: true,
                maxlength: 16,
            },
            report_desc: {
                required: true,
            },
            start_at: {
                required: true,
            },
            end_at: {
                required: true,
                compareDate: "#start_at",
            },
        },
        messages: {
            report_name: {
                required: "请输入报告名称",
                maxlength: "报告名称最多输入16个字",
            },
            report_desc: {
                required: "请描述报告内容",
            },
            start_at: {
                required: "请选择报告开始日期",
            },
            end_at: {
                required: "请选择报告结束日期",
                compareDate: "请选择合理结束日期",
            },
        },
    });

</script>
</html>
