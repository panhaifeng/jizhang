<?php
load_class('TMIS_TableDataGateway');
class Model_Yuanliao_TestMainChuku extends TMIS_TableDataGateway {
    var $tableName = 'yuanliao_llck';
	var $primaryKey = 'id';


	var $hasMany = array (
		array(
			'tableClass' => 'Model_Yuanliao_TestSonChuku',
			'foreignKey' => 'chukuId',
			'mappingName' => 'Products',
		)
	);

}
?>



