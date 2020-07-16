<?php
function _ctlGanghaooptions($name, $params)	{
	if($params['emptyText']) $ret = "<option value=''>{$params['emptyText']}</option>";

	$m = & FLEA::getSingleton("Model_Jichu_Product");
	$arr = $m->findAll();
	if($arr) foreach ($arr as $key => & $value) {
		$ret .= "<option value='{$value['proName']}'";
		if($params['selected']==$value['proName']) $ret .= " selected";
		$ret .= ">{$value['proName']}</option>";
	}
	return $ret;
}
?>