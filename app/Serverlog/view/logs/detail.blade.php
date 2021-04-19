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
                    <td class="break-all">{{ $data['id'] }}</td>
                </tr>
                <tr>
                    <td>请求链接</td>
                    <td class="break-all">{{ $data['url'] }}</td>
                </tr>
                <tr>
                    <td>请求方式</td>
                    <td class="break-all">{{ $data['method'] }}</td>
                </tr>
                <tr>
                    <td>请求详情</td>
                    <td class="break-all">{{ $data['info'] }}</td>
                </tr>
                <tr>
                    <td>请求头信息</td>
                    <td class="break-all">{{ $data['useragent'] }}</td>
                </tr>
                <tr>
                    <td>来源IP</td>
                    <td class="break-all">{{ $data['ip'] }}</td>
                </tr>
                <tr>
                    <td>管理员ID</td>
                    <td class="break-all">{{ $data['admin_id'] }}</td>
                </tr>
                <tr>
                    <td>管理员</td>
                    <td class="break-all">{{ $data['admin_name'] }}</td>
                </tr>
                <tr>
                    <td>请求时间</td>
                    <td class="break-all">
                        {{ date('Y-m-d H:i:s', $data['create_time']) }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection