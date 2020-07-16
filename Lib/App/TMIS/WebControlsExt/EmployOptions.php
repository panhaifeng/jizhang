<?php
function _ctlEmployoptions($name,$params) {
	$m = & FLEA::getSingleton('Model_Jichu_Employ');
	$sql = "select * from jichu_Employ where isCaiyang=1 ";
	//dump($sql);
	$rowset = $m->findBySql($sql);
	//dump($rowset);exit;
	$ret = "<option value='' style='color:#ccc'>选择采样人</option>";
	foreach($rowset as & $v) {
		$ret .= "<option value='{$v['employCode']}'";
		if($params['selected']==$v['employCode']) $ret .= " selected";
		$ret .= ">{$v['employName']}</option><br>";
	}
	return $ret;
}
?>
