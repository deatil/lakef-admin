@extends('admin.layout')

@section('title', '创建角色')

@section('container')
<div class="layuimini-main">
    <form class="layui-form" action="">
        <div class="layui-form-item">
            <label class="layui-form-label">角色名</label>
            <div class="layui-input-block">
                <input type="text" name="username" lay-verify="required" lay-reqtext="角色名不能为空" placeholder="请输入角色名" autocomplete="off" class="layui-input">
            </div>
        </div>
        
        <div class="layui-form-item">
            <label class="layui-form-label">守护类型</label>
            <div class="layui-input-block">
                <input type="text" name="username" value="server" lay-verify="required" lay-reqtext="守护类型不能为空" placeholder="请输入守护类型" autocomplete="off" class="layui-input">
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
                <button class="layui-btn" lay-submit="" lay-filter="demo1">立即提交</button>
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
});
</script>
@endsection