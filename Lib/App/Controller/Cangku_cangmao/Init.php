<?php
FLEA::loadClass('TMIS_Controller');
class Controller_Cangku_Init extends Tmis_Controller {
	var $_modelExample;
	var $title = "成品期初库存";
	var $funcId = 23;
	function Controller_Cangku_Init() {
		$this->_modelExample = & FLEA::getSingleton('Model_Cangku_Init');
	}

	
	function actionRight() {
		$this->authCheck($this->funcId);

		FLEA::loadClass('TMIS_Pager');
		$arrGet = TMIS_Pager::getParamArray(
			array(
				'key' => ''
			)
		);


		if ($arrGet['key'] != '') {
			$condition[] = array('Products.proCode', $arrGet['key']);		//关联表查询规格, 注意[]
		}
		$pager =& new TMIS_Pager($this->_modelExample,$condition);
        $rowset =$pager->findAll();

		if (count($rowset)>0) foreach($rowset as &$value) {
			$value['proCode'] = $value['Products']['proCode'];
			$value['proName'] = $value['Products']['proName'];
		}

		$arr_field_info = array(
			"proCode" =>"材料编码",
			"proName" =>"材料名称",
			'initCnt' => '数量',
			'memo'=> '备注',
			'dt' =>	'日期'
		);
		$arr_edit_info = array(
			"edit" =>"修改",
			"remove" =>"删除"
		);

		$smarty = & $this->_getView();
		$smarty->assign('title', $this->title);
		$smarty->assign('arr_field_info',$arr_field_info);
		$smarty->assign('arr_edit_info',$arr_edit_info);
		$smarty->assign('arr_field_value',$rowset);
		$smarty->assign('pk', $this->_modelExample->primaryKey);	
		$smarty->assign('arr_condition',$arrGet);	
		$smarty->assign('page_info',$pager->getNavBar($this->_url('right',$arrGet)));
		$smarty->display('TableList.tpl');
	}	

	private function _edit($arr) {
		$this->authCheck($this->funcId);
		$smarty = & $this->_getView();
		$smarty->assign('title', $this->title);
		$smarty->assign("aRow",$arr);		
		$pk=$this->_modelExample->primaryKey;
		$primary_key=(isset($_GET[$pk])?$pk:"");		
		$smarty->assign("pk",$primary_key);
		$smarty->assign('arr_js_css',$this->makeArrayJsCss(array('suggest')));
		$smarty->display('Cangku/InitEdit.tpl');
	}

	#增加界面
	function actionAdd() {
		$this->_edit(array());
	}

	#保存
	function actionSave() {
		if (trim($_POST['initCnt']) == '') {
			js_alert("数量不能为空!", "window.history.go(-1)");	exit;
		}

		$this->authCheck($this->funcId);

       	$success = $this->_modelExample->save($_POST);
		if($success) {
			echo "<script>alert('保存成功!');window.close();</script>";
			redirect($this->_url('add'));
		} else {
			echo "<script>alert('保存失败!');</script>";
		}
	}
	
	#修改界面
	function actionEdit() {
		$pk	=$this->_modelExample->primaryKey;
		$aYl =$this->_modelExample->find($_GET[$pk]);
		$this->_edit($aYl);
	}	

	function actionRemove() {
		$this->authCheck($this->funcId);

		$pk=$this->_modelExample->primaryKey;
		$this->_modelExample->removeByPkv($_GET[$pk]);
		redirect($this->_url("Right"));
	}
}
?>