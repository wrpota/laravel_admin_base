@extends('admin.layout.main')

@section('title') 编辑 @endsection

@section('content')
    <form class="layui-form" action="">
        <div class="main-container">
            <div class="layui-form-item">
                <label class="layui-form-label">标题：</label>
                <div class="layui-col-md6">
                    <input type="text" class="layui-input" name="title" placeholder="请输入标题" value="{{ $article['title'] }}">
                </div>
            </div>
        </div>
        <div class="main-container">
            <div class="layui-form-item">
                <label class="layui-form-label">内容：</label>
                <div class="layui-col-md6">
                    <textarea name="content" id="content" cols="30" rows="10">
                        {!!$article['content']!!}
                    </textarea>
                </div>
            </div>
        </div>
        <div class="main-container">
            <div class="layui-form-item">
                <label class="layui-form-label">价格：</label>
                <div class="layui-col-md6">
                    <input type="text" class="layui-input" name="price" placeholder="请输入价格" value="{{ $article['price'] }}">
                </div>
            </div>
        </div>
        <div class="main-container">
            <div class="layui-form-item">
                <label class="layui-form-label">原价：</label>
                <div class="layui-col-md6">
                    <input type="text" class="layui-input" name="oldprice" placeholder="请输入原价" value="{{ $article['oldprice'] }}">
                </div>
            </div>
        </div>
        <div class="main-container">
            <div class="layui-form-item">
                <label class="layui-form-label">下载链接：</label>
                <div class="layui-col-md6">
                    <input type="text" class="layui-input" name="url" placeholder="请输入下载链接" value="{{ $article['url'] }}">
                </div>
            </div>
        </div>
        <div class="main-container">
            <div class="layui-form-item">
                <label class="layui-form-label">是否显示：</label>
                <div class="layui-input-block">
                    <input type="radio" name="status" value="1" title="是" lay-filter="status" @if($article['status'] == 1) checked @endif>
                    <input type="radio" name="status" value="2" title="否" lay-filter="status" @if($article['status'] == 2) checked @endif>
                </div>
            </div>
        </div>
        <div style="height: 40px;"></div>
        <div class="main-container">
            <div class="layui-form-item layui-fixed">
                <div class="layui-input-block">
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
        </div>
    </form>
@endsection

@section('script')
    <script src="/admin/component/pear/pear.js"></script>
    <script>
        layui.use(['form','jquery', 'validate','upload','notice','tinymce'],function(){
            let form = layui.form;
            let validate = layui.validate;
            let $ = layui.jquery;
            let upload = layui.upload;
            let notice = layui.notice;

            var tinymce = layui.tinymce
            var edit = tinymce.render({
                elem: "#content",
                height: 400
            });

            form.on('submit(save)', function(data){
                data.field.content = edit.getContent();
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
                                parent.layui.table.reload("article-table");
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
