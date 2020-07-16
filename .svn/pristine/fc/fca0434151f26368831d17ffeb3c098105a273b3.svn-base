<html>
<head>
<script language="javascript" src="Resource/Script/calendar.js"></script>
<script language="javascript" src="Resource/Script/jquery.js"></script>
<script language="javascript" src="Resource/Script/Common.js"></script>
<link href="Resource/Css/Edit2.css" rel="stylesheet" type="text/css">
<script src="Resource/Script/autocomplete/autocomplete.js"></script>
<script src="Resource/Script/direct.js"></script>
<link href="Resource/Script/autocomplete/autocomplete.css" rel="stylesheet" type="text/css">
<object id="LODOP" classid="clsid:2105C259-1E0C-4534-8141-A753534CB4CA" width=0 height=0>
	<param name="CompanyName" value="常州易奇信息科技有限公司">
	<param name="License" value="664717080837475919278901905623">
	<embed id="LODOP_EM" type="application/x-print-lodop" width=0 height=0 pluginspage="install_lodop.exe"></embed>
</object>
<!-- <script language="javascript" src="Resource/Script/Print/LodopFuncs.js"></script> -->
<script language="javascript" src="Resource/Script/Lodop6.198/LodopFuncs.js"></script>
{literal}
<script language="javascript">
var srcTr2 = null;
var srcTr1= null;
var tr=null;
var obj ={/literal}{$aRow|@json_encode}{literal};
var LODOP; //声明为全局变量
function MyPreview() {	
 	LODOP=getLodop();  
 	var text = 'F1509033-2';
	LODOP.PRINT_INIT("打印控件功能演示_Lodop功能_不同高度幅面");
	// SET_PRINT_PAGESIZE(intOrient,intPageWidth,intPageHeight,strPageName)			
	LODOP.SET_PRINT_PAGESIZE(3,1385,1000,"");
	LODOP.ADD_PRINT_BARCODE(10,4,293,60,"Code39","F1509033-2");
	LODOP.ADD_PRINT_BARCODE(83,5,293,60,"EAN128A","F1509033-2");
	LODOP.ADD_PRINT_BARCODE(156,6,293,60,"EAN128C","F1509033-2");
	LODOP.ADD_PRINT_BARCODE(222,11,293,60,"Code93","F1509033-2");
	LODOP.ADD_PRINT_BARCODE(300,8,293,60,"128A","F1509033-2");
	//这里3表示纵向打印且纸高“按内容的高度”；1385表示纸宽138.5mm；45表示页底空白4.5mm
	// LODOP.PREVIEW();	
	LODOP.PRINT_DESIGN();	
};

