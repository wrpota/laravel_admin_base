@extends('admin.layout.main')

@section('title') 编辑用户 @endsection

@section('content')
    <form class="layui-form" action="">
        <div class="main-container">
            <div class="layui-form-item">
                <label class="layui-form-label">用户名：</label>
                <div class="layui-col-md4">
                    <input type="text" class="layui-input" name="username" placeholder="请输入用户名" value="{{ $user['username'] }}">
                </div>
            </div>
        </div>
        <div class="main-container">
            <div class="layui-form-item">
                <label class="layui-form-label">手机号：</label>
                <div class="layui-col-md4">
                    <input type="text" class="layui-input" name="phone" placeholder="请输入手机号" value="{{ $user['phone'] }}">
                </div>
            </div>
        </div>
        <div class="main-container">
            <div class="layui-form-item">
                <label class="layui-form-label">登录密码：</label>
                <div class="layui-col-md4">
                    <input type="password" class="layui-input" name="password" placeholder="请输入密码" value="{{ $user['password'] }}">
                </div>
            </div>
        </div>
        <div class="main-container">
            <div class="layui-form-item">
                <label class="layui-form-label">Email：</label>
                <div class="layui-col-md4">
                    <input type="text" class="layui-input" name="email" placeholder="请输入邮箱" value="{{ $user['email'] }}">
                </div>
            </div>
        </div>
        <div class="main-container">
            <div class="layui-form-item">
                <label class="layui-form-label">微信号：</label>
                <div class="layui-col-md4">
                    <input type="text" class="layui-input" name="wechat" placeholder="请输入微信号" value="{{ $user['wechat'] }}">
                </div>
            </div>
        </div>
        <div class="main-container">
            <div class="layui-form-item">
                <label class="layui-form-label">性别：</label>
                <div class="layui-input-block">
                    <input type="radio" name="sex" value="0" title="未知" lay-filter="sex" @if($user['sex'] == 0) checked @endif>
                    <input type="radio" name="sex" value="1" title="男" lay-filter="sex" @if($user['sex'] == 1) checked @endif>
                    <input type="radio" name="sex" value="2" title="女" lay-filter="sex" @if($user['sex'] == 2) checked @endif>
                </div>
            </div>
        </div>
        <div class="main-container">
            <div class="layui-form-item">
                <label class="layui-form-label">年龄：</label>
                <div class="layui-col-md4">
                    <input type="text" class="layui-input" name="age" placeholder="请输入年龄" value="{{ $user['age'] }}">
                </div>
            </div>
        </div>
        <div class="main-container">
            <div class="layui-form-item">
                <label class="layui-form-label">状态：</label>
                <div class="layui-input-block">
                    <input type="radio" name="status" value="0" title="已删除" lay-filter="status" @if($user['status'] == 0) checked @endif>
                    <input type="radio" name="status" value="1" title="正常" lay-filter="status" @if($user['status'] == 1) checked @endif>
                    <input type="radio" name="status" value="2" title="冻结" lay-filter="status" @if($user['status'] == 2) checked @endif>
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
        layui.use(['form','jquery', 'validate','upload','notice'],function(){
            let form = layui.form;
            let validate = layui.validate;
            let $ = layui.jquery;
            let upload = layui.upload;
            let notice = layui.notice;

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
                                parent.layui.table.reload("user-table");
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
