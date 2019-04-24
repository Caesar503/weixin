<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>商品详情图</title>

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


    <script src="/js/jquery-1.12.4.min.js"></script>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.4.0.js"></script>
    <script>
        wx.config({
            debug: true, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
            appId:"{{$a_config['appId']}}", // 必填，公众号的唯一标识
            timestamp:"{{$a_config['timestamp']}}", // 必填，生成签名的时间戳
            nonceStr: "{{$a_config['nonceStr']}}", // 必填，生成签名的随机串
            signature: "{{$a_config['signature']}}",// 必填，签名
            jsApiList: ['onMenuShareTimeline'] // 必填，需要使用的JS接口列表
        });
        wx.ready(function(){
            wx.onMenuShareTimeline({
                title: "现在的iphone4啊", // 分享标题
                link: "{{$a_config['url']}}", // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                imgUrl: "https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1556079981426&di=741f4c19088db58ea3339840587ba02f&imgtype=0&src=http%3A%2F%2Fwww.quaintfab.com%2FUploads%2Fimage%2F20160112%2F20160112032125_79518.jpg", // 分享图标
                success: function (res) {
                    console.log(res);
                }
            })
        })
    </script>
</div>
</div>
</body>
</html>
