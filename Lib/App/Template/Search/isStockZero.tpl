<select name="isStockZero" id="isStockZero">
	<option value=2>全部</option>
	<option value=0 {if $arr_condition.isStockZero === '0'} selected="selected" {/if}>有效库存</option>
	<option value=1 {if $arr_condition.isStockZero === '1'} selected="selected" {/if}>零库存</option>
</select>