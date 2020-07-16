<html xmlns="http://www.w3.org/1999/xhtml">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$title}</title>
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.js"}
<script src="Resource/Script/treeview/tree.js" type="text/javascript" ></script>
<script src="Resource/Script/Common.js" type="text/javascript" ></script>
<link href="Resource/Script/treeview/tree.css" type="text/css" rel="stylesheet">
<!--<link href="Resource/Css/Main.css" type="text/css" rel="stylesheet">-->
{literal}
<head>
    <title>Big Tree</title>
	
    <script type="text/javascript">
		var _cacheFunc = new Array();//当前角色拥有的node
		var _cacheMap = {/literal}{$map}{literal};
		var _cRow = null;//当前被选中的行
		var o = {
			//url: "?controller=Acm_Func&action=GetTreeJson" ,
			showcheck: true, //是否显示选择
			oncheckboxclick: false, //当checkstate状态变化时所触发的事件，但是不会触发因级联选择而引起的变化
			onnodeclick: false,
			cascadecheck: true,
			data: null,
			clicktoggle: true, //点击节点展开和收缩子节点
			theme: "bbit-tree-arrows" //bbit-tree-lines ,bbit-tree-no-lines,bbit-tree-arrows
		};
		//o.initData = {/literal}{$data}{literal};
		o.data = {/literal}{$data}{literal};
		//_cacheMap =
		//debugger;
        $(document).ready(function() {
			//_getData('44');
            $("#tree").treeview(o);
			$('tr[id="trRole"]').click(function(){
				if(_cRow) _cRow.style.backgroundColor='#efefef';
				_cRow = this;
				_cRow.style.backgroundColor = 'lightgreen';
				_cacheFunc = new Array();
				//单击某个角色，将这个角色拥有的所有funcId存入缓存中，然后根据缓存中的值，刷新树的被选中状态
				var url='?controller=Acm_Func&action=GetRoleQx';
				var param={roleId:$(this).attr('val')};
				$.getJSON(url,param,function(json){
					o.data = {/literal}{$data}{literal};
					if(json.length==0) {
						$("#tree").treeview(o);
						return false;
					}
					if(json.length) {
						for(var i=0;json[i];i++) {
							_cacheFunc.push(json[i]);
						}

						//根据_cacheFunc改变o.data的checkstate属性,主要是向下和向上改变
						for(var i=0;_cacheFunc[i];i++) {
							//得到当前节点
							var curNode = _getData(_cacheFunc[i].menuId);
							if(curNode==null) continue;
							//向下改变
							changeChildrenState(curNode,1);
							//向上改变
							changeParentState(curNode,1);
						}
						$("#tree").treeview(o);
					}
				});

			});
            $("#showchecked").click(function(e){
                var s=$("#tree").getTSVs();
                if(s !=null)
                alert(s.join(","));
                else
                alert("NULL");
            });
            $("#showcurrent").click(function(e){
                var s=$("#tree").getTCT();
                if(s !=null)
                    //alert(s.text);
					dump(s);
                else
                    alert("NULL");
            });
			$('#btnSub').click(function(){
				if(_cRow==null) return false;
				var items = $('#tree').getSelectedItem();

				//取得所有已选中的节点,提交
				var url='?controller=Acm_Func&action=AssignFuncByAjax';
				var param=[];
				param.push({name:'roleId',value:$(_cRow).attr('val')});
				for(var i=0;items[i];i++) {
					//如果有子节点跳过
					if(items[i].hasChildren) continue;
					param.push({name:'menuId[]',value:items[i].id});
				}
				//debugger;

				//得到被选中的节点
				/*var s=$("#tree").getTSVs();
				if(s !=null) for(var i=0;s[i];i++) {
					url+='&funcId[]='+s[i];
				}
				//alert(url);return fasle;
				if(ck2!=null)for(var j=0;ck2[j];j++){
					url+='&pSel[]='+ck2[j].id;
				}*/

				$.post(url,param,function(json){
					if(!json) return false;
					if(json['success']==true) {
						alert('保存成功!');
					}else alert(json.msg);
				},'json');

			});
        });
		//向下改变所有的子节点状态
		function changeChildrenState(nodeData,state) {
			nodeData.checkstate = state;
			//如果改为部分选中，不影响任何东西
			if(state==2) return false;

			if(nodeData.ChildNodes && nodeData.ChildNodes.length>0) {
				for(var i=0;nodeData.ChildNodes[i];i++) {
					changeChildrenState(nodeData.ChildNodes[i],state);
				}
			}
		}
		function changeParentState(nodeData,state) {
			var arr = nodeData.value.split('-');
			if(arr.length==1) return false;
			//得到父节点
			arr.pop();
			var curNode = _getData(arr.join('-'));

			//curNode下每个节点进行循环，发现checkstat和state不相等的时候，需要将curNode.checkstate置为2,
			var ch = true;
			for(var i=0;curNode.ChildNodes[i];i++) {
				if(	curNode.ChildNodes[i].checkstate!=state) {
					ch = false;break;
				}
			}
			if(ch) curNode.checkstate=state;
			else curNode.checkstate=2;
			changeParentState(curNode,state);
		}

		//获得menuId所对应的o.data中的节点
		function _getData(menuId) {
			var arr = menuId.split('-');
			var k = '';
			var curMap = _cacheMap;
			var curNode = o.data;
			var f = true;
			for(var j=0;j<arr.length;j++) {
				if(j==0) {
					k=arr[j];
					curMap = curMap[k];
					if(!curMap) {
						f=false;
						break;
					}
					curNode = curNode[curMap.nodeIndex]
				}
				else {					
					if(!curMap.children) {//数据库中存在某个节点的子节点，但是缓存中没有需要中断
						f=false;
						break;
					}
					k = k+'-'+arr[j];
					curMap = curMap.children[k];
					if(!curMap) {
						f=false;
						break;
					}
					curNode = curNode.ChildNodes[curMap.nodeIndex]
				}
			}
			if(!f) curNode = null;
			return curNode;
		}
    </script>

	<style type="text/css">
	body, html{padding:0px; margin:0px; font-family:"宋体"; font-size:12px;}
	#role{border-bottom: #c3daf9 1px solid; border-left: #c3daf9 1px solid; width: 200px; height: 500px; overflow: auto; border-top: #c3daf9 1px solid; border-right: #c3daf9 1px solid; float:left;margin-top:10px; margin-left:5px;}
	#role table{width:100%; border:1px; font-size:12px;}
	#role table td{height:25px;}

	#
	</style>
