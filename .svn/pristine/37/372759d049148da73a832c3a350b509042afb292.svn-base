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
<script language="javascript" type="text/javascript" src="Resource/Script/jquery.1.9.1.js?v=1419837077"></script>
<script language="javascript" type="text/javascript" src="Resource/bootstrap/bootstrap3.0.3/js/bootstrap.js?v=1419837090"></script>
<script language="javascript" type="text/javascript" src="Resource/Script/Calendar/WdatePicker.js?v=1419837085"></script>

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

<script language="javascript">
   
$(function(){
    //坯布
    $('.glyphicon-plus','#table_main').bind('click',function(){
        var Trmain = $(this).parents('.trRow').clone(true);
        $('[name="pbPlan[]"]',Trmain).val('');
        $('[name="pbId[]"]',Trmain).val('');
        $('.input-group-addon',Trmain).remove();
        addRow(Trmain,'#table_main');
    });
    $('#table_main').on('click','.glyphicon-minus',function(){
        var elm = $(this).parents('.trRow');
        var id=$('[name="pbId[]"]',elm).val();
        deleteId(id,elm);
    });
    //成品
    $('.glyphicon-plus','#table_cp').click(function(){
        var Trcp = $(this).parents('.trRow').clone(true);
        $('[name="cpPlan[]"]',Trcp).val('');
        $('[name="cpId[]"]',Trcp).val('');
        addRow(Trcp,'#table_cp');
    });
    $('#table_cp').on('click','.glyphicon-minus',function(){
        var elm = $(this).parents('.trRow');
        var id=$('[name="cpId[]"]',elm).val();
        deleteId(id,elm);
    });
    //测缩
    $('.glyphicon-plus','#table_sj').click(function(){
        var Trsj = $(this).parents('.trRow').clone(true);
        $('[name="sjPlan[]"]',Trsj).val('');
        addRow(Trsj,'#table_sj');
    });
    $('#table_sj').on('click','.glyphicon-minus',function(){
        var elm = $(this).parents('.trRow');
        var id=$('[name="sjId[]"]',elm).val();
        deleteId(id,elm);
    });
   
});
function deleteId(id,tr){
    var url="?controller=Shengchan_Huitian&action=RemoveByAjax";
     if(!id) {
          tr.remove();
          return;
		  }
    if(!confirm('此删除不可恢复，你确认吗?')) return;
    var param={'id':id};
    $.post(url,param,function(json){
      if(!json.success) {
        alert('出错');
        return;
      }
      tr.remove();
    },'json');
    return;
}
//增加行
function addRow(elm,id){
    $('.add',elm).replaceWith("<span class='glyphicon glyphicon-minus' style='font-size:18px;cursor:pointer;'></span>");
    $(id).append(elm);
}
</script>
{/literal}
<body>

<form name="form1" id="form1" class="form-horizontal" action="{url controller=$smarty.get.controller action=$action_save|default:'save'}" method="post" enctype="multipart/form-data">

<!-- 主表字段登记区域 -->
<div class="panel panel-info">
  <div class="panel-heading"><h3 class="panel-title" style="text-align:left;">生产数据回填：由各环节负责人，将生产中产生的实际工艺参数回填</h3></div>
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
  <!-- 标签 -->
<ul class="nav nav-tabs" role="tablist" id="myTab">
  <li role="presentation" class="active"><a href="#home" role="tab" data-toggle="tab">坯布数据：织造负责人填写</a></li>
  <li role="presentation"><a href="#profile" role="tab" data-toggle="tab">成品：成品负责人填写</a></li>
  <li role="presentation"><a href="#messages" role="tab" data-toggle="tab">测缩：相关负责人填写</a></li>
</ul>
    <!-- 分页标签内容 -->
