<?php
/**
 *޸by jeff zeng 2007-4-5
 *{webcontrol type='Clientoptions' selected=''}
 *以联动select方式显示供应商控件
 *因为建立的客户不多，所以改为普通的select控件
 */
function _ctlClientoptions($name,$params) {
	$m = & FLEA::getSingleton('Model_Jichu_Client');
	$sql = "select * from jichu_client where 1";

	//查找关联到的客户的信息
	if($params['isLimit']=='1'){
		$mUser = & FLEA::getSingleton('Model_Acm_User');
		$canSeeAllOrder = $mUser->canSeeAllOrder($_SESSION['USERID']);
		if(!$canSeeAllOrder) {//如果不能看所有订单，得到当前用户的客户业务员
			 $traderId = $mUser->getTraderIdByUser($_SESSION['USERID']);
			 if($traderId)$sql .= " and traderId in($traderId)";
		}
	}
	$sql.=" order by ";
	$kg = & FLEA::getAppInf('khqcxs');
	if($kg)$sql.=" letters";
	else $sql.=" compCode";
	$rowset = $m->findBySql($sql);
	$ret=$m->options($rowset,$params);
	return $ret;
}
?>