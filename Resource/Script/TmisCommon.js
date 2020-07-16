/**
该文件中放入一些常用的函数。基本上每个页面都需要调用的。
*/

/**
*固定float的小数位数函数。使用如下：aFloat.fixed(2) 取两位小数
*/
Number.prototype.fixed=function(n){   
  with(Math) return round(Number(this)*pow(10,n))/pow(10,n);
}

String.prototype.ellipse = function(maxLength){
    if(this.length > maxLength){
        return this.substr(0, maxLength-3) + '...';
    }
    return this;
}

Ext.form.DateField.prototype.format = 'Y-m-d';

Ext.BLANK_IMAGE_URL = 'Resource/Script/Lib/Extjs/resources/images/default/s.gif';

//条码处理类
var barCode = function(strCode) {
	this.sourceCode = strCode;
};
barCode.prototype = {
	//取得代表序号的部分,对于10除外
	getBatchNum : function  () {
		var t = this.sourceCode;
		return parseInt(t.substr(t.length-3),10);
	},
	//将batchNum向前增加n,对于10除外
	increaseBatchNum : function (n) {
		var o = this.getBatchNum(this.sourceCode);
		var t = this.sourceCode;
		o += 1000+n;
		return t.substr(0,t.length-3)+o.toString().substr(1);
	},
	//取得代表批次类型的信息,包括21和10两种信息
	getBatchKind : function  () {
	}
}

//标准参数
var Tmis = {
	entrance : 'Index.php',
	actionForFields : 'ExtFields',
	actionForIndex : 'ExtList',
	actionForRemove : 'ExtRemoveByPkvs',
	actionForSave : 'ExtSave'
}

//负责弹出一个Ext.layoutDialog
var TmisDialog = function (config) {
	this.width = config.width;
	this.height = config.height;
	var layoutConfig = {
		title : config.title,
		autoCreate : true,							
		syncHeightBeforeShow: true,
		shadow:true,
		fixedcenter:true,
		center:{autoScroll:false}		
	};
	//在一队多关系的弹出窗口中有用
	if (config.type == 'hasSouth') {
		layoutConfig.south ={
			split:true,	
			initialSize: 120,
			minSize: 100,
			maxSize: 250,			
			collapsible: true
		};
	}
	this.dialog = new Ext.LayoutDialog(Ext.id(), layoutConfig);
	this.dialog.addKeyListener(27, this.dialog.hide, this.dialog);
	//this.dialog.addButton("Build It!", this.getDownload, this);
	this.dialog.resizeTo(config.width, config.height);

	//弹出窗口
	this.show = function (elementPopFrom) {
		this.dialog.show(elementPopFrom);
	}

	//增加按钮
	this.addButton = function(cap,handler,scope) {
		//this.dialog.addButton(cap, this.getDownload, this);
		this.dialog.addButton(cap,handler,scope);		
	}
	this.add = function (pos) {
		var layout = this.dialog.getLayout();		
		layout.add('center', new Ext.ContentPanel('center', {title: 'The First Tab'}));
		layout.add('center',new Ext.ContentPanel(Ext.id(), {
            autoCreate:true, 
			title: 'Another Tab', 
			background:true
		}));
		
	}
};

//定义接口
var TmisCombox = function () {	
};
TmisCombox.prototype = {
	field : undefined,
	getField : function() {
		return this.field;
	},
	getStore : function() {
		return this.field.store;
	}
};

TmisCombox.client = function() {
};
Ext.extend(TmisCombox.client, TmisCombox, {	
	field : new Ext.form.ComboBox({
		store: new Ext.data.Store({
			proxy: new Ext.data.HttpProxy({
				url: 'Index.php?controller=Jichu_Client&action=' + Tmis.actionForIndex
			}),
			reader: new Ext.data.JsonReader({
				root: 'rows',
				totalProperty: 'totalCount',
				id: 'id'
			}, [
				{name: 'id'},
				{name: 'compName'}
			])
		}),
		pageSize:20,					
		fieldLabel: '客户',
		hiddenName:'clientId',					
		displayField:'compName',
		valueField:'id',
		triggerAction: 'all',
		emptyText:'选择客户...',
		selectOnFocus:true,
		editable:false,
		lazyRender:true,
		width:220
	})
});

TmisCombox.supplier = function() {
};
Ext.extend(TmisCombox.supplier, TmisCombox, {	
	field : new Ext.form.ComboBox({
		store: new Ext.data.Store({
			proxy: new Ext.data.HttpProxy({
				url: 'Index.php?controller=Jichu_Supplier&action=' + Tmis.actionForIndex
			}),
			reader: new Ext.data.JsonReader({
				root: 'rows',
				totalProperty: 'totalCount',
				id: 'id'
			}, [
				{name: 'id'},
				{name: 'compName'}
			])
		}),
		pageSize:20,					
		fieldLabel: '供应商',
		hiddenName:'supplierId',					
		displayField:'compName',
		valueField:'id',
		triggerAction: 'all',
		emptyText:'选择供应商...',
		selectOnFocus:true,
		editable:false,
		lazyRender:true,
		width:220
	})
});


TmisCombox.employ = function() {
};
Ext.extend(TmisCombox.employ, TmisCombox, {	
	field : new Ext.form.ComboBox({
		store: new Ext.data.Store({
			proxy: new Ext.data.HttpProxy({
				url: 'Index.php?controller=Jichu_Employ&action=' + Tmis.actionForIndex
			}),
			reader: new Ext.data.JsonReader({
				root: 'rows',
				totalProperty: 'totalCount',
				id: 'id'
			}, [
				{name: 'id'},
				{name: 'employName'}
			])
		}),
		//pageSize:20,					
		fieldLabel: '业务员',
		hiddenName:'employId',					
		displayField:'employName',
		valueField:'id',
		triggerAction: 'all',
		emptyText:'选择业务员...',
		selectOnFocus:true,
		editable:false,
		lazyRender:true,
		width:220
	})
});

TmisCombox.department = function() {
};
Ext.extend(TmisCombox.department, TmisCombox, {	
	field : new Ext.form.ComboBox({
		store: new Ext.data.Store({
			proxy: new Ext.data.HttpProxy({
				url: 'Index.php?controller=Jichu_department&action=' + Tmis.actionForIndex
			}),
			reader: new Ext.data.JsonReader({
				root: 'rows',
				totalProperty: 'totalCount',
				id: 'id'
			}, [
				{name: 'id'},
				{name: 'depName'}
			])
		}),
		//pageSize:20,					
		fieldLabel: '部门',
		hiddenName:'depId',					
		displayField:'depName',
		valueField:'id',
		triggerAction: 'all',
		emptyText:'选择部门...',
		selectOnFocus:true,
		editable:false,
		lazyRender:true,
		width:220
	})
});
