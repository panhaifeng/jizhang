<?php
load_class('TMIS_TableDataGateway');
class Model_Caiwu_Yf_Fukuan extends TMIS_TableDataGateway {
	var $tableName = 'caiwu_yf_fukuan';
	var $primaryKey = 'id';

	var $belongsTo = array (
		array(
			'tableClass' => 'Model_Jichu_Supplier',
			'foreignKey' => 'supplierId',
			'mappingName' => 'Supplier',
		)
	);

	//获取付款方式
	function typeOptions(){
		$sql="select distinct fkType from caiwu_yf_fukuan where 1 order by id";
		$temp=$this->findBySql($sql);
		$arr=array();
		foreach($temp as & $v){
			$arr[]=array('text'=>$v['fkType'],'value'=>$v['fkType']);
		}
		return $arr;
	}
	
}
?>