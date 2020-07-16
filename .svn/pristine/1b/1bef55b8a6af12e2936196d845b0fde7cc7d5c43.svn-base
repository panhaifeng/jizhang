/*********************************************************************\
*  Copyright (c) 1998-2013, TH. All Rights Reserved.
*  Author :Jeff
*  FName  :btGrid.js
*  Time   :2014/05/13 18:31:40
*  Remark :基于bootstrap的grid插件,接口设计参考easyui
*  columns的配置参数如下：
*   $_column = array(
      'compName'=>array('text'=>'送货小车','width'=>200,'checkbox'=>true,'align'=>'right'),
      'compName1'=>array('text'=>'送货小车','width'=>200,'checkbox'=>true,'align'=>'right'),
      'compName2'=>array('text'=>'送货小车','width'=>200,'checkbox'=>true,'align'=>'right'),
      'textColumn'=>'标题'
    );
\*********************************************************************/
// 创建一个闭包    
(function($) {    

  // 插件的定义
  //options可能是配置对象，也可能是方法名(string);
  //_relatedTarget:当options为方法名时的参数
  $.fn.btGrid = function(options,_methodParam) {
    //在这个区间，this指jquery对象,
    //在下面的函数中，this值element
    /*$('#a').click(function(){
      alert(this.text);
    });*/

    //默认配置
    // 插件的默认配置
    $.fn.btGrid.defaults = {
      'url':''//远程获取数据的地址
      ,'columns':null//字段对象一般是{'_edit':'aas','id':{'text':'id','width':100}}
      ,'data':[]//静态数据,优先级高于url
      ,'defaultColumnWidth':100//默认列宽度

      ,'showPage':true//是否显示分页
      ,'pageSize':50//分页的行数
      ,'pageIndex':0//当前分页
      ,'pageList': [10, 25, 50, 100]//pageSize选项

      ,'param':{}//查询参数

      ,'onClickRow':function(rowIndex,rowData){return;}//行的点击事件
      ,'onCheck':function(rowIndex,rowData){return;}//当选中checkbox
      // ,'onUncheck':function(rowIndex,rowData){}//当取消选中checkbox
      ,'onCheckAll':function(rows){return;}//当全选中checkbox
      //,'onUncheckAll':function(rows){}//当取消全选中checkbox
      ,'beforeLoad':function(param) {return;}//一般用来传递查询参数,param为控件中之前的参数
      ,'afterLoad':function(rows) {return;}//加载成功后的处理
    };
    
    //将默认配置和用户配置合并
    // var opts = $.extend({}, $.fn.btGrid.defaults, options);
    var opts = $.extend({}, $.fn.btGrid.defaults, typeof(options)=='object'?options:null);

    //定义插件方法
    var methods = {
      //初始化
      'init':function(){
        // debugger;
        var $this = $(this);
        //将配置加入element的data中，作为缓存使用，封装        
        // *  $(element).data() 方法向被选元素附加数据，或者从被选元素获取数据。
        // *  $("div").data("greeting", "Hello World");//附加值
        // *  $("div").data("greeting");//取值
        var o = $.extend({}, $.fn.btGrid.defaults, typeof(options)=='object'?options:null);
        //debugger;
        $this.data('optionsBtGrid',o);

        var columns=$this.data('optionsBtGrid').columns;

        //构建表头 '<thead><tr><th style="width:100px;">aa</th><th style="width:100px;">b</th></tr></thead>';
        var arrHtml = [];
        arrHtml.push('<thead><tr>');
        for(var i in columns) {
          var c = columns[i];
          // 若c元素为空, 认为设置为空
          if(null == c)c={text:'' ,width:''};
          //列宽
          var w = $this.data('optionsBtGrid').defaultColumnWidth;
          if(!c) {
            alert('未发现对字段"'+i+'"的定义');
            return false;
          }
          w=c.width?c.width:w;
          //标题
          var t = typeof c == 'string' ? c : c.text;
          if(c.checkbox) t='<input type="checkbox" id="chkAll" class="chkAll" value=""/>';
          arrHtml.push('<th style="width:'+w+'px;">'+t+'</th>');
        }        
        arrHtml.push('</tr></thead>');
        
        //构建表体<tr><td>aaa</td><td>aaa</td></tr><tr><td>bbb</td><td>bbb</td></tr>
        var body = '<tbody></tbody>';
        //填充
        $this.html(arrHtml.join('')+body);

        //显示分页栏
        $this.btGrid('initPage');

        //全选事件绑定
        $('.chkAll',$this).click(function(){
          // console.log($(this).attr('checked'));
          $('.chk',$this).attr('checked',this.checked);
          // $this.onCheckAll();
          $this.data('optionsBtGrid').onCheckAll();
        });
      }      
      //载入并显示第一页的记录，如果传递了'param'参数，它将会覆盖查询参数属性的值。通过传递一些参数，通常做一个查询，这个方法可以被称为从服务器加载新数据。
      ,'load':function(para) {
        //alert(1);
        //优先使用静态数据
        var $this=$(this);
        if(typeof(para)=='object') {
          $this.data('optionsBtGrid').param = para;          
        }
        $this.btGrid('_showLoading','载入中...');

        //如果远程地址为空,
        if($this.data('url')=='' && rowset && rowset.length>0) {
          var rowset = $this.data('optionsBtGrid')['data'];
          if (rowset && rowset.length>0) {
            $this.btGrid('_load',rowset);
            $this.btGrid('_hideLoading');
            return;
          }
        }
        //远程数据
        var columns=$this.data('optionsBtGrid').columns;
        var url=$this.data('optionsBtGrid').url; 
        //参数中必须加入pageIndex和pageSize        
        var param = $.extend($this.data('optionsBtGrid').param,{
           pageIndex:$this.data('optionsBtGrid').pageIndex
          ,pageSize:$this.data('optionsBtGrid').pageSize
        });

        //调用beforeLoad事件
        var bLoad = $this.data('optionsBtGrid').beforeLoad;
        if(typeof(bLoad)=='function') bLoad(param);
        // console.log($this.data('optionsBtGrid').param);

        $.post(url,param,function(json){
          if(!json || !json.success) {
            alert('reload 数据 出错');
            $this.btGrid('_hideLoading');
            return;
          }
          //载入数据前将数据集放入缓存
          $this.data('optionsBtGrid').data=json.rows;

          $this.btGrid('_load',json.rows);
          $this.btGrid('_hideLoading');
          //调用afterLoad事件
          var aLoad = $this.data('optionsBtGrid').afterLoad;
          if(typeof(aLoad)=='function') aLoad(json.rows);
          // $this.data('optionsBtGrid').afterLoad(json);
        },'json');
      }
      //载入静态数据,被load调用
      ,'_load':function(rows) {
        var $this = $(this);
        var columns=$this.data('optionsBtGrid').columns;
        //重新生成库存数据
        var arrHtml = [];
        for(var i=0;rows[i];i++) {
          var temp="<tr class='trRow'>";
          for(var c in columns) {          
            //cell中内容,如果为首列且不存在内容，显示为checkbox
            var v = rows[i][c];
            if(!v) {
              v='';
              if(columns[c].checkbox) {
                v='<input type="checkbox" id="chk" class="chk" value=""/>';
              }
            }
            temp+='<td>'+v+'</td>';
          }
          temp +="</tr>"; 
          arrHtml.push(temp);
        }
        var html=arrHtml.join('');
        $('tbody',this).html(html);

        //绑定行的click事件
        $('.trRow',this).click(function(){
          if(!$this.data('optionsBtGrid').onClickRow) return;
          var rowIndex =$('.trRow',$this).index(this);
          var rowData = $this.data('optionsBtGrid').data[rowIndex];
          $this.data('optionsBtGrid').onClickRow(rowIndex,rowData);
        });
        //绑定checkbox的事件
        $('.chk',this).click(function(){
          if(!$this.data('optionsBtGrid').onCheck) return;
          var rowIndex =$('.trRow',$this).index(this);
          var rowData = $this.data('optionsBtGrid').data[rowIndex];
          $this.data('optionsBtGrid').onCheck(rowIndex,rowData);
        });
      }
      //显示正在载入图标
      ,'_showLoading': function(tips){
        //直接返回，主页面中使用了ajaxstart，这里就不需要了。
        return false;
        // var $this = $(this);
        // // var windowWidth  = document.documentElement.clientWidth;

        // var divTip = $('div.tipsClass',$this.parent());
        // if(divTip.length==0) {
        //   //将tip的父元素的position设置为relative,方便相对定位
        //   $this.parent().css({'position':'relative'});
        //   var tipsDiv = '<div class="tipsClass">' + tips + '</div>';
        //   $this.parent().append(tipsDiv);
        //   divTip = $('div.tipsClass').css({
        //     'top'       : 2 + 'px',
        //     'left'      : '90%',
        //     'position'  : 'absolute',
        //     'padding'   : '3px 5px',
        //     'background': '#8FBC8F',
        //     'font-size' : 12 + 'px',
        //     'margin'    : '0 auto',
        //     'text-align': 'center',
        //     'width'     : 'auto',
        //     'color'     : '#fff',
        //     'opacity'   : '0.8'
        //   })
        // }
        // divTip.show();
      }
      //隐藏正在载入图标
      ,'_hideLoading':function() {
        return false;
        // var $this = $(this);
        // $('div.tipsClass',$this.parent()).fadeOut(1000);
      }
      //显示分页栏目
      ,'initPage':function(){
        var $this = $(this);
        //一般tbl的parent为<div class="table-responsive">,
        //一般这个div有滚动条,所以需要在这个div下面增加分页栏目
        var parentNode = $this.parent();
        var arrPage = [
          // '<ul class="pagination" style="margin:3px;">',
          //     '<li><a href="#">&laquo;</a></li>',
          //     '<li class="active"><span>1 <span class="sr-only">(current)</span></span></li>',
          //     '<li><a href="#">2</a></li>',
          //     '<li><a href="#">3</a></li>',
          //     '<li><a href="#">4</a></li>',
          //     '<li><a href="#">5</a></li>',
          //     '<li class="disabled"><span>6</span></li>',
          //     '<li><a href="#">&raquo;</a></li>',
          // '</ul>'
          //临时使用下面的按钮，后期再改进
          '<ul class="pager" style="margin:3px;">'
            ,'<li><a href="#" id="prePage">上一页</a></li>'
            ,'<li><a href="#" id="nextPage">下一页</a></li>'
          ,'</ul>'
        ];
        var page = $(arrPage.join(''));
        parentNode.after(page);

        //上一页
        $('#prePage',page).click(function(){
          //得到查询参数
          var param = $this.data('optionsBtGrid').param;
          //得到当前页数
          var pageIndex = $this.data('optionsBtGrid').pageIndex;
          pageIndex = pageIndex>0?(pageIndex-1):pageIndex;
          $this.data('optionsBtGrid').pageIndex = pageIndex;
          $this.btGrid('load',param);
        });

        //下一页
        $('#nextPage',page).click(function(){
          //得到查询参数
          var param = $this.data('optionsBtGrid').param;
          //得到当前页数
          var pageIndex = $this.data('optionsBtGrid').pageIndex;
          pageIndex++;
          $this.data('optionsBtGrid').pageIndex = pageIndex;
          // debugger;
          $this.btGrid('load',param);
        });
      }
      //得到总数据集,这里无法return 数据集，因为默认会return this,是为了保持链式调用
      ,'getRowset':function(){
        var rows = $(this).data('optionsBtGrid')['data'];
        return rows;
      }
      //得到被选中的数据集
      ,getSelectedRowset:function() {
        var $this = $(this);
        var rows = $this.btGrid('getRowset');        
        var ret =[];
        $('.chk',$this).each(function(i){
          if(this.checked) {
            ret.push(rows[i]);
          }
        });
        return ret;
      }
    };

    //return的目的是保证链式调用,比如$(a).click(fn).fade('show'); 
    //如果   
    var value;
    this.each(function() {
      //传入的参数如果为字符串，表示是调用方法,否则表示是初始化
      // debugger;
      if ( typeof options === 'object' || ! options ) {  
        value = methods.init.call( this, _methodParam );  
      } else if ( typeof options === 'string' && methods[options] ) {
        value = methods[options].call( this, _methodParam);  
      } else {  
        $.error( 'Method ' +  options + ' does not exist on btgrid' );  
      }  
    });

    return typeof value === 'undefined' ? this : value; 
  };    
  // 私有函数：debugging    
  // function debug($obj) {
  //   if (window.console && window.console.log)    
  //     window.console.log('hilight selection count: ' + $obj.size());    
  // }; 

  // 定义暴露format函数    
  // $.fn.btGrid.getRowset = function() {   
  //   alert(1);
  //   return 1; 
  //   // return '<strong>' + txt + '</strong>';    
  // };

     
// 闭包结束    
})(jQuery);   