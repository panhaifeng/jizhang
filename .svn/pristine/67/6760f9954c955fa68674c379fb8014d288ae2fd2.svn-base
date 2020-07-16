<!DOCTYPE html>
<html>
<head>
    <title>响应类api测试</title>
    <script language="javascript" src="Resource/Script/jquery.1.9.1.js"></script>
    <style type="text/css">
    .divRes {border: 1px solid #000; margin-bottom: 10px; width: 100%;height:300px;overflow:auto;}
    </style>
</head>
<body>
<form action='' method="post" id='form1'>
    <?php foreach($obj->_params as $k=>&$v) {
        echo "<p><input type='text' name='params[{$k}]' value='' placeholder='" . (is_array($v)?$v['name']:$v) . "' /></p>";
    }
    echo "<input type='hidden' name='method' id='method' value='{$obj->_method}' />";
    ?>
    token:<input type='text' id='token' name='params[token]' value='<?php echo $obj->_json['token']?>' readonly />必须和响应方的token一致
    <p>
    <input type='button' value=' 直接post ' id='btnOk'/> 
    <input type='button' value=' curl模式提交 ' id='btnOk1'/> 
    <input type='button' value=' curl调试模式提交 ' id='btnOk2'/> 
    <input type='button' value=' 返 回 ' id='btnback' onclick="window.history.go(-1)" />
    <input type='hidden' id='type' name='type' value='<?php echo $_GET['type']?>'/>
    <input type='hidden' name='isDebug' id='isDebug' value='0'/>
    <p>
    
</form>
<div class='divRes'></div>
<script type="text/javascript">
    $(function(){
        $('#btnOk') .click(function(){
            $('.divRes').html('等待response...');
            //得到md5加密的token
            var url = 'apitest.php';
            var params = {
                'type':'getsign',
                // 'token':$('#token').val()
            };
            $.post(url,params,function(_token) {                
                //访问api接口
                var p=$('#form1').serialize()+"&params%5Btoken%5D="+_token;
                $.post('api.php',p,function(json) {
                    //将unicode进行解码
                    var json = eval("'"+json+"'");
                    $('.divRes').html(json);
                })
            });            
        });
        $('#btnOk1') .click(function(){
            $('#isDebug').val(0);
            $('.divRes').html('等待response...');
            var url = 'apitest.php';
            var params=$('#form1').serialize();
            $.post(url,params,function(json) {
                $('.divRes').html(json);
            })
        });
        $('#btnOk2') .click(function(){
            $('#isDebug').val(1);
            $('.divRes').html('等待response...');
            var url = 'apitest.php';
            var params=$('#form1').serialize();
            $.post(url,params,function(json) {
                $('.divRes').html(json);
            })
        });
    });
</script>
</body>
</html>