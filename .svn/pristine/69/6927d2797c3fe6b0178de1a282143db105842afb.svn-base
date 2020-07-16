<?php
load_class('TMIS_TableDataGateway');
class Model_Jichu_ProductGx extends TMIS_TableDataGateway {
	var $tableName = 'jichu_product_gongxu';
	var $primaryKey = 'id';
	
	var $belongsTo = array (
			array(
					'tableClass' => 'Model_Jichu_Product',
					'foreignKey' => 'productId',
					'mappingName' => 'Pro',
			),
	);
}
?>