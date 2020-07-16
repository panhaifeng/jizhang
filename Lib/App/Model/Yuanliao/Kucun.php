<?php
FLEA::loadClass('TMIS_TableDataGateway');
class Model_Yuanliao_Kucun extends TMIS_TableDataGateway {
	var $tableName = "yuanliao_kucun";
	var $primaryKey = "id";
	
	var $belongsTo = array (
		array(
			'tableClass' => 'Model_Yuanliao_Cgrk2product',
			'foreignKey' => 'rukuId',
			'mappingName' => 'Pro'
		)
	);
}
?>