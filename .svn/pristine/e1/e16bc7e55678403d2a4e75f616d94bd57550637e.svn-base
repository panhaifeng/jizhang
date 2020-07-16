<?php
/*
*2014-3-26 by jeff : select需要被bootstrap控件使用，所以这里改动为select1
*/
function _ctlBtTextArea($name,$params){	
	$itemName 	= $params['itemName'];
	$readonly = $params['readonly']?"readonly":'';
	$disabled = $params['disabled']?"disabled":'';
    $value = $params['value']?$params['value']:'';
    // dump($params);
    $html = '<textarea class="form-control" rows="4"  name="'.$itemName.'" id="'.$itemName.'"'.$disabled.$readonly.'>'.$value.'</textarea>';
	// $html = "<input type='text' class='form-control' name='{$itemName}' id='{$itemName}' value='{$value}' {$disabled} {$readonly} />";	
	return $html;	
}
?>