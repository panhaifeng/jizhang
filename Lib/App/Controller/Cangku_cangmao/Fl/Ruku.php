<?php
	FLEA::loadClass("Controller_Cangku_Ruku");
	class Controller_Cangku_Fl_Ruku extends Controller_Cangku_Ruku {
		function __construct(){
			$this->_kind2=3; //标记变量用于区分成品半成品和原料
			$this->_modelRuku = & FLEA::getSingleton('Model_Cangku_Ruku');
			$this->_modelExample = & FLEA::getSingleton('Model_Cangku_Ruku');
			$this->_modelRuku->_head="FR";//原料入库单号前缀
			$this->_arrFieldInfo = array(
				'_edit' => '操作',
				'rukuNum' => '入库单号',
				'rukuDate' => '入库日期',
				'proCode'=>'编码',
				'proName'=>'名称',
				'guige'=>'规格',
				'unit'=>'单位',
				'cnt'=>'数量',
				// 'length'=>"长度",
				'rukuType'=>"入库类型",
				'kuweiName'=>"库位",
			 
				'danjia'=>'单价',
				 
				'memo'=>'备注',
			);
			$this->_fieldList=array(
				'proCode'=>array('type'=>'text','text'=>'产品编号' ,'hiddenName'=>'proId'),
				'proName'=>array('type'=>'text','text'=>'产品名称'),
				'guige'=>array('type'=>'text','text'=>'产品规格'),
	                   'unit'=>array('type'=>'text','text'=>'单位'),
	                   'danjia' =>array('type'=>'text','text'=>'单价'),
	                   // 'length'        =>array('type'=>'text','text'=>'长度'),
	                   'cnt'        =>array('type'=>'text','text'=>'数量'),
	                   'money' =>array('type'=>'text','text'=>'金额'),
	                   'memo'  =>array('type'=>'text','text'=>'备注')    
			);
		}

	}
?>