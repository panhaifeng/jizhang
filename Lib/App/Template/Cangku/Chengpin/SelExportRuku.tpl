<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$title}</title>
<script src="Resource/Script/jquery.js"></script>
<script type="text/javascript">
{literal}
  function checkForm(){
      var list = document.getElementsByTagName("input");
       for(var i =0;i<list.length;i++)
      {
         if(list[i].type == "checkbox" && list[i].checked)
          break;
      }
       if(i==list.length)
      {
       alert("没有选中的");
        return false;
      }
      return true; 
  }

  // function checkForm(){
  //   var pd = true;
  //     $("[type='checkbox']").each(function() {
  //         if (!this.checked) { 
  //           pd = false; 
  //         }
  //     });
  //     if(!pd){
  //       alert("请选择一种");
  //       return false;
  //     }
  //    return true;
  // }
</script>
{/literal}
</head>

<body>

<form action="{url controller=$smarty.get.controller action='ExportRuku'}" method="POST">
  <div style="height:500px; width:500px; margin:0 auto;">
     <div>选择导出的内容(请选择一种) <br/>
       <!-- <input type="checkbox" name="gongjin" value="checkbox" /> 公斤+ -->
       <input type="checkbox" name="mi" value="checkbox" /> 公斤+米  <br/>
       <input type="checkbox" name="mashu" value="checkbox" /> 公斤+码数 <br/>
       <input type="checkbox" name="bang" value="checkbox" /> 公斤+米+码+磅
    </div>
     <div >
       <input type='hidden' name='id' value='{$id}' />
       <input type='submit' name='btnsubmit' value='提交' onclick="return checkForm();"/>
       <input type='button' name='btnreturn' value='返回' onclick="javascript:window.location.href='{url controller=Cangku_Chengpin_Ruku action='right'}'" />
     </div>
  </div>
 
</form>
</body>
</html>
