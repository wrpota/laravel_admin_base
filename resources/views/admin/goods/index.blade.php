@extends('admin.layout.main')

@section('title') 商品列表 @endsection

@section('content')
    <div class="layui-card">
        <div class="layui-card-body">
            <form class="layui-form" action="">
                <div class="layui-form-item">
                    <div class="layui-input-inline">
                        <input type="text" name="goodsname" placeholder="请输入名称" class="layui-input">
                    </div>
                    <div class="layui-input-inline">
                        <input type="text" name="goodid" placeholder="请输入商品id" class="layui-input">
                    </div>
                    <div class="layui-input-inline layui-form">
                        <select class="layui-select category">
                            <option value=0>选择分类</option>
                            @foreach($category as $item)
                                <option value="{{ $item['id'] }}">{{ $item['title'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button class="pear-btn pear-btn-md pear-btn-primary" type="submit" lay-submit="select" lay-filter="select">
                        <i class="layui-icon layui-icon-search"></i>
                        查询
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div class="layui-card">
        <div class="layui-card-body">
            <table id="good-table" lay-filter="good-table"></table>
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
                elem: '#good-table',
                url: '{{ route('goods.list') }}',
                page: true,
                cols: [
                    [
                        {type: 'checkbox'},
                        {field: 'id', minWidth: 100, title: 'ID', align: "center"},
                        {field: 'pic', templet: '#pic', minWidth: 240, title: '图片'},
                        {field: 'title', minWidth: 240, title: '商品名称'},
                        {field: 'price', minWidth: 240, title: '价格'},
                        {field: 'sjsales', minWidth: 240, title: '实际销量'},
                        {field: 'enable', title: '启用',templet:'#good-enable'},
                        {field: 'enable', title: '置顶',templet:'#top-enable'},
                        {title: '操作', templet: '#good-bar', width: 150, align: 'center'}
                    ]
                ],
                skin: 'line',
                response: {
                    statusName: 'code'
                    ,statusCode: 0
                    ,msgName: 'msg'
                    ,countName: 'meta'
                    ,dataName: 'data'
                }
                ,even: true //开启隔行背景
            });
            // 查询
            form.on('submit(select)', function(data){
                validate.clear(data.form);
                $.ajax({
                    url:'{{ route('goods.select') }}',
                    data:JSON.stringify(data.field),
                    dataType:'json',
                    contentType:'application/json',
                    type:'post',
                    error: function (res) {
                        validate.form(res, data.form);
                        console.log(res);
                    },
                    success:function(result){
                        if(result.code === 0){
                            layer.msg(result.msg,{icon:1,time:1000},function(){
                                parent.layer.close(parent.layer.getFrameIndex(window.name));//关闭当前页
                                parent.layui.table.reload("good-table");
                            });
                        }else{
                            layer.msg(result.msg,{icon:2,time:1000});
                        }
                    }
                })
                return false;
            });
            // 上架/下架
            form.on('switch(good-enable)', function (data) {
                let loading = layer.load();
                $.ajax({
                    url: '{{ route('goods.change', ['id' => '_id', '_token' => csrf_token()]) }}'.replace('_id', data.value),
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
            // 置顶
            form.on('switch(top-enable)', function (data) {
                let loading = layer.load();
                $.ajax({
                    url: '{{ route('goods.top', ['id' => '_id', '_token' => csrf_token()]) }}'.replace('_id', data.value),
                    dataType:'json',
                    type:'put',
                    data: {top:data.elem.checked ? 1 : 2},
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

            table.on('toolbar(good-table)', function(obj) {
                if (obj.event === 'add') {
                    {{--var pop = layer.open({--}}
                    {{--    type: 2,--}}
                    {{--    title: '新增商品',--}}
                    {{--    content: '{{ route('goods.add', [ 'pid' => '_id']) }}'.replace('_id', {{ $pid }}),--}}
                    {{--});--}}
                    {{--layer.full(pop);--}}
                }
            });

            table.on('tool(good-table)', function (obj) {
                if (obj.event === 'edit') {
                    {{--var pop = layer.open({--}}
                    {{--    type: 2,--}}
                    {{--    title: '编辑商品',--}}
                    {{--    content: '{{ route('goods.edit', ['id' => '_id']) }}'.replace('_id', obj.data.id)--}}
                    {{--});--}}
                    {{--layer.full(pop);--}}
                }
            });
        })
    </script>

    <script type="text/html" id="pic">
        <img src="@{{ d.pic }}" style="width: 30px; height: 30px">
    </script>

{{--    <script type="text/html" id="good-toolbar">--}}
{{--        <button class="pear-btn pear-btn-primary pear-btn-md" lay-event="add">--}}
{{--            <i class="layui-icon layui-icon-add-1"></i>--}}
{{--            新增--}}
{{--        </button>--}}
{{--    </script>--}}

    <script type="text/html" id="good-bar">
        <button class="pear-btn pear-btn-primary pear-btn-sm" lay-event="edit"><i class="layui-icon layui-icon-edit"></i></button>
        <button class="pear-btn pear-btn-danger pear-btn-sm" lay-event="remove"><i class="layui-icon layui-icon-delete"></i></button>
    </script>

    <script type="text/html" id="good-enable">
        <input type="checkbox" name="status" value="@{{d.id}}" lay-skin="switch" lay-text="上架|下架" lay-filter="good-enable" @{{# if(d.status == 1){ }} checked @{{# } }}>
    </script>

    <script type="text/html" id="top-enable">
        <input type="checkbox" name="top" value="@{{d.id}}" lay-skin="switch" lay-text="置顶|不置顶" lay-filter="top-enable" @{{# if(d.top == 1){ }} checked @{{# } }}>
    </script>
@endsection
