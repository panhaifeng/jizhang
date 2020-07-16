<select name="dateType" id="dateType">
    <option value='fwDate' {if $arr_condition.dateType == 'fwDate'} selected="selected" {/if}>发外日期</option>
    <option value='chukuDate' {if $arr_condition.dateType == 'chukuDate'} selected="selected" {/if}>出库日期</option>
</select>