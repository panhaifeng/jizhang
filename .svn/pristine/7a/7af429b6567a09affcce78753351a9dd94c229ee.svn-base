<?php
FLEA::loadClass('TMIS_Controller');
class Controller_Acm_Func extends Tmis_Controller {
	var $_modelFunc; 
	// var $funcId=22;
	function Controller_Acm_Func() {
		$this->_modelFunc = FLEA::getSingleton('Model_Acm_Func');
	} 
	// 设置权限
	function actionSetQx() {
		$this->authCheck('7-1-3');
		$ret = $this->_modelFunc->getTreeJson(array('children' => $this->_modelFunc->menu
				)); 
		// dump($ret);exit;
		// 取得所有的组
		$mRole = FLEA::getSingleton('Model_Acm_Role');
		$rowRole = $mRole->findAll();

		$smarty = &$this->_getView();
		$smarty->assign('title', '为组设置权限');
		$smarty->assign('data', json_encode($ret));
		$smarty->assign('map', json_encode($this->_modelFunc->menu)); 
		// dump($this->_modelFunc->menu);exit;
		$smarty->assign('rowRole', $rowRole);
		$smarty->display("Acm/ShowFunc.tpl");
	} 
	// 分配权限时，点击某个role,需要获得该role所有的funcId
	function actionGetRoleQx() {
		$m = &FLEA::getSingleton('Model_Acm_Role');
		$sql = "select * from acm_func2role where roleId='{$_GET['roleId']}'";
		$rowset = $m->findBySql($sql);
		echo json_encode($rowset);
		exit;
	} 
	// 检查acm_func2role表中是否存在无效的menuId,发现则删除.
	function checkQx() {
	} 
	// 从树形结构页面中以ajax方式提交过来的处理函数
	function actionAssignFuncByAjax() {
		// dump($_POST['menuId']);exit;
		$m = FLEA::getSingleton('Model_Acm_Func2Role'); 
		// 先删除相关角色下所有已经分配的权限关系
		$sql = "delete from acm_func2role where roleId='{$_POST['roleId']}'";
		$m->execute($sql);
		$arr = array();
		foreach($_POST['menuId'] as &$v) {
			$arr[] = array('menuId' => $v,
				'roleId' => $_POST['roleId']
				);
		}
		$m->createRowset($arr);
		echo json_encode(array('success' => true));
		exit;
	}
}

?>