<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>课程评分管理系统|我的签到页面</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport"
          content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi"/>
    <link rel="shortcut icon" href="{{asset('/favicon.ico')}}" type="image/x-icon"/>
    <link rel="stylesheet" href="{{asset('template/css/font.css')}}">
    <link rel="stylesheet" href="{{asset('template/css/xadmin.css')}}">
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="{{asset('statics/DataTables/datatables.min.css')}}">
    <!-- jQuery -->
    <script type="text/javascript" charset="utf8" src="{{asset('statics/bootstrap/js/jquery.js')}}"></script>
    <!-- DataTables -->
    <script type="text/javascript" charset="utf8"
            src="{{asset('statics/DataTables/datatables.min.js')}}"></script>

    <style>
        table > tbody > tr > td {
            text-align: center;
        }
    </style>
</head>
<body>
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">数据统计</div>
                <div class="layui-card-body ">
                    <ul class="layui-row layui-col-space10 layui-this x-admin-carousel x-admin-backlog">
                        <li class="layui-col-md2 layui-col-xs6">
                            <a href="javascript:;" class="x-admin-backlog-body">
                                <h3>出勤：</h3>
                                <p>
                                    <cite>{{$_1_cnt}}次</cite></p>
                            </a>
                        </li>
                        <li class="layui-col-md2 layui-col-xs6">
                            <a href="javascript:;" class="x-admin-backlog-body">
                                <h3>缺勤：</h3>
                                <p>
                                    <cite>{{$_0_cnt}}次</cite></p>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">课程点名记录</div>
                <div class="layui-card-body ">
                    <table id="Signment">
                        <thead>
                        <tr>
                            <th>签到</th>
                            <th>签到情况</th>
                        </tr>
                        </thead>
                        <tbody>
                        @for($i=0;$i<count($sign);$i++)
                            <tr>
                                <td>第{{$i+1}}次签到</td>
                                @if($sign[$i]==0)
                                    <td><i class="iconfont">&#xe69a;</i></td>
                                @else
                                    <td><i class="iconfont">&#xe6ad;</i></td>
                                @endif
                            </tr>
                        @endfor
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<script>
    $('#Signment').DataTable({
        language: {
            "decimal": "",//小数的小数位符号  比如“，”作为数字的小数位符号
            "emptyTable": "没有数据",//没有数据时要显示的字符串
            "info": "当前 _START_ 条到 _END_ 条 共 _TOTAL_ 条",//左下角的信息，变量可以自定义，到官网详细查看
            "infoEmpty": "无记录",//当没有数据时，左下角的信息
            "infoFiltered": "(从 _MAX_ 条记录过滤)",//当表格过滤的时候，将此字符串附加到主要信息
            "infoPostFix": "",//在摘要信息后继续追加的字符串
            "thousands": ",",//千分位分隔符
            "lengthMenu": "每页 _MENU_ 条记录",//用来描述分页长度选项的字符串
            "loadingRecords": "加载中...",//用来描述数据在加载中等待的提示字符串 - 当异步读取数据的时候显示
            "processing": "处理中...",//用来描述加载进度的字符串
            "search": "搜索",//用来描述搜索输入框的字符串
            "zeroRecords": "没有找到",//当没有搜索到结果时，显示
            "paginate": {
                "first": "首页",
                "previous": "上一页",
                "next": "下一页",
                "last": "尾页"
            }
        },
        ordering: false,
        paging: false,
        searching: false,
    });
</script>
</html>