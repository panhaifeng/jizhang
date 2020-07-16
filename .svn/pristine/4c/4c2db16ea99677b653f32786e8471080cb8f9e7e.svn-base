<?php
FLEA::loadClass('TMIS_Controller');
class Controller_Shengchan_Yuliao_ConstructChuku extends Tmis_Controller {
	var $fldMain;
	var $headSon;
	var $rules; //表单元素的验证规则
	
	// **************************************构造函数 begin********************************
	function Controller_Shengchan_Yuliao_ConstructChuku() {
		$this->_modelDefault = &FLEA::getSingleton('Model_Yuanliao_Llck');
		$this->_modelExample = &FLEA::getSingleton('Model_Yuanliao_Llck'); 
		// 生产领用,销售出库,其他出库
		// 定义模板中的主表字段
		$this->fldMain = array('chukuDate' => array('title' => '出库日期', "type" => "calendar", 'value' => date('Y-m-d')), 
			// 入库单号，自动生成
			'chukuCode' => array('title' => '出库单号', 'type' => 'text', 'readonly' => true, 'value' => ''),

			'kind' => array('title' => '出库类别', 'type' => 'select', 'options' => array(
					array('text' => '销售出库', 'value' => '销售出库'),
					array('text' => '生产领用', 'value' => '生产领用'),
					array('text' => '发外加工', 'value' => '发外加工'),
					array('text' => '其他出库', 'value' => '其他出库'),
					)), 
			// 'supplierpopup需要显示供应商名称，所以需要定义supplierName属性,value属性作为supplierId用
			'supplierId' => array('title' => '供应商', 'type' => 'select', 'value' => '', 'model' => 'Model_Jichu_Supplier'),

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
	// **************************************构造函数 end********************************
	function actionAdd($Arr) {
		$this->authCheck('3-4'); 
		// 生成供应商 名称信息
		$m_jichu_employ = &FLEA::getSingleton('Model_Jichu_Supplier');
		$sql = "select * from jichu_supplier where 1";
		$rowset = $m_jichu_employ->findBySql($sql);
		foreach($rowset as &$v) {
			// *根据要求：options为数组,必须有text和value属性
			$rowsSupplier[] = array('text' => $v['compName'], 'value' => $v['id']);
		} 
		// 生成库位 名称信息
		$m_jichu_kuwei = &FLEA::getSingleton('Model_Jichu_Kuwei');
		$sql = "select * from jichu_kuwei where 1";
		$rowset = $m_jichu_kuwei->findBySql($sql);
		foreach($rowset as &$v) {
			// *根据要求：options为数组,必须有text和value属性
			$rowsKuwei[] = array('text' => $v['kuweiName'], 'value' => $v['id']);
		} 
		// 生成部门 名称信息
		$m_jichu_dept = &FLEA::getSingleton('Model_Jichu_Department');
		$sql = "select * from jichu_department where 1";
		$rowset = $m_jichu_dept->findBySql($sql);
		foreach ($rowset as &$v) {
			// *根据要求：options为数组,必须有text和value属性
			$rowsDept[] = array('text' => $v['depName'], 'value' => $v['id']);
		} 
		// 主表信息字段
		$fldMain = $this->fldMain; 
		// *在主表字段中加载供应商选项*
		$fldMain['supplierId']['options'] = $rowsSupplier; 
		// *在主表字段中加载库位选项*
		$fldMain['kuweiId']['options'] = $rowsKuwei; 
		// *入库号的默认值的加载*
		$fldMain['chukuCode']['value'] = $this->_getNewCode('YC', 'yuanliao_llck', 'chukuCode'); 
		// *部门信息 的加载*
		$fldMain['depId']['options'] = $rowsDept; 
		// dump($fldMain);exit;
		$headSon = $this->headSon; 
		// 从表信息字段,默认5行
		for($i = 0;$i < 5;$i++) {
			$rowsSon[] = array(
				// 'proCode'=>array('value'=>''),                //产品编码（中文）
				// 'proName'=>array('value'=>''),               //产品名称（英文与数字）
				// 'guige'=>array('value'=>''),                 //规格
				// 'pihao'=>array('value'=>''),                  //批号
				// 'cnt'=>array('value'=>''),                   //数量
				// 'danjia'=>array('value'=>''),                //单价
				// 'money'=>array('value'=>''),                 //金额
				// 'memo'=>array('value'=>''),                  //备注
				// 'kuweiName'=>array('value'=>''),              //库位名称
				// //从表中有时需要hidden控件的。
				// 'id'=>array('value'=>''),
				// 'productId'=>array('value'=>''),
				);
		} 
		// 主表区域信息描述
		$areaMain = array('title' => '出库基本信息', 'fld' => $fldMain); 
		// 从表区域信息描述
		$smarty = &$this->_getView();
		$smarty->assign('areaMain', $areaMain);
		$smarty->assign('headSon', $headSon);
		$smarty->assign('rowsSon', $rowsSon);
		$smarty->assign('rules', $this->rules);
		$smarty->display('Main2Son/T.tpl');
	} 
	// / 编辑订单基本信息
	function _edit($Arr) {
	} 
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
			//'clientId'=>$_POST['clientId'],//客户id
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
	// **********************************入库查询 begin*************************
	function actionRight() {
		// echo '查询中。。。';exit;
		$this->authCheck('3-6');
		FLEA::loadClass('TMIS_Pager'); 
		// /构造搜索区域的搜索类型
		$serachArea = TMIS_Pager::getParamArray(array('dateFrom' => date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-30, date("Y"))),
				'dateTo' => date("Y-m-d"),
				'supplierId' => '', 
				// 'traderId' => '',
				// 'isCheck' => 0,
				'key' => '',
				));

		$sql = "select y.chukuCode,
             y.chukuDate,
             y.supplierId,
             y.kind,
             x.id,
             x.chukuId as chukuId,
             x.productId,
             x.cnt,
             x.danjia,
             x.money,
             x.pihao 
               from yuanliao_llck2product x
                   left join yuanliao_llck y on (x.chukuId = y.id)";

		$str = "select
				x.chukuId,
				x.chukuCode,
				x.chukuDate,
				x.supplierId,
				x.productId,
				x.cnt,
				x.id,
				x.kind,
				y.compName,
				z.proCode,
				z.proName,
				z.zhonglei,
				z.guige,
				z.color
				from (" . $sql . ") x
				left join jichu_supplier y on x.supplierId = y.id
				left join jichu_product z on x.productId = z.id
                where 1";

		$str .= " and chukuDate >= '$serachArea[dateFrom]' and chukuDate<='{$serachArea[dateTo]}'";
		if ($serachArea['key'] != '') $str .= " and (x.rukuCode like '%{$serachArea[key]}%'
													or z.proName like '%{$serachArea[key]}%'
													or z.proCode like '%{$serachArea[key]}%'
													or z.guige like '%{$serachArea[key]}%')";
		if ($serachArea['supplierId'] != '') $str .= " and x.supplierId = '{$serachArea[supplierId]}'";
		$str .= " order by chukuDate desc, chukuCode desc";

