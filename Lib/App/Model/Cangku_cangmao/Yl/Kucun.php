<?php
FLEA::loadClass('TMIS_TableDataGateway');
class Model_Cangku_Yl_Kucun extends TMIS_TableDataGateway {
	var $tableName = "cangku_yl_init";
	var $primaryKey = "id";
	var $primaryName = "ylId";
	var $belongsTo = array (
		array(
			'tableClass' => 'Model_Jichu_Yl',
			'foreignKey' => 'ylId',
			'mappingName' => 'yls'
		)
	);
}
?>