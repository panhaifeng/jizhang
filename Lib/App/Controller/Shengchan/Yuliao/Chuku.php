<?php
FLEA::loadClass('Controller_Shengchan_Yuliao_ConstructChuku');
class Controller_Shengchan_Yuliao_Chuku extends Controller_Shengchan_Yuliao_ConstructChuku {
	
	// **************************************构造函数 begin********************************
	function Controller_Shengchan_Yuliao_Chuku() {
		$this->_modelDefault = &FLEA::getSingleton('Model_Yuanliao_Llck');
		$this->_modelExample = &FLEA::getSingleton('Model_Yuanliao_Llck'); 
            // 生产领用,销售出库,其他出库
		// 定义模板中的主表字段
		$this->fldMain = array('chukuDate' => array('title' => '出库日期', "type" => "calendar", 'value' => date('Y-m-d')), 
			// 入库单号，自动生成
			'chukuCode' => array('title' => '出库单号', 'type' => 'text', 'readonly' => true, 'value' => ''),

			'kind' => array('title' => '出库类别', 'type' => 'text', 'value'=>'采购出库','readonly' => true),
			// 'supplierpopup需要显示供应商名称，所以需要定义supplierName属性,value属性作为supplierId用
			'supplierId' => array('title' => '供应商', 'type' => 'select', 'value' => '', 'model' => 'Model_Jichu_Supplier'),
			'clientId'=>array('title' => '客户选择', 'type' => 'clientpopup', 'clientName' => ''),

			'orderId' => array('title' => '相关订单', "type" => "orderpopup", 'value' => ''),

			'depId' => array('title' => '部门名称', 'type' => 'select', 'value' => '', 'model' => 'Model_Jichu_Department'),

			'kuweiId' => array('title' => '库位选择', 'type' => 'select', 'value' => ''),

			'yuanyin' => array('title' => '出库原因', 'type' => 'textarea', 'disabled' => true, 'name' => 'yuanyin'), 
			// 定义了name以后，就不会以memo作为input的id了
			'memo' => array('title' => '出库备注', 'type' => 'textarea', 'disabled' => true, 'name' => 'chukuMemo'), 
			// 下面为隐藏字段
			'chukuId' => array('type' => 'hidden', 'value' => ''), 
			// 是色坯纱  用来标示是在色坯纱管理中
			'isSePiSha' => array('type' => 'hidden', 'value' => '1', 'name' => 'isSePiSha'),
			); 
		// /从表表头信息
		// /type为控件类型,在自定义模板控件
		// /title为表头
		// /name为控件名
		// /bt开头的type都在webcontrolsExt中写的,如果有新的控件，也需要增加
		$this->headSon = array('_edit' => array('type' => 'btBtnRemove', "title" => '+5行', 'name' => '_edit[]'),
			'productId' => array('type' => 'btyuanliaopopup', "title" => '产品选择', 'name' => 'productId[]'),
			'proName' => array('type' => 'bttext', "title" => '品名', 'name' => 'proName[]', 'readonly' => true),
			'guige' => array('type' => 'bttext', "title" => '规格', 'name' => 'guige[]', 'readonly' => true),
			'color' => array('type' => 'bttext', "title" => '颜色', 'name' => 'color[]', 'readonly' => true), 
			// 'unit'=>array('type'=>'bttext',"title"=>'单位','name'=>'unit[]','readonly'=>true),
			'pihao' => array('type' => 'bttext', 'title' => '批号', 'name' => 'pihao[]'),
			'cnt' => array('type' => 'bttext', "title" => '数量(吨)', 'name' => 'cnt[]'),
			'danjia' => array('type' => 'bttext', "title" => '单价', 'name' => 'danjia[]'),
			'money' => array('type' => 'bttext', "title" => '金额(元)', 'name' => 'money[]', 'readonly' => true), 
			// 'kuweiId'=>array('type'=>'btkuweiselect','title'=>'库位选择','name'=>'kuweiId[]'),
			'memo' => array('type' => 'bttext', "title" => '备注', 'name' => 'memo[]'), 
			// ***************如何处理hidden?
			'id' => array('type' => 'bthidden', 'name' => 'id[]'),
			); 
		// 表单元素的验证规则定义
		$this->rules = array('chukuDate' => 'required',
			'depId' => 'required',
			'supplierId' => 'required',
			'kuweiId' => 'required',
			'kind' => 'required',
			);


	}
	// **************************************构造函数 end*******************************

