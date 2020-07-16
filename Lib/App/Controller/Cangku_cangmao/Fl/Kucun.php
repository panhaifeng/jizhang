<?php
FLEA::loadClass('Controller_Cangku_Kucun');
class Controller_Cangku_Fl_Kucun extends Controller_Cangku_Kucun {
	// var $_kind2;
       function __construct(){
		$this->_kind2=3;
		$this->_cntField='cnt';
		$this->_modelExample = & FLEA::getSingleton('Model_Cangku_Kucun');
		$this->_mProduct = & FLEA::getSingleton('Model_Jichu_Product');
		$this->_mRuku = & FLEA::getSingleton("Model_Cangku_Ruku");
		$this->_mChuku = & FLEA::getSingleton("Model_Cangku_Chuku");
       }
}
?>