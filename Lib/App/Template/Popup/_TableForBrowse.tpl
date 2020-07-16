<style type="text/css">
{literal}
	#tb {
		width:100%;
		border: 1px solid #cccccc; 
		border-right:0px;
		margin-top:5px; 
		background-color:FFFFFF
		
	}
	#fieldInfo td {
		height:24px;
		border-right:1px solid #ccc;
		color:#000;
		background-color:#FEF4C8;
		padding-left:2px;
	}
	#fieldvalue td {
		border-top: 1px solid #cccccc;
		border-right: 1px solid #cccccc; 
		padding-left:2px; 
		padding-bottom:2px; 
		padding-top:2px;
	}
	a:visited{color:#993300;text-decoration:none;}
{/literal}
</style>
<table id="tb" cellspacing='0' cellpadding='0'>
      {*字段名称*}
      <tr id="fieldInfo">
	    {foreach from=$arr_field_info item=item}
        	{if $item|@is_string==1}<td align='left'>{$item}</td>
            {else}
        	<td align="{$item.align|default:'left'}">{$item.text}</td>
            {/if}
        {/foreach}		
      </tr>
	  
      {*字段的值*}
      {foreach from=$arr_field_value item=field_value}
	  {if $field_value.display != 'false'}	{*显示条件行*}
  	  <tr id="fieldValue" onDblClick='ret({$field_value|@json_encode|escape})'>
	  	{foreach from=$arr_field_info key=key item=item}
            {assign var=foo value="."|explode:$key}
		    {assign var=key1 value=$foo[0]}
		    {assign var=key2 value=$foo[1]}
			{assign var=key3 value=$foo[2]}			
    	<td align="left" {if $field_value._bgColor!=''}bgcolor="{$field_value._bgColor}"{/if}>
            {if $key2==''}{$field_value.$key|default:'&nbsp;'}
            {elseif $key3==''}{$field_value.$key1.$key2|default:'&nbsp;'}
            {else}{$field_value.$key1.$key2.$key3|default:'&nbsp;'}
            {/if}</td>
    	{/foreach}		  	
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