	// *****************************保存 begin*******************************
	// /保存
	function actionSave() {
		//dump($_POST);exit;
		$yuanliao_llck2product = array();
		foreach ($_POST['productId'] as $key => $v) {
			// 当没有记录 或者 没有输入数量时 跳出本次循环 防止多保存
			if (empty($_POST['productId'][$key]) || empty($_POST['cnt'][$key])) continue;
			$yuanliao_llck2product[] = array('id' => $_POST['id'][$key], // 主键id
				'productId' => $_POST['productId'][$key], // 产品id
				'cnt' => $_POST['cnt'][$key], // 要货数量
				'pihao' => $_POST['pihao'][$key], 
				// 'unit'=> $_POST['unit'][$key],                //单位
				'danjia' => $_POST['danjia'][$key], // 单价
				'money' => $_POST['money'][$key], // 金额
				'memo' => $_POST['memo'][$key], // 备注
				// 'kuweiId'=>$_POST['kuweiId'],                 //库位
				);
		} 
		// yuanliao_llck 表 的数组
		$yuanliao_llck = array('id' => $_POST['chukuId'], // 主键id
			'chukuCode' => $_POST['chukuCode'], // 出库单号
			'kind' => $_POST['kind'], // 出库类型
			'chukuDate' => $_POST['chukuDate'], // 出库日期
			'supplierId' => $_POST['supplierId'], // 供应商id
			'depId' => $_POST['depId'], // 部门id
			'memo' => $_POST['chukuMemo'], // 入库备注
			'yuanyin' => $_POST['yuanyin'], // 出库原因
			'kuwei' => $_POST['kuweiId'], // 库位
			'orderId' => $_POST['orderId'], // 订单id
			'clientId'=>$_POST['clientId'],//客户id
			// ''=>$_POST[''],
			); 
		// 表之间的关联
		$yuanliao_llck['Pro'] = $yuanliao_llck2product; 
		// 保存 并返回yuanliao_cgrk表的主键
		//dump($yuanliao_llck);exit;
		$itemId = $this->_modelExample->save($yuanliao_llck);
		if ($itemId) {
			if ($_POST['Submit'] == '保存并新增下一个') {
				js_alert('保存成功！', '', $this->_url('add'));
			}else {
				js_alert('保存成功！', '', $this->_url('right'));
			}
		}else die('保存失败!');
	}
	// *****************************保存 end*******************************

// *********************************编辑 begin******************
	function actionEdit() {
		$arr = $this->_modelDefault->find(array('id' => $_GET['id'])); 
		 //dump($arr);exit;
		foreach ($this->fldMain as $k => &$v) {
			$v['value'] = $arr[$k]; 
			// $arr[$k] =
		}

		$this->fldMain['chukuCode']['value'] = $arr['chukuCode']; 
		$this->fldMain['clientId']['clientName'] = $arr['Client']['compName'];
		$this->fldMain['chukuDate']['value'] = $arr['chukuDate'];
		$this->fldMain['supplierId']['value'] = $arr['supplierId'];
		$this->fldMain['depId']['value'] = $arr['depId'];
		$this->fldMain['chukuId']['value'] = $arr['id'];
		$this->fldMain['memo']['value'] = $arr['memo']; 
		// 生成库位信息
		$sql = "select * from jichu_kuwei where 1";
		$rowset = $this->_modelDefault->findBySql($sql); //dump( $rowset);exit;
		foreach($rowset as &$v) {
			$rowsKuwei[] = array('text' => $v['kuweiName'], 'value' => $v['id']);
		}
		$this->fldMain['kuweiId']['options'] = $rowsKuwei; 
		// 加载库位信息的值
		$this->fldMain['kuweiId']['value'] = $arr['kuwei'];

		$areaMain = array('title' => '出库基本信息', 'fld' => $this->fldMain); 
		// 入库明细处理
		foreach($arr['Pro'] as &$v) {
			// dump($v);exit;
			$sql = "select * from jichu_product where id='{$v['productId']}'";
			$_temp = $this->_modelDefault->findBySql($sql); //dump($_temp);exit;
			$v['proCode'] = $_temp[0]['proCode'];
			$v['proName'] = $_temp[0]['proName'];
			$v['guige'] = $_temp[0]['guige'];
			$v['color'] = $_temp[0]['color'];
		} 
		// dump($arr['Products']);exit;
		foreach($arr['Pro'] as &$v) {
			$temp = array();
			foreach($this->headSon as $kk => &$vv) {
				$temp[$kk] = array('value' => $v[$kk]);
			}
			$rowsSon[] = $temp;
		} 
		// 处理$rowsSon 的小数位
		foreach($rowsSon as &$v) {
			$v['cnt']['value'] = round($v['cnt']['value'], 2);
		}

		$smarty = &$this->_getView();
		$smarty->assign('areaMain', $areaMain);
		$smarty->assign('headSon', $this->headSon);
		$smarty->assign('rowsSon', $rowsSon);
		$smarty->assign('rules', $this->rules);
		$smarty->display('Main2Son/T.tpl');
	} 
	// *********************************编辑 end******************

}
?>