</head>
{/literal}
<body>
    <div id="role">
      <table>
            <tr>
              <td style="background-image:url(Resource/Image/th_bg.gif); padding-left:5px;border-bottom:1px solid #c3daf9;">
			  		<strong>选择组</strong>(单击进行设置)
			  </td>
            </tr>
            {foreach from=$rowRole item='item'}
            <tr onMouseOver="if(this!=_cRow) this.style.backgroundColor='#ccc'" onMouseOut="if(this!=_cRow) this.style.backgroundColor='#efefef'" bgcolor="#efefef" id='trRole' val="{$item.id}">
              <td style="padding-left:20px;">{$item.roleName}</td>
            </tr>
            {/foreach}

          </table>
	</div>
    <div style="border-bottom: #c3daf9 1px solid; border-left: #c3daf9 1px solid; width: 250px; height: 500px; overflow: auto; border: #c3daf9 1px dotted; float:left; margin-left:10px;margin-top:10px;">
        <div id="tree" style="text-align:left"></div>
    </div>
	<div style="float:left; margin-top:10px; margin-left:5px;" align="left">
      <input type="button" name="button" id="btnSub" value="保 存" style="width:100px; height:30px;">
        {*<button id="showchecked">Get Seleceted Nodes</button>
        <button id="showcurrent">Get Current Node</button>
   		<button id="get" onClick="dump($('#tree').getItem(35))">getItem(2)</button>*}
	</div>
</body>
</html>