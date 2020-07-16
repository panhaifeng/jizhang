{webcontrol type='LoadJsCss' src="Resource/Script/jquery.json.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/layer/layer.js"}
<script language="javascript">
var controller = '{$smarty.get.controller}';
var kuwei='';
{literal}
$(function(){
    $('[name="orderId"]').bind('onSel',function(event,ret){
      $('[name="compName"]').val(ret.compName);
      $('[name="cntYaohuo"]').val(ret.cntYaohuo);
      $('[name="clientId"]').val(ret.clientId);
      $('[name="ord2proId"]').val(ret.order2proId);
      //$('[name="orderId"]').val(ret.orderId);
    });

    $('[name="btnRemove"]').click(function(){
        var cntKg = 0;
        var length = 0;
        var juanshu = $('#juanshu').val();
        $('[name="cnt[]"]').each(function(){
            cntKg += parseFloat($(this).val());
        });
        $('#cntKg').val(cntKg);

        $('[name="cntL[]"]').each(function(){
            length += parseFloat($(this).val());
        });
        $('#mishu').val(length);
        $('#juanshu').val($('[name="cntJuan[]"]').length);
    });

    /**
    * 选择按钮单机事件
    * 打开选择界面
    */
    // $('[name="btnMadan"]').click(function(){
    //     var tr = $(this).parents('.trRow');
    //     var ganghao=$('[name="ganghao[]"]',tr).val();
    //     var productId=$('[name="productId[]"]',tr).val();
    //     var ord2proId=$('[name="ord2proId[]"]',tr).val();
    //     var index=$('[name="btnMadan"]').index(this);
    //     var url="?controller="+controller+"&action=ViewChoose&index="+index+'&productId='+productId+'&ord2proId='+ord2proId+'&ganghao='+ganghao;
    //     choose_layer = $.layer({
    //           type: 2,
    //           shade: [1],
    //           fix: false, 
    //           title: '选择',
    //           maxmin: true,
    //           iframe: {src : url},
    //           // border:false,
    //           area: ['1024px' , '640px'],
    //           close: function(index){//关闭时触发
                  
    //           },
    //           //回调函数定义
    //           callback:function(index,ret) {
    //             var cnt=parseFloat(ret.cnt).toFixed(2);
    //             var cntL=parseFloat(ret.cntL).toFixed(2);
    //             $('[name="Madan[]"]',tr).val(ret.cId);
    //             $('[name="cnt[]"]',tr).val(cnt);
    //             $('[name="cntL[]"]',tr).val(cntL);
    //             $('[name="cntJuan[]"]',tr).val(ret.cntJuan);
    //         }
    //     });
    // })
    $('[name="btnMadan"]').live('click',function(){
        var tr = $(this).parents('.trRow');
        var ganghao=$('[name="ganghao[]"]',tr).val();
        var productId=$('[name="productId[]"]',tr).val();
        var ord2proId=$('[name="ord2proId[]"]',tr).val();
        var index=$('[name="btnMadan"]').index(this);
        //url地址
        var url="?controller="+controller+"&action=ViewChoose2&index="+index+'&productId='+productId+'&ord2proId='+ord2proId+'&ganghao='+ganghao;
        var trRow = $(this).parents(".trRow");
        // var ruku2proId = $('[name="id[]"]',trRow).val();
        // url+="&ruku2proId="+ruku2proId;
        //弹出窗口，设置宽度高度
        var width = screen.width;
        var height = screen.height;
        width = width>1300?1300:width;
        height = height>640?640:height;
        //获取码单选择信息
        // var madan = $('[name="Madan[]"]',trRow).val();
        // var ret = window.showModalDialog(url,{data:$.toJSON(madan)},'dialogWidth:'+width+'px;dialogHeight:'+height+'px;');
        //取得隐藏字段的值，保存的是码单信息
        var madan = $('[name="Madan[]"]',trRow).val();
        if(madan=='')madan='""';//第一次进入的时候，隐藏字段不能为空
        var ret = window.showModalDialog(url,{data:madan},'dialogWidth:'+width+'px;dialogHeight:'+height+'px;');
          if(!ret){
            //window.returnValue取得返回值
            ret=window.returnValue;
            //第二次如果没有返回值的话，会取上一次的值，因此设置window.returnValue=null
            window.returnValue=null;
          }
          if(!ret) return;
        if(ret.ok!=1)return false;
        $('[name="cntJian[]"]',trRow).val(ret.cntJian);
        $('[name="cntJuan[]"]',trRow).val(ret.cntJuan);
        $('[name="cnt[]"]',trRow).val(ret.cnt);
        $('[name="cntM[]"]',trRow).val(ret.cntM);
        $('[name="cntL[]"]',trRow).val(ret.cntM);
        $('[name="cntMadan[]"]',trRow).val(ret.cntMadan);
        $('[name="Madan[]"]',trRow).val(ret.jsonStr);
    });
    $('[name="supplierId"]').bind('onSel',function(event,ret){
      //alert(111);
    });
    //计划开始
    $('#setStartRollno').click(function(){
        var url = "?controller="+controller+"&action=Add";
        $('#setStartRollno').attr('href',url); 
    });

});

function tb_remove(){
    layer.close(choose_layer); //执行关闭
}

{/literal}
</script>