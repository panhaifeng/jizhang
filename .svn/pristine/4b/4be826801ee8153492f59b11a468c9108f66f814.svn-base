<!--其他信息-->
<div class="panel panel-info">
  <div class="panel-heading"><h3 class="panel-title" style="text-align:left;">{'选择工序'}</h3></div>
  <div class="panel-body">
  <div style="overflow:auto; border:0px solid #bce8f1; margin-bottom:10px; max-height:290px;">
    <div class="table-responsive" style="width:{$tbl_other_width|default:"100%"};">
      <table class="table table-condensed table-striped trRowMore" name='table_gongxu' key='gxId[]' removeUrl='?controller={$smarty.get.controller}&action={$Remove2GongxuAjax|default:Remove2GongxuAjax}'>
        <thead>
          <tr>
            {foreach from=$headGongxu item=item key=key}
            {if $item.type!='bthidden'}
              {if $item.type=='btBtnRemove'}
                <th>{webcontrol type='btBtnAdd'}</th>
              {else}
              <th style='white-space:nowrap;'>{$item.title}</th>
              {/if}
            {/if}
            {/foreach}
          </tr>   
        </thead>
        <tbody>
          {foreach from=$gongxuSon item=item1 key=key1}
          <tr class='trRow'>
            {foreach from=$headGongxu item=item key=key}
              {if $item.type!='bthidden'}
              <td>{webcontrol 
                      type=$item.type
                      value=$item1[$key].value
                      kind=$item.kind
                      action=$item.action
                      itemName=$item.name
                      readonly=$item.readonly
                      style=$item.style 
                      disabled=$item.disabled
                      model=$item.model
                      options=$item.options
                      checked=$item1[$key].checked
                      url=$item.url 
                      textFld=$item.textFld
                      hiddenFld=$item.hiddenFld
                      text=$item1[$key].text
                      inTable=$item.inTable 
                      condition=$item.condition 
              }</td>
              {else}
                {webcontrol type=$item.type value=$item1[$key].value itemName=$item.name readonly=$item.readonly disabled=$item.disabled}
              {/if}
            {/foreach}
          </tr>  
          {/foreach}    
        </tbody>
      </table>
    </div>
  </div>
  </div>
  </div>
</div>