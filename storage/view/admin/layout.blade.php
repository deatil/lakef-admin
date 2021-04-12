<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>@yield('title')</title>
        <meta name="renderer" content="webkit">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <base href="{{ admin_assets('/admin/') }}" />
        
        @yield('style_before')
        
        @section('style')
        <link rel="stylesheet" href="lib/layui-v2.5.5/css/layui.css" media="all">
        <link rel="stylesheet" href="lib/font-awesome-4.7.0/css/font-awesome.min.css" media="all">
        <link rel="stylesheet" href="css/public.css" media="all">
        @show
        
        @yield('style_after')
    </head>
    <body>
        <div class="layuimini-container">
            @yield('container')
        </div>
        
        @yield('script_before')
        
        @section('script')
        <script src="lib/layui-v2.5.5/layui.js" charset="utf-8"></script>
        <script src="js/lay-config.js?v=1.0.4" charset="utf-8"></script>
        @show

        @yield('script_after')
    </body>
</html>
