<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<script language="javascript" src="Resource/Script/jquery.js"></script>
<script language="javascript" type="text/javascript" src="Resource/Script/Calendar/WdatePicker.js"></script>
<script language="javascript" src="Resource/Script/jquery.validate.js"></script>
<script language="javascript" src="Resource/Script/autocomplete/autocomplete.js"></script>
{literal}
<script type="text/javascript">

function prnbutt_onclick() {
  window.print();
  return true;
}

function window_onbeforeprint() {
  prn.style.visibility ="hidden";
  return true;
}

function window_onafterprint() {
  prn.style.visibility = "visible";
  return true;
}

window.onload=function(){
var kuaidu=$('#cdqkson').css('width');
$('#cdqkfather').css({width:kuaidu});
}
</script>
<style type="text/css">
table tr td{ height:25px;}
.trTitle td{ border:0px;}
.tr1{ width:25%}
#search{
  /*display: none;*/
  width: 500px;
  height: 300px;
  position: absolute;
  top: 50%;
  left: 50%;
  margin: -150px 0px 0px -250px;
  border: 1px solid #8DB2E3;
  background: #8DB2E3;
  z-index:11;
}
#search > ul,li{
  list-style: none;
  line-height: 34px;
  text-align: left;
}
#search input{
  height: 22px;
  line-height: 22px;
}
#search select{
  height: 24px;
  line-height: 24px;
}
#btn_search{
  margin-top: 15px;
  width: 320px;
  height: 32px !important;
  border: 0px;
  border-radius: 4px;
  background: #5897BA;
  cursor: pointer;
}
#btn_search:hover{
  background: #38779A;
}
#client_screen{
  width:100%;
  height:100%;
  background:#000;
  position:absolute;
  top:0;
  left:0;
  z-index:2;
  opacity:0.7;
  filter:alpha(opacity =70);
}
.header td{
  height: 26px;
  white-space: nowrap;
}
#list td{
  text-align: center;
}
</style>
{/literal}
</head>

<body onafterprint="return window_onafterprint()" onbeforeprint="return window_onbeforeprint()">
<form name="cdform" id="cdform" action="{url controller=$smarty.get.controller action='zhijian1'}" method="post">
<table width="98%"  border="1" align="center" cellpadding="0" cellspacing="0">
  <tr>
      <td align="center">Jinma TEXTILE</td>
  </tr>
  <tr>
      <td align="center">验 布 记 分 表</td>
  </tr>
  <tr>
      <td align="center">FABRIC INSPECTION REPORT</td>
  </tr>
  <tr>
      <td>
         <table width="98%"  border="1" align="center" cellpadding="0" cellspacing="0">
           <tr>
               <td class="tr1">订单号:{$info.orderCode }</td>
                <td class="tr1">品名:{$info.proName}</td>
                <td class="tr1">规格:{$info.guige}</td>
                <td class="tr1">产品编号:{$info.proCode}</td>
                <!-- <td class="tr1">验布员:{$info.yanbuName}</td>    -->
             </tr>
              <tr>
                <td class="tr1">颜色:{$info.color}</td>
                <td class="tr1">要货数量:{$info.cntYaohuo}{$info.unit}</td>
                <td class="tr1">客户:{$info.codeAtOrder}</td>
                <td class="tr1">日期:{$arr_condition.date}</td>
             </tr>
         </table>
      </td>
  </tr>
  <tr>
      <td>
         <table  border="1" align="center" cellpadding="0" cellspacing="0" class="tableHaveBorder">
           <tr>
              <td align="center">件号</td>
              <td align="center">缸号</td>
              <td align="center">称前重量</td>
              <!-- <td align="center">门幅</td>
              <td align="center">克重</td> -->
              <td align="center">数量</td>
              <td align="center">长度</td>
              <td align="center">验布工</td>
              <td id="cdqkfather">疵点情况</td>
              <td align="center">总扣分</td>
           </tr>
           {foreach from=$rowset item='item1'}
           <tr style="border-collapse:collapse">
            <td align="center">{$item1.jianhao}</td>
            <td align="center">{$item1.ganghao}</td>
            <td align="center">{$item1.c1}</td>
            <!-- <td align="center">{$item1.width}</td>
            <td align="center">{$item1.kezhong}</td> -->
            <td align="center">{$item1.weight}</td>
            <td align="center">{$item1.length}</td>
            <td align="center">{$item1.userName1}</td>
            <td >
              <table border="1" cellpadding="0" cellspacing="0"  rules="all" frame="rhs">
                <tr id="cdqkson">
                  <td>位置</td>
                    {foreach from=$item1.xcinfo item='item2'}
                      {if $item2.xcPos!=''}
                      <td align="center">{$item2.xcPos}</td>
                      {/if}
                    {/foreach}
                </tr>
                <tr>
                  <td>疵点</td>
                    {foreach from=$item1.xcinfo item='item2'}
                      {if $item2.xcName!=''}
                      <td align="center">{$item2.xcName}</td>
                      {/if}
                    {/foreach}
                </tr>
                <tr>
                  <td>扣分</td>
                    {foreach from=$item1.xcinfo item='item2'}
                      {if $item2.score!=''}
                      <td align="center">{$item2.score}</td>
                      {/if}
                    {/foreach}
                </tr>
              </table>
           </td>
           <td align="center">{$item1.totalScore}</td>
           </tr>
          {/foreach}
</table>
</td>
</tr>
</table>
</form>
</body>
</html>
