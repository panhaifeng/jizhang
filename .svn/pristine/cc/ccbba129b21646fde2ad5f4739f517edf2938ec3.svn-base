<?php
load_class('TMIS_TableDataGateway');
class Model_Sample_Yangpin extends TMIS_TableDataGateway{
	var $tableName = 'sample_db';
	var $primaryKey = 'id';
	//var $primaryName='barCode';
	var $hasMany=array(
	    array(
		    'tableClass' => 'Model_Sample_Caiyang',
		    'foreignKey' => 'sampleId',
		    'mappingName' => 'caiyang'
	    ),
	);

}
?>
