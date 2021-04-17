@extends('serverlog::layout')

@section('title', '创建权限')

@section('container')
<style>
.w200 {
    width: 200px;
}
.w300 {
    width: 300px;
}
</style>
<div class="layuimini-main">
    <form class="layui-form layuimini-form" action="">
        <div class="layui-form-item">
            <label class="layui-form-label required">父级</label>
            <div class="layui-input-block">
                <select name="parent_id" lay-verify="required" lay-filter="parent_id">
                    <option value="0">作为顶级权限</option>
                    @foreach($permissions as $key => $permission)
                        <option value="{{ $permission['id'] }}" {{ ($parentid == $permission['id'] ? 'selected=""' : '') }}>{!! $permission['spacer'] !!}{{ $permission['display_name'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        
        <div class="layui-form-item">
            <label class="layui-form-label required">权限名称</label>
            <div class="layui-input-block">
                <input type="text" name="display_name" lay-verify="required" lay-reqtext="权限名称不能为空" placeholder="请输入权限名称" autocomplete="off" class="layui-input">
            </div>
            <div class="layui-input-block">
                <div class="layui-form-mid layui-word-aux">权限名称通常为中文名称</div>
            </div>
        </div>
            
        <div class="layui-form-item">
            <label class="layui-form-label required">请求链接</label>
            <div class="layui-input-block">
                <input type="text" name="url" value="/admin" lay-verify="required" lay-reqtext="请求链接不能为空" placeholder="请输入请求链接" autocomplete="off" class="layui-input">
            </div>
            <div class="layui-input-block">
                <div class="layui-form-mid layui-word-aux">请求链接通用格式: /admin/action</div>
            </div>
        </div>
        
        <div class="layui-form-item">
            <label class="layui-form-label required">请求方式</label>
            <div class="layui-input-block w300">
                <select name="method" lay-verify="required">
                    <option value="">选择请求方式</option>
                    <option value="GET">GET</option>
                    <option value="POST">POST</option>
                    <option value="PUT">PUT</option>
                    <option value="PATCH">PATCH</option>
                    <option value="DELETE">DELETE</option>
                    <option value="HEAD">HEAD</option>
                    <option value="PATCH">PATCH</option>
                    <option value="OPTIONS">OPTIONS</option>
                </select>
            </div>
            <div class="layui-input-block">
                <div class="layui-form-mid layui-word-aux">请求方式目前只适用POST和GET</div>
            </div>
        </div>
        
        <div class="layui-form-item">
            <label class="layui-form-label required">跳转方式</label>
            <div class="layui-input-block w300">
                <select name="target" lay-verify="required">
                    <option value="">选择跳转方式</option>
                    <option value="_self" selected>本页【_self】</option>
                    <option value="_blank">跳出【_blank】</option>
                    <option value="_top">顶页【_top】</option>
                </select>
            </div>
            <div class="layui-input-block">
                <div class="layui-form-mid layui-word-aux">菜单的跳转方式</div>
            </div>
        </div>
        
        <div class="layui-form-item">
            <label class="layui-form-label">菜单图标</label>
            <div class="layui-input-block">
                <div class="layui-inline w200">
                    <input type="text" name="icon" autocomplete="off" placeholder="菜单图标" class="layui-input" value="">
                </div>
                <div class="layui-inline w300">
                    <input type="text" id="iconPicker" lay-filter="iconPicker" class="hide" value="">
                </div>
            </div>
        </div>
            
        <div class="layui-form-item">
            <label class="layui-form-label required">守护类型</label>
            <div class="layui-input-block">
                <select name="guard_name" lay-filter="guard_name" lay-verify="required" lay-reqtext="守护类型不能为空">
                    <option value=""></option>
                    <option value="web" selected="">web</option>
                </select>
            </div>
        </div>
        
        <div class="layui-form-item">
            <label class="layui-form-label required">菜单显示</label>
            <div class="layui-input-block">
                <input type="checkbox" checked="" name="is_menu" lay-skin="switch" lay-text="开启|关闭">
            </div>
        </div>
        
        <div class="layui-form-item">
            <label class="layui-form-label required">菜单点击</label>
            <div class="layui-input-block">
                <input type="checkbox" checked="" name="is_click" lay-skin="switch" lay-text="开启|关闭">
            </div>
        </div>
        
        <div class="layui-form-item">
            <label class="layui-form-label required">排序</label>
            <div class="layui-input-block">
                <input type="text" name="sort" value="1000" lay-verify="required" lay-reqtext="排序不能为空" placeholder="请输入排序" autocomplete="off" class="layui-input">
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
layui.use(['iconPickerFa', 'form'], function () {
    var $ = layui.jquery,
        iconPickerFa = layui.iconPickerFa,
        form = layui.form;
        
    iconPickerFa.render({
        // 选择器，推荐使用input
        elem: '#iconPicker',
        // fa 图标接口
        url: "lib/font-awesome-4.7.0/less/variables.less",
        // 是否开启搜索：true/false，默认true
        search: true,
        // 是否开启分页：true/false，默认true
        page: true,
        // 每页显示数量，默认12
        limit: 12,
        // 点击回调
        click: function (data) {
            $('input[name=icon]').val(data.icon);
        },
        // 渲染成功后的回调
        success: function (d) {}
    });
        
    // 监听提交
    form.on('submit(saveBtn)', function (data) {
        var data = data.field;
        
        var href = "{{ admin_url('permission/create') }}";
        
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