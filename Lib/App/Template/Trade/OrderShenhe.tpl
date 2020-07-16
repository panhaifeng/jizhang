<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$title}</title>
<script src="Resource/Script/jquery.js"></script>
<script type="text/javascript">
  var jsonEnable = {$rowsE|@json_encode};
  // debugger;
{literal}
  $(function(){
    
    $('input[type!="hidden"]').attr('disabled',true);
    for(var i=0;jsonEnable[i];i++) {
      //debugger;
      $('#'+jsonEnable[i]).attr('disabled',false);
    }
    
    //如果最终审核的按钮 是取消的话，除了最终审核按钮和返回按钮，其他的都不可编辑
    if($('#subZuizhong').val()!='最终审核'){
          // alert(1);
          $('input[type!="hidden"]').attr('disabled',true);
          $('#subZuizhong').attr('disabled',false);
          $('#returnBack').attr('disabled',false);
    }
     
     //控制ischeck 是否审核字段，点了最终审核才算审核结束
     $('#subZuizhong').click(function(){
      //判断当前按钮的值
     //alert($('#subZuizhong').val());
        if($('#subZuizhong').val()==='最终审核'){
          $('#ischeck').val(1);
        }else{
            $('#ischeck').val(0);
        }
    });


  });

</script>
<style type="text/css">
td{
  text-align: center;
}
</style>
{/literal}
</head>
<body>

