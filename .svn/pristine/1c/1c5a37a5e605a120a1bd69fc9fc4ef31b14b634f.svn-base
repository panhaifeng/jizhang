<?php
FLEA::loadClass('TMIS_TableDataGateway');
class Model_Jichu_Department extends TMIS_TableDataGateway {
	var $tableName = "jichu_department";
	var $primaryKey = "id";	
	var $primaryName = "depName";
    var $hasMany = array(
		array(
			'tableClass' => 'Model_Jichu_Employ',
			'foreignKey' => 'depId',
			'mappingName' => 'Employ'
			//,'linkRemove'=>false,
			//'linkRemoveFillValue'=>0
		)
	);
}
?>