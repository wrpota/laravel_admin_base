@extends('admin.layout.main')

@section('title')菜单操作页 @endsection

@section('content')
    <form class="layui-form" action="">
        {{ csrf_field() }}
        <div class="mainBox">
            <div class="main-container">
                <div class="layui-form-item">
                    <label class="layui-form-label">标题</label>
                    <div class="layui-input-block">
                        <input type="text" name="title" lay-verify="required" value="{{ $menu->title ?? '' }}" autocomplete="off" placeholder="请输入标题" class="layui-input">
                        <span class="layui-form-mid lay-validate-error"></span>
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">父级分类</label>
                    <div class="layui-input-block">
                        <select name="pid" lay-verify="required">
                            <option value="0">顶级分类</option>
                            @foreach ($menuTree as $item)
                                <option value="{{ $item['id'] }}" @if (($menu->pid ?? 0) == $item['id'])selected @endif @if($item['type'] == 1) disabled @endif> @for ($i = 1; $i < $item['level']; $i++)&emsp;&emsp; @endfor @if($item['type'] == 0)🗂 @else📄 @endif {{ $item['title'] }}</option>
                            @endforeach
                        </select>
                        <span class="layui-form-mid lay-validate-error"></span>
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">图标</label>
                    <div class="layui-input-inline">
                        <input type="text" name="icon" lay-verify="required" value="{{ $menu->icon ?? '' }}" placeholder="请输入图标" autocomplete="off" class="layui-input">
                    </div>
                    <div class="layui-form-mid layui-word-aux">
                        EX: set-fill 参考 <a href="https://www.layui.com/doc/element/icon.html" target="_blank">Layui</a>
                    </div>
                    <span class="layui-form-mid lay-validate-error"></span>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">类型</label>
                    <div class="layui-input-block">
                        <select name="type" lay-verify="required">
                            <option value="0">目录</option>
                            <option value="1" @if(($menu->type ?? 0) == 1) selected @endif>菜单</option>
                        </select>
                        <span class="layui-form-mid lay-validate-error"></span>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">链接</label>
                    <div class="layui-input-inline">
                        <input type="text" name="href" lay-verify="required" value="{{ $menu->href ?? '' }}" placeholder="请输入链接" autocomplete="off" class="layui-input">
                        <span class="layui-form-mid lay-validate-error"></span>
                    </div>
                    <div class="layui-form-mid layui-word-aux">
                        目录时填none
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">打开方式</label>
                    <div class="layui-input-block">
                        <input type="radio" name="open_type" value="_iframe" title="内部打开" @if(($menu->open_type ?? '_iframe') == '_iframe') checked @endif>
                        <input type="radio" name="open_type" value="_blank" title="新标签打开" @if(($menu->open_type ?? '_iframe') == '_blank') checked @endif>
                    </div>
                    <span class="layui-form-mid lay-validate-error"></span>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">排序</label>
                    <div class="layui-input-inline">
                        <input type="number" name="sort" lay-verify="required" value="{{ $menu->sort ?? 0 }}" placeholder="请输入排序" autocomplete="off" class="layui-input">
                    </div>
                    <div class="layui-form-mid layui-word-aux">
                        整数 正序排序
                    </div>
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
                                parent.layui.treetable.reload("permission-table");
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
