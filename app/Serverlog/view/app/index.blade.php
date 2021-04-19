@extends('admin::layout')

@section('title', '附件管理')

@section('container')
<div class="layuimini-main">

    <fieldset class="table-search-fieldset">
        <legend>搜索信息</legend>
        <div style="margin: 10px 10px 10px 10px">
            <form class="layui-form layui-form-pane" action="">
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">附件名</label>
                        <div class="layui-input-inline">
                            <input type="text" name="name" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    
                    <div class="layui-inline">
                        <label class="layui-form-label">附件后缀</label>
                        <div class="layui-input-inline">
                            <input type="text" name="ext" autocomplete="off" class="layui-input">
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

@verbatim
<script type="text/html" id="createTimeTpl">
    {{ layui.util.toDateString(d.create_time * 1000, 'yyyy-MM-dd HH:mm:ss') }}
</script>

<script type="text/html" id="currentTableBar">
    <a class="layui-btn layui-btn-normal layui-btn-xs data-count-detail" lay-event="detail">详情</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs data-count-delete" lay-event="delete">删除</a>
</script>

<script type="text/html" id="extTpl">
    <span class="layui-badge layui-bg-blue">{{ d.ext }}</span>
</script>

<script type="text/html" id="driverTpl">
    <span class="layui-badge layui-bg-green">{{ d.driver }}</span>
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
        url: "{{ admin_url('attachment/index-data') }}",
        method: 'get',
        toolbar: true,
        defaultToolbar: ['filter', 'exports', 'print'],
        cols: [[
            {field: 'name', minWidth: 100, title: '名称'},
            {field: 'sizes', width: 120, title: '大小'},
            {field: 'mime', width: 100, title: '类型', align: "center"},
            {field: 'ext', width: 100, title: '后缀', align: "center", templet: '#extTpl'},
            {field: 'driver', width: 100, title: '驱动', align: "center", templet: '#driverTpl'},
            {field: 'create_time', width: 160, title: '添加时间', sort: true, templet: '#createTimeTpl'},
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
        var name = data.name;
        var ext = data.ext;

        // 执行搜索重载
        table.reload('currentTableId', {
            page: {
                curr: 1
            }, 
            where: {
                name: name,
                ext: ext,
            }
        }, 'data');

        return false;
    });

    table.on('tool(currentTableFilter)', function (obj) {
        var data = obj.data;
        if (obj.event === 'detail') {
            var index = layer.open({
                title: '附件详情',
                type: 2,
                shade: 0.2,
                maxmin:true,
                shadeClose: true,
                area: ['100%', '100%'],
                content: "{{ admin_url('attachment/detail') }}?id=" + data.id,
            });
            $(window).on("resize", function () {
                layer.full(index);
            });
            return false;
        } else if (obj.event === 'delete') {
            layer.confirm('真的要删除该附件吗？', function (index) {
                var url = "{{ admin_url('attachment/delete') }}";
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