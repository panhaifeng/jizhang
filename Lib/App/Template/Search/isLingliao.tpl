<select name="isLingliao" id="isLingliao">
                <option value='0' {if $arr_condition.isLingliao == '0'} selected="selected" {/if}>未领料</option>
                <option value='1' {if $arr_condition.isLingliao == '1'} selected="selected" {/if}>已领料</option>
            </select>