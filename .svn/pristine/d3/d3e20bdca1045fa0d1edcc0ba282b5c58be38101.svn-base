<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>选择客户</title>
{webcontrol type='LoadJsCss' src="Resource/Css/Main.css"}
{webcontrol type='LoadJsCss' src="Resource/Css/page.css"}
{literal}
<style type="text/css">
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
</style>
{/literal}
<script language="javascript">
{literal}
function retClient(id,val){
	var obj = {clientId:id,compName:val};
	window.returnValue=obj;
	window.close();
}
{/literal}
</script>
</head>
<base target="_self">
<body>
<table width="100%" border="0" cellpadding="2" cellspacing="0" align="center">
	{if $nav_display!='none'}
  <tr>
        <form method="post" action="" name="FormSearch">
        <td id="searchGuide">  
          关键字：
          <input name="key" type="text" size="10" value="{$smarty.post.key|default:$smarty.get.key}"/>
          <input type="submit" name="Submit" value="搜索" class="botton"/>
      	</form>
  </tr> 
  {/if} 
	<tr>
		<td>{include file="_TableForBrowse.tpl"}</td>
  </tr>	
	<tr height=""><td>{$page_info}</td></tr>
</table>
</body>
</html>
