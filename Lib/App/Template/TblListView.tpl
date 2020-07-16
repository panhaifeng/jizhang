{*TblList.tpl模板中value值的显示区域，剥离出来 by li 2012-12-12 16:05:45*}
{foreach from=$arr_field_value item=field_value}
		<div id='divRow' name='divRow' style='background-color:{cycle values="#ffffff,#fafafa"}'>
			<table border="0" cellpadding="0" cellspacing="0" id="tblRow" name="tblRow">
				{if $field_value.display != 'false'}	{*显示条件行*}
				  <tr class='trRow' >
					{foreach from=$arr_field_info key=key item=item}
						{if $key!='_edit' || $smarty.get.no_edit!=1}
						  {assign var=foo value="."|explode:$key}
						  {assign var=key1 value=$foo[0]}
						  {assign var=key2 value=$foo[1]}
						  {assign var=key3 value=$foo[2]}
					<td {if $tbHeight!=''}height='{$tbHeight}px'{/if} {if $field_value._bgColor!=''} style="background-color:{$field_value._bgColor}" {/if}><div class='valueTdDiv'>
						  {if $key2==''}
							{$field_value.$key|default:'&nbsp;'}
						  {elseif $key3==''}
							{$field_value.$key1.$key2|default:'&nbsp;'}
						  {else}
							{$field_value.$key1.$key2.$key3|default:'&nbsp;'}
						  {/if}
					</div></td>
						{/if}
					{/foreach}
				  </tr>
				  {/if}
			  </table></div>
		{/foreach}