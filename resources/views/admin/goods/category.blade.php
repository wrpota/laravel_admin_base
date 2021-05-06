@extends('admin.layout.main')

@section('title') 列表 @endsection

@section('content')
    <div class="layui-card">
        <div class="layui-card-body">
            <table id="goods-table" lay-filter="goods-table"></table>
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
                elem: '#goods-table',
                url: '{{ route('goods.categorylist') }}',
                page: true,
                cols: [
                    [
                        {type: 'checkbox'},
                        {field: 'id', minWidth: 100, title: 'ID', align: "center"},
                        {field: 'title', minWidth: 240, title: '分类名称'},
                        {field: 'enable', title: '启用',templet:'#goods-enable'},
                        {title: '操作', templet: '#goods-bar', width: 150, align: 'center'}
                    ]
                ],
                skin: 'line',
                toolbar: '#goods-toolbar',
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

            form.on('switch(goods-enable)', function (data) {
                let loading = layer.load();
                $.ajax({
                    url: '{{ route('goods.changeCategory', ['id' => '_id', '_token' => csrf_token()]) }}'.replace('_id', data.value),
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

            table.on('toolbar(goods-table)', function(obj) {
                if (obj.event === 'add') {
                    layer.open({
                        type: 2,
                        title: '新增分类',
                        shade: 0.4,
                        area: ['700px', '400px'],
                        content: '{{ route('goods.addcategory') }}'
                    });
                }
            });

            table.on('tool(goods-table)', function (obj) {
                if (obj.event === 'edit') {
                    layer.open({
                        type: 2,
                        title: '修改',
                        shade: 0.4,
                        area: ['700px', '400px'],
                        content: '{{ route('goods.editcategory', ['id' => '_id']) }}'.replace('_id', obj.data.id)
                    });
                } else if (obj.event === 'remove') {
                    layer.confirm('确定要删除该分类', {
                        icon: 3,
                        title: '提示'
                    }, function(index) {
                        layer.close(index);
                        let loading = layer.load();
                        $.ajax({
                            url: '{{ route('goods.delCategory', ['id' => '_id', '_token' => csrf_token()])}}'.replace('_id', obj.data.id),
                            dataType: 'json',
                            type: 'delete',
                            success: function(result) {
                                layer.close(loading);
                                if(result.code === 0){
                                    layer.msg(result.msg,{icon:1,time:1000});
                                    table.reload("goods-table");
                                }else{
                                    layer.msg(result.msg,{icon:2,time:1000});
                                }
                            }
                        })
                    });
                } else if (obj.event === 'see') {
                    {{--var pop = layer.open({--}}
                    {{--    type: 2,--}}
                    {{--    title: 'banner列表',--}}
                    {{--    // shade: 0.4,--}}
                    {{--    // area: ['1600px',  '800px'],--}}
                    {{--    content: '{{ route('banner.index', ['id' => '_id']) }}'.replace('_id', obj.data.id),--}}
                    {{--    success: function (layer, index) {--}}

                    {{--    }--}}
                    {{--});--}}
                    {{--layer.full(pop);--}}
                }
            });
        })
    </script>

    <script type="text/html" id="goods-toolbar">
        <button class="pear-btn pear-btn-primary pear-btn-md" lay-event="add">
            <i class="layui-icon layui-icon-add-1"></i>
            新增
        </button>
    </script>

    <script type="text/html" id="goods-bar">
        <button class="pear-btn pear-btn-primary pear-btn-sm" lay-event="edit"><i class="layui-icon layui-icon-edit"></i></button>
        <button class="pear-btn pear-btn-danger pear-btn-sm" lay-event="remove"><i class="layui-icon layui-icon-delete"></i></button>
    </script>

    <script type="text/html" id="goods-see">
        <button class="pear-btn pear-btn-primary pear-btn-sm" lay-event="see">查看列表</button>
    </script>

    <script type="text/html" id="goods-enable">
        <input type="checkbox" name="status" value="@{{d.id}}" lay-skin="switch" lay-text="启用|禁用" lay-filter="goods-enable" @{{# if(d.status == 1){ }} checked @{{# } }}>
    </script>
@endsection