		$pager = &new TMIS_Pager($str);
		$rowset = $pager->findAll();

		if (count($rowset) > 0) foreach($rowset as &$value) {
			// dump($value);exit;
			$value['_edit'] = "<a href='" . $this->_url('View', array('id' => $value['id'])) . "' target='_blank' title='$title'>打印</a> | ";
			if ($value["guozhangId"]) {
				$tip = "ext:qtip='已过账禁止修改'";
				$value['_edit'] .= "<a href='javascript:void(0)' style='color:black' $tip>修改</a> | ";
				$value['_edit'] .= "<a $tip  >删除</a>";
			}else {
                $url = url($value['kind']!= '采购出库'?'Shengchan_Yuliao_ConstructChuku':'Shengchan_Yuliao_Chuku', 'edit', array('id' => $value['chukuId']));
				$value['_edit'] .= "<a href='{$url}'>修改</a> |"; 
				// $value['_edit'].="<a href='".$this->_url('Edit',array('id'=>$value['ruKuId']))."'>修改</a> | ";
				$value['_edit'] .= "<a href='" . $this->_url('Remove', array('id' => $value['chukuId'])) . "'  onclick=\"return confirm('确认删除吗?')\" >删除</a>";
			}

			// 设定颜色
			if ($value['kind'] == '采购出库') {
				$value['_bgColor'] = "lightgreen";
			}
		} 
		// 合计行
		$heji = $this->getHeji($rowset, array('cnt'), '_edit');
		$rowset[] = $heji; 
		// 显示信息
		$arrFieldInfo = array("_edit" => '操作',
			"chukuDate" => "入库日期",
			'chukuCode' => '入库单号',
			"compName" => "供应商",
			'proCode' => '产品编码',
			'proName' => '品名',
			'zhonglei' => '种类',
			'guige' => '规格',
			'color' => '颜色',
			'cnt' => '数量', 
			'kind'=>'类型',
			// ''=>'',
			);

