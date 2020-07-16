<?php
load_class('TMIS_TableDataGateway');
class Model_Shengchan_Huitian2Bu extends TMIS_TableDataGateway {
    var $tableName = 'shengchan_huitian2bu';
	var $primaryKey = 'id';
    var $belongsTo = array (
		array(
            'tableClass' => 'Model_Shengchan_Huitian',
			'foreignKey' => 'huitianId',
			'mappingName' => 'Huitians'
		)
	);
   
}
?>