@extends('admin::layout')

@section('title', '应用管理')

@section('container')
<div class="layuimini-main">

    <fieldset class="table-search-fieldset">
        <legend>搜索信息</legend>
        <div style="margin: 10px 10px 10px 10px">
            <form class="layui-form layui-form-pane" action="">
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">应用名称</label>
                        <div class="layui-input-inline">
                            <input type="text" name="name" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    
                    <div class="layui-inline">
                        <label class="layui-form-label">应用appid</label>
                        <div class="layui-input-inline">
                            <input type="text" name="app_id" autocomplete="off" class="layui-input">
                        </div>
                    </div>

                    <div class="layui-inline">
                        <button type="submit" class="layui-btn layui-btn-primary"  lay-submit lay-filter="data-search-btn"><i class="layui-icon"></i> 搜 索</button>
                    </div>
                </div>
            </form>
        </div>
    </fieldset>

    <table class="layui-hide" id="currentTableId" lay-filter="currentTableFilter"></table>

</div>

<script type="text/html" id="toolbarDemo">
    <div class="layui-btn-container">
        <button class="layui-btn layui-btn-normal layui-btn-sm data-add-btn" lay-event="add"> 添加 </button>
    </div>
</script>

<script type="text/html" id="currentTableBar">
    <a class="layui-btn layui-btn-normal layui-btn-xs data-count-edit" lay-event="edit">编辑</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs data-count-delete" lay-event="delete">删除</a>
</script>

@verbatim
<script type="text/html" id="allowOriginTpl">
    {{# if (d.is_check == 1) { }}
        <span class="layui-badge layui-bg-green">是</span>
    {{# } else { }}
        <span class="layui-badge">否</span>
    {{# } }}
</script>

<script type="text/html" id="checkTpl">
    {{# if (d.is_check == 1) { }}
        <span class="layui-badge layui-bg-green">是</span>
    {{# } else { }}
        <span class="layui-badge">否</span>
    {{# } }}
</script>

<script type="text/html" id="statusTpl">
    {{# if (d.status == 1) { }}
        <span class="layui-badge layui-bg-green">启用</span>
    {{# } else { }}
        <span class="layui-badge">禁用</span>
    {{# } }}
</script>
@endverbatim

@endsection

@section('script_after')
<script>
layui.use(['form', 'table'], function () {
    var $ = layui.jquery,
        form = layui.form,
        $ = layui.jquery,
        table = layui.table;

    table.render({
        elem: '#currentTableId',
        url: "{{ admin_url('server-log/app/index-data') }}",
        method: 'get',
        toolbar: '#toolbarDemo',
        defaultToolbar: ['filter', 'exports', 'print'],
        cols: [[
            {field: 'name', minWidth: 100, title: '应用名称'},
            {field: 'app_id', width: 180, title: 'AppId'},
            {field: 'allow_origin', width: 80, title: '跨域', templet: '#allowOriginTpl', align: "center"},
            {field: 'is_check', width: 80, title: '验证', templet: '#checkTpl', align: "center"},
            {field: 'status', width: 80, title: '状态', templet: '#statusTpl', align: "center"},
            {field: 'created_at', width: 160, title: '创建时间', sort: true},
            {title: '操作', width: 160, toolbar: '#currentTableBar', align: "center"}
        ]],
        limits: [10, 15, 20, 25, 50, 100],
        limit: 15,
        page: true,
        skin: 'line'
    });

    // 监听搜索操作
    form.on('submit(data-search-btn)', function (data) {
        var data = data.field;

        // 执行搜索重载
        table.reload('currentTableId', {
            page: {
                curr: 1
            }, 
            where: data
        }, 'data');

        return false;
    });

    /**
     * toolbar监听事件
     */
    table.on('toolbar(currentTableFilter)', function (obj) {
        if (obj.event === 'add') {  // 监听添加操作
            var index = layer.open({
                title: '创建应用',
                type: 2,
                shade: 0.2,
                maxmin:true,
                shadeClose: true,
                area: ['100%', '100%'],
                content: "{{ admin_url('server-log/app/create') }}",
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
                title: '编辑应用',
                type: 2,
                shade: 0.2,
                maxmin:true,
                shadeClose: true,
                area: ['100%', '100%'],
                content: "{{ admin_url('server-log/app/update') }}?id=" + data.id,
            });
            $(window).on("resize", function () {
                layer.full(index);
            });
            return false;
        } else if (obj.event === 'delete') {
            layer.confirm('真的要删除该条数据吗？', function (index) {
                var url = "{{ admin_url('server-log/app/delete') }}";
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
        }
    });

});
</script>
@endsection