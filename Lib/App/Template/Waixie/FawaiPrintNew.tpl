<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>常州金马劲飞布业后整出库单</title>
{webcontrol type='LoadJsCss' src="Resource/Script/Print/LodopFuncs.js"}
<object id="LODOP" classid="clsid:2105C259-1E0C-4534-8141-A753534CB4CA" width=0 height=0>
    <embed id="LODOP_EM" type="application/x-print-lodop" width=0 height=0 pluginspage="install_lodop.exe"></embed>
</object>
<param name="CompanyName" value="常州易奇信息科技有限公司">
<param name="License" value="664717080837475919278901905623">
</object> 
<script language="javascript" src="Resource/Script/jquery.js"></script>


<script language="javascript" >
var WeighBarcode = '{$obj.WeighBarcode}';
{literal}
var LODOP;


function CheckIsInstall() { 
  try{
      LODOP=getLodop(document.getElementById('LODOP_OB'),document.getElementById('LODOP_EM'));
    if ((LODOP!=null)&&(typeof(LODOP.VERSION)!="undefined")) alert("本机已成功安装过Lodop控件!\n  版本号:"+LODOP.VERSION);
   }catch(err){
    alert("Error:本机未安装或需要升级!");
   }
}
// function PrintInstall(value){
//     var pagewidth=setmm(document.getElementById('pagewidth').value);
//     var pageheight=setmm(document.getElementById('pageheight').value);
//     var tablewidth=document.getElementById('tablewidth').value;
//     var tableheight=document.getElementById('tableheight').value;
//     var tableleft=(800-parseFloat(tablewidth))/2;
//     //alert(tableleft);
//     LODOP=getLodop(document.getElementById('LODOP_OB'),document.getElementById('LODOP_EM'));
//     LODOP.SET_PRINT_PAGESIZE(0,pagewidth,pageheight,'aa');
//   var head=document.getElementById('compName').innerHTML;
//     var main=document.getElementById('div1').innerHTML;
//     var after=document.getElementById('div3').innerHTML;
//   LODOP.ADD_PRINT_HTM(10,tableleft,tablewidth,tableheight,head);
//   LODOP.SET_PRINT_STYLEA(0,"ItemType",1);
//   LODOP.ADD_PRINT_BARCODE(10,tableleft,200,50,"Code39",WeighBarcode);
//   LODOP.SET_PRINT_STYLEA(0,"ItemType",1);
//   LODOP.ADD_PRINT_TABLE(115,tableleft,tablewidth,tableheight,main);
//   LODOP.ADD_PRINT_TABLE(0,0,tablewidth,tableheight,after);
//   LODOP.SET_PRINT_STYLEA(0,"LinkedItem",-1);   
//   LODOP.ADD_PRINT_TEXT(10,653,135,20,"第#页/共&页");
//   LODOP.SET_PRINT_STYLEA(0,"ItemType",2);
//   if(value){
//     LODOP.PREVIEW();
//   }else {
//     LODOP.print();
//   }
// }
  var LODOP; //声明为全局变量

  function PrintTable(){
    //加载打印控件
    LODOP=getLodop(document.getElementById('LODOP_OB'),document.getElementById('LODOP_EM'));
    //设置纸张  
    //LODOP.SET_PRINT_PAGESIZE(0,'203mm','290mm','A4')
    LODOP.PRINT_INIT("易奇科技报表打印");
    LODOP.SET_PRINT_PAGESIZE(2,'203mm','290mm','A4');
    //LODOP.SET_PRINT_PAGESIZE(0,'224mm','94mm','');
    //设置居中显示LODOP.ADD_PRINT_HTM(Top,Left,Width,Height,strHtml)
    //in(英寸)、cm(厘米) 、mm(毫米) 、pt(磅)、px(1/96英寸) 、%(百分比)，如"10mm"表示10毫米。
    LODOP.ADD_PRINT_HTM("0%", "0%", "100%", "100%", document.getElementById("printBox").innerHTML);
    LODOP.SET_PRINT_STYLEA(0,"Horient",2);
    //LODOP.ADD_PRINT_BARCODE(40,30,151,39,"",$('#barcode').attr('data-barcode'));
    //预览
    //LODOP.PRINT_DESIGN();
    LODOP.PREVIEW();   
    var urls = "?controller=Shengchan_Waixie_FawaiNew&action=right";
    window.location.href=urls;
  };
function setmm(value){
  return value+'mm';
}
function mm2px(i) {
  return parseInt((i*96/25.4).toFixed(0));
}

function prnbutt_onclick() 
{ 
window.print(); 
return true; 
} 

