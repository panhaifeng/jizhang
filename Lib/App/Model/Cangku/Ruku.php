<?php
load_class('TMIS_TableDataGateway');
class Model_Cangku_Ruku extends TMIS_TableDataGateway {
    var $tableName = 'cangku_common_ruku';
	var $primaryKey = 'id';
	var $hasMany = array (
		array(
			'tableClass' => 'Model_Cangku_Ruku2Product',
			'foreignKey' => 'rukuId',
			'mappingName' => 'Products',
		)
	);

	var $belongsTo = array(
		array(
			'tableClass' => 'Model_Jichu_Supplier',
			'foreignKey' => 'supplierId',
			'mappingName' => 'Supplier',
		)
	);

}
?>