<?php
load_class('Model_Cangku_Chuku');
class Model_Cangku_Chengpin_Chuku extends Model_Cangku_Chuku {
 	var $tableName = 'cangku_common_chuku';
	var $primaryKey = 'id';
	//单据前缀
	//var $_head='YLRK';

	var $hasMany = array (
		array(
			'tableClass' => 'Model_Cangku_Chengpin_Chuku2Product',
			'foreignKey' => 'chukuId',
			'mappingName' => 'Products',
		)
	);

}
?>