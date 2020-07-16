<?php
load_class('Model_Cangku_Chuku');
class Model_Cangku_Yuanliao_Chuku extends Model_Cangku_Chuku {
 	var $tableName = 'cangku_common_chuku';
	var $primaryKey = 'id';

	var $hasMany = array (
		array(
			'tableClass' => 'Model_Cangku_Yuanliao_Chuku2Product',
			'foreignKey' => 'chukuId',
			'mappingName' => 'Products',
		)
	);
	var $belongsTo = array(
		array(
			'tableClass' => 'Model_Jichu_Department',
			'foreignKey' => 'depId',
			'mappingName' => 'Department',
		),
		// array(
		// 	'tableClass' => 'Model_Shengchan_Plan',
		// 	'foreignKey' => 'planId',
		// 	'mappingName' => 'Plan',
		// ),
	);
}
?>