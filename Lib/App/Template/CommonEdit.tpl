<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$title}</title>
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/Common.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/Calendar/WdatePicker.js"}
<script language="javascript">
var _i = {$formItems|@json_encode};
var _v = {$defaultValues|@json_encode};
{literal}
$(function(){		
	if(_i.ret2cab) ret2cab();
	document.getElementById('form1').action = _i.action;
	
	//设置元素的默认值
	for(var i in _i.fields) {
		_i.fields[i].value= _v[i] ? _v[i] : '';
	}
	
	//构造元素
	for(var i in _i.fields) {
		var it = makeItem(_i.fields[i],i);
		document.getElementById('divMain').appendChild(it);
	}	
	
	$('#form1').submit(function(){
		//检查每个_items.data中每个元素的有效性
		alert(this.action);
		return false;
	});
	//makeUpper(document.getElementById('compCode'));
	//makeUpper(document.getElementById('zhujiCode'));
   	
});

//根据c创建元素,创建的元素中，包括了validate属性等
function makeItem(c,id) {
	//根据类型创建元素
	var it = null;
	var div=document.createElement('div');
	
	//创建文字span
	var span = document.createElement('span');	
	if(c.allowBlank===false) {
		span.style.color='red';
	}
	$(span).text(c.text||'未知');
	div.appendChild(span);
	
	//debugger;
	if(!c.type ||c.type=='textField') {
		it=document.createElement('input');
		it.onclick=function(){this.select()};
	} else if(c.type=='dateField') {
		it=document.createElement('input');
		it.onclick=calendar;
	} else if(c.type=='textarea') {
		it=document.createElement('textarea');
	}
	//命名
	it.name=id;
	it.id=id;
	
	//默认值
	it.value=c.value;	
	
	if(it.disabled) it.disabled=true;
	
	//vtype,有效性
	if(c.vtype) {
		if(c.vtype=='alpha') {//只能字母
			it.onkeydown = function(e) {
				if(document.all) e=window.event;
				
			}
		} else if(c.vtype=='alphanum') {//只能数字或字母
			it.onkeydown = function(e) {
				if(document.all) e=window.event;
				alert(e.keyCode);
			}
		}
	}
	
	//allowBlank
	div.appendChild(it);
	return div;
}
</script>
<base target="_self">
<style type="text/css">
#divMain div {float:left; width:30%}
</style>
{/literal}</head>
<body>
<form name="form1" id="form1" method="post">
<input type="hidden" name="pos" id="pos" value="{$aClient.pos}">

<div id='divMain'>
</div>
<table>
<tr>
    	<td><input type="submit" id="Submit" name="Submit" value='保存并新增下一个' class="button"></td>
    	<td><input type="submit" id="Submit" name="Submit" value='保存' class="button"></td>
		<td><input type="reset" id="Reset" name="Reset" value='重置' class="button"></td>
        <td><input type="button" id="Back" name="Back" value='返回' onClick="javascript:window.history.go(-1);" class="button"></td>
    </tr>
</table>
</form>
</body>
</html>
