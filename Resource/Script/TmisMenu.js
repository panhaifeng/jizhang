window.tmisMenu = new Array();
//配置2222
var MenuConfig = {
	//firstMenu:new TmisMenu(),	//系统定义的第一个菜单,必须为容器(isDirectory=true)	
	bgColor:"#fff",	//设置菜单背景颜色
	leftBgColor:"#c9defa",
	overBgColor:"#c3daf9",	//设置菜单鼠标经过时的背景颜色
	fontSize:"13px",	//设置菜单字体大小
	fontColor:"#000",	//设置菜单字体颜色
	perMenuHeight:"25px",	//调整菜单的行距
	isIE:document.all?true:false,
	url:null,//ajax服务器地址	
	branchSelectable:false,//非叶子是否允许被选择
	callback : null//选择后的回调函数
}
if(!MenuConfig.isIE) {
	HTMLElement.prototype.contains=function(Node){// 是否包含某节点
		if(!Node) return false;
		do if(Node==this)return true;
		while(Node=Node.parentNode);
		return false;
	}
}
//节点类
var TmisItem = function (config) {
	this.value=config.value;	//节点代表的值	
	this.isDirectory=config.isDirectory;	//是否是目录
	this.text=config.text;
	this.targetObj = null;//选择后需要设置值得html对象
	this.htmlObj = null;//对应的tr对象
	this.menuObj = null;//包含该item的menu对象.
	this.subMenu = null;//该子节点包含的子菜单对象
	this.json = config.json;//原始的节点json对象。
	//this.Handle=handle?handle:"";	//单击时所执行的语句,目录不支持此属性
}
TmisItem.prototype = {
	click : function () {
		var obj = this.DataObj;			
		if (MenuConfig.branchSelectable||(!MenuConfig.branchSelectable&&!obj.isDirectory)){
			obj.targetObj.value = obj.value;
			if (typeof(MenuConfig.callback)=='function') MenuConfig.callback(obj);
		}
		return;
	},
	insertSubMenu : function (menu) {
	},
	mouseOver : function () {
		//改变tr的背景色
		this.style.backgroundColor=MenuConfig.overBgColor;
		//if directory,显示下一级菜单
		var menu=this.DataObj.menuObj;
		var len=menu.items.length;
		//隐藏所有的子菜单
		for(var i=0;i<len;i++)
		{
			if(menu.items[i].isDirectory&&menu.items[i].subMenu)
			{
				menu.items[i].subMenu.hidden();
			}
		}
		//显示子菜单
		if(this.DataObj.isDirectory)
		{			
			var dataObj = this.DataObj;
			if(!this.DataObj.subMenu) {
				//开始ajax对象.创建this.DataObj.subMenu
				//alert('开始ajax');
				//debugger;
				var e = window.event||arguments[0];
				if (this.contains(e.fromElement||e.relatedTarget)) return false;
				$.getJSON(MenuConfig.url,{parentId:dataObj.value},function (json) {					
					var newMenu = new TmisMenu(json,dataObj.targetObj,dataObj);
					dataObj.subMenu = newMenu;
					dataObj.subMenu.create();
					dataObj.subMenu.show();
				});				
			} else {
				///debugger;
				dataObj.subMenu.show();
			}
		}
	},
	mouseOut : function () {
		this.style.backgroundColor=MenuConfig.bgColor;
	}
}

