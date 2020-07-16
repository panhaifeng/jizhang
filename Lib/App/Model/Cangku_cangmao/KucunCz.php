<?php
FLEA::loadClass('TMIS_TableDataGateway');
class Model_Cangku_KucunCz extends TMIS_TableDataGateway {
	var $tableName = "cangku_kucun";
	var $primaryKey = "id";
	
	var $belongsTo = array (
		array(
			'tableClass' => 'Model_Cangku_RukuProduct',
			'foreignKey' => 'ruku2proId',
			'mappingName' => 'Pro'
		)
	);
}
?>