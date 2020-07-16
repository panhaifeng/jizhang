<?php
load_class('TMIS_TableDataGateway');
class Model_Shengchan_Waixie2Product extends TMIS_TableDataGateway {
    var $tableName = 'shengchan_waixie2product';
	var $primaryKey = 'id';
    
    var $belongsTo = array (
		array(
			'tableClass' => 'Model_Jichu_Product',
			'foreignKey' => 'productId',
			'mappingName' => 'Products'
		),
		array(
			'tableClass' => 'Model_Trade_Order2Product',
			'foreignKey' => 'ord2proId',
			'mappingName' => 'Order2product'
		),
		array(
            'tableClass' => 'Model_Shengchan_Waixie',
			'foreignKey' => 'waixieId',
			'mappingName' => 'waixie2pro'

		)
	);
   
}
?>