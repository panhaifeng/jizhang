<select name="traderId" id="traderId">
    		{webcontrol type='Traderoptions' model='Jichu_Employ' selected=$arr_condition.traderId emptyText='选择业务员'}
 </select>
 {include file='GetClientByTraderIdJson.tpl'}