$(function(){
	init();
	$('#btnTest').click(function(){
		MyPreview();
	});
	//打印动作
	$('#btnPrint').click(function(){
		CreateOneFormPage(obj);
		//LODOP.PRINT_DESIGN();return false;
        if(!confirm('打印后锁定，确定打印吗?')) return;
		var url='?controller=Output_Fenpi&action=GetIsprintByAjax';
      	var param={id:obj[0].shengchanId,fenpiDate:obj[0].fenpiDate};
//	  	$.post(url,param,function(json){
//		},'json');
		LODOP.PREVIEW();//return false;
        $.ajax({
          type: "POST",
          url: url,
          data: param,
          success: function(json){
             //刷新主页面
            document.location.href =document.location.href;                               
          },
          dataType: 'json',
          async: false//同步操作
        });
		//LODOP.PRINT();
		//window.close();
       
	});
	// 设置分匹数量
	$('#btnSetFenpi').click(function(){
		// 获得设置数量
		$('#tbl').find('.trRow').eq(0).show()
		var tr = $('#tbl').find('.trRow').eq(0);
		$('#tbl').find('.trRow').remove();
		var num=0;
		//取得最后一行的条码号
        $.ajax({
          type: "POST",
          url: '?controller=Output_Fenpi&action=Gettiaoma',
          data: {'shengchanId':$("#shengchanId").val()},
          success: function(json){
             num=(json.tiaoma.split("-"))[1];                             
          },
          dataType: 'json',
          async: false//同步操作
        });
//		num=($('[name="tiaoma[]"]').eq(-1).val().split("-"))[1];
		var code=$("#shengchanCode").val();
        $('#tbl2').find('.trRow2').each(function(){
            var that = this;
            // pinum2的值，分批数量  
            var pinum = $('[name="pinum2[]"]', $(that)).val();
           // pinum2的值不能为0
            pinum=pinum==0?1:pinum;
            var ganghao = $('[name="ganghao2[]"]', $(that)).val();
            var jitai = $('[name="jitai2[]"]', $(that)).val();
            var sehao = $('[name="sehao2[]"]', $(that)).val();
            var jianhao=0;
            //取得件号
            $.ajax({
              type: "POST",
              url: '?controller=Output_Fenpi&action=Getjianhao',
              data: {'ganghao':ganghao,'jitaiId':jitai},
              success: function(json){
                  jianhao=json.jianhao;                        
              },
              dataType: 'json',
              async: false//同步操作
            });
            for(var j=0;j<pinum;j++){
				num++;jianhao++;
				var row=tr.clone(true);
				$('[name="tiaoma[]"]',row).val(code+'-'+num);
				$('[name="ganghao[]"]',row).val(ganghao);
				$('[name="jitai[]"]',row).val(jitai);
				$('[name="jianhao[]"]',row).val(jianhao);
				//清空数据
				$('[name="shuhao[]"]',row).val('');
				$('[name="cntKg[]"]',row).val('');
				$('[name="cntM[]"]',row).val('');
				switch(sehao){
					case 'A': $('[name="sehao[]"]',row).val(sehao);break;
					case 'B': $('[name="sehao[]"]',row).val(j%2==0?'A':'B');break;
					case 'C': $('[name="sehao[]"]',row).val(j%3==0?'A':j%3==1?'B':'C');break;
					case 'D': $('[name="sehao[]"]',row).val(j%4==0?'A':j%4==1?'B':j%4==2?'C':'D');break;
					case 'E': $('[name="sehao[]"]',row).val(j%5==0?'A':j%5==1?'B':j%5==2?'C':j%5==3?'D':'E');break;
				}
				$('#tbl').append(row);
			}
        });
        
//		for(var i=0, iMax = $('[name="pinum2[]"]').parent().length;i<iMax;i++){
//			
//		}
		$('#setPlan').show();
	});
	// 返回上一页
	$('#btnBack').click(function(){
		window.history.go(-1);
	});
	//新增分计划行
	$('.aAddRow2').click(function(){
		var trCopy = srcTr2.clone(true);
		//自动完成渲染
		var obj = $('[name="ganghao2[]"]',trCopy);
		auto(obj);
		$('#tbl2').append(trCopy);
	});
    //新增总计划行
	$('.aAddRow1').click(function(){
		var trCopy = srcTr1.clone(true);
		//自动完成渲染
		var obj = $('[name="ganghao1[]"]',trCopy);
		auto(obj);
		$('#tbl1').append(trCopy);
	});
    //删除总计划行
	$('a[class="aDeleteRow1"]').click(function(){
		 var url="index.php?controller=Plan_Denim&action=RemoveBySum";
   		  var tr = $(this).parent().parent();
		    var id = $('[name="id1[]"]',tr).val();
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
	});
	
	//删除分计划行
	$('a[class="aDeleteRow2"]').live('click', function(){
		 var trs = $('.trRow2','#tbl2');
		 if(trs.length<=1) {
		      alert('至少保存一个明细');
		      return;
   		 }
   		  var tr = $(this).parents('.trRow2');
		  tr.remove();
	});
	//操作-删除明细
	$('a[class="aDeleteRow"]').click(function(){
		 var url="index.php?controller=Plan_Denim&action=RemoveByAjax";
		 var trs = $('[name="tiaoma[]"]');
		 if(trs.length<=2) {
		      alert('至少保存一个明细');
		      return;
   		 }
   		  var tr = $(this).parent().parent();
		    var id = $('[name="id[]"]',tr).val();
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
	});


    
    //审核操作
    $('a[class="isCheck"]').click(function(){
        var url="?Controller=Output_Fenpi&action=GetIsCheckByAjax";
        var tr=$(this).parent().parent();
		var check=trim($(this).html())=='锁定'?1:0;
        var id=$('[name="id[]"]',tr).val();
        var param={'id':id,'check':check};
//	  	$.post(url,param,function(json){
//            if(!json.success) alert('您没有当前模块操作权限!');
//            //刷新主页面
//            else document.location.href =document.location.href;
//		},'json');
        $.ajax({
		      type: "POST",
		      url: url,
		      data: param,
		      success: function(json){
                  debugger;
                  if(!json.success) alert('您没有当前模块操作权限!');
                  else document.location.href =document.location.href;
		      },
		      dataType: 'json',
		      async: false//同步操作
		    });
	});
    
    //色号必须是1~40  否则清空
    $('[name="shuhao[]"]').live('change',function(){
        var shuhao=$(this).val();
        var ex = /^\d+$/
        if(!(shuhao>0&&shuhao<41&&ex.test(shuhao))){
            alert('束号必须是1~40之间的整数')
            $(this).val('');
        }
    });
	
    //点条码显示打印信息
    $('[name="tiaoma[]"]').live('click',function(){
        var id=$('[name="id[]"]',($(this).parent().parent())).val();
        var url="?Controller=Plan_Denim&action=TiaomaShow&id="+id;
        window.showModalDialog(url, window ,'dialogWidth:500px;dialogHeight:400px');//弹出框
    });
    
     //束号报表
    $('a[class="shuhao"]').live('click',function(){
        var tr=$(this).parent().parent();
        var ganghao=$('[name="ganghao2[]"]',tr).val();
        var url="?Controller=Output_Fenpi&action=ShuhaoShow&ganghao="+ganghao;
        window.showModalDialog(url, window ,'dialogWidth:600px;dialogHeight:300px;');//弹出框
//        window.open(url);
	});
    
	//判断色号是否合理
//	$('[name="sehao2[]"],[name="pinum2[]"]').live('change',function(){
//		var sehao=$('[name="sehao2[]"]',tr).val();
//		var pinum=$('[name="pinum2[]"]',tr).val();
//		switch(sehao){
//			case 'B':if(pinum%2!=0) alert('色号不合理');break;
//			case 'C':if(pinum%3!=0) alert('色号不合理');break;
//			case 'D':if(pinum%4!=0) alert('色号不合理');break;
//			case 'E':if(pinum%5!=0) alert('色号不合理');break;
//		}
//	});
    
    //束号键盘方向键进行光标移动
    //上下左右键
	var name=['shuhao[]'];
	//*******direct(true)表示聚焦到下一个焦点时候，会选中里面的内容,false或没有参数则表示不选中
	direct({
		cellname:name,
		selectedorfocus:true,
		optionfocus:true
	});
    
	auto('[name="ganghao1[]"]');
	auto('[name="ganghao2[]"]');
	
});

