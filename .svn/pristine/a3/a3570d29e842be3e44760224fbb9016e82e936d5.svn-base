<?php
FLEA::loadClass('Tmis_Controller');
// / 父类，被Controller_Shengchan_Yuliao_ruku继承
class Controller_Shengchan_Yuliao_Construct extends Tmis_Controller {
	var $fldMain;
	var $headSon;
	var $rules; //表单元素的验证规则
	 
	// /构造函数
	function Controller_Shengchan_Yuliao_Construct() {
		$this->_modelDefault = &FLEA::getSingleton('Model_Yuanliao_Cgrk');
		$this->_modelExample = &FLEA::getSingleton('Model_Yuanliao_Cgrk'); 
		// 定义模板中的主表字段
		$this->fldMain = array(
			// /*******2个一行******
			'rukuDate' => array('title' => '入库日期', "type" => "calendar", 'value' => date('Y-m-d')), 
			// 入库单号，自动生成
			'rukuCode' => array('title' => '入库单号', 'type' => 'text', 'readonly' => true, 'value' => ''), 
			// /*******2个一行******
			// 'supplierpopup需要显示供应商名称，所以需要定义supplierName属性,value属性作为supplierId用
			'supplierId' => array('title' => '供应商', 'type' => 'select', 'value' => '', 'model' => 'Model_Jichu_Supplier'),

			'songhuoCode' => array('title' => '送货单号', 'type' => 'text', 'value' => '', 'readonly' => true),
			'kind' => array('title' => '入库类别', 'type' => 'text', 'value' => '初始化入库', 'readonly' => true),
			'kuweiId' => array('title' => '库位选择', 'type' => 'select', 'value' => ''), 
			// /*******2个一行******
			// 定义了name以后，就不会以memo作为input的id了
			'memo' => array('title' => '采购备注', 'type' => 'textarea', 'disabled' => true, 'name' => 'rukuMemo'), 
			// 下面为隐藏字段
			'ruKuId' => array('type' => 'hidden', 'value' => ''),
			'isGuozhang' => array('type' => 'hidden', 'value' => '0'),
			); 
		// /从表表头信息
		// /type为控件类型,在自定义模板控件
		// /title为表头
		// /name为控件名
		// /bt开头的type都在webcontrolsExt中写的,如果有新的控件，也需要增加
		$this->headSon = array('_edit' => array('type' => 'btBtnRemove', "title" => '+5行', 'name' => '_edit[]'),
			'productId' => array('type' => 'btYuanliaopopup', "title" => '产品选择', 'name' => 'productId[]'),
			'proName' => array('type' => 'bttext', "title" => '品名', 'name' => 'proName[]', 'readonly' => true),
			'guige' => array('type' => 'bttext', "title" => '规格', 'name' => 'guige[]', 'readonly' => true), 
			// 'unit'=>array('type'=>'bttext',"title"=>'单位','name'=>'unit[]','readonly'=>true),
			'pihao' => array('type' => 'bttext', 'title' => '色纱(批号)/坯纱(缸号)', 'name' => 'pihao[]'),
			'cnt' => array('type' => 'bttext', "title" => '数量(公斤)', 'name' => 'cnt[]'), 
			'danjia'=>array('type'=>'bttext',"title"=>'单价','name'=>'danjia[]'),
			'money'=>array('type'=>'bttext',"title"=>'金额(元)','name'=>'money[]','readonly'=>true),
			// 'kuweiId'=>array('type'=>'btkuweiselect','title'=>'库位选择','name'=>'kuweiId[]'),
			'memo' => array('type' => 'bttext', "title" => '备注', 'name' => 'memo[]'), 
			// ***************如何处理hidden?
			'id' => array('type' => 'bthidden', 'name' => 'id[]'),
			); 
		// 表单元素的验证规则定义
		$this->rules = array('rukuDate' => 'required', 
			// 'orderDate'=>'required',
			'supplierId' => 'required', 
			// 'traderId'=>'required'
			);
	}

	function actionInit() {
		// 不用过账
		$isGuozhang = 1;
		$this->actionAdd($isGuozhang);
	}

