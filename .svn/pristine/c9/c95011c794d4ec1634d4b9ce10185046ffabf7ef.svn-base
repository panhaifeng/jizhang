<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>条码打印</title>
<link href="Resource/Css/Print.css" type="text/css" rel="stylesheet" />
{literal}
<!-- <script language="javascript" src="Resource/Script/Print/LodopFuncs.js"></script> -->
<script language="javascript" src="Resource/Script/Print/2017.01.04/LodopFuncs.js"></script>
<object id="LODOP" classid="clsid:2105C259-1E0C-4534-8141-A753534CB4CA" width=0 height=0>
    <param name="CompanyName" value="常州易奇信息科技有限公司">
    <param name="License" value="664717080837475919278901905623">
    <embed id="LODOP_EM" type="application/x-print-lodop" width=0 height=0 pluginspage="install_lodop.exe"></embed>
</object>

<script language="javascript" src="Resource/Script/Common.js"></script>
<script language="javascript" src="Resource/Script/jquery.js"></script>
<script language="javascript" src="Resource/Script/jquery.validate.js"></script>
<script language="javascript">
$(function(){
	// prn1_preview();
	setTimeout(prn1_preview,1000);
})

	//传递的参数处理
	var obj ={/literal}{$aRow|@json_encode}{literal};
	
	var pre = function() {
        CreateOneFormPage(obj);  
        LODOP.PREVIEW();
        //LODOP.PRINT_DESIGN(); 
    }; 
	
	 var des = function() {
        CreateOneFormPage(obj);  
        //LODOP.PREVIEW();
        LODOP.PRINT_DESIGN(); 
    };     

	function prn1_preview() {
		CreateOneFormPage(obj);
		// LODOP.PRINT_DESIGN();return false;
		LODOP.PREVIEW();//return false;
		// window.close();
	};

	var LODOP;
	function CreateOneFormPage(obj){
		// LODOP=getLodop(document.getElementById('LODOP'),document.getElementById('LODOP_EM'));
        LODOP=getLodop(); 
        LODOP.ADD_PRINT_HTM("20","0.15cm","RightMargin:0.1cm","BottomMargin:20mm");
        LODOP.PRINT_INITA(0,0,800,800,"");
	    var i=0;
		for(var j=0, iMax = obj.length;j<iMax;j++){
			LODOP.NewPage();
            LODOP.SET_PRINT_PAGESIZE(1,"6cm","11cm","条码打印");
            
			var tPos=i*0;
			LODOP.ADD_PRINT_TEXT(21,18,208,30,"         水洗标");
			LODOP.SET_PRINT_STYLEA(0,"FontSize",12);
			LODOP.ADD_PRINT_TEXT(52+tPos,20,85,31,"合同号:");
			LODOP.SET_PRINT_STYLEA(0,"FontSize",10);
			LODOP.ADD_PRINT_TEXT(52+tPos,80,152,30,obj[j]['orderCode']);
			LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
			LODOP.ADD_PRINT_TEXT(69+tPos,20,85,27,"品名:");
			LODOP.SET_PRINT_STYLEA(0,"FontSize",10);
			LODOP.ADD_PRINT_TEXT(69+tPos,59,173,25,obj[j]['proName']);
			LODOP.SET_PRINT_STYLEA(0,"FontSize",10);
			LODOP.ADD_PRINT_TEXT(96+tPos,20,93,25,"克重:"+obj[j]['kezhong']);
			LODOP.SET_PRINT_STYLEA(0,"FontSize",10);
			LODOP.ADD_PRINT_TEXT(96+tPos,110,123,25,"门幅:"+obj[j]['menfu']);
			LODOP.SET_PRINT_STYLEA(0,"FontSize",10);
			LODOP.ADD_PRINT_TEXT(119+tPos,21,214,24,"纱支:"+obj[j]['guige']);
			LODOP.SET_PRINT_STYLEA(0,"FontSize",10);
			LODOP.ADD_PRINT_TEXT(149+tPos,20,85,24,"件号:");
			LODOP.SET_PRINT_STYLEA(0,"FontSize",10);
			LODOP.ADD_PRINT_TEXT(148+tPos,60,174,25,"                   ");
			LODOP.SET_PRINT_STYLEA(0,"FontSize",10);
			LODOP.SET_PRINT_STYLEA(0,"Underline",1);
			LODOP.ADD_PRINT_TEXT(174+tPos,20,84,28,"机台:");
			LODOP.SET_PRINT_STYLEA(0,"FontSize",10);
			LODOP.ADD_PRINT_TEXT(176+tPos,60,145,25,"                   ");
			LODOP.SET_PRINT_STYLEA(0,"FontSize",10);
			LODOP.SET_PRINT_STYLEA(0,"Underline",1);
			LODOP.ADD_PRINT_TEXT(202+tPos,20,82,21,"缸号:");
			LODOP.SET_PRINT_STYLEA(0,"FontSize",10);
			LODOP.ADD_PRINT_TEXT(203+tPos,61,180,19,"                   ");
			LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
			LODOP.SET_PRINT_STYLEA(0,"Underline",1);
			LODOP.ADD_PRINT_TEXT(252+tPos,18,49,24,"重量:");
			LODOP.SET_PRINT_STYLEA(0,"FontSize",10);
			LODOP.ADD_PRINT_TEXT(224+tPos,19,51,24,"姓名:");
			LODOP.SET_PRINT_STYLEA(0,"FontSize",10);
			LODOP.ADD_PRINT_TEXT(224+tPos,59,180,25,"                   ");
			LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
			LODOP.SET_PRINT_STYLEA(0,"Underline",1);
			LODOP.ADD_PRINT_TEXT(251+tPos,59,179,23,"                   ");
			LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
			LODOP.SET_PRINT_STYLEA(0,"Underline",1);
			LODOP.ADD_PRINT_TEXT(278+tPos,17,84,23,"日期:");
			LODOP.SET_PRINT_STYLEA(0,"FontSize",10);
			LODOP.ADD_PRINT_TEXT(278+tPos,59,177,21,"  月  日");
			LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
			LODOP.ADD_PRINT_TEXT(167+tPos,196,14,20,"#");
			LODOP.SET_PRINT_STYLEA(0,"FontSize",10);
	        i++;
		}

	}


</script>

<style type="text/css">
.tdList td{height:40px;}
.fuckTd span{font-weight:normal}
#rsTable td{font-size:14px;}
.xhx {width:200px; border-bottom:1px solid #000;}
</style>
{/literal}
</head>

<body>
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
<div id="prn" align="center">
 <input type="button" value="打印预览" onClick="pre()">
 <!-- <input type="button" value="设计模式" onClick="des()"> -->
 <!-- <input type="button" value="测试" onClick="test()"> -->
</div>
</body>

<!-- <body onafterprint="return window_onafterprint()" onbeforeprint="return window_onbeforeprint()" style="text-align:center">
<div></div>
</body> -->
</html>
