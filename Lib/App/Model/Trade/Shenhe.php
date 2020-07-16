<?php
load_class('TMIS_TableDataGateway');
class Model_Trade_Shenhe extends TMIS_TableDataGateway {
	var $tableName = 'trade_order_shenhe';
	var $primaryKey = 'id';
	// var $primaryName = 'orderCode';


	var $belongsTo = array (
		array(
			'tableClass' => 'Model_Trade_Order2Product',
			'foreignKey' => 'ord2proId',
			'mappingName' => 'Ord2pro',
		)
	);
}


?>