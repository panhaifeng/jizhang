<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/thickbox/thickbox.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/thickbox/thickbox.css"}
{webcontrol type='LoadJsCss' src="Resource/Script/Calendar/WdatePicker.js"}
<title>数据库修改提交</title>
{literal}
<script language="javascript">
$(function(){
	//补丁文件列表内容初始化
	getPatchs();	
	//getPrefix();
	//l.options[0].click();
	//执行选中
	$('#btnDoSel').click(function(){
		var filename = $('#listPatch').val();
		if(filename==null) return;
		executeSql(filename);
	});
	//标记为已执行
	$('#btnMark').click(function(){
		var fileName = $('#listPatch').val();
		if(fileName==null) return;
		var url= '?controller=Dbchange&action=DoMark';
		var param={fileName:fileName};
		$.post(url,param,function(json){
			if(!json) {
				showMsg('服务器返回错误');
				return;
			}
			if(!json.success) {
				showMsg(json.msg);
				return;
			}
			//删除option
			var opt = $('#listPatch')[0].options;
			for(var j=0;opt[j];j++) {
				if(opt[j].text==json.fileName) {
					$(opt[j]).remove();
					return;
				}
			}
			//$('#sql').val(json.content);
		},'json');
	});

	//开始执行
	$('#btnDo').click(function(){
		var opt = $('#listPatch')[0].options;		
		var fs = [];
		for(var i=0;opt[i];i++) {
			fs.push(opt[i].text);
		}
		
		var c=true;
		for(var i=0;fs[i];i++) {
			if(!c) break;				
			//ajax执行文件内容,执行结果回显
			var r = executeSql(fs[i]);
			if(!r) return;//执行失败，中断
		}
		return;		
	});
	
	//listPatch选中时获得文件内容
	$('#listPatch').change(function(){		
		//var l = $('#listPatch')[0];
		var f = $('#listPatch').val();		
		var url='?controller=Dbchange&action=GetSqlByAjax';
		var param={fileName:f};
		$.post(url,param,function(json){
			if(!json) {
				showMsg('服务器返回错误');
				return;
			}
			if(!json.success) {
				showMsg(json.msg);
				return;
			}
			$('#sql').val(json.content);
		},'json');
	});
	//通过ajax执行sql文件
	function executeSql(fileName) {
		var url='?controller=Dbchange&action=Execute';
		var param={fileName:fileName};
		var ret = true;//返回值，如果ajax返回错误，需要中断
		$.ajax({
			url:url,
			type:"POST",
			async:false,
			data:param,
			dataType:'json',
			success:function(json){
				if(!json) {
					showMsg('服务器返回错误');
					ret=false;
					return;
				}
				if(!json.success) {
					showMsg(json.msg);
					ret=false;
					return;
				}

				//执行成功，控制台输出
				ret = true;
				showMsg(json.msg);
				//删除opt中的		
				var opt = $('#listPatch')[0].options;		
				for(var j=0;opt[j];j++) {
					if(opt[j].text==json.fileName) {
						$(opt[j]).remove();
						return;
					}
				}
			}
		});
		return ret;
	}
	//获得所有未执行patchs
	function getPatchs(fn) {		
		var url='?controller=Dbchange&action=GetPatchs4Up';
		var param={};
		$.post(url,param,function(json){
			if(!json) {
				alert('获得补丁失败');
				return;
			}
			//清空list
			var l = document.getElementById('listPatch');
			while(l.options.length>0) {
				l.options[1]=null;
			}
			
			for(var i=0;json[i];i++) {
				//$("<option value='111'>UPS Ground</option>").appendTo($("select[@name=ISHIPTYPE]"));
				$("<option>"+json[i]+"</option>").appendTo(l);
			}
			
			if(fn&&typeof(fn)=='function') fn();
		},'json');
	}
	
	function showMsg(msg){
		$("#divLog").append("<div>"+msg+"</div>");
	}
	
});
</script>
<style type="text/css">
textarea,input,select {font-size:11px;}
#divLog div { clear:both; white-space:nowrap; border-bottom: 1px dotted #000; margin-top: 5px;}
#listPatch {width:200px;}
</style>
{/literal}
</head>

<body>
<table width="100%" border="1" cellspacing="1" cellpadding="1">  
  <tr>
    <td valign="top">未执行补丁,360天之前({$dateFrom})的补丁跳过<br />
      <select name="listPatch" size="10" id="listPatch"></select>
    <br />
    <input type="button" name="btnDo" id="btnDo" value="全部执行" />
    <input type="button" name="btnDoSel" id="btnDoSel" value="执行选中" />
    <input type="button" name="btnMark" id="btnMark" value="标记为已执行" />
	</td>
	<td valign="top">
		<table width="100%" border="0" cellspacing="1" cellpadding="1">
	      <tr>        
	        <td valign="top">sql语句：<br />
	          <textarea name="sql" cols="45" rows="10" disabled="disabled" id="sql"></textarea></td>
	      </tr>
	    </table>
	</td>
</tr>
</table>

执行结果:[ <a href='{url controller="Dbchange" action="showLog" TB_iframe=1}' class='thickbox' title='已执行补丁'>已执行补丁清单</a> ]
<div id='divLog' style="BORDER-RIGHT: 2px ridge; BORDER-TOP: 2px ridge; BACKGROUND: #ffffff; BORDER-LEFT: 2px ridge; WIDTH: 100%; BORDER-BOTTOM: 2px ridge; HEIGHT:300px; overflow:scroll;"> 
</div>
</body>
</html>