<?php
FLEA::loadClass('Controller_Cangku_Chuku');
class Controller_Cangku_Qieduan_Xsck extends Controller_Cangku_Chuku {
	var $fldMain;
	var $headSon;
	var $rules; //表单元素的验证规则
	var $_modelDefault;
	var $_modelExample;
	var $_modelMain;
	var $_modelSon;
	var $_head;//单据前缀
	var $_kind;//入库类型
	function __construct() {
		$this->_head = 'XSCKE';//单据前缀
		$this->_kind='销售出库';
		$this->_state = '切断后';
		
		parent::__construct();

		$this->fldRight = array(
			"chukuDate" => "出库日期",
			'chukuCode' => '出库单号',
			"kuwei" => "库位",
			"state" => "状态",
			'clientName' => '客户',
			'proCode' => '产品编码',
			'proName' => '品名',
			'guige' => '规格',
			'dengji' => '等级',
			'cnt' => '数量', 
			'danjia' => '单价', 
			'money' => '金额', 
		);		

		$mainOld = $this->fldMain;
		$sonOld = $this->headSon;
		$this->fldMain = array(
			'orderId' => array('title' => '相关订单', "type" => "orderpopup", 'value' => ''),
			'clientId' => array('title' => '客户', "type" => "clientpopup", 'value' => ''),

			'depName' => array('title' => '部门名称', 'type' => 'text', 'value' => '销售部', 'model' => 'Model_Jichu_Department','readonly' => true)
		) + $this->fldMain;

		//质量等级
		$optionDengji = array(
			array('value'=>'A','text'=>'A'),
			array('value'=>'B','text'=>'B'),
			array('value'=>'报废','text'=>'报废'),
		);
		$this->headSon = array(
			'_edit' => $sonOld['_edit'],
			'productId' => $sonOld['productId'],
			'dengji' => array('type' => 'btselect', 'title' => '等级', 'name' => 'dengji[]','options'=>$optionDengji),
			'pihao' => $sonOld['pihao'],
			'cnt' => $sonOld['cnt'],
			'danjia' => array('type' => 'bttext', "title" => '单价', 'name' => 'danjia[]'),
			'money' => array('type' => 'bttext', "title" => '金额(元)', 'name' => 'money[]'), 
			'memo' => $sonOld['memo'],
			// ***************如何处理hidden?
			'id' => $sonOld['id'],
			'ord2proId' => array('type' => 'bthidden', 'name' => 'ord2proId[]'),
		);

		

	}

	//销售出库要选择相关订单，所有需要重新定义onorderSel函数
	function actionAdd($Arr) {
		$this->authCheck('3-4'); 		
		// 主表信息字段
		$fldMain = $this->fldMain; 
		
		$headSon = $this->headSon; 
		// 从表信息字段,默认5行
		for($i = 0;$i < 5;$i++) {
			$rowsSon[] = array();
		} 
		// 主表区域信息描述
		$areaMain = array('title' => '出库基本信息', 'fld' => $fldMain); 
		// 从表区域信息描述
		$smarty = &$this->_getView();
		$smarty->assign('areaMain', $areaMain);
		$smarty->assign('headSon', $headSon);
		$smarty->assign('rowsSon', $rowsSon);
		$smarty->assign('rules', $this->rules);
		$smarty->assign('sonTpl', 'Cangku/Yuanliao/jsXsck.tpl');
		$smarty->display('Main2Son/T1.tpl');
	} 

	
}