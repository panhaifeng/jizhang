<script language="javascript">
{literal}
$(function(){
	 $("#checkedAll").click(function(){
     		$('input[name="check[]"]').attr("checked",this.checked);
	});
	$('#save').click(function(){
		var arr=[];
		$('[name="check[]"]').each(function(){
			if(this.checked==true){
				var tr=$(this).parents('tr');
				var danjia=$('[name="danjia[]"]',tr).val();
				var id=$('[name="id[]"]',tr).val();
				var kind=$('[name="kind[]"]',tr).val();
				var guozhangDate=$('[name="guozhangDate[]"]',tr).val();
				if(danjia==''){
					alert('单价不能为空');
					return false;
				}else{
					var temp={id:id,danjia:danjia,kind:kind,guozhangDate:guozhangDate};
					arr.push(temp);
				}
			}
		});

		if(arr.length==0){
			alert('请至少选择一条过账记录!');
			return false;
		}
		var str = JSON.stringify(arr);
		window.location.href="?controller=Shengchan_Waixie_JiaGongFei&action=SetDanjia&arr="+str;
	});
	//批量保存
	$('#setDate').click(function(){

		var chks = $('[name="check[]"]:checked');
		if(chks.length==0) {
			alert('请选择过账记录!');
			return;
		}
		//显示窗口
		var url='?controller=Shengchan_Waixie_JiaGongFei&action=setDate';
		var ret = window.showModalDialog(url);
		if(!ret) return;
		
		var guozhangDate=ret;
		var trs = chks.parents('.trRow');
		$('[name="guozhangDate[]"]',trs).val(guozhangDate);
	});
});
    // $('[name="danjia[]"]').live('change',function(){
    //     var danjia=$(this).val();
    //     var id=$(this).siblings('input:[name=id[]]').val();
    //     var kind=$(this).siblings('input:[name=kind[]]').val();
    //     window.location.href="?controller=Shengchan_Waixie_JiaGongFei&action=SetDanjia&danjia="+danjia+"&id="+id+"&kind="+kind;
    // });

{/literal}
</script>