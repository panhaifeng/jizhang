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

     $('#btnAdd','[name="table_gongxu"]').click(function(){
      var tbl=$(this).parents('.trRowMore').attr('name');
      fnAdd('[name="table_gongxu"]');
    });

     //删除工序
    $('[id="btnRemove"]','[name="table_gongxu"]').click(function(){
         var url="index.php?controller=jichu_Chanpin&action=Remove2GongxuAjax";
         var trs = $('[name="xuhao[]"]');
         if(trs.length<=1) {
              alert('至少保存一个明细');
              return;
         }
          var tr = $(this).parent().parent();
            var id = $('[name="gxId[]"]',tr).val();
            if(!id) {
              tr.remove();
              return;
            }
        
            if(!confirm('此删除不可恢复，你确认吗?')) return;
            var param={'id':id};
            $.post(url,param,function(json){
              if(!json.success) {
                alert('出错');
                return;
              }
              tr.remove();
            },'json');
            return;
    });

    // 类型改变引发物料编号改变
    $('#kind').change(function(){
        var kind = $(this).val();
        var url="?controller=jichu_Chanpin&action=getNewCodeByAjax";
        var param = {kind:kind};
        $.getJSON(url,param,function(json){
            $('#proCode').val(json.newCode);
        });
    });
    
    // 删除成分
    $('[id="btnRemove"]').click(function(){
        var id = setInterval(carculateCF,500);
    });

    //纱支比例设置后，自动设置布成分 组成相同的部分，成分相加
    $('[name="productId[]"],[name="viewPer[]"]').on('change',function(){
        carculateCF();
    });

    
    function carculateCF(){
        var chengfenPerArr = new Array();
        var componentInpArr = new Array();
        var bili;
        var chengfenPer;
        var componentInp;
        var val0;
        var com0;
        var val1;
        var com1;
        var val2;
        var com2;
        var chengfen = '';

        $('input[name="viewPer[]"]').each(function(i){
            if($(this).val()!=''){
                bili = parseFloat($(this).val())/100;//比例
                if($('[name="proChengFenPer[]"]').eq(i).val() ==''){
                    return;
                }
                chengfenPer = $('[name="proChengFenPer[]"]').eq(i).val();//成分
                componentInp = $('[name="proComponent[]"]').eq(i).val();//成分单位
                val0 = chengfenPer.split(",");

                for(var x=0;x<val0.length;x++){
                    val0.splice(x,1,(parseFloat(val0[x]))*bili);
                }

                com0 = componentInp.split(",");

                chengfenPerArr.push(val0);
                componentInpArr.push(com0);
            }
        });

        val0 = chengfenPerArr[0];  
        com0 = componentInpArr[0];

        if(componentInpArr.length>1){

            for(var m=1;m<componentInpArr.length;m++){ 

                val1 = chengfenPerArr[m];  
                com1 = componentInpArr[m];

                val2 = val1;
                com2 = com1;

                var comdition = 0;
                for(var i=0;i<com0.length;i++){
                    for(var j=0;j<com1.length;j++){
                        if(com0[i]==com1[j]){
                            val2.splice(j,1,(parseFloat(val0[i]) + parseFloat(val1[j])));
                            comdition = 1;
                            break;
                        }
                    }

                    if(comdition == 0 ){
                        val2.push(val0[i]);
                        com2.push(com0[i]);
                    }
                    comdition = 0;
                }
                 
                //将数组2中的值存到数组0中，并清空数组2中的数据
                //将每次处理好的一对值重新赋值给val0，并交换顺序
                val0.splice(0,val0.length);
                com0.splice(0,com0.length);

                for(var n=0;n<val2.length;n++){
                    val0.push(val2[n]);
                    com0.push(com2[n]);
                }     
                val2.splice(0,val2.length);
                com2.splice(0,com2.length);
            }
        }

        if(typeof(val0)!='undefined'){
            var newCF = new Array();
            for(var k=0;k<val0.length;k++){
                newCF[val0[k]+com0[k]] = com0[k];
                // chengfen = chengfen+getFloat(val0[k])+'%'+com0[k]+'+';
            }
            //冒泡排序
            var len = val0.length;
            for (var i = 0; i < len; i++) {
                for (var j = 0; j < len - 1 - i; j++) {
                    if (val0[j] < val0[j+1]) {        //相邻元素两两对比
                        var temp = val0[j+1];        //元素交换
                        val0[j+1] = val0[j];
                        val0[j] = temp;

                        var tempC = com0[j+1]; //同步元素交换
                        com0[j+1] = com0[j];
                        com0[j] = tempC;
                    }
                }
            }
            //重新构建成份 拼接字符串类似于20T+30C+35SP+20S....
            for(var k=0;k<val0.length;k++){
                chengfen = chengfen+getFloat(val0[k])+'%'+newCF[val0[k]+com0[k]]+'+';
            }

            $('#chengFen').val(chengfen.substr(0,(chengfen.length-1)));
        }
        
    }
    
    //获取小数点后的位数
    function getFloat(str){
        str = str + '';
        var s = str.indexOf('.');
        if(s!=-1){
            str = parseFloat(str).toFixed(2);
        }
        return str;
    }
        

    //纱支选择后，自动设置布的规格
    $('[name="proNameson[]"]').on('change',function(){
        var guigeArr = [];
        var strGuige = '';
        var length = $('input[name="proNameson[]"]').length;
        $('input[name="proNameson[]"]').each(function(i){
            if($(this).val()!='' && guigeArr.indexOf($(this).val())<0){
                if(i!=0){
                    strGuige += '+';
                }
                guigeArr.push($(this).val());
                strGuige += $(this).val();
            }
        });
        $('#guige').val(strGuige).change();
    });
    // 规格变动后，检测是否已存在
    $('#guige').change(function(){
        var noRepeat = repeatGuigeCheck($(this).val(), this);
        $(this).nextAll('label.error').remove();
        if(noRepeat){
            $(this).after('<label for="guige" generated="true" class="error"><i style="color:blue">不存在</i></label>');
        }else{
            $(this).after('<label for="guige" generated="true" class="error"><i style="color:#F4A460">已存在</i></label>');
        }
    });


    $('[name="productId[]"]').bind('onSel',function(event,ret){
        $('[name="proNameson[]"]',$(this).parents('tr')).val(ret.proName).change();
        $('[name="proGuige[]"]',$(this).parents('tr')).val(ret.guige).change();
        $('[name="proChengFenPer[]"]',$(this).parents('tr')).val(ret.chengfenPer);
        $('[name="proComponent[]"]',$(this).parents('tr')).val(ret.component);
        carculateCF();
    });
});



//修改布匹图片时，删除原有图片，2015-09-11，by liuxin
function setBox(){
    var imagevalue=document.getElementById('imageFile').value;
    var cbdelImage=document.getElementById('isDelImage');
    if(imagevalue!='' &&　cbdelImage!=null){
        document.getElementById('isDelImage').checked=true;
    }else{
        document.getElementById('isDelImage').checked=false;
    }
}
/**
* 添加5行方法，适应于多个table
*/
function fnAdd(tblId) {
var rows = $('.trRow',tblId);
var len = rows.length;
var xuhao=$('[name="xuhao[]"]').eq(-1).val();
for(var i=0;i<5;i++) {
  var nt = rows.eq(len-1).clone(true);
  $('input,select',nt).val('');
  $('input[type="radio"],input[type="checkbox"]',nt).attr('checked',false);
  $('[name="xuhao[]"]',nt).val(parseInt(xuhao)+1+i);
   //加载新增后运行的代码
  if(typeof(beforeAdd) == 'function'){
    beforeAdd(nt,tblId);
  }
  //拼接
  $(tblId).append(nt);
}

return;
} 
{/literal}
</script>