<form action='' method="post">
<div>合同主要信息:合同号:{$ord2pro.Order.orderCode}</div>
<div>产品编号:{$ord2pro.Products.proCode}</div>
<div>品名:{$ord2pro.Products.proName}</div>
<div>颜色：{$ord2pro.Products.color}</div>
<div>规格：{$ord2pro.Products.guige}</div>
<div>门幅：{$ord2pro.Products.menfu}</div>
<div>克重：{$ord2pro.Products.kezhong}</div>
<div>订单数量：{$ord2pro.cntYaohuo}</div>
<div>短溢范围：±{$ord2pro.Order.overflow}%</div>
<div><div>贸易部要求:</div><textarea rows="5" cols="100" name='memoTrade' id='memoTrade' readonly="true">{$ord2pro.Order.memoTrade}</textarea></div>
<div>工艺数据:</div>
<input type='hidden' id='ischeck' name='ischeck' value=''/>
<div>
<table width="1350" height="188" border="1" style="border-collapse:collapse">
  <tr>
    <td width="55" height="50" align="center">坯布</td>
    <td width="55" align="center">门幅cm</td>
    <td width="234" align="center"><input type='text' name='pibuMenfu' id='pibuMenfu' value='{$sh.pibuMenfu}' /></td>
    <td width="55" align="center">克重g/m2</td>
    <td width="77" align="center"><input type='text' name='pibuKeZhong' id='pibuKeZhong' value='{$sh.pibuKeZhong}' /></td>
    <td width="55" align="center">线长</td>
    <td width="77" align="center"><input type='text' name='pibuXianChang' id='pibuXianChang' value='{$sh.pibuXianChang}' /></td>
    <td width="55" align="center">成分</td>
    <td width="79" align="center"><input type='text' name='pibuChengfen' id='pibuChengfen' value='{$sh.pibuChengfen}' /></td>
  </tr>
  <tr>
    <td width="55" height="50">成布</td>
    <td>下机门幅</td>
    <td><input type='text' name='ChengbuMenfu' id='ChengbuMenfu' value='{$sh.ChengbuMenfu}' /></td>
    <td>下机克重</td>
    <td><input type='text' name='ChengbuKeZhong' id='ChengbuKeZhong' value='{$sh.ChengbuKeZhong}' /></td>
    <td>打卷实际门幅</td>
    <td><input type='text' name='ChengbuShiJiMenfu' id='ChengbuShiJiMenfu' value='{$sh.ChengbuShiJiMenfu}' /></td>
    <td>打卷实际克重</td>
    <td><input type='text' name='ChengbuShiJiKeZhong' id='ChengbuShiJiKeZhong' value='{$sh.ChengbuShiJiKeZhong}' /></td>
  </tr>
  <tr>
    <td width="55" height="50">测缩</td>
    <td>门幅cm</td>
    <td><input type='text' name='ceshiMenfu' id='ceshiMenfu' value='{$sh.ceshiMenfu}' /></td>
    <td>克重g/m2</td>
    <td><input type='text' name='ceshiKeZhong' id='ceshiKeZhong' value='{$sh.ceshiKeZhong}' /></td>
    <td>经向缩率</td>
    <td><input type='text' name='ceshiJingXiang' id='ceshiJingXiang' value='{$sh.ceshiJingXiang}' /></td>
    <td>纬向缩率</td>
    <td><input type='text' name='ceshiWeiXiang' id='ceshiWeiXiang' value='{$sh.ceshiWeiXiang}' /></td>
  </tr>
  <tr>
    <td width="55" rowspan="6">用纱计划</td>
    <td>纱支</td>
    <td>比率</td>
    <td>计划用纱1.05%</td>
    <td>纱情况</td>
    <td>用纱损耗</td>
    <td>实发坯布</td>
    <td><div style="width:160px;">
    <div  style=" float:left;width:120px ;"><input type='text' name='shifapibu' id='shifapibu' value='{$sh.shifapibu}' style="width:115px"></div>
    <div style="float:left;width:30px">匹</div>
    </div></td>
    <td><div style="width:160px;">
    <div  style=" float:left;width:120px ;"><input type='text' name='shifaGongjin' id='shifaGongjin' value='{$sh.shifaGongjin}' style="width:115px"></div>
    <div style="float:left;width:30px">公斤</div>
    </div></td>
  </tr>
  <tr>
    <td><input type='text' name='shazhi1' id='shazhi1' value='{$sh.shazhi1}' /></td>
    <td><input type='text' name='bilv1' id='bilv1' value='{$sh.bilv1}' /></td>
    <td><input type='text' name='jihuaYongSha1' id='jihuaYongSha1' value='{$sh.jihuaYongSha1}' /></td>
    <td><input type='text' name='shaqingkuang1' id='shaqingkuang1' value='{$sh.shaqingkuang1}' /></td>
    <td><input type='text' name='sunhao1' id='sunhao1' value='{$sh.sunhao1}' /></td>
    <td>成布数量</td>
    <td><div style="width:160px;">
    <div  style=" float:left;width:120px ;"><input type='text' name='chengbushuliang' id='chengbushuliang' value='{$sh.chengbushuliang}' style="width:115px"></div>
    <div style="float:left;width:30px">匹</div>
    </div></td>
    <td><div style="width:160px;">
    <div  style=" float:left;width:120px ;"><input type='text' name='chengbuGongjin' id='chengbuGongjin' value='{$sh.chengbuGongjin} ' style="width:115px"></div>
    <div style="float:left;width:30px">公斤</div>
    </div></td>
  </tr>
  <tr>
     <td><input type='text' name='shazhi2' id='shazhi2' value='{$sh.shazhi2}' /></td>
    <td><input type='text' name='bilv2' id='bilv2' value='{$sh.bilv2}' /></td>
    <td><input type='text' name='jihuaYongSha2' id='jihuaYongSha2' value='{$sh.jihuaYongSha2}' /></td>
    <td><input type='text' name='shaqingkuang2' id='shaqingkuang2' value='{$sh.shaqingkuang2}' /></td>
    <td><input type='text' name='sunhao2' id='sunhao2' value='{$sh.sunhao2}' /></td>
    <td>定型损耗</td>
    <td colspan="2"><input type='text' name='dingxingSunhao' id='dingxingSunhao' value='{$sh.dingxingSunhao}'>%</td>
  </tr>
  <tr>
    <td><input type='text' name='shazhi3' id='shazhi3' value='{$sh.shazhi3}' /></td>
    <td><input type='text' name='bilv3' id='bilv3' value='{$sh.bilv3}' /></td>
    <td><input type='text' name='jihuaYongSha3' id='jihuaYongSha3' value='{$sh.jihuaYongSha3}' /></td>
    <td><input type='text' name='shaqingkuang3' id='shaqingkuang3' value='{$sh.shaqingkuang3}' /></td>
    <td><input type='text' name='sunhao3' id='sunhao3' value='{$sh.sunhao3}' /></td>
    <td>机台号</td>
    <td colspan="2"><input type='text' name='jitaihao' id='jitaihao' value='{$sh.jitaihao}'></td>
  </tr>
  <tr>
    <td><input type='text' name='shazhi4' id='shazhi4' value='{$sh.shazhi4}' /></td>
    <td><input type='text' name='bilv4' id='bilv4' value='{$sh.bilv4}' /></td>
    <td><input type='text' name='jihuaYongSha4' id='jihuaYongSha4' value='{$sh.jihuaYongSha4}' /></td>
    <td><input type='text' name='shaqingkuang4' id='shaqingkuang4' value='{$sh.shaqingkuang4}' /></td>
    <td><input type='text' name='sunhao4' id='sunhao4' value='{$sh.sunhao4}' /></td>
    <td>次品数量</td>
    <td colspan="2"><input type='text' name='cipinshuliang' id='cipinshuliang' value='{$sh.cipinshuliang}'></td>
  </tr>
  <tr>
    <td><input type='text' name='shazhi5' id='shazhi5' value='{$sh.shazhi5}' /></td>
    <td><input type='text' name='bilv5' id='bilv5' value='{$sh.bilv5}' /></td>
    <td><input type='text' name='jihuaYongSha5' id='jihuaYongSha5' value='{$sh.jihuaYongSha5}' /></td>
    <td><input type='text' name='shaqingkuang5' id='shaqingkuang5' value='{$sh.shaqingkuang5}' /></td>
    <td><input type='text' name='sunhao5' id='sunhao5' value='{$sh.sunhao5}' /></td>
    <td>完成日期</td>
    <td colspan="2"><input type='text' name='wanchengDate' id='wanchengDate' value='{$sh.wanchengDate}'></td>
  </tr>
