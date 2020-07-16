<?php
load_class('TMIS_TableDataGateway');
class Model_Caiwu_Ys_Guozhang extends TMIS_TableDataGateway {
	var $tableName = 'caiwu_ar_guozhang';
	var $primaryKey = 'id';

	var $belongsTo = array (
		array(
			'tableClass' => 'Model_Jichu_Client',
			'foreignKey' => 'clientId',
			'mappingName' => 'Client',
		),
		array(
			'tableClass' => 'Model_Jichu_Product',
			'foreignKey' => 'productId',
			'mappingName' => 'Product',
		)
	);

	
}
?>