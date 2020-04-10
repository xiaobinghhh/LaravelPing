<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>课程评分管理系统|新的签到</title>
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport"
          content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi"/>
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    {{--引入bootstrap--}}
    <link href="{{asset('statics/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet"/>
    <script src="{{asset('statics/bootstrap/js/jquery.js')}}"></script>
    <script src="{{asset('statics/bootstrap/js/bootstrap.min.js')}}"></script>
    {{--引入layui与xadmin的js--}}
    <script src="{{asset('template/lib/layui/layui.js')}}" charset="utf-8"></script>
    <script type="text/javascript" src="{{asset('template/js/xadmin.js')}}"></script>

</head>
<body>

<div class="container" z-index="-1">
    <div class="row" style="padding: 10px">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding: 10px">
            <a type="button" class="btn btn-default" onclick="history.go(-1)"><span
                        class="glyphicon glyphicon-chevron-left"></span>返回</a>
        </div>
    </div>
    <div class="row" style="padding: 10px">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <form action="" method="post">
                {{csrf_field()}}
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th style="text-align: center">学号</th>
                        <th style="text-align: center">学生</th>
                        <th style="text-align: center">签到<input type="checkbox" name="chooseAll"
                                                                id="chkAll">
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($students as $student)
                        <tr>
                            <td style="text-align: center">{{$student->no}}</td>
                            <td style="text-align: center">{{$student->name}}</td>
                            <td style="text-align: center"><input type="checkbox" value="{{$student->no}}"
                                                                  name="chkItm[]" class="check"></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <button class="btn btn-primary" type="submit" style="float: right">提交</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
<script type="text/javascript">
    $(function () {
        $("#chkAll").on('change', function () {
            var self = $(this);
            var all_box = $('tbody').find("input[type='checkbox']");
            if (self.prop('checked') == false) {
                self.prop('checked', false);
                all_box.prop('checked', false);
            } else {
                self.prop('checked', true);
                all_box.prop('checked', true);
            }
        });

        @if (count($errors) > 0)
        //以JavaScript弹窗形式输出错误的内容
        var allError = '';
        @foreach ($errors->all() as $error)
            allError += "{{$error}}<br/>";
        @endforeach
        //输出错误信息
        layui.use('layer', function () {
            var layer = layui.layer;
            layer.msg(allError, {icon: 2});
        });
        @endif
    });
</script>
</html>