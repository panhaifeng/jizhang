<?php
load_class('TMIS_TableDataGateway');
class Model_Cangku_Chuku extends TMIS_TableDataGateway {
	var $tableName = 'cangku_chuku';
	var $primaryKey = 'id';
	var $primaryName = 'chukuNum';
	var $_head="CC";
	var $belongsTo = array (
		array(
			'tableClass' => 'Model_Jichu_Client',
			'foreignKey'=>'clientId',
			'mappingName' => 'Client'
		),
		array(
			'tableClass' => 'Model_Trade_Order',
			'foreignKey' => 'orderId',
			'mappingName' => 'Order'
		),
		array(
			'tableClass' => 'Model_Acm_User',
			'foreignKey' => 'operatorId',
			'mappingName' => 'Operator'
		)
	);
	var $hasMany = array(
		array(
			'tableClass' => 'Model_Cangku_ChukuProduct',
			'foreignKey' => 'chukuId',
			'mappingName' => 'Pro'
		)
	);


	function getNewChukuNum() {
		$head = $this->_head;
		$arr=$this->find(array(
			array('chuKuNum',$head.date("ym")."___",'like')
		),'chuKuNum desc','chuKuNum');
		$max = $arr[chuKuNum];
		$temp = $head.date("ym")."001";
		if ($temp>$max) return $temp;
		$a = substr($max,-3)+1001;
		return substr($max,0,-3).substr($a,1);
	}

}
?>