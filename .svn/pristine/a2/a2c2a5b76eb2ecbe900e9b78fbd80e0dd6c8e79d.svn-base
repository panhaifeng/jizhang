<?php
FLEA::loadClass('TMIS_Controller');
class Controller_Jichu_chengfen extends Tmis_Controller {
	var $_modelExample;
	var $fldMain; 
	// /构造函数
	function Controller_Jichu_chengfen() {
		$this->_modelExample = &FLEA::getSingleton('Model_Jichu_Chengfen'); 
		$this->fldMain = array(
			'name' => array('title' => '名称', 'type' => 'text', 'value' => ''),
			'memo' => array('title' => '备注', 'type' => 'text', 'value' => ''),
			'id' => array('type' => 'hidden', 'value' => ''), 
			);
		$this->rules = array(
			'name'=>'required',
		);
	}

	function actionRight() {
		FLEA::loadClass('TMIS_Pager');
		$arr = TMIS_Pager::getParamArray(array(
				'key' => '',
				));
		$str = "select * from jichu_chengfen where 1 ";
		if ($arr['key'] != '') $str .= " and name like '%{$arr['key']}%'";
		$str .= " order by name asc";
		$pager = &new TMIS_Pager($str);
		$rowset = $pager->findAllBySql($str); 
		if (count($rowset) > 0) foreach($rowset as &$v) {
			$v['_edit'] = $this->getEditHtml($v['id']) . ' ' . $this->getRemoveHtml($v['id']);
		}
		$smarty = &$this->_getView();
		$smarty->assign('title', '选择成分');
		$pk = $this->_modelExample->primaryKey;
		$smarty->assign('pk', $pk);
		$arr_field_info = array("_edit" => '操作',
			"name" => '名称',
			"memo" => '备注',
			);
		$smarty->assign('arr_field_info', $arr_field_info);
		$smarty->assign('arr_field_value', $rowset); 
		$smarty->assign('arr_js_css', $this->makeArrayJsCss(array('thickbox')));
		$smarty->assign('arr_condition', $arr);
		$smarty->assign('page_info', $pager->getNavBar($this->_url($_GET['action'], $arr)));
		$smarty->display('TblList.tpl');
	} 
	function actionAdd(){
		$smarty = & $this->_getView();
		$smarty->assign('fldMain',$this->fldMain);
		$smarty->assign('title','成分信息编辑');
		$smarty->assign('rules',$this->rules);
		$smarty->display('Main/A.tpl');
	}
	function actionEdit(){
		$row = $this->_modelExample->find(array('id'=>$_GET['id']));
		$this->fldMain = $this->getValueFromRow($this->fldMain,$row);
		$smarty = & $this->_getView();
		$smarty->assign('fldMain',$this->fldMain);
		$smarty->assign('rules',$this->rules);
		$smarty->assign('title','成分信息编辑');
		$smarty->assign('aRow',$row);
		$smarty->display('Main/A.tpl');
	}
}

?>