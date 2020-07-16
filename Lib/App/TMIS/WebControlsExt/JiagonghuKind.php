<?php
function _ctlJiagonghuKind($name,$params) {
	$m = & FLEA::getSingleton('Model_Jichu_Employ');
	$sql = "select * from jichu_jiagonghu where 1 group by kind";
	//dump($sql);
	$rowset = $m->findBySql($sql);
	//dump($rowset);exit;
	$ret = "<option value='' style='color:#ccc'>选择类别</option>";
	foreach($rowset as & $v) {
		$ret .= "<option value='{$v['kind']}'";
		if($params['selected']==$v['kind']) $ret .= " selected";
		$ret .= ">{$v['kind']}</option>";
	}
	return $ret;
}
?>
