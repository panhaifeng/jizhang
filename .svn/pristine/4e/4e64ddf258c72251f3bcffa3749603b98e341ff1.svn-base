<?php
load_class('TMIS_TableDataGateway');
class Model_Shengchan_Plan2Zhizao extends TMIS_TableDataGateway {
    var $tableName = 'shengchan_plan2zhizao';
	var $primaryKey = 'id';
    var $belongsTo = array (
		array(
            'tableClass' => 'Model_Shengchan_Plan',
			'foreignKey' => 'planId',
			'mappingName' => 'Plan'
		)
	);
    var $hasMany = array (
    		array(
    				'tableClass' => 'Model_Shengchan_Plan2Zhizao2sha',
    				'foreignKey' => 'zhizaoId',
    				'mappingName' => 'Shas',
    		)
    	);
}
?>