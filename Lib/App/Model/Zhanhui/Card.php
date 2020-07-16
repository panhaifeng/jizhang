<?php
load_class('TMIS_TableDataGateway');
class Model_Zhanhui_Card extends TMIS_TableDataGateway {
	var $tableName = 'zhanhui_card';
	var $primaryKey = 'id';
	//var $primaryName = 'compName';
	var $hasMany = array(
		array(
			'tableClass' => 'Model_Zhanhui_Jiyang',
			'foreignKey' => 'cardId',
			'mappingName' => 'Jiyang'
		)
	);
}
?>