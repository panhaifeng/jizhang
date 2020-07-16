<?php
load_class('TMIS_TableDataGateway');
class Model_Jichu_SupplierTaitou extends TMIS_TableDataGateway {
	var $tableName = 'jichu_supplier_taitou';
	var $primaryKey = 'id';
//	var $primaryName = 'compName';
	var $belongsTo = array(
		array(
			'tableClass' => 'Model_Jichu_Supplier',
			'foreignKey' => 'supplierId',
			'mappingName' => 'Supplier'
		)

	);
}
?>