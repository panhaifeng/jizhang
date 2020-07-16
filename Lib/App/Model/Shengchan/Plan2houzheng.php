<?php
load_class('TMIS_TableDataGateway');
class Model_Shengchan_Plan2Houzheng extends TMIS_TableDataGateway {
    var $tableName = 'shengchan_plan2houzheng';
	var $primaryKey = 'id';
    var $belongsTo = array (
		array(
            'tableClass' => 'Model_Shengchan_Plan',
			'foreignKey' => 'planId',
			'mappingName' => 'Plan'
		)
	);
   
}
?>