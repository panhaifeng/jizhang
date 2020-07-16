<?php
load_class('TMIS_Model_Cangku_Son');
class Model_Yuanliao_Cgrk2product extends TMIS_Model_Cangku_Son {
    var $tableName = 'yuanliao_cgrk2product';
	var $primaryKey = 'id';
    
    //库存model标识符
	var $modelKucun = 'Model_Yuanliao_Kucun';
	//是否出库，如果是出库，数量新增时需要*-1
	var $isChuku = false;
	//子表记录中的主表映射名,用来取得主表记录
	var $mappingName = 'Ruku';
	//日期字段,必须在主表中
	var $fldDate = 'rukuDate';
	//数量,金额字段,必须在子表中,且数量字段可以是多个
	var $fldCnt = 'cnt';
	var $fldMoney = 'money';
	//其他需要复制到库存表中的字段，主要是汇总字段(分主从表,字段名必须一致)，也可能是数量字段（不参与库存计算)
	var $fldCopyMain = array('kuwei','supplierId');
	var $fldCopySon = array('productId','pihao');



	
   var $belongsTo = array (
		array(
			'tableClass' => 'Model_Jichu_Product',
			'foreignKey' => 'productId',
			'mappingName' => 'Products'
		),
		array(
			'tableClass' => 'Model_Yuanliao_Cgrk',
			'foreignKey' => 'rukuId',
			'mappingName' => 'Ruku'
		),
		// array(
  //           'tableClass' => 'Model_Jichu_Kuwei',
		// 	'foreignKey' => 'kuweiId',
		// 	'mappingName' => 'Kuwei'
		// )
	);

}
?>