<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ $resHost }}/component/pear/css/pear.css" />
    @yield("css")
</head>
<body class="pear-container">

@yield("content")

<script type="text/javascript" src="{{ $resHost }}/component/layui/layui.js"></script>
@yield("script")
</body>
</html>
