<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>码单——单卷单匹编辑</title>
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.validate.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/common.js"}
<link href="Resource/Css/validate.css" type="text/css" rel="stylesheet">
{webcontrol type='LoadJsCss' src="Resource/Script/ext/resources/css/ext-all.css"}
{webcontrol type='LoadJsCss' src="Resource/Script/ext/adapter/ext/ext-base.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/ext/ext-all.js"}
<link rel="stylesheet" href="Resource/bootstrap/bootstrap3.0.3/css/bootstrap.css">
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.json.js"}
<script language="javascript">
// var _cache={$madanRows};//设置的全局变量，用来存放值
// var _temp={$madanRows};
var _cache=eval('('+window.dialogArguments.data+')');//取得window.showModalDialog中的传入参数
var _removeUrl='?controller={$smarty.get.controller}&action=RemoveMadanByAjax';
var page=1;
{literal}
 function btnRemove(id) {
 	if(id){
 														//暂时不用！
	 	$.post("?controller=Cangku_Chengpin_Ruku&action=RemoveMadanByAjax_old",{'id':id},function(json){
	 	  //$("input[title='"+id+"']").attr('title'," ");
	      $("input[title='"+id+"']").attr('title',id).parent("div").siblings('div').find("input[ readonly!='']").val('');
	    });
	    var p = $("input[title='"+id+"']").parent("div").siblings('div').find("input[name='number[]']").val();
	    changeCacheD(p-1,p);
	    getHeji();
	}else{
    	alert("请先保存！");
    }
 }
 //删除行,临时写在这里，后期需要用sea.js封装
  $('[name="btnRemove"]').click(function(){
  
    //利用ajax删除,后期需要利用sea.js进行封装
    var url=_removeUrl;
    var trs = $('.trRow');
    if(trs.length<=1) {
      alert('至少保存一个明细');
      return;
    }
    var tr = $(this).parents('.trRow');
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
      window.parent.showMsg('删除成功!');
    },'json');
    return;
  });

