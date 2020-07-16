<?php
load_class('TMIS_TableDataGateway');
class Model_Jifen_UpLogByuser extends TMIS_TableDataGateway {
	var $tableName = 'jifen_upbyuser_log';
	var $primaryKey = 'id';
	//var $primaryName = 'zhishu';
        var $belongsTo = array(
		array(
			'tableClass' => 'Model_Jifen_User',
			'foreignKey' => 'userId',
			'mappingName' => 'User'
		)

	);

}
?>