//自动匹配缸号搜索
function auto(str){
	$(str).autocomplete('?controller=Plan_Denim&action=Autocomplete', {
			minChars:1,
			remoteDataType:'json',
			useCache:false,
			sortResults:false,
			onItemSelect:function(v){
			}
	});
}
//初始化   先将第一行复制再删除
function init(){
	srcTr2 = $('#tbl2').find('.trRow2').eq(0);
	var trCopy = srcTr2.clone(true);
    //去除色号的绑定事件
    $('[name="sehao[]"]').unbind('change');
	//自动完成渲染
	var obj = $('[name="ganghao2[]"]',trCopy);
	auto(obj);
	$('#tbl2').append(trCopy);
	srcTr2.remove();
    
	//隐藏计划列表页面
	var show=$("#show").val();
	if(show=='')  $('#setPlan').hide();
	else $('#tbl').find('.trRow').eq(0).hide();
    
	var chk=$('[name="isOver[]"]').length;
    var isPrint=$('[name="isPrint[]"]');
	for(var i=0;i<chk;i++){
        var tr=isPrint.eq(i+1).parent().parent();
		//打印过的内容变为绿色
		if($('[name="isPrint[]"]',tr).val()==1){
            tr.css('background-color','rgb(127, 241, 172)');
			tr.find('input').css({'background-color':'rgb(127, 241, 172)','border':'0'});
			tr.find('select').css({'background-color':'rgb(127, 241, 172)','border':'0'});
//            $('.:input[type!="checkbox"]',tr).attr("readonly","readonly");
//            $('.aDeleteRow',tr).hide();
		}
        //勾选的内容背景变为红色
		if($('[name="isOver[]"]',tr).val()==1){
			tr.css('background-color','#F56B87');
			tr.find('input').css({'background-color':'#F56B87','border':'0'});
			tr.find('select').css({'background-color':'#F56B87','border':'0'});
		}
        //锁定后的内容不能修改
        if(trim($('.isCheck',tr).html())=='取消锁定') $('[name="shuhao[]"]',tr).attr("readonly","readonly");
	}
}
//勾选触发事件
function val(o){
	var trs2 = $(o).parent('td');
	if($('[name="chk[]"]',trs2).attr('checked')==true){
		$('[name="isOver[]"]',trs2).val(1);
	}else
		$('[name="isOver[]"]',trs2).val(0);	
}
////判断色号是否合理
function sehao(o){
    var tr=$(o).parent().parent();
    var sehao=$('[name="sehao2[]"]',tr).val();
    var pinum=$('[name="pinum2[]"]',tr).val();
    switch(sehao){
        case 'B':if(pinum%2!=0) alert('色号不合理');break;
        case 'C':if(pinum%3!=0) alert('色号不合理');break;
        case 'D':if(pinum%4!=0) alert('色号不合理');break;
        case 'E':if(pinum%5!=0) alert('色号不合理');break;
    }
}
    

