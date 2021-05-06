@extends('admin.layout.main')

@section('title') 反馈详情 @endsection

@section('content')
    <form class="layui-form" action="">
        <div class="main-container">
            <div class="layui-form-item">
                <label class="layui-form-label">手机号：</label>
                <div class="layui-input-block">
                    <input type="text" name="url" lay-verify="required" value="{{ $data->phone ?? '' }}" autocomplete="off" placeholder="请输入跳转url" class="layui-input" readonly>
                    <span class="layui-form-mid lay-validate-error"></span>
                </div>
            </div>
        </div>
        <div class="main-container">
            <div class="layui-form-item">
                <label class="layui-form-label">微信：</label>
                <div class="layui-input-block">
                    <input type="text" name="url" lay-verify="required" value="{{ $data->wechat ?? '' }}" autocomplete="off" placeholder="请输入跳转url" class="layui-input" readonly>
                    <span class="layui-form-mid lay-validate-error"></span>
                </div>
            </div>
        </div>
        <div class="main-container">
            <div class="layui-form-item">
                <label class="layui-form-label">反馈内容：</label>
                <div class="layui-input-block">
                    <textarea type="text" class="layui-textarea" name="reply_content" placeholder="请输入内容" readonly>{{ $data->content ?? ''}}</textarea>
                </div>
            </div>
        </div>
        <div class="main-container">
            <div class="layui-form-item">
                <label class="layui-form-label">回复内容：</label>
                <div class="layui-input-block">
                    <textarea type="text" class="layui-textarea" name="reply_content" placeholder="请输入内容" readonly>{{ $data->reply_content ?? ''}}</textarea>
                </div>
            </div>
        </div>
        <div class="main-container">
            <div class="layui-form-item">
                <label class="layui-form-label">回复时间：</label>
                <div class="layui-input-block">
                    <input type="text" name="url" lay-verify="required" value="{{ $data->updated_at ?? '' }}" autocomplete="off" placeholder="请输入跳转url" class="layui-input" readonly>
                    <span class="layui-form-mid lay-validate-error"></span>
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

        })
    </script>
@endsection
