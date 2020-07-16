<?php
FLEA::loadClass('TMIS_TableDataGateway');
class Model_Caiwu_Ys_Fapiao extends TMIS_TableDataGateway {
	var $tableName = 'caiwu_ar_fapiao';
	var $primaryKey = 'id';
	var $belongsTo = array(
		array(
			'tableClass' => 'Model_Jichu_Client',
			'foreignKey' => 'clientId',
			'mappingName' => 'Client'
		)
	);
	
}

?>