<script language="javascript">
{literal}
$(function(){
    //'unit[]',
    var quickMoveMap = ['menfu[]', 'kezhong[]',  'zhenshu[]', 'cunshu[]', 'xiadanXianchang[]', 'cntJian[]', 'cntYaohuo[]', 'danjia[]', 'memo[]'];
    // 针数、寸数、下单线长，第一行填完给下面行赋值
    $('input[name="zhenshu[]"],input[name="cunshu[]"],input[name="xiadanXianchang[]"]').live('change',function(){
        copy($(this));
    });

    $('#table_main').on('keydown', '[name="'+quickMoveMap.join('"],[name="')+'"]', function(e){
        quickMove(e, $(this));
    });
    var quickMove = function(e, $o){
        var keyCode = {'left':37,'right':39, 'top':38, 'down':40, 'enter':13};
        var length = quickMoveMap.length;
        // 当前所在位置,0为起始
        if(!Array.indexOf){
            Array.prototype.indexOf = function(el){
                for (var i=0,n=this.length; i<n; i++){
                    if (this[i] === el){
                        return i;
                    }
                }
                return -1;
            }
        }
        var nowX = quickMoveMap.indexOf($o.attr('name'));
        var nowY = $('[name="'+$o.attr('name')+'"]').index($o);
        // 移动后所在位置
        var moveToX = 0, moveToY = 0;

        // 所有快捷移动在altKey下支持
        if(e.altKey){
            return false;
        }

        if(e.keyCode == keyCode.left){
            moveToX = nowX - 1;
            moveToY = nowY;
        }else if(e.keyCode == keyCode.top){
            moveToX = nowX;
            moveToY = nowY - 1;
        }else if(e.keyCode == keyCode.right){
            moveToX = nowX + 1;
            moveToY = nowY;
        }else if(e.keyCode == keyCode.down || e.keyCode ==keyCode.enter){
            moveToX = nowX;
            moveToY = nowY + 1;
        }else{
            // 其他情况，不响应
            return false;
        }
        if(moveToX<0){
            if(moveToY == 0){
                moveToX = 0;
            }else{
                moveToY = moveToY - 1;
                moveToX = length - 1;
            }
        }else if(moveToX==length){
            moveToY = moveToY + 1;
            moveToX = 0;
        }
        if(moveToY<0){
            moveToY = 0;
        }

        if(moveToX>=0 && moveToY>=0){
            document.getElementsByName(quickMoveMap[moveToX])[moveToY].focus();
        }
    };


     $('#btnclientName').click(function(){
        //重写onSelect接口后再弹出
        var _this = this;
        onSelect = function(ret) {
            $(_this).siblings('#clientId').val(ret.id);
            $(_this).siblings('#clientName').val(ret.compName);
            if(ret.traderId){
                $('#traderId').val(ret.traderId);
                $('#traderId').attr('disabled',true);
            }
            return;
        }
        var url="?controller=Jichu_Client&action=Popup";
        var ret = window.open(url,'newwindow','height=500,width=1000,top=200,left=400');
        //触发onSelClient函数
        if(onSelect) onSelect(this);
        return;
      });

    $('[name="btnProduct"]').click(function(){
        //重写onSelect接口后再弹出
        var _this = this;
        onSelect = function(ret) {
          var tr = $(_this).parents(".trRow");
          $('[name="proCode"]',tr).val(ret.proCode);
          $('[name="productId[]"]',tr).val(ret.id);
          $('[name="proName[]"]',tr).val(ret.proName);
          $('[name="guige[]"]',tr).val(ret.guige);
          $('[name="chengfen[]"]',tr).val(ret.chengFen);
          $('[name="menfu[]"]',tr).val(ret.menfu);
          $('[name="kezhong[]"]',tr).val(ret.kezhong);
          // alert(ret.guige);
          $('[name="proName[]"]',tr).attr("title",ret.proName);
          //alert(ret.color);
          $('[name="color[]"]',tr).val(ret.color);
          if(!ret.unit){
            $('[name="unit[]"]',tr).val('公斤');
          }
          else{
            $('[name="unit[]"]',tr).val(ret.unit);
          }
          $('[name="xiadanXianchang[]"]',tr).val(ret.xianchang);
          $('[name="zhenshu[]"]',tr).val(ret.zhengshu);
          $('[name="cunshu[]"]',tr).val(ret.cunshu);
          $('[name="cnt[]"]',tr).focus();
          return;
        }
        var url="?controller=jichu_product&action=popup2&proKind=1";
        var ret = window.open(url,'window','height=600,width=1200,top=150,left=300');
      });

});

    $('[name="btnPopForAdd"]').click(function(e){
        var p = $(this).parents('.clsPop');
        //弹出窗地址
        var url = $(this).attr('url');    

        var textFld= $(this).attr('textFld');
        var hiddenFld= $(this).attr('hiddenFld');
        var id = $('.hideId',p).attr('id');
        var tip = $(this).attr('tip')||'新增功能';

         //打开窗口之前处理url地址
        // if($("[name='"+id+"']").data("beforeOpen")){
        //   url=$('.hideId',p).data('beforeOpen').call($('.hideId',p),url);
        //   if(url==false)return;
        // }
        var _width = parseInt($(this).attr('dialogWidth'))||750;

        //2014-9-24 by jeff,改为使用layer
        $.layer({
          type: 2,
          shade: [1],
          fix: false,
          title: tip,
          maxmin: false,
          iframe: {src : url},
          // border:false,
          area: [_width+'px' , '440px'],
          close: function(index){//关闭时触发
              
          },
          //回调函数定义
          callback:function(index,ret) {
            //选中行后填充textBox和对应的隐藏id
            // debugger;
            $('#textBox',p).val(ret[textFld]);
            $('.hideId',p).val(ret[hiddenFld]);
            //执行回调函数,就是触发自定义事件:onSel
            // if(!$("[name='"+id+"']").data("events") || !$("[name='"+id+"']").data("events")['onSel']) {
              
            // }
            if(!$("[name='"+id+"']").data("onSel")) {
              alert('未发现对popup控件 '+id+ ' 的回调函数进行定义,您可能需要在sonTpl中用data进行事件绑定:\n$("[name=\''+id+'[]\']").data(\'onSel\',function(ret){...})');
              return;
            }
            // debugger;

            $('.hideId',p).data('onSel').call($('.hideId',p),ret);
          }
        });
    });

function copy(obj){
    var name = obj.attr('name');
    var value = obj.val();
    var pos = $('input[name="'+name+'"]').index(obj);
    if(pos==0){
        $('input[name="'+name+'"]').each(function(i){
            if(i!=0){
                $(this).val(value);
            }
        });
    }
}

function beforeSubmit(){
    var traderId=$('#traderId').val();
    if(traderId){
        $("#traderId").attr("disabled",false);
    }
    return true;
}

// function tb_remove(){
//     layer.close(madan_layer); //执行关闭
// }

{/literal}
</script>