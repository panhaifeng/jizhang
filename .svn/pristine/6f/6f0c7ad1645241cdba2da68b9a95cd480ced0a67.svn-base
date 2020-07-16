<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title>条码打印</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  {webcontrol type='LoadJsCss' src="Resource/Script/jquery.js"}
  {webcontrol type='LoadJsCss' src="Resource/Script/Print/Lodop6.226/LodopFuncs.js"}
  <object id="LODOP_OB" classid="clsid:2105C259-1E0C-4534-8141-A753534CB4CA" width=0 height=0> 
    <embed id="LODOP_EM" type="application/x-print-lodop" width=0 height=0 pluginspage="install_lodop.exe"></embed>
    <param name="CompanyName" value="常州易奇信息科技有限公司">
    <param name="License" value="664717080837475919278901905623">
  </object> 
</head>
<script language="javascript">
{literal}
$(function(){

	// setTimeout(prn1_preview,1000);
})

	//传递的参数处理
	var obj ={/literal}{$aRow|@json_encode}{literal};
	var temp = {/literal}{$temp|@json_encode}{literal};
	var batch = {/literal}{$batchRes|@json_encode}{literal};
	var test = {/literal}'test'{literal};
	
	var pre = function() {
        CreateOneFormPage(obj);  
		LODOP.SET_PREVIEW_WINDOW(0,0,0,0,0,"");			
        LODOP.PREVIEW();
        //LODOP.PRINT_DESIGN(); 
    }; 
	
	 var direct = function() {
        PrintDirectly(batch);  
    };     

	function prn1_preview() {
		CreateOneFormPage(obj);
		//LODOP.PRINT_DESIGN();return false;
		LODOP.PREVIEW();//return false;
		if(temp){
			if(temp=='order'){
				var urls = "?controller=Trade_order&action=right";
				window.location.href=urls;
			}else if(temp=='biaoqian'){
				// self.parent.tb_remove();
                var urls = "?controller=Shengchan_PiJian_Plan&action=printlist";
                window.location.href=urls;
			}
		}
		window.close();
		opener.parent.location.reload();
	};

	var LODOP;
	function CreateOneFormPage(obj){
    	LODOP=getLodop(document.getElementById('LODOP_OB'),document.getElementById('LODOP_EM'));
        // LODOP=getLodop(); 
        // LODOP.ADD_PRINT_HTM("20","0.15cm","RightMargin:0.1cm","BottomMargin:20mm");
        LODOP.PRINT_INITA(0,0,800,800,"");
        var strBASE64Code=document.getElementById('t1').value;
	    var i=0;
		for(var j=0, iMax = obj.length;j<iMax;j++){
			createHtml(obj,j,i,strBASE64Code);
		}
	}

	/*分批打印*/
	function PrintDirectly(batch){
		//if(!confirm("下面的操作会产生大量的实际打印操作，确定继续吗？")) return;
    	// LODOP=getLodop(document.getElementById('LODOP_OB'),document.getElementById('LODOP_EM'));
    	LODOP=getLodop();
        LODOP.PRINT_INITA(0,0,800,800,"");
        var strBASE64Code=document.getElementById('t1').value;
		var i=0;
		for (i = 0; i <batch.length; i++) {
			for(var j=0, iMax = batch[i].length;j<iMax;j++){
				createHtml(batch[i],j,i,strBASE64Code);
			}
		}
    	LODOP.PRINT();
    	if(temp){
			if(temp=='order'){
				var urls = "?controller=Trade_order&action=right";
				window.location.href=urls;
			}else if(temp=='biaoqian'){
				// self.parent.tb_remove();
                var urls = "?controller=Shengchan_PiJian_Plan&action=printlist";
                window.location.href=urls;
			}
		}
		window.close();
		opener.parent.location.reload();
     	// LODOP.PREVIEW();
        // LODOP.PRINT_DESIGN(); 
	}


	function createHtml(obj,j,i,strBASE64Code){
        LODOP.SET_LICENSES("常州易奇信息科技有限公司","664717080837475919278901905623","","");
		LODOP.NewPage();
        LODOP.SET_PRINT_PAGESIZE(1,"6cm","17cm","条码打印");
        
		var tPos=i*0;
		LODOP.ADD_PRINT_TEXTA('text1'+i,29,23,183,30,"       水洗标");
		LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
		LODOP.SET_PRINT_STYLE("FontName","宋体");
		// LODOP.SET_PRINT_STYLEA(0,"Bold",1);
		LODOP.ADD_PRINT_TEXTA('text2'+i,61+tPos,23,55,31,"合同号:");
		LODOP.SET_PRINT_STYLEA(0,"FontSize",7);
		LODOP.SET_PRINT_STYLE("FontName","宋体");
		// LODOP.SET_PRINT_STYLEA(0,"Bold",1);
		LODOP.ADD_PRINT_TEXTA('text3'+i,61+tPos,62,148,30,obj[j]['orderCode']);
		LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
        LODOP.SET_PRINT_STYLE("FontName","宋体");
        // LODOP.SET_PRINT_STYLEA(0,"Bold",1);
		LODOP.ADD_PRINT_TEXTA('text4'+i,83+tPos,24,44,31,"条码:");
		LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
		LODOP.SET_PRINT_STYLE("FontName","宋体");
		// LODOP.SET_PRINT_STYLEA(0,"Bold",1);
		LODOP.ADD_PRINT_TEXTA('text4'+i,83+tPos,61,148,30,obj[j]['ExpectCode']);
		LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
        LODOP.SET_PRINT_STYLE("FontName","宋体");
        // LODOP.SET_PRINT_STYLEA(0,"Bold",1);
		LODOP.ADD_PRINT_TEXTA('text5'+i,95+tPos,24,85,27,"品名:");
		LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
		LODOP.SET_PRINT_STYLE("FontName","宋体");
		// LODOP.SET_PRINT_STYLEA(0,"Bold",1);
		LODOP.ADD_PRINT_TEXTA('text6'+i,95+tPos,60,148,25,obj[j]['proName']);
		LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
		LODOP.SET_PRINT_STYLE("FontName","宋体");
		// LODOP.SET_PRINT_STYLEA(0,"Bold",1);
		LODOP.ADD_PRINT_TEXTA('text7'+i,122+tPos,24,120,25,"克重:"+obj[j]['kezhong']);
		LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
		LODOP.SET_PRINT_STYLE("FontName","宋体");
		// LODOP.SET_PRINT_STYLEA(0,"Bold",1);
		LODOP.ADD_PRINT_TEXTA('text8'+i,141+tPos,24,120,25,"门幅:"+obj[j]['menfu']);
		LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
		LODOP.SET_PRINT_STYLE("FontName","宋体");
		// LODOP.SET_PRINT_STYLEA(0,"Bold",1);
		LODOP.ADD_PRINT_TEXTA('text9'+i,161+tPos,24,85,30,"纱支:"); 
		LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
		LODOP.SET_PRINT_STYLE("FontName","宋体");
		// LODOP.SET_PRINT_STYLEA(0,"Bold",1);
		LODOP.ADD_PRINT_TEXTA('text10'+i,161+tPos,54,163,26,obj[j]['guige']); 
		LODOP.SET_PRINT_STYLEA(0,"FontSize",7.8);
		LODOP.SET_PRINT_STYLE("FontName","宋体");

		// LODOP.SET_PRINT_STYLEA(0,"Bold",1);
		LODOP.ADD_PRINT_TEXTA('text11'+i,190+tPos,24,85,24,"件号:");
		LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
		LODOP.SET_PRINT_STYLE("FontName","宋体");
		// LODOP.SET_PRINT_STYLEA(0,"Bold",1);
		LODOP.ADD_PRINT_TEXTA('text12'+i,189+tPos,62,149,25,"                   ");
		LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
		LODOP.SET_PRINT_STYLEA(0,"Underline",1);
		LODOP.ADD_PRINT_TEXTA('text13'+i,215+tPos,24,84,28,"机台:");
		LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
		// LODOP.SET_PRINT_STYLEA(0,"Bold",1);
		LODOP.ADD_PRINT_TEXTA('text14'+i,213+tPos,62,145,25,"                   "); 
		LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
		LODOP.SET_PRINT_STYLEA(0,"Underline",1);
		LODOP.ADD_PRINT_TEXTA('text15'+i,239+tPos,24,82,21,"缸号:");
		LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
		LODOP.SET_PRINT_STYLE("FontName","宋体");
		// LODOP.SET_PRINT_STYLEA(0,"Bold",1);
		LODOP.ADD_PRINT_TEXTA('text16'+i,240+tPos,63,145,19,"                   ");
		LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
		LODOP.SET_PRINT_STYLEA(0,"Underline",1);
		LODOP.ADD_PRINT_TEXTA('text17'+i,289+tPos,24,49,24,"重量:");
		LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
		LODOP.SET_PRINT_STYLE("FontName","宋体");
		// LODOP.SET_PRINT_STYLEA(0,"Bold",1);
		LODOP.ADD_PRINT_TEXTA('text18'+i,261+tPos,24,51,24,"姓名:");
		LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
		LODOP.SET_PRINT_STYLE("FontName","宋体");
		// LODOP.SET_PRINT_STYLEA(0,"Bold",1);
		LODOP.ADD_PRINT_TEXTA('text19'+i,262+tPos,61,148,25,"                   ");
		LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
		LODOP.SET_PRINT_STYLEA(0,"Underline",1);
		LODOP.ADD_PRINT_TEXTA('text20'+i,289+tPos,61,149,23,"                   ");
		LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
		LODOP.SET_PRINT_STYLEA(0,"Underline",1);
		LODOP.ADD_PRINT_TEXTA('text21'+i,308+tPos,24,84,23,"日期:");
		LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
		LODOP.SET_PRINT_STYLE("FontName","宋体");
		// LODOP.SET_PRINT_STYLEA(0,"Bold",1);
		LODOP.ADD_PRINT_TEXTA('text22'+i,308+tPos,61,86,21,obj[j]['month']+"月"+obj[j]['day']+"日");
		LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
		LODOP.SET_PRINT_STYLE("FontName","宋体");
		// LODOP.SET_PRINT_STYLEA(0,"Bold",1);
		LODOP.ADD_PRINT_TEXTA('text23'+i,205+tPos,197,14,20,"#");
		LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
		LODOP.SET_PRINT_STYLE("FontName","宋体");
		// LODOP.SET_PRINT_STYLEA(0,"Bold",1);
		LODOP.ADD_PRINT_TEXTA('text24'+i,308+tPos,158,55,20,obj[j]['pageNum']);
		// LODOP.ADD_PRINT_IMAGE(17,24,"25.11%","6.70%","<img src='Resource/Image/logo11.png' />");
		LODOP.ADD_PRINT_IMAGE(17,24,"25.11%","6.70%",strBASE64Code);
		
		LODOP.SET_PRINT_STYLEA(0,"Stretch",1);
		LODOP.ADD_PRINT_BARCODE(325+tPos,27,183,58,"128A",obj[j]['ExpectCode']);
		LODOP.ADD_PRINT_RECT(15,22,184,375,0,1);

        i++;
	}
