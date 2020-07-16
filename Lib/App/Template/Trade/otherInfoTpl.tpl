<div class="panel panel-info">
  <div class="panel-heading"><h3 class="panel-title" style="text-align:left;">合同备注</h3></div>
  <div class="panel-body">
    <div class="row">      
      {foreach from=$arr_memo item=item key=key}
      {include file="Main2Son/"|cat:$item.type|cat:".tpl"}      
      {/foreach}
    </div>       
  </div>
</div>

<!-- <div class="panel panel-info">
  <div class="panel-heading"><h3 class="panel-title" style="text-align:left;">合同条款</h3></div>
  <div class="panel-body">
    <div class="row">      
      {foreach from=$arr_item item=item key=key}
      {include file="Main2Son/"|cat:$item.type|cat:".tpl"}      
      {/foreach}
    </div>       
  </div>
</div> -->