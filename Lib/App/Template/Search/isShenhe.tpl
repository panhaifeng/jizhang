<select name="isShenhe" id="isShenhe">
	<option value=0 {if $arr_condition.isShenhe == 0} selected="selected" {/if}>未审核</option>
	<option value=1 {if $arr_condition.isShenhe == 1} selected="selected" {/if}>已审核</option>
	<option value=2 {if $arr_condition.isShenhe == 2} selected="selected" {/if}>全部</option>
</select>