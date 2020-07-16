<?php
FLEA::loadClass('TMIS_Controller');
class Controller_Caiwu_Bank extends Tmis_Controller {
	var $_modelExample;
	var $funcId = 35;
	function __construct() {		
		$this->_modelExample = & FLEA::getSingleton('Model_Caiwu_Bank');

		$this->fldMain = array(
			'itemName' => array('title' => '账户名', "type" => "text", 'value' => ''),
			'bankName' => array('title' => '银行名', "type" => "text", 'value' => ''),
			// 'address' => array('title' => '地址', "type" => "textarea", 'value' => ''),
			// 'manger' => array('title' => '负责人', "type" => "text", 'value' => ''),
			// 'tel' => array('title' => '电话', "type" => "text", 'value' => ''),
			// 'contacter' => array('title' => '联系人', "type" => "text", 'value' => ''),
			// 'phone' => array('title' => '营业厅电话', "type" => "text", 'value' => ''),
			'acountCode' => array('title' => '开户账号', "type" => "text", 'value' => ''),
			// 'xingzhi' => array('title' => '性质', "type" => "text", 'value' => ''),
			'id' => array('type' => 'hidden', 'value' => ''), 
		);
	}	
	
	function actionRight(){
		$this->authCheck('5-11');
		FLEA::loadClass('TMIS_Pager');
		$arr = & TMIS_Pager::getParamArray(array(
			'key'=>''
		));
		$pager =& new TMIS_Pager($this->_modelExample);
		$rowset =$pager->findAll();
		foreach ($rowset as & $v) {
			$v['_edit'] = $this->getEditHtml($v['id'])." ".$this->getRemoveHtml($v['id']);
		}
		$smarty = & $this->_getView();		
		$smarty->assign('title', '银行帐户');
		$arr_field_info = array(
			"_edit"         =>"操作",
			"itemName" =>"账户名",
			"bankName" =>"银行",
			// "address"=>"地址",
			// "manger"=>"负责人",
			// "tel"=>"电话",
			// "contacter"=>"联系人",
			// "phone"=>"营业厅电话",
			"acountCode"=>"账号",
			// "xingzhi"=>"性质"
		);
		#对操作栏进行赋值
		 
		// $smarty->assign('arr_edit_info',$arr_edit_info);		
		$smarty->assign('arr_field_info',$arr_field_info);
		$smarty->assign('arr_field_value',$rowset);
		$smarty->assign('arr_condition', $arr);	 
		$smarty->assign('page_info',$pager->getNavBar($this->_url('right')));
		$smarty->assign('arr_js_css',$this->makeArrayJsCss(array('grid')));
		$smarty-> display('TableList.tpl');
	}
	
	// function _edit($Arr) {
	// 	// $this->authCheck($this->funcId);
	// 	$smarty = & $this->_getView();
	// 	$smarty->assign('title', "银行账户");
	// 	$smarty->assign("aRow",$Arr);		
	// 	$smarty->display('Caiwu/BankEdit.tpl');
	// }

	function actionAdd() {		
		$smarty = &$this->_getView();
		$smarty->assign('fldMain', $this->fldMain);
		$smarty->assign('title', '账户信息');
		$smarty->display('Main/A.tpl');
	}

	function actionSave() {
       	$id = $this->_modelExample->save($_POST);
       	js_alert(null,'window.parent.showMsg("保存成功!")',$this->_url('add'));
		// if($_POST['Submit'] == '保存并新增下一个'){
			
		// } else {
		// 	js_alert('保存成功！','',$this->_url('right'));
		// }
	}

	function actionEdit() {		
		$row = $this->_modelExample->find(array('id' => $_GET['id']));
		$this->fldMain = $this->getValueFromRow($this->fldMain, $row); 
		$smarty = &$this->_getView();
		$smarty->assign('fldMain', $this->fldMain);
		$smarty->assign('title', '账户信息');
		$smarty->assign('aRow', $row);
		$smarty->display('Main/A.tpl');
	}	

}
?>