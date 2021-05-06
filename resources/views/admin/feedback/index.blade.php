@extends('admin.layout.main')

@section('title') 反馈列表 @endsection

@section('content')
    <div class="layui-card">
        <div class="layui-card-body">
            <table id="feedback-table" lay-filter="feedback-table"></table>
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
                elem: '#feedback-table',
                url: '{{ route('feedback.list') }}',
                page: true,
                cols: [
                    [
                        {type: 'checkbox'},
                        {field: 'id', minWidth: 100, title: 'ID', align: "center"},
                        {field: 'phone', minWidth: 240, title: '手机号'},
                        {field: 'wechat', minWidth: 240, title: '微信'},
                        {field: 'content', minWidth: 240, title: '内容'},
                        {title: '操作', templet: '#feedback-bar', width: 150, align: 'center'}
                    ]
                ],
                skin: 'line',
                toolbar: '#feedback-toolbar',
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

            table.on('tool(feedback-table)', function (obj) {
                if (obj.event === 'reply') {
                    layer.open({
                        type: 2,
                        title: '回复',
                        shade: 0.4,
                        area: ['700px', '400px'],
                        content: '{{ route('feedback.reply', ['id' => '_id']) }}'.replace('_id', obj.data.id)
                    });
                } else if (obj.event === 'review') {
                    var pop = layer.open({
                        type: 2,
                        title: '回复详情',
                        // shade: 0.4,
                        // area: ['1600px',  '800px'],
                        content: '{{ route('feedback.replydetail', ['id' => '_id']) }}'.replace('_id', obj.data.id)
                    });
                    layer.full(pop);
                }
            });
        })
    </script>

    <script type="text/html" id="banner-toolbar">
        <button class="pear-btn pear-btn-primary pear-btn-md" lay-event="add">
            <i class="layui-icon layui-icon-add-1"></i>
            新增
        </button>
    </script>

    <script type="text/html" id="feedback-bar">
        <button class="pear-btn pear-btn-primary pear-btn-sm" lay-event="reply"><i>回复</i></button>
        <button class="pear-btn pear-btn-primary pear-btn-sm" lay-event="review"><i>查看</i></button>
    </script>

@endsection
