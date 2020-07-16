<?php
/*********************************************************************\
*  Copyright (c) 1998-2013, TH. All Rights Reserved.
*  Author :Jeff
*  FName  :Ruku.php
*  Time   :2014/05/13 18:31:40
*  Remark :采购退货控制器
\*********************************************************************/
FLEA::loadClass('Controller_Cangku_Ruku');
class Controller_Cangku_Yuanliao_Cgth extends Controller_Cangku_Ruku {
	// var $fldMain;
	// var $headSon;
	// var $rules;//表单元素的验证规则
	// **************************************构造函数 begin********************************
	function __construct() {
		$this->_state = '原料';
		$this->_head = 'CGTHA';
		$this->_kind='采购退货';
		$this->_modelDefault = &FLEA::getSingleton('Model_Cangku_Yuanliao_Ruku');
		$this->_modelExample = &FLEA::getSingleton('Model_Cangku_Yuanliao_Ruku'); 
		// $this->_modelExample = &FLEA::getSingleton('Model_Cangku_Yuanliao_Ruku'); 
		// $this->_modelExample = &FLEA::getSingleton('Model_Cangku_Yuanliao_Ruku'); 
		$this->_modelMain = &FLEA::getSingleton('Model_Cangku_Yuanliao_Ruku');
		$this->_modelSon = &FLEA::getSingleton('Model_Cangku_Yuanliao_Ruku2Product');
		parent::__construct();


		//弹出选择相关入库记录
		//可以考虑让客户在备注中写即可,不需要另外写控件了。
	}

	function actionSave() {
		//退货时，需要将数量为正的自动变成负数
		foreach($_POST['cnt'] as $k=>&$v) {
			if($v<0) continue;
			$v = -1*$v;
			$_POST['money'][$k] *=-1;
		}
		// dump($_POST);exit;
		parent::actionSave();
	}

	function actionAdd() {
		$this->authCheck('3-1-13');
		parent::actionAdd();
	}
}