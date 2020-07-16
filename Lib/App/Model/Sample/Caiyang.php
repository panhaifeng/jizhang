<?php
load_class('TMIS_TableDataGateway');
class Model_Sample_Caiyang extends TMIS_TableDataGateway{
	var $tableName = 'sample_caiyang';
	var $primaryKey = 'id';

	var $belongsTo = array(
		array(
			'tableClass' => 'Model_Sample_Yangpin',
			'foreignKey' => 'sampleId',
			'mappingName' => 'sampleInfo'
		)
	);
}
?>
