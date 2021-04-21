@extends('admin::layout')

@section('title', '修改密码')

@section('style_after')
<style>
.layui-form-item .layui-input-company {width: auto;padding-right: 10px;line-height: 38px;}
</style>
@endsection

@section('container')
<div class="layuimini-main">

    <div class="layui-form layuimini-form">
        <div class="layui-form-item">
            <label class="layui-form-label required">旧的密码</label>
            <div class="layui-input-block">
                <input type="password" name="old_password" lay-verify="required" lay-reqtext="旧的密码不能为空" placeholder="请输入旧的密码"  value="" class="layui-input">
                <tip>输入自己账号的旧的密码。</tip>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label required">新的密码</label>
            <div class="layui-input-block">
                <input type="password" name="new_password" lay-verify="required" lay-reqtext="新的密码不能为空" placeholder="请输入新的密码"  value="" class="layui-input">
                <tip>输入账号新的密码</tip>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label required">确认密码</label>
            <div class="layui-input-block">
                <input type="password" name="again_password" lay-verify="required" lay-reqtext="确认密码不能为空" placeholder="请输入确认密码"  value="" class="layui-input">
                <tip>输入账号新的确认密码</tip>
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn layui-btn-normal" lay-submit lay-filter="saveBtn">确认更新</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script_after')
<script src="lib/md5.js" charset="utf-8"></script>
<script>
layui.use(['form','miniTab'], function () {
    var form = layui.form,
        layer = layui.layer,
        $ = layui.jquery,
        miniTab = layui.miniTab;

    //监听提交
    form.on('submit(saveBtn)', function (data) {
        data = data.field;
        if (data.old_password == '') {
            layer.msg('旧的密码不能为空');
            return false;
        }
        if (data.new_password == '') {
            layer.msg('新的密码不能为空');
            return false;
        }
        if (data.again_password == '') {
            layer.msg('确认密码不能为空');
            return false;
        }
        
        var href = "{{ admin_url('profile/password') }}";
        
        $.post(href, {
            'old_password': hex_md5(data.old_password),
            'new_password': hex_md5(data.new_password),
            'again_password': hex_md5(data.again_password),
        }, function(data) {
            if (data.code == 0) {
                layer.msg('密码更新成功', function () {
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
