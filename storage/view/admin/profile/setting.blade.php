@extends('admin.layout')

@section('title', '个人设置')

@section('style_after')
<style>
.layui-form-item .layui-input-company {width: auto;padding-right: 10px;line-height: 38px;}
</style>
@endsection

@section('container')
<div class="layuimini-main">

    <div class="layui-form layuimini-form">
        <div class="layui-form-item">
            <label class="layui-form-label required">管理账号</label>
            <div class="layui-input-block">
                <input type="text" name="username" lay-verify="required" lay-reqtext="管理账号不能为空" placeholder="请输入管理账号"  value="{{ $admin['name'] }}" class="layui-input">
                <tip>填写自己管理账号的名称。</tip>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label required">邮箱</label>
            <div class="layui-input-block">
                <input type="email" name="email"  placeholder="请输入邮箱"  value="{{ $admin['email'] }}" class="layui-input">
            </div>
        </div>
        
        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">备注信息</label>
            <div class="layui-input-block">
                <textarea name="remark" class="layui-textarea" placeholder="请输入备注信息">{{ $admin['remark'] }}</textarea>
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn layui-btn-normal" lay-submit lay-filter="saveBtn">确认保存</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script_after')
<script>
layui.use(['form','miniTab'], function () {
    var form = layui.form,
        layer = layui.layer,
        $ = layui.jquery,
        miniTab = layui.miniTab;

    //监听提交
    form.on('submit(saveBtn)', function (data) {
        data = data.field;
        if (data.username == '') {
            layer.msg('用户名不能为空');
            return false;
        }
        if (data.email == '') {
            layer.msg('用户邮箱不能为空');
            return false;
        }
        
        var href = "{{ admin_url('profile/setting') }}";
        
        $.post(href, {
            'name': data.username,
            'email': data.email,
            'remark': data.remark,
        }, function(data) {
            if (data.code == 0) {
                layer.msg('信息更新成功', function () {
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
