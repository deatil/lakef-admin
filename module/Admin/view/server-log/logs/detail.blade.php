@extends('admin::layout')

@section('title', '附件详情')

@section('container')
<div class="layuimini-main">
    <div class="table-responsive">
        <table class="layui-table">
            <colgroup>
                <col width="200">
                <col>
            </colgroup>
      
            <thead>
                <tr>
                    <td>名称</td>
                    <td>内容</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>ID</td>
                    <td class="break-all">{{ $info['id'] }}</td>
                </tr>
                <tr>
                    <td>AppId</td>
                    <td class="break-all">{{ $info['app_id'] }}</td>
                </tr>
                <tr>
                    <td>App应用名称</td>
                    <td class="break-all">{{ $app['name'] ?? '--' }}</td>
                </tr>
                <tr>
                    <td>日志内容</td>
                    <td class="break-all" id="json_view">{!! $info['content'] !!}</td>
                </tr>
                <tr>
                    <td>日志时间</td>
                    <td class="break-all">{{ date('Y-m-d H:i:s', $info['add_time']) }}</td>
                </tr>
                <tr>
                    <td>来源IP</td>
                    <td class="break-all">{{ $info['add_ip'] }}</td>
                </tr>
                <tr>
                    <td>创建时间</td>
                    <td class="break-all">{{ $info['created_at'] }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('script_after')
<script type="text/javascript" src="lib/jquery.min.js"></script>
<script type="text/javascript" src="lib/jquery.jsonview/jquery.jsonview.js"></script>
<link rel="stylesheet" href="lib/jquery.jsonview/jquery.jsonview.css" />
<script type="text/javascript">
$(function() {
    var json = $("#json_view").html();
    $("#json_view").JSONView(json, { 
        collapsed: true, 
        nl2br: true, 
        recursive_collapser: true 
    });
});
</script>
@endsection