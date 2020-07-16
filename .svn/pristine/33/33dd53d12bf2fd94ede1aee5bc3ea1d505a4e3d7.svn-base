<?php
load_class('TMIS_TableDataGateway');
class Model_OaMessage extends TMIS_TableDataGateway {
	var $tableName = 'oa_message';
	var $primaryKey = 'id';
    var $belongsTo = array(
		  array(
			'tableClass' => 'Model_Trade_Order',
			'foreignKey' => 'orderId',
			'mappingName' => 'Order'
		)

	);

}
?>