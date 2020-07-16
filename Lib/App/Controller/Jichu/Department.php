<?php
FLEA::loadClass('TMIS_Controller');
class Controller_Jichu_Department extends Tmis_Controller {
	var $_modelExample;
	var $funcId = 28;
	var $_tplEdit = 'Jichu/DepartmentEdit.tpl';
	function Controller_Jichu_Department() {
		$this->_modelExample = &FLEA::getSingleton("Model_Jichu_Department");
	}
	function actionRight() {
		$title = '加工户档案'; 
		// /////////////////////////////表头
		$head = array('_edit' => "操作",
			"depName" => "部门名"
			); 
		// /////////////////////////////模块定义
		$this->authCheck('6-7'); //权限判断
		FLEA::loadClass('TMIS_Pager'); 
		// 搜索条件定义
		$arr = TMIS_Pager::getParamArray(array('key' => ''
				));
		$condition = array();
		if ($arr['key'] != '') {
			// $condition[] = array('compCode',"%{$arr['key']}%",'like','or');
			$condition[] = array('depName', "%{$arr['key']}%", 'like');
		} 
		// 利用分页接口取得数据集
		$pager = &new TMIS_Pager($this->_modelExample, $condition);
		$rowset = $pager->findAll(); 
		// 形成操作列
		if (count($rowset) > 0) foreach($rowset as &$v) {
			$v['_edit'] = $this->getEditHtml($v['id']) . '&nbsp;&nbsp;' . $this->getRemoveHtml($v['id']);
		} 
		// 开始显示模板
		$smarty = &$this->_getView();
		$smarty->assign('title', $title);
		$smarty->assign('arr_field_info', $head);
		$smarty->assign('sub_field', 'Employ');
		$smarty->assign('arr_field_value', $rowset);
		$smarty->assign('arr_condition', $arr);

		 
		$smarty->assign('arr_js_css', $this->makeArrayJsCss(array('calendar')));
		$smarty->assign('page_info', $pager->getNavBar($this->_url($_GET['action'], $arr)));
		$smarty->display('TableList.tpl');
	}

	function actionSave() {
		if (empty($_POST['id'])) {
			$sql = "SELECT count(*) as cnt FROM `jichu_department` where depName='" . $_POST['depName'] . "'";
			$rr = $this->_modelExample->findBySql($sql); 
			// dump($rr);exit;
			if ($rr[0]['cnt'] > 0) {
				js_alert('部门名称重复!', null, $this->_url('add'));
			}
		}else {
			// 修改时判断是否重复
			$str1 = "SELECT count(*) as cnt FROM `jichu_department` where id!=" . $_POST['id'] . " and depName='" . $_POST['depName'] . "'";
			$ret = $this->_modelExample->findBySql($str1);
			if ($ret[0]['cnt'] > 0) {
				js_alert('部门名称重复!', null, $this->_url('Edit', array('id' => $_POST['id'])));
			}
		} 
		// parent::actionSave();
		$id = $this->_modelExample->save($_POST);
		if ($_POST['id'] == '')
			js_alert(null, "window.parent.showMsg('保存成功!')", $this->_url('add'));
		else
			js_alert(null, "window.parent.showMsg('保存成功!')", $this->_url('right'));
	}

	function actionRemove() {
		if ($_GET['id'] != "") {
			$sql = "SELECT count(*) as cnt FROM `jichu_employ` where depId =" . $_GET['id']; 
			// dump($sql);exit;
			$re = $this->_modelExample->findBySql($sql); 
			// dump($re);exit;
			if ($re[0]['cnt'] > 0) {
				js_alert('此部门已经有员工，禁止删除！', null, $this->_url('right'));
			}
		}
		parent::actionRemove();
	}
}

?>