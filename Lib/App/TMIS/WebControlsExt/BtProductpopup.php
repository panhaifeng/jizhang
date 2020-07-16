<?php
/*
*2014-3-26 by jeff : 产品弹出选择框
*/
function _ctlBtProductpopup($name,$params){
	// dump($params);
	$itemName 	= $params['itemName'];
	$value = $params['value'];
	$kind = $params['kind'];

	//得到产品信息
	if($value!='') {
		$sql = "select * from jichu_product where id='{$value}'";
		$m = & FLEA::getSingleton('Model_Jichu_Product');
		$temp = $m->findBySql($sql);
		$proCode = $temp[0]['proCode'];
// 		$proName = $temp[0]['proName'];
		$guige = $temp[0]['guige'];
		$unit = $temp[0]['unit'];
	}
	        //
	$html = "
	<div style='width:110px;'>
	    <div class='input-group span12'>
	      <input name='_proKind' type='hidden' id='_proKind' value='{$kind}' />
	      <input name='{$itemName}' type='hidden' id='{$itemName}' value='{$value}' />
	      <input name='proCode' class='form-control'  type='text' id='proCode' size='3' value='{$proCode}' readonly placeholder='单击选择' />
	      <span class=\"input-group-btn\" name='btnProduct' id='btnProduct'>
	        <button class=\"btn btn-default\" type=\"button\">...</button>
	      </span>
	    </div>
	</div>
	";
	return $html;
}
?>