//菜单类
//tarobj为需要改变值的html元素
//config为从ajax中取得的描述菜单的对象.如aMenu
var TmisMenu = function (config,tarObj,parentItem) {
	this.config=config;
	this.items=new Array();	//存储节点的数组
	//debugger;
	this.parentItem=parentItem?parentItem:null;	//父节点
	this.htmlObj=null;	//关联HTML对象
	this.targetObj = tarObj;
	this.noHideObj = new Array();
	this.noHideObj.push(this.targetObj);
	this.zIndex = 900;//防止被其他的元素覆盖住
	
	//与window对象相关联,在隐藏所有的menu对象时有用	
	if(!parentItem) {
		window.tmisMenu[window.tmisMenu.length] = this;
	}	
	
	//开始构建menu对象
	var items = config.items;
	//如果不是异步,则用递归对items进行处理.
	for(var i=0;i<items.length;i++) {
		var it = new TmisItem(items[i]);
		it.targetObj = tarObj;
		this.items[i] = it;
	}
}
TmisMenu.prototype = {
	 create : function () {
		var ParentElement=document.createElement("span");
		this.htmlObj=ParentElement;	//关联子菜单的HTML对象容器
		ParentElement.style.cursor="default";
		ParentElement.style.position="absolute";
		//ParentElement.style.visibility="hidden";
		ParentElement.style.display="none";
		ParentElement.style.zIndex=this.zIndex;
		ParentElement.style.border="1px solid #464646";	
		ParentElement.style.borderRight="1px solid #aaa";
		ParentElement.style.borderBottom="1px solid #aaa";
		ParentElement.style.borderTop="1px solid #fff";
		ParentElement.style.borderLeft="1px solid #fff";
		ParentElement.onmousedown=function(e)
		{
			MenuConfig.isIE?window.event.cancelBubble=true:e.stopPropagation();
		}
		ParentElement.onselectstart=function()
		{
			return false;
		}		
		var table=document.createElement("table");
		table.cellPadding=0;
		table.cellSpacing=0;
		var tbody=document.createElement("tbody");
		var tr=document.createElement("tr");
		var ltd=document.createElement("td");
		var rtd=document.createElement("td");				
		ltd.style.width="25px";
		ltd.style.background = MenuConfig.leftBgColor;
		//ltd.style.backgroundImage="url(http://www.fjcjhr.com/bg.gif)";
		ltd.innerHTML=MenuConfig.isIE?"<pre></pre>":"<pre></pre>";
		tr.appendChild(ltd);
		tr.appendChild(rtd);
		tbody.appendChild(tr);
		table.appendChild(tbody);
		ParentElement.appendChild(table);
		var len=this.config.items.length;
		if(len>0){
			var ChildTable=document.createElement("table");
			var ChildTBody=document.createElement("tbody");
			ChildTable.border=0;
			ChildTable.cellPadding=0;
			ChildTable.cellSpacing=0;
			ChildTable.style.fontSize=MenuConfig.fontSize;
			ChildTable.style.color=MenuConfig.fontColor;
			ChildTable.appendChild(ChildTBody);
			rtd.appendChild(ChildTable);
		}
		for(var i=0;i<len;i++) {
			var tempTr=document.createElement("tr");
			//关联HTML对象和DATA对象
			this.items[i].htmlObj=tempTr;	//关联子菜单的HTML对象
			this.items[i].menuObj = this;
			tempTr.DataObj=this.items[i];
			var tempTd=document.createElement("td");
			tempTr.style.backgroundColor=MenuConfig.bgColor;
			tempTr.appendChild(tempTd);
			tempTd.style.height=MenuConfig.perMenuHeight;
			tempTd.vAlign="middle";
			tempTd.style.paddingLeft="5px";
			tempTd.style.paddingRight="5px";
			tempTr.onmouseover=this.items[i].mouseOver;
			tempTr.onmouseout=this.items[i].mouseOut;
			tempTr.onclick=this.items[i].click;
			tempTd.innerHTML="<nobr>"+this.items[i].text+"</nobr>";
			var DirectoryTd=document.createElement("td");
			if(this.items[i].isDirectory)
			{
				DirectoryTd.innerHTML="<font face='webdings'>4</font>";
			}
			tempTr.appendChild(DirectoryTd);
			ChildTBody.appendChild(tempTr);
		}
		document.body.appendChild(ParentElement);
	 },
	 show : function () {
		//创建html对象
		//if(!this.targetObj.targetMenu.htmlObj&&this.parentItem==null) {
			
		//}
		//取得位置信息
		if(this.items.length==0)	return;
		var ChildHTMLObj=this.htmlObj;
		var DWidth=document.body.clientWidth;
		var DHeight=document.body.clientHeight;
		var left=document.body.scrollLeft,top=document.body.scrollTop;
		var x,y;
		if(this.parentItem==null)	//根对象
		{
			//x=e.clientX,y=e.clientY;
			//x = this.targetObj.offsetLeft,y = this.targetObj.offsetTop+this.targetObj.offsetHeight;
			x = getLeft(this.targetObj);y = getTop(this.targetObj)+this.targetObj.offsetHeight;
			if(x+ChildHTMLObj.offsetWidth>DWidth)
			{
				x-=ChildHTMLObj.offsetWidth;
			}
			if(y+ChildHTMLObj.offsetHeight>DHeight)
			{
				y-=ChildHTMLObj.offsetHeight;
			}
			//x+=left;
			//y+=top;
		}
		else
		{
			//debugger;
			var CurrentHTMLObj=this.parentItem.htmlObj;
			var x=this.getMenuPositionX(CurrentHTMLObj)+CurrentHTMLObj.offsetWidth,y=this.getMenuPositionY(CurrentHTMLObj);
			if(x+ChildHTMLObj.offsetWidth>DWidth+left)
			{
				x-=(CurrentHTMLObj.offsetWidth+ChildHTMLObj.offsetWidth);
			}
			if(y+ChildHTMLObj.offsetHeight>DHeight+top)
			{
				y-=ChildHTMLObj.offsetHeight;
				y+=CurrentHTMLObj.offsetHeight;
			}
		}
		ChildHTMLObj.style.left=x;
		ChildHTMLObj.style.top=y;		
				
		//this.htmlObj.style.visibility="visible";
		$(this.htmlObj).show();
		//this.htmlObj.style.display="";
	 },
	 hidden : function () {		
		var len=this.items.length;
		for(var i=0;i<len;i++)
		{
			if(this.items[i].isDirectory)
			{
				if(this.items[i].subMenu) {
					this.items[i].subMenu.hidden();
				}				
			}
		}
		//this.htmlObj.style.visibility="hidden";
		$(this.htmlObj).hide();
	 },
	 clear : function () {
	 },
	 getMenuPositionX : function (obj) {
		var ParentObj=obj;
		var left;
		left=ParentObj.offsetLeft;
		while(ParentObj=ParentObj.offsetParent){
			left+=ParentObj.offsetLeft;
		}
		return left;
	 },
	 getMenuPositionY : function (obj) {
		var ParentObj=obj;
		var top;
		top=ParentObj.offsetTop;
		while(ParentObj=ParentObj.offsetParent){
			top+=ParentObj.offsetTop;
		}
		return top;
	 },
	 //click某个控件,触发document.onclick事件，如果不想单机时隐藏menu，使用此方法，比如按钮控件现实menu
	 setNoHide : function(obj) {
		for (var i=0;i<this.noHideObj.length ;i++ ){
			if (this.noHideObj[i]==obj) return;
		}
		this.noHideObj.push(obj);
	 }
}

