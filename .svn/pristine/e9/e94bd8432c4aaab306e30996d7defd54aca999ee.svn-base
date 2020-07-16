<?php
	FLEA::loadClass("Controller_Cangku_Chuku");
	class Controller_Cangku_Yl_Chuku extends Controller_Cangku_Chuku {
		function __construct(){
			$this->_kind2=2;
			$this->_modelExample = & FLEA::getSingleton('Model_Cangku_Chuku');
			$this->_modelChuku = & FLEA::getSingleton('Model_Cangku_Chuku');
	             $this->_modelProduct= & FLEA::getSingleton('Model_Jichu_Product');
			$this->_modelChukuProduct = & FLEA::getSingleton('Model_Cangku_ChukuProduct');
			$this->_modelExpense=& FLEA::getSingleton('Model_Caiwu_Expense');
			$this->_modelChuku->_head="YC";//原料出库单号的前缀
			$this->_arrFieldInfo= array(
				'_edit' => '操作',
				'chukuNum' => '出库单号',
				'chukuDate' => '出库日期',
				'depName'=>'出库对象',
				'operator' => '制单人',
				'proCode'=>'编码',
				'proName'=>'名称',
				'guige'=>'规格',
				'unit'=>'单位',
				'danjia'=>'单价',
				'money' =>'金额',
				// 'chukuOrder'=>'出库流转单号',
				//'kuweiName'=>'库位',
				'cnt'=>'长度',
				// 'cntCi'=>'次品数',
				//'danjia'=>'单价',
				//'money'=>'金额',
				'memo'=>'备注',
			);
			$this->_fieldList=array(
				'proCode'=>array('type'=>'text','text'=>'产品编号' ,'hiddenName'=>'proId'),
				'proName'=>array('type'=>'text','text'=>'产品名称','hiddenName'=>'order2productId'),
				'guige'=>array('type'=>'text','text'=>'产品规格'),
				// 'guigeOrd'=>array('type'=>'text','text'=>'订单规格'),
		             'unit'=>array('type'=>'text','text'=>'单位'),
		             'danjia' =>array('type'=>'text','text'=>'单价',"onChange"=>"setXsws(this)"),
		             // 'cnt'        =>array('type'=>'text','text'=>'重量'),
		             'cnt'        =>array('type'=>'text','text'=>'长度'),
		             'money' =>array('type'=>'text','text'=>'金额'),
		             'memo'  =>array('type'=>'text','text'=>'备注')    
			);
		}

	}
?>