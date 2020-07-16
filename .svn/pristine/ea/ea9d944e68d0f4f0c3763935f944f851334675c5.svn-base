<?php
FLEA::loadClass('Controller_Cangku_Chuku');
class Controller_Cangku_Qieduan_Chuku extends Controller_Cangku_Chuku {
	function __construct() {
		// $this->_kuwei = '仓库';//库位
		$this->_head = 'LLCKE';//单据前缀
		$this->_kind='领料出库';
		$this->_state = '切断后';
		
		parent::__construct();

		//浏览界面的字段
		$this->fldRight = array(			
			"chukuDate" => "出库日期",
			
			// "kind" => "类别",
			'kuwei' => '库位',
			'state' => '状态',
			// 'proKind' => '种类',
			'proCode' => '产品编码',
			'proName' => '品名',
			'guige' => '规格',
			'pihao'=>'批号',
			'dengji'=>'等级',
			// 'color' => '颜色',
			'cnt' => '领用数量', 
			'chukuCode' => array("text"=>'出库单号','width'=>150),
			// 'danjia' => '单价', 
			// 'money' => '金额', 
			// "compName" => "供应商",
			// 'songhuoCode' => '送货单号',
			'memo' => '备注'
		);

		//质量等级
		$optionDengji = array(
			array('value'=>'A','text'=>'A'),
			array('value'=>'B','text'=>'B'),
			array('value'=>'报废','text'=>'报废'),
		);
		// /从表表头信息
		$this->headSon = array(
			'_edit' => array('type' => 'btBtnRemove', "title" => '+5行', 'name' => '_edit[]'),
			'plan2proId' => array('type' => 'BtPlanPopup', "title" => '相关生产计划', 'name' => 'plan2proId[]'),
			'productId' => array('type' => 'btProductpopup', "title" => '产品编码', 'name' => 'productId[]'),
			'dengji' => array('type' => 'btselect', 'title' => '等级', 'name' => 'dengji[]','options'=>$optionDengji),
			// 'proName' => array('type' => 'bttext', "title" => '品名', 'name' => 'proName[]', 'readonly' => true),
			// 'guige' => array('type' => 'bttext', "title" => '规格', 'name' => 'guige[]', 'readonly' => true),
			// 'color' => array('type' => 'bttext', "title" => '颜色', 'name' => 'color[]', 'readonly' => true), 
			// 'unit'=>array('type'=>'bttext',"title"=>'单位','name'=>'unit[]','readonly'=>true),
			'pihao' => array('type' => 'bttext', 'title' => '批号', 'name' => 'pihao[]'),
			'cnt' => array('type' => 'bttext', "title" => '数量(kg)', 'name' => 'cnt[]'),
			//'danjia' => array('type' => 'bttext', "title" => '单价', 'name' => 'danjia[]'),
			//'money' => array('type' => 'bttext', "title" => '金额(元)', 'name' => 'money[]'), 
			'memo' => array('type' => 'bttext', "title" => '备注', 'name' => 'memo[]'), 
			// ***************如何处理hidden?
			'id' => array('type' => 'bthidden', 'name' => 'id[]'),
			// 'plan2proId' => array('type' => 'bthidden', 'name' => 'plan2proId[]'),
		); 

	}
}