<select name="kindYuanliao" id="kindYuanliao">
	<option value=''>纱类别</option>
	<option value='坯纱' {if $arr_condition.kindYuanliao == '坯纱'} selected="selected" {/if}>坯纱</option>
	<option value='色纱' {if $arr_condition.kindYuanliao == '色纱'} selected="selected" {/if}>色纱</option>
</select>