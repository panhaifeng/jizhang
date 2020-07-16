<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$title}</title>
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/Calendar/WdatePicker.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.validate.js"}
{webcontrol type='LoadJsCss' src="Resource/Css/validate.css"}
{webcontrol type='LoadJsCss' src="Resource/Script/Common.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/ymPrompt/ymPrompt.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.validate.js"}
{webcontrol type='LoadJsCss' src="Resource/Css/validate.css"}
{webcontrol type='LoadJsCss' src="Resource/Css/EditCommon.css"}
{webcontrol type='LoadJsCss' src="Resource/Script/autocomplete/autocomplete.css"}
{webcontrol type='LoadJsCss' src="Resource/Script/autocomplete/autocomplete.js"}
{literal}
<style type='text/css'>
#chukuCnt{
  background-image: url(Resource/Image/unitKg.png);
  background-position: 125px 3px;
  background-repeat: no-repeat;
}
</style>
<script language="javascript">
var ghHtml=null;
var kwHtml=null;
$(function(){
  ghHtml = $('#ganghao').parent().html();
  kwHtml = $('#kucunWei').parent().html();
    $.validator.addMethod("checkCnts", function() {
        var cnt=document.getElementById('chukuCnt').value;
        if(cnt.length>0){
            if(parseFloat(cnt)>=0){
                    return true;
            }else{
                return false;
            }
        }
    }, "必选，应为整数类型");
    $('#form1').validate({
        rules:{
            'proCode':'required',
            'caiyangren':'required',
            'chukuCnt':'checkCnts',
            'chukuDate':'required'
        },
        submitHandler : function(form){
            var o=document.getElementsByName('submit');
            for(var i=0;i<o.length;i++){
                o[i].disabled=true;
            }
            form.submit();
        }
        //,debug:true
    });



    document.onkeydown = function(e){
        var ev = document.all ? window.event : e;
        var target = document.all ? ev.srcElement : ev.target;
        if(ev.keyCode==13  && target.type!='submit')  ev.keyCode=9;
        //alert(ev.keyCode);
    };

  $('#chukuCnt,#danjia').change(function(){
    var cnt = parseFloat($('#chukuCnt').val())||0;
    var danjia = parseFloat($('#danjia').val())||0;
    var money = (danjia*cnt).toFixed(2);

    $('#money').val(money);
  });

  auto();

});


    function getBarCodeInfo(obj) {
        if(obj.value=="" || obj.value==null){
            setValue();
            document.getElementById('imagediv').innerHTML="";
            return false;
        }
        var id=document.getElementById('id').value;
        var url = '?controller=Sample_Caiyang&action=GetCodeInfo';
        var param={proCode:obj.value,id:id};
        $.getJSON(url,param,function(json){
            auto();
            if(json.success==false) {
                setValue();
                document.getElementById('imagediv').innerHTML=json.msg;
                return false;
            }else
            {
                //document.getElementById('barCode').value=json.barCode;
                $('#sampleId').val(json.id);
                $('#guige').val(json.guige);
                $('#chengfen').val(json.chengfen);
                $('#color').val(json.color);
                $('#menfu').val(json.menfu);
                $('#kezhong').val(json.kezhong);
                $('#kucunCnt').val(json.kucun);
                $('#proCode').val(json.proCode);
                $('#danjia').val(json.danjia);
                $('#ganghao').val('');
                $('#kucunWei').val('');

                document.getElementById('caiyangren').focus();

                //alert(json.id);
                if(json.imageFile!=""){
                    var imageValue="<img id='imagefile' src='"+json.imageFile+"'  border='1'>";
                    document.getElementById('imagediv').innerHTML=imageValue;

                }
                else{
                    var imageValue="<table width='150' height='60' border='1'>"+
                                "<tr>"+
                                "<td align='center' valign='middle' style='color:#CCC; border:0.5px;'>暂无图片</td>"+
                                "</tr>"+
                                "</table>";
                    document.getElementById('imagediv').innerHTML=imageValue;
                }
            }
        });
    }


    function setValue(){
            // $('#barCode').val('');
            $('#chengfen').val('');
            $('#guige').val('');
            $('#color').val('');
            $('#menfu').val('');
            $('#kezhong').val('');
            $('#kucunCnt').val('');
            $('#sampleId').val('');
            $('#proCode').val('');
            $('#ganghao').val('');
            $('#kucunWei').val('');

            document.getElementById('caiyangren').focus();
    }

    function chekRukuCnt(){
        var cnt=document.getElementById('chukuCnt').value;
        // var cnt2=document.getElementById('kucunCnt').value;
        if(cnt.length<0){
            return false;
        }
        if(parseFloat(cnt)<0 || parseFloat(cnt)=="NaN"){
            return false;
        }
        // if(parseFloat(cnt2)<0){
        // 	return false;
        // }
        if(parseFloat(cnt)==0){
            alert("必须大于0！");
            var cnt=document.getElementById('chukuCnt').value="";
            return false;
        }

        // if(parseFloat(cnt)>parseFloat(cnt2)){
        // 	alert("不能大于库存数！");
        // 	var cnt=document.getElementById('chukuCnt').value="";
        // 	return false;
        // }


    }

  function auto(){
    //查找现有的值
    var ganghao = $('#ganghao').val();
    var kucunWei = $('#kucunWei').val();

    //替换干净的控件代码
    $('#ganghao').replaceWith(ghHtml);
    $('#kucunWei').replaceWith(kwHtml);

    //保持值不变
    $('#ganghao').val(ganghao);
    $('#kucunWei').val(kucunWei);

    //开始渲染
    $('#ganghao').autocomplete('?controller=Sample_Yangpin&action=autocompleteGh', {
        minChars: 0,
        remoteDataType:'json',
        onFocus:true,
        useCache:false,
        extraParams:{proCode:$('#proCode').val()}
      });

      $('#kucunWei').autocomplete('?controller=Sample_Yangpin&action=autocompleteKw', {
        minChars: 0,
        remoteDataType:'json',
        onFocus:true,
        useCache:false,
        extraParams:{proCode:$('#proCode').val()}
      });
  }

