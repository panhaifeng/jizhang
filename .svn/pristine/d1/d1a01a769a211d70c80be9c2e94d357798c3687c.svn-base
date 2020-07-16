<?php
load_class('TMIS_TableDataGateway');
class Model_Shengchan_Shangji extends TMIS_TableDataGateway {
    var $tableName = 'shengchan_shangji';
	var $primaryKey = 'id';
    
    var $belongsTo = array (
		array(
			'tableClass' => 'Model_Trade_Order2Product',
			'foreignKey' => 'ord2proId',
			'mappingName' => 'Ord2pro'
		),
	);
}
?>