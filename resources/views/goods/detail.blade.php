<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    <!-- Styles -->
    <style>
html, body {
    background-color: #fff;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
        }

        .full-height {
    height: 100vh;
        }

        .flex-center {
    align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
    position: relative;
}

        .top-right {
    position: absolute;
    right: 10px;
            top: 18px;
        }

        .content {
    text-align: center;
        }

        .title {
    font-size: 84px;
        }

        .links > a {
    color: #636b6f;
    padding: 0 25px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
    margin-bottom: 30px;
        }
    </style>
</head>
<body>
<div class="flex-center position-ref full-height">
@if (Route::has('login'))
    <div class="top-right links">
        @auth
        <a href="{{ url('/home') }}">Home</a>
        @else
            <a href="{{ route('login') }}">Login</a>

            @if (Route::has('register'))
                <a href="{{ route('register') }}">Register</a>
            @endif
            @endauth
    </div>
@endif

<div class="content">
    <div class="title m-b-md">

    </div>
    <ul>
        <li>商品图：<img src="https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1556079981426&di=741f4c19088db58ea3339840587ba02f&imgtype=0&src=http%3A%2F%2Fwww.quaintfab.com%2FUploads%2Fimage%2F20160112%2F20160112032125_79518.jpg"></li>
        <li>商品名称 ：{{$data['goods_name']}}</li>
        <li>商品单价 ： {{$data['goods_price']}}</li>
        <li><button id="share">点击分享</button></li>
    </ul>
</div>
</div>
</body>
</html>
