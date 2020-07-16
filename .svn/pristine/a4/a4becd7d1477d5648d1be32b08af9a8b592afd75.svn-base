<?php
FLEA::loadClass('TMIS_Controller');
class Controller_Jifen extends Tmis_Controller {
	var $_modelExample;
	var $_modelAcmOa;
	function Controller_Jifen() {
		$this->_modelExample = &FLEA::getSingleton('Model_OaMessage');
		$this->_modelAcmOa = &FLEA::getSingleton('Model_Acm_User2message');
	}
	function actionIndex() {
		redirect(url('Jifen_Comp', 'upJifenByUser'));
	}
}

?>