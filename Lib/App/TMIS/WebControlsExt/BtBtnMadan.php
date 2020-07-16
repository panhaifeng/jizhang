<?php
/*
*2014-3-26 by jeff : select需要被bootstrap控件使用，所以这里改动为select1
*/
function _ctlBtBtnMadan($name,$params){	
	$itemName 	= $params['itemName'];
    $value = $params['value']?$params['value']:'';    
	$html = "<a name='btnMadan' id='btnMadan' class='btn btn-sm btn-primary' href='javascript:;'>码单</a>";
	$html .="<input type='hidden' name='{$itemName}' value='{$value}' class='hideMadan'>";
	return $html;
}
?>