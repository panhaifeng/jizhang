<script language="javascript">
{literal}
//本行的产品信息进行自动选择
$("[name='ord2proId[]']").bind('onSel',function(event,ret){
    // debugger;
    var tr = $(this).parents('.trRow');
    $('[name="productId[]"]',tr).val(ret.productId);
    $('[name="proCode"]',tr).val(ret.proCode);
    $('[name="proName[]"]',tr).val(ret.proName);
    $('[name="guige[]"]',tr).val(ret.guige);
    $('[name="color[]"]',tr).val(ret.color);
    $('[name="pihao[]"]',tr).val(ret.pihao);
    $('[name="ganghao[]"]',tr).val(ret.ganghao);
    // $('[name="proCode"]',tr).val(ret.productId);
    // $('[name="proCode"]',tr).val(ret.productId);
    // var g = $(this).parents('.input-group');
    //  $('#textBox',g).val(ret.orderCode);
    //  $("[name='ord2proId[]']",g).val(ret.id);
    // alert(1);
});
$("[name='plan2touliaoId[]']").bind('onSel',function(event,ret){
    if (ret.length==undefined) {
        var tr = $(this).parents('.trRow');
        $('[name="planId[]"]',tr).val(ret.planId);
        $('[name="productId[]"]',tr).val(ret.productId);
        $('[name="proCode"]',tr).val(ret.proCode);
        $('[name="proName[]"]',tr).val(ret.proName);
        $('[name="guige[]"]',tr).val(ret.guige);
        $('[name="color[]"]',tr).val(ret.color);
        $('[name="pihao[]"]',tr).val(ret.pihao);
        $('[name="ganghao[]"]',tr).val(ret.ganghao);
        $('[name="cnt[]"]',tr).val(ret.cnt);
    }else{
        var pos=$('[name="plan2touliaoId[]"]').index(this);
        var trs = $('.trRow');
        var trTpl = trs.eq(pos).clone(true);
        //$('input,select',trTpl).val('');
        var parent = trs.eq(pos).parent();
        for(var i=pos;trs[i];i++) {
            trs.eq(i).remove();
        }
        for(var i=0;i<ret.length;i++){
            var trNew = trTpl.clone(true);
            parent.append(trNew);
            $('[name="planId[]"]',trNew).val(ret[i].planId);
            $('[name="productId[]"]',trNew).val(ret[i].productId);
            $('[name="proCode"]',trNew).val(ret[i].proCode);
            $('[name="proName[]"]',trNew).val(ret[i].proName);
            $('[name="guige[]"]',trNew).val(ret[i].guige);
            $('[name="color[]"]',trNew).val(ret[i].color);
            $('[name="pihao[]"]',trNew).val(ret[i].pihao);
            $('[name="ganghao[]"]',trNew).val(ret[i].ganghao);
            $('[name="cnt[]"]',trNew).val(ret[i].cnt);
            $('[name="plan2touliaoId[]"]',trNew).val(ret[i].id);
            $('#textBox',trNew).val(ret[i].planCode);
        }
    }
});
$(function(){
    //设置方向键,方法一包装在jquery 中
    var dir=['ganghao[]','cntJian[]','cntOrg[]','jiagonghuId[]','unit[]','cnt[]','danjia[]','bizhong[]','money[]','memo[]'];
    //*******direct(true)表示聚焦到下一个焦点时候，会选中里面的内容,false或没有参数则表示不选中
    direct({
        cellname:dir,
        selectedorfocus:true,
        optionfocus:true
    });

    $('[name="productId[]"]').bind('onSel',function(event,ret){
        var tr = $(this).parents(".trRow");
        $('[name="proCode"]',tr).val(ret.proCode);
        $('[name="productId[]"]',tr).val(ret.id);
        $('[name="proName[]"]',tr).val(ret.proName);
        $('[name="guige[]"]',tr).val(ret.guige);
        $('[name="menfu[]"]',tr).val(ret.menfu);
        $('[name="kezhong[]"]',tr).val(ret.kezhong);
        //alert(ret.color);
        $('[name="color[]"]',tr).val(ret.color);
    });
});
{/literal}
</script>