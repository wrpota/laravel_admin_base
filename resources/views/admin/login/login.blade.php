<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Login Page</title>
    <!-- 样 式 文 件 -->
    <link rel="stylesheet" href="component/pear/css/pear.css" />
    <link rel="stylesheet" href="admin/css/other/login.css" />
</head>
<!-- 代 码 结 构 -->
<body background="admin/images/background2.svg" style="background-size: cover;">
<form class="layui-form" action="javascript:void(0);">
    <div class="layui-form-item">
        <img class="logo" src="admin/images/logo.png" />
        <div class="title">管理后台</div>
    </div>
    {{ csrf_field() }}
    <div class="layui-form-item">
        <input placeholder="账 户" hover class="layui-input" name="name" />
        <span class="layui-form-mid lay-validate-error"></span>
    </div>
    <div class="layui-form-item">
        <input placeholder="密 码" hover class="layui-input" name="password" type="password"/>
        <span class="layui-form-mid lay-validate-error"></span>
    </div>
    <div class="layui-form-item">
        <input placeholder="验证码"  hover class="code layui-input layui-input-inline" name="captcha" />
        <img src="{{captcha_src()}}" class="codeImage" onclick="this.src='{{captcha_src()}}'+Math.random()">
        <span class="layui-form-mid lay-validate-error"></span>
    </div>
    <div class="layui-form-item">
        <input type="checkbox" name="remember" title="记住登录状态" lay-skin="primary" checked>
    </div>
    <div class="layui-form-item">
        <button type="button" class="pear-btn pear-btn-success login" lay-submit lay-filter="login">
            登 入
        </button>
    </div>
</form>
<!-- 资 源 引 入 -->
<script src="component/layui/layui.js"></script>
<script src="component/pear/pear.js"></script>
<script>
    layui.use(['form', 'element', 'jquery', 'popup', 'validate'], function() {
        var form = layui.form
            ,$ = layui.jquery
            ,popup = layui.popup
            ,validate = layui.validate;

        form.on("submit(login)",function(data){
            validate.clear(data.form);
            $.ajax({
                elem: '{{ route('auth.login') }}',
                type: 'post',
                data:data.field,
                error: function (res) {
                    validate.form(res, data.form);
                    $('.codeImage').click();
                },
                success: function (msg, data) {
                    popup.success("登录成功,正在为您跳转", function() {
                        location.href = "/admin/index";
                    });
                }
            });
            return false;
        })
    })
</script>
</body>
</html>
