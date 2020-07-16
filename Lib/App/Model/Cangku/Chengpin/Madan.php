<?php
FLEA::loadClass('TMIS_TableDataGateway');
class Model_Cangku_Chengpin_Madan extends TMIS_TableDataGateway {
	var $tableName = 'cangku_madan';
	var $primaryKey = 'id';
	var $belongsTo = array(
		array(
			'tableClass' => 'Model_Cangku_Chengpin_Ruku2Product',
			'foreignKey' => 'ruku2proId',
			'mappingName' => 'Ruku2pro'
		)
	);

}
?>