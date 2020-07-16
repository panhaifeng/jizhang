<?php
load_class('TMIS_TableDataGateway');
class Model_Shengchan_Waixie_Fawai extends TMIS_TableDataGateway {
    var $tableName = 'cangku_fawai';
	var $primaryKey = 'id';
    
    var $hasMany = array (
		array(
			'tableClass' => 'Model_Shengchan_Waixie_FawaiSon',
			'foreignKey' => 'fawaiId',
			'mappingName' => 'Son',
		)
    );

}
?>