//表单验证:束号必填
function chkForm(obj){
	var shuhao=$('[name="shuhao[]"]');
    var num=shuhao.length;
	for(var i=1;i<num;i++){
		if(shuhao.eq(i).val()==''){
            alert('束号不能为空');return false;
        }
	}
	return true;
}

function CreateOneFormPage(obj){
	LODOP=getLodop(document.getElementById('LODOP'),document.getElementById('LODOP'));
	// LODOP.PRINT_INITA(-2,-2,800,0,"易奇科技报表打印");
	// LODOP.SET_PRINT_PAGESIZE(3,600,0,'');
	LODOP.PRINT_INIT("易奇科技报表打印");
	// SET_PRINT_PAGESIZE(intOrient,intPageWidth,intPageHeight,strPageName)			
	LODOP.SET_PRINT_PAGESIZE(3,600,0,"");
    var i=0;
	for(var j=0, iMax = obj.length;j<iMax;j++){
        //打印过的就不要再打印
		if(obj[j]['isCheck']==1)  continue; 
		//LODOP.SET_PRINT_STYLEA(0,"Horient",2);
		var tPos=i*410;
		LODOP.ADD_PRINT_TEXT(52+tPos,20,85,31,"客户编号：");
		LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
		LODOP.ADD_PRINT_TEXT(52+tPos,100,105,30,obj[j]['compCode']);
		LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
		LODOP.ADD_PRINT_TEXT(74+tPos,20,78,27,"定产号：");
		LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
		LODOP.ADD_PRINT_TEXT(74+tPos,100,102,25,obj[j]['manuCode']);
		LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
		LODOP.ADD_PRINT_TEXT(95+tPos,20,85,29,"纱缸号：");
		LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
		LODOP.ADD_PRINT_TEXT(95+tPos,100,99,25,obj[j]['ganghao']);
		LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
		LODOP.ADD_PRINT_TEXT(115+tPos,20,85,29,"产品编号：");
		LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
		LODOP.ADD_PRINT_TEXT(115+tPos,100,120,25,obj[j]['proCode']);
		LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
		LODOP.ADD_PRINT_TEXT(137+tPos,20,85,29,"布号：");
		LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
		LODOP.ADD_PRINT_TEXT(137+tPos,60,190,25,obj[j]['buhao']);
		LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
		LODOP.ADD_PRINT_TEXT(159+tPos,20,84,28,"布类：");
		LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
		if (obj[j]['guige'].length<14) {
			   LODOP.ADD_PRINT_TEXT(159+tPos,60,180,30,obj[j]['guige']);
			   LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
  		}else{
			   LODOP.ADD_PRINT_TEXT(159+tPos,60,160,60,obj[j]['guige']);
			   LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
			   tPos+=20;
  			} 
		LODOP.ADD_PRINT_TEXT(179+tPos,20,82,27,"成品：");
		LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
		LODOP.ADD_PRINT_TEXT(179+tPos,80,180,30,obj[j]['chenp']);
		LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
//			LODOP.ADD_PRINT_TEXT(203+i*430,20,81,30,"纱长：");
//			LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
//			LODOP.ADD_PRINT_TEXT(203+i*430,100,91,29," ");
//			LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
		LODOP.ADD_PRINT_TEXT(199+tPos,20,76,28,"颜色：");
		LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
		LODOP.ADD_PRINT_TEXT(199+tPos,70,230,30,obj[j]['proColor']);
		LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
		LODOP.ADD_PRINT_TEXT(219+tPos,20,79,29,"机台号：");
		LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
		LODOP.ADD_PRINT_TEXT(219+tPos,100,112,29,obj[j]['name']);
		LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
		LODOP.ADD_PRINT_TEXT(239+tPos,20,78,35,"重量：");
		LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
		LODOP.ADD_PRINT_TEXT(239+tPos,100,104,33,'');
		LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
		LODOP.ADD_PRINT_TEXT(259+tPos,20,84,33,"生产类型：");
		LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
		LODOP.ADD_PRINT_TEXT(259+tPos,100,106,31,obj[j]['kind']);
		LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
		LODOP.ADD_PRINT_BARCODE(279+tPos,20,200,60,"Code39",obj[j]['tiaoma']);
		// LODOP.ADD_PRINT_TEXT(279+tPos,20,78,32,"条码：");
		// LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
		LODOP.ADD_PRINT_TEXT(346+tPos,20,100,32,"操作工姓名：");
		LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
		LODOP.ADD_PRINT_TEXT(366+tPos,20,99,30,"技工：");
		LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
        LODOP.ADD_PRINT_TEXT(386+tPos,20,99,30,"坯检：");
		LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
        LODOP.ADD_PRINT_TEXT(406+tPos,20,99,30,"成检：");
		LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
        i++;
	}


}
</script>
<style type="text/css">
    .tiaoma {cursor:pointer;color:blue;text-decoration: underline;}
