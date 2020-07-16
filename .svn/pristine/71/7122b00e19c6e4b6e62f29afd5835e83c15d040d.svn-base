<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title>打印出库单</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  {webcontrol type='LoadJsCss' src="Resource/Script/jquery.js"}
  {webcontrol type='LoadJsCss' src="Resource/Script/Print/LodopFuncs.js"}
  <object id="LODOP_OB" classid="clsid:2105C259-1E0C-4534-8141-A753534CB4CA" width=0 height=0> 
    <embed id="LODOP_EM" type="application/x-print-lodop" width=0 height=0 pluginspage="install_lodop.exe"></embed>
    <param name="CompanyName" value="常州易奇信息科技有限公司">
    <param name="License" value="664717080837475919278901905623">
  </object> 
</head>

<script type="text/javascript">
var controller = '{$smarty.get.controller}';
var id = '{$smarty.get.id}';
{literal}
  $(function(){
    $('#button').click(function(){
      var url="?controller="+controller+"&action=UpdatePrint";
      var param={'id':id};
      $.getJSON(url,param,function(data){

      });
    });
  });

  var LODOP; //声明为全局变量
  function PrintTable(){
    //加载打印控件
    LODOP=getLodop(document.getElementById('LODOP_OB'),document.getElementById('LODOP_EM'));
    //设置纸张  
    //LODOP.SET_PRINT_PAGESIZE(0,'203mm','290mm','A4')
    LODOP.PRINT_INIT("易奇科技报表打印");
    //设置居中显示LODOP.ADD_PRINT_HTM(Top,Left,Width,Height,strHtml)
    //in(英寸)、cm(厘米) 、mm(毫米) 、pt(磅)、px(1/96英寸) 、%(百分比)，如"10mm"表示10毫米。
    LODOP.ADD_PRINT_HTM("0%", "0%", "90%", "100%", document.getElementById("printBox").innerHTML);
    LODOP.SET_PRINT_STYLEA(0,"Horient",2);
    // LODOP.ADD_PRINT_BARCODE(100,27,151,39,"",$('#tiaoma').val());
    //预览
    // LODOP.PRINT_DESIGN();
    LODOP.PREVIEW();    
  };
  function loadPrint(){
    var auto={/literal}{$smarty.get.auto|@json_encode}{literal};
    if(auto==1)document.getElementById('button').click();
  }
</script>
{/literal}
<body onload="loadPrint()">
<div id="printBox" style=" width:720px; margin:0 auto; position:relative;">
{*以下为lodop载入时需要css,不能放到head中*}
{literal}
<style>
/*打印按钮样式*/
#divPrint { position:fixed; bottom:10px; right:250px;}
#divPrint input {  background-color: #0362fd !important; border:0px; color: #fff !important; font-family: "Segoe UI",Helvetica,Arial,sans-serif;cursor: pointer; outline: medium none; font-size:14px; padding: 7px 14px;}
.tablec tr td{
   border-bottom:1px solid #000;
   border-right:1px solid #000;
   height:45px;
   font-size: 18px;
   padding:0px 5px 0px 5px;   
   /*text-align: center;*/
}
.top td{
  border-top:1px solid #000;
  text-align: center;
}
.left{
  border-left:1px solid #000;
  text-align: center;
}
</style>
{/literal}
<table width="710px"  border="0" align="center" cellpadding="0" cellspacing="0" >
    <tr>
      <td width="20%">&nbsp;</td>
      <td width="50%" align="center"><strong style="font-size:22px;">常州市金马劲飞布业有限公司<br/>筒子染色计划单</strong></td>
      <td width="30%">&nbsp;</td>
    </tr>
    <tr>
      <td width="20%">&nbsp;</td>
      <td width="50%" align="center"></td>
      <td width="30%">&nbsp;</td>
    </tr>
    <tr>
      <td width="20%">&nbsp;</td>
      <td width="50%" align="center"></td>
      <td width="30%">&nbsp;</td>
    </tr>
  </table>

    <table  class="tablec"  align="center" cellpadding="0" cellspacing="0" >
        <tr class="top">
            <td width="100" class="left">计划日期</td>
            <td colspan="2"  align="center">{$rowset.chukuDate|default:'&nbsp;'}</td>
            <td width="100" >纱库位</td>
            <td colspan="2" >{$rowset.kuwei|default:'&nbsp;'}</td>
            <td width="100">染色计划单号</td>
            <td width="100">{$rowset.chukuCode|default:'&nbsp;'}</td>
        </tr>
   

        <tr>
          <td height="19" colspan="9" valign="left" class="left" style="text-align: left;padding-bottom: 300px;">贴布样，纱样区：&nbsp;</td>
        </tr>
        <tr>
            <td width="120" valign="center" height="50" class="left">品名</td>
            <td width="84" valign="center" height="50">纱支规格</td>
            <td width="84" valign="center" height="50">成分</td>
            <td width="80" valign="center"  height="50" colspan="2">缸号（批号）</td>
            <td width="94" valign="center"  height="50">投料数量（kg）</td>
            <td width="92" valign="center"  height="50">颜色</td>
            <td width="171" valign="center"  height="50">备注</td>
        </tr>
        {foreach from=$rowset.Products item=item} 
        <tr>
            <td width="120" valign="center" class="left">{$item.proName}</td>
            <td width="84" valign="center">{$item.guige}</td>
            <td width="84" valign="center">{$item.chengFen}</td>
            <td width="80" valign="center" colspan="2">{$item.ganghao}</td>
            <td width="94" valign="center">{$item.cnt}</td>
            <td width="92" valign="center">{$item.color}</td>
            <td width="171" valign="center">{$item.memo}</td>
        </tr>
        {/foreach}
        <tr>
            <td width="120" class="left"  align="center">合计</td>
            <td width="94" valign="center"></td>
            <td width="92" valign="center"></td>
            <td width="171" valign="center" colspan="2"></td>
            <td width="92" valign="center">{$heji.cnt|default:'&nbsp;'}</td>
            <td width="92" valign="center"></td>
            <td width="92" valign="center"></td>
        </tr>
    </table>
    <table border="0px" width="710px">
        <tr>
            <td width="92" valign="center"></td>
            <td width="92" valign="center"></td>
            <td width="92" valign="center"></td>
        </tr>
        <br/>
        <br/>
        <tr>
            <td align="left" width="20%">本厂跟单员:<font style="padding-left:15px; padding-right:15px;">{$rowset.0.people|default:'&nbsp;'}</font></td>
            <td width="35%" align="center">本厂主管：{$rowset.0.time|default:'&nbsp;'}</td>
            <td width="45%" align="left" style="padding-right:30px;">染厂签收人<font style="border-bottom: 1px dotted #000; ">   </font></td>
        </tr>
    </table>
</div>
<!-- <input type="button" id="btn_print" value='确定并打印 (打印后表示发货成功)' onclick="PrintTable()"> -->
<!-- 打印按钮 -->
<div id="divPrint" class="wrap">
    <input type="button" name="button" id="button" value="打印" onclick="PrintTable()"/>
</div>
</body>
</html>