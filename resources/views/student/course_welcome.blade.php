<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>课程评分管理系统|学生课程欢迎界面</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport"
          content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi"/>
    <link rel="shortcut icon" href="{{asset('/favicon.ico')}}" type="image/x-icon"/>
    <link rel="stylesheet" href="{{asset('template/css/font.css')}}">
    <link rel="stylesheet" href="{{asset('template/css/xadmin.css')}}">
    {{-- buttons CSS--}}
    <link rel="stylesheet" href="{{asset('statics/DataTables/Buttons-1.6.1/css/buttons.dataTables.min.css')}}">
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="{{asset('statics/DataTables/datatables.min.css')}}">
    <!-- jQuery -->
    <script type="text/javascript" charset="utf8" src="{{asset('statics/bootstrap/js/jquery.js')}}"></script>
    <!-- DataTables -->
    <script type="text/javascript" charset="utf8"
            src="{{asset('statics/DataTables/datatables.min.js')}}"></script>
    {{--jsZip--}}
    <script type="text/javascript" charset="utf8"
            src="{{asset('statics/DataTables/JSZip-2.5.0/jszip.min.js')}}"></script>
    {{--pdfmake--}}
    <script type="text/javascript" charset="utf8"
            src="{{asset('statics/DataTables/pdfmake-0.1.36/pdfmake.min.js')}}"></script>
    <script type="text/javascript" charset="utf8"
            src="{{asset('statics/DataTables/pdfmake-0.1.36/vfs_fonts.js')}}"></script>
    {{--buttons JS--}}
    <script type="text/javascript" charset="utf8"
            src="{{asset('statics/DataTables/Buttons-1.6.1/js/dataTables.buttons.min.js')}}"></script>
    <script type="text/javascript" charset="utf8"
            src="{{asset('statics/DataTables/Buttons-1.6.1/js/buttons.flash.min.js')}}"></script>
    <script type="text/javascript" charset="utf8"
            src="{{asset('statics/DataTables/Buttons-1.6.1/js/buttons.html5.min.js')}}"></script>
    <script type="text/javascript" charset="utf8"
            src="{{asset('statics/DataTables/Buttons-1.6.1/js/buttons.print.min.js')}}"></script>

</head>
<body>
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-body ">
                    <blockquote class="layui-elem-quote">欢迎学生:
                        <span class="x-red">{{session('studentInfo')['name']}}</span>！当前时间:{{now()->toDateTimeString('Y-m-d H:i:s')}}
                    </blockquote>
                </div>
            </div>
        </div>
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">数据统计</div>
                <div class="layui-card-body ">
                    <ul class="layui-row layui-col-space10 layui-this x-admin-carousel x-admin-backlog">
                        <li class="layui-col-md2 layui-col-xs6">
                            <a href="javascript:;" class="x-admin-backlog-body">
                                <h3>出勤率</h3>
                                <p>
                                    <cite>{{sprintf("%01.2f", $data['rate'][0]['arrive_rate']*100).'%'}}</cite></p>
                            </a>
                        </li>
                        <li class="layui-col-md2 layui-col-xs6">
                            <a href="javascript:;" class="x-admin-backlog-body">
                                <h3>作业完成率</h3>
                                <p>
                                    <cite>{{sprintf("%01.2f", $data['rate'][1]['finish_rate']*100).'%'}}</cite></p>
                            </a>
                        </li>
                        <li class="layui-col-md2 layui-col-xs6">
                            <a href="javascript:;" class="x-admin-backlog-body">
                                <h3>报告提交率</h3>
                                <p>
                                    <cite>{{sprintf("%01.2f", $data['rate'][2]['commit_rate']*100).'%'}}</cite></p>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">成绩一览</div>
                <div class="layui-card-body ">
                    <table id="StudentScore" class="layui-table layui-table-hover">
                        <thead class="layui-table-header">
                        <tr>
                            <th>评分项</th>
                            <th>情况</th>
                        </tr>
                        </thead>
                        <tbody class="layui-table-body">
                        @foreach($data as $one_data=>$data_info)
                            @if($one_data=='signment')
                                <tr>
                                    <td>签到</td>
                                    <td>
                                        签到数据：
                                        @foreach($data_info['sign_data'] as $sign_data)
                                            @if($sign_data==='1')
                                                到-
                                            @else
                                                未到-
                                            @endif
                                        @endforeach
                                        <br>
                                        签到成绩：{{$data_info['sign_score']}}
                                    </td>
                                </tr>
                            @elseif($one_data=='homework')
                                <tr>
                                    <td>作业</td>
                                    @if($data_info)
                                        <td>
                                            @foreach($data_info as $one_homework)
                                                @foreach($one_homework as $k=>$v)
                                                    {{$k}}:
                                                    @if($v=='未完成')
                                                        未完成<br>
                                                    @else
                                                        已完成 , 成绩：{{$v['homework_score']}}<br>
                                                    @endif
                                                @endforeach
                                            @endforeach
                                        </td>
                                    @else
                                        <td>该课程无作业</td>
                                    @endif
                                </tr>
                            @elseif($one_data=='report')
                                <tr>
                                    <td>报告</td>
                                    @if($data_info)
                                        <td>
                                            @foreach($data_info as $one_report)
                                                @foreach($one_report as $k=>$v)
                                                    {{$k}}:
                                                    @if($v=='未完成')
                                                        未完成<br>
                                                    @else
                                                        已完成 , 成绩：{{$v['report_score']}}<br>
                                                    @endif
                                                @endforeach
                                            @endforeach
                                        </td>
                                    @else
                                        <td>该课程无报告</td>
                                    @endif
                                </tr>
                            @elseif($one_data=='final_exam')
                                <tr>
                                    <td>期末考试</td>
                                    <td>考试成绩：{{$data['final_exam']['final_exam_score']}}</td>
                                </tr>
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">开发团队</div>
                <div class="layui-card-body ">
                    <table class="layui-table">
                        <tbody>
                        <tr>
                            <th>开发者</th>
                            <td>小冰</td>
                        </tr>
                        <tr>
                            <th>技术</th>
                            <td>Laravel5.5
                                <a href="https://learnku.com/docs/laravel/5.5" style="color: #0d6aad" target="_blank">中文文档</a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="layui-col-md12">
            <blockquote class="layui-elem-quote layui-quote-nm">感谢layui,百度Echarts,jquery,本系统由x-admin提供技术支持。</blockquote>
        </div>
    </div>
</div>
</body>
<script>
    $('#StudentScore').dataTable({
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
        info: false,
        paging: false,
        searching: true,
        dom: 'Bfrtip',
        buttons: [{
            text: '打印',
            extend: 'print',
            title: '{{$course->name}}成绩单'
        }
        ],
    });
</script>
</html>