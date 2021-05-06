@extends('admin.layout.main')

@section('title') 用户列表 @endsection

@section('content')
    <div class="layui-card">
        <div class="layui-card-body">
            <table id="user-table" lay-filter="user-table"></table>
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
                elem: '#user-table',
                url: '{{ route('user.list') }}',
                page: true,
                cols: [
                    [
                        {type: 'checkbox'},
                        {field: 'id', minWidth: 100, title: 'ID', align: "center"},
                        {field: 'username', minWidth: 240, title: '用户名'},
                        {field: 'phone', minWidth: 240, title: '手机号'},
                        {field: 'wechat', minWidth: 240, title: '微信号'},
                        {field: 'sex', Width: 100, toolbar: '#sex', title: '性别'},
                        {field: 'enable', title: '启用',templet:'#user-enable'},
                        {title: '操作', templet: '#banner-bar', width: 150, align: 'center'}
                    ]
                ],
                skin: 'line',
                toolbar: '#user-toolbar',
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

            form.on('switch(user-enable)', function (data) {
                let loading = layer.load();
                $.ajax({
                    url: '{{ route('user.change', ['id' => '_id', '_token' => csrf_token()]) }}'.replace('_id', data.value),
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

            table.on('toolbar(user-table)', function(obj) {
                if (obj.event === 'add') {
                    var pop = layer.open({
                        type: 2,
                        title: '新增用户',
                        content: '{{ route('user.add') }}'
                    });
                    layer.full(pop);
                }
            });

            table.on('tool(user-table)', function (obj) {
                if (obj.event === 'edit') {
                    var pop = layer.open({
                        type: 2,
                        title: '编辑用户',
                        content: '{{ route('user.edit', ['id' => '_id']) }}'.replace('_id', obj.data.id)
                    });
                    layer.full(pop);
                }
            });
        })
    </script>

    <script type="text/html" id="user-toolbar">
        <button class="pear-btn pear-btn-primary pear-btn-md" lay-event="add">
            <i class="layui-icon layui-icon-add-1"></i>
            新增
        </button>
    </script>

    <script type="text/html" id="banner-bar">
        <button class="pear-btn pear-btn-primary pear-btn-sm" lay-event="edit"><i class="layui-icon layui-icon-edit"></i></button>
    </script>

    <script type="text/html" id="sex">
        @{{#if(d.sex == 0) { }}
            <p>未知</p>
        @{{#  } else if(d.sex == 1) { }}
            <p>男</p>
        @{{# } else { }}
            <p>女</p>
        @{{#} }}
    </script>

    <script type="text/html" id="user-enable">
        <input type="checkbox" name="status" value="@{{d.id}}" lay-skin="switch" lay-text="正常|冻结" lay-filter="user-enable" @{{# if(d.status == 1){ }} checked @{{# } }}>
    </script>
@endsection
