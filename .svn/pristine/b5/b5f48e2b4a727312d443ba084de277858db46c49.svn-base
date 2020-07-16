<?php
FLEA::loadClass('TMIS_Controller');
class Controller_Jichu_Kuwei extends Tmis_Controller {
	var $_modelExample;
	var $fldMain; 
	// /构造函数
	function __construct() {
		$this->_modelExample = &FLEA::getSingleton('Model_Jichu_Kuwei'); 
		// //得到产品分类options
		// $sql = "select distinct kind from jichu_product where 1 ";
		// $rowset = $this->_modelExample->findBySql($sql);
		// foreach($rowset as & $v) {
		// 	$optKind[] = array('text'=>$v['kind'],'value'=>$v['kind']);
		// }
		// //得到品名options
		// $sql = "select distinct proName from jichu_product where 1 ";
		// $rowset = $this->_modelExample->findBySql($sql);
		// foreach($rowset as & $v) {
		// 	$optProname[] = array('text'=>$v['proName'],'value'=>$v['proName']);
		// }
		$this->fldMain = array(
			'kuweiName' => array('title' => '库位', "type" => "text", 'value' => ''),
			'type'=>array('title'=>'类别','type'=>'select','options'=>array(
                    array('text' => '原料', 'value' => '原料'),
					array('text' => '成品', 'value' => '成品'),
				) ),
			'memo' => array('title' => '备注', "type" => "textarea", 'value' => ''),
			// 'manger' => array('title' => '负责人', "type" => "text", 'value' => ''),
			// 'tel' => array('title' => '电话', "type" => "text", 'value' => ''),
			// 'contacter' => array('title' => '联系人', "type" => "text", 'value' => ''),
			// 'phone' => array('title' => '营业厅电话', "type" => "text", 'value' => ''),
			// 'acountCode' => array('title' => '开户账号', "type" => "text", 'value' => ''),
			// 'xingzhi' => array('title' => '性质', "type" => "text", 'value' => ''),
			// 'proCode' => array('title' => '物料编码', "type" => "text", 'value' => ''),
			// 'proName' => array('title' => '品名', 'type' => 'combobox', 'value' => '','options'=>$optProname),
			// 'guige' => array('title' => '规格', 'type' => 'text', 'value' => '','addonEnd'=>'mm'),
			// // 'color' => array('title' => '纱支颜色', 'type' => 'combobox', 'value' => '', 'options' => $color),
			// 'memo' => array('title' => '备注说明', 'type' => 'textarea', 'value' => ''),
			'id' => array('type' => 'hidden', 'value' => ''), 
			// 'kind'=>array('value'=>''),
		);
	}

	function actionRight() {
		FLEA::loadClass('TMIS_Pager');
		$arr = TMIS_Pager::getParamArray(array(
			// "kind" => "",
			'key' => '',
		));
		$str = "select * from {$this->_modelExample->qtableName} where 1";
		if ($arr['key'] != '') $str .= " and kuweiName like '%{$arr['key']}%'";
		$pager = &new TMIS_Pager($str);
		$rowset = $pager->findAll(); 
		if (count($rowset) > 0) foreach($rowset as &$v) {
			$v['_edit'] = $this->getEditHtml($v['id']) . ' ' . $this->getRemoveHtml($v['id']);
		}
		$smarty = &$this->_getView();
		$smarty->assign('title', '库位');
		$arr_field_info = array(
			"_edit" => '操作',
		);
		foreach($this->fldMain as $k=>& $v) {
			if($v['type'] == 'hidden') continue;
			$arr_field_info[$k] = $v['title'];
		}
		$smarty->assign('arr_field_info', $arr_field_info);
		$smarty->assign('arr_field_value', $rowset); 
		$smarty->assign('arr_js_css', $this->makeArrayJsCss(array('thickbox')));
		$smarty->assign('arr_condition', $arr);
		$smarty->assign('page_info', $pager->getNavBar($this->_url('Popup', $arr)));
		$smarty->display('TblList.tpl');
	} 

	function actionAdd() {
		$smarty = &$this->_getView();
		$smarty->assign('fldMain', $this->fldMain);
		$smarty->assign('title', '库位信息');
		$smarty->display('Main/A.tpl');
	}

	function actionEdit() {
		$row = $this->_modelExample->find(array('id' => $_GET['id']));
		$this->fldMain = $this->getValueFromRow($this->fldMain, $row); 
		// dump($row);dump($this->fldMain);exit;
		$smarty = &$this->_getView();
		$smarty->assign('fldMain', $this->fldMain);
		$smarty->assign('title', '库位信息');
		$smarty->assign('aRow', $row);
		$smarty->display('Main/A.tpl');
	}

	function actionSave() {
		// 确保产品编码,品名,规格,颜色都存在
		if (!$_POST['kuweiName']) {
			js_alert('请输入库位名!', 'window.history.go(-1)');
			exit;
		}

		// 产品编码不重复
		$sql = "select count(*) cnt from {$this->_modelExample->qtableName} where kuweiName='{$_POST['kuweiName']}' and id<>'{$_POST['id']}'";
		$_rows = $this->_modelExample->findBySql($sql);
		if ($_rows[0]['cnt'] > 0) {
			js_alert('库位重复!',  'window.history.go(-1)');
			exit;
		}		
		
		$this->_modelExample->save($_POST);
		js_alert(null, 'window.parent.showMsg("保存成功")', $this->_url($_POST['fromAction']));
		exit;
	}
}

?>