@extends('serverlog::layout')

@section('title', '创建角色')

@section('container')
<div class="layuimini-main">
    <form class="layui-form layuimini-form" action="">
        <div class="layui-form-item">
            <label class="layui-form-label">账号</label>
            <div class="layui-input-block">
                <input type="text" value="{{ $info['name'] }}" disabled="" autocomplete="off" class="layui-input">
            </div>
            <div class="layui-input-block">
                <div class="layui-form-mid layui-word-aux">账号名称</div>
            </div>
        </div>
        
        <div class="layui-form-item">
            <label class="layui-form-label required">新密码</label>
            <div class="layui-input-block">
                <input type="password" name="password" lay-verify="required" lay-reqtext="新密码不能为空" placeholder="请输入新密码" autocomplete="off" class="layui-input">
            </div>
            <div class="layui-input-block">
                <div class="layui-form-mid layui-word-aux">
                    密码推荐使用字母、下划线及其组合为佳
                </div>
            </div>
        </div>
        
        <div class="layui-form-item">
            <label class="layui-form-label required">确认新密码</label>
            <div class="layui-input-block">
                <input type="password" name="password_confirm" lay-verify="required" lay-reqtext="确认新密码不能为空" placeholder="请再次输入新密码" autocomplete="off" class="layui-input">
            </div>
            <div class="layui-input-block">
                <div class="layui-form-mid layui-word-aux">
                    请确认密码
                </div>
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
<script src="lib/md5.js" charset="utf-8"></script>
<script>
layui.use(['form'], function () {
    var $ = layui.jquery,
        form = layui.form,
        $ = layui.jquery;

    // 监听提交
    form.on('submit(saveBtn)', function (data) {
        var data = data.field;
        
        var href = "{{ admin_url('admin/password') }}?id={{ $info['id'] }}";
        
        $.post(href, {
            'password': hex_md5(data.password),
            'password_confirm': hex_md5(data.password_confirm),
        }, function(data) {
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