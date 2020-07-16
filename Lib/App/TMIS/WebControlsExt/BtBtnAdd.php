<?php
/*
*2014-3-26 by jeff : select需要被bootstrap控件使用，所以这里改动为select1
*/
function _ctlBtBtnAdd($name,$params){	
	$itemName 	= $params['itemName'];	
    $value = $params['value']?$params['value']:'';    
	$html = '
<button type="button" class="btn btn-default btn-sm" id="btnAdd">
  <span class="glyphicon glyphicon-plus-sign"></span> +5行
</button>';
	return $html;	
}
?>