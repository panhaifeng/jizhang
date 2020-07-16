/*2012-11-5 by jeff
tblList.tpl和tblListMore.tpl模板专用的js文件
定义了setCellsWidth函数
扩展了SplitDragZone类。
*/
//将表头的位置和表体的位置对齐,onscroll事件，layoutGrid和setCellWidth触发
function duiqiHead(divGrid) {
	var divHead = $('#divHead',divGrid)[0];
	var divList = $('#divList',divGrid)[0];
	//ie中可能需要设置2次。
	divHead.scrollLeft  = divList.scrollLeft ;
	divHead.scrollLeft  = divList.scrollLeft ;
}

//根据视图的变化重新设置表体的变化。
function layoutGrid(divGrid) {
	divGrid = $(divGrid);
	
	var divHead = $('#divHead', divGrid.eq(0));//document.getElementById('divHead');
	var divList = $('#divList', divGrid.eq(0));//document.getElementById('divList');

	var p = $(divGrid).parent()
	var w = p.width();//outerWidth-2
	var h = p.height();

	var hh = $(divHead).height();

	divGrid.width(w);
	divGrid.height(h);

	divHead.width(w);

	divList.width(w);
	divList.height(h-hh);

	duiqiHead(divGrid);
}
//鼠标移上和out改变className
function fnOver(e) {
	$(this).addClass('rowOver');
	//this.className='rowOver';
	e.cancelBubble = true;
	return ;
}
function fnOut(e) {
	$(this).removeClass('rowOver');
	e.cancelBubble = true;
	return ;
}