$(function(){	
	/*
	*设置页面布局
	*/
		var southItem = {
			xtype: 'box',
			region: 'south',
			height:30,
			contentEl: 'footer'
		};

		var centerItem = {
			  region:'center',
			  layout:'border',
			  items:[{
				//title:'明细(选中上边某行显示)',
				id : 'pihao',
				region:'west',
				title:'卷号[<a href="javascript:;" title="Alt+N 快捷添加" style="color:green;font-size:13px;" name="addMenuLink" id="addMenuLink" accesskey="N">+5页</a>]',
				layout:'fit',
				contentEl: 'caidan',
				margins: '-2 -2 -2 -2',
				autoScroll:true,
				width:115
				//split: true
			  },
			  {
				  id : 'gridView',
				  title:'<div><div style="float:left">码单明细</div><div id="divHeji" style="float:right;color:green;font-size:13px;padding-right:70px;"></div></div>',
				  collapsible: false,
				  region:'center',
				  margins: '-2 -2 -2 -2',
				  layout:'fit',
				  contentEl: 'tab',
				  autoScroll: true
				}]
		  };

        var viewport = new Ext.Viewport({
            layout: 'border',
            items: [southItem,centerItem]
        });
		//页面布局end
	//禁止回车键提交
	$('#form1').keydown(function(e){
		if(e.keyCode==13){
			if(e.target.type!='textarea')event.returnValue=false;
		}
	});
	/*
	*获取最大的卷号，用于判断共需多少页，每页100个卷号，每100个卷号用一个层作为导航
	*左侧菜单栏加载导航信息，判断需要加载的个数
	*/
	var maxPi=0;
	if(_cache){
		for(var k in _cache){
			if(isNaN(parseInt(k)))continue;
			if(!_cache[k])continue;
			if(_cache[k].number>maxPi)maxPi=parseInt(_cache[k].number);
		}
	}
	if(maxPi>0)page=Math.ceil(maxPi/100);
	//加载层******************
	for(var i=0;i<page;i++){
		var div='<div class="div_caidan">'+(i*100+1)+'—'+(i*100+100)+'</div>';
		$('#caidan').append(div);
	}
	/*
	*end************************************
	*/
	/////////////////////////////////////////////////////////////
	//加载码单列表
	getMadanList();
	//卷号选择菜单的事件
	$('.div_caidan').live('mouseover',function(){
		//如果该div 被选中，则不作处理
		// if(this.style.background=='#f0f061')return;
		// this.style.background='#dedede';
		this.style.fontWeight='bold';
	});
	$('.div_caidan').live('mouseout',function(){
		//如果该div 被选中，则不作处理
		// if(this.style.background=='#f0f061')return;
		// this.style.background='#ffffff';
		this.style.fontWeight='';
	});
	$('.div_caidan').live('click',function(){
		clear();
		this.style.background='#f0f061';
		//改变卷号的值并加载对应的码单明细
		var strnum=$(this).html();
		var num=parseInt(strnum.substring(0,strnum.indexOf('—')));
		selectNum(num);
		getDataBynumber(num);
		$(this).attr('active',true);
	});
	$('.div_caidan:first').click();
	//+100
	$('#addMenuLink').click(function(){
		for(var i=0;i<5;i++){
			addMenu();
		}
	});
	
	//设置卷号,修改缓存
	// $('[name="cntFormat[]"],[name="cnt_M[]"],[name="cntMadan[]"]').live('change',function(){
	// 		var pos=$('[name="'+$(this).attr('name')+'"]').index(this);
	// 		var number=$('[name="number[]"]').eq(pos).val();
	// 		changeCache(pos,number-1);
			
	// 		getHeji();
	// });

	//设置卷号,修改缓存
	$('[name="lot[]"]').live('change',function(){
			var pos=$('[name="lot[]"]').index(this);
			var number=$('[name="number[]"]').eq(pos).val();
			changeCache(pos,number-1,false);
            // //同时设置后面的lot[] 为的分割数字+1

	});
	
	$('[name="seCode[]"]').live('change',function(){
			var pos=$('[name="seCode[]"]').index(this);
			var number=$('[name="number[]"]').eq(pos).val();
			changeCache(pos,number-1,false);
            // //同时设置后面的lot[] 为的分割数字+1

	});
	
	//监听cntFormat的keyup事件
    $('[name="cntFormat[]"]').live('keyup',function(){
    	    //得到输入的索引位置
    	    var pos=$('[name="'+$(this).attr('name')+'"]').index(this);
    	    //得到卷号
    	    var number=$('[name="number[]"]').eq(pos).val();
    	    //得到输入值
    	    var cntFormat=$(this).val();
    	    var menfu=$('#menfu').val();
    	    var kezhong=$('#kezhong').val();
    	    //正则表达式验证输入的是数字
            var reg = new RegExp("^(([0-9]+\\.[0-9]*[1-9][0-9]*)|([0-9]*[1-9][0-9]*\\.[0-9]+)|([0-9]*[1-9][0-9]*))$");
            if(reg.test(menfu)&&reg.test(kezhong)){
                //如果输入的门幅与克重是浮点数

                /* 输入公斤数，自动获得米数与码数
                    kg/门幅/克重=米数
                ** 1m=0.9144码
                **  1kg=2.2046226磅
                */
                var cnt_M=parseFloat(cntFormat)/parseFloat(menfu)/parseFloat(kezhong);
                cnt_M=cnt_M.toFixed(1);//保留1为小数
                //对当前行的米数赋值
                $('[name="cnt_M[]"]').eq(pos).val(cnt_M);
                //根据米数计算码数
                var cntMadan=(cnt_M/parseFloat(0.9144)).toFixed(1);
                $('[name="cntMadan[]"]').eq(pos).val(cntMadan);
                
                /*
                **得到上一层的lot[]的值
                */
                 // var test=$('[name="lot[]"]').eq(pos+1).val();
               // alert(test);
                // if(pos!=0 && $('[name="lot[]"]').eq(pos-1).val()!=''){
                //      alert(11);
                //      //取出上一层的值
                //      var preLot=$('[name="lot[]"]').eq(pos-1).val();
                //      //处理后的值，赋给当前层的lot[]
                //      var str=getSplitNum(preLot);
                //       $('[name="lot[]"]').eq(pos).val(str);

                // }



                //将数值写入cache中
                changeCache(pos,number-1);
                getHeji();
            }else{
            	//如果输入的门幅与克重不满足要求
            	//清空当前行的米数 与 码数
            	$('[name="cnt_M[]"]').eq(pos).val('');
            	$('[name="cntMadan[]"]').eq(pos).val('');
            	  //将数值写入cache中
                changeCache(pos,number-1);
                getHeji();
            }
    });

	//加载前100卷号的数据
	getDataBynumber(1);
	
	//初始化合计信息
	getHeji();
});
//清空菜单颜色
function clear(){
	$('.div_caidan').each(function(){
		this.style.background='#ffffff';
	});
	$('.div_caidan').attr('active','');
}
//添加菜单
function addMenu(){
	var strnum=$('.div_caidan:last').html();
	var num=parseInt(strnum.substr(strnum.indexOf('—')+1))+1;
	//添加新的层
	var div='<div class="div_caidan">'+num+'—'+(num+99)+'</div>';
	$('#caidan').append(div);
}

