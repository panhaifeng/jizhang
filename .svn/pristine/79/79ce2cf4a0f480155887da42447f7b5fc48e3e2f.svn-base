<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$title}</title>
{webcontrol type='LoadJsCss' src="Resource/Script/Print/LodopFuncs.js"}
<object id="LODOP" classid="clsid:2105C259-1E0C-4534-8141-A753534CB4CA" width=0 height=0>
    <embed id="LODOP_EM" type="application/x-print-lodop" width=0 height=0 pluginspage="install_lodop.exe"></embed>
</object>
<param name="CompanyName" value="常州易奇信息科技有限公司">
<param name="License" value="664717080837475919278901905623">
</object> 
<script language="javascript" src="Resource/Script/jquery.js"></script>
{literal}
<script language="javascript" type="text/javascript"> 
    //传递的参数处理
    var obj ={/literal}{$obj|@json_encode}{literal};
    var defaultValue ={/literal}{$default|@json_encode}{literal};
    function prn1_preview() {
        CreateOneFormPage(obj,10);//这边只有10，11 两种 如要改小自行调整间距行距大小等

    }
    var LODOP;
    function CreateOneFormPage(row,textsize){
        var lineheight = 24;
        var lines = obj['Son'].length;
        //第一套
        if (textsize==11) {
            var textheight = 17;
            var add = 0;
        }
        //第二套
        if (textsize==10) {
            var textheight = 17;
            var add = 2;
        }
        
        LODOP=getLodop(document.getElementById('LODOP'),document.getElementById('LODOP_EM'));
        LODOP.PRINT_INITA(0,0,831,351,"易奇科技报表打印");
        // LODOP.SET_PRINT_PAGESIZE(0,2100,2800,"0");
        //LODOP.SET_PRINT_PAGESIZE(0,2200,930,"0");
        LODOP.SET_PRINT_PAGESIZE(2,'','','A4');

        LODOP.SET_PRINT_STYLE("FontSize",10);
        LODOP.SET_PRINT_STYLE("Alignment",2);
        //头部
        LODOP.ADD_PRINT_TEXT(15,300,413,34,'常州金马劲飞布业后整出库单');
        LODOP.SET_PRINT_STYLEA(0,"FontSize",19);
        LODOP.SET_PRINT_STYLEA(0,"Bold",1);
        // LODOP.ADD_PRINT_TEXT(35,368,265,20,"后整出库单");
        // LODOP.SET_PRINT_STYLEA(0,"FontSize",13);
        LODOP.ADD_PRINT_TEXT(53,16,243,20,"出库时间："+row.rukuDate);
        LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
        LODOP.SET_PRINT_STYLEA(0,"Alignment",1);
        LODOP.ADD_PRINT_TEXT(53,280,207,20,"加工户："+row.compName);
        LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
        LODOP.SET_PRINT_STYLEA(0,"Alignment",1);
        // LODOP.ADD_PRINT_TEXT(53,479,283,20,"加工户："+row.compName);
        // LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
        // LODOP.SET_PRINT_STYLEA(0,"Alignment",1);
        //直线（横线）
        for (var i = 0; i < obj['Son'].length; i++) {
            LODOP.ADD_PRINT_LINE(74,16,73,1099,0,1);
            LODOP.ADD_PRINT_LINE(98,16,97,1099,0,1);
            LODOP.ADD_PRINT_LINE(122+lineheight*i,16,121+lineheight*i,1099,0,1);
        }
        //直线（竖线）
        LODOP.ADD_PRINT_LINE(97+(lines*lineheight),16,73,17,0,1);
        LODOP.ADD_PRINT_LINE(97+(lines*lineheight),113,73,113,0,1);
        LODOP.ADD_PRINT_LINE(97+(lines*lineheight),191,73,191,0,1);
        LODOP.ADD_PRINT_LINE(97+(lines*lineheight),342,73,342,0,1);
        LODOP.ADD_PRINT_LINE(97+(lines*lineheight),636,73,636,0,1);
        LODOP.ADD_PRINT_LINE(97+(lines*lineheight),778,73,778,0,1);
        LODOP.ADD_PRINT_LINE(97+(lines*lineheight),831,73,831,0,1);
        LODOP.ADD_PRINT_LINE(97+(lines*lineheight),881,73,881,0,1);
        LODOP.ADD_PRINT_LINE(97+(lines*lineheight),916,73,916,0,1);
        LODOP.ADD_PRINT_LINE(97+(lines*lineheight),965,73,965,0,1);
        LODOP.ADD_PRINT_LINE(97+(lines*lineheight),1019,73,1019,0,1);
        LODOP.ADD_PRINT_LINE(97+(lines*lineheight),1051,73,1051,0,1);
        LODOP.ADD_PRINT_LINE(97+(lines*lineheight),1099,73,1099,0,1);
        // LODOP.ADD_PRINT_LINE(97+(lines*lineheight),790,73,791,0,1);
        //标题部分
        LODOP.ADD_PRINT_TEXT(78,17,44,20,"订单号");
        LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
        LODOP.ADD_PRINT_TEXT(78,99,103,20,"客户");
        LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
        LODOP.ADD_PRINT_TEXT(78,188,121,20,"品名");
        LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
        LODOP.ADD_PRINT_TEXT(78,374,165,20,"规格");
        LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
        LODOP.ADD_PRINT_TEXT(78,687,52,20,"颜色");
        LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
        LODOP.ADD_PRINT_TEXT(78,765,65,20,"门幅");
        LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
        LODOP.ADD_PRINT_TEXT(78,829,40,20,"克重");
        LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
        LODOP.ADD_PRINT_TEXT(78,855,88,20,"件数");
        LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
        LODOP.ADD_PRINT_TEXT(78,895,88,20,"重量");
        LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
        LODOP.ADD_PRINT_TEXT(78,946,88,20,"缸号");
        LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
        LODOP.ADD_PRINT_TEXT(78,992,88,20,"机号");
        LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
        LODOP.ADD_PRINT_TEXT(78,1032,88,20,"工序");
        LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
        // LODOP.ADD_PRINT_TEXT(78,682,110,20,"备注");
        // LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
        //中间内容循环部分
        for (var i = 0; i < obj['Son'].length; i++) {
            LODOP.ADD_PRINT_TEXT(101+(lineheight*i)+add,17,103,textheight,obj['Son'][i].orderCode);
            LODOP.SET_PRINT_STYLEA(0,"FontSize",textsize);
            LODOP.ADD_PRINT_TEXT(101+(lineheight*i)+add,99,121,textheight,obj['Son'][i].codeAtOrder);
            LODOP.SET_PRINT_STYLEA(0,"FontSize",textsize);
            LODOP.ADD_PRINT_TEXT(101+(lineheight*i)+add,189,165,textheight,obj['Son'][i].proName);
            LODOP.SET_PRINT_STYLEA(0,"FontSize",textsize);
            LODOP.ADD_PRINT_TEXT(101+(lineheight*i)+add,321,340,textheight,obj['Son'][i].guige);
            LODOP.SET_PRINT_STYLEA(0,"FontSize",textsize);
            // LODOP.SET_PRINT_STYLEA(0,"LetterSpacing",-1);
            LODOP.ADD_PRINT_TEXT(101+(lineheight*i)+add,606,200,textheight,obj['Son'][i].color);
            LODOP.SET_PRINT_STYLEA(0,"FontSize",textsize);
            LODOP.ADD_PRINT_TEXT(101+(lineheight*i)+add,776,60,textheight,obj['Son'][i].menfu);
            LODOP.SET_PRINT_STYLEA(0,"FontSize",textsize);
            LODOP.ADD_PRINT_TEXT(101+(lineheight*i)+add,827,60,textheight,obj['Son'][i].kezhong);
            LODOP.SET_PRINT_STYLEA(0,"FontSize",textsize);
            LODOP.ADD_PRINT_TEXT(101+(lineheight*i)+add,871,60,textheight,obj['Son'][i].cntJianAll);
            LODOP.SET_PRINT_STYLEA(0,"FontSize",textsize);
            LODOP.ADD_PRINT_TEXT(101+(lineheight*i)+add,913,60,textheight,obj['Son'][i].cntAll);
            LODOP.SET_PRINT_STYLEA(0,"FontSize",textsize);
            LODOP.ADD_PRINT_TEXT(101+(lineheight*i)+add,960,62,textheight,obj['Son'][i].ganghao);
            LODOP.SET_PRINT_STYLEA(0,"FontSize",textsize);
            LODOP.ADD_PRINT_TEXT(101+(lineheight*i)+add,1004,60,textheight,obj['Son'][i].zhiJiCode);
            LODOP.SET_PRINT_STYLEA(0,"FontSize",9);
            LODOP.ADD_PRINT_TEXT(101+(lineheight*i)+add,1044,60,textheight,obj['Son'][i].gongxuName);
            LODOP.SET_PRINT_STYLEA(0,"FontSize",9);
            // LODOP.ADD_PRINT_TEXT(101+(lineheight*i)+add,680,110,textheight,obj['Son'][i].memo);
            // LODOP.SET_PRINT_STYLEA(0,"FontSize",textsize);
        }
        //尾部
        LODOP.ADD_PRINT_TEXT(101+(lineheight*lines),15,207,20,"制单人：");
        LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
        LODOP.SET_PRINT_STYLEA(0,"Alignment",1);
        LODOP.ADD_PRINT_TEXT(101+(lineheight*lines),575,207,20,"收货人：");
        LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
        LODOP.SET_PRINT_STYLEA(0,"Alignment",1);
        // LODOP.PRINT_design();
        // return false;
        LODOP.PREVIEW();
        // LODOP.PRINT_design();
    }
</script>
{/literal}
</head>
<body onLoad="prn1_preview();window.location.href=('?controller=Shengchan_Waixie_FawaiNew&action=right')"></body>
</html>