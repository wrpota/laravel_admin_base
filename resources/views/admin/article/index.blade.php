@extends('admin.layout.main')

@section('title') 列表 @endsection

@section('content')
    <div class="layui-card">
        <div class="layui-card-body">
            <table id="article-table" lay-filter="article-table"></table>
        </div>
    </div>
@endsection

@section('script')
    <script src="/admin/component/pear/pear.js"></script>
    <script>
        layui.use(['table', 'form', 'jquery', 'notice'],function () {
            let table = layui.table;
            let form = layui.form;
            let notice = layui.notice;
            let $ = layui.jquery;

            table.render({
                elem: '#article-table',
                url: '{{ route('article.list', ['type' => '_type']) }}'.replace('_type', '{{ $type }}'),
                page: true,
                cols: [
                    [
                        {type: 'checkbox'},
                        {field: 'id', width: 200, title: 'ID', align: "center"},
                        {field: 'title', minWidth: 240, title: '标题'},
                        {field: 'content', minWidth: 240, title: '内容'},
                        {field: 'price', width: 240, title: '价格', align: "center"},
                        {field: 'enable', title: '启用',templet:'#article-enable'},
                        {title: '操作', templet: '#article-bar', width: 150, align: 'center'}
                    ]
                ],
                skin: 'line',
                toolbar: '#article-toolbar',
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

            form.on('switch(article-enable)', function (data) {
                let loading = layer.load();
                $.ajax({
                    url: '{{ route('article.change', ['id' => '_id', '_token' => csrf_token()]) }}'.replace('_id', data.value),
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

            table.on('toolbar(article-table)', function(obj) {
                if (obj.event === 'add') {
                    var pop = layer.open({
                        type: 2,
                        title: '新增',
                        content: '{{ route('article.add', ['type' => '_type']) }}'.replace('_type', '{{ $type }}')
                    });
                    layer.full(pop);
                }
            });

            table.on('tool(article-table)', function (obj) {
                if (obj.event === 'edit') {
                    var pop = layer.open({
                        type: 2,
                        title: '编辑',
                        content: '{{ route('article.edit', ['id' => '_id', 'type' => '_type']) }}'.replace('_id', obj.data.id).replace('_type', '{{ $type }}')
                    });
                    layer.full(pop);
                }else if (obj.event === 'remove') {
                    layer.confirm('确定要删除吗？', {
                        icon: 3,
                        title: '提示'
                    }, function(index) {
                        layer.close(index);
                        let loading = layer.load();
                        $.ajax({
                            url: '{{ route('article.del', ['id' => '_id', '_token' => csrf_token()])}}'.replace('_id', obj.data.id),
                            dataType: 'json',
                            type: 'delete',
                            success: function(result) {
                                layer.close(loading);
                                if(result.code === 0){
                                    layer.msg(result.msg,{icon:1,time:1000});
                                    table.reload("article-table");
                                }else{
                                    layer.msg(result.msg,{icon:2,time:1000});
                                }
                            }
                        })
                    });
                }
            });
        })
    </script>

    <script type="text/html" id="article-toolbar">
        <button class="pear-btn pear-btn-primary pear-btn-md" lay-event="add">
            <i class="layui-icon layui-icon-add-1"></i>
            新增
        </button>
    </script>

    <script type="text/html" id="article-bar">
        <button class="pear-btn pear-btn-primary pear-btn-sm" lay-event="edit"><i class="layui-icon layui-icon-edit"></i></button>
        <button class="pear-btn pear-btn-danger pear-btn-sm" lay-event="remove"><i class="layui-icon layui-icon-delete"></i></button>
    </script>

    <script type="text/html" id="article-enable">
        <input type="checkbox" name="status" value="@{{d.id}}" lay-skin="switch" lay-text="启用|禁用" lay-filter="article-enable" @{{# if(d.status == 1){ }} checked @{{# } }}>
    </script>

@endsection
