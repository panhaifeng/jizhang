<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>为角色"{$aRole.id}:{$aRole.roleName}"分配权限</title>
{webcontrol type='LoadJsCss' src="Resource/Script/Common.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.js"}
<script language="javascript" src="Resource/Script/TmisMenu.js"></script>
{webcontrol type='LoadJsCss' src="Resource/Script/ymPrompt/ymPrompt.js"}
<link href="Resource/Css/Edit200.css" type="text/css" rel="stylesheet">
{webcontrol type='LoadJsCss' src="Resource/Script/ymPrompt/skin/bluebar/ymPrompt.css"}
<script language="javascript">
{literal}
function popMenu(e,obj) {
	//tMenu(e,'Index.php?controller=Acm_Func&action=tmismenu',0,true);
	//dump(e);
	if(e.keyCode==13){
		//alert('ddddd');return false;
		var url="?controller=Acm_Func&action=getJsonByKey";
		param={key:obj.value};
		$.getJSON(url,param,function(json){
				if(!json||json.length==0){
					alert('该模块不存在！');
					return false;
				}
				if(json.length==1){
					obj.value=json[0].id;
					return false;
				}
				selRole(json);
				return false;
									 
		});
		return false;
	}
		
	/*url="?controller=Acm_Func&action=tmismenu";
	var arr=showModalDialog(url);*/
}
function selRole(obj){
	var url="?controller=Acm_Func&action=Popup";
	var objs = document.getElementById('funcId');
	if(objs.value!='') url += '&key=' + encodeURI(objs.value);
			
	ymPrompt.win({message:url,handler:callBack,width:700,height:500,title:'选择模块',iframe:true});
	return false;  
	function callBack(ret){
		//dump(ret);return false;
		if(!ret||ret=='close') return false;
		
		document.getElementById('funcId').value=ret.id;
		//document.getElementById('employName').value=ret.employName;
	} 
}
$(function(){
	ret2cab();		   
});
{/literal}
</script>
</head>

<body>
<fieldset>     
<legend>为角色"{$aRole.id}:{$aRole.roleName}"分配权限</legend>
<form method="post" action="{url controller=$smarty.get.controller action=saveassign}">
	<input name="roleId" type="hidden" id="roleId" value="{$aRole.id}" />
	<div id=func>
			选择权限：
			<input name="funcId" type="text" id="funcId" onkeyDown="popMenu(event,this)"/>
			<input type="button" name="button" id="button" value="..." onClick="selRole(this)">
<input type="submit" name="Submit" value="提 交" />
	</div>
</form>	

	<table class="tableHaveBorder" style="width:400px;">
      <tr align="center" bgcolor="#f0f0f0">
        <td>已分配权限编号</th>
        <td>已分配权限</th>
        <td>操作</th>
      </tr>
	  {foreach from=$aRole.funcs item=aFunc}
      <tr align="center">
        <td>{$aFunc.id}</td>
        <td>{$aFunc.funcName}</td>
        <td><a href="{url controller=$smarty.get.controller action=removeAssign roleId=$smarty.get.id funcId=$aFunc.id}" onClick="return confirm('您确认要删除吗?')">删除</a></td>
      </tr>
	  {/foreach}
    </table>
	<br>
</fieldset>
</form>
</body>
</html>
