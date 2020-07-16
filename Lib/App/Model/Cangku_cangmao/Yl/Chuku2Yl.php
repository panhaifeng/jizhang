<?php
load_class('TMIS_TableDataGateway');
class Model_Cangku_Yl_Chuku2Yl extends TMIS_TableDataGateway {
	var $tableName = 'cangku_yl_chuku2yl';
	var $primaryKey = 'id';
	var $belongsTo = array (
		array(
			'tableClass' => 'Model_Jichu_Yl',
			'foreignKey' => 'ylId',
			'mappingName' => 'Yl'
		),
		array(
			'tableClass' => 'Model_Cangku_Yl_Chuku',
			'foreignKey' => 'chukuId',
			'mappingName' => 'Chuku'
		)
	);

	/*

	function getDanjia($productId) {
		$model = FLEA::getSingleton('Model_Cangku_RukuProduct');
		$a=$model->find("productId='$productId'",'id desc');		
		$danjia = $a[danjia];		
		if ($danjia>0) return $danjia;

		$model = FLEA::getSingleton('Model_Cangku_Init');
		$a = $model->find("productId = '$productId'");		
		if ($a[cntInit]==0) return false;
		$danjia = $a[moneyInit]/$a[cntInit];
		return number_format($danjia,2,".","");
	}

	*/
}
?>