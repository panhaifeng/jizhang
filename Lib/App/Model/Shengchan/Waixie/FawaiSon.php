<?php
load_class('TMIS_TableDataGateway');
class Model_Shengchan_Waixie_FawaiSon extends TMIS_TableDataGateway {
    var $tableName = 'cangku_fawai2product';
	var $primaryKey = 'id';
    
    var $belongsTo = array (
		array(
			'tableClass' => 'Model_Jichu_Product',
			'foreignKey' => 'productId',
			'mappingName' => 'Product'
		),
		array(
			'tableClass' => 'Model_Trade_Order2Product',
			'foreignKey' => 'ord2proId',
			'mappingName' => 'Order2product'
		),
		array(
            'tableClass' => 'Model_Shengchan_Waixie_Fawai',
			'foreignKey' => 'fawaiId',
			'mappingName' => 'Fawai'

		)
	);
	var $hasMany = array (
		array(
			'tableClass' => 'Model_Shengchan_Waixie_FawaiChanliang',
			'foreignKey' => 'fawaiId',
			'mappingName' => 'Chanliang',
		)
    );
   
}
?>