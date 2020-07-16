<!-- 编辑布档案主信息栏添加上传工艺图片功能，2017-09-20，by yinqian -->
<div class="col-xs-4">
    <div class="form-group">
        <label for="{$item.name|default:$key}" class="col-sm-3 control-label lableMain lableMain">{$item.title}:</label>
        <div class="col-sm-9">
        	<input type="file" name="imageFileGY" id="imageFileGY" onChange="setBoxGY()">	 	  
			  {if $item.value!=''}删除原来图片     
			  <input type="checkbox" name="isDelImage" value="yes" id="isDelImage" title="只选择该复选框则只删除原来图片，可以不选择图片">{/if}
        </div>
    </div>
</div>
