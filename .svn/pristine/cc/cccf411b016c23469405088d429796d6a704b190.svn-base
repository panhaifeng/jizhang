<?php
load_class('TMIS_TableDataGateway');
class Model_Shengchan_Chanliang extends TMIS_TableDataGateway {
    var $tableName = 'shengchan_chanliang';
	var $primaryKey = 'id';
    
    var $belongsTo = array (
		array(
			'tableClass' => 'Model_Jichu_Product',
			'foreignKey' => 'productId',
			'mappingName' => 'Products'
		),
	);
}
?>