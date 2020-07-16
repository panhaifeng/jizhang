<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>码单出库——选择要出库的码单</title>
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
var _selected=eval('('+window.dialogArguments.data+')');
var _cache={$madanRows};
{literal}
var page=1;
$(function(){
	/**
	* 两个缓存需要分析选中的信息
	*/
	if(_selected!=undefined && _selected.indexOf(';')>0){
		var number_dialog=_selected.split(';');
		if(number_dialog[1]!=''){
			var number_arr = number_dialog[1].split(',');
			//先清空所有选中缓存
			for(var t in _cache){
				if(!_cache[t])continue;
				if(isNaN(parseInt(t)))continue;
				if(_cache[t].disabled==true)continue;
				_cache[t].isChecked=false;
			}
			//重新选中已出库的记录
			for(var t in number_arr){
				if(isNaN(parseInt(t)))continue;
				_cache[number_arr[t]-1].isChecked=true;
			}
		}
		
	}

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
				title:'卷号选择',
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
		$('[name="selPage"]')[0].checked=false;
	});
	
	//加载前100卷号的数据
	getDataBynumber(1);
	
	getHeji();
});
//清空菜单颜色
function clear(){
	$('.div_caidan').each(function(){
		this.style.background='#ffffff';
	});
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
				//卷号
				t.push("<div class='j'>");
				t.push("<input name='number[]' readonly value='"+(i+1)+"' class='juan form-control'/>");
				t.push("</div>");

				//数量
				t.push("<div class='s'>");
				//debugger;
				t.push("<input name='cntFormat[]' value='' class='cnt form-control' disabled/>");
				//t.push("<input name='madanId[]' value='' type='hidden'/>");
				//t.push("<input name='sonId[]' value='' type='hidden'/>");
				t.push("</div>");

				//数量
				// t.push("<div class='s'>");
				// //debugger;
				// t.push("<input name='cnt_M[]' value='' class='cntM form-control' disabled/>");
				// t.push("</div>");

				//质量
				t.push("<div class='l'>");
				t.push("<input name='lot[]' value='' class='lot form-control' disabled/>");
				//其他按钮
				//t.push("<span style='background-color:lightblue; width:10px; color:red; font-size:12px; cursor:pointer'>×</span>");
				t.push("</div>");

				t.push("<div class='nn'>");
				//debugger;
				t.push("<input type='checkbox' name='sel[]' onclick='ckSel(this)' class='sel'/>");
				t.push("</div>");

				t.push("</div>");//end divDuan

			}
			t.push("</div>");//end divBlock
		}
		t.push("</div>");
	}
	var headArr=[];
	for(var ib=0;ib<5;ib++){
		headArr.push("<div class='headDuan'><div class='j form-control'>卷号</div><div class='s form-control'>数量</div><div class='l form-control'>lot#</div><div class='nn'></div></div>");
	}
	var divHtml="<div class='classHead'>"+headArr.join('')+"</div>";
	divHtml+=t.join('');
	//加载到div
	$('#tab').html(divHtml);
}

