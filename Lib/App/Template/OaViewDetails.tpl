<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/Calendar/WdatePicker.js"}
<title>{$title}</title>
{webcontrol type='LoadJsCss' src="Resource/Css/EditCommon.css"}
{webcontrol type='LoadJsCss' src="Resource/Script/ymPrompt/ymPrompt.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/ymPrompt/skin/bluebar/ymPrompt.css"}
{webcontrol type='LoadJsCss' src="Resource/Script/TmisPopup.js"}
{literal}
<script language="javascript">
	$(function(){
		$('#divTail').click(function(){
		window.parent.location.href=window.parent.location.href;
	  });
	});
</script>
<style type="text/css">
.line{border-bottom: solid 1px #996600;}
td{font-size:12px;}
</style>
{/literal}
</head>

<body>



<table width="100%" border="0">
  <tr>
    <td align="center" colspan="2"  >
      <span style="font-size:15pt; font:bold">{$row.title|default:'&nbsp;'}</span><br/>

    </td>
    </tr>
	<tr >
    	<td align="left" class="line">创建人：{$row.creater|default:'系统自动生成'}</td>
    	<td align="right" class="line">发布日期：{$row.buildDate|default:$smarty.now|date_format:'%Y-%m-%d'}</td>
    </tr>
  <tr>
    <td align="center" style="height:300px" class="line" colspan="2"><div style="height:300px; width:100%; text-align:left;word-wrap:break-word;word-break:break-all;"   >{$row.content|default:'&nbsp;'}</div></td>
  </tr>
  <tr>
    <td align="right"  colspan="2"  ><input type="hidden" name="hiddenField" id="hiddenField" value="{$smarty.session.USERNAME}"></td>
    </tr>

</table>
<div id='divTail'>
      <input type="button" name="btnCancel" id="btnCancel" value=" 关闭 " />
    </div>

</body>
</html>
