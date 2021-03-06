@extends('admin::layout')

@section('title', '日志管理')

@section('container')
<div class="layuimini-main">

    <fieldset class="table-search-fieldset">
        <legend>搜索信息</legend>
        <div style="margin: 10px 10px 10px 10px">
            <form class="layui-form layui-form-pane" action="">
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">AppId</label>
                        <div class="layui-input-inline">
                            <input type="text" name="app_id" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    
                    <div class="layui-inline">
                        <label class="layui-form-label">日志内容</label>
                        <div class="layui-input-inline">
                            <input type="text" name="content" autocomplete="off" class="layui-input">
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

<script type="text/html" id="toolbarTpl">
    <div class="layui-btn-container">
        <button class="layui-btn layui-btn-sm layui-btn-danger data-delete-btn" lay-event="delete"> 删除 </button>
        <button class="layui-btn layui-btn-sm layui-btn-green data-clear-btn" lay-event="clear"> 清空 </button>
    </div>
</script>

@verbatim
<script type="text/html" id="createTimeTpl">
    {{ layui.util.toDateString(d.add_time * 1000, 'yyyy-MM-dd HH:mm:ss') }}
</script>

<script type="text/html" id="currentTableBar">
    <a class="layui-btn layui-btn-normal layui-btn-xs data-count-detail" lay-event="detail">详情</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs data-count-delete" lay-event="delete">删除</a>
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
        url: "{{ admin_url('server-log/logs/index-data') }}",
        method: 'get',
        toolbar: '#toolbarTpl',
        defaultToolbar: ['filter', 'exports', 'print'],
        cols: [[
            {type: "checkbox", width: 50},
            {field: 'id', width: 180, title: 'ID'},
            {field: 'app_id', minWidth: 100, title: 'AppId'},
            {field: 'add_ip', width: 120, title: '来源IP'},
            {field: 'add_time', width: 160, title: '添加时间', sort: true, templet: '#createTimeTpl'},
            {title: '操作', width: 120, toolbar: '#currentTableBar', align: "center"}
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
        if (obj.event === 'delete') {  // 监听删除操作
            layer.confirm('真的要删除选中的数据吗？', function (index) {
                var checkStatus = table.checkStatus('currentTableId')
                    , data = checkStatus.data;
                var ids = [];
                $.each(data, function(index, item) {
                    ids.push(item.id);
                });
                
                var url = "{{ admin_url('server-log/logs/delete') }}";
                $.post(url, {
                    id: ids
                }, function(data) {
                    if (data.code == 0) {
                        layer.msg(data.message, function () {
                            table.reload('currentTableId', {
                                page: {
                                    curr: 1
                                }, 
                                where: {}
                            }, 'data');
                            
                            layer.close(index);
                        });
                    } else {
                        layer.msg(data.message);
                    }
                });
            });
        } if (obj.event === 'clear') {
            layer.confirm('真的要清空30天前的数据吗？', function (index) {
                var url = "{{ admin_url('server-log/logs/clear') }}";
                $.post(url, {}, function(data) {
                    if (data.code == 0) {
                        layer.msg(data.message, function () {
                            table.reload('currentTableId', {
                                page: {
                                    curr: 1
                                }, 
                                where: {}
                            }, 'data');
                            
                            layer.close(index);
                        });
                    } else {
                        layer.msg(data.message);
                    }
                });
            });
        }
    });

    table.on('tool(currentTableFilter)', function (obj) {
        var data = obj.data;
        if (obj.event === 'detail') {
            var index = layer.open({
                title: '日志详情',
                type: 2,
                shade: 0.2,
                maxmin:true,
                shadeClose: true,
                area: ['100%', '100%'],
                content: "{{ admin_url('server-log/logs/detail') }}?id=" + data.id,
            });
            $(window).on("resize", function () {
                layer.full(index);
            });
            return false;
        } else if (obj.event === 'delete') {
            layer.confirm('真的要删除该日志吗？', function (index) {
                var url = "{{ admin_url('server-log/logs/delete') }}";
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