	function actionAdd($Arr) {
		$this->authCheck('3-2');
		$this->_edit(array(isGuozhang => $Arr));
	} 
	// / 编辑订单基本信息
	function _edit($Arr) {
		// 生成供应商 名称信息
		// $m_jichu_employ=& FLEA::getSingleton('Model_Jichu_Supplier');
		// $sql = "select * from jichu_supplier where 1";
		// $rowset = $m_jichu_employ->findBySql($sql);
		// foreach($rowset as & $v) {
		// // *根据要求：options为数组,必须有text和value属性
		// $rowsSupplier[] = array('text'=>$v['compName'],'value'=>$v['id']);
		// }
		// 生成库位 名称信息
		$m_jichu_kuwei = &FLEA::getSingleton('Model_Jichu_Kuwei');
		$sql = "select * from jichu_kuwei where 1";
		$rowset = $m_jichu_kuwei->findBySql($sql);
		foreach($rowset as &$v) {
			// *根据要求：options为数组,必须有text和value属性
			$rowsKuwei[] = array('text' => $v['kuweiName'], 'value' => $v['id']);
		} 
		// 主表信息字段
		$fldMain = $this->fldMain; 
		// *在主表字段中加载供应商选项*
		// $fldMain['supplierId']['options'] =$rowsSupplier;
		// *在主表字段中加载库位选项*
		$fldMain['kuweiId']['options'] = $rowsKuwei; 
		// *入库号的默认值的加载*
		$fldMain['rukuCode']['value'] = $this->_getNewCode('YR', 'yuanliao_cgrk', 'rukuCode'); 
		// 判断是否需要过账 0要 1否
		$fldMain['isGuozhang']['value'] = $Arr['isGuozhang']; 
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
		$areaMain = array('title' => '入库基本信息', 'fld' => $fldMain); 
		// 从表区域信息描述
		$smarty = &$this->_getView();
		$smarty->assign('areaMain', $areaMain);
		$smarty->assign('headSon', $headSon);
		$smarty->assign('rowsSon', $rowsSon);
		$smarty->assign('rules', $this->rules);
		$smarty->display('Main2Son/T.tpl');
	} 
	// /保存
	function actionSave() {
		//dump($_POST);exit;
		$yuanliao_cgrk2product = array();
		foreach ($_POST['productId'] as $key => $v) {
			// 当没有记录 或者 没有输入数量时 跳出本次循环 防止多保存
			if (empty($_POST['productId'][$key]) || empty($_POST['cnt'][$key])) continue;
			$yuanliao_cgrk2product[] = array('id' => $_POST['id'][$key], // 主键id
				'productId' => $_POST['productId'][$key], // 产品id
				'cnt' => $_POST['cnt'][$key], // 要货数量
				'pihao' => $_POST['pihao'][$key], 
				// 'unit'=> $_POST['unit'][$key],                //单位
				'danjia'=>$_POST['danjia'][$key],             //单价
				'money'=>$_POST['money'][$key],               //金额
				'memo' => $_POST['memo'][$key], // 备注
				// 'kuweiId'=>$_POST['kuweiId'],                 //库位
				);
		} 
		// yuanliao_cgrk 表 的数组
		$yuanliao_cgrk = array('id' => $_POST['ruKuId'], // 主键id
			'rukuCode' => $_POST['rukuCode'], // 入库单号
			'songhuoCode' => $_POST['songhuoCode'], // 送货单号
			'rukuDate' => $_POST['rukuDate'], // 入库日期
			'isGuozhang' => $_POST['isGuozhang'], // 是否过账
			'supplierId' => $_POST['supplierId'], // 供应商id
			'memo' => $_POST['rukuMemo'], // 入库备注
			'kind' => $_POST['kind'], // 入库类型
			'kuwei' => $_POST['kuweiId'], // 库位
			// ''=>$_POST[''],
			); 
		// 表之间的关联
		$yuanliao_cgrk['Products'] = $yuanliao_cgrk2product; 
		// dump($yuanliao_cgrk);exit;
		// 保存 并返回yuanliao_cgrk表的主键
		// dump($yuanliao_cgrk);exit;
		$itemId = $this->_modelExample->save($yuanliao_cgrk);
		if ($itemId) {
			if ($_POST['Submit'] == '保存并新增下一个') {
				js_alert('保存成功！', '', $this->_url('add'));
			}else {
				js_alert('保存成功！', '', $this->_url('right'));
			}
		}else die('保存失败!');
	}
	// **********************************入库查询 begin*************************
	function actionRight() {
		// echo "还没写呢";exit;
		$this->authCheck('3-3');
		FLEA::loadClass('TMIS_Pager'); 
		// /构造搜索区域的搜索类型
		$serachArea = TMIS_Pager::getParamArray(array('dateFrom' => date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-30, date("Y"))),
				'dateTo' => date("Y-m-d"),
				'supplierId' => '', 
				// 'traderId' => '',
				// 'isCheck' => 0,
				// 'rukuCode' =>'',
				'key' => '',
				)); 
		// /查询sql语句
		// $sql = "select *
		// from yuanliao_cgrk";
		// $str = "select
		// x.*,
		// y.compName
		// from (".$sql.") x
		// left join jichu_supplier y on x.supplierId = y.id
		// where 1";
		$sql = "select y.rukuCode,
		               y.rukuDate,
		               y.supplierId,
		               y.memo as rukuMemo,
		               y.isGuozhang,
		               y.kind,
		               x.id,
		               x.ruKuId as ruKuId,
		               x.productId,
		               x.cnt,
		               x.memo 
			               from yuanliao_cgrk2product x
	                       left join yuanliao_cgrk y on (x.ruKuId = y.id)";

		$str = "select
				x.ruKuId,
				x.rukuCode,
				x.rukuDate,
				x.supplierId,
				x.productId,
				x.cnt,
				x.rukuMemo,
				x.memo,
				x.isGuozhang,
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

		$str .= " and rukuDate >= '$serachArea[dateFrom]' and rukuDate<='{$serachArea[dateTo]}'";
		if ($serachArea['key'] != '') $str .= " and (x.rukuCode like '%{$serachArea[key]}%'
											or z.proName like '%{$serachArea[key]}%'
											or z.proCode like '%{$serachArea[key]}%'
											or z.guige like '%{$serachArea[key]}%')"; 
		// if($serachArea['isCheck'] != '')   $str .= " and x.isCheck = '{$serachArea[isCheck]}'";
		if ($serachArea['rukuCode'] != '') $str .= " and x.rukuCode like '%{$serachArea[rukuCode]}%'";
		if ($serachArea['supplierId'] != '') $str .= " and x.supplierId = '{$serachArea[supplierId]}'"; 
		// if ($serachArea['traderId'] != '') $str .= " and x.traderId = '{$serachArea[traderId]}'";
		$str .= " order by rukuDate desc, rukuCode desc";

		$pager = &new TMIS_Pager($str);
		$rowset = $pager->findAll(); 
		// dump($rowset);exit;
		// $yuanliao_cgrk2product = & FLEA::getSingleton('Model_Yuanliao_Cgrk2product');
		if (count($rowset) > 0) foreach($rowset as &$value) {
			// dump($value);exit;
			$value['_edit'] = "<a href='" . $this->_url('View', array('id' => $value['id'])) . "' target='_blank' title='$title'>打印</a> | ";
			if ($value["guozhangId"]) {
				$tip = "ext:qtip='已过账禁止修改'";
				$value['_edit'] .= "<a href='javascript:void(0)' style='color:black' $tip>修改</a> | ";
				$value['_edit'] .= "<a $tip  >删除</a>";
			}else {
				$url = url($value['kind'] == '初始化入库'?'Shengchan_Yuliao_Construct':'Shengchan_Yuliao_ruku', 'edit', array('id' => $value['ruKuId']));
				$value['_edit'] .= "<a href='{$url}'>修改</a> |"; 
				// $value['_edit'].="<a href='".$this->_url('Edit',array('id'=>$value['ruKuId']))."'>修改</a> | ";
				$value['_edit'] .= "<a href='" . $this->_url('Remove', array('id' => $value['ruKuId'])) . "'  onclick=\"return confirm('确认删除吗?')\" >删除</a>";
			} 
			// 设定颜色
			if ($value['isGuozhang'] == 1) {
				$value['_bgColor'] = "lightgreen";
			}

			$value['cnt'] = round($value['cnt'], 2);
		} 
		// 合计行
		$heji = $this->getHeji($rowset, array('cnt'), '_edit');
		$rowset[] = $heji; 
		// dump($rowset);exit;
		// 左边信息
		$arrFieldInfo = array("_edit" => '操作',
			"rukuDate" => "入库日期",
			'rukuCode' => '入库单号',
			"compName" => "供应商",
			'proCode' => '产品编码',
			'proName' => '品名',
			'zhonglei' => '种类',
			'guige' => '规格',
			'color' => '颜色',
			'cnt' => '数量', 
			// ''=>'',
			);

		$smarty = &$this->_getView();
		$smarty->assign('title', '订单查询'); 
		// $smarty->assign('pk', $this->_modelDefault->primaryKey);
		$smarty->assign('arr_field_info', $arrFieldInfo);
		$smarty->assign('add_display', 'none');
		$smarty->assign('arr_condition', $serachArea);
		$smarty->assign('arr_field_value', $rowset);
		$smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action']), $serachArea) . "<font color='green'><p>绿色表示初始化<p></font>");
		$smarty->assign('arr_js_css', $this->makeArrayJsCss(array('grid', 'calendar')));
		$smarty->display('TableList.tpl'); 
		// $smarty->display('TblListMore.tpl');
	}
	// **********************************入库查询 end************************* 
	// *********************************编辑 begin******************
	function actionEdit() {
		// echo '123';exit;
		$arr = $this->_modelDefault->find(array('id' => $_GET['id'])); 
		//dump($arr);exit;
		foreach ($this->fldMain as $k => &$v) {
			$v['value'] = $arr[$k]; 
			// $arr[$k] =
		}

		$this->fldMain['rukuCode']['value'] = $arr['rukuCode']; 
		// $this->fldMain['orderId']['orderMemo'] = $arr['memo'];
		$this->fldMain['rukuDate']['value'] = $arr['rukuDate'];
		$this->fldMain['supplierId']['value'] = $arr['supplierId'];
		$this->fldMain['songhuoCode']['value'] = $arr['songhuoCode'];
		$this->fldMain['ruKuId']['value'] = $arr['id'];
		$this->fldMain['isGuozhang']['value'] = $arr['isGuozhang'];
		$this->fldMain['memo']['value'] = $arr['memo'];
		$this->fldMain['kuweiId']['value'] = $arr['kuwei']; 
		// 生成供应商信息
		$sql = "select * from jichu_supplier where 1";
		$rowset = $this->_modelDefault->findBySql($sql); //dump( $rowset);exit;
		foreach($rowset as &$v) {
			$rowsSupplier[] = array('text' => $v['compName'], 'value' => $v['id']);
		}
		$this->fldMain['supplierId']['options'] = $rowsSupplier; 
		// 生成库位信息
		$sql = "select * from jichu_kuwei where 1";
		$rowset = $this->_modelDefault->findBySql($sql); //dump( $rowset);exit;
		foreach($rowset as &$v) {
			$rowsKuwei[] = array('text' => $v['kuweiName'], 'value' => $v['id']);
		}
		$this->fldMain['kuweiId']['options'] = $rowsKuwei; 
		// //加载库位信息的值
		// $this->fldMain['kuweiId']['value']=$arr['Products'][0]['kuweiId'];
		$areaMain = array('title' => '入库基本信息', 'fld' => $this->fldMain); 
		// dump($areaMain);exit;
		// 入库明细处理
		// dump($arr['Products']);exit;
		foreach($arr['Products'] as &$v) {
			// dump($v);exit;
			$sql = "select * from jichu_product where id='{$v['productId']}'";
			$_temp = $this->_modelDefault->findBySql($sql); //dump($_temp);exit;
			$v['proCode'] = $_temp[0]['proCode'];
			$v['proName'] = $_temp[0]['proName'];
			$v['guige'] = $_temp[0]['guige'];
		} 
		// dump($arr['Products']);exit;
		foreach($arr['Products'] as &$v) {
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
	// **************************打印 begin*******************
	function actionView() {
		$yuanliao_cgrk2product = &FLEA::getSingleton('Model_Yuanliao_Cgrk2product');
		$rowset = $yuanliao_cgrk2product->find($_GET['id']); 
		// dump($rowset);exit;
		$smarty = &$this->_getView();
		$smarty->assign("arr_field_value", $rowset);
		$smarty->display('Yuanliao/RukuView.tpl');
	}
	// **************************打印 end******************* 
	// 编辑界面利用ajax删除
	function actionRemoveByAjax() {
		$m = &FLEA::getSingleton('Model_Yuanliao_Cgrk2product');
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

	//弹出窗口选择
	function actionPopup(){
		FLEA::loadClass('TMIS_Pager');
		// /构造搜索区域的搜索类型
		$serachArea = TMIS_Pager::getParamArray(
			array(
				'dateFrom' => date("Y-m-d", mktime(0, 0, 0, date("m")-1, date("d"), date("Y"))),
				'dateTo' => date("Y-m-d"),
				'supplierId' => '',
				'proName' => '',
				'guige' => '',
				'pihao' => '',
		)); 

		//查找数据
		$sql = "select y.rukuCode,
		               y.rukuDate,
		               y.supplierId,
		               y.memo as rukuMemo,
		               y.isGuozhang,
		               y.kind,
		               y.kuwei,
		               x.pihao,
		               x.id,
		               x.ruKuId,
		               x.productId,
		               x.cnt,
		               x.danjia,
		               x.money,
		               x.memo 
			               from yuanliao_cgrk2product x
	                       left join yuanliao_cgrk y on (x.ruKuId = y.id)";

		$str = "select
				x.ruKuId,
				x.rukuCode,
				x.rukuCode as textValue,
				x.rukuDate,
				x.supplierId,
				x.productId,
				x.cnt,
				x.danjia,
                x.money,
				x.rukuMemo,
				x.memo,
				x.isGuozhang,
				x.id,
				x.id as ruku2ProId,
				x.kind,
				x.pihao,
				y.compName,
				z.proCode,
				z.proName,
				z.zhonglei,
				z.guige,
				z.color,
				b.kuweiName,
				x.kuwei,
				'色坯纱入库过账' as kind
				from (" . $sql . ") x
				left join jichu_supplier y on x.supplierId = y.id
				left join jichu_product z on x.productId = z.id
				left join jichu_kuwei b on b.id=x.kuwei
				left join caiwu_yf_guozhang a on a.kind='色坯纱入库过账' and a.ruku2ProId=x.id
                where 1 and x.isGuozhang=0 and a.id is null";

		if($serachArea['dateFrom']!=''){
			$str .= " and x.rukuDate >= '{$serachArea['dateFrom']}' and x.rukuDate<='{$serachArea['dateTo']}'";
		}
		if ($serachArea['proName'] != '') $str .= " and z.proName like '%{$serachArea['proName']}%'";
		if ($serachArea['guige'] != '') $str .= " and z.guige like '%{$serachArea['guige']}%'";
		if ($serachArea['pihao'] != '') $str .= " and z.pihao like '%{$serachArea['pihao']}%'"; 
		if ($serachArea['rukuCode'] != '') $str .= " and x.rukuCode like '%{$serachArea['rukuCode']}%'";
		if ($serachArea['supplierId'] != '') $str .= " and x.supplierId = '{$serachArea['supplierId']}'";
		$str .= " order by x.rukuDate desc, rukuCode desc";
		// echo $str;exit;
		$pager = &new TMIS_Pager($str);
		$rowset = $pager->findAll(); 

		if (count($rowset) > 0) foreach($rowset as &$value) {
			$value['cnt'] = round($value['cnt'], 2);
			$temp=array();
			if($value['proName']!='')$temp[]='品名：'.$value['proName'];
			if($value['guige']!='')$temp[]='规格：'.$value['guige'];
			if($value['pihao']!='')$temp[]='批号/缸号:'.$value['pihao'];
			$value['qitaMemo']=join(' ',$temp);
		} 
		// 合计行
		// $heji = $this->getHeji($rowset, array('cnt'), '_edit');
		// $rowset[] = $heji; 
		// dump($rowset);exit;
		// 左边信息
		$arrFieldInfo = array(
			// 'ruKuId'=>'入库',
			"rukuDate" => "入库日期",
			'rukuCode' => '入库单号',
			"compName" => "供应商",
			'proCode' => '产品编码',
			// 'proName' => '品名',
			// 'guige' => '规格',
			'qitaMemo' => '描述',
			'cnt' => '数量', 
			'danjia'=>'单价',
			'money'=>'金额',
			'kuweiName'=>'库位',
			);

		$smarty = &$this->_getView();
		$smarty->assign('title', '订单查询'); 
		// $smarty->assign('pk', $this->_modelDefault->primaryKey);
		$smarty->assign('arr_field_info', $arrFieldInfo);
		$smarty->assign('add_display', 'none');
		$smarty->assign('arr_condition', $serachArea);
		$smarty->assign('arr_field_value', $rowset);
		$smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action']), $serachArea));
		$smarty->display('Popup/CommonNew.tpl'); 
	}
}

?>