{webcontrol type='LoadJsCss' src="Resource/Script/jquery.json.js"}
<script language="javascript">
{literal}
$(function(){
	$('[name="order2proId"]').bind('onSel',function(event,ret){
        $("#order2proId").val(ret.order2proId);
        $("#orderId").val(ret.orderId);
        $("#overflow").val(ret.overflow);
        $("#cntYaohuo").val(ret.cntYaohuo+ret.unit);
        $('#proId').siblings('input').val(ret.proCode);
        $('#proId').val(ret.productId);
        $('#proName').val(ret.proName);
        $('#guige').val(ret.guige);
        $('#color').val(ret.color);
        $('#menfu').val(ret.menfu);
        $('#kezhong').val(ret.kezhong);
        $('#planMemo').val(ret.memoTrade);
        $('#chengfen').val(ret.chengFen);
        $('#xianchang').val(ret.xiadanXianchang);
        //if(ret.unit=='公斤') $('#zhcntKg').val(ret.cntYaohuo);
        //else $('#zhcntKg').val('');
        onSelPlan(ret);

    });

    $('[name="proId"]').bind('onSel',function(event,ret){
        var ret={'productId':ret.id};
        onSelPlan(ret);
    });
    $('[name="productId[]"]').bind('onSel',function(event,ret){
    });


    $('[name="jiagonghuId[]"]').bind('onSel',function(event,ret){
        var id=[];
        var jia=[];
        for(var i=0;i<ret.length;i++){
            id.push(ret[i].id);
            jia.push(ret[i].compName);
        }
        $(this).val(id.join(','));
        $(this).siblings('input').val(jia.join(','));
    });

//    $('[name="songsha[]"]').bind('onSel',function(event,ret){
//        $(this).val(ret);
//    });

    //送纱
    $('[name="btnSongsha"]').live('click',function(){
		//url地址
		var url="?controller=Shengchan_Plan&action=Songsha";
		var trRow = $(this).parents(".trRow");
		//弹出窗口，设置宽度高度
		var width = screen.width;
		var height = screen.height;
		width = width>1300?1300:width;
		height = height>640?640:height;
		//获取码单选择信息
		var songsha = $('[name="songsha[]"]',trRow).val();//存放码单id 与 number
		var ret = window.showModalDialog(url,{data:songsha},'dialogWidth:'+width+'px;dialogHeight:'+height+'px;');

	    if(!ret) ret=window.returnValue;
	    if(!ret) return;
		$('[name="songsha[]"]',trRow).val(ret);
	});
    //折合公斤
    // $('#zhcntKg').live('change',function(){
    //     var len=$('[name="bilv[]"]').length;
    //     var zhcntKg=$(this).val();
    //     for(var i=0;i<len;i++){
    //         var bl=$('[name="bilv[]"]').eq(i).val();
    //         //计划投料的计算公式从105% 改成103%
    //         if(bl) $('[name="cnt[]"]').eq(i).val((zhcntKg*bl*1.03/100).toFixed(2));
    //     }
    // });
     //送纱
    $('[name="btnkucun"]').live('click',function(){
        var trRow = $(this).parents(".trRow");

        onSelect = function(ret) {
            $('[name="songsha[]"]',trRow).val(ret);
            $('[name="pihao[]"]',trRow).val(ret.pihao);
            $('[name="ganghao[]"]',trRow).val(ret.ganghao);
            return;
        }
        var productId= $('[name="productId[]"]',trRow).val();
        //url地址
        var url="?controller=Shengchan_Plan&action=Kucun&productId="+productId;
        var ret = window.open(url,'window','height=300,width=800,top=250,left=250');
	});

     //复制后整
    $('#btnAdd','[name="table_houzheng"]').click(function(){
      var tbl=$(this).parents('.trRowMore').attr('name');
      fnAdd('[name="table_houzheng"]');
    });
    //删除后整
	$('[id="btnRemove"]','[name="table_houzheng"]').click(function(){
		 var url="index.php?controller=Shengchan_Plan&action=Remove2HouzhengAjax";
		 var trs = $('[name="xuhao[]"]');
		 if(trs.length<=1) {
		      alert('至少保存一个明细');
		      return;
   		 }
   		  var tr = $(this).parent().parent();
		    var id = $('[name="houzhengId[]"]',tr).val();
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
});
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
function onSelPlan(ret){
     //ajax取得产品明细
        var url='?Controller=Jichu_Chanpin&action=GetShaInfo';
        var param={'proId':ret.productId};
        // dump(param);
        $.getJSON(url,param,function(json){
            if(!json.success) {
                alert('未发现产品纱比例信息!');
                return;
            }
            var pros = json.Sha;
            if(pros.length==0) {
                alert('未发现产品纱比例信息!');
                return;
            }
            var trs = $('.trRow','#table_main');

                                            //复制行,临时写在这里，后期需要用sea.js封装
    $('#btnAdd',tblIds[i]).click(function(){

      var tbl=$(this).parents('.trRowMore').attr('id');

      fnAdd('#'+tbl);
    });
            var len = trs.length;
            var trTpl = trs.eq(len-1).clone(true);
            var parent = trs.eq(0).parent();
            $('input,select',trTpl).val('');
            for(var i=0;trs[i];i++) {
                var id = $('[name="id[]"]',trs[i]);
                if(id.val()!='') continue;
                trs.eq(i).remove();
            }
            //将选中订单的明细形成新行插入
            for(var i=0;pros[i];i++) {
                var jhlv=0;//计划投料数
                jhlv=(ret.cntYaohuo*pros[i].viewPer*1.03/100).toFixed(1);
                var newTr = trTpl.clone(true);
                $('[name="productId[]"]',newTr).val(pros[i].productId);
                $('[name="productId[]"]',newTr).siblings('input').val(pros[i].proName);
                $('[name="bilv[]"]',newTr).val(pros[i].viewPer);
                $('[name="cnt[]"]',newTr).val(jhlv);
                parent.append(newTr);
            }
        });
}


{/literal}
</script>