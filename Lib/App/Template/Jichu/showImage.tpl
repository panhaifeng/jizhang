
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$title}</title>
   
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.jqzoom.js"}

</head>
{literal}
    <script type="text/javascript">
      $(document).ready(function(){
        var bimg = $('.tupian').attr('jqimg');
        if(bimg==''){
           $('.tupian').click(function(e){
              $('.tupian').addClass('show_image');
           })         
        }else{
            $(".jqzoom").jqueryzoom({
                  xzoom: 250, //zooming div default width(default width value is 200)
                  yzoom: 250, //zooming div default width(default height value is 200)
                  offset: 10, //zooming div default offset(default offset value is 10)
                  position: "right" //zooming div position(default position value is "right")
            });
        }
      
      });

    </script>
    <link rel="stylesheet" href="Resource/Css/stylezoom.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="Resource/Css/jqzoom.css" type="text/css" media="screen" />
    <style type="text/css">
       .show_image{
           -webkit-transition: all 0.6s;
           -moz-transition: all 0.6s;
           -ms-transition: all 0.6s;
           -o-transition: all 0.6s;
           transition: all 0.6s;
           -webkit-transform: scale(1.7);
           -moz-transform: scale(1.7);
           -ms-transform: scale(1.7);
           -o-transform: scale(1.7);
           transform: scale(1.7);
       }
    </style>
{/literal}
<body>
   <table  border="0" cellspacing="0" cellpadding="0"  style="margin-top: 60px;margin-left: 155px">
  <tr>
    <td align="left">{$row.barCode}</td>
  </tr>
  
  <tr>
      <td align="left" valign="top">
       {if $row.img!=''}
             <div class="jqzoom">
            <img src="{$row.img}" alt="scarpa" jqimg="{$row.bimg}" class="tupian" />
          </div>
          {else}
              <br>
          <table width="200" height='160' border="1" cellspacing="0" cellpadding="0">
                  <tr>
                      <td align="center" valign="middle" style="color:#CCC; border:0.5px;">暂无图片</td>
                  </tr>
              </table>
          {/if}
          
      </td>
  </tr>
  
</table>

</body>
</html>