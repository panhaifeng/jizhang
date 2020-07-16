
<div id="divModel4" class="s-isindex-wrap s-model-set-menu menu-top" style="display:none;"> 
    <div><input type="text" class="jiagonghuText2" id="jiagonghuText2"/></div>
</div>
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.json.js"}
<script language="javascript">
{literal}

$(function(){
        $('#openMadanInfo').click(function(){
            var that = this;
            // if (!$("#jiagonghuId").val()) {
            //     alert('加工户未选择,请选择！');
            //     return false;
            // }
            // if (!$("#gongxuId").val()) {
            //     alert('工序未选择,请选择!');
            //     return false;
            // }
            var oc = getfactoryCode();
            if(!oc){
                alert('至少选择一个记录！');
                return false;
            }
            // if(!validate_rukuKind()){
            //     alert('类型不一样,可能存在发外加工户和工序不一致,请核实!');
            //     return false;
            // }
            $(that).attr('href',that.href+oc+'&TB_iframe=1');
        });

        $('#openMadanInfo2').click(function(){
            var that = this;
            var oc = getfactoryCode();
            if(!oc){
                // alert('至少选择一个记录！');
                layer.alert("至少选择一个记录！");
                return false;
            }
            //返回已选择的数据
            // var obj = {'cId':che_data.cId,'cnt':che_data.cnt,'cntJuan':che_data.cntJuan,'cntL':che_data.cntL};
            parent.layer.callback(oc);
            parent.tb_remove();
        });
        //加工户自动完成
        $('#jiagonghuText2').autocomplete('?controller=jichu_jiagonghu&action=GetJsonByKey', {
                minChars:1,
                remoteDataType:'json',
                useCache:false,
                sortResults:false,
                onItemSelect:function(v){
                    $('#jiagonghuIdFc').val(v.data[0].id);
                    $('#divModel4').hide();
                    $('#btnMore4').removeClass('active');
                }
        });
        //切换divModel3的可见
        $('#btnMore4').click(function(){
            $('#jiagonghuText2').val('');
            if($('#divModel4').is(':hidden')){
                var showTarget = $(this), target= $('#jiagonghuIdFc');
                var showL = target.offset().left
                   ,showT = target.offset().top
                   ,showW = target.width()
                   ,showH = target.height();
                $('#jiagonghuText2').css("left", showL).css("top", showT).css("width",showW).css("height", showH+1);
                $('#divModel4').css({'left':showL,'top':showT, 'width':(showW+2), 'height':(showH+2)}).show();
                $(this).addClass('active');
                $('#jiagonghuText2').focus();
            } else {
                $('#divModel4').hide();
                $(this).removeClass('active');
            }
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
        jiagonghuId = typeof($("#jiagonghuId").val())=='undefined'?'':$("#jiagonghuId").val();
        jiagonghuIdFc = typeof($("#jiagonghuIdFc").val())=='undefined'?'':$("#jiagonghuIdFc").val();
        gongxuId = typeof($("#gongxuId").val())=='undefined'?'':$("#gongxuId").val();
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
            rukuKind.push($(this).attr('check_kind'));
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