//加载码单明细
function getMadanList(){
	//载入新数据
	var t = [];
	var tJuan =0;
	var tCnt =0;
	var page =1;
	//加载码单
	for(var ii=0;ii<page;ii++) {
		t.push("<div id='divPage'>");
		for(var j=0;j<5;j++) {
			t.push("<div id='divBlock'>");
			//每20卷卷合计和数量合计
			var _s=0;
			var _j=0;
			for(var k=0;k<20;k++) {
				var i = ii*100+j*20+k;
				t.push("<div id='divDuan'>");
				t.push("<div class='j'>");
				t.push("<input  name='btnRemove[]'  type='button' title=''  value='删除' onclick='btnRemove(this.title) '/>");
				
				t.push("</div>");
				//卷号
				t.push("<div class='j'>");
				t.push("<input name='number[]' readonly value='"+(i+1)+"' class='juan form-control'/>");
				t.push("</div>");
				//数量(公斤数)
				t.push("<div class='s'>");
				//debugger;
				t.push("<input name='cntFormat[]' value='' class='cnt form-control' onkeydown='moveNext(event,this)'/>");
				t.push("</div>");

				//数量(米数)
				t.push("<div class='s'>");
				//debugger;
				t.push("<input name='cnt_M[]' value='' class='cntM form-control' onkeydown='moveNext(event,this)'/>");
				t.push("</div>");

				//数量(码数)
				t.push("<div class='s'>");
				//debugger;
				t.push("<input name='cntMadan[]' value='' class='cntMadan form-control' onkeydown='moveNext(event,this)'/>");
				t.push("</div>");
				
				//色号
				t.push("<div class='s'>");
				//debugger;
				t.push("<input name='seCode[]' value='' class='cntMadan form-control' onkeydown='moveNext(event,this)'/>");
				t.push("</div>");
				//memo
				t.push("<div class='l'>");
				t.push("<input name='lot[]' value='' onclick='this.select()' class='lot form-control' onkeydown='moveNext(event,this)'/>");
				//其他按钮
				//t.push("<span style='background-color:lightblue; width:10px; color:red; font-size:12px; cursor:pointer'>×</span>");
				t.push("</div>");
				t.push("</div>");//end divDuan

			}
			t.push("</div>");//end divBlock
		}
		t.push("</div>");
	}
	var headArr=[];
	for(var ib=0;ib<5;ib++){
		headArr.push("<div class='headDuan'><div class='delete form-control'>操作</div><div class='j form-control'>卷号</div><div class='s form-control'>数量Kg</div><div class='s form-control'>米数</div><div class='s form-control'>码数</div><div class='s form-control'>色号</div><div class='l form-control'>缸号-件号</div></div>");
	}
	var divHtml="<div class='classHead'>"+headArr.join('')+"</div>";
	divHtml+=t.join('');
	//加载到div
	$('#tab').html(divHtml);

	//鼠标放在数据上面，title显示数据
	$('.cnt,.cntM').mouseover(function(){
		var strCnt = $(this).val();
		if(strCnt.indexOf('+')<=0){
			$(this).attr('title','');return;
		}
		$(this).attr('title',strCnt+'='+getHeSplitStr(strCnt));
	});

	//聚焦
	$('.cnt:first').focus();
}
//设置键盘方向键
function moveNext(e,o) {
	var i=$('[name="'+o.name+'"]').index(o);
	var pos=-1;
	//alert(pos);
	if(e.keyCode==37) {//左
		pos = i-20;
	} else if(!e.altKey&&e.keyCode==38) {//上
		pos = i-1;
	} else if(e.keyCode==39) {//右
		pos = i+20;
	} else if(!e.altKey&&(e.keyCode==40 || e.keyCode==13)) {//下
		pos = i+1;
	} else if(e.altKey&&(e.keyCode==40 || e.keyCode==13)){
		//获取当前被选中的菜单
		var pos=$('.div_caidan').index($('.div_caidan[active=true]'));
		if($('.div_caidan')[pos+1]){
			$('.div_caidan').eq(pos+1).click();
			$('.cnt:first').focus();
		}
		return;
	}else if(e.altKey&&e.keyCode==38){
		//获取当前被选中的菜单
		var pos=$('.div_caidan').index($('.div_caidan[active=true]'));
		if($('.div_caidan')[pos-1]){
			$('.div_caidan').eq(pos-1).click();
			$('.cnt:first').focus();
		}
		return;
	}
	if(pos>-1) {
		if(pos>99) return false;
		document.getElementsByName(o.name)[pos].focus();
		return false;
	}
	return true;
}

