<script language="javascript">
{literal}
$(function(){
    $('[name="baojia[]"]').change(function(){
        var baojia=$(this).val();
        var tr=$(this).parents(".trRow");
        var id=$('[name="id[]"]',tr).val();
        window.location.href="?controller=Jichu_Chanpin&action=SetBaojia&baojia="+baojia+"&id="+id;
    });

    $('[name="price[]"]').change(function(){
        var price=$(this).val();
        var tr=$(this).parents(".trRow");
        var id=$('[name="priceId[]"]',tr).val();
        var url = "?controller=Jichu_Chanpin&action=SetPrice";
        var params = {};
        params.id = id;
        params.price = price;

        $.post(url, params, function(data){
            if(true == data.success){
                // 反馈信息，设置成功
                window.parent.showMsg("单价设置成功")
            }else{
                // 反馈没有设置成功的信息
                window.parent.showMsg('单价设置失败', false);
                $(this).val('').focus();
            }
        }, 'json');
    });

});
{/literal}
</script>