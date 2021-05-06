@extends('admin.layout.main')

@section('title') 分类编辑 @endsection

@section('content')
    <form class="layui-form" action="">
        {{ csrf_field() }}
        <div class="main-container">
            <div class="layui-form-item">
                <label class="layui-form-label">分类名称：</label>
                <div class="layui-input-block">
                    <input type="text" name="title" lay-verify="required" value="{{ $goods->title ?? '' }}" autocomplete="off" placeholder="请输入名称" class="layui-input">
                    <span class="layui-form-mid lay-validate-error"></span>
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
        layui.use(['form','jquery', 'validate'],function(){
            let form = layui.form;
            let validate = layui.validate;
            let $ = layui.jquery;

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
                                parent.layui.table.reload("goods-table");
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