document.onclick=function()	{
	var len = window.tmisMenu.length;
	//var event = arguments[0] || window.event;    //firefox || ie  事件对象
   // var element = event.target || event.srcElement;   //firefox || ie 触发对象
	var element = MenuConfig.isIE?window.event.srcElement:arguments[0].target;
	//debugger;
	for(var i=0;i<len;i++) {
		if(element!=window.tmisMenu[i].targetObj) {
			//判断element是否是避免触发hidden的对象。
			for (var j=0;j<window.tmisMenu[i].noHideObj.length ;j++ ){
				if (window.tmisMenu[i].noHideObj[j]==element) return;
			}
			window.tmisMenu[i].hidden();
		}		
	}
}

function tMenu(e,url,parentItemId,branchSelectable,callback) {
	var params;
	var element = MenuConfig.isIE?window.event.srcElement:arguments[0].target;
	if (!MenuConfig.url) MenuConfig.url = url;
	if (branchSelectable) {MenuConfig.branchSelectable = branchSelectable};
	if(typeof(callback)=='function') MenuConfig.callback = callback;
	if(!parentItemId) params = {parentId:0};
	else params = {parentId:parentItemId};
	if(!e.targetMenu) {	
		$.getJSON(url,params,function (json) {
		    if (!json) return false;
			var newMenu = new TmisMenu(json,e);
			//将触发显示的当前控件设置为不触发hidden事件
			newMenu.setNoHide(element);
			newMenu.create();
			e.targetMenu = newMenu;
			e.targetMenu.show();
		});		
	} else e.targetMenu.show();
}