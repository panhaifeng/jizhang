
{literal}
<script language="javascript">
//function setXiajia(id) {
//        var target = document.all ? window.event.srcElement : e.target;
//        if(confirm("您确认要"+target.innerHTML+"吗?")==false)return false;
//        //alert(target.innerHTML+id);
//        var isXiajia=0;
//        if(target.innerHTML=="下架")
//            isXiajia=0;
//        else
//            isXiajia=1;
//        if(id=='') return false;
//
//        var url = '?controller=Sample_Yangpin&action=SetXiajia';
//        var param={id:id,isXiajia:isXiajia};
//        $.getJSON(url,param,function(json){
//            if(json.success===false) {
//                if(target.innerHTML=="下架")target.innerHTML="<font color='green'>上架</font>";
//                else target.innerHTML="<font color='blue'>下架</font>";
//                window.parent.showMsg('操作失败!');
//            }else
//            {
//                if(target.innerHTML=="下架")target.innerHTML="<font color='green'>上架</font>";
//                else target.innerHTML="<font color='blue'>下架</font>";
//                window.parent.showMsg('操作成功!');
//                //window.document.location.reload();
//            }
//        });
//}

$(function(){
    // 绑定下架/上架事件
    $('a.setXiajia').click(function(){
        var $that = $(this),
            id = $that.attr('data-id'),
            isXiajia = 0;

        if($that.text()=="下架"){
            isXiajia=0;
        }else{
            isXiajia=1;
        }

        if(id=='') return false;

        var url = '?controller=Sample_Yangpin&action=SetXiajia';
        var param={id:id,isXiajia:isXiajia};
        $.post(url,param,function(json){
            if(json.success===false) {
                window.parent.showMsg('操作失败!');
            }else {
                if($that.text()=="下架"){
                    $that.html("<font color='green'>上架</font>");
                } else{
                    $that.html("<font color='blue'>下架</font>");
                }
                window.parent.showMsg('操作成功!');
            }
        }, 'json');

    });

})
</script>
{/literal}
