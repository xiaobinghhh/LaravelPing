<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>课程评分管理系统|签到依据</title>
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

    {{--引入layui与xadmin的js--}}
    <script src="{{asset('template/lib/layui/layui.js')}}" charset="utf-8"></script>
    <script type="text/javascript" src="{{asset('template/js/xadmin.js')}}"></script>

</head>
<body>
<div class="container-fluid">

    {{-- 顶部工具栏 --}}
    <div class="row page-title-row">
        <div class="col-md-6">
            <h3 class="pull-left">Signments</h3>
            <div class="pull-left" style="padding-top: 10px">
                <ul class="breadcrumb">
                    @foreach ($breadcrumbs as $path => $disp)
                        <li><a href="{{url('course/'.$course->no.'/signment_file?folder='.$path)}}">{{ $disp }}</a>
                        </li>
                    @endforeach
                    <li class="active">{{ $folderName }}</li>
                </ul>
            </div>
        </div>
        <div class="col-md-6 text-right" style="padding-top: 10px;">
            <button type="button" class="btn btn-success btn-md" data-toggle="modal" data-target="#modal-folder-create">
                <i class="fa fa-plus-circle"></i> 新目录
            </button>
            <button type="button" class="btn btn-primary btn-md" data-toggle="modal" data-target="#modal-file-upload">
                <i class="fa fa-upload"></i> 上传
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            @include('partials.errors')
            @include('partials.success')
            <table id="uploads-table" class="table table-striped table-bordered" data-undefined-text="-"
                   data-striped="true" data-sort-order="asc" data-sort-stable="true" data-pagination="true"
                   data-page-number="1" data-page-size="10" data-search="true">
                <thead>
                <tr>
                    <th>文件名</th>
                    <th>文件类型</th>
                    <th>上传日期</th>
                    <th>文件大小</th>
                    <th data-sortable="false">操作</th>
                </tr>
                </thead>
                <tbody>

                {{-- 子目录 --}}
                @foreach ($subfolders as $path => $name)
                    <tr>
                        <td>
                            <a href="{{url('course/'.$course->no.'/signment_file?folder='.$path)}}">
                                <i class="fa fa-folder fa-lg fa-fw"></i>
                                {{ $name }}
                            </a>
                        </td>
                        <td>目录</td>
                        <td>-</td>
                        <td>-</td>
                        <td>
                            <button type="button" class="btn btn-xs btn-danger" onclick="delete_folder('{{ $name }}')">
                                <i class="fa fa-times-circle fa-lg"></i>
                                删除
                            </button>
                        </td>
                    </tr>
                @endforeach

                {{-- 所有文件 --}}
                @foreach ($files as $file)
                    <tr>
                        <td>
                            <a href="{{ $file['webPath'] }}">
                                @if (is_image($file['mimeType']))
                                    <i class="fa fa-file-image-o fa-lg fa-fw"></i>
                                @else
                                    <i class="fa fa-file-o fa-lg fa-fw"></i>
                                @endif
                                {{ $file['name'] }}
                            </a>
                        </td>
                        <td>{{ $file['mimeType'] or 'Unknown' }}</td>
                        <td>{{ $file['modified']->format('j-M-y g:ia') }}</td>
                        <td>{{ human_filesize($file['size']) }}</td>
                        <td>
                            <button type="button" class="btn btn-xs btn-danger"
                                    onclick="delete_file('{{ $file['name'] }}')">
                                <i class="fa fa-times-circle fa-lg"></i>
                                删除
                            </button>
                            @if (is_image($file['mimeType']))
                                <button type="button" class="btn btn-xs btn-success"
                                        onclick="preview_image('{{ $file['webPath'] }}')">
                                    <i class="fa fa-eye fa-lg"></i>
                                    预览
                                </button>
                                <button type="button" class="btn btn-xs btn-primary"
                                        onclick="recognize_image('{{ $file['name'] }}')">
                                    <i class="fa fa-eye fa-lg"></i>
                                    识别
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>

        </div>
    </div>
</div>
@include('partials.signment_models')
</body>
<script type="text/javascript">
    // 确认文件删除
    function delete_file(name) {
        $("#delete-file-name1").html(name);
        $("#delete-file-name2").val(name);
        $("#modal-file-delete").modal("show");
    }

    // 确认目录删除
    function delete_folder(name) {
        $("#delete-folder-name1").html(name);
        $("#delete-folder-name2").val(name);
        $("#modal-folder-delete").modal("show");
    }

    // 预览图片
    function preview_image(path) {
        $("#preview-image").attr("src", path);
        $("#modal-image-view").modal("show");
    }

    // 识别图片
    function recognize_image(name) {
        $("#recognize-image-name1").html(name);
        $("#recognize-image-name2").val(name);
        $("#modal-image-recognize").modal("show");
    }

    // 初始化数据
    $(function () {
        $("#uploads-table").bootstrapTable({
            search: true,                      //是否显示表格搜索
            pagination: true,                   //是否显示分页（*）
            sortable: true,                     //是否启用排序
            sortOrder: "asc",                   //排序方式
            pageSize: 5,                     //每页的记录行数（*）
        });
    });

    //加载中显示
    function showloading(t) {
        if (t) {//如果是true则显示loading
            console.log(t);
            loading = layer.load(1, {
                shade: [0.1, '#fff'] //0.1透明度的白色背景
            });
        } else {//如果是false则关闭loading
            console.log("关闭loading层:" + t);
            layer.closeAll('loading');
        }
    }

    $('#recognize').click(function () {
        $.ajax({
            url: $('#recognize-form').attr('action'), //发送后台的url
            type: 'post',
            async: true,
            data: $('#recognize-form').serialize(),
            dataType: 'json', //后台返回的数据类型
            timeout: 60000, //超时时间
            beforeSend: function (XMLHttpRequest) {
                showloading(true); //在后台返回success之前显示loading图标
            },
            success: function (data) { //data为后台返回的数据
                showloading(false);//关闭loading
                $("#loading").empty(); //ajax返回成功，
                if (data.status === 0) {
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
                $('#modal-image-recognize').modal('hide');//隐藏模态框
            }
        });
    });

</script>
</html>