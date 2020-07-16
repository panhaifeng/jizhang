<?php
load_class('TMIS_TableDataGateway');
class Model_Yuanliao_TestMainRuku extends TMIS_TableDataGateway {
    var $tableName = 'yuanliao_cgrk';
	var $primaryKey = 'id';
	var $_head="CR";


	var $hasMany = array (
		array(
			'tableClass' => 'Model_Yuanliao_TestSonRuku',
			'foreignKey' => 'rukuId',
			'mappingName' => 'Products',
		)
	);

}
?>



