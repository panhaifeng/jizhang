<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$title}</title>
{webcontrol type='LoadJsCss' src="Resource/Css/Main.css"}
{webcontrol type='LoadJsCss' src="Resource/Css/page.css"}
<style type="text/css">
{literal}
	/*td{ font-size:12px;}*/
	form {margin:0px; padding:0px}
	#searchGuide {
		margin:0px; padding:0px;
		width:100%;
		clear:both;
		border:1px solid #CCCCCC; 
		background-color: #FBFFE1;
		height:25px;
		padding-left:5px;
		color: #FF6600;
	}
	#searchGuide * {
		vertical-align:middle;
	}
	#add {padding-left:230px;}
{/literal}
</style>

<script language="javascript">
{literal}
	function retOptionValue(id,val){
		var obj = {value:id,text:val};
		window.returnValue=obj;
		window.close();
	}
	

	function openAddDialog(addUrl){
		var supplier = showModalDialog(addUrl,window,'dialogWidth:500px;dialogHeight:480px;center: yes;help:no;resizable:yes;status:no');
		if (!supplier) return false;
		document.getElementById('aRefresh').click();
	}
{/literal}
</script>
</head>
<base target="_self">
<body>
<table width="100%" border="0" cellpadding="2" cellspacing="0" align="center">
  <tr>
        <form method="post" action="" name="FormSearch">
        <td id="searchGuide">
          关键字：
          <input name="key" type="text" size="10" value="{$smarty.post.key|default:$smarty.get.key}"/>
          <input type="submit" name="Submit" value="搜索" class="botton"/>
		  <!--<a href="{$add_url}" id="add" title="aaaaaaaaa" class="thickbox">新增记录</a>	-->
		  <a href="{url controller=$smarty.get.controller action=$smarty.get.action}" id='aRefresh'>刷新</a>
			{if $add_url}<a href="#" onClick="openAddDialog('{$add_url}');" id="add">新增记录</a>	{/if}
		  </td>
      	</form>
  </tr>  
	<tr>
		<td>{include file="_TableForBrowse.tpl"}</td>
	</tr>	
	<tr height=""><td>{$page_info}</td></tr>
</table>
</body>
</html>