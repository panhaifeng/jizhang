<html>
<head>
<base target="_self" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$title}</title>
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.js"}
<script src="Resource/Script/treeview/tree.js" type="text/javascript" ></script>
<script src="Resource/Script/Common.js" type="text/javascript" ></script>
<link href="Resource/Script/treeview/tree.css" type="text/css" rel="stylesheet">
{literal}
<script language="javascript">
	var _cacheFunc = new Array();//当前角色拥有的node
	var _cRow = null;//当前被选中的行
	var o = { 
		showcheck: true,							
		url: "?controller=jichu_pishaguige&action=GetTreeJson" ,			
		showcheck: true, //是否显示选择            
		oncheckboxclick: false, //当checkstate状态变化时所触发的事件，但是不会触发因级联选择而引起的变化		
		cascadecheck: true,
		data: null,
		clicktoggle: true, //点击节点展开和收缩子节点
		theme: "bbit-tree-arrows", //bbit-tree-lines ,bbit-tree-no-lines,bbit-tree-arrows	
		onnodeclick: function(){
			var obj = $('#tree').getCurrentItem();
			if(!obj.hasChildren) {
				var ret = {id:obj.id,guige:obj.text}
				window.parent.ymPrompt.doHandler(ret,true);
			} else {
				var i = $("#tree").getCurrentItem();
				//debugger;
				i.isexpand?$("#tree").unexpandItem(i.id):$("#tree").expandItem(i.id);
			}
			//alert(obj.value);
		}
	};
  	o.data = {/literal}{$data}{literal};
  	$(function(){
		$("#tree").treeview(o);
		$('#btnBlank').click(function(){
			var ret = {id:'',guige:''}
			window.parent.ymPrompt.doHandler(ret,true);
		});
	});
</script>
<style type="text/css">
*{margin:0px; padding:0px;}
table tr td{font-size:14px;}
</style>
{/literal}
</head>
<body style="margin-left:5px; margin-right:5px;">
<div style="border-bottom: #c3daf9 1px solid; border-left: #c3daf9 1px solid; width: 90%; height: 90%; overflow: auto; border: #c3daf9 1px dotted; float:left; margin-left:10px;margin-top:10px;">
        <div id="tree" style="text-align:left"></div>     
         
</div>
    <div align="right" style="margin-right:20px; clear:both">
      <input type="button" name="btnBlank" id="btnBlank" value=" 关闭 ">
    </div>  
</body>
</html>