</table>
<div>审核人:{$sh.subTrader},审核时间:{$sh.subTraderTime}<input type='submit' value='{if $sh.subTrader}取消{else}业务员审核{/if}' name='subTrader' id='subTrader'/></div>
<div>审核人:{$sh.subGendan},审核时间:{$sh.subGendanTime}<input type='submit' value='{if $sh.subGendan}取消{else}跟单审核{/if}' name='subGendan' id='subGendan' /></div>
<div>审核人:{$sh.subZhizao},审核时间:{$sh.subZhizaoTime}<input type='submit' value='{if $sh.subZhizao}取消{else}织造审核{/if}' name='subZhizao' id='subZhizao' /></div>
<div>审核人:{$sh.subDingxing},审核时间:{$sh.subDingxingTime}<input type='submit' value='{if $sh.subDingxing}取消{else}定型审核{/if}' name='subDingxing' id='subDingxing' /></div>
<div>审核人:{$sh.subChengpin},审核时间:{$sh.subChengpinTime}<input type='submit' value='{if $sh.subChengpin}取消{else}成品审核{/if}' name='subChengpin' id='subChengpin' /></div>
<div>审核人:{$sh.subShengchan},审核时间:{$sh.subShengchanTime}<input type='submit' value='{if $sh.subShengchan}取消{else}生产负责审核{/if}' name='subShengchan' id='subShengchan' /></div>
<div>审核人:{$sh.subZuizhong},审核时间:{$sh.subZuizhongTime}<input type='submit' value='{if $sh.subZuizhong}取消{else}最终审核{/if}' name='subZuizhong' id='subZuizhong' /></div>
<div><input type="button" id="returnBack" name="returnBack" value='返回' onClick="javascript:window.location.href='{url controller=Trade_Order action='ShenheList'}'" /></div>
<input type='hidden' id='ord2proId' name='ord2proId' value='{$smarty.get.ord2proId}'/>
</form>
</body>
</html>
