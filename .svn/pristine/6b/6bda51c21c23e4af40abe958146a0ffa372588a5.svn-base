<?php
FLEA::loadClass('TMIS_Controller');
class Controller_Index extends Tmis_Controller {
	var $_modelExample;

	function Controller_Index() {
	}

	function actionIndex() {
		redirect(url('Login'));
		exit;
	} 
	// 调试模板专用 2011.3.18 by shi
	function actionTest() {
		$smarty = &$this->_getView(); 
		// $smarty->display('Jichu/ClientEdit.tpl');
		// $smarty->display('Jichu/RanchangEdit.tpl');
		// $smarty->display('Jichu/JiangchangEdit.tpl');
		// $smarty->display('Jichu/ZhichangEdit.tpl');
		// $smarty->display('Jichu/ZhenglichangEdit.tpl');
		// $smarty->display('Jichu/DepartmentEdit.tpl');
		// $smarty->display('Jichu/EmployEdit.tpl');
		// $smarty->display('Shengchan/Gongyi/RanshaPlanEdit.tpl');
		// $smarty->display('Shengchan/Sesha/HuishouEdit.tpl');
		// $smarty->display('Shengchan/plan/TblList.tpl');
		$smarty->display('Shengchan/plan/edit.tpl');
	}
}

?>