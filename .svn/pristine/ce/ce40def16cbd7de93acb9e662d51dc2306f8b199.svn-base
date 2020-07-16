<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/Calendar/WdatePicker.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/Common.js"}
<title>数据库修改提交</title>
{literal}
<script language="javascript">
$(function(){
	//补丁文件列表内容初始化
	getPatchs();	
	//getPrefix();
	//l.options[0].click();
	
	//刷新
	$('#btnRfresh').click(function(){
		getPatchs();
		$('#sql').val('');
	});
	
	//删除补丁文件
	$('#btnRemove').click(function(){
		var l = $('#listPatch')[0];
		var f = l.options[l.selectedIndex].value;
		if(f=='') {
			alert('请选择文件');return;
		}
		var url='?controller=Dbchange&action=remove';
		var param={fileName:f};
		$.post(url,param,function(json){
			if(!json) {
				alert('服务器返回错误');
				return;
			}
			if(!json.success) {
				alert(json.msg);
				return;
			}
			getPatchs();
			$('#sql').val('');
		},'json');
	});
	
	//日期改变时，补丁文件内容刷新
	document.getElementById('datePatch').onclick=function(){
		WdatePicker({onpicked:getPatchs})
	}
	
	//前缀改变时刷新list
	$('#prefix').change(function(){
		if(this.value=='') {
			alert('请输入程序员身份标记');
			$('#prefix').focus();
			return;
		}
		//ajax改变前缀文件，成功后刷新补丁列表
		var url='?controller=Dbchange&action=ChangePrefix';
		var param = {prefix:this.value};
		$.post(url,param,function(json){
			if(!json.success) {
				alert(json.msg);
				retun;
			}
			getPatchs();
			$('#sql').val('');
		},'json');		
	});
	
	//listPatch选中时获得文件内容
	$('#listPatch').change(function(){
		if(this.selectedIndex==0) {
			$('#sql').val('');
			return;
		}
		
		var l = $('#listPatch')[0];
		var f = l.options[l.selectedIndex].value;		
		var url='?controller=Dbchange&action=GetSqlByAjax';
		var param={fileName:f};		
		$.post(url,param,function(json){			
			if(!json) {
				alert('服务器返回错误');
				return;
			}
			if(!json.success) {
				alert(json.msg);
				return;
			}
			$('#sql').val(json.content);
		},'json');
	});
	
	//提交前有效性判断
	$('#btnOk').click(function(){
		if($('#prefix').val()=='') {
			alert('请输入前缀');
			return;
		}
		if($('#sql').val()=='') {
			alert('请输入sql语句');
			return;
		}
		this.disabled=false;
		//开始提交
		var l = $('#listPatch')[0];
		var f = l.options[l.selectedIndex].value;		
		var url='?controller=Dbchange&action=save';
		var param={fileName:f,prefix:$('#prefix').val(),datePatch:$('#datePatch').val(),sql:$('#sql').val()};
		$.post(url,param,function(json){
			if(!json) {
				alert('服务器返回错误');
				return;
			}
			if(!json.success) {
				alert(json.msg);
				return;
			}
			alert('保存成功');
			getPatchs(function(){
				var f = json.curFile;
				
				var l = $('#listPatch')[0];
				for(var i=0;l.options[i];i++) {
					if(l.options[i].value==f) {
						l.selectedIndex = i;
						$(l).change();
						return;
					}
				}
			});
			//还原选中状态
			//$('#sql').val(json.content);
		},'json');
	});
	
	//根据日期获得所有patchs
	function getPatchs(fn) {
		var d = $('#datePatch').val();
		var prefix = $('#prefix').val();
		if(prefix=='') {
			alert('程序员身份标记不存在,请输入');
			return;
		}
		var url='?controller=Dbchange&action=GetPatchsByAjax';
		var param={datePatch:d,prefix:prefix};
		$.post(url,param,function(json){
			if(!json) {
				alert('获得补丁失败');
				return;
			}
			//清空list
			var l = document.getElementById('listPatch');
			while(l.options.length>1) {
				l.options[1]=null;
			}
			
			for(var i=0;json[i];i++) {
				//$("<option value='111'>UPS Ground</option>").appendTo($("select[@name=ISHIPTYPE]"));
				$("<option value='"+json[i]+"'>"+json[i]+"</option>").appendTo(l);
			}
			
			if(fn&&typeof(fn)=='function') fn();
		},'json');
	}
	
	//将最后一个置为选中
	var l = document.getElementById('listPatch');
	l.selectedIndex = 0;
	$(l).change();
});
</script>
<style type="text/css">
textarea,input,select {font-size:11px;}
</style>
{/literal}
</head>

<body>

<table width="100%" border="1" cellspacing="1" cellpadding="1">
  <tr>
  	<td>补丁提交(程序员用)</td>
  	<td align='right'>程序员身份标记：
  		<input name="prefix" type="text" id="prefix" value="{$prefix}" />
    </td>
  </tr>
  <tr>
    <td valign="top"><select name="listPatch" size="10" id="listPatch">
      <option value=''>创建新补丁</option>
	</select>
    <br />
    <input type="button" name="btnRfresh" id="btnRfresh" value="刷新" />
    <input type="button" name="btnRemove" id="btnRemove" value="删除" /></td>
    <td valign="top"><table width="100%" border="0" cellspacing="1" cellpadding="1">
      <tr>
        <td align="right">日期：</td>
        <td><input name="datePatch" type="text" id="datePatch" value="{$smarty.now|date_format:'%Y-%m-%d'}"/></td>
        </tr>      
      <tr>
        <td align="right" valign="top">sql语句：</td>
        <td valign="top"><label for="sql"></label>
          <textarea name="sql" id="sql" cols="100" rows="20"></textarea><br />
          有多条sql语句以&quot;;&quot;隔开,sql语句中不能出现分号</td>
        </tr>
      <tr>
        <td valign="top">&nbsp;</td>
        <td valign="top"><input type="submit" name="btnOk" id="btnOk" value=" 提 交 " /></td>
        </tr>
    </table></td>
  </tr>
  
</table>
</body>
</html>