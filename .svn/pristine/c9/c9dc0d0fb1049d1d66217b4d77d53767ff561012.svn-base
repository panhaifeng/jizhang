<?php
load_class('TMIS_TableDataGateway');
class Model_Chengpin_Cpck extends TMIS_TableDataGateway {
    var $tableName = 'chengpin_cpck';
	var $primaryKey = 'id';

	var $hasMany = array (
		array(
			'tableClass' => 'Model_Chengpin_Cpck2product',
			'foreignKey' => 'chukuId',
			'mappingName' => 'Products',
		)
	);
	
	var $belongsTo = array (
		array(
			'tableClass' => 'Model_Jichu_Client',
			'foreignKey' => 'clientId',
			'mappingName' => 'Client',
		),
	);

}
?>