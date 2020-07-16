<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="Resource/Css/Main.css" rel="stylesheet" type="text/css" />
{literal}
<script language="javascript" src="Resource/Script/Calendar/WdatePicker.js"></script>
<script language="javascript">
String.prototype.getParam = function(name) 
  {
    var reg = new RegExp("(^|;|\\s)"+name+"\\s*(=|:)\\s*(\"|\')([^\\3;]*)\\3(\\s|;|$)", "i");
    var r = this.match(reg); if (r!=null) return r[4]; return "";
  };
function tb_delete()
{
    var ls_t=document.all("mytable");
    maxcell=ls_t.rows.length;

    if(maxcell > 1)
    {
        ls_t.deleteRow() ;
    }
}
function AddNew()
{
	var ArrVar=new Array("chukuId","chukuNum","chukuDate","compName","money","memo");	
    ls_t=window.dialogArguments.document.all("TbAdd");
    maxcell=ls_t.rows(0).cells.length;
    mynewrow = ls_t.insertRow();	

	for (i=0;i<ArrVar.length;i++) {
		if (ArrVar[i]=="id") continue;
		text=arguments[0].getParam(ArrVar[i]);
		if (text=="") text="&nbsp;";
		mynewcell=mynewrow.insertCell();		
		if (ArrVar[i]=="chukuNum") mynewcell.innerHTML="<input type=\"text\" size=15 name=\"chukuNum[]\">";	
		else if (ArrVar[i]=="chukuDate") mynewcell.innerHTML="<input type=\"text\" size=15 name=\"chukuDate[]\">";
		else if (ArrVar[i]=="productId") mynewcell.innerHTML="<input type=\"hidden\" size=15 name=\"productId[]\">";
		else if (ArrVar[i]=="id1") mynewcell.innerHTML="<input type=\"hidden\" size=15 name=\"id1[]\">";
		else if (ArrVar[i]=="compName") mynewcell.innerHTML="<input type=\"text\" size=15 name=\"compName[]\">";
		else if (ArrVar[i]=="money") mynewcell.innerHTML="<input type=\"text\" size=15 name=\"money[]\">";
		else if (ArrVar[i]=="memo") mynewcell.innerHTML="<input type=\"text\" size=15 name=\"memo[]\">";
		else if	 (ArrVar[i]=="action") mynewcell.innerHTML="<input name='btnDel' type='button' id='btnDel' value='删除' onClick='delRow(this)'>";	
		else mynewcell.innerHTML=text;
	}

}
function ret(){
	var tbl = document.getElementById('tbl');
	var sels = document.getElementsByName('id[]'); 
	var tr = null;	
	var r = new Array();
	for(var i=0;i<sels.length;i++) {
		if(sels[i].checked==true) {
			
			var obj={};
			tr = tbl.rows[i+1];	
			for(var j=0;j<tr.cells.length;j++) {
				if(tr.cells[j].id) obj[tr.cells[j].id]=tr.cells[j].innerHTML;
			}
			r.splice(0,0,obj);
		}
	}

	window.returnValue=r;
	window.close();
}
</script>
{/literal}
<base target="_self">
</head>

<body>
    <form name="FormSearch" method="post" action="">
<table width="100%" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr align="center">
      <td colspan="2"><strong> 出库明细</strong></td>
    </tr>
    <tr>
    <td>
	{if $arr_condition != ''}
     日期：
			<input name="dateFrom" type="text" id="dateFrom" value="{$arr_condition.dateFrom}" size="10" onclick="calendar()">
		到
			<input name="dateTo" type="text" id="dateTo" value="{$arr_condition.dateTo}" size="10" onclick="calendar()"/>
            <input type="submit" name="Submit" value="搜索"/>
    {/if}
    </td>
    </tr>
    <tr>
      <td align="center">
            <table width="100%" class="tableHaveBorder table100" id="tbl" border="1">
              <tr class="th">
                <td align="center">选择</td>
                <td align="center">系统编号</td>
                <td align="center">单号</td>
                <td align="center">日期</td>
                <td align="center">客户</td>
                <td align="center">金额</td>
                <td align="center">备注</td>
              </tr>
              {foreach from=$arr_field_value item=item}
              <tr>
                <td align="center">
                <input type="checkbox"  id="id[]" name="id[]" style="padding:0; margin:0px;" value="{$item.productId}" onClick="this.disabled=true;"/>
                </td>
                <td align="center" style="font-size:13px;" id="chukuId">
                {$item.chukuId}
                </td>
                <td align="center" style="font-size:13px;" id="chukuNum">
                {$item.chukuNum}
                </td>
                <td align="center" style="font-size:13px;" id="chukuDate">
                {$item.chukuDate}
                </td>
                <td align="center" style="font-size:13px;" id="compName">
                {$item.compName}
                </td>
                <td align="center" style="font-size:13px;" id="money">{$item.money} </td>
                <td align="center" style="font-size:13px;" id="memo">
                {$item.memo}
                </td>
              </tr>
              {/foreach}
            </table>
			</td>
    </tr>
    <tr>
      <td align="center">
      <input type="button" name="Submit2" value="确定" onClick="ret()">
      <input type="button" name="Submit2" value="取消" onClick="window.close()">
      </td>
    </tr>
  </table>
   </form>
</body>
</html>
