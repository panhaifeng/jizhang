<?php
load_class('TMIS_TableDataGateway');
class Model_Yuanliao_Cgrk extends TMIS_TableDataGateway {
    var $tableName = 'yuanliao_cgrk';
	var $primaryKey = 'id';
	var $_head="CR";


	var $hasMany = array (
		array(
			'tableClass' => 'Model_Yuanliao_Cgrk2product',
			'foreignKey' => 'rukuId',
			'mappingName' => 'Products',
		)
	);
	
    //取得新的出库编号,在新增出库时用
	function getNewRukuNum() {
		$head = $this->_head;
		$arr=$this->find(array(
			array('rukuCode',$head.date("ym")."___",'like')
		),'rukuCode desc','rukuCode');
		$max = $arr[rukuCode];
		$temp = $head.date("ym")."001";
		if ($temp>$max) return $temp;
		$a = substr($max,-3)+1001;
		return substr($max,0,-3).substr($a,1);
	}

}
?>



