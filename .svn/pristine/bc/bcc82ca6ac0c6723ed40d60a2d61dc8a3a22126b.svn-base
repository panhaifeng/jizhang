<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
{webcontrol type='LoadJsCss' src="Resource/Script/ext/resources/css/ext-all.css"}
{webcontrol type='LoadJsCss' src="Resource/Script/ext/adapter/ext/ext-base.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/ext/ext-all.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/ext/ux/Portal.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/ext/ux/PortalColumn.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/ext/ux/Portlet.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/ext/ux/css/Portal.css"}
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/Common.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/thickbox/thickbox.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/thickbox/thickbox.css"}
{literal}
<script language="javascript">  
  Ext.onReady(function(){
	$(".tongzhi").click(function(){
		var win = Ext.getCmp("win");
		if(!win){
            win = new Ext.Window({
                id:'win',
                layout:'fit',
                width:500,
                height:300,
                closeAction:'close',
                plain: true,
				title:'详细内容',
				autoScroll: true,
				padding:'5 2 2 2',
				modal:true,
				html:'从ajax中取得通知内容',
				//autoLoad:'?controller=main&action=GetContentByAjax'
                /*items: new Ext.TabPanel({
                    applyTo: 'hello-tabs',
                    autoTabs:true,
                    activeTab:0,
                    deferredRender:false,
                    border:false
                }),*/

                buttons: [{
                    text: '关闭',
                    handler: function(){
                        win.close();						
                    }
                }]
				//,afterHide:function(){win=false;}
            });
        }
		var url="?controller=main&action=GetContentByAjax";
		var param={id:this.id};
		var me = this;
		$.getJSON(url,param,function(json){
			win.html=json.content;
			win.setTitle(json.title+"("+json.dt+")");
        	win.show(me);
		});
		
	});
	
	//创建信息窗口
	var tools = [{
        id:'close',
        handler: function(e, target, panel){
            panel.ownerCt.remove(panel, true);
        }
    }];	
	var viewport = new Ext.Viewport({
        layout:'border'
		,onRender : function(){
			renderForm(document.getElementById('form1'));
		}
        ,items:[{
            xtype:'portal'
            ,region:'center'
            ,margins:'-1 -1 -1 -1'			
			// ,tbar:[
			//  {xtype: 'tbtext', contentEl:'searchForm'}
			// // ,new Ext.Button({'text':'bbb'})
			//  /*,{text: ' 刷 新 ',iconCls: 'x-tbar-loading',cls:'x-btn-mc',handler:function(){
			// 	window.location.href=window.location.href
			//  }}*/
			//  //icon:'Resource/Script/ext/resources/images/default/dd/drop-add.gif' 
			//  ,{
			// 	text: '<b>快速跟踪</b>'
			// 	//cls:'x-btn-default-small'
			// 	,ctCls: 'x-btn-over'
			// 	//,pressed:true
			// 	,overCls: 'x-btn-pressed'
			// 	,icon:'Resource/Script/ext/resources/images/default/dd/drop-add.gif',
			// 	//cls: 'x-btn',
			// 	//iconCls: 'x-icon-btn-ok',
			// 	handler: function(){
			// 		$('#form1').submit();
			// 	}
			// }
		//{text: '快速跟踪',iconCls:'btnSub',width:80}
			 //,'->'			 
			// ]
			,defaults:{defaults:{height:250}}
			//不需要关闭按钮，所以取消tools
			//defaults:{defaults:{height:250,tools:tools}},
            ,items:[{
                columnWidth:.4,
                style:'padding:10px 0 10px 10px',
                items:[{
                    title: '行政通知',
                    layout:'fit',
					autoScroll: true,
					contentEl:'div1'
                },{
                    title: '交期预警(近7天需交货)',
					autoScroll: true,
                    contentEl:'div2'
                }]
            },{
                columnWidth:.4,
                style:'padding:10px 0 10px 10px',
                items:[{
                    title: '生产通知',
					autoScroll: true,
                    contentEl:'div3'
                },{
                    title: '已逾期订单',
					autoScroll: true,
                    contentEl:'div4'
                }]
            },{
                columnWidth:.2,
                style:'padding:10px',
                items:[{
                    title: '快捷方式',
					height:510,
					autoScroll: true,
                    contentEl:'div5'
                }]
            }]
        }]
    });
	
  });
/*function myrefresh(){
	window.location.reload();
}
$(function(){
	setInterval('myrefresh()',30000);
});*/
</script>
<style type="text/css">
.div div {
	margin:5px;
	white-space:nowrap;
	line-height:130%;
}
#searchForm input,#searchForm select{margin-left:5px;margin-right:5px; font-size:13px;}
#searchForm input { height:18px; width:150px; line-height:18px; padding-left:3px;}
#searchForm select { width:150px;}

.btnSub{  
  background-image: url(Resource/Script/ext/resources/images/default/dd/drop-add.gif)!important;  
} 
a{}
a:link{color:#0000FF;text-decoration:none;}
a:visited{color:#0000FF;text-decoration:none;}
a:hover{color:#0000FF;text-decoration:underline;}
</style>
{/literal}

<body style='position:static'>
<div id='div1' class='x-hide-display div'> {foreach from=$tongzhi_xingzheng item=item}
  <div><span style='margin-right:10px;'>{$item.buildDate}</span>  {$item.title}</div>
  {/foreach} </div>
<div id='div2' class='x-hide-display div'> {foreach from=$tongzhi_jiaohuo item=item}
  <div>{$item.orderCode}</div>
  {/foreach} </div>
<div id='div3' class='x-hide-display div'> {foreach from=$tongzhi_biandong item=item}
  <div>{$item.title}</div>
  {/foreach} </div>
<div id='div4' class='x-hide-display div'> {foreach from=$tongzhi_yuqi item=item}
  <div>{$item.orderCode}</div>
  {/foreach} </div>
<div id='div5' class='x-hide-display div'> {foreach from=$baobiao item=item}
  <div><a href='{$item.src}' target="blank">{$item.text}</a></div>
  {/foreach} </div>
<div id='searchForm' class='x-hide-display div'>
	<form name="form1" id="form1" action="{url controller='Trade_Order' action='Trace'}"  method="post" target="_blank">
     {include file='Search/traderId.tpl'}
     {include file='Search/clientId.tpl'}
	 {include file='Search/huaxing.tpl'}
     {include file='Search/orderCode.tpl'}     
    </form>
</div>
</body>
</html>