</style>
{/literal}
<title>针织牛仔计划-匹数设置</title>
</head>
<body>
<form id="form1" name="form1" method="post" action="{url controller=$smarty.get.controller action='saveFenpi'}" onSubmit="return chkForm(this)" >
	<fieldset id="setNum">     
			<legend>总计划</legend>
			<table width="100%" border="0" cellpadding="1" cellspacing="1" id="tbl1">
				<tr>
					<td align="center">缸号</td>
					<td align="center">机台数</td>
					<td align="center">匹数</td>
				</tr>
                {foreach from=$fensum item=item key=key}
                 <tr class="trRow1">
			    	<td align="center">{$item.ganghao}</td>
			    	<td align="center">{$item.jinum}</td>
					<td align="center">{$item.pinum}</td>
                </tr>
                {/foreach}
                <tr><td colspan="3"><input type="hidden" name="shengchanId" id="shengchanId" value="{$id}"/>
                    <input type="hidden" name="shengchanCode" id="shengchanCode" value="{$manuCode}"/>
			    	<input type="hidden" name="show" id="show" value="{$aRow.0.id}"/>
                </td></tr>
			</table>
	 </fieldset>
	 <fieldset id="setNum">     
			<legend>分计划&nbsp;&nbsp;<a title="新增一行" class="aAddRow2" href="#"> 新增</a>&nbsp;&nbsp;&nbsp;<input type="button" name="btnSetFenpi" id="btnSetFenpi" value="OK"><input type="hidden" name="fenpiNum" id="fenpiNum" value="10"/></legend>
			<table width="100%" border="0" cellpadding="1" cellspacing="1" id="tbl2">
				<tr scope="col">
					<th>操作</th>
					<th>缸号</th>
					<th>机台号</th>
					<th>匹数</th>
					<th>色号</th>
				</tr>
                {foreach from=$fenjihua item=item key=key}
			    <tr  class="trRow2" >
			    	<td align="center"><a title="删除本行" class="aDeleteRow2" href="javascript:;">删除</a></td>
			    	<td align="center"><input name="ganghao2[]" id="ganghao2[]" type="text" value="{$item.ganghao}"/><a title="束号" class="shuhao" href="javascript:;">束号报表</td>
			    	<td align="center"><select name="jitai2[]" id="jitai2[]" >                      
              		{webcontrol type='TmisOptions' model='JiChu_QiuranDevice' selected=$item.qiuranId condition="kind='圆机'"}	
                  	<td align="center"><input name="pinum2[]" id="pinum2[]" type="text" value="{$item.pinum}" onchange="sehao(this)"/></td>
                  	</select></td>
					<td align="center">
                        <select name="sehao2[]" id="sehao2[]" onchange="sehao(this)" >                      
                            <option value="A" {if $item.sehao=="A"}selected{/if}>A</option>
                            <option value="B" {if $item.sehao=="B"}selected{/if}>A/B</option>
                            <option value="C" {if $item.sehao=="C"}selected{/if}>A/B/C</option>
                            <option value="D" {if $item.sehao=="D"}selected{/if}>A/B/C/D</option>
                            <option value="E" {if $item.sehao=="E"}selected{/if}>A/B/C/D/E</option>	
                        </select></td>
                    <input name="id2[]" type="hidden" id="id2[]" value="{$item.id}">
			    </tr>
                {/foreach}
			</table>
	 </fieldset>
 <fieldset id="setPlan"><legend>分匹计划</legend>
		  <table width="100%" border="0" cellpadding="1" cellspacing="1">
				<tr> 
				    <td>
				      <div style="height:300px; overflow:auto; border-left:1px solid;border-right:1px solid;border-top:1px solid; border-bottom:1px solid;">
				      	<table width="100%" border="0" cellpadding="1" cellspacing="1" id="tbl">
					        <tr>
					          <th bgcolor="6699cc" scope="col">{*序号*} 操作
					          {*<a title="新增一行" class="aAddRow" href="#"> 新增</a>*}</th>
					          <th bgcolor="6699cc" scope="col">预计条码</th>
					          <th bgcolor="6699cc" scope="col">时间</th>
					          <th bgcolor="6699cc" scope="col">缸号</th>
					          <th bgcolor="6699cc" scope="col">束号</th>
					          <th bgcolor="6699cc" scope="col">机台</th>
					          <th bgcolor="6699cc" scope="col">件号</th>
					          <th bgcolor="6699cc" scope="col">色号</th>
					          <th bgcolor="6699cc" scope="col">预计公斤</th>
					          <th bgcolor="6699cc" scope="col">预计米数</th>
					          <th bgcolor="6699cc" scope="col">报废{*全选<input type="checkbox" name="checkAll" id="checkAll">*}</th>
					        </tr>
					        
					    	<tr class="trRow" >  <!-- style="display:none" -->
					          <td align="center">
					          		<span name="spanNo"></span> 
					          		{*<a title="删除本行" class="aDeleteRow" href="javascript:;">删除</a>*}
					          		<input name="id[]" type="hidden" id="id[]" value="">
                                  <input name="isPrint[]" type="hidden" id="isPrint[]" value="">
                                  <input name="isCheck[]" type="hidden" id="isCheck[]" value="{$item.isCheck}">
					          </td>
					          <td align="center">
					                 <!-- 预计条码 -->
                                  <input name="tiaoma[]" type="text" id="tiaoma[]" value="" readonly >
					          </td>
					          <td align="center">
					                 <!-- 时间-->
					          		<input name="fenpiDate[]" type="text" id="fenpiDate[]"  value="{$arr_field_value.fenpiDate|default:$smarty.now|date_format:'%Y-%m-%d'}" size="15" onClick="calendar()"></td>
					          </td>
					          <td align="center">
					                 <!-- 缸号-->
					          		<input name="ganghao[]" type="text" id="ganghao[]"  value="" size="10" style="width:90px;" readonly></td>
					          </td>
					          <td align="center">
					                 <!-- 束号-->
					          		<!--<select name="shuhao[]" id="shuhao[]" >                      
              							{webcontrol type='TmisOptions' model='JiChu_Shuhao' selected=$item.shuhao}	
                  					</select>-->
                                    <input name="shuhao[]" type="text" id="shuhao[]"  value="{$item.shuhao}" size="10" ></td>
					          </td>
					          <td align="center">
					          		<!-- 机台 -->
					          	 <select name="jitai[]" id="jitai[]" >                      
              							{webcontrol type='TmisOptions' model='JiChu_QiuranDevice' condition="kind='圆机'"}	
                  				</select >
					          </td>
					          <td align="center">
					                 <!-- 件号-->
					          		<input name="jianhao[]" type="text" id="jianhao[]"  value="" size="15" readonly></td>
					          </td>
					          <td align="center">
					                 <!-- 色号-->
					          		<select name="sehao[]" id="sehao[]">                      
              							<option value="A">A</option>
              							<option value="B">B</option>
              							<option value="C">C</option>
              							<option value="D">D</option>
              							<option value="E">E</option>	
                  					</select >
					          </td>
					          <td align="center">
					          		<!-- 预计公斤数 -->
					          		<input name="cntKg[]" type="text" id="cntKg[]" value="">
					          </td>
					          <td align="center">
									<!-- 预计米数 -->
									<input name="cntM[]" type="text" id="cntM[]" value="">
					          </td>
					          <td align="center"><input name="chk[]" type="checkbox" id="chk[]" value="0">
					          <input name="isOver[]" type="hidden" id="isOver[]" value="0"></td>
					        </tr>
					        
					        {foreach from=$aRow item=item key=key}
						        <tr>
						          <td align="center">
						          		{*<span name="spanNo">{$key+1}</span> *}
                                      {if $item.isCheck==0}
						          		<a title="删除本行" class="aDeleteRow" href="javascript:;">删除</a>
                                        {/if}
                                      {if $item.isPrint==1}
                                        <a {if $item.isCheck==1}title="已审核禁止修改"{/if} class="isCheck" href="javascript:;">
                                            {if $item.isCheck==1}取消锁定{else}锁定{/if}</a>
                                      {/if}
                                    <input name="id[]" type="hidden" id="id[]" value="{$item.id}">
                                    <input name="isPrint[]" type="hidden" id="isPrint[]" value="{$item.isPrint}">
                                    <input name="isCheck[]" type="hidden" id="isCheck[]" value="{$item.isCheck}">
						          </td>
						          <td align="center">
						                 <!-- 预计条码 -->
						          		<input class="tiaoma" name="tiaoma[]" type="text" id="tiaoma[]" value="{$item.tiaoma}" readonly>
						          </td>
						          <td align="center">
						                 <!-- 时间-->
						          		<input name="fenpiDate[]" type="text" id="fenpiDate[]"  value="{$item.fenpiDate|default:$smarty.now|date_format:'%Y-%m-%d'}" size="15" onClick="calendar()" readonly></td>
						          </td>
						          <td align="center">
					                 <!-- 缸号-->
					          		<input name="ganghao[]" type="text" id="ganghao[]"  value="{$item.ganghao}" style="width:90px;" readonly></td>
					          	  </td>
					          	  <td align="center">
					                 <!-- 束号-->
					          		<!--<select name="shuhao[]" id="shuhao[]" >                      
              							{webcontrol type='TmisOptions' model='JiChu_Shuhao' selected=$item.shuhao}	
                  					</select>-->
                                    <input name="shuhao[]" type="text" id="shuhao[]"  value="{$item.shuhao}" size="10" ></td>
					          	  </td>
						          <td align="center">
						          		<!-- 机台 -->
						          	 <select name="jitai[]" id="jitai[]">                      
	              							{webcontrol type='TmisOptions' model='JiChu_QiuranDevice' selected=$item.qiuranId condition="kind='圆机'"}	
	                  				</select>
						          </td>
						          <td align="center">
					                 <!-- 件号-->
					          		<input name="jianhao[]" type="text" id="jianhao[]"  value="{$item.jianhao}" size="15" readonly></td>
					          	  </td>
					          	  <td align="center">
					                 <!-- 色号-->
					          		<select name="sehao[]" id="sehao[]">                      
              							<option value="A" {if $item.sehao=="A"}selected{/if}>A</option>
              							<option value="B" {if $item.sehao=="B"}selected{/if}>B</option>
              							<option value="C" {if $item.sehao=="C"}selected{/if}>C</option>
              							<option value="D" {if $item.sehao=="D"}selected{/if}>D</option>
              							<option value="E" {if $item.sehao=="E"}selected{/if}>E</option>	
                  					</select>
					          	  </td>
						          <td align="center">
						          		<!-- 预计公斤数 -->
						          		<input name="cntKg[]" type="text" id="cntKg[]" value="{$item.cntKg}" >
						          </td>
						          <td align="center">
										<!-- 预计米数 -->
										<input name="cntM[]" type="text" id="cntM[]" value="{$item.cntM}" >
						          </td>
						          <td align="center"><input name="chk[]" type="checkbox" id="chk[]" value="0" {if $item.chk==1}checked {/if} onclick="val(this)">
						          <input name="isOver[]" type="hidden" id="isOver[]" value="{$item.chk}" >
						          </td>
						        </tr>
					        {/foreach}
					        
						 </table>
					 	</div>
					 </td>
				    </tr>
				    <tr>
				      <td align="center"><h3><span id="spanHeji">&nbsp;</span></h3></td>
				    </tr>
				    <tr>
				      <td>
                          <div style="width:40%;float: left; color:red;">绿色代表已打印，红色代表作废的条码<br/>相同缸号和机台号，件号递增</div>
                          <div style="width:60%;float: left;">
                              <input type="submit" name="submit" id="submit" value="保存" title="保存后进入修改界面">
                              <input type="button" name="print" id="btnPrint" value="打印" title="选中打印">
                              <input type="button" name="back" id="btnBack" value="返回" title="返回进入计划游览">
                              <input type="button" name="btnTest" id="btnTest" value="打印测试" title="打印测试">
                          </div>
				      </td>
				    </tr>
		  </table>
 </fieldset>
</form>
</body>
</html>