		$smarty = &$this->_getView();
		$smarty->assign('title', '订单查询'); 
		// $smarty->assign('pk', $this->_modelDefault->primaryKey);
		$smarty->assign('arr_field_info', $arrFieldInfo);
		$smarty->assign('add_display', 'none');
		$smarty->assign('arr_condition', $serachArea);
		$smarty->assign('arr_field_value', $rowset);
		$smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action']), $serachArea) . "<font color='green'><p>绿色表示采购出库<p></font>");
		$smarty->assign('arr_js_css', $this->makeArrayJsCss(array('grid', 'calendar')));
		$smarty->display('TableList.tpl');
	}
	// **********************************入库查询 end************************* 
	// *********************************编辑 begin******************
	function actionEdit() {
		$arr = $this->_modelDefault->find(array('id' => $_GET['id'])); 
		 //dump($arr);exit;
		foreach ($this->fldMain as $k => &$v) {
			$v['value'] = $arr[$k]; 
			// $arr[$k] =
		}

		$this->fldMain['chukuCode']['value'] = $arr['chukuCode']; 
		// $this->fldMain['orderId']['orderMemo'] = $arr['memo'];
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
	// ***************************库存报表 begin***************
	function actionReport() {
		// echo "123";exit;
		$this->authCheck('3-6');
		FLEA::loadClass("TMIS_Pager");
		$arr = &TMIS_Pager::getParamArray(array("dateFrom" => date('Y-m-01'),
				"dateTo" => date('Y-m-d'), 
				//"supplierId" =>"",
				// "proName"=>"",
				// "guige"  =>''
				)); 
		$strCon = " and 1";
		$strGroup = "kuwei,supplierId,productId";
		$sqlUnion = "select {$strGroup},
			sum(cntFasheng) as cntInit,
			sum(moneyFasheng) as moneyInit,
			0 as cntRuku,0 as moneyRuku,0 as cntChuku,0 as moneyChuku
			from `yuanliao_kucun` where dateFasheng<'{$arr['dateFrom']}' 
			 {$strCon} group by {$strGroup} 
			union 
			select {$strGroup},
			0 as cntInit,0 as moneyInit,
			sum(cntFasheng) as cntRuku,
			sum(moneyFasheng) as moneyRuku,
			0 as cntChuku,0 as moneyChuku
			from `yuanliao_kucun` where 
			dateFasheng>='{$arr['dateFrom']}' and dateFasheng<='{$arr['dateTo']}'
			and rukuId>0  {$strCon} group by {$strGroup} 
			union 
			select {$strGroup},
			0 as cntInit,0 as moneyInit,
			0 as cntRuku,
			0 as moneyRuku,
			sum(cntFasheng*-1) as cntChuku,
			sum(moneyFasheng*-1) as moneyChuku 
			from `yuanliao_kucun` where 
			dateFasheng>='{$arr['dateFrom']}' and dateFasheng<='{$arr['dateTo']}'
			and chukuId>0  {$strCon} group by {$strGroup}";

		$sql = "select 
			{$strGroup},
			y.proCode,y.proName,y.guige,y.color,
			z.compName as supplierName,
			sum(cntInit) as cntInit,
			sum(moneyInit) as moneyInit,
			sum(cntRuku) as cntRuku,
			sum(moneyRuku) as moneyRuku,
			sum(cntChuku) as cntChuku,
			sum(moneyChuku) as moneyChuku
			from ({$sqlUnion}) as x
			left join jichu_product y on x.productId=y.id
			left join jichu_supplier z on x.supplierId=z.id
			group by {$strGroup}
			having sum(cntInit)<>0 or sum(moneyInit)<>0 
			or sum(cntRuku)<>0 or sum(moneyRuku)<>0
			or sum(cntChuku)<>0 or sum(moneyChuku)<>0"; 
 
		$pager = &new TMIS_Pager($sql);
		$rowset = $pager->findAll();
		foreach($rowset as &$v) {
			$v['cntKucun'] = round($v['cntInit'] + $v['cntRuku'] - $v['cntChuku'], 2);
		} 
		// 显示信息
		$arrFieldInfo = array("supplierName" => '供应商',
			"proCode" => "产品编码",
			'kuwei'=>'kuwei',
			'proName' => '品名',
			"guige" => "规格",
			'color' => '颜色', 
			// 'pihao'=>'批号',
			'cntInit' => '期初',
			'cntRuku' => '本期入库',
			'cntChuku' => '本期出库',
			'cntKucun' => '余存', 
			// 'cnt'=>'数量',
			);

		$smarty = &$this->_getView();
		$smarty->assign('title', '收发存报表'); 
		// $smarty->assign('pk', $this->_modelDefault->primaryKey);
		$smarty->assign('arr_field_info', $arrFieldInfo);
		$smarty->assign('add_display', 'none');
		$smarty->assign('arr_condition', $arr);
		$smarty->assign('arr_field_value', $rowset);
		$smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action']), $arr));
		$smarty->assign('arr_js_css', $this->makeArrayJsCss(array('grid', 'calendar')));
		$smarty->display('TableList.tpl');
	}
	// ***************************库存报表 end***************
	// **************************打印 begin*******************
	function actionView() {
		// dump($_GET['id']);exit;
		$yuanliao_llck2product = &FLEA::getSingleton('Model_Yuanliao_Llck2product');
		$rowset = $yuanliao_llck2product->find($_GET['id']); 
		// dump($rowset);exit;
		$smarty = &$this->_getView();
		$smarty->assign("arr_field_value", $rowset);
		$smarty->display('Yuanliao/ChukuView.tpl');
	}
	// **************************打印 end******************* 
	// 编辑界面利用ajax删除
	function actionRemoveByAjax() {
		$m = &FLEA::getSingleton('Model_Yuanliao_Llck2product');
		$r = $m->removeByPkv($_POST['id']);
		if (!$r) {
			$arr = array('success' => false, 'msg' => '删除失败');
			echo json_encode($arr);
			exit;
		}
		$arr = array('success' => true);
		echo json_encode($arr);
		exit;
	}
}

?>