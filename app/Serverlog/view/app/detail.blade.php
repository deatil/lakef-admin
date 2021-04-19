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
                @if (in_array($data['ext'], ['png', 'jpg', 'jpeg', 'gif', 'bmp', 'webp']))
                <tr>
                    <td>预览</td>
                    <td>
                        <a href="{{ $data['uri'] }}" title="点击查看原图" target="_blank">
                            <img src="{{ $data['uri'] }}" />
                        </a>
                    </td>
                </tr>
                @endif
                <tr>
                    <td>ID</td>
                    <td class="break-all">{{ $data['id'] }}</td>
                </tr>
                <tr>
                    <td>原始名称</td>
                    <td class="break-all">{{ $data['name'] }}</td>
                </tr>
                <tr>
                    <td>路径</td>
                    <td class="break-all">{{ $data['path'] }}</td>
                </tr>
                <tr>
                    <td>mime类型</td>
                    <td>{{ $data['mime'] }}</td>
                </tr>
                <tr>
                    <td>后缀</td>
                    <td>{{ $data['ext'] }}</td>
                </tr>
                <tr>
                    <td>大小</td>
                    <td>{{ $data['sizes'] }}</td>
                </tr>
                <tr>
                    <td>md5</td>
                    <td class="break-all">{{ $data['md5'] }}</td>
                </tr>
                <tr>
                    <td>sha1散列值</td>
                    <td class="break-all">{{ $data['sha1'] }}</td>
                </tr>
                <tr>
                    <td>上传驱动</td>
                    <td>{{ $data['driver'] }}</td>
                </tr>
                <tr>
                    <td>状态</td>
                    <td>
                        <span class='layui-badge-dot layui-bg-green'></span>
                        <span class='text-green'>启用</span>
                    </td>
                </tr>
                <tr>
                    <td>添加时间</td>
                    <td>
                        {{ date('Y-m-d H:i:s', $data['create_time']) }}
                    </td>
                </tr>
                <tr>
                    <td>更新时间</td>
                    <td>
                        {{ date('Y-m-d H:i:s', $data['update_time']) }}
                    </td>
                </tr>

            </tbody>
        </table>
    </div>
</div>
@endsection