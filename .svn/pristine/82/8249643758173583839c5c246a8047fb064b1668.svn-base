<?php
	FLEA::loadClass("Controller_Cangku_Ruku");
	class Controller_Cangku_Yl_Ruku extends Controller_Cangku_Ruku {
		function __construct(){
			$this->_kind2=2; //标记变量用于区分成品半成品和原料
			$this->_modelRuku = & FLEA::getSingleton('Model_Cangku_Ruku');
			$this->_modelExample = & FLEA::getSingleton('Model_Cangku_Ruku');
			$this->_modelRuku->_head="YR";//原料入库单号前缀
			$this->_arrFieldInfo = array(
				'rukuNum' => '入库单号',
				'rukuDate' => '入库日期',
				'proCode'=>'编码',
				'proName'=>'名称',
				'guige'=>'规格',
				'unit'=>'单位',
				// 'cnt'=>'重量',
				'cnt'=>"长度",
				'rukuType'=>"入库类型",
				'kuweiName'=>"库位",
				// 'cntCi'=>'次品数',
				'danjia'=>'单价',
				//'money'=>'金额',
				//'danjiaJG'=>'加工费',
				//'name'=>'车间名称',
				'memo'=>'备注',
				'_edit' => '操作'
			);
			$this->_fieldList=array(
				'proCode'=>array('type'=>'text','text'=>'产品编号' ,'hiddenName'=>'proId','readonly'=>'readonly'),
				'proName'=>array('type'=>'text','text'=>'产品名称','readonly'=>'readonly'),
				'guige'=>array('type'=>'text','text'=>'产品规格','readonly'=>'readonly'),
	                   'unit'=>array('type'=>'text','text'=>'单位'),
	                   'danjia' =>array('type'=>'text','text'=>'单价'),
	                   // 'length'        =>array('type'=>'text','text'=>'长度'),
	                   'cnt'        =>array('type'=>'text','text'=>'长度'),
	                   'money' =>array('type'=>'text','text'=>'金额'),
	                   'memo'  =>array('type'=>'text','text'=>'备注')    
			);
		}

	}
?>