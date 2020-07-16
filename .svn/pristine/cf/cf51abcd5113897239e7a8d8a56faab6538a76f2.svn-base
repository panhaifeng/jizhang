<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>条码打印</title>
<link href="Resource/Css/Print.css" type="text/css" rel="stylesheet" />
{literal}
<script language="javascript" src="Resource/Script/Print/LodopFuncs.js"></script>
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
	setTimeout(prn1_preview,500);
})

//传递的参数处理
var obj ={/literal}{$aRow|@json_encode}{literal};
// alert(obj.proName);
function prn1_preview() {
	//dump(obj);
	CreateOneFormPage(obj);
	// LODOP.PRINT_DESIGN();return false;
	LODOP.PREVIEW();//return false;
	//LODOP.PRINT();
	window.close();
};
var LODOP;
function CreateOneFormPage(obj){
	LODOP=getLodop(document.getElementById('LODOP'),document.getElementById('LODOP_EM'));
	// LODOP.PRINT_INITA(-2,-2,800,0,"易奇科技报表打印");
	// LODOP.SET_PRINT_PAGESIZE(3,600,0,'');
	LODOP.PRINT_INIT("易奇科技报表打印");
	// SET_PRINT_PAGESIZE(intOrient,intPageWidth,intPageHeight,strPageName)			
	LODOP.SET_PRINT_PAGESIZE(3,600,0,"");
    var i=0;
	for(var j=0, iMax = obj.length;j<iMax;j++){
        //打印过的就不要再打印
		//if(obj[j]['isCheck']==1)  continue; 
		//LODOP.SET_PRINT_STYLEA(0,"Horient",2);
		var tPos=i*400;
		LODOP.ADD_PRINT_TEXT(22+tPos,19,182,30,"WF坯布【"+obj[j]['Code']+"】");
		LODOP.SET_PRINT_STYLEA(0,"FontSize",12);
		LODOP.ADD_PRINT_TEXT(52+tPos,20,85,31,"订单编号:");
		LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
		LODOP.ADD_PRINT_TEXT(52+tPos,100,105,30,obj[j]['orderCode']);
		LODOP.SET_PRINT_STYLEA(0,"FontSize",11);

		LODOP.ADD_PRINT_TEXT(69+tPos,20,85,27,"坯布数量:");
		LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
		LODOP.ADD_PRINT_TEXT(69+tPos,100,102,25,obj[j]['pibuCnt']+'kg');
		LODOP.SET_PRINT_STYLEA(0,"FontSize",11);

		LODOP.ADD_PRINT_TEXT(86+tPos,20,202,20,"总匹数:" + obj[j]['pishuCnt']);
		LODOP.SET_PRINT_STYLEA(0,"FontSize",11);

		LODOP.ADD_PRINT_TEXT(103+tPos,20,200,29,"品名："+obj[j]['proName']);
		LODOP.SET_PRINT_STYLEA(0,"FontName","黑体")
		LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
		// LODOP.ADD_PRINT_TEXT(95+tPos,100,99,25,obj[j]['proName']);
		// LODOP.SET_PRINT_STYLEA(0,"FontSize",11);

		LODOP.ADD_PRINT_TEXT(128+tPos,20,200,29,"规格："+obj[j]['guige']);
		LODOP.SET_PRINT_STYLEA(0,"FontSize",10);
		// LODOP.ADD_PRINT_TEXT(115+tPos,100,120,25,obj[j]['guige']);
		// LODOP.SET_PRINT_STYLEA(0,"FontSize",9);

		LODOP.ADD_PRINT_TEXT(154+tPos,20,85,24,"门幅：");
		LODOP.SET_PRINT_STYLEA(0,"FontSize",10);
		LODOP.ADD_PRINT_TEXT(154+tPos,60,190,25,obj[j]['menfu']);
		LODOP.SET_PRINT_STYLEA(0,"FontSize",10);

		LODOP.ADD_PRINT_TEXT(175+tPos,20,84,28,"克重：");
		LODOP.SET_PRINT_STYLEA(0,"FontSize",10);
		LODOP.ADD_PRINT_TEXT(174+tPos,62,180,25,obj[j]['kezhong']);
		LODOP.SET_PRINT_STYLEA(0,"FontSize",10);
  	
		LODOP.ADD_PRINT_TEXT(194+tPos,20,82,21,"机台号：");
		LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
		LODOP.ADD_PRINT_TEXT(195+tPos,77,180,19,obj[j]['jitai']);
		LODOP.SET_PRINT_STYLEA(0,"FontSize",11);

		LODOP.ADD_PRINT_TEXT(215+tPos,20,76,22,"件号：");
		LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
		LODOP.ADD_PRINT_TEXT(215+tPos,69,176,20,obj[j]['jianhao']);
		LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
		LODOP.ADD_PRINT_TEXT(254+tPos,20,79,24,"公斤数：");
		LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
		LODOP.ADD_PRINT_TEXT(254+tPos,100,112,24,'');
		LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
		LODOP.ADD_PRINT_TEXT(277+tPos,20,78,25,"姓名：");
		LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
		LODOP.ADD_PRINT_TEXT(277+tPos,99,104,23,'');
		LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
		LODOP.ADD_PRINT_TEXT(300+tPos,20,84,23,"日期：");
		LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
		LODOP.ADD_PRINT_TEXT(300+tPos,100,106,21,obj[j]['date']);
		LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
		LODOP.ADD_PRINT_BARCODE(320+tPos,11,200,60,"Code39",obj[j]['ExpectCode']);
		LODOP.ADD_PRINT_TEXT(237+tPos,19,55,20,"缸号：");
		LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
		LODOP.ADD_PRINT_TEXT(236+tPos,70,155,20,obj[j]['ganghao']);

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

<body onafterprint="return window_onafterprint()" onbeforeprint="return window_onbeforeprint()" style="text-align:center">
<div></div>
</body>
</html>
