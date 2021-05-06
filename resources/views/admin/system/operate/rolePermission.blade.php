@extends('admin.layout.main')

@section('title')角色权限操作页 @endsection

@section('content')
    <div class="main-container">
        <div class="main-container">
            <button class="pear-btn pear-btn-success pear-btn-md" dtree-id="role" dtree-menu="checkAll">
                <i class="dtreefont dtree-icon-roundcheckfill"></i> 全选
            </button>
            <button class="pear-btn pear-btn-success pear-btn-md"  dtree-id="role" dtree-menu="unCheckAll">
                <i class="dtreefont dtree-icon-roundclosefill"></i>全不选
            </button>
            <button class="pear-btn pear-btn-success pear-btn-md" dtree-id="role" dtree-menu="refresh">
                <i class="layui-icon layui-icon-refresh"></i> 刷新
            </button>

        </div>
    </div>
    <form class="layui-form" action="put">
        <div class="main-container">
            <div class="main-container">
                <ul id="role" class="dtree" data-id="0"></ul>
            </div>
        </div>
        <div style="height: 51px;"></div>
        <div class="bottom-fixed">
            <div class="button-container">
                <button type="submit" class="layui-btn layui-btn-normal layui-btn-sm" lay-submit="" lay-filter="role-save">
                    <i class="layui-icon layui-icon-ok"></i>
                    提交
                </button>
            </div>
        </div>
    </form>

@endsection
@section('script')
    <script src="/admin/component/pear/pear.js"></script>
    <script>
        layui.use(['form','jquery', 'validate', 'dtree'],function(){
            let form = layui.form;
            let validate = layui.validate;
            let dtree = layui.dtree;
            let $ = layui.jquery;
            dtree.render({
                elem: "#role",
                method: "get",
                data: {!! json_encode($permissionTree) !!},
                dataFormat: "list",
                checkbar: true,
                skin: "layui",
                initLevel: "2",
                checkbarType: "all",
                response: {treeId: "id", parentId: "pid", title: "name"},
                menubar:true,
                menubarTips:{
                    group:[]
                },
            });

            form.on('submit(role-save)', function (data) {
                let param = dtree.getCheckbarNodesParam("role");
                let ids = [];
                for (let i = 0; i < param.length; i++) {
                    let id = param[i].nodeId;
                    ids.push(id);
                }
                data.field.permissionIds = ids;
                data.field._token = '{{ csrf_token() }}';
                $.ajax({
                    url: '{{ route('system.role.rolePermission', ['id' => $id]) }}',
                    data: data.field,
                    dataType: 'json',
                    type: 'put',
                    success: function (result) {
                        if(result.code === 0){
                            layer.msg(result.msg,{icon:1,time:1000},function(){
                                parent.layer.close(parent.layer.getFrameIndex(window.name));//关闭当前页
                                parent.layui.table.reload('role-table');
                            });
                        }else{
                            layer.msg(result.msg,{icon:2,time:1000});
                        }
                    }
                })
                return false;
            });
        })
    </script>
@endsection
