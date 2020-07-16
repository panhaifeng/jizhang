<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$title}</title>
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.validate.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/Calendar/WdatePicker.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/common.js"}
{webcontrol type='LoadJsCss' src="Resource/Css/EditCommon.css"}
{webcontrol type='LoadJsCss' src="Resource/Css/validate.css"}
{webcontrol type='LoadJsCss' src="Resource/Script/ymPrompt/ymPrompt.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/ymPrompt/skin/bluebar/ymPrompt.css"}
{webcontrol type='LoadJsCss' src="Resource/Script/TmisPopup.js"}
{literal}
<script language="javascript">
function delRow(obj) {
	var _tbl = document.getElementById('listTable');
	var pos =-1;
	var objs = document.getElementsByName('btnDel');
	for(var i=0;objs[i];i++) {
		if(objs[i]==obj) {
			pos=i;break;
		}
	}
	if(pos==-1) return false;
	//debugger;
	var url = '?controller=Jichu_Client&action=delTaitouAjax';
		//debugger;
		var param={
			id:document.getElementsByName('id[]')[pos].value				
		};
		$.getJSON(url,param,function(json){
			//debugger;
			if(!json.success) {
				alert(json.msg);
				return false;
			}	
			_tbl.deleteRow(pos+1);
		});	
}


</script>
{/literal}

</head>
<body style="width:400px; height:300px;">
<form name="form1" id="form1" action="{url controller=$smarty.get.controller action='saveTaitou'}" method="post" onSubmit="return check();">
<table id="listTable" class="tableHaveBorder">
<tr class="th">
   
	    <td>抬头</td>
	    <td>备注</td>
	    <td>操作</td>
  </tr>
  
   {foreach from=$aRow item=item}
	<tr>
		
	  <td><input name="id[]" type="hidden" id="id[]" value="{$item.id}">
        <input name="taitou[]" type="text" id="taitou[]" size="10" value="{$item.taitou}">
       </td>
	  <td><textarea id="memo[]" name="memo[]" >{$item.memo}</textarea></td>
		<td><input type="button" name="btnDel" id="btnDel" value="删除" size="8" onClick="delRow(this)" class="button"></td>		
		
        
	</tr>
    {/foreach}
  
 <tr>
		
	  <td><input type="text" id="taitou" name="taitou" size="10" /><input type="hidden" id="id" name="id" /></td>
	  <td><textarea id="memo" name="memo" ></textarea></td>
		<td><input  id="Submit" name="Submit" value="提交"  type="submit" />
        </td>		
		
       
	</tr>
    
</table>
<div align="center"><input type="button" id="Back" name="Back" value='返回' onClick="window.parent.location.href=window.parent.location.href"></div>
<input type="hidden" id="clientId" name="clientId" value="{$smarty.get.clientId}" />
<!--<input type="button" id="Back" name="Back" value="取消" javascript:window.parent.tb_remove() />-->
</form>
</body>
</html>
