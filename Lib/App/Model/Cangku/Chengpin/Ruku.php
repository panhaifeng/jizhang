<?php
load_class('Model_Cangku_Ruku');
class Model_Cangku_Chengpin_Ruku extends Model_Cangku_Ruku {
 	var $tableName = 'cangku_common_ruku';
	var $primaryKey = 'id';
	//单据前缀
	//var $_head='YLRK';

	var $hasMany = array (
		array(
			'tableClass' => 'Model_Cangku_Chengpin_Ruku2Product',
			'foreignKey' => 'rukuId',
			'mappingName' => 'Products',
		)
	);

}
?>