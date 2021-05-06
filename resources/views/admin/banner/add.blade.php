@extends('admin.layout.main')

@section('title') banner添加 @endsection

@section('content')
    <form class="layui-form" action="">
{{--        {{ csrf_field() }}--}}
        <div class="main-container">
            <div class="layui-form-item">
                <label class="layui-form-label">名称：</label>
                <div class="layui-input-block">
                    <input type="text" name="title" lay-verify="required" value="" autocomplete="off" placeholder="请输入名称" class="layui-input">
                    <span class="layui-form-mid lay-validate-error"></span>
                </div>
            </div>
        </div>
        <div class="main-container">
            <div class="layui-form-item">
                <label class="layui-form-label">图片：</label>
                <div class="layui-input-block">
                    <div class="layui-col-md8">
                        <input type="text" class="layui-input pic" readonly required lay-verify="required" name="pic" value="{{ $data['titlepic'] ?? '' }}" placeholder="">
                    </div>
                    <div class="layui-col-md3">
                        <button type="button" class="layui-btn" id="upload-img">
                            <i class="layui-icon">&#xe67c;</i>上传图片
                        </button>
                        <span style="color: red">(900*320)</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="main-container">
            <div class="layui-form-item" hidden>
                <div class="layui-input-block">
                    <img src="" class="titlepic-img" style="height: 75px;width: 150px;">
                </div>
            </div>
        </div>
        <div class="main-container">
            <div class="layui-form-item">
                <label class="layui-form-label">url：</label>
                <div class="layui-input-block">
                    <input type="text" name="url" lay-verify="required" value="" autocomplete="off" placeholder="请输入跳转url" class="layui-input">
                    <span class="layui-form-mid lay-validate-error"></span>
                </div>
            </div>
        </div>
        <div class="main-container">
            <div class="layui-form-item">
                <label class="layui-form-label">是否显示：</label>
                <div class="layui-input-block">
                    <input type="radio" name="status" value="1" title="是" lay-filter="status" checked>
                    <input type="radio" name="status" value="2" title="否" lay-filter="status">
                </div>
            </div>
        </div>
        <div class="main-container">
            <div class="layui-form-item">
                <label class="layui-form-label">排序：</label>
                <div class="layui-input-block">
                    <input type="text" name="sort" lay-verify="required" value="0" autocomplete="off" placeholder="数字越大越排在前面" class="layui-input">
                    <span class="layui-form-mid lay-validate-error"></span>
                </div>
            </div>
        </div>
        <div class="main-container">
            <div class="layui-form-item">
                <label class="layui-form-label">类型：</label>
                <div class="layui-input-block">
                    <input type="radio" name="type" value="1" title="网页" lay-filter="type" checked>
                </div>
            </div>
        </div>
        <div class="bottom">
            <div class="button-container">
                <button type="submit" class="layui-btn layui-btn-normal layui-btn-sm" lay-submit="" lay-filter="save">
                    <i class="layui-icon layui-icon-ok"></i>
                    提交
                </button>
                <button type="reset" class="layui-btn layui-btn-primary layui-btn-sm">
                    <i class="layui-icon layui-icon-refresh"></i>
                    重置
                </button>
            </div>
        </div>
    </form>
@endsection

@section('script')
    <script src="/admin/component/pear/pear.js"></script>
    <script>
        layui.use(['form','jquery', 'validate','upload','notice'],function(){
            let form = layui.form;
            let validate = layui.validate;
            let $ = layui.jquery;
            let upload = layui.upload;
            let notice = layui.notice;
            upload.render({
                elem: '#upload-img' //绑定元素
                ,url: '{{ route('banner.uploadimg') }}' //上传接口
                ,accept: 'images'
                // ,data:{folder:'banner',uploadFile:'file'}
                // ,field:'file'
                ,size:2048
                ,done: function(res) {
                    if (res.code === 0) {
                        notice.success('上传成功');
                        $('.pic').val(res.data.path);
                        $('.titlepic-img').attr('src', res.data.path);
                        $('.titlepic-img').parent().parent().show();
                    } else {
                        notice.msg(res.exception);
                    }
                }
                ,error: function(res){
                    notice.success('上传失败');
                }
            });

            form.on('submit(save)', function(data){
                validate.clear(data.form);
                $.ajax({
                    url:'',
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
                                parent.layui.table.reload("banner-table");
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
