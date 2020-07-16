<select name="isOver" id="isOver">
				<option value=0 {if $arr_condition.isOver == 0} selected="selected" {/if}>未完成</option>
				<option value=1 {if $arr_condition.isOver == 1} selected="selected" {/if}>已完成</option>
				<option value=2 {if $arr_condition.isOver == 2} selected="selected" {/if}>是否完成</option>
            </select>