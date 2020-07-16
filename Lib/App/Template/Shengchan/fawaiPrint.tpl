
<script language="javascript">
var controller = '{$smarty.get.controller}';
{literal}

 
// $(function(){
//     $('#print').click(function(){
//         var ckPrintId = getIds();
//         if(!ckPrintId){
//             layer.alert('至少选中一个需要导出的');
//             return false;
//         }
//         var urls = "?controller="+controller+"&action=PrintFahuo&ckPrintId="+ckPrintId;
//         window.location.href = urls;
//     });


// });
var chanliangId = '{$chanliangId}';
var chuku2proId = '{$chuku2proId}';
var ord2proId = '{$ord2proId}';
$(function(){

       //全选
    $("#checkedAll").click(function(){
        var t = [];
      
        $('input[name="check[]"]').attr("checked",this.checked);
        $('[name="check[]"]').each(function(){
            if(this.checked==true){
                t.push($(this).attr("data_jiagonghuId"));
            }
        })
        var tt = t.sort();
        var ts = t[0];
        for(var i=0;i<t.length;i++){
             if (ts!=tt[i]){

              alert("不是同一个加工户！");
              $('[name="check[]"]').each(function(){
                  this.checked=false;
              })
              return false;

             }

        }

        
    });

    $('[name="madanSearch"]').click(function(){
        var chanliangId = $(this).attr('data_chanliang');
        var chuku2proId = $(this).attr('data_chuku2proId');
        var ord2proId = $(this).attr('data_ord2proId');
        var url="?controller="+controller+"&action=MadanView&chanliangId="+chanliangId+"&chuku2proId="+chuku2proId+"&ord2proId="+ord2proId;
        //弹出窗口，设置宽度高度
        var width = screen.width;
        var height = screen.height;
        width = width>1000?1000:width;
        height = height>640?640:height;
        var ret = window.open(url,'','dialogWidth:'+width+'px;dialogHeight:'+height+'px;');
    })
})

function fnPrint() {
    
    var ck = $('[name="check[]"]:checked');


    //将所有的选中的id用,连接后传递
    var id=[];
    ck.each(function(i){
        id.push(this.value);
    });
    if(id.length==0){
        alert('至少选中一个需要打印的');
        return false;
    }
    if(id.length>31){
        // alert('打印个数超出限制，请不要选择超过30条记录！');
        // return false;
    }
    var url = "?controller="+controller+"&action=ShezhiZd&ckPrintId="+id;
    window.location.href=url;
    //window.open(url);
}

function getIds(){
    var ckIds = [];
    $('input[name="check[]"]').each(function(){
        if(this.checked)
            ckIds.push($(this).attr('id'));
    });
    return ckIds.join(',');
}

var jiagonghuId='';
function selClient(obj,id){
    if(jiagonghuId==''){
        jiagonghuId=id;
    }
    else{
        if(jiagonghuId==id){
        //同一个客户
            var i=0;
            $('input[name="check[]"]').each(function(){
                if(this.checked==true){
                        i++;
                }
            });
            if(i==0){
                jiagonghuId='';
            }
        }
        else{
            obj.checked=false;
            var i=0;
            $('input[name="check[]"]').each(function(){
                if(this.checked==true){
                        i++;
                }
            });
            if(i==0){
                jiagonghuId='';
                obj.checked=true;
            }
            else{
                alert('不是同一个加工户！');
            }
            //return false;
        }
    }
}

{/literal}
</script>