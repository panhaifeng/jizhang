<select name="gzType" id="gzType">
	<option value=2>过账类型</option>
	<option value=0 {if $arr_condition.gzType === '0'} selected="selected" {/if}>应收过账</option>
	<option value=1 {if $arr_condition.gzType === '1'} selected="selected" {/if}>其他过账</option>
</select>