//每个menu.item左边的空格图像
Ext.BLANK_IMAGE_URL = 'Resource/Script/Lib/Extjs/resources/images/default/s.gif';
var Docs = function(){
	        return {
	            init : function(){
	               var layout = new Ext.BorderLayout(document.body, {
						north: {
							split:false,
							initialSize: 28
						},						
						west: {		
							title: '快捷栏',
							split:true,
							initialSize: 120,
							minSize: 80,
							maxSize: 200,
							titlebar: true,
							collapsible: true,
							animate: true,
							useShim:true,
							cmargins: {top:2,bottom:2,right:2,left:2}
						},						
						center: {
							//titlebar: true,
							//title: '数据处理',
							autoScroll:false,
							tabPosition: 'top',
							closeOnTab: true,
							//alwaysShowTabs: true,
							resizeTabs: true
						}
	                });
	                layout.beginUpdate();
	                
	                layout.add('north', new Ext.ContentPanel('header',{fitToFrame:true}));					
					layout.add('west', new Ext.ContentPanel('menu', {fitToFrame:true}));
					center = layout.getRegion('center');
					center.add(new Ext.ContentPanel('main_container', {fitToFrame:true}));		
					layout.restoreState();
					layout.endUpdate();
					
					//载入页面
					var loading = Ext.get('loading');
					var mask = Ext.get('loading-mask');
					mask.setOpacity(.8);
					mask.shift({
						xy:loading.getXY(),
						width:loading.getWidth(),
						height:loading.getHeight(), 
						remove:true,
						duration:1,
						opacity:.3,
						easing:'bounceOut',
						callback : function(){
							loading.fadeOut({duration:.2,remove:true});
						}
					});
	           }
	     };
	       
	}();
	Ext.onReady(function() {
		Docs.init();
		Ext.QuickTips.init();	
		
		
		//定义采购管理菜单
		var menuBase = new Ext.menu.Menu({
			id : "menuBase",
			items : [
				{text : '产品资料',handler:function(){loadDoc('Index.php?controller=Jichu_Product&action=extIndex');}},				
				//{text : '供应商资料',handler:function(){loadDoc('Index.php?controller=Jichu_Supplier&action=extIndex');}},
				{text : '客户资料',handler:function(){loadDoc('Index.php?controller=Jichu_Client&action=extIndex');}}
			]
		});		
		
		var menuStorage = new Ext.menu.Menu({
			id : 'menuStorage',
			items : [
				{text : '库存初始化',handler:function(){loadDoc('Index.php?controller=Cangku_InitProduct&action=extIndex');}},
				{text : '产品入库',handler:function(){loadDoc('Index.php?controller=Cangku_Ruku&action=extIndex');}},				
				{text : '销售出库',handler:function(){loadDoc('Index.php?controller=Cangku_Chuku&action=extIndex');}}				
			]
		});	
		
		var menuCaiwu = new Ext.menu.Menu({
			id : 'menuCaiwu',
			items : [
				//{text : '提货单管理',handler:function(){loadDoc('Index.php?controller=Caiwu_DingDan&action=extIndex');}},
				{text : '收款管理',handler:function(){loadDoc('Index.php?controller=Sell_ShouKuan&action=extIndex');}},
				{text : '退货管理',handler:function(){loadDoc('Index.php?controller=Caiwu_Return&action=extIndex');}}
			]
		});
		var menuReport = new Ext.menu.Menu({
			id : 'menuReport',
			items : [				
				{text : '库存报表',handler:function(){loadDoc('Index.php?controller=Cangku_AnalyseProduct&action=extIndex');}},
				//{text : '月末盘点报表',handler:function(){loadDoc('Index.php?controller=Report_PanDianMonth&action=extIndex');}},
				{text : '销售分析报表',handler:function(){loadDoc('Index.php?controller=Report_AnalyseSell&action=extIndex');}},
				{text : '收款分析报表',handler:function(){loadDoc('Index.php?controller=Report_AnalyseSell&action=extYingshou');}}

			]
		});
		
		
		var menuPerson = new Ext.menu.Menu({
			id : 'menuPerson',
			items : [				
				{text : '部门资料',handler:function(){loadDoc('Index.php?controller=Jichu_Department&action=extIndex');}},
				{text : '员工资料',handler:function(){loadDoc('Index.php?controller=Jichu_Employ&action=extIndex');}}
			]
		});
		
		
		var menuSys = new Ext.menu.Menu({
			id : 'menuSys',
			items : [
				{text : '权限管理' , menu : {
					items : [
						{text : '用户管理',handler:function(){loadDoc('Index.php?controller=Acm_User&action=extIndex')}},
						{text : '角色管理',handler:function(){loadDoc('Index.php?controller=Acm_Role&action=extIndex')}}
						//,{text : '模块管理',handler:function(){alert('本模块仅作配置用,不建议开放给客户使用');loadDoc('Index.php?controller=Acm_Func&action=Index')}}
					]
				}},				
				'-',
				{text : '修改密码',handler:function(){loadDoc('Index.php?controller=Acm_User&action=changePwd')}}
				
			]
		});		

		var menuHelp = new Ext.menu.Menu({
			id : 'menuHelp',
			items : [		
				{text : '帮助',handler:function(){window.open("Help/index.html");}},
				'-',
				{id: 'aboutEq' ,text : '关于陆信',handler:function(){					
					var msg = '公司地址: 常州武青北路5号金隆大厦<br>'
							+'联 系 人: 史先生/13775052508<br>'
							+'邮   编: 	213003<br>'
							+'传   真: 	0519-6640433<br>'
							+'E-mail:  shx@eqinfo.com.cn   shihaoxin@gmail.com<br>';
					Ext.MessageBox.show({
					   title: '关于陆信',
					   msg: msg,
					   width:300,
					   buttons: Ext.MessageBox.OK,
					   //multiline: true,					   
					   animEl: this.getEl()
				   });
				}},				
				{text : '发送反馈意见',handler:function(){
					window.open("http://www.eqinfo.com.cn/cn/LeaveWord.asp");
				}}
			]
		});
		
		
		var tb = new Ext.Toolbar('toolbar');
		tb.add(
			{
				cls: 'x-btn-text-icon bmenu', // icon and text class
				text:'基础资料',
				menu: menuBase  // assign menu by instance
			},
			{
				cls: 'x-btn-text-icon bmenu', // icon and text class
				text:'库存管理',
				menu: menuStorage  // assign menu by instance
			},
			{
				cls: 'x-btn-text-icon bmenu', // icon and text class
				text:'财务管理',
				menu: menuCaiwu  // assign menu by instance
			},
			{
				cls: 'x-btn-text-icon bmenu', // icon and text class
				text:'报表分析',
				menu: menuReport  // assign menu by instance
			},
			{
				cls: 'x-btn-text-icon bmenu', // icon and text class
				text:'人事管理',
				menu: menuPerson  // assign menu by instance
			},
			{
				cls: 'x-btn-text-icon bmenu', // icon and text class
				text:'系统设置',
				menu: menuSys  // assign menu by instance
			},
			{
				cls: 'x-btn-text-icon bmenu', // icon and text class
				text:'帮助',
				menu: menuHelp  // assign menu by instance
			},
			'-',			
			{text : '注销',handler:function(){window.location.href='Index.php?controller=login&action=Logout';}},
			{text : '退出',handler:function(){
				Ext.MessageBox.confirm(
					'退出', 
					'Are you sure you want to do that?', 
					function(btn){
						//debugger;
						if (btn == 'yes'){
        					window.close();
    					}
					}
				);				
			}}
		);
	});