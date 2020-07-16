<html>
<head>
  <title>{webcontrol type='GetAppInf' varName='systemName'}</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    {webcontrol type='LoadJsCss' src="Resource/Script/ext/resources/css/ext-all.css"}
    {webcontrol type='LoadJsCss' src="Resource/Css/main1.css"}
    {webcontrol type='LoadJsCss' src="Resource/Script/ext/adapter/ext/ext-base.js"}
    {webcontrol type='LoadJsCss' src="Resource/Script/ext/ext-all.js"}
    {webcontrol type='LoadJsCss' src="Resource/Script/jquery.js"}
	{webcontrol type='LoadJsCss' src="Resource/Script/thickbox/thickbox.js"}
    {webcontrol type='LoadJsCss' src="Resource/Script/thickbox/thickbox.css"}
    {literal}
    <style type="text/css">
    html, body {
        font:normal 12px Arial;
        margin:0;
        padding:0;
        border:0 none;
        overflow:hidden;
        height:100%;
    }
    p {
        /*margin:5px;*/
    }
	#loading-mask {
		background-color:white;
		height:100%;
		left:0;
		position:absolute;
		top:0;
		width:100%;
		z-index:20000;
		background-color:#FFFFFF
	}

	#divMsg {
		width:100px;
		height: 22px;
		float: left;
		font-size: 12px;
		line-height: 22px;
		overflow: hidden;
		white-space: nowrap;
		background: none repeat scroll 0 0 #16960E;
		color : #fff;
		font-weight:bold;
		text-align:center;
		border:1px solid #0C9;
		margin-top:21px;
		margin-left:25%;
		display:none;
	}
    </style>   
    <script type="text/javascript">
    Ext.onReady(function(){
		var imagePath = 'Resource/Script/ext/resources/images';
		Ext.BLANK_IMAGE_URL = imagePath+'/default/s.gif';
		var detailEl;
		var tabs;
		var welcomeUrl='';
		//var treePath = 'Resource/Script/tree-data.json';



		var topHtml = '<div style="width:100%;height:100%; color:white;background-image:url(Resource/Image/bg_top.gif)"><div style="float:left; margin:8px; padding-top:5px; font:16px Arial, Helvetica, sans-serif; font-weight:bold;">{/literal}<img src="Resource/Image/logo2.gif" style="vertical-align:middle; height:27px; font-family:"微软雅黑", "新宋体";" />&nbsp;欢迎进入{webcontrol type='GetAppInf' varName='compName'}信息管理系统！</div><div id="divMsg">aaa</div><div style="float:right; padding-top:20px;"><p style="font-family:Arial, Helvetica, sans-serif; padding-right:5px;"><img src="Resource/Image/huiyi_icon.gif">&nbsp;{$smarty.session.REALNAME}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{literal}<a href="?controller=Login&action=logout" style="color:white;text-decoration: underline;">注销</a> | <a href="javascript:void(0)" onclick="window.close()" style="color:white;text-decoration: underline;">退出</a>{/literal}{literal}</p></div></div>';
		var southTitle = '技术支持：常州创盈高科&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;全国服务电话：0519-86339029';
		var treePanel = new Ext.tree.TreePanel({
			id: 'tree-panel',
			//title: '菜单目录',
			region:'center',
			split: true,
			//height: 400,
			//minSize: 300,
			autoScroll: true,

			// tree-specific configs:
			rootVisible: false,
			lines: false,
			singleExpand: true,
			useArrows: true,

			loader: new Ext.tree.TreeLoader({
				dataUrl:"?controller=tool&action=GetToolMenu"
			}),
			root: new Ext.tree.AsyncTreeNode()
		});

		// Assign the changeLayout function to be called on tree node click.
		treePanel.on('click', function(n){
			//debugger;//alert('右边窗口增加一个tab窗口');
			var sn = this.selModel.selNode || {}; // selNode is null on initial selection
			var desc = "<p style='margin:8px'>"+(n.attributes.desc||'没有使用说明')+'</p>';
			var href = n.attributes.src;
			var id = 'docs-' + n.attributes.id;
			var text = n.attributes.text;
			if(n.leaf && n.id != sn.id){  // ignore clicks on folders and currently selected node
				//Ext.getCmp('content-panel').layout.setActiveItem(n.id + '-panel');
				if(!detailEl){
					var bd = Ext.getCmp('details-panel').body;
					bd.update('').setStyle('background','#fff');
					detailEl = bd.createChild(); //create default empty div
				}
				//detailEl.hide().update(Ext.getDom(n.id+'-details').innerHTML).slideIn('l', {stopFx:true,duration:.2});
				detailEl.hide().update(desc).slideIn('l', {stopFx:true,duration:.2});
			}

			//处理tab
			if(!n.leaf) return ;
			var tab = tabs.getComponent(id);
			if(tab){
				document.getElementById('_frm'+id).src = href;
				tab.show();
			}else{
				tabs.add({
					id:id,
					title: text,
					iconCls: 'tabs',
					html: '<iframe scrolling="auto" frameborder="0" width="100%" height="100%" src="'+href+'" id="_frm'+id+'"> </iframe>',
					closable:true
				}).show();
			}
		});

		var detailsPanel = {
			id: 'details-panel',
			title: '操作提示',
			region: 'south',
			height: 150,
			minSize: 80,
			bodyStyle: 'padding-bottom:15px;background:#eee;',
			autoScroll: true,
			html: '<p class="details-info"></p>'
		};

        var northItem = new Ext.BoxComponent({
			region: 'north',
			height: 45, // give north and south regions a height
			autoEl: {
				tag: 'div',
				html:topHtml
			}
		});
		var southItem = {
			// lazily created panel (xtype:'panel' is default)
			region: 'south',
			id: 'south1',
			title: southTitle,
			layout: 'fit'
			//contentEl: 'south',
			//split: true,
			,height: 27
			//minSize: 100,
			//maxSize: 200,
			//collapsible: true,

			//margins: '0 0 0 0'
		};
		var westItem =  {
			region: 'west',
			//iconCls: 'tabs',
			id: 'west-panel', // see Ext.getCmp() below
			layout: 'border',
			title: '系统菜单',
			split: true,
			width: 200,
			minSize: 175,
			maxSize: 400,
			collapsible: true,
			//margins: '0 0 0 5',
			//layout: {
				//type: 'accordion',
				//animate: true
			//},
			items: [treePanel, detailsPanel]
		};
		var centerItem = new Ext.TabPanel({
			region: 'center', // a center region is ALWAYS required for border layout
			deferredRender: false,
			enableTabScroll:true,
			activeTab: 0,     // first tab initially active
			items: [{
				//contentEl: 'center2',
				title: '系统导航图',
				autoScroll: true,
				html: '<iframe scrolling="auto" frameborder="0" width="100%" height="100%" src="'+welcomeUrl+'"> </iframe>'
			}]
		});
		tabs = centerItem;


        var viewport = new Ext.Viewport({
            layout: 'border',
            items: [northItem, southItem,westItem,centerItem]
        });

		setTimeout(function(){
			Ext.get('loading').remove();
			Ext.get('loading-mask').fadeOut({remove:true});
		}, 500);
    });

	//text表示提示框中要出现的文字，
	//ok表示是显示ok图标还是出错图标
	function showMsg(text,ok) {
		/*var img = 'Resource/Image/ok.gif';
		if(ok===false) {
			img = 'Resource/Image/error.gif';
		}

		var t = '<img src="'+img+'" style="vertical-align:middle;" />' + text;*/
		$('#divMsg').text(text).fadeIn('slow');
		setTimeout(function(){$('#divMsg').fadeOut('fast');}, 2000);
	}
    </script>
    {/literal}
<body>

 <div id="loading-mask" style=""></div>
  <div id="loading">
    <div class="loading-indicator"><img src="Resource/Script/ext/loading.gif" width="32" height="32" style="margin-right:8px;" align="absmiddle"/>正在载入...</div>
  </div>
</body>
</html>
