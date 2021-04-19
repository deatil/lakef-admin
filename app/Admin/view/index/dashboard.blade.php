@extends('admin::layout')

@section('title', '首页')

@section('style_after')
<style>
.layui-card {border:1px solid #f2f2f2;border-radius:5px;}
.icon {margin-right:10px;color:#1aa094;}
.icon-cray {color:#ffb800!important;}
.icon-blue {color:#1e9fff!important;}
.icon-tip {color:#ff5722!important;}
.layuimini-qiuck-module {text-align:center;margin-top: 10px}
.layuimini-qiuck-module a i {display:inline-block;width:100%;height:60px;line-height:60px;text-align:center;border-radius:2px;font-size:30px;background-color:#F8F8F8;color:#333;transition:all .3s;-webkit-transition:all .3s;}
.layuimini-qiuck-module a cite {position:relative;top:2px;display:block;color:#666;text-overflow:ellipsis;overflow:hidden;white-space:nowrap;font-size:14px;}
.welcome-module {width:100%;height:210px;}
.panel {background-color:#fff;border:1px solid transparent;border-radius:3px;-webkit-box-shadow:0 1px 1px rgba(0,0,0,.05);box-shadow:0 1px 1px rgba(0,0,0,.05)}
.panel-body {padding:10px}
.panel-title {margin-top:0;margin-bottom:0;font-size:12px;color:inherit}
.label {display:inline;padding:.2em .6em .3em;font-size:75%;font-weight:700;line-height:1;color:#fff;text-align:center;white-space:nowrap;vertical-align:baseline;border-radius:.25em;margin-top: .3em;}
.layui-red {color:red}
.main_btn > p {height:40px;}
.layui-bg-number {background-color:#F8F8F8;}
.layuimini-notice:hover {background:#f6f6f6;}
.layuimini-notice {padding:7px 16px;clear:both;font-size:12px !important;cursor:pointer;position:relative;transition:background 0.2s ease-in-out;}
.layuimini-notice-title,.layuimini-notice-label {
    padding-right: 70px !important;text-overflow:ellipsis!important;overflow:hidden!important;white-space:nowrap!important;}
.layuimini-notice-title {line-height:28px;font-size:14px;}
.layuimini-notice-extra {position:absolute;top:50%;margin-top:-8px;right:16px;display:inline-block;height:16px;color:#999;}
</style>
@endsection

@section('container')
<div class="layuimini-main">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md8">
            <div class="layui-row layui-col-space15">
                <div class="layui-col-md6">
                    <div class="layui-card">
                        <div class="layui-card-header"><i class="fa fa-warning icon"></i>数据统计</div>
                        <div class="layui-card-body">
                            <div class="welcome-module">
                                <div class="layui-row layui-col-space10">
                                    <div class="layui-col-xs6">
                                        <div class="panel layui-bg-number">
                                            <div class="panel-body">
                                                <div class="panel-title">
                                                    <span class="label pull-right layui-bg-blue">实时</span>
                                                    <h5>管理员</h5>
                                                </div>
                                                <div class="panel-content">
                                                    <h1 class="no-margins">{{ $counts['admin'] }}</h1>
                                                    <small>系统管理员数量</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="layui-col-xs6">
                                        <div class="panel layui-bg-number">
                                            <div class="panel-body">
                                                <div class="panel-title">
                                                    <span class="label pull-right layui-bg-cyan">实时</span>
                                                    <h5>角色</h5>
                                                </div>
                                                <div class="panel-content">
                                                    <h1 class="no-margins">{{ $counts['role'] }}</h1>
                                                    <small>系统角色数量</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="layui-col-xs6">
                                        <div class="panel layui-bg-number">
                                            <div class="panel-body">
                                                <div class="panel-title">
                                                    <span class="label pull-right layui-bg-orange">实时</span>
                                                    <h5>权限菜单</h5>
                                                </div>
                                                <div class="panel-content">
                                                    <h1 class="no-margins">{{ $counts['permission'] }}</h1>
                                                    <small>系统权限菜单数量</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="layui-col-xs6">
                                        <div class="panel layui-bg-number">
                                            <div class="panel-body">
                                                <div class="panel-title">
                                                    <span class="label pull-right layui-bg-green">实时</span>
                                                    <h5>附件数量</h5>
                                                </div>
                                                <div class="panel-content">
                                                    <h1 class="no-margins">1234</h1>
                                                    <small>系统附件数量</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="layui-col-md6">
                    <div class="layui-card">
                        <div class="layui-card-header"><i class="fa fa-credit-card icon icon-blue"></i>快捷入口</div>
                        <div class="layui-card-body">
                            <div class="welcome-module">
                                <div class="layui-row layui-col-space10 layuimini-qiuck">
                                    <div class="layui-col-xs3 layuimini-qiuck-module">
                                        <a href="javascript:;" layuimini-content-href="{{ admin_url('permission/index') }}" data-title="权限菜单" data-icon="fa fa-window-maximize">
                                            <i class="fa fa-window-maximize"></i>
                                            <cite>权限菜单</cite>
                                        </a>
                                    </div>
                                    <div class="layui-col-xs3 layuimini-qiuck-module">
                                        <a href="javascript:;" layuimini-content-href="{{ admin_url('role/index') }}" data-title="角色" data-icon="fa fa-user">
                                            <i class="fa fa-user"></i>
                                            <cite>角色管理</cite>
                                        </a>
                                    </div>
                                    <div class="layui-col-xs3 layuimini-qiuck-module">
                                        <a href="javascript:;" layuimini-content-href="{{ admin_url('admin/index') }}" data-title="管理员" data-icon="fa fa-user-md">
                                            <i class="fa fa-user-md"></i>
                                            <cite>管理员</cite>
                                        </a>
                                    </div>
                                    <div class="layui-col-xs3 layuimini-qiuck-module">
                                        <a href="javascript:;" layuimini-content-href="{{ admin_url('profile/setting') }}" data-title="账号设置" data-icon="fa fa-gears">
                                            <i class="fa fa-gears"></i>
                                            <cite>账号设置</cite>
                                        </a>
                                    </div>
                                    <div class="layui-col-xs3 layuimini-qiuck-module">
                                        <a href="javascript:;" layuimini-content-href="{{ admin_url('profile/password') }}" data-title="更改密码" data-icon="fa fa-file-text">
                                            <i class="fa fa-file-text"></i>
                                            <cite>更改密码</cite>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="layui-col-md12">
                    <div class="layui-card">
                        <div class="layui-card-header"><i class="fa fa-line-chart icon"></i>报表统计</div>
                        <div class="layui-card-body">
                            <div id="echarts-records" style="width: 100%;min-height:500px"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="layui-col-md4">
            <div class="layui-card">
                <div class="layui-card-header"><i class="fa fa-fire icon"></i>系统信息</div>
                <div class="layui-card-body layui-text">
                    <table class="layui-table">
                        <colgroup>
                            <col width="100">
                            <col>
                        </colgroup>
                        <tbody>
                            <tr>
                                <td>系统名称</td>
                                <td>
                                    {{ $system['title'] }}
                                </td>
                            </tr>
                            <tr>
                                <td>当前版本</td>
                                <td>
                                    v{{ $system['version'] }}
                                </td>
                            </tr>
                            <tr>
                                <td>主要特色</td>
                                <td>微服务 / swoole / hyperf</td>
                            </tr>
                            <tr>
                                <td>Github</td>
                                <td>
                                    <a href="https://github.com/deatil/hyperf-server-log" target="_blank">hyperf-server-log</a> 
                                </td>
                            </tr>
                            <tr>
                                <td>服务器</td>
                                <td class="domains">{{ $sys_info['host'] }} [ {{ $sys_info['ip'] }} ]</td>
                            </tr>
                            <tr>
                                <td>服务器信息</td>
                                <td class="server">{{ $sys_info['php_uname'] }}</td>
                            </tr>
                            <tr>
                                <td>PHP 版本</td>
                                <td class="phpv">{{ $sys_info['phpv'] }}</td>
                            </tr>
                            <tr>
                                <td>MySQL 版本</td>
                                <td class="dataBase">{{ $sys_info['mysql_version'] }}</td>
                            </tr>
                            <tr>
                                <td>最大上传限制</td>
                                <td class="maxUpload">{$sys_info.fileupload}</td>
                            </tr>
                            <tr>
                                <td>GD 库</td>
                                <td class="maxUpload">{{ $sys_info['gdinfo'] }}</td>
                            </tr>
                            <tr>
                                <td>服务器时间</td>
                                <td class="time">{{ $sys_info['time'] }}</td>
                            </tr>
                        
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="layui-card">
                <div class="layui-card-header"><i class="fa fa-bullhorn icon icon-tip"></i>系统公告</div>
                <div class="layui-card-body layui-text">
                    <div class="layuimini-notice">
                        <div class="layuimini-notice-title">完善权限检测</div>
                        <div class="layuimini-notice-extra">2021-04-17 18:08</div>
                        <div class="layuimini-notice-content layui-hide">
                            完善权限检测。<br>
                            完成基础功能开发<br>
                        </div>
                    </div>
                    <div class="layuimini-notice">
                        <div class="layuimini-notice-title">完善权限检测</div>
                        <div class="layuimini-notice-extra">2021-04-17 18:08</div>
                        <div class="layuimini-notice-content layui-hide">
                            完善权限检测。<br>
                            完成基础功能开发<br>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script_after')
<script>
layui.use(['layer', 'miniTab','echarts'], function () {
    var $ = layui.jquery,
        layer = layui.layer,
        miniTab = layui.miniTab,
        echarts = layui.echarts;

    miniTab.listen();

    /**
     * 查看公告信息
     **/
    $('body').on('click', '.layuimini-notice', function () {
        var title = $(this).children('.layuimini-notice-title').text(),
            noticeTime = $(this).children('.layuimini-notice-extra').text(),
            content = $(this).children('.layuimini-notice-content').html();
        var html = '<div style="padding:15px 20px; text-align:justify; line-height: 22px;border-bottom:1px solid #e2e2e2;background-color: #2f4056;color: #ffffff">\n' +
            '<div style="text-align: center;margin-bottom: 20px;font-weight: bold;border-bottom:1px solid #718fb5;padding-bottom: 5px"><h4 class="text-danger">' + title + '</h4></div>\n' +
            '<div style="font-size: 12px">' + content + '</div>\n' +
            '</div>\n';
        parent.layer.open({
            type: 1,
            title: '系统公告'+'<span style="float: right;right: 1px;font-size: 12px;color: #b1b3b9;margin-top: 1px">'+noticeTime+'</span>',
            area: '300px;',
            shade: 0.8,
            id: 'layuimini-notice',
            btn: ['查看', '取消'],
            btnAlign: 'c',
            moveType: 1,
            content:html,
            success: function (layero) {
                var btn = layero.find('.layui-layer-btn');
                btn.find('.layui-layer-btn0').attr({
                    href: 'https://github.com/deatil/hyperf-server-log',
                    target: '_blank'
                });
            }
        });
    });

    /**
     * 报表功能
     */
    var echartsRecords = echarts.init(document.getElementById('echarts-records'), 'walden');
    var optionRecords = {
        tooltip: {
            trigger: 'axis'
        },
        legend: {
            data:['邮件营销','联盟广告','视频广告','直接访问','搜索引擎']
        },
        grid: {
            left: '3%',
            right: '4%',
            bottom: '3%',
            containLabel: true
        },
        toolbox: {
            feature: {
                saveAsImage: {}
            }
        },
        xAxis: {
            type: 'category',
            boundaryGap: false,
            data: ['周一','周二','周三','周四','周五','周六','周日']
        },
        yAxis: {
            type: 'value'
        },
        series: [
            {
                name:'邮件营销',
                type:'line',
                data:[120, 132, 101, 134, 90, 230, 210]
            },
            {
                name:'联盟广告',
                type:'line',
                data:[220, 182, 191, 234, 290, 330, 310]
            },
            {
                name:'视频广告',
                type:'line',
                data:[150, 232, 201, 154, 190, 330, 410]
            },
            {
                name:'直接访问',
                type:'line',
                data:[320, 332, 301, 334, 390, 330, 320]
            },
            {
                name:'搜索引擎',
                type:'line',
                data:[820, 932, 901, 934, 1290, 1330, 1320]
            }
        ]
    };
    echartsRecords.setOption(optionRecords);

    // echarts 窗口缩放自适应
    window.onresize = function(){
        echartsRecords.resize();
    }
});
</script>

@endsection

