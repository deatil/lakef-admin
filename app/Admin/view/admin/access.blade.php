@extends('serverlog::layout')

@section('title', '账号授权')

@section('container')
<div class="layuimini-main">
    <form class="layui-form" method="post" action="{{ admin_url('admin/access') }}">
        <div class="layui-form-item">
            <label class="layui-form-label">用户名</label>
            <div class="layui-input-block w300">
                <input type="text" disabled class="layui-input" value="{{ $info['name'] }}">
            </div>
        </div>
        
        <div class="layui-form-item">
            <label class="layui-form-label">权限组</label>
            <div class="layui-input-block w300">
                <select name="roleid" 
                    xm-select="roleid" 
                    xm-select-search="" 
                    lay-filter="roleid">
                    @foreach($roles as $vo)
                        <option value="{{ $vo['id'] }}" {{ in_array($vo['id'], $admin_roles) ? 'selected' : '' }}>{!! $vo['spacer'] !!}{{ $vo['name'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="layui-input-block">
                <div class="layui-form-mid layui-word-aux">选择需要授权的用户组。</div>
            </div>
        </div>
        
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="" lay-filter="formSubmit">立即提交</button>
            </div>
        </div>
    </form>
</div>
@endsection

@section('script_after')
<link rel="stylesheet" href="lib/formSelects-v4/formSelects-v4.css" />
<script src="lib/formSelects-v4/formSelects-v4.js"></script>
<script type="text/javascript">
layui.use(['form'], function () {
    var $ = layui.jquery,
        form = layui.form;
        
    $(function() {
        var formSelects = layui.formSelects;
        
        formSelects.value('roleid');
    });

    // 监听提交
    form.on('submit(formSubmit)', function (data) {
        var data = data.field;
        
        var href = "{{ admin_url('admin/access') }}?id={{ $info['id'] }}";
        
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