<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Document</title>
</head>
<body>
<form action="/weixin/ksqunfa" method="post">
    @csrf
    @foreach($bq as $k => $v)
    <input type="checkbox" value="{{$v['id']}}" name="biaoqian">{{$v['name']}}
    @endforeach
    <textarea name="content" cols="30" rows="10"></textarea>
    <input type="submit" value="发送">
</form>
</body>
</html>