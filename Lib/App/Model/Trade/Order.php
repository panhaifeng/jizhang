<?php
load_class('TMIS_TableDataGateway');
class Model_Trade_Order extends TMIS_TableDataGateway {
	var $tableName = 'trade_order';
	var $primaryKey = 'id';
	var $primaryName = 'orderCode';


	var $belongsTo = array (
		array(
			'tableClass' => 'Model_Jichu_Employ',
			'foreignKey' => 'traderId',
			'mappingName' => 'Trader',
		),
		array(
			'tableClass' => 'Model_Jichu_Client',
			'foreignKey' => 'clientId',
			'mappingName' => 'Client',
		),
		// array(
		// 	'tableClass' => 'Model_Acm_User',
		// 	'foreignKey' => 'userId',
		// 	'mappingName' => 'User',
		// )
	);

	var $hasMany = array (
		array(
			'tableClass' => 'Model_Trade_Order2Product',
			'foreignKey' => 'orderId',
			'mappingName' => 'Products',
		)
	);

	/**
	 * @desc ：合同新编号 前缀:大货JM, 本厂开发KF, 客户打样DY, 来料加工JG  范例：KF-160608001
	 * Time：2016/06/08 11:14:05
	 * @author Wuyou
	*/
	function getNewOrderCode($orderType,$head){
		$condition[] = array('orderType', "{$orderType}", '=');
		$condition[] = array('orderCode', "{$head}%", 'like');
		$arr=$this->find($condition,'orderCode desc','orderCode');
		$max = substr($arr['orderCode'],3);

		$frist = date("ymd")."001";
		if ($frist>$max) {
			return $head.'-'.$frist;
		}
		$next = substr($max,-3)+1001;
		return $head.'-'.date("ymd").substr($next,1);

	}


	//得到合同的收款金额
	function getMoneyAccept($orderId) {
		$sql = "select sum(x.money) as money
			from caiwu_ar_guozhang x
			inner join cangku_chuku2product y on x.id=y.guozhangId
			inner join trade_order2product z on y.order2productId=z.id
			where x.incomeId>0 and z.orderId='$orderId'";
		$re = $this->findBySql($sql);
		return $re[0]['money'];
	}
}


?>