</script>

{/literal}
</head>
<body><form name="form1" id="form1" action="{url controller=$smarty.get.controller action='save'}" enctype="multipart/form-data" method="post">
<fieldset>
<legend>采样基本信息</legend>
<input type="hidden" name="id" id="id" value="{$rowset.id}">
<input type="hidden" name="sampleId" id="sampleId" value="{$rowset.sampleId}">
<table id="mainTable">
  <tr>
    <td class="title">条码扫描：</td>
    <td><input type="text" id="proCode" name="proCode" value="{$rowset.sampleInfo.proCode}" onChange="getBarCodeInfo(this)" ><span class="bitian">*</span>
    </td>
    <td rowspan="11" valign="top" align="left">
    <div id="imagediv">{if $rowset.sampleInfo.imageFile!='' }<img src="{$rowset.sampleInfo.imageFile}"  border="0.5"><br>
       {elseif $rowset!=null}
      <table width="150" height='60' border="1" cellspacing="0" cellpadding="0">
        <tr>
             <td align="center" valign="middle" style="color:#CCC; border:0.5px;">暂无图片</td>
        </tr>
     </table>
{/if}</div>

     </td>
    </tr>

  <tr>
  <td class="title">规格：</td>
<td>
  <input tupe="text" name="guige" id="guige" value="{$rowset.sampleInfo.guige}" readonly='true'></td>
