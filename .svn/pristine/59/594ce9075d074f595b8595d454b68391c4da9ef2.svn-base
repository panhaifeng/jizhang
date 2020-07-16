<?php
/*********************************************************************\
*  Copyright (c) 1998-2013, TH. All Rights Reserved.
*  Author :Jeff
*  FName  :Init.php
*  Time   :2014/05/12 13:55:16
*  Remark :库存初始化模块
\*********************************************************************/
FLEA::loadClass('Controller_Cangku_Ruku');
class Controller_Cangku_Qieduan_Init extends Controller_Cangku_Ruku {
	// **************************************构造函数 begin********************************
	function __construct() {
		
		// $this->_modelDefault = &FLEA::getSingleton('Model_Cangku_Jingzha_Ruku');
		// $this->_modelExample = &FLEA::getSingleton('Model_Cangku_Jingzha_Ruku'); 
		// $this->_modelMain = &FLEA::getSingleton('Model_Cangku_Jingzha_Ruku');
		// $this->_modelSon = &FLEA::getSingleton('Model_Cangku_Jingzha_Ruku2Product');
		$this->_head = 'INITE';
		$this->_state = '切断后';
		$this->_kind='初始化';
		parent::__construct();
		
		// // dump($this->fldMain);exit;
		unset($this->fldMain['songhuoCode']);
		unset($this->fldMain['supplierId']);
		unset($this->headSon['danjia']);
		unset($this->headSon['money']);

		// dump($this->headSon);//exit;
		//质量等级
		$optionDengji = array(
			array('value'=>'A','text'=>'A'),
			array('value'=>'B','text'=>'B'),
			array('value'=>'报废','text'=>'报废'),
		);

		//重新定义明细表表头，因为有计划明细
		$this->headSon = array(
			'_edit' => array('type' => 'btBtnRemove', "title" => '+5行', 'name' => '_edit[]'),
			// 'plan2proId' => array('type' => 'BtPlanPopup', "title" => '相关生产计划', 'name' => 'plan2proId[]'),
			'productId' => array('type' => 'btProductpopup', "title" => '产品选择', 'name' => 'productId[]'),
			// 'proName' => array('type' => 'bttext', "title" => '品名', 'name' => 'proName[]', 'readonly' => true),
			// 'guige' => array('type' => 'bttext', "title" => '规格', 'name' => 'guige[]', 'readonly' => true), 
			// 'unit'=>array('type'=>'bttext',"title"=>'单位','name'=>'unit[]','readonly'=>true),
			'dengji' => array('type' => 'btselect', 'title' => '等级', 'name' => 'dengji[]','options'=>$optionDengji),
			'pihao' => array('type' => 'bttext', 'title' => '批号', 'name' => 'pihao[]'),
			'width' => array('type' => 'bttext', "title" => '实际宽度', 'name' => 'width[]'), 
			'thick' => array('type' => 'bttext', "title" => '实际厚度', 'name' => 'thick[]'), 
			'cnt' => array('type' => 'bttext', "title" => '数量', 'name' => 'cnt[]'), 
			// 'danjia'=>array('type'=>'bttext',"title"=>'单价','name'=>'danjia[]'),
			// 'money'=>array('type'=>'bttext',"title"=>'金额(元)','name'=>'money[]'),
			// 'kuweiId'=>array('type'=>'btkuweiselect','title'=>'库位选择','name'=>'kuweiId[]'),
			'memo' => array('type' => 'bttext', "title" => '备注', 'name' => 'memo[]'), 
			// ***************如何处理hidden?
			'id' => array('type' => 'bthidden', 'name' => 'id[]'),
		); 

		//浏览界面的字段
		$this->fldRight = array(			
			"rukuDate" => "入库日期",			
			// "kind" => "类别",
			'kuwei' => '库位',
			'state' => '状态',
			// 'proKind' => '种类',
			'proCode' => '产品编码',
			'proName' => '品名',
			'guige' => '规格',
			'width' => '实际宽度',
			'thick' => '实际厚度',
			'pihao'=>'批号',
			'dengji'=>'等级',
			// 'color' => '颜色',
			'cnt' => '数量', 
			'rukuCode' => array("text"=>'入库单号','width'=>150),
			// 'danjia' => '单价', 
			// 'money' => '金额', 
			// "compName" => "供应商",
			// 'songhuoCode' => '送货单号',
			'memo' => '备注'
		);
		// dump($this->fldMain);//exit;
	}

	//浏览界面显示新增按钮
	function actionRight() {
		parent::actionRight(true);
	}
}