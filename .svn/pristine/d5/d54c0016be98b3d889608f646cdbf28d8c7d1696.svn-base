<?php
function _ctlSupplieroptions($name,$params) {
	$m = & FLEA::getSingleton('Model_Jichu_Supplier');
	$sql = "select * from jichu_supplier where 1";

	$emptyText = $params['emptyText']==''?'选择供应商':$params['emptyText'];
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