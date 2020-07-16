<?php
FLEA::loadClass('TMIS_TableDataGateway');
class Model_Cangku_Kucun extends TMIS_TableDataGateway {
	var $tableName = "cangku_init";
	var $primaryKey = "id";
	var $primaryName = "productId";
	var $belongsTo = array (
		array(
			'tableClass' => 'Model_Jichu_Product',
			'foreignKey' => 'productId',
			'mappingName' => 'Pro'
		)
	);
}
?>