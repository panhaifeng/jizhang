<?php
/*
*2014-3-26 by jeff : select需要被bootstrap控件使用，所以这里改动为select1
*/
function _ctlBtHidden($name,$params){	
	// dump($params);
	$itemName 	= $params['itemName'];
	
    $value = $params['value']?$params['value']:'';
	$html = "<input type='hidden' name='{$itemName}' id='{$itemName}' value='{$value}' />";
	return $html;	
}
?>