function window_onbeforeprint() 
{ 
prn.style.visibility ="hidden"; 
//prn1.style.visibility ="hidden"; 
return true; 
} 

function window_onafterprint() 
{ 
prn.style.visibility = "visible"; 
return true; 
} 

function PrintView(){

}
function loadPrint(){
    var auto={/literal}{$smarty.get.auto|@json_encode}{literal};
    if(auto==1)document.getElementById('button').click();
  }

{/literal}
</script>

  

</head>
<body onload="loadPrint()">
<div id="printBox" style=" width:100%; margin:0 auto; position:relative;">
<table width="100%" align="center" style="margin-top:0px" >
   
       
  
    <tr>
      <td colspan="12" style="text-align: center;">  
      <div id="compName">
          <style type="text/css">
          {literal}
        #divPrint { position:fixed; bottom:10px; right:250px;}
        #divPrint input {  background-color: #0362fd !important; border:0px; color: #fff !important; font-family: "Segoe UI",Helvetica,Arial,sans-serif;cursor: pointer; outline: medium none; font-size:14px; padding: 7px 14px;}
        td{font-size:15px;}
        td span{font-weight:bold;}
        .haveBorder{border:1px solid #000;}
        .haveBorder td {border-bottom:1px solid #000; border-right:1px solid #000; border-collapse:collapse;}
        .caption{font-size:25px; font-weight:bold;}
        #topTable td{font-size:14px;}
        .tr {height: 35px;}
        .tr td{font-size: 16px;white-space:nowrap;}
      {/literal}
      </style>
       <span style="font-weight: bold;font-size: 18px;">常州金马劲飞布业后整出库单</span>

       </td>
    </tr>
    <tr>
      <td style="width: 30%">出库时间：{$obj.rukuDate}</td>
      <td style="width: 30%">客户：{$obj.compName}</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
    <td colspan="3">
        <div id="div1">

        <style type="text/css">
        {literal}
      td{font-size:14px;}
      td span{font-weight:bold;}
      .haveBorder{border:1px solid #000;}
      .haveBorder td {border-bottom:1px solid #000; border-right:1px solid #000; border-collapse:collapse;}
      .caption{font-size:14px; font-weight:bold;}
      #topTable td{font-size:14px;}
      .tr {height: 26px;}
      .tr td{font-size: 16px; text-overflow:ellipsis; white-space:nowrap;}
    {/literal}
    </style>

    <table width="100%" class="haveBorder" cellpadding="2px" cellspacing="0" align='center' >
    <thead class="th tr">
      <td width="6%" align="center">订单号</td>
      <td width="10%" align="center">客户</td>
      <td width="11%" align="center">品名</td>
      <td width="15%" align="center">规格</td>
      <td width="6%" align="center">颜色</td>
      <td width="6%" align="center">门幅</td>
      <td width="6%" align="center">克重</td>
      <td width="6%" align="center">件数</td>
      <td width="8%" align="center">重量</td>
      {if $ganghao}
         <td width="8%" align="center">缸号</td>
      {/if}
      {if $zhiJiCode}
         <td width="7%" align="center">机号</td>
      {/if}
      {if $gongxuName}
         <td width="8%" align="center">工序</td>
      {/if}
    </thead>
    {foreach from=$obj.Son item=item}
    <tr class='tr'>
      <td align="center" >{$item.orderCode|default:'&nbsp;'}</td>
      <td align="center" >{$item.codeAtOrder|default:'&nbsp;'}</td>   
        <td align="center">{$item.proName|default:'&nbsp;'}</td>
      <td align="center">{$item.guige|default:'&nbsp;'}</td>
      <td align="center">{$item.color|default:'&nbsp;'}</td>
      <td align="center">{$item.menfu|default:'&nbsp;'}</td>
      <td align="center">{$item.kezhong|default:'&nbsp;'}</td>
      <td align="center">{$item.cntJianAll|default:'&nbsp;'}</td>
      <td align="center">{$item.cntAll|default:'&nbsp;'}</td>
      {if $ganghao}
         <td align="center">{$item.ganghao|default:'&nbsp;'}</td>
      {/if}
      {if $zhiJiCode}
         <td align="center">{$item.zhiJiCode|default:'&nbsp;'}</td>
      {/if}
      {if $gongxuName}
         <td align="center">{$item.gongxuName|default:'&nbsp;'}</td>
      {/if}
    </tr>       
    {/foreach}
        </table>
                </div>
      </td>
    </tr>
    
</table>
</div>
<div id="divPrint" class="wrap">
    <input type="button" name="button" id="button" value="打印" onclick="PrintTable()"/>
</div>
</body>
</html>