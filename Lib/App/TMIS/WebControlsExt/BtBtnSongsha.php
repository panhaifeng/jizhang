<?php
/*
*2015-5-12 by jiang : select需要被bootstrap控件使用，所以这里改动为select1
*/
function _ctlBtBtnSongsha($name,$params){	
	$itemName 	= $params['itemName'];
    $value = $params['value']?$params['value']:'';    
	$html = "<a name='btnSongsha' id='btnSongsha' class='btn btn-sm btn-primary' href='javascript:;'>设置</a>";
	$html .="<input type='hidden' name='{$itemName}' value='{$value}' class='hideSongsha'>";
	return $html;
}
?>