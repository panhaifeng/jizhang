<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$title}</title>
</head>
<link rel="stylesheet" href="Resource/bootstrap/bootstrap3.0.3/css/bootstrap.css">
</head>
<body>
<form name="form1" id='form1' method="post" action="{url controller=Trade_Chengben action='saveShenhe'}">
<div id='divHtml'>
  <table id="table_main" class="table table-hover" >
      <tr align="center">
          <td colspan="5" height=40 ><b>色纱品名</b></td>
    	</tr>
	 	<tr>
      		<th>色纱品名</th>
      		<th>缸号(批号)</th>
            <th>比率%</th>
      		<th>送纱数量(KG)</th>
            <th>送纱箱数</th>
    	</tr>
        <tr class='trRow'>
          <td><input type="text" name="proCode[]" value="" class="form-control"/>
            <input type="hidden" name="productId[]" value=""/>
            <input type="hidden" name="id[]" value="" /></td>
          <td><input type="text" name="ganghao[]" value="" class="form-control"/></td>
          <td><input type="text" name="bilv[]" value="" class="form-control"/></td>
          <td><input type="text" name="cntKg[]" value="" class="form-control"/></td>
          <td><input type="text" name="cntBox[]" value="" class="form-control"/></td>
        </tr>
     </table>
<div align="center" id='prn'>
<input id=prnbutt type='button' value="保存" class="btn btn-info">
<input id='cannel' onclick='window.close();' type='button' value="取消" class="btn btn-warning">
</div>
</div>
</form>
{webcontrol type='LoadJsCss' src="Resource/bootstrap/bootstrap3.0.3/js/jquery.min.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.json.js"}
<script language=javascript>
{literal}
 var _cache=eval(window.dialogArguments.data);//取得window.showModalDialog中的传入参数
$(function(){
    if(_cache){
        //复制行
        var srcTr = $('#table_main').find('.trRow').eq(0);
        srcTr.remove();
        for(var i in _cache){
			     var trCopy = srcTr.clone(true);
            $('[name="id[]"]',trCopy).val(_cache[i].id);
            $('[name="productId[]"]',trCopy).val(_cache[i].productId);
            $('[name="proCode[]"]',trCopy).val(_cache[i].proCode);
            $('[name="ganghao[]"]',trCopy).val(_cache[i].ganghao);
            $('[name="bilv[]"]',trCopy).val(_cache[i].bilv);
            $('[name="cntKg[]"]',trCopy).val(_cache[i].cntKg);
            $('[name="cntBox[]"]',trCopy).val(_cache[i].cntBox);
            $('#table_main').append(trCopy);
    		}
    }else{
        //取得父页面的数据
        var parentDoc = window.opener.document;
        var num=$(parentDoc).find('[name="productId[]"]').length;
        //复制行
        var srcTr = $('#table_main').find('.trRow').eq(0);
        srcTr.remove();
        //将父页面的数据填写到子页面中
        for(var i=0;i<num;i++){
            var productId=$(parentDoc).find('[name="productId[]"]').eq(i).val();
            if(!productId) continue;
            var trCopy = srcTr.clone(true);
            $('[name="productId[]"]',trCopy).val(productId);
            $('[name="proCode[]"]',trCopy).val($(parentDoc).find('[name="productId[]"]').eq(i).siblings('input').val());
            $('[name="ganghao[]"]',trCopy).val($(parentDoc).find('[name="ganghao[]"]').eq(i).val());
            $('[name="bilv[]"]',trCopy).val($(parentDoc).find('[name="bilv[]"]').eq(i).val());
            $('[name="cntKg[]"]',trCopy).val($(parentDoc).find('[name="cnt[]"]').eq(i).val());
            $('#table_main').append(trCopy);
        }
    }


    //点确定将数据返回到主页面
    $('#prnbutt').click(function(){
 	   var len=$('[name="proCode[]"]').length;
 	   var arr=[];
 	   for(var i=0;i<len;i++) {
           arr.push({
            'id':$('[name="id[]"]').eq(i).val(),
            'proCode':$('[name="proCode[]"]').eq(i).val(),
            'productId':$('[name="productId[]"]').eq(i).val(),
            'ganghao':$('[name="ganghao[]"]').eq(i).val(),
            'bilv':$('[name="bilv[]"]').eq(i).val(),
            'cntKg':$('[name="cntKg[]"]').eq(i).val(),
            'cntBox':$('[name="cntBox[]"]').eq(i).val()
           });
	   }
        window.returnValue = $.toJSON(arr);
        window.close();
	});
});
</script>
{/literal}
</body>
</html>
