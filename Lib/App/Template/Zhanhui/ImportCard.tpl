<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>图片文件上传</title>
<script language="javascript" src="Resource/Script/jquery.js"></script>
<script src="Resource/Script/uploadify/jquery.uploadify.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="Resource/Script/uploadify/uploadify.css">

<script type="text/javascript">
	//远程服务器的上传路径
	var _remotePath = '?controller=zhanhui&action=SaveImportCard&pre={$pre}';
	{literal}
	$(function() {
		$('#file_upload').uploadify({
			'queueID'  : 'some_file_queue',
			'fileObjName':'Filedata',//使用$_POST['Filedata']取得上传的文件			
			'swf'      : 'Resource/Script/uploadify/uploadify.swf'
			,'uploader' : _remotePath
			,'fileTypeExts'		 : '*.jpg'
			,'buttonText':'选择文件'				
			,'onQueueComplete' : function(queueData) {
				//alert(queueData.uploadsSuccessful + ' files were successfully uploaded.');
				//alert(queueData.uploadsErrored + ' files were failed.');
				$('#divMsg').text(queueData.uploadsSuccessful+' 个文件成功上传!请不要重复上传');
				$('#file_upload').uploadify('disable', true);
			}
			,'onUploadSuccess' : function(file, data, response) {//调试时使用
				//$('#content').html('The file ' + file.name + ' was successfully uploaded with a response of ' + response + ':' + data);
			}	
			//'debug':true
			/*
			,'onUploadSuccess' : function(file, data, response) {//上传成功后的回调文件
				//alert('The file ' + file.name + ' was successfully uploaded with a response of ' + response + ':' + data);
			}
			'formData'     : {//上传时附加的$_POST变量
				'timestamp' : '<?php echo $timestamp;?>',
				'token'     : '<?php echo md5('unique_salt' . $timestamp);?>'
			},
			'onQueueComplete' : function(queueData) {
				alert(queueData.uploadsSuccessful + ' files were successfully uploaded.');
				alert(queueData.uploadsErrored + ' files were failed.');
			}*/
		});
	});
</script>
<style type="text/css">
body {
	font: 13px Arial, Helvetica, Sans-serif;
}
#some_file_queue,#content{
	height:500px;
	background-color: #FFF;	
    border-radius: 3px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.25);
    margin-bottom: 10px;
    overflow: auto;
    padding: 5px 10px;
    
}
#some_file_queue {
    float:left;
	border:1px dotted #ccc;
	width: 300px;
}
#content {
    float:left;
	margin-left:10px;
	border:1px solid #bbb;
	width: 500px;
	line-height:20px;
	color:red;
}
#bottom {clear:both; white-space:nowrap;}
#bottom div{ float:left;}
#divMsg  { margin-left:20px; color:blue;}
</style>
{/literal}
</head>

<body>
	<h1>展会图片上传</h1>
<div id='main'>
		<div id="some_file_queue"></div>
        <div id='content'>
操作说明：<br />
0),上传前请进入系统设置->系统设置,修改服务器地址和上传前缀以及展会信息。<br />
1),点击"选择文件"按钮,选择名片扫描文件所在目录，一般在展会现场管理程序目录的"pic/big"目录下.<br />
2),在弹出的文件选择框中,按住"ctrl+A"将目录下的文件全部选中,点击"打开"按钮<br />
3),确认后，图片文件自动开始上传。<br>
上传路径为ERP项目目录/upload/zhanhui/{$pre}<br />
4),上传完毕后，不可重复上传。如的确需要重复上传，请联系开发人员。
        </div>
</div>
	
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    	  <tr>
    	    <td style="width:130px;"><input id="file_upload" name="file_upload" type="file" multiple="true"></td>
    	    <td><div id='divMsg'></div></td>
  	    </tr>
  	  </table>
	   	
</div>
</body>
</html>