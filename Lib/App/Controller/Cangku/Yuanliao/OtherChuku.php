<?php
FLEA::loadClass('Controller_Cangku_Chuku');
class Controller_Cangku_Yuanliao_OtherChuku extends Controller_Cangku_Chuku {	
	function __construct() {
		// $this->_kuwei = '原料仓库';//库位
		$this->_state = '原料';
		$this->_head = 'QITAA';//单据前缀
		$this->_kind='其他出库';
		
		parent::__construct();

		unset($this->headSon['plan2proId']);

	}

	function actionAdd(){
		$this->authCheck('3-1-10');
		parent::actionAdd();
	}

	function actionRight(){
		$this->authCheck('3-1-11');
		parent::actionRight(false);
	}
}