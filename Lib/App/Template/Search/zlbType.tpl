<select name="zlbType" id="zlbType">
    <option value='' {if $arr_condition.zlbType == ''} selected="selected" {/if}>整理布区分</option>
    <option value='A' {if $arr_condition.zlbType == 'A'} selected="selected" {/if}>A</option>
    <option value='B' {if $arr_condition.zlbType == 'B'} selected="selected" {/if}>B</option>
    <option value='C' {if $arr_condition.zlbType == 'C'} selected="selected" {/if}>C</option>
    <option value='D' {if $arr_condition.zlbType == 'D'} selected="selected" {/if}>D</option>
</select>