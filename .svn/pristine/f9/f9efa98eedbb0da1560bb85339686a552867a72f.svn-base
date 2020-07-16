<?php
load_class('TMIS_TableDataGateway');
class Model_Caiwu_Yf_Guozhang extends TMIS_TableDataGateway {
	var $tableName = 'caiwu_yf_guozhang';
	var $primaryKey = 'id';

	var $belongsTo = array (
		array(
			'tableClass' => 'Model_Jichu_Supplier',
			'foreignKey' => 'supplierId',
			'mappingName' => 'Supplier',
		),
		array(
			'tableClass' => 'Model_Jichu_Product',
			'foreignKey' => 'productId',
			'mappingName' => 'Product',
		)
	);

	
}
?>