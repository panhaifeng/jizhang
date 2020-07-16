//将控件改为助记码和弹出选择的控件
//比如在客户选择和供应商选择，产品选择，原料选择时都可使用，通用性比较大。
//可根据文本框中输入的关键字进行模糊搜索，也可在弹出的对话框中选择
//注意，urlSearch返回的json对象必须为多维数组，不能为单一对象。
var TmisPopup = function (settings) {
    var config = {
        obj:null,//进行渲染的目标元素,可以是document.getElementsByName('')得到的数组
        fieldInText:'',//选择后对text控件进行赋值的字段
        fieldInHidden:'id',//选择后对hidden控件进行赋值的字段
        width : 120,//渲染后的宽度
        urlPop:'',//弹出框的地址
        titlePop:'',//弹出框的标题
        widthPop:700,
        heightPop:500,
        urlSearch:'',//根据输入进行检索的地址
        idHidden:'',//创建的hidden元素的id和name
        idBtn:'',//创建的按钮的id,如果不需要创建按钮，留空表示不需要创建按钮,urlPop也不需要
        isArray:false,//if true,创建的元素以[]结尾
        onSelect:null//选择某个记录后的触发动作，会以该记录作为参数执行
    };
    $.extend(config, settings);

    //如果obj是数组，肯定是数组
    if(config.obj.length) config.isArray=true;

    this.config = config;

    var arr = [];
    if(config.isArray) {
        config.idHidden+='[]';
        config.idBtn+='[]';
    }

    if(config.obj.length) arr = config.obj;
    else arr.push(config.obj);
    var _this = this;
    $(arr).each(function(ppooss){
        //设置外观
        _this.render(this);
    });
}
TmisPopup.prototype = {
    render : function(obj){
        if($(obj).attr('isPopup')==1) return true;
        var config = this.config;
        $(obj).attr('isPopup',1);
        var indexOfThis = $('input[name="'+obj.name+'"]').index(obj);
        //在弹出窗口中选择某个记录后的回调函数
        var callback = function(ret,pos){
            if(typeof(ret)!='object') return false;
            //将config.obj中的元素形成数组
            var arr = [];
            if(config.obj.length) arr = config.obj;
            else arr.push(config.obj);
            var hid = $(arr[pos]).prev('input[type="hidden"]');
            if(ret===null) {
                arr[pos].value='';
                hid.value='';
                return false;
            }
            arr[pos].value=$.trim(ret[config.fieldInText]);
            hid.val(ret[config.fieldInHidden]);
            //dump(config);
            if(config.onSelect) {
                config.onSelect(ret,pos);
            }
            return false;
        }

        var _this = this;
        //设置外观
        $(obj).css('width',config.width+'px');
        $(obj).focus(function(){
            this.select();
        });
        $(obj).keydown(function(e){			
            var _this = this;
			//debugger;
			if(config.urlSearch=='') {
				return true;
			}
            var arr = [];
            if(config.obj.length) arr = config.obj;
            else arr.push(config.obj);
            if(e.keyCode==13) {
                //ajax获得相匹配的数据
                var url=config.urlSearch;
				//alert(obj.value);
                var param = {
                    //code:this.value
					code:obj.value
                    };
                var pos = $('input[name="'+_this.name+'"]').index(_this);
                $.getJSON(url,param,function(json){
											// alert(json);
                    if(!json||json.length==0) {
                        alert('未发现匹配记录');arr[pos].select();return false;
                    }

                    //如果是单一记录，直接赋值
                    if(json.length==1) {
                        arr[pos].value=$.trim(json[0][config.fieldInText]);
                        var hid = $(arr[pos]).prev('input[type="hidden"]');
                        hid.val(json[0][config.fieldInHidden]);
                        if(config.onSelect) config.onSelect(json[0],pos);
                        return false;
                    }

                    //如果多条记录,弹出选择框
                    if(config.urlPop.indexOf('?')==-1) var url = config.urlPop+'?code='+encodeURI(obj.value);
                    else var url = config.urlPop+'&key='+encodeURI(obj.value);
                    ymPrompt.win({
                        message:url,
                        handler:function(ret){
                            //得到位置
                            var pos = $('input[name="'+_this.name+'"]').index(_this);
                            callback(ret,pos);
                        },
                        width:config.widthPop,
                        height:config.heightPop,
                        title:config.titlePop,
                        iframe:true
                    });
                    return false;
                });
                return false;
            }
        });

        var hid = $(obj).prev('input[type="hidden"]');
        //首先增加btn//设置按钮的onclick
        if(config.idBtn!='') {
            //var temp = document.createElement("<input onclick='alert(1)'>");
            //temp.onclick=function(){alert(1)};
            //obj.parentNode.appendChild(temp);
            var temp = $('<input type="button" name="'+config.idBtn+'" id="'+config.idBtn+'" value="..." style="border:1px solid #999;height:22px;width:22px; background-color:#ccc; margin-left:1px;"/>');
            temp.click(function(){
                var url = config.urlPop;
                var _this = this;
                ymPrompt.win({
                    message:url,
                    handler:function(ret){
                        //得到位置
                        var pos=$('input[name="'+_this.name+'"]').index(_this);
                        callback(ret,pos);
                    },
                    width:config.widthPop,
                    height:config.heightPop,
                    title:config.titlePop,
                    iframe:true
                });
                return false;
            });
            //debugger;
            $(obj).after(temp);
            //else hid.before(temp);
        }
        //增加hidden控件,如果已经存在则不增加
        if(hid.length<1) $(obj).before('<input type="hidden" name="'+config.idHidden+'" id="'+config.idHidden+'"/>');
    }
}