//得到总和
function getTotal(){
	var cntHeji=0;
	var cntMHeji=0;
	var cntDuan=0;
	var cntMashuHeji=0;
	for(var t in _cache){
		if(isNaN(parseInt(t)))continue;
		if(!_cache[t])continue;
		if(_cache[t].cnt==0 || _cache[t].cnt=='')continue;
		var _t=_cache[t].cnt;
		var _tM=_cache[t].cntM;
		var _tMashu=_cache[t].cntMadan;
		cntHeji += parseFloat(_t||0);
		cntMHeji += parseFloat(_tM||0);
		cntMashuHeji+=parseFloat(_tMashu||0);
		
		if(_t>0)cntDuan++;
	}
	return {cnt:cntHeji.toFixed(1),cntDuan:cntDuan.toFixed(1),cntM:cntMHeji.toFixed(1),cntMadan:cntMashuHeji.toFixed(1)};
}
//修改缓存
////i为输入公斤数的索引,p为对应的卷号数-1,isShow为控制是否更改缸号-件号
function changeCache(i,p,isShow){
	//i为输入公斤数的索引,p为对应的卷号数-1
	if(!_cache)_cache=[];
	if(!_cache[p])_cache[p]={"id":'',"ruku2proId":'',"cnt":'',"cntFormat":'','cnt_M':'','cntM':'',"number":'',"lot":'',"cntMadan":'',"seCode":'',"menfu":'',"kezhong":''};
	var _t=_cache[p];
	_t.cntFormat=$('[name="cntFormat[]"]').eq(i).val();
	_t.number=$('[name="number[]"]').eq(i).val();
	_t.cnt_M=$('[name="cnt_M[]"]').eq(i).val();
	_t.cntMadan=$('[name="cntMadan[]"]').eq(i).val();
	_t.seCode=$('[name="seCode[]"]').eq(i).val();
	_t.lot=$('[name="lot[]"]').eq(i).val();
	_t.menfu=$('[name="menfu"]').val();
	_t.kezhong=$('[name="kezhong"]').val();
    
    var kk=isShow==false?false:true;
    if(kk){
    	//得到上一层数组中的lot的值
	    if(p!=0 && _cache[p-1].lot!=''){
	    
	       var _preLot=_cache[p-1].lot;
	       //处理之后的值给当前的lot
	        var _str=getSplitNum(_preLot);
	        $('[name="lot[]"]').eq(i).val(_str);//显示出来
	        _t.lot=$('[name="lot[]"]').eq(i).val();//存入缓存
	    }
	    
    }
	//合计
	_t.cnt=getHeSplitStr(_t.cntFormat);
	_t.cntM=getHeSplitStr(_t.cnt_M);
	// debugger;
}
function changeCacheD(i,p){
	// _cache[i] = []; 
	_cache[i]['cnt']='';
	_cache[i]['cntFormat']='';
	_cache[i]['cntM']='';
	_cache[i]['seCode']='';
	_cache[i]['lot']='';

}

