{webcontrol type='LoadJsCss' src="Resource/Script/jquery.json.js"}
<script language="javascript">
{literal}
$(function(){
	$('#create').click(function(){
		var tr = $('#table_fenpi').find('.trRow').eq(0);
		var parent = tr.parent();
		// $('#table_fenpi').find('.trRow').remove();
		// $('[name="fenpiId[]"]').each(function(e){
		// 	if ($(this).val()=='') {
		// 	   $('#table_fenpi').find('.trRow').remove();
		// 	};
		// });
		$('[name="fenpiId[]"]').each(function(e){
			if($(this).val()==''){
				$(this).parent('.trRow').remove();
			}
		})
		var trs = $('.trRow','#table_fenpi');
		var num = 0;
		var jianhao;
		var ord2proId = $('#ord2proId').val();
		var url = "?controller=Shengchan_PiJian_Plan&action=GetCodeNum";
	    var param = {ord2proId:ord2proId};
	    $.ajaxSettings.async = false; 
	    $.getJSON(url,param,function(json){
	    	if (json.success) {
	    		num = json.num;
	    		jianhao = json.jianhao
	    	}
	    })
		var code = $('#planCode').val();
		$('#table_main').find('.trRow').each(function(){
			var pishu = $('[name="pishu[]"]', $(this)).val();
			var jitai = $('[name="jitai[]"]', $(this)).val();
			var date = Date('Y-m-d');
			pishu=pishu==0?1:pishu;

			//var jianhao = 0;
			var a = num;
			for(var j=0;j<pishu-a;j++){
				num++;jianhao++;
				//alert(jitai+'-'+num);
				var row=tr.clone(true);
				$('[name="ExpectCode[]"]',row).val(ord2proId+'-'+num);
				//$('[name="fenpiDate[]"]',row).val(date);
				$('[name="jitaiName[]"]',row).val(jitai);
				$('[name="jianhao[]"]',row).val(jianhao);
				//清空数据
				$('[name="cntKg[]"]',row).val('');
				$('[name="fenpiId[]"]',row).val('');
				parent.append(row);
			}
		});
	});

	$('#table_main').find('#btnAdd2').click(function(){
		var tbl=$(this).parents('.trRowMore').attr('id');
		var x =$(this).attr('Rows');
  		fnAdd('#'+tbl,x);

	});
	//匹数 机台数 修改
	$('[name="pishu[]"]').change(function(){
		var num = 0;
		$('[name="pishu[]"]').each(function(e){
			num+=parseInt($(this).val()|0);
		});
		$('#pishuCnt').val(num);
	});
	$('[name="jitai[]"]').change(function(){
		var num = 0;
		$('[name="jitai[]"]').each(function(e){
			if ($(this).val()!='') {
				num+=1;
			};
		});
		$('#jitaiCnt').val(num);
	});
	//缸号修改
	$('[name="ganghao[]"]').live('change',function(){
		var ganghao = $(this).val();
		$('[name="ganghao[]"]').each(function(e){
			if ($(this).val()=='') {
				$('[name="ganghao[]"]').val(ganghao);
			};
		});
	});

	/**
	* 添加x行方法，适应于多个table
	*/
	function fnAdd(tblId,x) {
		var rows = $('.trRow',tblId);
		var len = rows.length;

		for(var i=0;i<x;i++) {
			var nt = rows.eq(len-1).clone(true);
			$('input,select',nt).val('');
			$('input[type="radio"],input[type="checkbox"]',nt).attr('checked',false);

			//加载新增后运行的代码
			if(typeof(beforeAdd) == 'function'){
				beforeAdd(nt,tblId);
			}
			//拼接
			rows.eq(len-1).after(nt);
		}

		return;
	}

});
{/literal}
</script>