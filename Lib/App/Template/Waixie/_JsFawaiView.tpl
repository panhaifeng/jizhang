
<div id="divModel4" class="s-isindex-wrap s-model-set-menu menu-top" style="display:none;"> 
    <div><input type="text" class="jiagonghuText2" id="jiagonghuText2"/></div>
</div>
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.json.js"}
<script language="javascript">
{literal}

$(function(){
    $('.button_search').hide();
    $('#jiagonghuIdFc').attr('disabled','disabled');
    $('#jiagonghuId').attr('disabled','disabled');
    $('#gongxuId').attr('disabled','disabled');
    $('#btnMore4').hide();
    $('#btnMore3').hide();
    $('#openMadanInfo2').click(function(){
        var that = this;
        var oc = getfactoryCode();
        if(!oc){
            alert("至少选择一个记录！");
            return false;
        }
        //返回已选择的数据
        parent.layer.callback(oc);
        parent.tb_remove();
    });
    
});

    //获取已选择的缸号信息
    function getfactoryCode(){
        var chanliangId = [];
        var plan2hzlId = '';
        var jiagonghuId = '';
        var jiagonghuIdFc = '';
        var gongxuId = '';
        $('[name="ck"]:checked').each(function(){
            chanliangId.push($(this).val());
            // plan2hzlId = $(this).attr('data');
        });
        jiagonghuId = $("#jiagonghuId").val();
        jiagonghuIdFc = $("#jiagonghuIdFc").val();
        gongxuId = $("#gongxuId").val();
        // var string = "&chanliangId="+chanliangId.join(',')+"&plan2hzlId="+plan2hzlId;
        var string = "&chanliangId="+chanliangId.join(',')+"&jiagonghuId="+jiagonghuId+"&jiagonghuIdFc="+jiagonghuIdFc+"&gongxuId="+gongxuId;
        if (chanliangId.length==0) {
            string='';
        }
        return encodeURI(string);
    }

    //验证入库类型
    function validate_rukuKind(){
        var order2proId = [];
        var rukuKind = [];
        $('[name="ck"]:checked').each(function(){
            order2proId.push($(this).val());
            rukuKind.push($(this).attr('rukuKind'));
        });

        rukuKind=rukuKind.unique();

        if(rukuKind.length>1 ){
            return false;
        }
        return true;
    }

    //去掉重复值
    Array.prototype.unique=function(){
        var o={},newArr=[],i,j;
        for( i=0;i<this.length;i++){
            if(typeof(o[this[i]])=="undefined")
            {
                o[this[i]]="";
            }
        }
        for(j in o){
             newArr.push(j)
        }
        return newArr;
    }
    $(function(){
        $('#ckAll').toggle(function(){
            $('[name="ck"]').attr('checked',true);
        },function(){
            $('[name="ck"]').attr('checked',false);
        });
    });
{/literal}
</script>