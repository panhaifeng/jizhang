{*
T1为T的升级版本,主要是将js剥离出来了。
注意:
proKind为产品弹出选择控件的必须参考的元素,特里特个性化需求,出了订单登记界面外其他使用产品弹出选择控件的模板必须制定proKind为hidden控件
*}<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$title}</title>
</head>
<link href="Resource/Css/validate.css" type="text/css" rel="stylesheet">
<link rel="stylesheet" href="Resource/bootstrap/bootstrap3.0.3/css/bootstrap.css">

{literal}
<style type="text/css">

body{margin-left:5px; margin-top:5px; margin-right: 8px;}
.btns { position:absolute; right:16px; top:1px; height:28px;}
.relative { position:relative;}
.frbtn {position:absolute; top:1px; right:0px; height:28px;z-index:1000;}
.pd5{ padding-left:5px;}
#heji { padding-left:20px; height:20px; line-height:20px; margin-bottom:5px;}
label.error {
  color: #FF0000;
  font-style: normal;
  position:absolute;
  right:-50px;
  top:5px;
}
.lableMain {
  padding-left: 2px !important;
  padding-right: 2px !important;
}
.trRow select{width:auto;}
.trRow input{min-width:75px;}
.form-horizontal{overflow: hidden;}
</style>
{/literal}
<body>

<form name="form1" id="form1" class="form-horizontal" action="{url controller=$smarty.get.controller action=$action_save|default:'save'}" method="post">

<!-- 主表字段登记区域 -->
<div class="panel panel-info">
  <div class="panel-heading"><h3 class="panel-title" style="text-align:left;">{$areaMain.title}</h3></div>
  <div class="panel-body">
    <div class="row">
    {foreach from=$areaMain.fld item=item key=key}
      {assign var="f" value="Lib/App/Template/Main2Son/"|cat:$item.type|cat:".tpl"}
      {if file_exists($f)}
      {include file="Main2Son/"|cat:$item.type|cat:".tpl"}
      {else}
      {$key}:{$item.type}对应的模板文件{$f}不存在
      {/if}
    {/foreach}
    </div>
  </div>
</div>
<div class="panel panel-info">
<div class="panel-heading">
  <h3 class="panel-title" style="text-align:left;">{$sonTitle|default:'明细信息'}</h3>
</div>
<div class="panel-body" style="overflow:auto;max-height:320px;">
  <div class="table-responsive" style="width:{$tbl_son_width|default:"100%"};">
    <table class="table table-condensed table-striped trRowMore" id='table_main' removeUrl='?controller={$smarty.get.controller}&action={$RemoveByAjax|default:RemoveByAjax}' key='id[]'>
      <thead>
        <tr>
        {assign var=i value=0}
        {foreach from=$headSon item=item key=key}
        {assign var=i value=$i+1}
        {if $item.type!='bthidden'}
          {*如果是第一列,判断$firstColumn.head,再判断是否btnRemove*}
          {if $i==1}
            {*如果有特殊表头信息，需要加载特殊表头信息*}
            {if $firstColumn.head}
              {if $firstColumn.head.type}
                <th>{webcontrol type=$firstColumn.head.type title=$firstColumn.head.title url=$firstColumn.head.url}</th>
              {else}
                <th style='white-space:nowrap;'>{$firstColumn.head.title}</th>
              {/if}
            {elseif $item.type=='btBtnRemove'}{*不存在表头信息，处理默认的信息*}
              <th>{webcontrol type='btBtnAdd'}</th>
            {else}
              <th style='white-space:nowrap;'>{$item.title}</th>
            {/if}
          {else}
              <th style='white-space:nowrap;' {if $item.colmd>0}class="col-md-{$item.colmd}"{/if}>{$item.title}</th>
          {/if}  
        {/if}
        {/foreach}
        </tr>
      </thead>
      <tbody>
        {foreach from=$rowsSon item=item1 key=key1}
         <tr class='trRow'>
        {foreach from=$headSon item=item key=key}
          {if $item.type!='bthidden'}
            <td>{webcontrol 
            type=$item.type 
            itemName=$item.name
            action=$item.action  
            readonly=$item.readonly 
            disabled=$item.disabled 
            model=$item.model 
            options=$item.options 
            style=$item.style 

            url=$item.url 
            textFld=$item.textFld
            hiddenFld=$item.hiddenFld
            text=$item1[$key].text

            width=$item.width
            dialogWidth=$item.dialogWidth
            emptyText=$item.emptyText 
            inTable=$item.inTable|default:true 
            value=$item1[$key].value|default:$item.defaultValue
            kind=$item.kind  
            checked=$item1[$key].checked }</td>
            {else}
              {webcontrol 
              type=$item.type 
              value=$item1[$key].value|default:$item.defaultValue 
              itemName=$item.name 
              readonly=$item.readonly 
              disabled=$item.disabled}
          {/if}
        {/foreach}
        </tr>        
        {/foreach}
      </tbody>

    </table>
  </div>
</div>
</div>

{*导入其他编辑界面，有可能为多个*}
{if $otherInfoTpl}
  {if $otherInfoTpl|@is_string==1}
    {include file=$otherInfoTpl}
  {else}
    {foreach from=$otherInfoTpl item=other_file}
      {include file=$other_file}
    {/foreach}
  {/if}
{/if}

<div class="form-group">
  <div class="col-sm-offset-4 col-sm-8">
      <input class="btn btn-primary" type="submit" id="Submit" name="Submit" value=" 保 存 " onclick="$('#submitValue').val('保存')">
      {*其他一些功能按钮,*}
      {$other_button}
      <input class="btn btn-default" type="reset" id="Reset" name="Reset" value=" 重 置 ">
      <input type='hidden' name='submitValue' id='submitValue' value=''/>
      <input type='hidden' name='fromController' id='fromController' value='{$fromController|default:$smarty.get.controller}'/>
      <input type='hidden' name='flag' id='flag' value='{$flag|default:'&nbsp;'}'/>
 	  <input type='hidden' name='submitValue' id='submitValue' value=''/>
  </div>
</div>
<div style="clear:both;"></div>
</form>
{*通用的js代码放在_jsCommon中,主要是一些组件的效果*}
{include file='Main2Son/_jsCommon.tpl'}
{*下面是个性化的js代码,和特殊的业务逻辑挂钩,比如某些模板中自动合计的效果等*}
{if $sonTpl}
  {if $sonTpl|@is_string==1}
    {include file=$sonTpl}
  {else}
    {foreach from=$sonTpl item=js_item}
      {include file=$js_item}
    {/foreach}
  {/if}
{/if}
</body>
</html>