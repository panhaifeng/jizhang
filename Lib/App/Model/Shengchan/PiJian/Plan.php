<?php
load_class('TMIS_TableDataGateway');
class Model_Shengchan_PiJian_Plan extends TMIS_TableDataGateway {
	var $tableName = 'shengchan_planpj';
	var $primaryKey = 'id';
	var $primaryName = 'planCode';


	var $belongsTo = array (
		array(
			'tableClass' => 'Model_Trade_Order2Product',
			'foreignKey' => 'ord2proId',
			'mappingName' => 'ord2pro',
		)
	);
	
	var $hasMany = array (
		array(
			'tableClass' => 'Model_Shengchan_PiJian_Plan2Son',
			'foreignKey' => 'planId',
			'mappingName' => 'Son',
		),

	);
}
?>