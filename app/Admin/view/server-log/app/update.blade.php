@extends('admin::layout')

@section('title', '更新账号')

@section('container')
<div class="layuimini-main">
    <form class="layui-form layuimini-form" action="">
        <div class="layui-form-item">
            <label class="layui-form-label required">应用名称</label>
            <div class="layui-input-block">
                <input type="text" name="name" value="{{ $info['name'] }}" lay-verify="required" lay-reqtext="账号不能为空" placeholder="请输入账号" autocomplete="off" class="layui-input">
            </div>
            <div class="layui-input-block">
                <div class="layui-form-mid layui-word-aux">应用名称通常为中文、字母、数字、下划线、中划线及其组合</div>
            </div>
        </div>
        
        <div class="layui-form-item">
            <label class="layui-form-label required">AppId</label>
            <div class="layui-input-block">
                <input type="text" disabled name="app_id" value="{{ $info['app_id'] }}" lay-verify="required" lay-reqtext="账号不能为空" placeholder="请输入账号" autocomplete="off" class="layui-input">
            </div>
            <div class="layui-input-block">
                <div class="layui-form-mid layui-word-aux">应用AppId</div>
            </div>
        </div>
        
        <div class="layui-form-item">
            <label class="layui-form-label required">AppSecret</label>
            <div class="layui-input-block">
                <input type="text" disabled name="app_secret" value="{{ $info['app_secret'] }}" lay-verify="required" lay-reqtext="账号不能为空" placeholder="请输入账号" autocomplete="off" class="layui-input">
            </div>
            <div class="layui-input-block">
                <div class="layui-form-mid layui-word-aux">应用AppSecret</div>
            </div>
        </div>
        
        <div class="layui-form-item">
            <label class="layui-form-label required">更新AppSecret</label>
            <div class="layui-input-block">
                <input type="checkbox" name="make_secret" lay-skin="switch" lay-text="更新|保持">
            </div>
            <div class="layui-input-block">
                <div class="layui-form-mid layui-word-aux">通常在AppSecret丢失时更新</div>
            </div>
        </div>
        
        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">应用描述</label>
            <div class="layui-input-block">
                <textarea name="description" placeholder="请输入应用描述" class="layui-textarea">{{ $info['description'] }}</textarea>
            </div>
            <div class="layui-input-block">
                <div class="layui-form-mid layui-word-aux">设置应用的描述信息</div>
            </div>
        </div>
        
        <div class="layui-form-item">
            <label class="layui-form-label required">允许跨域</label>
            <div class="layui-input-block">
                <input type="checkbox" {{ ($info['allow_origin'] == 1 ? 'checked=""' : '') }} name="allow_origin" lay-skin="switch" lay-text="启用|禁用">
            </div>
            <div class="layui-input-block">
                <div class="layui-form-mid layui-word-aux">应用的api是否允许跨域</div>
            </div>
        </div>
        
        <div class="layui-form-item">
            <label class="layui-form-label required">权限检测</label>
            <div class="layui-input-block">
                <input type="checkbox" {{ ($info['is_check'] == 1 ? 'checked=""' : '') }} name="is_check" lay-skin="switch" lay-text="启用|禁用">
            </div>
            <div class="layui-input-block">
                <div class="layui-form-mid layui-word-aux">基于appid的检测</div>
            </div>
        </div>
        
        <div class="layui-form-item">
            <label class="layui-form-label required">排序</label>
            <div class="layui-input-block">
                <input type="text" name="sort" value="{{ $info['sort'] }}" lay-verify="required" lay-reqtext="排序不能为空" placeholder="请输入应用的排序" autocomplete="off" class="layui-input">
            </div>
            <div class="layui-input-block">
                <div class="layui-form-mid layui-word-aux">设置应用的排序</div>
            </div>
        </div>
        
        <div class="layui-form-item">
            <label class="layui-form-label required">状态</label>
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
        form = layui.form;

    // 监听提交
    form.on('submit(saveBtn)', function (data) {
        var data = data.field;
        
        var href = "{{ admin_url('server-log/app/update') }}?id={{ $info['id'] }}";
        
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