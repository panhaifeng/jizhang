<?php
load_class('TMIS_TableDataGateway');
class Model_Chengpin_Cprk extends TMIS_TableDataGateway {
    var $tableName = 'chengpin_cprk';
	var $primaryKey = 'id';



	var $hasMany = array (
		array(
			'tableClass' => 'Model_Chengpin_Cprk2product',
			'foreignKey' => 'rukuId',
			'mappingName' => 'Products',
		)
	);
	

}
?>