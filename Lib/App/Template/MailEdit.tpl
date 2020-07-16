<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$title}</title>
{webcontrol type='LoadJsCss' src="Resource/Css/EditCommon.css"}
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/Calendar/WdatePicker.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.validate.js"}
<script charset="utf-8" src="Resource/Script/kindeditor/kindeditor.js"></script>
<script charset="utf-8" src="Resource/Script/kindeditor/lang/zh_CN.js"></script>
<script charset="utf-8" src="Resource/Script/kindeditor/plugins/code/prettify.js"></script>
{webcontrol type='LoadJsCss' src="Resource/Css/validate.css"}
{literal}
<script language="javascript">
$(function(){
	$.validator.addMethod("oneUser", function(value, element) {		
		var o = $('[name="userId[]"]');
		if(o.length>0)return true;
		else{
			alert('至少填写一个收件人');
			return false;
		}
	}, "");
	$('#form1').validate({
		rules:{		
			'title':'required',
			'buildDate':'required',
			'content':'required',
			'accepter':'required',
			'id':'oneUser'
		}
		,submitHandler : function(form){
			$('#Submit').attr('disabled',true);
			form.submit();
		}
	});
	KindEditor.ready(function(K) {
			var editor1 = K.create('textarea[name="content"]', {
				cssPath : 'Resource/Script/kindeditor/plugins/code/prettify.css',
				uploadJson : 'Resource/Script/kindeditor/php/upload_json.php',
				fileManagerJson : 'Resource/Script/kindeditor/php/file_manager_json.php',
				allowFileManager : true,
				afterCreate : function() {
					var self = this;
					this.focus();
				}
			});
			prettyPrint();
		});
	
	$('.colDiv').live('mouseover',function(){
		$(this).css({color:'red','background-color':'#FC9'});
		$(this).attr('title','点击删除该用户名');
	});
	$('.colDiv').live('mouseout',function(){
		$(this).css({color:'blue','background-color':''});
	});
	$('.colDiv').live('click',function(){
		var pos=$('.colDiv').index(this);
		$('.parDiv').eq(pos).remove();
		$('[name="userId[]"]').eq(pos).remove();
	});
});

function addText(sender) {
    var idx = sender.selectedIndex;
    if(idx < 0) return;
    var opt = sender.options[idx];
	var div="<div class='parDiv'><div class='userDiv'>"+opt.text+"</div><div class='colDiv'></div></div>";
	var html=$('#accepter').html();
	html+=div;
	$('#accepter').html(html);
   // var ipt = sender.form.accepter;
    //ipt.value += (ipt.value ? "，" : "") + opt.text;
	
	//增加隐藏域
	var html = '<input type="hidden" name="userId[]" id="userId[]" value="'+opt.value+'">';
	document.getElementById('spanHidden').innerHTML += html;
	
}
</script>
<style type="text/css">
.mainTableStyle100 .title{width:100px;}
.mainTableStyle100 {width:400px;}
#accepter{ border:1 solid #7F9DB9; width:700px; height:22px; background-color:#FFF; padding-top:4px;}
.parDiv{ height:18px; border:0px solid; white-space:nowrap; float:left; padding-right:6px;}
.userDiv{ height:18px; border:0px solid; float:left; font-size:12px;}
.colDiv{ height:15px;width:15px;border:0px;float:left;cursor:pointer;background-image:url('Resource/Image/close.gif')}
</style>
{/literal}
</head>
<body>
<form id='form1' name="form1" method="post" action="{url controller=$smarty.get.controller action='save'}"  enctype="multipart/form-data">
    <table width="100%" border="0" cellpadding="1" cellspacing="1" bgcolor="#CCCCCC">
	<tr>
      <td height="30" colspan="3" align="center" bgcolor="#efefef"><strong>邮件发送</strong></td>
    </tr>
	<tr>
	  <td width="80" rowspan="3" align="right" valign="top" bgcolor="#efefef"><div align="left">选择收件人：
<!-- <select name="accepter1" size="10" id="accepter1" multiple ondblclick='addText(this)'>
	      <?php
		  listUser($accepter1);
		  ?>
	      </select>-->
          <select name="accepter1" size="10" id="accepter1" multiple ondblclick='addText(this)'>
     		 {webcontrol type='Tmisoptions' model='acm_user' }
    	</select>
	    </div></td>
	  <td height="30" align="right" bgcolor="#efefef">收件人:</td>
	  <td height="30" bgcolor="#efefef">
      <div id='accepter'  name="accepter">{$aRow.accepter}</div>
      <!--input name="accepter" readonly type="hidden" id="accepter" size="80" value="{$aRow.accepter}"-->
	    <span id='spanHidden'>{$hidden}</span></td>
	  </tr>
	  <tr    bgcolor="#efefef">
	    <td width="80" height="30" align="right">主题:</td>
        <td height="30"><input name="title" type="text" style="width:700px;" check="^(\s|\S)+$" warning="请输入主题！" value="{$aRow.title}"></td>
      </tr>
	  <tr bgcolor="#efefef">
	    <td height="300" align="right" valign="top">内容:</td>
        <td><textarea name="content" id="content" style="width:700px;height:400%;">{$aRow.content}</textarea></td>
      </tr> 
	  <tr align="center" bgcolor="#FFFFFF"> 
        <td colspan="3"> 
          <input type="submit" name="Submit" value="立即发送">
          <input type="reset" name="reset" value="重填">
          <input type="hidden" id='id' name="id"></td>
      </tr>
    </table>

  </form>
</body>
</html>
