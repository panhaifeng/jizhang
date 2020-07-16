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
		$('#btnCancel').click(function(){
			window.parent.location.href=window.parent.location.href;
		});	   
		$('#btnBack,#btnBackAll').click(function(){
			var id=$('#id').val();
			var url="?controller=Mail&action=add&isBack=1&mailId="+id;
			if(this.id=='btnBackAll'){
				url+="&isAll=1";
			}
			window.parent.location.href=url;
		});		   
	});
</script>
<style type="text/css">
.line{border-bottom: solid 1px #996600;}
td{font-size:12px;}
input{ border:1px #999 solid; height:25px;}
</style>
{/literal}
</head>

<body>
<table id="mainTable">


<table width="100%" border="0">
  <tr>
    <td align="center" class="line" >
      <span style="font-size:12pt; font:bold">{$row.title|default:'&nbsp;'}</span><br/>
     <div style="float:left;"> 发布日期：{$row.dt|default:$smarty.now|date_format:'%Y-%m-%d'}</div>
     <div style="float:right;">发件人：{$row.Sender.realName}</div>
     <br><div style="float:left; padding-top:3px;">收件人：{foreach from=$accepter item=item}
     <span id="accepter">{$item.realName}</span>
     {/foreach}</div>
    </td>
    </tr>

  <tr>
    <td align="center" style="height:300px" class="line"><div style="height:300px; width:100%; text-align:left;word-wrap:break-word;word-break:break-all;">{$row.content|default:'&nbsp;'}</div></td>
  </tr>
  <tr>
    <td align="right">
    <input type="hidden" name="hiddenField" id="hiddenField" value="{$smarty.session.USERNAME}">
    <input type="hidden" name="id" id="id" value="{$row.id}">
    </td>
    </tr>

</table>
<div id='divTail'>
      <input type="button" name="btnCancel" id="btnCancel" value=" 关 闭 "/>&nbsp;&nbsp;
      <input type="button" name="btnBack" id="btnBack" value=" 回 复 "/>&nbsp;&nbsp;
      <input type="button" name="btnBackAll" id="btnBackAll" value="全部回复"/>
    </div>

</body>
</html>
