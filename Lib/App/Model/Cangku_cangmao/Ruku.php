<?php
load_class('TMIS_TableDataGateway');
class Model_Cangku_Ruku extends TMIS_TableDataGateway {
	var $tableName = 'cangku_ruku';
	var $primaryKey = 'id';
	var $primaryName = 'rukuNum';
	var $_head="CR";
	

	var $hasMany = array(
		array(
			'tableClass' => 'Model_Cangku_RukuProduct',
			'foreignKey' => 'rukuId',
			'mappingName' => 'Pro'
		)
	);


	//取得新的出库编号,在新增出库时用
	function getNewRukuNum() {
		$head = $this->_head;
		$arr=$this->find(array(
			array('rukuNum',$head.date("ym")."___",'like')
		),'rukuNum desc','rukuNum');
		$max = $arr[rukuNum];
		$temp = $head.date("ym")."001";
		if ($temp>$max) return $temp;
		$a = substr($max,-3)+1001;
		return substr($max,0,-3).substr($a,1);
	}

}


?>