<?php
load_class('TMIS_TableDataGateway');
class Model_Cangku_Yl_Ruku extends TMIS_TableDataGateway {
	var $tableName = 'cangku_yl_ruku';
	var $primaryKey = 'id';
	var $primaryName = 'rukuNum';
	var $belongsTo = array (
		array(
			'tableClass' => 'Model_Jichu_Supplier',
			'foreignKey' => 'supplierId',
			'mappingName' => 'Supplier'
		)
	);
	var $hasMany = array(
		array(
			'tableClass' => 'Model_Cangku_Yl_Ruku2Yl',
			'foreignKey' => 'rukuId',
			'mappingName' => 'Yl'
		)
	);

	//取得新的出库编号,在新增出库时用
	function getNewRukuNum() {
		$head = 'YR';
		$arr=$this->find(array(
			array('rukuNum',$head.date("ym")."___",'like')
		),'rukuNum desc','rukuNum');
		$max = $arr['rukuNum'];
		$temp = $head.date("ym")."001";
		if ($temp>$max) return $temp;
		$a = substr($max,-3)+1001;
		return substr($max,0,-3).substr($a,1);
	}
}


?>