/*
* 输入字符串形式：15+25+45，自动计算合计
*/
function getHeSplitStr(str){
	var temp = str.split('+');
	var cntHeji=0;
	for(var j=0;temp[j];j++) {
		cntHeji += parseFloat(temp[j]||0);
	}
	return cntHeji;
}


/*
* 输入字符串形式：QJI201-1，已-分割，返回 QJI201-2
*/
function getSplitNum(str){
	var temp = str.split("-");         //分割字符串
	var prefix = temp[0];			   //前缀
	var suffix = temp[1];			   //后缀
	if(!isNaN(suffix)){				   //判断是否是纯数字
		suffix = parseInt(suffix) + 1; //数字直接+1
	}else{							   //不是数字需要先拆分数字和字母再计算
	   suffix_Char = suffix.substring(suffix.length-1,suffix.length);             //获取字母
	   suffix_Num = String(parseInt(suffix.substring(0,suffix.length-1)) + 1) ;   //数字部分+1
	   suffix = suffix_Num + suffix_Char;										  //拼接数字和字母
	}
	return prefix+'-'+suffix;          //返回最终编号
}

//选择批号时改变卷号的值
function selectNum(num){
	$('[name="number[]"]').each(function(){
		    // alert(this.value);
			this.value=num;
			num++;
	});	
}

//加载数据，以开始的number开始加载 
function getDataBynumber(num){
	var pos=num-1;
	//清空当前值
	//$('[name="btnRemove[]"]').attr('value','');
	$('[name="cntFormat[]"]').attr('value','');
	$('[name="cnt_M[]"]').attr('value','');
	$('[name="cntMadan[]"]').attr('value','');
	$('[name="seCode[]"]').attr('value','');
	$('[name="lot[]"]').attr('value','');
	$('[name="lot[]"]').attr('readonly',false);
	$('[name="cntFormat[]"]').attr('readonly',false);
	$('[name="cntFormat[]"]').attr('title','');
	$('[name="cnt_M[]"]').attr('readonly',false);
	$('[name="cnt_M[]"]').attr('title','');
	$('[name="cntMadan[]"]').attr('readonly',false);
	$('[name="cntMadan[]"]').attr('title','');
	$('[name="seCode[]"]').attr('readonly',false);
	$('[name="seCode[]"]').attr('title','');
	//重新赋值
	if(!_cache)return false;	
	for(var t in _cache){
		if(!_cache[t])continue;
		if(t>=pos && t<=(pos+99)){
			var _pos=t%100;
			//使用jquery检索导致速度变慢
			document.getElementsByName('btnRemove[]')[_pos].title=_cache[t].id;
			document.getElementsByName('cntFormat[]')[_pos].value=_cache[t].cntFormat;
			document.getElementsByName('cnt_M[]')[_pos].value=_cache[t].cnt_M;
			document.getElementsByName('cntMadan[]')[_pos].value=_cache[t].cntMadan;
			document.getElementsByName('seCode[]')[_pos].value=_cache[t].seCode;
			document.getElementById('menfu').value=_cache[t].menfu;
			document.getElementById('kezhong').value=_cache[t].kezhong;
			if(_cache[t].chuku2proId>0){
				//如果有chuku2proId,表示已经出库，设置只读
				document.getElementsByName('btnRemove[]')[_pos].readOnly=true;
				document.getElementsByName('cntFormat[]')[_pos].readOnly=true;
				document.getElementsByName('cntFormat[]')[_pos].title="该码单已出库，不能修改";
				document.getElementsByName('cnt_M[]')[_pos].readOnly=true;
				document.getElementsByName('cnt_M[]')[_pos].title="该码单已出库，不能修改";
				document.getElementsByName('cntMadan[]')[_pos].readOnly=true;
				document.getElementsByName('cntMadan[]')[_pos].title="该码单已出库，不能修改";
				document.getElementById('menfu').readOnly=true;
				document.getElementById('kezhong').readOnly=true;
			}
			_cache[t].lot=_cache[t].lot==undefined?'':_cache[t].lot;
			document.getElementsByName('lot[]')[_pos].value=_cache[t].lot;
			_cache[t].seCode=_cache[t].seCode==undefined?'':_cache[t].seCode;
			document.getElementsByName('seCode[]')[_pos].value=_cache[t].seCode;
		}
	}
}

