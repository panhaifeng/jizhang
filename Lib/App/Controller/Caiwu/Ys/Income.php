<?php
FLEA::loadClass('TMIS_Controller');
class Controller_Caiwu_Ys_Income extends Tmis_Controller {
	var $_modelExample;
	var $title = "收款登记";
	var $_tplEdit='Caiwu/Ys/IncomeEdit.tpl';
	function __construct() {
		$this->_modelExample = & FLEA::getSingleton('Model_Caiwu_Ys_Income');
		// dump($this->_modelExample->typeOptions());exit;
		$this->fldMain = array(
        	'creater' => array('type' => 'text', 'value' => $_SESSION['REALNAME'],'title'=>'制单人','readonly'=>true),
        	'shouhuiCode' => array('type' => 'text', 'value' => '','title'=>'收汇单号'),
			'shouhuiDate' => array('title' => '收款日期', "type" => "calendar", 'value' => date('Y-m-d')),
            'clientId' => array(
                'title' => '客户名称',
                'type' => 'popup',
                'url'=>url('Jichu_Client','PopupWithHead'),
                'textFld'=>'clientName',
                'hiddenFld'=>'id',
                'inTable'=>true,
            ),
            'head' => array('title' => '发票抬头', 'type' => 'text', 'value'=>''),
			'type' => array('title' => '收款方式', 'type' => 'combobox', 'value' => '','options'=>$this->_modelExample->typeOptions()),
			'money' => array('title' => '收款金额', 'type' => 'text', 'value' => ''),
			'bankId' => array('title' => '银行账号', 'type' => 'select', 'value' => '', 'model' =>'Model_Caiwu_bank'),
			'bizhong' => array('title' => '币种', 'type' => 'select', 'value' => 'RMB', 'options' => array(
					array('text' => 'RMB', 'value' => 'RMB'),
					array('text' => 'USD', 'value' => 'USD'),
					)),
			'huilv' => array('title' => '汇率', 'type' => 'text', 'value' => '1'),
			'memo' => array('title' => '备注', 'type' => 'textarea', 'value' => ''),
			'id' => array('type' => 'hidden', 'value' => ''),
		);

		// 表单元素的验证规则定义
		$this->rules = array(
			//'shouhuiCode' => 'required repeat',
			'shouhuiDate' => 'required',
			'clientId' => 'required',
			'money' => 'required number'
		);
	}

	function actionRight() {
		// $this->authCheck('5-2-6');
		FLEA::loadclass('TMIS_Pager');
		$arr=TMIS_Pager::getParamArray(array(
			'dateFrom'=>date('Y-m-d',mktime(0,0,0,date('m'),date('d')-30,date('Y'))),
			'dateTo'=>date('Y-m-d'),
			'clientId'=>'',
			'key'=>'',
			'no_edit'=>'',
		));
		$sql="select x.*,a.compName,z.itemName from caiwu_ar_income x
			left join jichu_client a on a.id=x.clientId
			left join caiwu_bank z on z.id=x.bankId
			where 1";
		if($arr['clientId']!='')$sql.=" and x.clientId='{$arr['clientId']}'";
		if($arr['dateFrom']!=''){
			$sql.=" and x.shouhuiDate >= '{$arr['dateFrom']}'";
		}
		if($arr['dateTo']!=''){
			$sql.=" and x.shouhuiDate <='{$arr['dateTo']}'";
		}
		if($arr['key']!=''){
			$sql.=" and x.shouhuiCode like '%{$arr['key']}%'";
		}
		$sql.=" order by x.id desc";
		$page=& new TMIS_Pager($sql);
		$rowset=$page->findAll();
		//dump($rowset);exit;
		if(count($rowset)>0)foreach($rowset as & $v) {
				$v['_edit'].=$this->getEditHtml($v['id']).'&nbsp;&nbsp;'.$this->getRemoveHtml($v['id']);
				//折合人民币
				$v['moneyRmb']=round($v['money']*$v['huilv'],2);
		}

		$rowset[] = $this->getHeji($rowset, array('money','moneyRmb'), $_GET['no_edit']==1?'shouhuiCode':'_edit');
		
		$arr_field_info=array(
			'_edit'=>'操作',
			'shouhuiCode'=>array('text'=>'收汇单号','width'=>120),
			'shouhuiDate'=>'收款日期',
			'compName'=>'客户',
            'head'=>'抬头',
			'money'=>'金额',
			'moneyRmb'=>'金额(RMB)',
			// 'bizhong'=>'币种',
            'huilv'=>'汇率',
			'type'=>'收款方式',
			'itemName'=>'银行账号',
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

	function actionSave() {
		$arr=array();
		foreach($this->fldMain as $key=>&$v) {
			$arr[$key] = $_POST[$key];
		}

		$arr['huilv']=empty($arr['huilv'])?1:$arr['huilv'];
		// dump($arr);exit;
		$id=$this->_modelExample->save($arr);
		js_alert(null,'window.parent.showMsg("操作成功");',$this->_url($_POST['fromAction']==''?'add':$_POST['fromAction']));

	}

	function actionAdd() {
		$smarty = &$this->_getView();
		$smarty->assign('fldMain', $this->fldMain);
		$smarty->assign('rules', $this->rules);
		$smarty->assign('title', '收款信息编辑');
        $smarty->assign('sonTpl', 'Caiwu/clientHead.tpl');
		$smarty->display('Main/A1.tpl');
	}

	function actionEdit() {
		$row = $this->_modelExample->find(array('id' => $_GET['id']));
		
		$this->fldMain = $this->getValueFromRow($this->fldMain, $row); 
		$this->fldMain['clientId']['clientName']=$row['Client']['compName'];
        $this->fldMain['clientId']['text']=$row['Client']['compName'];
		// dump($row);exit;
		$smarty = &$this->_getView();
		$smarty->assign('fldMain', $this->fldMain);
		$smarty->assign('rules', $this->rules);
		$smarty->assign('title', '收款信息编辑');
		$smarty->assign('aRow', $row);
        $smarty->assign('sonTpl', 'Caiwu/clientHead.tpl');
		$smarty->display('Main/A1.tpl');
	}
	
}
?>