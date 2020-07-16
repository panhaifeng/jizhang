<?php
function _ctlJiagonghuoptions($name,$params) {
	$m = & FLEA::getSingleton('Model_Jichu_Jiagonghu');
	$sql = "select * from jichu_jiagonghu where 1 order by paixu";

	$emptyText = $params['emptyText']==''?'选择加工户':$params['emptyText'];
	//dump($sql);
	$rowset = $m->findBySql($sql);
	//dump($rowset);exit;
	$ret = "<option value='' style='color:#ccc'>{$emptyText}</option>";
	foreach($rowset as & $v) {
		$ret .= "<option value='{$v['id']}'";
		if($params['selected']==$v['id']) $ret .= " selected";
		$ret .= ">{$v['compName']}</option>";
	}
	return $ret;
}
?>