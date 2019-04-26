<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title></title>
</head>
<body>
    <table>
        <tr>
            <td width="20%" align="center"><input type="checkbox"></td>
            <td width="50%" align="center">openid</td>
        </tr>
        @foreach($openid as $k=>$v)
            <tr>
                <td width="20%" align="center"><input type="checkbox"></td>
                <td width="50%" align="center">{{$v}}</td>
            </tr>
            @endforeach
    </table>
</body>
</html>