</script>

<style type="text/css">
 *{margin: 0;padding: 0;}
.div1{width: 500px;height: 600px;/*background-color: #c2c2c2;*/position: relative;}
.div2{width: 150px;height: 130px;/* background-color: #fff; */ float:left;position: relative;left: 50px;top: 30px;font-size: 10px;}
</style>
{/literal}
</head>

<body>
{foreach from=$showPrint item=main key=key}
 	<div style="float: left;width: 100%;height: 200px;" class="div1">
     	{foreach from=$main item=item key=kk}
      	<div class="div2">
	       <span>水洗标</span>
	       <br/><span>合同号:{$item.orderCode|default:'&nbsp;'}</span>
	       <br/><span>条码:{$item.ExpectCode|default:'&nbsp;'}</span>
	       <br/><span>品名:{$item.proName|default:'&nbsp;'}</span>
	       <br/><span>克重:{$item.kezhong|default:'&nbsp;'}</span>
	       <br/><span>门幅:{$item.menfu|default:'&nbsp;'}</span>
	       <br/><span>纱支:{$item.guige|default:'&nbsp;'}</span>
	       <br/><span>日期:{$item.month}月{$item.day}日</span>&nbsp;&nbsp;{$item.pageNum|default:'&nbsp;'}
     	</div>
     	{/foreach}
  	</div>
{/foreach}
<br/>
<br/>
<br/>
<div id="prn" align="center">
 <input type="button" value="打印预览" onClick="prn1_preview()"> 
 <input type="button" value="直接打印" onClick="direct()">
 <!-- <input type="button" value="测试" onClick="test()"> -->
</div> 

<textarea rows="28" id="t1" cols="85" style="display: none;">
data:image/png;base64,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
</textarea>


</body>

<!-- <body onafterprint="return window_onafterprint()" onbeforeprint="return window_onbeforeprint()" style="text-align:center">
<div></div>
</body>
</html>

