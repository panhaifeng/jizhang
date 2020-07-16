{webcontrol type='LoadJsCss' src="Resource/Script/jquery.json.js"}
<script language="javascript">
{literal}
var codeArray = new Array();
$(function(){
   $("#checkAll").click(function(){
        var checked = $(this).attr('checked');
        setChecked(checked);
   })
});

//设置全选反选
function setChecked(checked){
    $('[name="ck[]"]').each(function(){
       $(this).attr('checked',checked);
    });
}

$(function(){
        $('#openMadanInfo').click(function(){
            var that = this;
            var oc = getfactoryCode();
            /*alert(oc);*/
            if(!oc){
                alert('至少选择条码一个入库');
                return false;
            }
            //判断是否勾选相同的验布条码
            if (oc=='repeat') {
                return false;
            }
            //获取data属性中id 信息  
            var ids = getfactoryCode2();
            //判断验布类型
            if(!validate_rukuKind()){
                alert('验布类型不一样，可能存在成品的卷验记录');
                return false;
            }

            $(that).attr('href',that.href+'&factoryCode='+oc+'&factoryId='+ids+'&TB_iframe=1');
        });
        
    });
    function isRepeat(arr){
        var hash = {};
        for(var i in arr) {
            if(hash[arr[i]])
            return true;
            hash[arr[i]] = true;
        }
        return false;
    }
    //获取已选择的坯检信息
    function getfactoryCode(){
        var ghs = [];
        var cnt=0;
        $('[name="ck[]"]').each(function(){
            if(this.checked){
            var pos = $('[name="ck[]"]').index($(this));
            var id = $(this).val();
            /*alert(id);die;*/
              
                    ghs.push(id);
                    cnt++;
            
            }
           /* ghs.push($(this).val());*/
        });
        if(isRepeat(ghs)){
            alert('勾选的条码中存在重复条码，请检查核实！');
            return 'repeat';
        }
        return encodeURI(ghs.join(','));
    }
    // 获取已选择（排除重复条码）的坯检id信息
    function getfactoryCode2(){
        var ids = [];
        $('[name="ck[]"]').each(function(){
            if(this.checked){
               ids.push($(this).attr('data'));
            }
        })
        /*$('[name="ck[]"]:checked').each(function(){
            ids.push($(this).attr('data'));
        });*/
        return encodeURI(ids.join(','));
    }
    //验证入库类型
    function validate_rukuKind(){
        var order2proId = [];
        var rukuKind = [];
        $('[name="ck[]"]:checked').each(function(){
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
{/literal}
</script>