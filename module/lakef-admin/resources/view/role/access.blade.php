@extends('admin::layout')

@section('title', '编辑访问权限')

@section('container')
<style>
.role-access {
    border: 1px solid #D2D2D2;
    border-radius: 2px;
    padding: 5px 0 15px 3px;
}
</style>
<div class="layuimini-main">
    <form class="layui-form form-horizontal" action="" method="post">
        <input type="hidden" name="id" value="{{ $info['id'] }}" />
        
        <div class="layui-form-item">
            <label class="layui-form-label">角色</label>
            <div class="layui-input-block">
                <input type="text" class="layui-input" value="{{ $info['name'] }}" disabled>
            </div>
        </div>
        
        <div class="layui-form-item">
            <label class="layui-form-label">角色权限</label>
            <div class="layui-input-block">
                <div class="role-access left">
                    <div style="margin-left: 5px;margin-top:5px; padding: 0;">
                        <a href="javascript:;" title="全部展开、折叠 ">
                            <span class="button ico_open"></span>
                            <span id="expandAll" data-open="true">全部展开、折叠 </span>
                        </a> 
                    </div>
                    <div id="permissionTree"></div>
                </div>
            </div>
        </div>
        
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn ajax-post" lay-submit="" lay-close="self" lay-filter="*" target-form="form-horizontal">立即提交</button>
            </div>
        </div>
    </form>
</div>
@endsection

@section('script_after')
<script>
layui.use(['jquery', 'miniAdmin', 'tree'], function() {
    var $ = layui.$,
        miniAdmin = layui.miniAdmin,
        tree = layui.tree;
        
        // 获取选中节点的id
        function getCheckedIds(data) {
            var id = "";
            $(data).each(function (index, item) {
                if (id != "") {
                    id = id + "," + item.id;
                }
                else {
                    id = item.id;
                }
                var i = getCheckedIds(item.children);
                if (i != "") {
                    id = id + "," + i;
                }
            });
            return id;
        }

        // 格式化选中节点的id
        function formatCheckedIds(ids, data) {
            var checkedIds = " ";
            $(data).each(function (index, item) {
                var id = item.id;
                if (item.children == undefined 
                    && $.inArray(id, ids) != -1 
                ) {
                    if (checkedIds != "") {
                        checkedIds = checkedIds + "," + id;
                    } else {
                        checkedIds = id;
                    }
                }
                
                if (item.children != undefined) {
                    var childCheckedIds = formatCheckedIds(ids, item.children);
                    if (childCheckedIds != "") {
                        checkedIds = checkedIds + "," + childCheckedIds;
                    }
                }
            });
            
            var checkedIdArr = checkedIds.split(',');
            checkedIdArr = $.grep(checkedIdArr, function(n, i) {
                return n;
            },false);
            
            return checkedIdArr;
        }
    
    // 节点数据
    var data = {!! json_encode($permissions) !!};
    
    // 已有权限
    var permissions = {!! json_encode($access_permissions) !!};
    
    // 选中
    var checkedIds = formatCheckedIds(permissions, data);
    
    tree.render({
        elem: '#permissionTree',
        data: data,
        showCheckbox: true,  //是否显示复选框,
        id: 'permissionTreeId',
        isJump: false,
        click: function(obj){
        }
    });
    
    tree.setChecked('permissionTreeId', checkedIds); //勾选指定节点
    
    $("#expandAll").click(function() {
        if ($(this).data("open")) {
            $('.layui-tree-setHide').find('.layui-tree-iconClick').trigger('click');
            $(this).data("open", false);
        } else {
            $('.layui-tree-spread').find('.layui-tree-iconClick').trigger('click');
            $(this).data("open", true);
        }
    });
    
    // 通用表单post提交
    $('.ajax-post').on('click', function(e) {
        var id = $('input[name="id"]').val();
        
        var checkedData = tree.getChecked('permissionTreeId'); //获取选中节点的数据
        var permissions = getCheckedIds(checkedData);

        var href = "{{ admin_url('role/access') }}";
        
        var query = {
            'id': id,
            'permissions': permissions,
        };

        $.post(href, query).success(function(data) {
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