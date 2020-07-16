<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>二维码打印</title>
{literal}
<script language="javascript" src="Resource/Script/Print/LodopFuncs.js"></script>
<object id="LODOP" classid="clsid:2105C259-1E0C-4534-8141-A753534CB4CA" width=0 height=0>
    <param name="CompanyName" value="常州易奇信息科技有限公司">
    <param name="License" value="664717080837475919278901905623">
    <embed id="LODOP_EM" type="application/x-print-lodop" width=0 height=0 pluginspage="install_lodop.exe"></embed>
</object>
<script language="javascript" src="Resource/Script/Common.js"></script>
<script language="javascript" src="Resource/Script/jquery.js"></script>
<script language="javascript" type="text/javascript">
$(function(){
    prn1_preview();
})

//传递的参数处理
var obj ={/literal}{$aRow|@json_encode}{literal};
function prn1_preview() {
    // dump(obj);
    CreateOneFormPage(obj);
    // LODOP.PRINT_DESIGN();//return false;
    LODOP.PREVIEW();//return false;
    // LODOP.PRINT();
    window.close();
};
var LODOP;
function CreateOneFormPage(obj){
    LODOP=getLodop(document.getElementById('LODOP'),document.getElementById('LODOP_EM'));
    LODOP.PRINT_INITA("1.32mm","2.65mm","109.27mm","153.99mm","二维码单据打印");
    // LODOP.ADD_PRINT_SETUP_BKIMG("C:\\Users\\wuyou\\Desktop\\qrCodePrint.jpg");
    LODOP.SET_PRINT_PAGESIZE(1,"109.27mm","153.99mm","二维码单据打印");
    LODOP.SET_PRINT_STYLE("FontColor","#000000");
    LODOP.ADD_PRINT_TEXT("9.53mm","15.61mm","77.26mm","0.926cm","张家港孚宝仓储有限公司");
    LODOP.SET_PRINT_STYLEA(0,"FontSize",18);
    LODOP.SET_PRINT_STYLEA(0,"FontColor","#000000");
    LODOP.SET_PRINT_STYLEA(0,"Bold",1);
    LODOP.ADD_PRINT_TEXT("19.84mm","35.72mm","35.72mm","7.94mm","【提货单】");
    LODOP.SET_PRINT_STYLEA(0,"FontSize",18);
    LODOP.SET_PRINT_STYLEA(0,"FontColor","#000000");
    LODOP.SET_PRINT_STYLEA(0,"Bold",1);
    LODOP.ADD_PRINT_TEXT("31.01mm","17.01mm","35mm","6.01mm","客  户  名  称:");
    LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
    LODOP.SET_PRINT_STYLEA(0,"FontColor","#000000");
    LODOP.ADD_PRINT_TEXT("38.5mm","17.01mm","35mm","6.01mm","客 户 订 单 号:");
    LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
    LODOP.SET_PRINT_STYLEA(0,"FontColor","#000000");
    LODOP.ADD_PRINT_TEXT("46.01mm","17.01mm","35mm","6.01mm","车          号:");
    LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
    LODOP.SET_PRINT_STYLEA(0,"FontColor","#000000");
    LODOP.ADD_PRINT_TEXT("53.5mm","17.01mm","35mm","6.01mm","产          品:");
    LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
    LODOP.SET_PRINT_STYLEA(0,"FontColor","#000000");
    LODOP.ADD_PRINT_TEXT("61.01mm","17.01mm","35mm","6.01mm","订  单  数  量:");
    LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
    LODOP.SET_PRINT_STYLEA(0,"FontColor","#000000");
    LODOP.ADD_PRINT_TEXT("68.5mm","17.01mm","35mm","6.01mm","订 单 起始日期:");
    LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
    LODOP.SET_PRINT_STYLEA(0,"FontColor","#000000");
    LODOP.ADD_PRINT_TEXT("75.99mm","17.01mm","35mm","6.01mm","订 单 终止日期:");
    LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
    LODOP.SET_PRINT_STYLEA(0,"FontColor","#000000");
    LODOP.ADD_PRINT_TEXT("31.01mm","48.5mm","55.01mm","6.01mm",obj.clientName);
    LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
    LODOP.SET_PRINT_STYLEA(0,"FontColor","#000000");
    LODOP.SET_PRINT_STYLEA(0,"Bold",1);
    LODOP.ADD_PRINT_TEXT("38.5mm","48.5mm","55.01mm","6.01mm",obj.customerRef);
    LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
    LODOP.SET_PRINT_STYLEA(0,"FontColor","#000000");
    LODOP.SET_PRINT_STYLEA(0,"Bold",1);
    LODOP.ADD_PRINT_TEXT("46.01mm","48.5mm","55.01mm","6.01mm",obj.carName);
    LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
    LODOP.SET_PRINT_STYLEA(0,"FontColor","#000000");
    LODOP.SET_PRINT_STYLEA(0,"Bold",1);
    LODOP.ADD_PRINT_TEXT("53.5mm","48.5mm","55.01mm","6.01mm",obj.proName);
    LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
    LODOP.SET_PRINT_STYLEA(0,"FontColor","#000000");
    LODOP.SET_PRINT_STYLEA(0,"Bold",1);
    LODOP.ADD_PRINT_TEXT("61.01mm","48.5mm","55.01mm","6.01mm",obj.tidanCnt+'(t)');
    LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
    LODOP.SET_PRINT_STYLEA(0,"FontColor","#000000");
    LODOP.SET_PRINT_STYLEA(0,"Bold",1);
    LODOP.ADD_PRINT_TEXT("68.5mm","48.5mm","55.01mm","6.01mm",obj.yujiDate);
    LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
    LODOP.SET_PRINT_STYLEA(0,"FontColor","#000000");
    LODOP.SET_PRINT_STYLEA(0,"Bold",1);
    LODOP.ADD_PRINT_TEXT("75.99mm","48.5mm","55.01mm","6.01mm",obj.youxiaoDate);
    LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
    LODOP.SET_PRINT_STYLEA(0,"FontColor","#000000");
    LODOP.SET_PRINT_STYLEA(0,"Bold",1);
    LODOP.ADD_PRINT_IMAGE("79.38mm","26.72mm","52.92mm","52.92mm","<img src='"+obj.qrCodePath+"'/>");
    LODOP.SET_PRINT_STYLEA(0,"Stretch",1);
    LODOP.ADD_PRINT_TEXT("131mm","17.01mm","69mm","6.01mm","打印时间："+obj.datetime);
    LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
    LODOP.SET_PRINT_STYLEA(0,"FontColor","#000000");
    LODOP.SET_PRINT_STYLEA(0,"Bold",1);
    LODOP.ADD_PRINT_TEXT("140.49mm","17.01mm","24mm","6.01mm","司机签名：");
    LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
    LODOP.SET_PRINT_STYLEA(0,"FontColor","#000000");
    LODOP.SET_PRINT_STYLEA(0,"Bold",1);
    LODOP.ADD_PRINT_SHAPE(0,"146mm","41.51mm","45.01mm","0.26mm",0,1,"#000000");

}


</script>
{/literal}
</head>

<body onafterprint="return window_onafterprint()" onbeforeprint="return window_onbeforeprint()" style="text-align:center">
<div></div>
</body>
</html>
