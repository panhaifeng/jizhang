<?php
load_class('TMIS_TableDataGateway');
class Model_Mail extends TMIS_TableDataGateway {
	var $tableName = 'mail_db';
	var $primaryKey = 'id';
    var $belongsTo = array(
		array(
			'tableClass' => 'Model_Acm_User',
			'foreignKey' => 'senderId',
			'mappingName' => 'Sender'
		),
		array(
			'tableClass' => 'Model_Acm_User',
			'foreignKey' => 'accepterId',
			'mappingName' => 'Accepter'
		)

	);

}
?>