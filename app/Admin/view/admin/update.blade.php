@extends('serverlog::layout')

@section('title', '更新账号')

@section('container')
<div class="layuimini-main">
    <form class="layui-form" action="">
        <div class="layui-form-item">
            <label class="layui-form-label">账号</label>
            <div class="layui-input-block">
                <input type="text" name="name" value="{{ $info['name'] }}" lay-verify="required" lay-reqtext="账号不能为空" placeholder="请输入账号" autocomplete="off" class="layui-input">
            </div>
        </div>
        
        <div class="layui-form-item">
            <label class="layui-form-label">账号邮箱</label>
            <div class="layui-input-block">
                <input type="text" name="email" value="{{ $info['email'] }}" lay-verify="required" lay-reqtext="账号邮箱不能为空" placeholder="请输入账号邮箱" autocomplete="off" class="layui-input">
            </div>
            <div class="layui-input-block">
                <div class="layui-form-mid layui-word-aux">后台登陆使用</div>
            </div>
        </div>
        
        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">账号备注</label>
            <div class="layui-input-block">
                <textarea name="remark" placeholder="请输入账号备注" class="layui-textarea">{{ $info['remark'] }}</textarea>
            </div>
        </div>
        
        <div class="layui-form-item">
            <label class="layui-form-label">状态</label>
            <div class="layui-input-block">
                <input type="checkbox" {{ ($info['status'] == 1 ? 'checked=""' : '') }} name="status" lay-skin="switch" lay-text="启用|禁用">
            </div>
        </div>
        
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit lay-filter="saveBtn">立即提交</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
            </div>
        </div>
    </form>
</div>
@endsection

@section('script_after')
<script>
layui.use(['form'], function () {
    var $ = layui.jquery,
        form = layui.form,
        $ = layui.jquery;

    // 监听提交
    form.on('submit(saveBtn)', function (data) {
        var data = data.field;
        
        var href = "{{ admin_url('admin/update') }}?id={{ $info['id'] }}";
        
        $.post(href, data, function(data) {
            if (data.code == 0) {
                layer.msg(data.message, function () {
                    window.location.reload();
                });
            } else {
                layer.msg(data.message);
            }
        });
        
        return false;
    });
});
</script>
@endsection