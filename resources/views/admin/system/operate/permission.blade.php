@extends('admin.layout.main')

@section('title')权限操作页 @endsection

@section('content')
    <form class="layui-form" action="" lay-filter="permission-form">
        {{ csrf_field() }}
        <div class="mainBox">
            <div class="main-container">
                <div class="layui-form-item">
                    <label class="layui-form-label">标题</label>
                    <div class="layui-input-block">
                        <input type="text" name="name" lay-verify="required" value="{{ $permission->name ?? '' }}" autocomplete="off" placeholder="请输入标题" class="layui-input">
                        <span class="layui-form-mid lay-validate-error"></span>
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">父级权限</label>
                    <div class="layui-input-block">
                        <select name="pid" lay-verify="required" lay-filter="pid" @if($permission->id ?? 0)disabled @endif>
                            <option value="0" identification="">顶级权限</option>
                            @foreach ($permissionTree as $item)
                                <option value="{{ $item['id'] }}" identification="{{ $item['identification'] ?? '' }}" @if (($permission->pid ?? 0) == $item['id'])selected @endif> @for ($i = 1; $i < $item['level']; $i++)&emsp;&emsp; @endfor @if(!empty($item['children']))🗂 @else📄 @endif {{ $item['name'] }}</option>
                            @endforeach
                        </select>
                        <span class="layui-form-mid lay-validate-error"></span>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">权限标识</label>
                    <div class="layui-input-block">
                        <input type="text" name="identification" value="{{ $permission->identification ?? '' }}" autocomplete="off" placeholder="请输入权限标识" class="layui-input">
                        <span class="layui-form-mid lay-validate-error"></span>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">备注</label>
                    <div class="layui-input-block">
                        <textarea name="remark" placeholder="请输入备注" class="layui-textarea">{{ $permission->remark ?? '' }}</textarea>
                        <span class="layui-form-mid lay-validate-error"></span>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">排序</label>
                    <div class="layui-input-inline">
                        <input type="number" name="sort" lay-verify="required" value="{{ $permission->sort ?? 0 }}" placeholder="请输入排序" autocomplete="off" class="layui-input">
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
            //选择父级权限时 将父级权限标识放入当前表单中
            form.on('select(pid)', function(data){
                let identification = data.elem[data.elem.selectedIndex].getAttribute('identification');
                if (identification !== '') {
                    identification += '.';
                }
                form.val("permission-form", {
                    "identification": identification
                });
            });

        })
    </script>
@endsection
