<?php
load_class('TMIS_TableDataGateway');
class Model_Shengchan_Huitian extends TMIS_TableDataGateway {
	var $tableName = 'shengchan_huitian';
	var $primaryKey = 'id';
	var $belongsTo = array (
		array(
			'tableClass' => 'Model_Shengchan_Plan',
			'foreignKey' => 'planId',
			'mappingName' => 'Plan',
		)
	);
	
	var $hasMany = array (
		array(
			'tableClass' => 'Model_Shengchan_Huitian2Bu',
			'foreignKey' => 'huitianId',
			'mappingName' => 'Huitians',
		),
	);
}
?>