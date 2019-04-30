<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
<form action="">
    @foreach($bq as $k => $v)
    <input type="checkbox" value="{{$v['id']}}">{{$v['name']}}
    @endforeach
</form>
</body>
</html>