@extends('admin.layout.main')

@section('title')角色列表 @endsection

@section('content')
<div class="layui-card">
    <div class="layui-card-body">
        <table id="role-table" lay-filter="role-table"></table>
    </div>
</div>
@endsection

@section('script')
    <script src="/admin/component/pear/pear.js"></script>
    <script>
        layui.use(['table','form','jquery', 'validate', 'notice'],function () {
            let table = layui.table;
            let form = layui.form;
            let $ = layui.jquery;
            let notice = layui.notice;
            let validate = layui.validate;

            let cols = [
                [
                    {type:'checkbox'},
                    {title: '角色名', field: 'name', align:'center', width:100},
                    {title: '描述', field: 'remark', align:'center'},
                    {title: '是否可用', field: 'status', align:'center', templet:'#role-status'},
                    {title: '操作', toolbar: '#role-bar', align:'center', width:195}
                ]
            ]
            table.render({
                elem: '#role-table',
                url: '{{ route('system.role', ['list' => 1]) }}',
                page: true ,
                cols: cols ,
                skin: 'line',
                toolbar: '#role-toolbar',
                defaultToolbar: [{
                    layEvent: 'refresh',
                    icon: 'layui-icon-refresh',
                }, 'filter', 'print', 'exports']
                ,response: {
                    statusName: 'code'
                    ,statusCode: 0
                    ,msgName: 'msg'
                    ,countName: 'meta'
                    ,dataName: 'data'
                }
                ,even: true //开启隔行背景
            });

            table.on('tool(role-table)', function(obj){
                if(obj.event === 'remove'){
                    window.remove(obj);
                } else if(obj.event === 'edit'){
                    window.edit(obj);
                } else if(obj.event === 'power'){
                    window.power(obj);
                }
            });

            table.on('toolbar(role-table)', function(obj){
                if(obj.event === 'add'){
                    window.add();
                } else if(obj.event === 'refresh'){
                    window.refresh();
                } else if(obj.event === 'batchRemove'){
                    window.batchRemove(obj);
                }
            });

            form.on('switch(ckStatus)', function (data) {
                let loading = layer.load();
                $.ajax({
                    url: '{{ route('system.role.change', ['id' => '_id', '_token' => csrf_token()]) }}'.replace('_id', data.value),
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

            window.add = function(){
                layer.open({
                    type: 2,
                    title: '新增',
                    shade: 0.1,
                    area: ['500px', '400px'],
                    content: '{{ route('system.role.edit', ['id' => 0]) }}'
                });
            }

            window.power = function(obj){
                layer.open({
                    type: 2,
                    title: '授权',
                    shade: 0.1,
                    area: ['800px', '500px'],
                    content: '{{ route('system.role.rolePermission', ['id' => '_id']) }}'.replace('_id', obj.data.id)
                });
            }

            window.edit = function(obj){
                layer.open({
                    type: 2,
                    title: '修改',
                    shade: 0.1,
                    area: ['500px', '400px'],
                    content: '{{ route('system.role.edit', ['id' => '_id']) }}'.replace('_id', obj.data.id)
                });
            }

            window.remove = function(obj){
                layer.confirm('确定要删除该角色', {icon: 3, title:'提示'}, function(index){
                    layer.close(index);
                    let loading = layer.load();
                    $.ajax({
                        url: '{{ route('system.role.delete', ['id' => '_id', '_token' => csrf_token()]) }}'.replace('_id', obj.data.id),
                        dataType:'json',
                        type:'delete',
                        error: function (res) {
                            layer.close(loading);
                            validate.layer(res);
                        },
                        success:function(result){
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

            window.batchRemove = function(obj){
                let data = table.checkStatus(obj.config.id).data;
                if(data.length === 0){
                    layer.msg("未选中数据",{icon:3,time:1000});
                    return false;
                }
                let ids = "";
                for(let i = 0;i<data.length;i++){
                    ids += data[i].userId+",";
                }
                ids = ids.substr(0,ids.length-1);
                layer.confirm('确定要删除这些用户', {icon: 3, title:'提示'}, function(index){
                    layer.close(index);
                    let loading = layer.load();
                    $.ajax({
                        url: MODULE_PATH+"batchRemove/"+ids,
                        dataType:'json',
                        type:'delete',
                        success:function(result){
                            layer.close(loading);
                            if(result.success){
                                layer.msg(result.msg,{icon:1,time:1000},function(){
                                    table.reload('user-table');
                                });
                            }else{
                                layer.msg(result.msg,{icon:2,time:1000});
                            }
                        }
                    })
                });
            }

            window.refresh = function(){
                table.reload('role-table');
            }
        })
    </script>
    <script type="text/html" id="role-toolbar">
        <button class="pear-btn pear-btn-primary pear-btn-md" lay-event="add">
            <i class="layui-icon layui-icon-add-1"></i>
            新增
        </button>
        <button class="pear-btn pear-btn-danger pear-btn-md" lay-event="batchRemove">
            <i class="layui-icon layui-icon-delete"></i>
            删除
        </button>
    </script>

    <script type="text/html" id="role-bar">
        <button class="pear-btn pear-btn-primary pear-btn-sm" lay-event="edit"><i class="layui-icon layui-icon-edit"></i></button>
        <button class="pear-btn pear-btn-warming pear-btn-sm" lay-event="power"><i class="layui-icon layui-icon-vercode"></i></button>
        <button class="pear-btn pear-btn-danger pear-btn-sm" lay-event="remove"><i class="layui-icon layui-icon-delete"></i></button>
    </script>

    <script type="text/html" id="role-status">
        <input type="checkbox" name="status" value="@{{d.id}}" lay-filter="ckStatus" lay-skin="switch" lay-text="启用|禁用" lay-filter="user-enable" @{{# if(d.status == 1){ }} checked @{{# } }}>
    </script>

@endsection
