<?php
/*
*2014-3-26 by jeff : select需要被bootstrap控件使用，所以这里改动为select1
*/
function _ctlBtBtnCopy($name,$params){
	$itemName 	= $params['itemName'];
    $value = $params['value']?$params['value']:'';

    $html = "<div class='input-group input-group-sm input-group-btn' style='width:100px;'>";
	$html .= "<a name='btnRemove' id='btnRemove' class='btn btn-sm btn-default' href='javascript:;' ><span class='glyphicon glyphicon-trash'></span> 删除</a>";
	$html .= "<a name='btnCopy' id='btnCopy' class='btn btn-sm btn-default' href='javascript:;' ><span class='glyphicon glyphicon-file'></span> 复制</a>";
	$html .= "</div>";

	return $html;
}
?>