function changeCursor(e){
	var mouseX = e.clientX;
	var left = $(this).offset().left;
	var width = $(this).width();//不包括边框
	var cursor='';

	if(mouseX>=left && mouseX<left+5) {
	   //第一列的左边不用变化
	   if($('.headTd',this.parentNode).index(this)>0) cursor='col-resize';
	} else if(mouseX>left+width-2 && mouseX<=left+width) {
	   cursor='col-resize';
	} else {
	   cursor='';
	}
	this.style.cursor=cursor;
}
function changeDateNew(obj){
	var df = document.getElementById('dateFrom');
	var dt = document.getElementById('dateTo');
	var newDate = new Date();
	var y = newDate.getFullYear();
	if(obj.value=='1' || obj.value=='-1'){ //本月
    	var m = newDate.getMonth()+1;
	}
	if(obj.value=='2'){ //上月
    	var m = newDate.getMonth();
    	if(m=='00') {y = y-1; // 若为一月，则年份也要相应减1
    	m = 12;}
	}
    if(m<10) m="0"+m;
	df.value=y+'-'+m+'-'+'01';
	//如果为1、3、5、7、8、10、12一个月为31天
	if(m=='01'||m=='03'||m=='05'||m=='07'||m=='08'||m=='10'||m=='12')
	{
		dt.value=y+'-'+m+'-'+'31';
	}else if(m=='02') { //如果是2月份判断是否闰年
		if((y%4==0 && y%100!=0) || y%400==0){
			dt.value=y+'-'+m+'-'+'29';
		}else{
			dt.value=y+'-'+m+'-'+'28';;
		}
	}else{ //如果为4、6、9、11一个月为30天
		dt.value=y+'-'+m+'-'+'30';
	}
	if(obj.value=='3'){ //本年
		df.value = y+'-01-01';
		dt.value = y+'-12-31';
	}
	if(obj.value=='13') { //全部
		df.value='2010-01-01';
		dt.value=y+'-12-31';
	}

}
//加载页面后，自动对表格中的信息添加qtip提示功能
function qtipToCellContents(){
	//需要自动识别并添加qtip的标识
	var cellType=['A','FONT','SPAN']
	//自动加载鼠标提示信息
	$('.valueTdDiv').live('mouseover',function(){
				//获取当前层的宽,并设置需要添加qtip的字符串宽度
				var minWidth=(parseInt(this.offsetWidth)||0)-9;
				if(minWidth<5)minWidth=5;
				
				//获取当前div中的所有子项，如果存在则分别处理
				var child = $(this).children();
				//如果不存在子项，则对text进行处理
				if(child.length==0){
					var text=trim($(this).text())||'';
					//获取当前字符串占用的像素
					var instance = Ext.util.TextMetrics.Instance(this);
					var itemwidth=instance.getWidth($(this).text());
					//如果字符串的长度大于设置的默认宽度，则需要添加qtip
					if(itemwidth>minWidth)$(this).attr('ext:qtip',text);return;
				}
				//存在子信息
				$(child).each(function(){
						//当前类型
						var typeofthis=this.tagName.toUpperCase();
						for(var k in cellType){
							if(typeofthis==cellType[k]){
								var qtip=$(this).attr('ext:qtip')||'';
								if(qtip=='')qtip=$(this).attr('title')||'';
								if(qtip=='')$(this).attr('ext:qtip',$(this).text());
								break;
							}
						}						
				});
	});	
}
//搜索自动调整
function autoSearchDiv(){
	//没有搜索
	if($('#search_input_div').id=='undefined')return;
	//搜索控件是否太多，出现换行
	if($('#search_input_div').height()>35){
		$('#search_input_div').hide();
		$('#search_height').css({'display':'block'});
	}else{
		return;	
	}
	////////////////////////////////////////
	$('#search_img').click(function(){
		document.getElementById('search_input_div').className='search_input_div';
		$('#search_input_div').show();
	});
	$(document).click(function(e){
		var element = document.all?window.event.srcElement:arguments[0].target;
			var isParent=true;
			var isDiv=false;
			while(isParent==true){
				if(element.id=='search_input_div' || element.id=='search_img'){
					isParent=false;
					isDiv=true;
				}else{
					element=element.parentNode;
				}
				if(!element.parentNode)isParent=false;
			}
			//隐藏搜索内容
			if(isDiv==false)$('#search_input_div').hide();
	});
}
//设置表格的各列宽度
function setCellsWidth(divGrid,jsonHead){
	var tblHead = $('#tblHead',divGrid)[0];//document.getElementById('tblHead');
	var divHeadOffset = $('#divHeadOffset',divGrid)[0];//document.getElementById('divHeadOffset');
	var divRow = $('[id="divRow"]',divGrid);//document.getElementsByName('divRow');
	var tblRow = $('[id="tblRow"]',divGrid);//document.getElementsByName('tblRow');
	var cntRows = tblRow.length;
	var h = jsonHead;

	var i=0;
	var t = 0;
	var width = [];
	for(var k in h) {
	  var w = 100;
	  if(typeof(h[k])=='object' && h[k].width) {
		  w = parseInt(h[k].width);
	  } else {
		  if(k=='_edit' && cntRows>0) {
			  //如果是_edit需要自适应宽度
			  var tempDiv = $('#divTemp');
			  if(tempDiv.length==0) {
				  tempDiv = $('<div id="divTemp" style="position:absolute;white-space:nowrap;left:100px;top:100px; border:1px;display:block;visibility:hidden;padding-width:3px 3px 3px 5px;">'+tblRow[0].rows[0].cells[i].innerHTML+'</div>').appendTo('body');
			  } else {
				  tempDiv.html(tblRow[0].rows[0].cells[i].innerHTML);
			  }			  
			  var _w = tempDiv.text()==''?100:tempDiv[0].offsetWidth;
			  //_w = _w<100?100:_w;
			  tempDiv.html('');
			  w = _w+4;
			  if(w<20) w=100;
		  }
	  }

	  width[i] = (w-2)+'px';
	  // debugger;
	  t+=w;
	  //w = (w-2)+'px';
	  if(tblHead.rows[0].cells[i]) tblHead.rows[0].cells[i].style.width=width[i];
	  i++;
	}
	// debugger;
	tblHead.style.width=t+'px';divHeadOffset.style.width=(t+19)+'px';//divHeadOffset.style.width=

	for(var i=0;width[i];i++) {
		if(tblHead.rows[0].cells[i]) tblHead.rows[0].cells[i].style.width=width[i];
	}
	for (var i=0;i<cntRows ;i++ ){
	  divRow[i].style.width = t+'px';
	  tblRow[i].style.width = t+'px';
	  var row = tblRow[i].rows[0];
	  for(var j=0;width[j];j++) {
		if(row.cells[j]) row.cells[j].style.width= width[j];
	  }
	}
}