//求合计
function getHeji(){
	var heji=0;
	var hejiM=0;
	var hejiMashu=0;
	var ma=0;
	for(var t in _cache){
		if(!_cache[t])continue;
		if(isNaN(parseInt(t)))continue;
		heji+=parseFloat(_cache[t].cnt)||0;
		hejiM+=parseFloat(_cache[t].cntM)||0;
		hejiMashu+=parseFloat(_cache[t].cntMadan)||0;
		//alert(_cache[t].cntFormat);
		if(_cache[t].cntFormat > 0)ma++;
	}
	//合计显示在页面上
	var hejiStr="合计Kg:"+"<font color='red'>"+heji.toFixed(1)+"</font>&nbsp;&nbsp;合计M:"+"<font color='red'>"+hejiM.toFixed(1)+"</font>&nbsp;&nbsp;合计码数<font color='red'>:"+hejiMashu.toFixed(1)+"</font>&nbsp;&nbsp;卷数<font color='red'>:"+ma+"</font>";
	$('#divHeji').html(hejiStr);
}
//返回给父窗口
//通过ajax 保存
function saveByAjax(o){
	// debugger;
	var total=getTotal();
	var param = {jsonStr:$.toJSON(_cache),'ruku2proId':$('#ruku2proId').val(),'menfu':$('[name="menfu"]').val(),'kezhong':$('[name="kezhong"').val(),"cnt":total.cnt,"cntM":total.cntM,"cntJian":total.cntDuan,"cntMadan":total.cntMadan,"ok":1};
	
    if(window.opener!=undefined) {
		window.opener.returnValue = param;
	} else {
		window.returnValue = param;
	}
	window.close();	
}

</script>
<style type="text/css">
body{ text-align:left;}
input{ height: 24px !important; min-width: 40px; padding-left: 3px !important; padding-right: 2px !important;}
td,div {font-size:12px; white-space:nowrap; white-space:nowrap;}
#main {margin: 10px 0px 0px 10px; width:1850px;}
.div_caidan{width:100%; border:0px; float:left; font-size:14px; color:#00F; padding:3px 0 0 10px; height:22px; border-bottom:1px solid #bbbbbb; cursor:pointer;}
.delete {width:42px; float:left;border:0px solid #000; margin-top: 2px;}
.j {width:42px; float:left;border:0px solid #000; margin-top: 2px;}
.s {width:54px; float:left;border:0px solid #000; margin-top: 2px;}
.l {width:58px; float:left;border:0px solid #000; margin-top: 2px;}
.classData {clear:both; margin-top:0px;margin-left:3px;}
#toolbar { text-align:center; width:100%; margin-top:5px; margin-bottom:10px;}
.classHead {width:2000px;clear:both;text-align:left; font-weight:bold;}
.headDuan{width:380px; float:left;}
#divPage {width:2000px; clear:both; overflow: auto;}
#divBlock {width:380px; float:left;}
#divDuan {width:100%; clear:both;}
#btnRemove {height: 24px !important; line-height:12px;  min-width: 40px; padding-left: 3px !important; padding-right: 2px !important; }
</style>
{/literal}
</head>
<body>
<form name="form1" id="form1" action="" method="post"  autocomplete="off">
<div id="caidan">
</div>
<div id='tab'>
</div>
<div id="footer">
<table id="buttonTable" align="center">
<tr>
		<td>
		<input type="hidden" value="{$ruku2proId}" id='ruku2proId' name="ruku2proId">
		实际克重(kg/m2):<input type='text' name='kezhong' id='kezhong' value='{$arr_field_value.kezhong}' />
		实际门幅(m):<input type='text'  name='menfu' id='menfu' value='{$arr_field_value.menfu}' />
		<input class="btn btn-primary" style="height:28px !important;width:60px !important;" type="button" name="Submit" value='确定' onClick="saveByAjax(this)">
      </td>
	</tr>
</table>
</div>
</form>
</body>
</html>
