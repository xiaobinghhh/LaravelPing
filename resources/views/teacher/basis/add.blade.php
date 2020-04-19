<html>
<head>
    <meta charset="UTF-8">
    <title>课程评分管理系统|新增评分项</title>
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
            <a type="button" class="btn btn-default" href="{{url("course/".$course->no."/basis")}}"><span
                        class="glyphicon glyphicon-chevron-left"></span>返回</a>
        </div>
    </div>
    @include('partials.errors')
    <div class="row" style="padding: 10px">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <form action="{{url("course/".$course->no."/basis/add")}}" method="post" id="form_basis_add"
                  style="padding-top: 10px">
                {{csrf_field()}}
                <table class="table">
                    <tbody>
                    <tr>
                        <th><i style="color: red;">*</i>评分项</th>
                        <td>
                            <div class="col-sm-6">
                                <select class="form-control" id="basis_name" name="basis_name">
                                    <option value="">请选择</option>
                                    @foreach($basis as $k=>$v)
                                        <option value="{{$k}}">
                                            {{$v}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th><i style="color: red;">*</i>评分权重</th>
                        <td>
                            <div class="col-sm-6">
                                <input type="text" placeholder="输入0-100的整数" class="form-control"
                                       name="basis_weight">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th></th>
                        <td>
                            <div class="col-sm-6">
                                <input type="submit" class="btn btn-primary" value="新增">
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

    $("#form_basis_add").validate({
        rules: {
            basis_name: {
                required: true,
            },
            basis_weight: {
                required: true,
            },
        },
        messages: {
            basis_name: {
                required: "请选择评分项",
            },
            basis_weight: {
                required: "请输入评分权重",
            },
        },
    });

</script>
</html>