SplitDragZone = Ext.extend(Ext.dd.DDProxy, {
	constructor: function(divGrid){
		this.divGrid = $(divGrid);
		this.tblHead = $('#tblHead',divGrid)[0];

		var id = 'spliter_'+this.divGrid[0].id;

		this.divGrid.append("<div class='spliter' id='"+id+"'></div>");

		this.proxy = Ext.get(id);//必须使用Ext.get函数，将dom封装为ext的基本元素。

		this._spliter = $('.spliter',this.divGrid);
        SplitDragZone.superclass.constructor.call(this, this.tblHead,
            'gridSplitters_'+this.divGrid[0].id, {//draggroup
            dragElId : id, resizeFrame:false
        });
        this.scroll = false;
        this.hw = 5;
    },

    b4StartDrag : function(x, y){
		var h = this._spliter.parent().height();
        this.proxy.setHeight(h);
        this.resetConstraints();
        this.setXConstraint(75, 1000);
        this.setYConstraint(0, 0);
        this.minX = x- ($(this.tdHead).outerWidth() - 50);//最小列宽为50
        this.maxX = x + 1000;
        this.startPos = x;
        Ext.dd.DDProxy.prototype.b4StartDrag.call(this, x, y);
		//alert(1);
    },

	//tblhead的mousedown触发次事件。
	//如果要判断第一列不允许拖动，需要在此进行判断，参考extjs.all.js的73841行
	//必须定义这个函数，否则，endDrag函数中接收到的e.target就是spliter,而不是我们想要的元素
    handleMouseDown : function(e){
		//确保this.tdHead为要调整列宽的列
		var t = e.getTarget();
		if(t.className=='headTdDiv') {//确保t为headTd
			t = t.parentNode;
		};
		//确保t是需要调整列宽的td,考虑各种特殊情况
		var mouseX = e.getXY()[0];
		var gridLeft = this.divGrid.offset().left;
		var gridWidth = this.divGrid.outerWidth();//不包括边框
		var cellWidth = $(t).outerWidth();//包括td边框的宽度
		var cellLeft = $(t).offset().left;

		if(mouseX<gridLeft+5) {//如果是在grid左边的附件,表示是对第一列的左线进行调整，应该禁止。
			return;
		}
		if(mouseX>gridLeft+gridWidth-1) {//如果超出了grid的右边，禁止
			return;
		}
		if(mouseX>=cellLeft+5&&mouseX<cellLeft+cellWidth-2) {
			return;
		}
		if(mouseX>=cellLeft&&mouseX<cellLeft+5) {
			t=$(t).prev()[0];
		}
		this.tdHead = t;
		//debugger;
		SplitDragZone.superclass.handleMouseDown.apply(this, arguments);
    },

    endDrag : function(e){
		//根据this.tdHead来确定改变哪个列宽,tdHead可能是headTdDiv，可能是headTd
		//得到应该改变的td的index
		var i = $('.headTd',this.divGrid).index(this.tdHead);
		//得到应变宽度
		var width = $(this.tdHead).outerWidth();//td的边框为1,
		var endX = Math.max(this.minX, e.getXY()[0]),
			diff = endX - this.startPos;
		//设置tbl列宽
		this.setCellWidth(i,width+diff);
    },
    autoOffset : function(){
        this.setDelta(0,0);
    },
	setCellWidth : function(cellIndex,width){
		var divGrid = this.divGrid;
		var tblHead = this.tblHead;//document.getElementById('tblHead');
		var divHeadOffset = this.tblHead.parentNode;
		var divRow = $('[id="divRow"]',divGrid);//document.getElementsByName('tblRow');
		var cntRows = divRow.length;

		//得到总宽度
		var me = this;
		var diff = width-$($('.headTd',this.divGrid)[cellIndex]).outerWidth();
		var t=$(tblHead).outerWidth()+diff;

		//设置head宽度
		tblHead.style.width=t+'px';

		divHeadOffset.style.width=(t+19)+'px';//tblHeadOffset的宽度

		if(tblHead.rows[0].cells[cellIndex]) tblHead.rows[0].cells[cellIndex].style.width=(width-2)+'px';
		//设置表体宽度
		for (var i=0;i<cntRows ;i++ ){
		  divRow[i].style.width=t+'px';
		  var tbl = $('#tblRow',divRow[i])[0];
		  tbl.style.width=t+'px';
		  var row = tbl.rows[0];
		  if(row.cells[cellIndex]) row.cells[cellIndex].style.width= (width-2)+'px';
		}
		duiqiHead(divGrid);
	}
});