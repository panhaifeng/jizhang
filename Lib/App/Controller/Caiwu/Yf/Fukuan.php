<?php
FLEA::loadClass('TMIS_Controller');
class Controller_Caiwu_Yf_Fukuan extends Tmis_Controller {
	var $_modelExample;
	var $_tplEdit='Caiwu/Yf/FukuanEdit.tpl';
	function __construct() {
		$this->_modelExample = & FLEA::getSingleton('Model_Caiwu_Yf_Fukuan');
		 //搭建过账界面
        $this->fldMain = array(
        	'creater' => array('type' => 'text', 'value' => $_SESSION['REALNAME'],'title'=>'制单人','readonly'=>true),
        	'fukuanCode' => array('type' => 'text', 'value' => '','title'=>'付款单号'),
			'fukuanDate' => array('title' => '付款日期', "type" => "calendar", 'value' => date('Y-m-d')),
// 			'supplierId' => array('title' => '供应商', 'type' => 'selectgys', 'value' => '','model'=>'Model_Jichu_Supplier'),
        	'supplierId' => array(
					'type' => 'popup',
					"title" => '供应商',
					'name' => 'supplierId',
					'url'=>url('Jichu_supplier','PopupWithHead'),
					'textFld'=>'compName',
					'hiddenFld'=>'id',
			),
            'head' => array('title' => '发票抬头', 'type' => 'text', 'value'=>''),
			'fkType' => array('title' => '付款方式', 'type' => 'combobox', 'value' => '','options'=>$this->_modelExample->typeOptions()),
			'money' => array('title' => '本次付款', 'type' => 'text', 'value' => '','addonEnd'=>'元'),
			'memo' => array('title' => '备注', 'type' => 'textarea', 'value' => ''),
			'id' => array('type' => 'hidden', 'value' => ''),
        	'kind' => array('type' => 'hidden', 'value' => '0'),
		);

		// 表单元素的验证规则定义
		$this->rules = array(
			//'fukuanCode' => 'required repeat',
			'fukuanDate' => 'required',
			'supplierId' => 'required',
			'money' => 'required number'
		);
	}
	function actionRight() {
		// $this->authCheck('4-1-6');
		FLEA::loadclass('TMIS_Pager');
		$arr=TMIS_Pager::getParamArray(array(
			'dateFrom'=>date('Y-m-d',mktime(0,0,0,date('m'),date('d')-30,date('Y'))),
			'dateTo'=>date('Y-m-d'),
			'supplierId'=>'',
			'key'=>'',
			'no_edit'=>'',
		));
		$sql="select x.*,a.compName from caiwu_yf_fukuan x
			left join jichu_supplier a on a.id=x.supplierId
			where 1 and kind=0";
		if($arr['supplierId']!='')$sql.=" and x.supplierId='{$arr['supplierId']}'";
		if($arr['dateFrom']!=''){
			$sql.=" and x.fukuanDate >= '{$arr['dateFrom']}'";
		}
		if($arr['dateTo']!=''){
			$sql.=" and x.fukuanDate <='{$arr['dateTo']}'";
		}
		if($arr['key']!=''){
			$sql.=" and x.fukuanCode like '%{$arr['key']}%'";
		}
		$sql.=" order by x.id desc";
		$page=& new TMIS_Pager($sql);
		$rowset=$page->findAll();
		//dump($rowset);exit;
		if(count($rowset)>0)foreach($rowset as & $v) {
				$v['_edit'].=$this->getEditHtml($v['id']).'&nbsp;&nbsp;'.$this->getRemoveHtml($v['id']);
		}

		$rowset[] = $this->getHeji($rowset, array('money'), $_GET['no_edit']==1?'fukuanCode':'_edit');
		
		$arr_field_info=array(
			'_edit'=>'操作',
			'fukuanCode'=>array('text'=>'付款单号','width'=>100),
			'fukuanDate'=>'付款日期',
			'compName'=>'加工供应商',
            'head'=>'发票抬头',
			'fkType'=>'付款方式',
			'money'=>'金额',
			'memo'=>'备注',
		);
		$smarty=& $this->_getView();
		$smarty->assign('arr_condition',$arr);
		$smarty->assign('add_display','none');
		$smarty->assign('arr_field_info',$arr_field_info);
		$smarty->assign('arr_field_value',$rowset);
		$smarty->assign('page_info',$page->getNavBar($this->_url($_GET['action'],$arr)).$note);
		$smarty->display('TblList.tpl');
	}

	// function _edit($arr) {
	// 	$this->authCheck('4-1-5');
	// 	parent::_edit($arr);
	// }

	function actionSave() {
		$arr=array(
			'id'=>$_POST['id'],
			'supplierId'=>$_POST['supplierId'],
			'fukuanCode'=>$_POST['fukuanCode'],
			'fukuanDate'=>$_POST['fukuanDate'],
			'money'=>$_POST['money'],
			'memo'=>$_POST['memo'],
			'creater'=>$_POST['creater'],
			'fkType'=>$_POST['fkType'],
			'kind'=>$_POST['kind'],
			// 'bizhong'=>$_POST['bizhong'],
			// 'huilv'=>empty($_POST['huilv'])?1:$_POST['huilv'],
		);
		// dump($arr);exit;
		$id=$this->_modelExample->save($arr);
		js_alert(null,'window.parent.showMsg("操作成功");',$this->_url($_POST['fromAction']==''?'add':$_POST['fromAction']));
	}

	function actionGetSelectByAjax(){
		$sql="select fkType as text from caiwu_yf_fukuan group by fkType order by count(id) desc";
		$res=$this->_modelExample->findBySql($sql);
		echo json_encode($res);
	}

	function actionAdd() {
		$smarty = &$this->_getView();
		$smarty->assign('fldMain', $this->fldMain);
		$smarty->assign('rules', $this->rules);
		$smarty->assign('title', '付款信息编辑');
		$smarty->assign('sonTpl', 'caiwu/yf/sonTpl.tpl');
		$smarty->display('Main/A1.tpl');
	}

	function actionEdit() {
		$row = $this->_modelExample->find(array('id' => $_GET['id']));
		
		$this->fldMain = $this->getValueFromRow($this->fldMain, $row); 
		$this->fldMain['supplierId']['text']=$row['Supplier']['compName'];
		$smarty = &$this->_getView();
		$smarty->assign('fldMain', $this->fldMain);
		$smarty->assign('rules', $this->rules);
		$smarty->assign('title', '付款信息编辑');
		$smarty->assign('aRow', $row);
		$smarty->assign('sonTpl', 'caiwu/yf/sonTpl.tpl');
		$smarty->display('Main/A1.tpl');
	}
}
?>