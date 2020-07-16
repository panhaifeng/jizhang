{*
注意:
proKind为产品弹出选择控件的必须参考的元素,特里特个性化需求,出了订单登记界面外其他使用产品弹出选择控件的模板必须制定proKind为hidden控件
*}<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$title}</title>
</head>

<script language="javascript" src="Resource/Script/Calendar/WdatePicker.js"></script>
<script language="javascript" src="Resource/bootstrap/bootstrap3.0.3/js/jquery.min.js"></script>
<link href="Resource/Css/validate.css" type="text/css" rel="stylesheet">
<script language="javascript" src="Resource/Script/jquery.validate.js"></script>
<!-- Bootstrap -->
<link rel="stylesheet" href="Resource/bootstrap/bootstrap3.0.3/css/bootstrap.css">
<script src="Resource/bootstrap/bootstrap3.0.3/js/bootstrap.js"></script>
<script src="Resource/bootstrap/bootstrap3.0.3/js/tooltip.js"></script>
<script language="javascript">
var _removeUrl='?controller={$smarty.get.controller}&action=RemoveByAjax';
var _rules = {$rules|@json_encode};
{literal}
$.extend({
  getUrlVars: function(){
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
      hash = hashes[i].split('=');
      vars.push(hash[0]);
      vars[hash[0]] = hash[1];
    }
    return vars;
  },
  getUrlVar: function(name){
    return $.getUrlVars()[name];
  }
});



</script>
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
</style>
{/literal}
<body>

<form name="form1" id="form1" class="form-horizontal" action="{url controller=$smarty.get.controller action='save'}" method="post">

<!-- 主表字段登记区域 -->
<div class="panel panel-info">
  <div class="panel-heading"><h3 class="panel-title" style="text-align:left;">{$areaMain.title}</h3></div>
  <div class="panel-body">
    <div class="row">
      {foreach from=$areaMain.fld item=item key=key}
      {include file="Main2Son/"|cat:$item.type|cat:".tpl"}
      {/foreach}
    </div>
  </div>
</div>
<div style="">

  <div class="table-responsive" style="width:{$tbl_son_width|default:"100%"};">
    <table class="table table-condensed table-striped">
      <thead>
        <tr>
          {foreach from=$headSon item=item key=key}
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
        {foreach from=$rowsSon item=item1 key=key1}
        <tr class='trRow'>
          {foreach from=$headSon item=item key=key}
            {if $item.type!='bthidden'}
            <td>{webcontrol type=$item.type value=$item1[$key].value itemName=$item.name readonly=$item.readonly disabled=$item.disabled options=$item.options}</td>
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
{if $otherInfoTpl!=''}
{include file=$otherInfoTpl}
{/if}
<div class="panel panel-info">
  <div class="panel-heading"><h3 class="panel-title" style="text-align:left;">{$arr_item1.title}</h3></div>
  <div class="panel-body">
    <div class="row">      
      {foreach from=$arr_item1.fld item=item key=key}
      {include file="Main2Son/"|cat:$item.type|cat:".tpl"}      
      {/foreach}
    </div>       
  </div>
</div>

<div class="panel panel-info">
  <div class="panel-heading"><h3 class="panel-title" style="text-align:left;">{$arr_item2.title}</h3></div>
  <div class="panel-body">
    <div class="row">      
      {foreach from=$arr_item2.fld item=item key=key}
      {include file="Main2Son/"|cat:$item.type|cat:".tpl"}      
      {/foreach}
    </div>       
  </div>
</div>

<div class="panel panel-info">
  <div class="panel-heading"><h3 class="panel-title" style="text-align:left;">{$arr_item3.title}</h3></div>
  <div class="panel-body">
    <div class="row">      
      {foreach from=$arr_item3.fld item=item key=key}
      {include file="Main2Son/"|cat:$item.type|cat:".tpl"}      
      {/foreach}
    </div>       
  </div>
</div>

<div class="form-group">
  <div class="col-sm-offset-4 col-sm-8">
      <input class="btn btn-primary" type="submit" id="Submit" name="Submit" value=" 保 存 ">
    <!-- <input class="btn btn-default" type="submit" id="Submit" name="Submit" value="保存并新增"> -->
      <input class="btn btn-default" type="reset" id="Reset" name="Reset" value=" 重 置 ">
  </div>
</div>
<div style="clear:both;"></div>
</form>
{include file='Main2Son/_jsCommon.tpl'}
{if $sonTpl}{include file=$sonTpl}{/if}
</body>
</html>