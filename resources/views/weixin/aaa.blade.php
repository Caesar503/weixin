<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
    <table>
        <tr>
            <td>
                商品名称：
            </td>
            <td>
                {{$data['goods_name']}}
            </td>
        </tr>
        <tr>
            <td>
                商品单价：
            </td>
            <td>
                {{$data['goods_price']}}
            </td>
        </tr>
        <tr>
            <td>
                商品简介：
            </td>
            <td>
                国产手机挺好的啊
            </td>
        </tr>
    </table>
    <br>
    <br>
    <br>
    <br>
    <hr>
<div id="qrcode"></div>
</body>
</html>
<script src="/js/qrcode.js"></script>
<script src="/js/jquery-1.12.4.min.js"></script>
<script>
    new QRCode(document.getElementById("qrcode"),"{{$url}}");
</script>