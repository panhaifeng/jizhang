<?php
/**
 *޸by jeff zeng 2007-4-5
 *{webcontrol type='Clientoptions' selected=''}
 *以联动select方式显示供应商控件
 *因为建立的客户不多，所以改为普通的select控件
 */
function _ctlColoroptions($name,$params) {
	$m = & FLEA::getSingleton('Model_Jichu_Color');
	$sql = "select * from jichu_color where 1";
	$rowset = $m->findBySql($sql);
	$ret=$m->options($rowset,$params);
	return $ret;
}
?>