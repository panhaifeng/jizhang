<?php
load_class('TMIS_TableDataGateway');
class Model_Jichu_ProductSon extends TMIS_TableDataGateway {
	var $tableName = 'jichu_product_chengfen';
	var $primaryKey = 'id';
	
	var $belongsTo = array (
			array(
					'tableClass' => 'Model_Jichu_Product',
					'foreignKey' => 'proId',
					'mappingName' => 'Pro',
			),
	);
}
?>