</tr>

  <tr>
    <td class="title">成分：</td>
  <td><input tupe="text" name="chengfen" id="chengfen" value="{$rowset.sampleInfo.chengfen}" readonly='true'></td>
  </tr>
  <tr>
  <td class="title">颜色：</td>
    <td><input tupe="text" name="color" id="color" value="{$rowset.sampleInfo.color}" readonly='true'></td>
    </tr>
  <tr>
  <td class="title">门幅：</td>
    <td><input tupe="text" name="menfu" id="menfu" value="{$rowset.sampleInfo.menfu}" readonly='true'></td>
    </tr>
  <tr>
    <td class="title">克重：</td>
  <td><input tupe="text" name="kezhong" id="kezhong" value="{$rowset.sampleInfo.kezhong}" readonly='true'></td>
  </tr>
  <!-- <tr>
    <td class="title">库存数量：</td>
    <td ><input tupe="text" name="kucunCnt" id="kucunCnt" value="{$rowset.sampleInfo.kucunCnt}" readonly='true'></td>
  </tr> -->
  <tr>
    <td class="title">缸号：</td>
  <td ><input tupe="text" name="ganghao" id="ganghao" value="{$rowset.ganghao}"></td>
  </tr>
  <tr>
    <td class="title">库存位置：</td>
  <td ><input tupe="text" name="kucunWei" id="kucunWei" value="{$rowset.kucunWei}"></td>
  </tr>
  <tr>
      <td class="title">采样人：</td>
      <td colspan="2"><select name="caiyangren" id="caiyangren" style="width:auto;">

{webcontrol type='Employoptions' model='jichu_Employ' selected=$rowset.caiyangren}

        </select><span class="bitian">*</span></td>
    </tr>
  <tr>
    <td class="title">客户：</td>
      <td colspan="2">
      <input name="clientName" type="text" id="clientName" value="{$rowset.clientName}">
      <input name="clientId" type="hidden" id="clientId" value="{$rowset.clientName}"></td>
    </tr>
    <tr>
    <td class="title">采样日期：</td>
      <td colspan="2"><input tupe="text" name="chukuDate" id="chukuDate"  value="{$rowset.chukuDate|default:$smarty.now|date_format:'%Y-%m-%d'}" onClick="calendar()"><span class="bitian">*</span></td>
    </tr>
    <tr>
    <td class="title">采样件数：</td>
      <td colspan="2"><input tupe="text" name="cntJian" id="cntJian" value="{$rowset.cntJian}"></td>
    </tr>
    <tr>
    <td class="title">采样数量：</td>
      <td colspan="2"><input tupe="text" name="chukuCnt" id="chukuCnt"  onBlur="chekRukuCnt()"  value="{$rowset.chukuCnt}" ><span class="bitian">*</span></td>
    </tr>
    <tr>
    <td class="title">采样米数：</td>
      <td colspan="2"><input tupe="text" name="cntM" id="cntM" value="{$rowset.cntM}" ></td>
    </tr>
    <tr>
    <td class="title">单价：</td>
  <td ><input tupe="text" name="danjia" id="danjia" value="{$rowset.danjia}"></td>
  </tr>
  <tr>
    <td class="title">金额：</td>
  <td ><input tupe="text" name="money" id="money" value="{$rowset.money}"></td>
  </tr>
   <tr>
    <td class="title">运费：</td>
  <td ><input tupe="text" name="yunfei" id="yunfei" value="{$rowset.yunfei}"></td>
  </tr>
    <tr>
    <td class="title">备注：</td>
    <td colspan="2"><textarea name="memo" id="memo" cols="50"  style="width:250px;" value="">{$rowset.memo}</textarea></td>
    </tr>
</table>
</fieldset>
<table id="buttonTable">
<tr>
        <td><input type="submit" id="submit" name="submit"  value='保存并返回'>
        <input type="submit" id="submit" name="submit"  value='保存并新增下一个'>
        <input type="button" id="button" name="button"  value='返回'
        onClick="window.location.href=('?controller=Sample_Caiyang&action=right')">
        </td>
    </tr>
</table>
</form>

</body>
</html>
