@extends('admin::layout')

@section('title', '权限')

@section('container')
<div class="layuimini-main">

    <fieldset class="table-search-fieldset">
        <legend>搜索信息</legend>
        <div style="margin: 10px 10px 10px 10px">
            <form class="layui-form layui-form-pane" action="">
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">权限名称</label>
                        <div class="layui-input-inline">
                            <input type="text" name="display_name" autocomplete="off" class="layui-input">
                        </div>
                    </div>

                    <div class="layui-inline">
                        <label class="layui-form-label">权限</label>
                        <div class="layui-input-inline">
                            <input type="text" name="name" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    
                    <div class="layui-inline">
                        <label class="layui-form-label">请求方式</label>
                        <div class="layui-input-inline">
                            <select name="method">
                                <option value="">全部方式</option>
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
                    </div>
                    
                    <div class="layui-inline">
                        <label class="layui-form-label">菜单</label>
                        <div class="layui-input-inline">
                            <select name="is_menu">
                                <option value="">全部显示</option>
                                <option value="1">显示</option>
                                <option value="2">隐藏</option>
                            </select>
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
            <button class="layui-btn layui-btn-green layui-btn-sm data-menu-btn" lay-event="menu"> 菜单 </button>
        </div>
    </script>

    <table class="layui-hide" id="currentTableId" lay-filter="currentTableFilter"></table>
    
</div>

@verbatim
<script type="text/html" id="currentTableBar">
    <a class="layui-btn layui-btn-normal layui-btn-xs data-count-edit" lay-event="edit">编辑</a>
    <a class="layui-btn layui-btn-green layui-btn-xs" lay-event="add">添加</a>
    <a class="layui-btn layui-btn-xs layui-btn-danger data-count-delete" lay-event="delete">删除</a>
</script>

<script type="text/html" id="iconTpl">
    <span title="{{d.display_name}}：{{d.id}}"><i class='fa {{d.icon}}'></i></span>
</script>

<script type="text/html" id="targetTpl">
    {{#  if(d.target == '_self'){ }}
        <span class="layui-btn layui-btn-normal layui-btn-xs">本页</span>
    {{#  } }}
    {{#  if(d.target == '_blank'){ }}
        <span class="layui-btn layui-btn-green layui-btn-xs">跳出</span>
    {{#  } }}
    {{#  if(d.target == '_top'){ }}
        <span class="layui-btn layui-btn-primary layui-btn-xs">顶页</span>
    {{#  } }}
</script>
@endverbatim

<script type="text/html" id="menuTpl">
    <input type="checkbox" name="is_menu" data-href="{{ admin_url('permission/setmenu') }}?id=@{{ d.id }}" value="@{{ d.id }}" lay-skin="switch" lay-text="显示|隐藏" lay-filter="switchStatus" @{{ d.is_menu==1 ? 'checked' : '' }}>
</script>

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
        url: "{{ admin_url('permission/index-data') }}",
        method: 'get',
        toolbar: '#toolbarDemo',
        defaultToolbar: ['filter', 'exports', 'print'],
        cols: [
            [
                {field: 'id', width: 80, title: 'ID', sort: true},
                {title: '图标', width: 80, align: 'center', templet:'#iconTpl' },
                {field: 'display_name', width: 120, title: '权限名称'},
                {field: 'name', minWidth: 80, title: '权限'},
                {field: 'target', width: 120, title: '跳转方式', align: "center", templet: '#targetTpl'},
                {field: 'is_menu', align: 'center', width: 95, title: '菜单', templet: '#menuTpl', unresize: true },
                {field: 'sort', width: 80, title: '排序', edit: 'text'},
                {field: 'created_at', width: 160, title: '创建时间', sort: true},
                {title: '操作', width: 160, align: "center", toolbar: '#currentTableBar', align: "center"}
            ]
        ],
        limits: [10, 15, 20, 25, 50, 100],
        limit: 15,
        page: true,
        skin: 'line'
    });

    // 监听搜索操作
    form.on('submit(data-search-btn)', function (data) {
        var data = data.field;
        
        var name = data.name;
        var display_name = data.display_name;
        var method = data.method;
        var is_menu = data.is_menu;
        var guard_name = data.guard_name;

        // 执行搜索重载
        table.reload('currentTableId', {
            page: {
                curr: 1
            }, 
            where: {
                name: name,
                display_name: display_name,
                method: method,
                is_menu: is_menu,
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
                title: '创建权限',
                type: 2,
                shade: 0.2,
                maxmin:true,
                shadeClose: true,
                area: ['100%', '100%'],
                content: "{{ admin_url('permission/create') }}",
            });
            $(window).on("resize", function () {
                layer.full(index);
            });
        } else if (obj.event === 'menu') {
            var index = layer.open({
                title: '权限菜单',
                type: 2,
                shade: 0.2,
                maxmin:true,
                shadeClose: true,
                area: ['100%', '100%'],
                content: "{{ admin_url('permission/menu') }}",
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
        } else if (obj.event === 'delete') {
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
        } else if (obj.event === 'add') {
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
    table.on('edit(currentTableFilter)', function(obj) {
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
    
    form.on('switch(switchStatus)', function(data) {
        var that = $(this),
            status = 0;
        if (!that.attr('data-href')) {
            notice.info('请设置data-href参数');
            return false;
        }
        if (this.checked) {
            status = 1;
        }
        $.post(that.attr('data-href'), { status: status }, function(res) {
            if (res.code === 0) {
                miniAdmin.success(res.message);
            } else {
                miniAdmin.error(res.message);
                that.trigger('click');
                form.render('checkbox');
            }
        });
    });
});
</script>
@endsection