{*通用的编辑模板，一个典型的$field实例是:
$arrF = array(
			"dtShangzhou" =>array('text'=>"上轴日期",'type'=>'calendar'),
			"zhijiId" =>array('text'=>"织机",'type'=>'select','model'=>'jichu_zhiji'),
			'dateYuliao'=>array('text'=>'预了机时间','type'=>'calendar'),
			'dateYuliao'=>array('text'=>'预了机时间','type'=>'calendar'),
			'rate'=>array('text'=>'预计织成率'),
			'isOver'=>array('text'=>'是否了机','type'=>'select','option'=>array(
					array('text'=>'未了','value'=>0),
					array('text'=>'了机','value'=>1),
			)),
			'overTime'=>array('text'=>'了机时间','type'=>'calendar'),
			'id'=>array('type'=>'hidden')
		);
*}<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$title}</title>
{webcontrol type='LoadJsCss' src="Resource/Css/EditCommon.css"}
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/Calendar/WdatePicker.js"}
{literal}
<script language="javascript">
function goback() {
	if(window.parent.tb_remove) window.parent.tb_remove();
	else window.history.go(-1);
}
</script>
{/literal}
</head>
<body>
<form name="form1" id="form1" action="{url controller=$smarty.get.controller action=$action|default:'CommonSave'}" method="post" >
  <table id="mainTable">
  {foreach from=$field item=item key=key}
  {if $item.type!='hidden'}
  <tr>
	<td class="title bitian">{$item.text}：</td>
	<td>
    {if $item.type=='select'}
    <select name='{$key}' id='{$key}'>
    	{if $item.model}
    	{webcontrol type='Tmisoptions' model=$item.model selected=$aRow[$key]}
        {else}
        	{foreach from=$item.option item=it}
            	<option value="{$it.value}" {if $it.value==$aRow[$key]}selected{/if}>{$it.text}</option>
            {/foreach}
        {/if}
    </select>
    {else}
    <input name="{$key}" type="{if $item.type=='calendar'}text
    {elseif $item.type==''}text
    {else}{$item.type}
    {/if}" id="{$key}" value="{if $item.type=='calendar' && $aRow[$key]=='0000-00-00'}{else}{$aRow[$key]}{/if}" {if $item.type=='calendar'}onclick="calendar()"{/if} 
    {if $item.disabled}disabled{/if} {if $item.readonly}readonly{/if} />
    {/if}
    </td>
  </tr>
  {else}
  <input name="{$key}" id="{$key}" type="hidden" value="{$aRow[$key]}" />
  {/if}
  {/foreach}
</table>

<table id="buttonTable">
<tr>
		<td><input name="modelName" type="hidden" id="modelName" value="{$smarty.get.modelName}">
		  <input type="submit" id="Submit" name="Submit" value='保存'>
		<input type="button" id="Back" name="Back" value='返回' onClick="goback()">
		</td>
	</tr>
</table>
</form>
</body>
</html>
