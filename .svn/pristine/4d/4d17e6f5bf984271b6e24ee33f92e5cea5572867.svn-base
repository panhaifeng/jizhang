{*
次模板为单一界面的通用模板，主要在基础档案中应用或其他单一表的编辑时使用
*}<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$title}</title>
</head>
<link href="Resource/Css/validate.css" type="text/css" rel="stylesheet">
<link rel="stylesheet" href="Resource/bootstrap/bootstrap3.0.3/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="Resource/Css/clientAuto.css" />
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
</style>
{/literal}
<body>
<div class='container'>
  <form name="form1" id="form1" class="form-horizontal" action="{url controller=$smarty.get.controller action=$form.action|default:'save'}" method="post">


  <!-- 主表字段登记区域 -->
  <div class="panel panel-info">
    <div class="panel-heading"><h3 class="panel-title" style="text-align:left;">{$title}</h3></div>
    <div class="panel-body">
      <div class="row">
        {foreach from=$fldMain item=item key=key}
        {include file="Main/"|cat:$item.type|cat:".tpl"}
        {/foreach}
      </div>
    </div>
  </div>

  {if $otherInfoTpl!=''}
  {include file=$otherInfoTpl}
  {/if}
  <div class="form-group">
    <div class="col-sm-offset-4 col-sm-8">
    {if $flag==1}
      <input type="button" name="Back" value='返回' onClick="window.location.href='{url controller=$smarty.get.controller action='Right'}'" class="btn btn-info">
    {else}
    <input class="btn btn-primary" type="submit" id="Submit" name="Submit" value=" 保 存 " onclick="$('#submitValue').val('保存')">
      {*其他一些功能按钮,*}
      {$other_button}
      <input class="btn btn-default" type="reset" id="Reset" name="Reset" value=" 重 置 ">
    {/if}
    </div>
  </div>
  <div style="clear:both;"></div>
  <input type='hidden' name='fromAction' value='{$smarty.get.fromAction|default:"right"}' />
  </form>
</div>
<div id="divModel" class="s-isindex-wrap s-model-set-menu menu-top" style="display:none;"> 
	<div><input type="text" class="supplyText" id="supplyText"/></div>
</div>
{*通用的js代码放在_jsCommon中,主要是一些组件的效果*}
{include file='Main2Son/_jsCommon.tpl'}
{*下面是个性化的js代码,和特殊的业务逻辑挂钩,比如某些模板中自动合计的效果等*}
{if $sonTpl}{include file=$sonTpl}{/if}
</body>
</html>