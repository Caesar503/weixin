<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title></title>
</head>
<body>
    <table border="1">
        <tr>
            <td width="20%" align="center"><input type="checkbox" id="allbox"></td>
            <td width="50%" align="center">openid</td>
        </tr>
        @foreach($openid as $k=>$v)
            <tr>
                <td width="20%" align="center"><input type="checkbox" class="box" openid="{{$v}}"></td>
                <td width="50%" align="center">{{$v}}</td>
            </tr>
        @endforeach
    </table>
    <br>
    <br>

    <form action="" mothod="post">
        @csrf
        <table width="400px">
            <tr>
                <td colspan="2">
                    <h4>消息群发</h4>
                </td>
            </tr>
            <tr>
                <td align="center" width="50%">群发消息选择类型:</td>
                <td align="center" width="50%">
                    <select id="lx">
                        <option value="">--请选择--</option>
                        <option value="1">文本</option>
                        <option value="2">图片/语音/视频</option>
                </td>
            </tr>
            <tr height="40px">
                <td colspan="2" align="right"></td>
            </tr>
            <tr>
                <td colspan="2" align="center"><input type="button" value="点击群发" id="btn"></td>
            </tr>
        </table>
    </form>
</body>
</html>
<script src="/js/jquery-1.12.4.min.js"></script>
<script>
    $(function(){
        //全选
        $('#allbox').click(function(){
            var stat = $(this).prop('checked');
            var box = $('.box');
            box.each(function(index){
                 $(this).prop('checked',stat);
            })
        })
        //内容更新事件
        $('#lx').change(function(){
         var lx = $(this).val();
            if(lx==1){
               $(this).parents('tr').next('tr').find('td').html("<textarea id='content' type='text'></textarea>");
            }else{
                $(this).parents('tr').next('tr').find('td').html("<select id='content'>@foreach($sc_data as $k=>$v)
               <option value='{{$v['media_id']}}' type='{{$v['type']}}' class='content'>{{$v['type'].'-'.$v['id']}}</option>@endforeach
           </select>");
            }
        })
        //点击群发
        $('#btn').click(function(){
            //获取群发用户
            var box = $('.box');
            var openid = '';
            box.each(function(index){
                if($(this).prop('checked')==true){
                    openid += $(this).attr('openid')+',';
                }
            })
            if(openid==''){
                alert('群发用户不能为空');
                return false;
            }




            //判断用户有没有选择群发内容
            if($('#lx').val()==''){
                alert('请选择群发类型');
                return false;
            }


            //获取群发内容
            var send_content = $('#content').val();


            //获取群发类型
            if($('#lx').val()==1){
                var send_type = $('#content').attr('type');
            }else{
                var aa_content = $('.content');
                var send_type = '';
                aa_content.each(function(index){
                    if($(this).prop('selected')==true){
                        send_type = $(this).attr('type');
                    }
                })
            }



            if(send_content==''){
                alert('群发消息不能为空');
                return false;
            }
//            console.log(openid);
//            console.log(send_content);
//            console.log(send_type);


            //传送数据
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post(
                "/admin/senddo",
                {openid:openid,send_content:send_content,send_type:send_type},
                    function(res){
//                        console.log(res);
                        if(res == '1'){
                            alert('发送成功');
                        }else{
                            alert('发送失败');
                        }
                    }
            );
        })
    })
</script>