//得到总和
function getTotal(){
	var cntHeji=0;
	var cntDuan=0;
	for(var t in _cache){
		if(isNaN(parseInt(t)))continue;
		if(!_cache[t])continue;
		if(_cache[t].disabled==true)continue;
		var _t=_cache[t].cnt;
		if(_cache[t].isChecked==true){
			cntHeji += parseFloat(_t||0);
			if(_t!='')cntDuan++;
		}
	}
	return {cnt:cntHeji,cntDuan:cntDuan};
}
//修改缓存
function changeCache(i,p){
	if(!_cache)_cache=[];
	if(!_cache[p])_cache[p]={"id":'',"ruku2proId":'',"cnt":'',"cntFormat":'',"number":'',"lot":'',"isChecked":false};
	var _t=_cache[p];
	_t.cntFormat=$('[name="cntFormat[]"]').eq(i).val();
	_t.number=$('[name="number[]"]').eq(i).val();
	_t.lot=$('[name="lot[]"]').eq(i).val();
	//合计
	_t.cnt=getHeSplitStr(_t.cntFormat);
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
//选择批号时改变卷号的值
function selectNum(num){
	$('[name="number[]"]').each(function(){
			this.value=num;
			num++;
	});	
}

//加载数据，以开始的number开始加载
function getDataBynumber(num){
	var pos=num-1;
	//清空当前值
	$('[name="cntFormat[]"]').attr('value','');
	$('[name="lot[]"]').attr('value','');
	$('[name="sel[]"]').each(function(){
		this.checked=false;
		this.disabled=false;
	});
	//重新赋值
	if(!_cache)return false;
	for(var t in _cache){
		if(!_cache[t])continue;
		if(t>=pos && t<=(pos+99)){
			var _pos=t%100;
			document.getElementsByName('cntFormat[]')[_pos].value=_cache[t].cntFormat;
			document.getElementsByName('lot[]')[_pos].value=_cache[t].lot;
			if(_cache[t].disabled==1)document.getElementsByName('sel[]')[_pos].disabled=true;
			if(_cache[t].isChecked==1)document.getElementsByName('sel[]')[_pos].checked=true;
		}
	}
}

//全选
function selCheck(n,obj){
		//n=0表示全选本页，n=1表示全选
		var number=parseInt($('[name="number[]"]').eq(0).val())-1;
		for(var k in _cache){
			var pos=parseInt(k);
			if(isNaN(pos))continue;
			if((pos<number || pos>number+99) && n==0)continue;
			if(_cache[pos].isChecked!=obj.checked)sel(pos);
		}
		getHeji();
}
//改变选中出库的缓存与状态
function sel(pos){
	var o=document.getElementsByName('sel[]');
	var isCheck=true;
	var p=pos%100;
	if(_cache[pos].disabled==true)return false;
	if(_cache[pos].isChecked)isCheck=false;
	//如果显示本页，则选中
	var number=parseInt(document.getElementsByName('number[]')[0].value)-1;
	if(pos>=number && pos<=number+99)o[p].checked=isCheck;
	_cache[pos].isChecked=isCheck;	
}

function ckSel(o){
	var pos=$('[name="sel[]"]').index(o);
	var number=parseInt($('[name="number[]"]').eq(pos).val())-1;
	if($('[name="cntFormat[]"]').eq(pos).val()!=''){
		_cache[number].isChecked=o.checked;
		getHeji();
	}
	else o.checked=false;
}
//求合计
function getHeji(){
	var heji=0;
	var ma=0;
	for(var t in _cache){
		if(!_cache[t])continue;
		if(isNaN(parseInt(t)))continue;
		if(_cache[t].disabled==true)continue;
		// if(_cache[t].isChecked==false)continue;
		if(_cache[t].isChecked){
			heji+=parseFloat(_cache[t].cnt)||0;
			if(_cache[t].cntFormat!='')ma++;
		}
			
	}
	//合计显示在页面上
	var hejiStr="总数："+"<font color='red'>"+heji.toFixed(2)+"</font>&nbsp;&nbsp;件数<font color='red'>："+ma+"</font>";
	$('#divHeji').html(hejiStr)
}
//返回给父窗口
function ok(){
	var o=getTotal();
	var strData=getSelect('id')+';'+getSelect('number');
	console.log(getSelect('number'));

	var obj={"data":strData,"ok":1,"cnt":o.cnt,"cntJian":o.cntDuan};
	if(window.opener!=undefined) {
		window.opener.returnValue = obj;
	} else {
		window.returnValue = obj;
	}
	window.close();	
}

/*
*获取选中的码单id
*/
function getSelect(str){
	var ids=[];
	for(var t in _cache){
		if(!_cache[t])continue;
		if(isNaN(parseInt(t)))continue;
		if(_cache[t].disabled==true)continue;
		if(_cache[t].isChecked==true){
			ids.push(_cache[t][str]);
		}
	}
	return ids.join(',');
}

</script>
<style type="text/css">
body{ text-align:left;}
input{ height: 24px !important;padding-left: 3px !important; padding-right: 2px !important;}
td,div {font-size:12px; white-space:nowrap; white-space:nowrap;}
#main {margin: 10px 0px 0px 10px; width:900px;}
.div_caidan{width:100%; border:0px; float:left; font-size:14px; color:#00F; padding:3px 0 0 10px; height:22px; border-bottom:1px solid #CCC; cursor:pointer;}
.j {width:42px; float:left;border:0px solid #000; margin-top: 2px;}
.s {width:54px; float:left;border:0px solid #000; margin-top: 2px;}
.l {width:58px; float:left;border:0px solid #000; margin-top: 2px;}
.nn {width:13px; float:left;border:0px solid #000; margin-top: 2px;}
.classData {clear:both; margin-top:0px;margin-left:3px;}

#toolbar { text-align:center; width:100%; margin-top:5px; margin-bottom:10px;}
.classHead {width:880px;clear:both;text-align:left; font-weight:bold;}
.headDuan{width:176px; float:left;}
#divPage {width:880px; clear:both; overflow: auto;}
#divBlock {width:176px; float:left;}
#divDuan {width:100%; clear:both;}
.sel{ border:0px !important; width:13px !important; height:13px !important;}
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
<table id="buttonTable" align="center" style="white-space:nowrap;">
<tr>

	<td>本页全选</td>
    <td>
    <input type="checkbox" name="selPage" id="selPage" value="0" title="选中该100条记录" onClick="selCheck(0,this)" style="border:0px !important; width:13px !important;height:!important;">&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td>全选</td>
     <td>
    <input type="checkbox" name="selPage" id="selPage" value="1" title="全部选中/全部取消选中" onClick="selCheck(1,this)" style="border:0px !important; width:13px !important;height:!important;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td>    
	<input type="button" class="btn btn-primary" style="height:28px !important;width:60px !important;" id="Submit" name="Submit" value='确定' onClick="ok()">
	<input type="button"class="btn btn-default" style="height:28px !important;width:60px !important;"  id="Back" name="Back" value='取消' onClick="window.returnValue=null;window.close();">
      </td>
	</tr>
</table>
</div>
</form>
</body>
</html>
