@extends('admin.layout.main')

@section('title')管理员列表 @endsection

@section('content')
    <div class="layui-card">
        <div class="layui-card-body">
            <form class="layui-form" action="">
                <div class="layui-form-item">
                    <div class="layui-form-item layui-inline">
                        <label class="layui-form-label">ID</label>
                        <div class="layui-input-inline">
                            <input type="number" name="id" placeholder="" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item layui-inline">
                        <label class="layui-form-label">用户名</label>
                        <div class="layui-input-inline">
                            <input type="text" name="adminname" placeholder="" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item layui-inline">
                        <button class="pear-btn pear-btn-md pear-btn-primary" lay-submit lay-filter="admin-query">
                            <i class="layui-icon layui-icon-search"></i>
                            查询
                        </button>
                        <button type="reset" class="pear-btn pear-btn-md">
                            <i class="layui-icon layui-icon-refresh"></i>
                            重置
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="layui-card">
        <div class="layui-card-body">
            <table id="admin-table" lay-filter="admin-table"></table>
        </div>
    </div>
@endsection

@section('script')
    <script src="/admin/component/pear/pear.js"></script>
    <script>
        layui.use(['table', 'form', 'jquery','common', 'notice', 'validate'], function() {
            let table = layui.table;
            let form = layui.form;
            let $ = layui.jquery;
            let common = layui.common;
            let notice = layui.notice;
            let validate = layui.validate;

            let cols = [
                [
                    {type: 'checkbox'},
                    {
                        title: 'ID',
                        field: 'id',
                        align: 'center',
                        width: 100
                    },
                    {
                        title: '账号',
                        field: 'username',
                        align: 'center',
                        width: 100
                    },
                    {
                        title: '用户组',
                        field: 'role',
                        align: 'center',
                        templet: '#admin-role'
                    },
                    {
                        title: '状态',
                        field: 'status',
                        align: 'center',
                        templet: '#admin-status'
                    },
                    {
                        title: '操作',
                        toolbar: '#admin-bar',
                        align: 'center',
                        width: 130
                    }
                ]
            ]

            table.render({
                elem: '#admin-table',
                url: '{{ route('system.admin', ['list'=>1]) }}',
                page: true,
                cols: cols,
                skin: 'line',
                toolbar: '#admin-toolbar',
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

            table.on('tool(admin-table)', function(obj) {
                if (obj.event === 'remove') {
                    window.remove(obj);
                } else if (obj.event === 'edit') {
                    window.edit(obj);
                }
            });

            table.on('toolbar(admin-table)', function(obj) {
                if (obj.event === 'add') {
                    window.add();
                } else if (obj.event === 'refresh') {
                    window.refresh();
                }
            });

            form.on('submit(admin-query)', function(data) {
                console.log(data);
                table.reload('admin-table', {
                    where: data.field
                })
                return false;
            });

            form.on('switch(ckStatus)', function (data) {
                let loading = layer.load();
                $.ajax({
                    url: '{{ route('system.admin.change', ['id' => '_id', '_token' => csrf_token()]) }}'.replace('_id', data.value),
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
            });
            window.add = function() {
                layer.open({
                    type: 2,
                    title: '修改',
                    shade: 0.1,
                    area: ['800px', '500px'],
                    content: '{{ route('system.admin.edit', ['id' => 0]) }}'
                });
            }

            window.edit = function(obj) {
                layer.open({
                    type: 2,
                    title: '修改',
                    shade: 0.1,
                    area: ['800px', '500px'],
                    content: '{{ route('system.admin.edit', ['id' => '_id']) }}'.replace('_id', obj.data.id)
                });
            }

            window.remove = function(obj) {
                layer.confirm('确定要删除该用户', {
                    icon: 3,
                    title: '提示'
                }, function(index) {
                    layer.close(index);
                    let loading = layer.load();
                    $.ajax({
                        url: '{{ route('system.admin.delete', ['id' => '_id', '_token' => csrf_token()])}}'.replace('_id', obj.data.id),
                        dataType: 'json',
                        type: 'delete',
                        success: function(result) {
                            layer.close(loading);
                            if(result.code === 0){
                                layer.msg(result.msg,{icon:1,time:1000});
                            }else{
                                layer.msg(result.msg,{icon:2,time:1000});
                            }
                        }
                    })
                });
            }

            window.refresh = function(param) {
                table.reload('admin-table');
            }
        })
    </script>
    <script type="text/html" id="admin-toolbar">
        <button class="pear-btn pear-btn-primary pear-btn-md" lay-event="add">
            <i class="layui-icon layui-icon-add-1"></i>
            新增
        </button>
    </script>

    <script type="text/html" id="admin-bar">
        <button class="pear-btn pear-btn-primary pear-btn-sm" lay-event="edit"><i class="layui-icon layui-icon-edit"></i></button>
        <button class="pear-btn pear-btn-danger pear-btn-sm" lay-event="remove"><i class="layui-icon layui-icon-delete"></i></button>
    </script>
    <script type="text/html" id="admin-role">
        @{{# d.role.forEach(function (item) { }}
        <button type="button" class="tag-item layui-btn layui-btn-primary layui-btn-radius layui-btn-xs">@{{ item.name }}</button>
        @{{# }) }}
    </script>

    <script type="text/html" id="admin-status">
        <input type="checkbox" name="status" value="@{{d.id}}" lay-filter="ckStatus" lay-skin="switch" lay-text="启用|禁用" lay-filter="user-enable" @{{# if(d.status == 1){ }} checked @{{# } }}>
    </script>

@endsection
