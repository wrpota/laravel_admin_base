@extends('admin.layout.main')

@section('title')权限列表 @endsection

@section('content')
    <div class="layui-card">
        <div class="layui-card-body">
            <table id="permission-table" lay-filter="permission-table"></table>
        </div>
    </div>
@endsection

@section('script')
    <script src="/admin/component/pear/pear.js"></script>
    <script>
        layui.use(['table','form','jquery','treetable', 'validate', 'notice'],function () {
            let table = layui.table;
            let form = layui.form;
            let notice = layui.notice;
            let $ = layui.jquery;
            let treetable = layui.treetable;
            let validate = layui.validate;

            let insTb = treetable.render({
                tree: {
                    iconIndex: 1,
                    isPidData: true,
                    idName: 'id',
                    pidName: 'pid'
                },
                skin:'line',
                method:'get',
                toolbar:'#permission-toolbar',
                elem: '#permission-table',
                url: '{{ route('system.permission', ['list' => 1]) }}',
                page: false,
                cols: [
                    [
                        {type: 'checkbox'},
                        {field: 'name', minWidth: 200, title: '权限名称'},
                        {field: 'identification', title: '标识'},
                        {field: 'status', title: '是否可用',templet:'#permission-status'},
                        {field: 'sort', title: '排序', edit:'text'},
                        {title: '操作',templet: '#permission-bar', width: 150, align: 'center'}
                    ]
                ]
            });

            treetable.on('tool(permission-table)',function(obj){
                if (obj.event === 'remove') {
                    window.remove(obj);
                } else if (obj.event === 'edit') {
                    window.edit(obj);
                }  else if (obj.event === 'menu') {
                    window.menu(obj);
                }
            });
            treetable.on('edit(permission-table)', function (obj) {
                console.log(obj)
                var regexp = /^[+]{0,1}(\d+)$/
                if (obj.field === 'sort') {
                    if (!regexp.test(obj.value)) {
                        layer.tips('请输入正整数', $(this), {tips: [1, '#FF5722']});
                        return false;
                    }
                    let loading = layer.load();
                    $.ajax({
                        url: '{{ route('system.permission.change', ['id' => '_id', '_token' => csrf_token()]) }}'.replace('_id', obj.data.id),
                        dataType:'json',
                        type:'put',
                        data: {sort:Number(obj.value)},
                        error: function (res) {
                            layer.close(loading);
                            validate.layer(res);
                        },
                        success:function(result){
                            layer.close(loading);
                            notice.success('修改成功');
                            obj.update({sort:Number(obj.value)})
                        }
                    })
                }
            });

            form.on('switch(ckStatus)', function (data) {
                let loading = layer.load();
                $.ajax({
                    url: '{{ route('system.permission.change', ['id' => '_id', '_token' => csrf_token()]) }}'.replace('_id', data.value),
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


            treetable.on('toolbar(permission-table)', function(obj){
                if(obj.event === 'add'){
                    window.add();
                } else if(obj.event === 'refresh'){
                    window.refresh();
                } else if(obj.event === 'expandAll'){
                    insTb.expandAll();
                } else if(obj.event === 'foldAll'){
                    insTb.foldAll();
                }
            });

            window.add = function(){
                layer.open({
                    type: 2,
                    title: '新增',
                    shade: 0.1,
                    area: ['800px', '500px'],
                    content: '{{ route('system.permission.edit', ['id' => 0]) }}'
                });
            }

            window.edit = function(obj){
                layer.open({
                    type: 2,
                    title: '修改',
                    shade: 0.1,
                    area: ['800px', '500px'],
                    content: '{{ route('system.permission.edit', ['id' => '_id']) }}'.replace('_id', obj.data.id)
                });
            }
            window.remove = function(obj){
                if (obj.data.children && obj.data.children.length > 0) {
                    return notice.warning("有子级不能删除!");
                }
                layer.confirm('确定要删除该权限', {icon: 3, title:'提示'}, function(index){
                    layer.close(index);
                    let loading = layer.load();
                    $.ajax({
                        url: '{{ route('system.permission.delete', ['id' => '_id', '_token' => csrf_token()]) }}'.replace('_id', obj.data.id),
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

            window.menu = function (obj) {
                layer.open({
                    type: 2,
                    title: '授权',
                    shade: 0.1,
                    area: ['800px', '500px'],
                    content: '{{ route('system.permission.permissionMenu', ['id' => '_id']) }}'.replace('_id', obj.data.id)
                });
            }
        })
    </script>
    <script type="text/html" id="permission-toolbar">
        <button class="pear-btn pear-btn-primary pear-btn-md" lay-event="add">
            <i class="layui-icon layui-icon-add-1"></i>
            新增
        </button>
        <button class="pear-btn pear-btn-success pear-btn-md" lay-event="expandAll">
            <i class="layui-icon layui-icon-spread-left"></i>
            展开
        </button>
        <button class="pear-btn pear-btn-success pear-btn-md" lay-event="foldAll">
            <i class="layui-icon layui-icon-shrink-right"></i>
            折叠
        </button>
    </script>

    <script type="text/html" id="permission-bar">
        <button class="pear-btn pear-btn-primary pear-btn-sm" lay-event="menu" title="设置菜单"><i class="icon pear-icon pear-icon-menu"></i></button>
        <button class="pear-btn pear-btn-primary pear-btn-sm" lay-event="edit"><i class="layui-icon layui-icon-edit"></i></button>
        <button class="pear-btn pear-btn-danger pear-btn-sm" lay-event="remove"><i class="layui-icon layui-icon-delete"></i></button>
    </script>

    <script type="text/html" id="permission-status">
        <input type="checkbox" name="status" value="@{{d.id}}" lay-filter="ckStatus" lay-skin="switch" lay-text="启用|禁用" lay-filter="user-enable" @{{# if(d.status == 1){ }} checked @{{# } }}>
    </script>

@endsection
