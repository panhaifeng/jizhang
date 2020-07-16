{if $arr_condition.dateTo!==null}
<select name="dateSelect" id="dateSelect" onchange="changeDateNew(this)">
    <option value=-1>时间选择</option>
    <option value= 1>本  月</option>
    <option value= 2>上  月</option>
    <option value= 3>本  年</option>
    <option value= 13>全  部</option>
    <!-- <option value= -1>月份</option>
        {section loop=12 name=loop}
            <option value={$smarty.section.loop.index}>{$smarty.section.loop.index+1}月</option>
        {/section}
    <option value=13>全部</option> -->
</select>
{/if}
<input name="dateFrom" type="text" id="dateFrom" value="{$arr_condition.dateFrom}" size="10" onclick="calendar()" emptyText='选择日期' placeholder='选择日期' />{if $arr_condition.dateTo!==null}-<input name="dateTo" size="10" type="text" id="dateTo" value="{$arr_condition.dateTo}"  onclick="calendar()" emptyText='选择日期' placeholder='选择日期'/>{/if}