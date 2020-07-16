<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$title}</title>
{webcontrol type='LoadJsCss' src="Resource/Script/Print/LodopFuncs.js"}
<object id="LODOP_OB" classid="clsid:2105C259-1E0C-4534-8141-A753534CB4CA" width=0 height=0> 
<embed id="LODOP_EM" type="application/x-print-lodop" width=0 height=0 pluginspage="install_lodop.exe"></embed>
<param name="CompanyName" value="常州易奇信息科技有限公司">
<param name="License" value="664717080837475919278901905623">
</object> 
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.validate.js"}
<link href="Resource/Css/Print.css" type="text/css" rel="stylesheet" />
{literal}
<script language="javascript">
	//传递的参数处理
	var LODOP;
	var obj ={/literal}{$row|@json_encode}{literal};
	function prn1_preview() {		
		CreatePage();	
		LODOP.PREVIEW();
		// LODOP.PRINT_design(); 	
	};
	
	function CreatePage(){
		LODOP=getLodop(document.getElementById('LODOP_OB'),document.getElementById('LODOP_EM'));  

		LODOP.PRINT_INITA(0,0,522,333,"");
		LODOP.SET_PRINT_PAGESIZE(1,900,550,"条码打印");
		LODOP.ADD_PRINT_RECT(5,5,330,200,0,1);
		LODOP.ADD_PRINT_RECT(31,5,330,25,0,1);
		LODOP.ADD_PRINT_RECT(81,5,330,25,0,1);
		LODOP.ADD_PRINT_RECT(105,5,330,25,0,1);
		LODOP.ADD_PRINT_LINE(6,120,129,121,0,1);
		LODOP.ADD_PRINT_TEXT(10,8,109,23,"ART.NO/编号");
		LODOP.ADD_PRINT_TEXT(10,123,208,23,obj.proCode);
		LODOP.SET_PRINT_STYLEA(0,"Bold",1);
		LODOP.ADD_PRINT_TEXT(34,8,130,23,"Specification/规格");
		LODOP.ADD_PRINT_TEXT(35,123,229,23,obj.guige);
		LODOP.SET_PRINT_STYLEA(0,"Bold",1);
		LODOP.ADD_PRINT_TEXT(60,7,121,23,"COMPOSITION/成分");
		LODOP.ADD_PRINT_TEXT(60,123,217,23,obj.chengfen);
		LODOP.SET_PRINT_STYLEA(0,"Bold",1);
		LODOP.ADD_PRINT_TEXT(85,7,114,25,"DENSITY/经纬密");
		LODOP.ADD_PRINT_TEXT(86,123,209,23,obj.jingwei);
		LODOP.SET_PRINT_STYLEA(0,"Bold",1);
		LODOP.ADD_PRINT_TEXT(109,7,107,23,"WIDTH/门幅");
		LODOP.ADD_PRINT_TEXT(109,123,208,23,obj.menfu);
		LODOP.SET_PRINT_STYLEA(0,"FontSize",10);
		LODOP.SET_PRINT_STYLEA(0,"Bold",1);
		LODOP.ADD_PRINT_BARCODE(135,18,284,65,"Code39",obj.proCode);

	};	
	
	
</script> 

<style type="text/css">
table tr{height:40px;}
.title{font-weight:bold; text-align:right;}
</style>
{/literal}
</head>

<body onLoad="prn1_preview();window.location.href=('?controller=Sample_Yangpin&action=right')">
<table width="800"  border="0" align="center" cellpadding="0" cellspacing="0">
     <tr align="center">
       <td height="25" style="font-size:24px; font-weight:bold;">
  {webcontrol type='GetAppInf' varName='compName'}-条码打印</td>
     </tr>
      <tr>
       <td height="100" valign="middle" align="center"><font size="+1"><b>{$row.proCode|default:'&nbsp;'}</b> </font></td>
     </tr>
     <tr align="center" bgcolor="#FFFFFF">
       <td height="25">&nbsp;</td>
     </tr>
  </table>
<!--
<div style="font-size:12px;" align="center">
常州创盈（专业开发筒染、色织系统）联系方式：0519-86339029
</div>-->
<div id="prn" align="center">
 <input type="button" value="打印预览" onClick="prn1_preview()">
</div>
</body>
</html>
