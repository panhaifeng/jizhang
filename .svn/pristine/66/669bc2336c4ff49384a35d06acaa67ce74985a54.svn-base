<?php
function _ctlKuweiSel($name, $params)	{
	$m = & FLEA::getSingleton('Model_Jichu_Kuwei');
	$sql = "select * from jichu_kuwei where 1";

	$emptyText = $params['emptyText']==''?'选择库位':$params['emptyText'];
	//dump($sql);
	$rowset = $m->findBySql($sql);
	//dump($rowset);exit;
	$ret = "<option value='' style='color:#ccc'>{$emptyText}</option>";
	foreach($rowset as & $v) {
		$ret .= "<option value='{$v['kuweiName']}'";
		if($params['selected']==$v['kuweiName']) $ret .= " selected";
		$ret .= ">{$v['kuweiName']}</option>";
	}
	return $ret;
}
?>