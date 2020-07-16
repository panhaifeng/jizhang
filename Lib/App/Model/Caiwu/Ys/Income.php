<?php
load_class('TMIS_TableDataGateway');
class Model_Caiwu_Ys_InCome extends TMIS_TableDataGateway {
	var $tableName = 'caiwu_ar_income';
	var $primaryKey = 'id';

	var $belongsTo = array (
		array(
			'tableClass' => 'Model_Jichu_Client',
			'foreignKey' => 'clientId',
			'mappingName' => 'Client',
		),
		array(
			'tableClass' => 'Model_Caiwu_Bank',
			'foreignKey' => 'bankId',
			'mappingName' => 'Bank',
		)
	);

	//获取付款方式
	function typeOptions(){
		$sql="select distinct type from caiwu_ar_income where 1 order by id";
		$temp=$this->findBySql($sql);
		$arr=array();
		foreach($temp as & $v){
			$arr[]=array('text'=>$v['type'],'value'=>$v['type']);
		}
		return $arr;
	}
}
?>