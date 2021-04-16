@extends('serverlog::layout')

@section('title', '角色')

@section('container')
<div class="layuimini-main">

    <fieldset class="table-search-fieldset">
        <legend>搜索信息</legend>
        <div style="margin: 10px 10px 10px 10px">
            <form class="layui-form layui-form-pane" action="">
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">角色名称</label>
                        <div class="layui-input-inline">
                            <input type="text" name="name" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    
                    <div class="layui-inline">
                        <label class="layui-form-label">守护类型</label>
                        <div class="layui-input-inline">
                            <input type="text" name="guard_name" autocomplete="off" class="layui-input">
                        </div>
                    </div>

                    <div class="layui-inline">
                        <button type="submit" class="layui-btn layui-btn-primary"  lay-submit lay-filter="data-search-btn"><i class="layui-icon"></i> 搜 索</button>
                    </div>
                </div>
            </form>
        </div>
    </fieldset>

    <script type="text/html" id="toolbarDemo">
        <div class="layui-btn-container">
            <button class="layui-btn layui-btn-normal layui-btn-sm data-add-btn" lay-event="add"> 添加 </button>
            <button class="layui-btn layui-btn-sm layui-btn-danger data-delete-btn" lay-event="delete"> 删除 </button>
            <button class="layui-btn layui-btn-green layui-btn-sm data-tree-btn" lay-event="tree"> 菜单 </button>
        </div>
    </script>

    <table class="layui-hide" id="currentTableId" lay-filter="currentTableFilter"></table>

    <script type="text/html" id="currentTableBar">
        <a class="layui-btn layui-btn-xs data-count-access" lay-event="access">授权</a>
        <a class="layui-btn layui-btn-normal layui-btn-xs data-count-edit" lay-event="edit">编辑</a>
        <a class="layui-btn layui-btn-xs layui-btn-danger data-count-delete" lay-event="delete">删除</a>
    </script>

</div>
@endsection

@section('script_after')
<script>
layui.use(['form', 'table', 'miniAdmin'], function () {
    var $ = layui.jquery,
        form = layui.form,
        miniAdmin = layui.miniAdmin,
        table = layui.table;

    table.render({
        elem: '#currentTableId',
        url: "{{ admin_url('role/data') }}",
        method: 'get',
        toolbar: '#toolbarDemo',
        defaultToolbar: ['filter', 'exports', 'print', {
            title: '提示',
            layEvent: 'LAYTABLE_TIPS',
            icon: 'layui-icon-tips'
        }],
        cols: [[
            {type: "checkbox", width: 50},
            {field: 'id', width: 80, title: 'ID', sort: true},
            {field: 'name', title: '角色名'},
            {field: 'guard_name', width: 135, title: '守护类型'},
            {field: 'sort', width: 80, title: '排序', edit: 'text'},
            {field: 'created_at', width: 160, title: '创建时间', sort: true},
            {title: '操作', minWidth: 80, toolbar: '#currentTableBar', align: "center"}
        ]],
        limits: [10, 15, 20, 25, 50, 100],
        limit: 15,
        page: true,
        skin: 'line'
    });

    // 监听搜索操作
    form.on('submit(data-search-btn)', function (data) {
        var data = data.field;
        var name = data.name;
        var guard_name = data.guard_name;

        // 执行搜索重载
        table.reload('currentTableId', {
            page: {
                curr: 1
            }, 
            where: {
                name: name,
                guard_name: guard_name,
            }
        }, 'data');

        return false;
    });

    /**
     * toolbar监听事件
     */
    table.on('toolbar(currentTableFilter)', function (obj) {
        if (obj.event === 'add') {  // 监听添加操作
            var index = layer.open({
                title: '创建角色',
                type: 2,
                shade: 0.2,
                maxmin:true,
                shadeClose: true,
                area: ['100%', '100%'],
                content: "{{ admin_url('role/create') }}",
            });
            $(window).on("resize", function () {
                layer.full(index);
            });
        } else if (obj.event === 'delete') {  // 监听删除操作
            layer.confirm('真的要删除选中的数据吗？', function (index) {
                var checkStatus = table.checkStatus('currentTableId')
                    , data = checkStatus.data;
                var ids = [];
                $.each(data, function(index, item) {
                    ids.push(item.id);
                });
                
                var url = "{{ admin_url('role/delete') }}";
                $.post(url, {
                    id: ids
                }, function(data) {
                    if (data.code == 0) {
                        layer.msg(data.message, function () {
                            table.reload('currentTableId', {
                                page: {
                                    curr: 1
                                }, 
                                where: {
                                    name: '',
                                    guard_name: '',
                                }
                            }, 'data');
                            
                            layer.close(index);
                        });
                    } else {
                        layer.msg(data.message);
                    }
                });
            });
        } else if (obj.event === 'tree') {
            var index = layer.open({
                title: '角色结构',
                type: 2,
                shade: 0.2,
                maxmin:true,
                shadeClose: true,
                area: ['100%', '100%'],
                content: "{{ admin_url('role/tree') }}",
            });
            $(window).on("resize", function () {
                layer.full(index);
            });
        }
    });

    //监听表格复选框选择
    table.on('checkbox(currentTableFilter)', function (obj) {
        // console.log(obj)
    });

    table.on('tool(currentTableFilter)', function (obj) {
        var data = obj.data;
        if (obj.event === 'edit') {
            var index = layer.open({
                title: '编辑角色',
                type: 2,
                shade: 0.2,
                maxmin:true,
                shadeClose: true,
                area: ['100%', '100%'],
                content: "{{ admin_url('role/update') }}?id=" + data.id,
            });
            $(window).on("resize", function () {
                layer.full(index);
            });
            return false;
        } else if (obj.event === 'delete') {
            layer.confirm('真的要删除该条数据吗？', function (index) {
                var url = "{{ admin_url('role/delete') }}";
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
        } else if (obj.event === 'access') {
            var index = layer.open({
                title: '账号授权',
                type: 2,
                shade: 0.2,
                maxmin:true,
                shadeClose: true,
                area: ['100%', '100%'],
                content: "{{ admin_url('role/access') }}?id=" + data.id,
            });
            $(window).on("resize", function () {
                layer.full(index);
            });
        }
    });
    
    // 监听单元格编辑
    table.on('edit(currentTableFilter)', function(obj) {
        var value = obj.value,
            data = obj.data;
            
        var url = "{{ admin_url('role/sort') }}";
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