@extends('admin.layout.main')

@section('title') banner列表 @endsection

{{--@section('css')--}}
{{--    <style>--}}
{{--        /** 控制表格单元格换行 **/--}}
{{--        .layui-table-cell {--}}
{{--            height: auto;--}}
{{--            word-break: normal;--}}
{{--            display: block;--}}
{{--            white-space: pre-wrap;--}}
{{--            word-wrap: break-word;--}}
{{--            overflow: hidden;--}}
{{--        }--}}
{{--    </style>--}}
{{--@endsection--}}

@section('content')
    <div class="layui-card">
        <div class="layui-card-body">
            <table id="banner-table" lay-filter="banner-table"></table>
        </div>
    </div>
@endsection

@section('script')
    <script src="/admin/component/pear/pear.js"></script>
    <script>
        var layerIndex;
        var layerInitWidth;
        var layerInitHeight;
        var $;
        layui.use(['table','form','jquery', 'notice'],function () {
            let table = layui.table;
            let form = layui.form;
            let notice = layui.notice;
            $ = layui.jquery;

            table.render({
                elem: '#banner-table',
                url: '{{ route('banner.list', [ 'pid' => '_id']) }}'.replace('_id', {{ $pid }}),
                page: true,
                cols: [
                    [
                        {field: 'id', minWidth: 100, title: 'ID', align: "center"},
                        {field: 'pic', templet: '#pic', minWidth: 240, title: '图片'},
                        {field: 'title', minWidth: 240, title: '名称'},
                        {field: 'sort', minWidth: 100, title: '排序'},
                        {field: 'enable', title: '启用',templet:'#banner-enable'},
                        {title: '操作', templet: '#banner-bar', width: 150, align: 'center'}
                    ]
                ],
                skin: 'line',
                toolbar: '#banner-toolbar',
                defaultToolbar: [
                    {
                        layEvent: 'refresh',
                        icon: 'layui-icon-refresh',
                    }, 'filter', 'print'
                ],
                response: {
                    statusName: 'code'
                    ,statusCode: 0
                    ,msgName: 'msg'
                    ,countName: 'meta'
                    ,dataName: 'data'
                }
                ,even: true //开启隔行背景
            });

            form.on('switch(banner-enable)', function (data) {
                let loading = layer.load();
                $.ajax({
                    url: '{{ route('banner.change', ['id' => '_id', '_token' => csrf_token()]) }}'.replace('_id', data.value),
                    dataType:'json',
                    type:'put',
                    data: {status:data.elem.checked ? 1 : 2},
                    error: function (res) {
                        layer.close(loading);
                        validate.layer(res);
                        data.elem.checked=!data.elem.checked;
                        form.render();
                    },
                    success:function(result){
                        layer.close(loading);
                        notice.success('切换成功');
                    }
                })
            })

            table.on('toolbar(banner-table)', function(obj) {
                if (obj.event === 'add') {
                    layer.open({
                        type: 2,
                        title: '新增',
                        shade: 0.4,
                        area: ['1100px', '600px'],
                        content: '{{ route('banner.add', [ 'pid' => '_id']) }}'.replace('_id', {{ $pid }}),
                        success: function (layer, index) {
                            //获取当前弹出窗口的索引及初始大小
                            layerIndex = index;
                            layerInitWidth = $("#layui-layer" + layerIndex).width();
                            layerInitHeight = $("#layui-layer" + layerIndex).height();
                            resizeLayer(layerIndex, layerInitWidth, layerInitHeight);
                            form.render();
                        }
                    });
                }
            });

            table.on('tool(banner-table)', function (obj) {
                if (obj.event === 'edit') {
                    layer.open({
                        type: 2,
                        title: '修改',
                        shade: 0.4,
                        area: ['1100px', '600px'],
                        content: '{{ route('banner.edit', ['id' => '_id']) }}'.replace('_id', obj.data.id),
                        success: function (layer, index) {
                            //获取当前弹出窗口的索引及初始大小
                            layerIndex = index;
                            layerInitWidth = $("#layui-layer" + layerIndex).width();
                            layerInitHeight = $("#layui-layer" + layerIndex).height();
                            resizeLayer(layerIndex, layerInitWidth, layerInitHeight);
                            form.render();
                        }
                    });
                } else if (obj.event === 'remove') {
                    layer.confirm('确定要删除该banner吗？', {
                        icon: 3,
                        title: '提示'
                    }, function(index) {
                        layer.close(index);
                        let loading = layer.load();
                        $.ajax({
                            url: '{{ route('banner.del', ['id' => '_id', '_token' => csrf_token()])}}'.replace('_id', obj.data.id),
                            dataType: 'json',
                            type: 'delete',
                            success: function(result) {
                                layer.close(loading);
                                if(result.code === 0){
                                    layer.msg(result.msg,{icon:1,time:1000});
                                    table.reload("banner-table");
                                }else{
                                    layer.msg(result.msg,{icon:2,time:1000});
                                }
                            }
                        })
                    });
                }
            });
        })

        function resizeLayer(layerIndex, layerInitWidth, layerInitHeight) {
            var windowWidth = $(document).width();
            var windowHeight = $(document).height();
            var minWidth = layerInitWidth > windowWidth ? windowWidth : layerInitWidth;
            var minHeight = layerInitHeight > windowHeight ? windowHeight : layerInitHeight;
            // console.log("win:", windowWidth, windowHeight);
            // console.log("lay:", layerInitWidth, layerInitHeight);
            // console.log("min:", minWidth, minHeight);
            layer.style(layerIndex, {
                top: 18,
                width: minWidth,
                height: minHeight
            });
        }
    </script>

    <script type="text/html" id="banner-toolbar">
        <button class="pear-btn pear-btn-primary pear-btn-md" lay-event="add">
            <i class="layui-icon layui-icon-add-1"></i>
            新增
        </button>
    </script>

    <script type="text/html" id="pic">
        <img src="@{{ d.pic }}" style="width: 30px; height: 30px">
    </script>

    <script type="text/html" id="banner-bar">
        <button class="pear-btn pear-btn-primary pear-btn-sm" lay-event="edit"><i class="layui-icon layui-icon-edit"></i></button>
        <button class="pear-btn pear-btn-danger pear-btn-sm" lay-event="remove"><i class="layui-icon layui-icon-delete"></i></button>
    </script>

    <script type="text/html" id="banner-enable">
        <input type="checkbox" name="status" value="@{{d.id}}" lay-skin="switch" lay-text="启用|禁用" lay-filter="banner-enable" @{{# if(d.status == 1){ }} checked @{{# } }}>
    </script>
@endsection
