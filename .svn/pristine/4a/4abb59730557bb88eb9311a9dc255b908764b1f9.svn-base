/// <reference path="../intellisense/jquery-1.2.6-vsdoc-cn.js" />
/****************************************
author:xuanye.wan@gmail.com
***************************************/
(function($) {
    $.fn.swapClass = function(c1, c2) {
        return this.removeClass(c1).addClass(c2);
    }
    $.fn.switchClass = function(c1, c2) {
        if (this.hasClass(c1)) {
            return this.swapClass(c1, c2);
        }
        else {
            return this.swapClass(c2, c1);
        }
    }
    $.fn.treeview = function(settings) {
        var dfop =
            {
                method: "POST",//ajax方式
                datatype: "json",//数据源格式
                url: false,//展开时获取子节点的url
                cbiconpath: "Resource/Script/treeview/images/icons/", //icon路径
                icons: ["checkbox_0.gif", "checkbox_1.gif", "checkbox_2.gif"],//checkbox的3种状态的图片
                showcheck: true, //是否显示选择            
                oncheckboxclick: false, //当checkstate状态变化时所触发的事件，但是不会触发因级联选择而引起的变化
				onexpand: false,//节点展开后触发的函数
                onnodeclick: false,//节点点击后触发的事件
                cascadecheck: true,
                data: null,//数据源
                clicktoggle: true, //点击节点展开和收缩子节点
                theme: "bbit-tree-arrows" //bbit-tree-lines ,bbit-tree-no-lines,bbit-tree-arrows
            }

        $.extend(dfop, settings);
        var treenodes = dfop.data;
        var me = $(this);
        var id = me.attr("id");
        if (id == null || id == "") {
            id = "bbtree" + new Date().getTime();
            me.attr("id", id);
        }

        var html = [];
        buildtree(dfop.data, html);
        me.addClass("bbit-tree").html(html.join(""));
        InitEvent(me);
        html = null;
        //预加载图片
		//debugger;
        if (dfop.showcheck) {
            for (var i = 0; i < 3; i++) {
                var im = new Image();
                im.src = dfop.cbiconpath + dfop.icons[i];
            }
        }
        function buildtree(data, ht) {
            ht.push("<div class='bbit-tree-bwrap'>"); // Wrap ;
            ht.push("<div class='bbit-tree-body'>"); // body ;
            ht.push("<ul class='bbit-tree-root ", dfop.theme, "'>"); //root
            var l = data.length;
            for (var i = 0; i < l; i++) {
                buildnode(data[i], ht, 0, i, i == l - 1);
            }
            ht.push("</ul>"); // root and;
            ht.push("</div>"); // body end;
            ht.push("</div>"); // Wrap end;
        }
        //endregion
        function buildnode(nd, ht, deep, path, isend) {
            ht.push("<li class='bbit-tree-node'>");
            ht.push("<div id='", id, "_", nd.id, "' tpath='", path, "' unselectable='on'");
            var cs = [];
            cs.push("bbit-tree-node-el");
            if (nd.hasChildren) {
                cs.push(nd.isexpand ? "bbit-tree-node-expanded" : "bbit-tree-node-collapsed");
            }
            else {
                cs.push("bbit-tree-node-leaf");
            }
            if (nd.classes) { cs.push(nd.classes); }

            ht.push(" class='", cs.join(" "), "'>");
            //span indent
            ht.push("<span class='bbit-tree-node-indent'>");
            if (deep == 1) {
                ht.push("<img class='bbit-tree-icon' src='Resource/Script/treeview/images/s.gif'/>");
            }
            else if (deep > 1) {
                ht.push("<img class='bbit-tree-icon' src='Resource/Script/treeview/images/s.gif'/>");
                for (var j = 1; j < deep; j++) {
                    ht.push("<img class='bbit-tree-elbow-line' src='Resource/Script/treeview/images/s.gif'/>");
                }
            }
            ht.push("</span>");
            //img
            cs.length = 0;
            if (nd.hasChildren) {
                if (nd.isexpand) {
                    cs.push(isend ? "bbit-tree-elbow-end-minus" : "bbit-tree-elbow-minus");
                }
                else {
                    cs.push(isend ? "bbit-tree-elbow-end-plus" : "bbit-tree-elbow-plus");
                }
            }
            else {
                cs.push(isend ? "bbit-tree-elbow-end" : "bbit-tree-elbow");
            }
            ht.push("<img class='bbit-tree-ec-icon ", cs.join(" "), "' src='Resource/Script/treeview/images/s.gif'/>");
            ht.push("<img class='bbit-tree-node-icon' src='Resource/Script/treeview/images/s.gif'/>");
            //checkbox
			//debugger;
            if (nd.showcheck===true || (nd.showcheck===undefined&&dfop.showcheck===true)) {
                if (nd.checkstate == null || nd.checkstate == undefined) {
                    nd.checkstate = 0;
                }
                ht.push("<img  id='", id, "_", nd.id, "_cb' class='bbit-tree-node-cb' src='", dfop.cbiconpath, dfop.icons[nd.checkstate], "'/>");
            }
            //a
            ht.push("<a hideFocus class='bbit-tree-node-anchor' tabIndex=1 href='javascript:void(0);'>");
            ht.push("<span unselectable='on'>", nd.text, "</span>");
            ht.push("</a>");
            ht.push("</div>");
            //Child
            if (nd.hasChildren) {
                if (nd.isexpand) {
                    ht.push("<ul  class='bbit-tree-node-ct'  style='z-index: 0; position: static; visibility: visible; top: auto; left: auto;'>");
                    if (nd.ChildNodes) {
                        var l = nd.ChildNodes.length;
                        for (var k = 0; k < l; k++) {
                            nd.ChildNodes[k].parent = nd;
                            buildnode(nd.ChildNodes[k], ht, deep + 1, path + "." + k, k == l - 1);
                        }
                    }
                    ht.push("</ul>");
                }
                else {
                    ht.push("<ul style='display:none;'></ul>");
                }
            }
            ht.push("</li>");
            nd.render = true;
        }
        function getItem(path) {
            var ap = path.split(".");
            var t = treenodes;
            for (var i = 0; i < ap.length; i++) {
                if (i == 0) {
                    t = t[ap[i]];
                }
                else {
                    t = t.ChildNodes[ap[i]];
                }
            }
            return t;
        }
        function check(item, state, type) {
            var pstate = item.checkstate;
            if (type == 1) {
                item.checkstate = state;
            }
            else {// 上溯
                var cs = item.ChildNodes;
                var l = cs.length;
                var ch = true;
                for (var i = 0; i < l; i++) {
                    if ((state == 1 && cs[i].checkstate != 1) || state == 0 && cs[i].checkstate != 0) {
                        ch = false;
                        break;
                    }
                }
                if (ch) {
                    item.checkstate = state;
                }
                else {
                    item.checkstate = 2;
                }
            }
            //change show           
            if (item.render && pstate != item.checkstate) {
                var et = $("#" + id + "_" + item.id + "_cb");
                if (et.length == 1) {
                    et.attr("src", dfop.cbiconpath + dfop.icons[item.checkstate]);
                }
            }
        }
        //遍历子节点
        function cascade(fn, item, args) {
            if (fn(item, args, 1) != false) {
                if (item.ChildNodes != null && item.ChildNodes.length > 0) {
                    var cs = item.ChildNodes;
                    for (var i = 0, len = cs.length; i < len; i++) {
                        cascade(fn, cs[i], args);
                    }
                }
            }
        }
        //冒泡的祖先
        function bubble(fn, item, args) {
            var p = item.parent;
            while (p) {
                if (fn(p, args, 0) === false) {
                    break;
                }
                p = p.parent;
            }
        }
        function nodeclick(e) {
			//alert(1);
            var path = $(this).attr("tpath");
            var et = e.target || e.srcElement;
            var item = getItem(path);

            if (et.tagName == "IMG") {
                // +号需要展开
                if ($(et).hasClass("bbit-tree-elbow-plus") || $(et).hasClass("bbit-tree-elbow-end-plus")) {
                   expand(item);
                }
                else if ($(et).hasClass("bbit-tree-elbow-minus") || $(et).hasClass("bbit-tree-elbow-end-minus")) {  //- 号需要收缩  
					unexpand(item);
                    /*$(this).next().hide();
                    if ($(et).hasClass("bbit-tree-elbow-minus")) {
                        $(et).swapClass("bbit-tree-elbow-minus", "bbit-tree-elbow-plus");
                    }
                    else {
                        $(et).swapClass("bbit-tree-elbow-end-minus", "bbit-tree-elbow-end-plus");
                    }
                    $(this).swapClass("bbit-tree-node-expanded", "bbit-tree-node-collapsed");*/
                }
                else if ($(et).hasClass("bbit-tree-node-cb")) // 点击了Checkbox
                {
                    var s = item.checkstate != 1 ? 1 : 0;
                    var r = true;
                    if (dfop.oncheckboxclick) {
                        r = dfop.oncheckboxclick.call(et, item, s);
                    }
                    if (r != false) {
                        if (dfop.cascadecheck) {
                            //遍历
                            cascade(check, item, s);
                            //上溯
                            bubble(check, item, s);
                        }
                        else {
                            check(item, s, 1);
                        }
                    }
                }
            }
            else {
                if (dfop.citem) {
                    $("#" + id + "_" + dfop.citem.id).removeClass("bbit-tree-selected");
                }
                dfop.citem = item;
                $(this).addClass("bbit-tree-selected");
                if (dfop.onnodeclick) {
                    dfop.onnodeclick.call(this, item);
                }
            }
        }
        function asnybuild(nodes, deep, path, ul, pnode) {
            var l = nodes.length;
            if (l > 0) {
                var ht = [];
                for (var i = 0; i < l; i++) {
                    nodes[i].parent = pnode;
                    buildnode(nodes[i], ht, deep, path + "." + i, i == l - 1);
                }
                ul.html(ht.join(""));
                ht = null;
                InitEvent(ul);
            }
            ul.addClass("bbit-tree-node-ct").css({ "z-index": 0, position: "static", visibility: "visible", top: "auto", left: "auto", display: "" });
            ul.prev().removeClass("bbit-tree-node-loading");
        }
        function asnyloadc(pul, pnode, callback) {
            if (dfop.url) {
                var param = builparam(pnode);
                $.ajax({
                    type: dfop.method,
                    url: dfop.url,
                    data: param,
                    dataType: dfop.datatype,
                    success: callback,
                    error: function(e) { alert("error occur!"); }
                });
            }
        }
        function builparam(node) {
            var p = [{ name: "id", value: encodeURIComponent(node.id) }
                    , { name: "text", value: encodeURIComponent(node.text) }
                    , { name: "value", value: encodeURIComponent(node.value) }
                    , { name: "checkstate", value: node.checkstate}];
            return p;
        }
		function bindevent() {
            $(this).hover(function() {
                $(this).addClass("bbit-tree-node-over");
            }, function() {
                $(this).removeClass("bbit-tree-node-over");
            }).click(nodeclick)
             .find("img.bbit-tree-ec-icon").each(function(e) {
                 if (!$(this).hasClass("bbit-tree-elbow")) {
                     $(this).hover(function() {
                         $(this).parent().addClass("bbit-tree-ec-over");
                     }, function() {
                         $(this).parent().removeClass("bbit-tree-ec-over");
                     });
                 }
             });
        }
        function InitEvent(parent) {
            var nodes = $("li.bbit-tree-node>div", parent);
            nodes.each(function(e) {
                $(this).hover(function() {
                    $(this).addClass("bbit-tree-node-over");
                }, function() {
                    $(this).removeClass("bbit-tree-node-over");
                })
                .click(nodeclick)
                .find("img.bbit-tree-ec-icon").each(function(e) {
                    if (!$(this).hasClass("bbit-tree-elbow")) {
                        $(this).hover(function() {
                            $(this).parent().addClass("bbit-tree-ec-over");
                        }, function() {
                            $(this).parent().removeClass("bbit-tree-ec-over");
                        });
                    }
                });
            });
        }
		
		//展开某个节点，如果需要的话，使用ajax
		function expand(itemOrItemId){			
			var itemId;
			if (typeof (itemOrItemId) == "string" || typeof (itemOrItemId) == "number") {
				itemId = itemOrItemId+'';
			}
			else {
				itemId = itemOrItemId.id;
			}

			var node = $("#" + id + "_" + itemId);
			var path = $(node).attr("tpath");
			var deep = path.split(".").length;
			var item = getItem(path);
			

			var ul = $(node).next(); //"bbit-tree-node-ct"
			if (ul.hasClass("bbit-tree-node-ct")) {
				item.isexpand = true;
				ul.show();
			}
			else {				
				if (item.complete) {
					item.isexpand = true; 
					item.ChildNodes != null && asnybuild(item.ChildNodes, deep, path, ul, item);
				}
				else {
					$(node).addClass("bbit-tree-node-loading");
					asnyloadc(ul, item, function(data) {
						item.complete = true;
						item.isexpand = true;
						item.ChildNodes = data;
						asnybuild(data, deep, path, ul, item);
						if (dfop.onexpand) {
							dfop.onexpand.call(this, item);
						}
					});
				}
			}

			var im = node.children('.bbit-tree-elbow-plus');
			if(im.length==1) im.swapClass("bbit-tree-elbow-plus","bbit-tree-elbow-minus");
			else {
				im= node.children('.bbit-tree-elbow-end-plus');
				im.swapClass("bbit-tree-elbow-end-plus","bbit-tree-elbow-end-minus");
			}
			$(node).swapClass("bbit-tree-node-collapsed", "bbit-tree-node-expanded");
		}
		
		//收缩节点
		function unexpand(itemOrItemId){
			var itemId;
			if (typeof (itemOrItemId) == "string" || typeof (itemOrItemId) == "number") {
				itemId = itemOrItemId+'';
			}
			else {
				itemId = itemOrItemId.id;
			}

			var node = $("#" + id + "_" + itemId);
			var path = $(node).attr("tpath");
			var deep = path.split(".").length;
			var item = getItem(path);
			item.isexpand = false;
			node.next().hide();
			var im = node.children('.bbit-tree-elbow-minus');
			if(im.length==1) im.swapClass("bbit-tree-elbow-minus", "bbit-tree-elbow-plus");
			else {
				im= node.children('.bbit-tree-elbow-end-minus');
				im.swapClass("bbit-tree-elbow-end-minus", "bbit-tree-elbow-end-plus");
			}				
			node.swapClass("bbit-tree-node-expanded", "bbit-tree-node-collapsed");
		}
		
		//重新从服务器利用ajax提取子节点
		function reflash(itemId) {
            var nid = itemId.replace(/[^\w-]/gi, "_");
            var node = $("#" + id + "_" + nid);
            if (node.length > 0) {
                node.addClass("bbit-tree-node-loading");
                var isend = node.hasClass("bbit-tree-elbow-end") || node.hasClass("bbit-tree-elbow-end-plus") || node.hasClass("bbit-tree-elbow-end-minus");
                var path = node.attr("tpath");
                var deep = path.split(".").length;
                var item = getItem(path);
                if (item) {
                    asnyloadc(node.next(), item, function(data) {
                        item.complete = true;
                        item.ChildNodes = data;
                        item.isexpand = true;
                        if (data && data.length > 0) {
                            item.hasChildren = true;
                        }
                        else {
                            item.hasChildren = false;
                        }
                        var ht = [];
                        buildnode(item, ht, deep - 1, path, isend);
                        ht.shift();
                        ht.pop();
                        var li = node.parent();
                        li.html(ht.join(""));
                        ht = null;
                        InitEvent(li);
                        bindevent.call(li.find(">div"));
                    });
                }
            }
            else {
                alert("该节点还没有生成");
            }
        }
		//得到所有的被选中的节点,将其放入第二个参数c中
        function getck(items, c, fn) {
            for (var i = 0, l = items.length; i < l; i++) {
                items[i].checkstate == 1 && c.push(fn(items[i]));
                if (items[i].ChildNodes != null && items[i].ChildNodes.length > 0) {
                    getck(items[i].ChildNodes, c, fn);
                }
            }
        }
		//得到所有的未展开过，且状态为部分选中的节点,将其放入第二个参数c中
		function getck2(items, c, fn) {
            for (var i = 0, l = items.length; i < l; i++) {
				if(items[i].checkstate <2) continue;
				if (items[i].ChildNodes == null) c.push(fn(items[i]));
				else getck2(items[i].ChildNodes, c, fn);               
            }
        }
		function getItemById(itemId,nodes){
			//var times =0;
			//递归寻找id为itemId的节点
			//debugger;
			if(!nodes) nodes = treenodes;
			for(var i=0;nodes[i];i++) {
				//times++;
				if(nodes[i].id==itemId) {
					//alert('a'+times);
					return nodes[i];
				}
				if (nodes[i].ChildNodes != null && nodes[i].ChildNodes.length > 0) {
					var temp = getItemById(itemId,nodes[i].ChildNodes);
					if(temp!=null) {
						//alert('b'+times);
						return temp;
					}
				}
			}
			return null;
		}
        me[0].t = {
            getSelectedNodes: function() {
                var s = [];
                getck(treenodes, s, function(item) { return item });
                return s;
            },
            getSelectedValues: function() {
                var s = [];
                getck(treenodes, s, function(item) { return item.value });
                return s;
            },
            getCurrentItem: function() {
                return dfop.citem;
            },
			//的搭配
			getck2:function(){
				var s = [];
                getck2(treenodes, s, function(item) { return item });
                return s;
			},

			//利用ajax重新提取某个节点的子节点，并刷新
			reflash: function(itemOrItemId) {
                var id;
                if (typeof (itemOrItemId) == "string" || typeof (itemOrItemId) == "number") {
                    id = itemOrItemId+'';
                }
                else {
                    id = itemOrItemId.id;
                }
                reflash(id);
            },
			getItem: function(itemId){
				//debugger;
				return getItemById(itemId);				
			},
			changeCheckstate: function(itemId,checkstate) {
				var item = getItemById(itemId);
				item.checkstate = checkstate;
				var et = $("#" + id + "_" + item.id + "_cb");
				if (et.length == 1) {
					et.attr("src", dfop.cbiconpath + dfop.icons[item.checkstate]);
				}				
				return false;
			},
			
			//展开某节点
			expand: function(itemOrItemId){
				return expand(itemOrItemId);
			},
			//收缩某节点
			unexpand: function(itemOrItemId){
				return unexpand(itemOrItemId);
			}			
        };
        return me;
    }
    //获取所有选中的节点的Value数组
    $.fn.getTSVs = function() {
        if (this[0].t) {
            return this[0].t.getSelectedValues();
        }
        return null;
    }
    //获取所有选中的节点的Item数组
    $.fn.getTSNs = function() {
        if (this[0].t) {
            return this[0].t.getSelectedNodes();
        }
        return null;
    }

    $.fn.getTCT = function() {
        if (this[0].t) {
            return this[0].t.getCurrentItem();
        }
        return null;
    }

	
	$.fn.reflash = function(ItemOrItemId) {
        if (this[0].t) {
            return this[0].t.reflash(ItemOrItemId);
        }
    }

	//获取checkstate=1的所有节点
    $.fn.getSelectedItem = function() {
		//debugger;
        if (this[0].t) {			
            return this[0].t.getSelectedNodes();
        }
        return null;
    }
	//取得被选中的当前节点。
	$.fn.getCurrentItem = function() {
        if (this[0].t) {
            return this[0].t.getCurrentItem();
        }
        return null;
    }
	//根据节点的id值返回相关id
	$.fn.getItem = function(itemId) {
        if (this[0].t) {
            return this[0].t.getItem(itemId);
        }
    }
	//根据节点的id值，改变相关节点的checkstate
	$.fn.changeCheckstate = function(itemId,checkstate) {
        if (this[0].t) {
            return this[0].t.changeCheckstate(itemId,checkstate);
        }
    }

	//展开某个节点
	$.fn.expandItem = function(itemOrItemId) {
        if (this[0].t) {
			return this[0].t.expand(itemOrItemId);
            //return this[0].t.reflash(itemOrItemId);
        }
    }
	//收缩某个节点
	$.fn.unexpandItem = function(itemOrItemId) {
        if (this[0].t) {
            return this[0].t.unexpand(itemOrItemId);
        }
    }
	
	//得到所有的未展开过，且状态为部分选中的节点
	$.fn.getck2 = function(itemOrItemId) {
         if (this[0].t) {
            return this[0].t.getck2();
        }
		return null;
    }
})(jQuery);