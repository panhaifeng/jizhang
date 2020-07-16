<script type="text/javascript">
{literal}
$(function(){
    var repeatGuigeCheck =  function(value, element){
        var url='?controller=Jichu_Chanpin&action=isGuigeRepeat';
        var param = {field:element.name,fieldValue:value,id:$('#proId').val()};

        var repeat=true;
        //通过ajax获取值是否已经存在
        $.ajax({
            type: "GET",
            url: url,
            data: param,
            success: function(json){
                repeat = json.success;
            },
            dataType: 'json',
            async: false//同步操作
        });
        return repeat;
    };

    //成分占比设置后，自动设置纱成分
    $('[name="chengfenPer[]"],[name="component[]"]').on('change',function(){
        var chengfenArr = [];
        var strChengfen = '';
        var chengfenInp = $('select[name="component[]"]'); 
        $('input[name="chengfenPer[]"]').each(function(i){
            if($(this).val()!=''){
                var bili = parseFloat($(this).val());//比例
                var chengfen = chengfenInp.eq(i).find("option:selected").text();//成分
                var cnt = 0;// 计算不存在的次数
                if(chengfen=='请选择'){
                  chengfen=null;
                }else{
                  for (var i = 0; i < chengfenArr.length; i++) {
                        if(chengfen==chengfenArr[i]['chengfen']){//存在则累加
                            bili += parseFloat(chengfenArr[i]['bili']);
                            bili = bili.toFixed(1);
                            chengfenArr[i]['bili'] = bili;
                        }else{
                            cnt++;
                        }
                    }
                }
                // 不存在，则新增
                if(cnt == chengfenArr.length){
                    var temp = {chengfen:chengfen,bili:bili};
                    chengfenArr.push(temp);
                }
            }
        });
        // 遍历成分数组，拼接显示在成分框中
        for (var i in chengfenArr) {
            if(i > 0){
                strChengfen += '+';
            }
            strChengfen += chengfenArr[i]['bili']+'%'+chengfenArr[i]['chengfen'];
        };
        $('#chengFen').val(strChengfen);
    });
   

});
$('[name="productId[]"]').bind('onSel',function(event,ret){
    $('[name="proNameson[]"]',$(this).parents('tr')).val(ret.proName);
    $('[name="proGuige[]"]',$(this).parents('tr')).val(ret.guige).change();
});
{/literal}
</script>