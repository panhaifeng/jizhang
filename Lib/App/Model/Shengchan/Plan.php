<?php
load_class('TMIS_TableDataGateway');
class Model_Shengchan_Plan extends TMIS_TableDataGateway {
	var $tableName = 'shengchan_plan';
	var $primaryKey = 'id';
	var $primaryName = 'planCode';


	var $belongsTo = array (
		array(
			'tableClass' => 'Model_Trade_Order2Product',
			'foreignKey' => 'order2proId',
			'mappingName' => 'order2pro',
		)
	);
	
	var $hasMany = array (
		array(
			'tableClass' => 'Model_Shengchan_Plan2Touliao',
			'foreignKey' => 'planId',
			'mappingName' => 'Touliaos',
		),
		array(
				'tableClass' => 'Model_Shengchan_Plan2Zhizao',
				'foreignKey' => 'planId',
				'mappingName' => 'Zhizaos',
		),
		array(
				'tableClass' => 'Model_Shengchan_Plan2Houzheng',
				'foreignKey' => 'planId',
				'mappingName' => 'houhengs',
		),
	);
}
?>