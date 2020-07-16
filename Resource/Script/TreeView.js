<!--
/******************************************************************\
 *  FileName: MzTreeView.js                                       *
 *  Author:   meizz(梅花雪)                                       *
 *  Version:  1.0                                                 *
 *  Subject:  Web TreeView Class                                  *
 *  Begin:    2004-10-18                                          *
 *  Download: http://www.meizz.com/Web/Download/MzTreeView10.rar  *
 *                                                                *
 *      You may use this code on a public web site only           *
 *      this entire copyright notice appears unchanged            *
 *      and you clearly display a link to                         *
 *      http://www.meizz.com/                                     *
 *                                                                *
 *                                                                *
 *      Please send questions and bug reports to:                 *
 *      E-mail: meizz@sohu.com                                    *
 *      MSN: huangfr@msn.com                                      *
\*________________________________________________________________*/
function MzTreeView(Tname)
{
  String.prototype.getParam = function(name) //Get value from arguments by name
  {
    var reg = new RegExp("(^|;|\\s)"+name+"\\s*(=|:)\\s*(\"|\')([^\\3;]*)\\3(\\s|;|$)", "i");
    var r = this.match(reg); if (r!=null) return r[4]; return "";
  };
  function getObjectById(id)
  {
    if (typeof(id) != "string" || id == "") return null;
    if (document.all) return document.all(id);
    if (document.getElementById) return document.getElementById(id);
    try {return eval(id);} catch(e){ return null;}
  }
  if(typeof(Tname) != "string" || Tname == "")
    throw(new Error(-1, '创建类实例的时候请把类实例的引用变量名传递进来！'));
  
  this.divider  = "_";
  this.url      = "#";
  this.target   = "_self";

  var highLight = "#0A246A";
  var highLightText   = "#FFFFFF";
  var mouseOverBgColor= "#D4D0C8";

  this.icons    = {
    L0        : 'L0.gif',  //┏
    L1        : 'L1.gif',  //┣
    L2        : 'L2.gif',  //┗
    L3        : 'L3.gif',  //━
    L4        : 'L4.gif',  //┃
    PM0       : 'P0.gif',  //＋┏
    PM1       : 'P1.gif',  //＋┣
    PM2       : 'P2.gif',  //＋┗
    PM3       : 'P3.gif',  //＋━
    empty     : 'L5.gif',     //空白图
    root      : 'root.gif',   //缺省的根节点图标
    ffolder    : 'ffolder.gif', //缺省的文件夹图标
    file      : 'file.gif',   //缺省的文件图标

    event     : 'event.gif',
    object    : 'object.gif',
    behavior  : 'behavior.gif',
    property  : 'property.gif',
    method    : 'method.gif',
    collection: 'collection.gif',

    exit      : 'exit.gif'
  };
  this.iconsExpand = {  //存放节点图片在展开时的对应图片
    PM0       : 'M0.gif',     //－┏
    PM1       : 'M1.gif',     //－┣
    PM2       : 'M2.gif',     //－┗
    PM3       : 'M3.gif',     //－━
    ffolder    : 'folderopen.gif',

    exit      : 'exit.gif'
  };
  this.setIconPath  = function(path)
  {
    for(var i in this.icons)
    {
      var tmp = this.icons[i];
      this.icons[i] = new Image();
      this.icons[i].src = path + tmp;
	  //alert(this.icons[i].src);
    }
    for(var i in this.iconsExpand)
    {
      var tmp = this.iconsExpand[i];
      this.iconsExpand[i]=new Image();
      this.iconsExpand[i].src = path + tmp;
    }
  };



  this.nodeHTML = function(node, AtEnd)
  {
    var id   = node.id;
    var param= this.nodes[node.parentId + this.divider + id];
    var data = param.getParam("data"); if(data) data = "&"+ data;
    var url  = param.getParam("url");  if(!url) url  = this.url;
        url += (url.indexOf("\?")==-1?"\?":"&") +"MzTreeViewId="+ id + data;
    var target = param.getParam("target");if(!target) target = this.target;

    var HCN  = node.hasChild, isRoot = node.parentId=="0";
    if(isRoot && node.icon=="") node.icon = "root";
    if(node.icon=="") node.icon = HCN ? "ffolder" : "file";
       node.iconExpand  = HCN ? AtEnd ? "PM2" : "PM1" : AtEnd ? "L2" : "L1";
    if(id!="0" && !isRoot)  node.childAppend = node.parentNode.childAppend +
      "<IMG border='0' align='absmiddle' src='"+this.icons[(AtEnd?"empty":"L4")].src+"'>";
    
    //alert(this.icons[(AtEnd?"empty":"L4")].src);
	//alert("1");
	//alert(this.icons[node.iconExpand].src);
	//alert("aaaaaaaaaa"+node.icon);
	//alert(this.icons[node.icon].src);
    var nodeHTML = "<DIV noWrap><SPAN onmouseover='this.style.backgroundColor=\""+
      mouseOverBgColor +"\"' onmouseout='this.style.backgroundColor=\"\"'><NOBR>"+
      (isRoot ? "" : node.parentNode.childAppend +
      "<IMG border='0' align='absmiddle' id='"+ Tname +"_expand_"+ id +"' "+
        (HCN ?"onclick=\""+ Tname +".expand('"+ id +"')\" " : "") +
        "src='"+ this.icons[node.iconExpand].src +"' style='cursor: hand'>")+
      "<SPAN oncontextmenu='return "+ Tname +".popupmenu(\""+ id +"\")' "+
        "onclick=\""+ Tname +".focus('"+ id +"')\""+
        (HCN ? " ondblclick=\""+ Tname +".expand('"+ id +"')\"" : "") +">"+
      "<IMG border='0' align='absmiddle' id='"+ Tname +"_icon_"+ id +"' "+
        "src='"+ this.icons[node.icon].src +"'>"+
      "<A class='MzTreeview' id='"+ Tname +"_link_"+ id +"' target='"+ target +
        "' href='"+ url +"' title='"+ node.hint +"' onfocus='this.blur()' onclick='"+
        "return "+ Tname +".click(\""+ id +"\");'>"+ node.text +"</A></NOBR>"+
      "</SPAN></SPAN></DIV>\r\n"; if(isRoot && node.text=="") nodeHTML = "";
      nodeHTML += "<SPAN id='"+ Tname +"_tree_"+ id +"' style='DISPLAY: none'></SPAN>";
	  //alert("2");
    return nodeHTML;
	//alert("3");
  };var _d = "\x0f"; this._nodes = {}; this.nodes = {}; this.currentNode = null;
  this._nodes["0"] =
  {
    id: "0",
    childAppend: "",
    position: "0",
    childNodes: new Array(),
    isLoad: false
  };

  this.loadNode = function(id) //load node's childNode
  {
    var str = "(^|"+_d+")"+ id + this.divider +"[^"+_d+ this.divider +"]+("+_d+"|$)"
    var reg = new RegExp().compile(str, "g"), CN = this._nodes[id].childNodes;
    var ids = this.names.match(reg); str = ""; if (ids){ 
    var re  = new RegExp(_d, "g");
    for(var i=0; i<ids.length; i++){
      var cid = ids[i].replace(re, "");CN[CN.length] = this.initNode(cid);}
    }
    this._nodes[id].isLoad = true;
  };
  this.initNode = function(comboId)
  {
    if(typeof(comboId) != "string" || comboId.indexOf(this.divider)<0)
      throw(new Error(-1, '节点的ID（"'+ comboId +'"）不符合规则！\r\n'+
      '节点ID命名规则：由“父节点ID '+ this.divider +' 子节点ID”组合成的字符串！'));
    var param   = this.nodes[comboId];
    var hint    = param.getParam("hint");
    var text    = param.getParam("text");
    var ids     = comboId.split(this.divider);
    this._nodes[ids[1]] = {
      isLoad  : false,
      id      : ids[1],
      parentId: ids[0],
      position: this._nodes[ids[0]].position + this.divider + ids[1],
      icon    : param.getParam("icon"),
      text    : text,
      hint    : hint ? hint : text,
      childNodes: new Array(),
      parentNode: this._nodes[ids[0]],
      childAppend : ""
    };
    this._nodes[ids[1]].hasChild = this.names.indexOf(_d+ids[1]+this.divider)>=0;
    return this._nodes[ids[1]];
  };
  this.drawNode = function(id)
  {
    var d = new Date().getTime();
    var CN      = this._nodes[id].childNodes, str = "";
	//alert("cn.length");
    for (var i=0; i<CN.length; i++) str += this.nodeHTML(CN[i], i==CN.length-1);
    getObjectById(Tname +"_tree_"+ id).innerHTML = str;
    //getObjectById("TimeShow").innerText = this._nodes[id].text +" 共有 "+ this._nodes[id].childNodes.length +" 个子节点！加载耗时 "+ (new Date().getTime()-d) +" 毫秒！";
  }
  this.toString = function()
  {
    var a = new Array(); for (id in this.nodes) a[a.length] = id;
    this.names = a.join(_d+_d); this.loadNode("0");
    var rootCN = this._nodes["0"].childNodes;
    var str = "<A id='"+ Tname +"_RootLink' href='#' style='DISPLAY: none'></A>";
    if(rootCN.length>0)
    {
	  ////alert("rootcn.length");
      for(var i=0; i<rootCN.length; i++) str += this.nodeHTML(rootCN[i], i==rootCN.length-1);
      setTimeout(Tname +".afterLoad()", 200);
      setTimeout(Tname +".expand('"+ rootCN[0].id +"', true); "+ 
        Tname +".focus('"+ rootCN[0].id +"'); "+ Tname +".atRootIsEmpty()",1);
    }
    return str;
  };
  this.click    = function(id)
  {
    var param= this.nodes[this._nodes[id].parentId + this.divider + id];
    var node = this._nodes[id]; eval(param.getParam("method"));
    if(!param.getParam("url") && this.url=="#") return false;
    return true;
  };
  this.atRootIsEmpty = function()
  {
    var RCN = this._nodes["0"].childNodes;
    for(var i=0; i<RCN.length; i++)
    {
      if(RCN[i].text=="")
      {
        var node = RCN[i].childNodes[0];
        var HCN  = node.hasChild;
        node.iconExpand  =  RCN[i].childNodes.length>1 ? HCN ? "PM0" : "L0" : HCN ? "PM3" : "L3"
		//alert(this.icons[node.iconExpand].src);
        getObjectById(Tname +"_expand_"+ node.id).src = this.icons[node.iconExpand].src;
      }
    }
  }
  this.afterLoad= function() //load menu
  {
    try{ if(this.popupMenu!="" && typeof(this.popupMenu)=="string")
        this.popupMenu = eval(this.popupMenu);}catch(e){}
  };
  this.popupmenu  = function(id)
  {
    try
    {
      if(this.popupMenu)
      {
        if(id)
        {
          this.popupMenu.data = this._nodes[id].data;
          this.popupMenu.treeviewitem = this._nodes[id];
        }
        for(var i=0; i<this.popupMenu.items.length; i++)
        {
          if(this.popupMenu.items[i].bind != "")
          this.popupMenu.items[i].bind = Tname +"_tree_"+ Tname;
        }
        this.popupMenu.show(window.event, window.event.srcElement);
        return false;
      }
      else return true;
    }catch(e){}
  };
  this.getPosition= function(id)
  {
    var A = new Array(); A[0] = id, pid=id;
    while(id!="0" && id!="")
    {
      var str = "(^|"+_d+")([^"+_d+ this.divider +"]+"+ this.divider + id +")("+_d+"|$)";
      var ids = this.names.match(new RegExp(str, "g"));
      if(ids)
      {
        id = ids[0].replace(_d, "").split(this.divider)[0];
        A[A.length] = id;
      } else break;
    }
    return A.reverse();
  };
  this.focus      = function(id)
  {
    if(!this.currentNode) this.currentNode = this._nodes["0"];
    if(typeof(this._nodes[id])=="undefined") var apid = this.getPosition(id);
    else var apid = this._nodes[id].position.split(this.divider);
    for(var i=0; i<apid.length-1; i++) this.expand(apid[i], true);

    var a = getObjectById(Tname +"_link_"+ id); if (a) { a.focus();
    var link = getObjectById(Tname +"_link_"+ this.currentNode.id);
    if(link)with(link.style){color="";   backgroundColor="";}
    with(a.style){color = highLightText; backgroundColor = highLight;}
    this.currentNode= this._nodes[id];}
  };
  this.expand   = function(id, sureExpand)
  {
    var area    = getObjectById(Tname +"_tree_"+ id);
    if (area)
    {
      var node  = this._nodes[id];
      var icon  = this.icons[node.icon];
      var iconE = this.iconsExpand[node.icon];
      var exp   = this.icons[node.iconExpand];
      var expE  = this.iconsExpand[node.iconExpand];
      var Bool  = sureExpand || area.style.display == "none";
      var img   = getObjectById(Tname +"_icon_"+ id);
      if (img)  img.src = !Bool ? icon.src :typeof(iconE)=="undefined" ? icon.src : iconE.src;
      var img   = getObjectById(Tname +"_expand_"+ id);
      if (img)  img.src = !Bool ? exp.src : typeof(expE) =="undefined" ? exp.src  : expE.src;
      if(!Bool && this.currentNode.position.indexOf(node.position)==0){
        this.focus(id); this.click(id);}
      area.style.display = Bool ? "block" : "none";
      if(!node.isLoad)
      {
        this.loadNode(id);
		//alert("node.childNodes.length"+node.childNodes.length);
		//alert(node.icon);
		//alert(node.iconExpand);
        if(node.childNodes.length<36) this.drawNode(id); else
        {
          setTimeout(Tname +".drawNode('"+ id +"')", 1);
          area.innerHTML = this._nodes[id].childAppend +
          "<IMG border='0' align='absmiddle' src='"+this.icons["L2"].src+"'>"+
          "<IMG border='0' align='absmiddle' src='"+this.icons["file"].src+"'>"+
          "<span style='background-Color:"+ highLight +"; color:"+ highLightText +
          "; font-size: 9pt'>loading...</span>";
        }
      }
    }
  };
}
// -->