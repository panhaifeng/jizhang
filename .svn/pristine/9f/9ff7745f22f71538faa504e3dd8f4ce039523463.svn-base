<?php
load_class('TMIS_TableDataGateway');
class Model_Cangku_Yl_Ruku2Yl extends TMIS_TableDataGateway {
	var $tableName = 'cangku_yl_ruku2yl';
	var $primaryKey = 'id';
	var $belongsTo = array (
		array(
			'tableClass' => 'Model_Jichu_Yl',
			'foreignKey' => 'ylId',
			'mappingName' => 'Yl'
		),
		array(
			'tableClass' => 'Model_Cangku_Yl_Ruku',
			'foreignKey' => 'rukuId',
			'mappingName' => 'Ruku'
		)
	);
}
?>