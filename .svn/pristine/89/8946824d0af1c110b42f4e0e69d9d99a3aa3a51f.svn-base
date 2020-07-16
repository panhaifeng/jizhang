{*传递no_edit=1,可使_edit字段不显示*}
<style type="text/css">
{literal}
	#tb {
		width:100%;
		border: 1px solid #cccccc;
		border-right:0px;
		margin-top:2px;
		background-color:FFFFFF;
		border-collapse:collapse;
	}
	#tb td {
		white-space:nowrap;
	}
	#fieldInfo td {
		height:22px;
		border-right:1px solid #ccc;
		color:#000;
		background-color:#D4E2F4;
		padding-left:2px;
	}
	#fieldvalue td {
		border-top: 1px solid #cccccc;
		border-right: 1px solid #cccccc;
		padding-left:2px;
		padding-bottom:1px;
		padding-top:2px;
		vertical-align:top;
		height:20px;
	}
	a{}
	a:link{color:#0000FF;text-decoration:none;}
	a:visited{color:#996600;text-decoration:none;}
	a:hover{color:#0000FF;text-decoration:underline;}
{/literal}
</style>
<table id="tb" cellspacing='0' cellpadding='0'>
      {*字段名称*}
      <tr id="fieldInfo">
	    {foreach from=$arr_field_info item=item key=key}
        	{if $item|@is_string==1}
            {if $key!='_edit' || $smarty.get.no_edit!=1}
            <td align='left'>{$item}</td>
            {/if}
            {else}
        	<td align="{$item.align|default:'left'}">
            	{if $item.sort}
                <a href='{url controller=$smarty.get.controller action=$smarty.get.action sortBy=$key}'>{$item.text}</a>
                {else}
                {$item.text}
                {/if}
            </td>
            {/if}
        {/foreach}

		{if $arr_edit_info != ""}
        	<td align="center">操作</td>
		{/if}
      </tr>

      {*字段的值*}
      {foreach from=$arr_field_value item=field_value}
	  {if $field_value.display != 'false'}	{*显示条件行*}
  	  <tr id="fieldValue">
	  	{foreach from=$arr_field_info key=key item=item}
        	{if $key!='_edit' || $smarty.get.no_edit!=1}
            {assign var=foo value="."|explode:$key}
		    {assign var=key1 value=$foo[0]}
		    {assign var=key2 value=$foo[1]}
			{assign var=key3 value=$foo[2]}
    	<td align="left" {if $field_value._bgColor!=''} style="background-color:{$field_value._bgColor}" {/if}>
            {if $key2==''}{$field_value.$key|default:'&nbsp;'}
            {elseif $key3==''}{$field_value.$key1.$key2|default:'&nbsp;'}
            {else}{$field_value.$key1.$key2.$key3|default:'&nbsp;'}
            {/if}</td>
            {/if}
		{/foreach}

		{if $arr_edit_info != ""}
			<td align="center">&nbsp;
			{foreach from=$arr_edit_info key=key item=item}
			{if $item == "删除" && $field_value.id!=""}
				<a href="{url controller=$smarty.get.controller action=$key id=$field_value.id parentId=$smarty.get.parentId}" onclick="return confirm('确认删除吗?')">{$item}</a>
			{else}
				<a href="{url controller=$smarty.get.controller action=$key id=$field_value.id}">{$item}</a>
			{/if}
			{/foreach}
			</td>
		{/if}
  	  </tr>
	  {/if}
      {/foreach}
    </table>
{literal}
<script type="text/javascript">
 var obj=document.getElementById("tb");
 for(var i=1;i<obj.rows.length;i++){
   obj.rows[i].onmouseover=function(){
	  this.style.background="#DDEEF2";
	}
   obj.rows[i].onmouseout=function(){this.style.background="";}
 }
</script>
{/literal}