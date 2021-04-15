@extends('serverlog::layout')

@section('title', '创建角色')

@section('container')
<div class="layuimini-main">
    <form class="layui-form" action="">
        <div class="layui-form-item">
            <label class="layui-form-label">父级</label>
            <div class="layui-input-block">
                <select name="parent_id" lay-verify="required" lay-filter="parent_id">
                    <option value="0">作为顶级角色</option>
                    @foreach($parents as $key => $parent)
                        <option value="{{ $parent['id'] }}" {{ ($parentid == $parent['id'] ? 'selected=""' : '') }}>{!! $parent['spacer'] !!}{{ $parent['name'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        
        <div class="layui-form-item">
            <label class="layui-form-label">角色名</label>
            <div class="layui-input-block">
                <input type="text" name="name" lay-verify="required" lay-reqtext="角色名不能为空" placeholder="请输入角色名" autocomplete="off" class="layui-input">
            </div>
        </div>
        
        <div class="layui-form-item">
            <label class="layui-form-label">守护类型</label>
            <div class="layui-input-block">
                <select name="guard_name" lay-filter="guard_name">
                    <option value=""></option>
                    <option value="server" selected="">server</option>
                </select>
            </div>
        </div>
        
        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">角色描述</label>
            <div class="layui-input-block">
                <textarea name="description" placeholder="请输入角色描述" class="layui-textarea"></textarea>
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
        
        if (data.name == '') {
            layer.msg('角色名不能为空');
            return false;
        }
        if (data.guard_name == '') {
            layer.msg('守护类型不能为空');
            return false;
        }
        
        var href = "{{ admin_url('role/create') }}";
        
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