<div class="tab-content">
  <div role="tabpanel" class="tab-pane active" id="home">
    <div style='width:500px'>
        <div class="form-group">
            <label class="col-sm-2 control-label lableMain lableMain" for="compCode">门幅cm:</label>
            <div class="col-sm-7">
                <input id="menfu_bu" class="form-control" type="text" value="{$ret.menfu_bu}" name="menfu_bu">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label lableMain lableMain" for="compCode">克重g/m2:</label>
            <div class="col-sm-7">
                <input id="kezhong_bu" class="form-control" type="text" value="{$ret.kezhong_bu}" name="kezhong_bu">
            </div>
        </div>
     </div>
      <table style='width:700px' class="table table-hover" id="table_main">
            <tr>
              <th>坯检日期</th>
              <th>上传图片</th>
              <th>操作</th>
            </tr>
          {foreach from=$Row.pb item=item key=key}
            <tr class="trRow" >
                <td><input type='text' class='form-control' onClick="calendarUntilNow()" name="pbDate[]" value="{$item.huiDate|default:$smarty.now|date_format:'%Y-%m-%d'}"/>
                    <input type="hidden" name="pbId[]" value="{$item.id}"/></td>
                <td><div class="col-sm-9">
                        <div class="input-group input-group-sm">
                            <input id="pbPlan[]" class="form-control" type="file" name="pbPlan[]">
                            {$item.spic}
                        </div>
                    </div></td>
                <td class="add"><span class="{if $key==0}glyphicon glyphicon-plus {else}glyphicon glyphicon-minus{/if}" style="font-size:18px;cursor:pointer;" ></span></td>
            </tr>
          {/foreach}
      </table>
  </div>
  <div role="tabpanel" class="tab-pane" id="profile">
      <div style='width:500px'>
          <div class="form-group">
            <label class="col-sm-2 control-label lableMain lableMain" for="compCode">门幅cm:</label>
            <div class="col-sm-7">
                <input id="menfu_cp" class="form-control" type="text" value="{$ret.menfu_cp}" name="menfu_cp">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label lableMain lableMain" for="compCode">克重g/m2:</label>
            <div class="col-sm-7">
                <input id="kezhong_cp" class="form-control" type="text" value="{$ret.kezhong_cp}" name="kezhong_cp">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label lableMain lableMain" for="compCode">打卷门幅:</label>
            <div class="col-sm-7">
                <input id="damenfu" class="form-control" type="text" value="{$ret.damenfu}" name="damenfu">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label lableMain lableMain" for="compCode">打卷克重:</label>
            <div class="col-sm-7">
                <input id="dakezhong" class="form-control" type="text" value="{$ret.dakezhong}" name="dakezhong">
            </div>
        </div>
     </div>
      <table style='width:700px' class="table table-hover" id="table_cp">
            <tr>
              <th>成品质检日期</th>
              <th>上传图片</th>
              <th>操作</th>
            </tr>
          {foreach from=$Row.cp item=item key=key}
            <tr class="trRow" >
                <td><input type='text' class='form-control' onClick="calendarUntilNow()" name="cpDate[]" value="{$item.huiDate|default:$smarty.now|date_format:'%Y-%m-%d'}"/>
                    <input type="hidden" name="cpId[]" value="{$item.id}"/></td>
                <td><div class="col-sm-9">
                        <div class="input-group input-group-sm">
                            <input id="cpPlan[]" class="form-control" type="file" name="cpPlan[]">
                            {$item.spic}
                        </div>
                    </div></td>
                <td class="add"><span class="{if $key==0}glyphicon glyphicon-plus {else}glyphicon glyphicon-minus{/if}" style="font-size:18px;cursor:pointer;"></span></td>
            </tr>
          {/foreach}
      </table>
  </div>
  <div role="tabpanel" class="tab-pane" id="messages">
    <div style='width:500px'>
    <div class="form-group">
        <label class="col-sm-2 control-label lableMain lableMain" for="compCode">门幅cm:</label>
        <div class="col-sm-7">
            <input id="menfu_cs" class="form-control" type="text" value="{$ret.menfu_cs}" name="menfu_cs">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label lableMain lableMain" for="compCode">克重g/m2:</label>
        <div class="col-sm-7">
            <input id="kezhong_cs" class="form-control" type="text" value="{$ret.kezhong_cs}" name="kezhong_cs">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label lableMain lableMain" for="compCode">经向缩率:</label>
        <div class="col-sm-7">
            <input id="jxsuolv" class="form-control" type="text" value="{$ret.jxsuolv}" name="jxsuolv">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label lableMain lableMain" for="compCode">纬向缩率:</label>
        <div class="col-sm-7">
            <input id="wxsuolv" class="form-control" type="text" value="{$ret.wxsuolv}" name="wxsuolv">
        </div>
    </div>
 </div>
  <table style='width:700px' class="table table-hover" id="table_sj">
        <tr>
          <th>缩检日期</th>
          <th>上传图片</th>
          <th>操作</th>
        </tr>
      {foreach from=$Row.sj item=item key=key}
        <tr class="trRow" >
            <td><input type='text' class='form-control' onClick="calendarUntilNow()" name="sjDate[]" value="{$item.huiDate|default:$smarty.now|date_format:'%Y-%m-%d'}"/>
                <input type="hidden" name="sjId[]" value="{$item.id}"/></td>
            <td><div class="col-sm-9">
                        <div class="input-group input-group-sm">
                            <input id="sjPlan[]" class="form-control" type="file" name="sjPlan[]">
                            {$item.spic}
                        </div>
                    </div></td>
            <td class="add"><span class="{if $key==0}glyphicon glyphicon-plus {else}glyphicon glyphicon-minus{/if}" style="font-size:18px;cursor:pointer;"></span></td>
        </tr>
      {/foreach}
  </table>
 </div>
</div>
<div class="text-center btnSubmit">
    <input id="Submit" class="btn btn-primary" type="submit" value=" 保 存 " name="Submit">
    <input id="Reset" class="btn btn-danger" type="reset" value=" 重 置 " name="Reset">
</div>
</form>

</body>
</html>