@extends('admin::layout')

@section('title', '查看菜单')

@section('style_after')
<style>
.layui-btn:not(.layui-btn-lg ):not(.layui-btn-sm):not(.layui-btn-xs) {
    height: 34px;
    line-height: 34px;
    padding: 0 8px;
}
</style>
@endsection

@section('container')
<div class="layuimini-main">
    <div>
        <div class="layui-btn-group">
            <button class="layui-btn" id="btn-expand">全部展开</button>
            <button class="layui-btn layui-btn-normal" id="btn-fold">全部折叠</button>
        </div>
        
        <button class="layui-btn layui-btn-normal" id="btn-add"> 添加 </button>
        
        <table id="menu-table" class="layui-table" lay-filter="menu-table"></table>
    </div>
</div>

@verbatim
<script type="text/html" id="iconTpl">
    <span title="{{d.display_name}}：{{d.id}}"><i class='fa {{d.icon}}'></i></span>
</script>

<script type="text/html" id="guardNameTpl">
    <span class="layui-badge layui-bg-green">{{ d.guard_name }}</span>
</script>

<script type="text/html" id="menuTpl">
    <div class="layui-btn-group">
        {{# if (d.is_menu == 1) { }}
            <span class="layui-btn layui-btn-xs layui-btn-rim">菜单</span>
        {{# } else { }}
            <span class="layui-btn layui-btn-xs layui-btn-danger">隐藏</span>
        {{# } }}
        
        {{# if (d.is_click == 1) { }}
            <span class="layui-btn layui-btn-xs layui-bg-gray">点击</span>
        {{# } else { }}
            <span class="layui-btn layui-btn-xs layui-bg-blue">禁用</span>
        {{# } }}
    </div>
</script>

<!-- 操作列 -->
<script type="text/html" id="auth-state">
    <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="edit">编辑</a>
    <a class="layui-btn layui-btn-green layui-btn-xs" lay-event="add">添加</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="delete">删除</a>
</script>
@endverbatim

@endsection

@section('script_after')
<script>
layui.use(['table', 'treetable', 'miniAdmin'], function () {
    var $ = layui.jquery;
    var table = layui.table;
    var treetable = layui.treetable;
    var miniAdmin = layui.miniAdmin;

    // 渲染表格
    layer.load(2);
    treetable.render({
        treeColIndex: 1,
        treeSpid: 0,
        treeIdName: 'id',
        treePidName: 'parent_id',
        elem: '#menu-table',
        url: "{{ admin_url('permission/menu-data') }}",
        page: false,
        skin: 'line',
        cols: [
            [
                {width: 80, title: '图标',align: 'center', templet: '#iconTpl' },
                {field: 'display_name', minWidth: 200, title: '权限名称'},
                {field: 'name', minWidth: 80, title: '权限'},
                {field: 'guard_name', width: 100, title: '守护类型', align: "center", templet: '#guardNameTpl'},
                {field: 'sort', width: 80, title: '排序', edit: 'text'},
                {field: 'isMenu', width: 100, title: '类型', align: 'center', templet: '#menuTpl' },
                {field: 'created_at', width: 160, title: '创建时间', sort: true},
                {title: '操作', width: 160, toolbar: '#auth-state', align: "center"}
            ]
        ],
        done: function () {
            layer.closeAll('loading');
            treetable.foldAll('#menu-table');
        }
    });
    
    $('#btn-expand').click(function () {
        treetable.expandAll('#menu-table');
    });
    
    $('#btn-fold').click(function () {
        treetable.foldAll('#menu-table');
    });
    
    $('#btn-add').click(function (obj) {
        var index = layer.open({
            title: '创建权限',
            type: 2,
            shade: 0.2,
            maxmin:true,
            shadeClose: true,
            area: ['100%', '100%'],
            content: "{{ admin_url('permission/create') }}",
        });
    });

    // 监听工具条
    table.on('tool(menu-table)', function (obj) {
        var data = obj.data;
        var layEvent = obj.event;

        if (layEvent === 'delete') {
            layer.confirm('真的要删除该条数据吗？', function (index) {
                var url = "{{ admin_url('permission/delete') }}";
                $.post(url, {
                    id: data.id
                }, function(data) {
                    if (data.code == 0) {
                        layer.msg(data.message, function () {
                            obj.del();
                            layer.close(index);
                        });
                    } else {
                        layer.msg(data.message);
                    }
                });
            });
        } else if (layEvent === 'edit') {
            var index = layer.open({
                title: '编辑权限',
                type: 2,
                shade: 0.2,
                maxmin:true,
                shadeClose: true,
                area: ['100%', '100%'],
                content: "{{ admin_url('permission/update') }}?id=" + data.id,
            });
            $(window).on("resize", function () {
                layer.full(index);
            });
            return false;
        } else if (layEvent === 'add') {
            var index = layer.open({
                title: '创建权限',
                type: 2,
                shade: 0.2,
                maxmin:true,
                shadeClose: true,
                area: ['100%', '100%'],
                content: "{{ admin_url('permission/create') }}?parentid=" + data.id,
            });
            $(window).on("resize", function () {
                layer.full(index);
            });
        }
    });
    
    // 监听单元格编辑
    table.on('edit(menu-table)', function(obj) {
        var value = obj.value,
            data = obj.data;
            
        var url = "{{ admin_url('permission/sort') }}";
        $.post(url, { 
            'id': data.id, 
            'value': value 
        }, function(data) {
            if (data.code == 0) {
                miniAdmin.success(data.message);
            } else {
                miniAdmin.error(data.message);
            }

        })
    });
});
</script>
@endsection
