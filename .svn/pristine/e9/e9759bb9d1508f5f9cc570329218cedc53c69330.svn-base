<?php
FLEA::loadClass('Tmis_Controller');
class Controller_Shengchan_Chengpin_Ruku extends Tmis_Controller {
	var $fldMain;
	var $headSon;
	var $rules; //表单元素的验证规则

	function Controller_Shengchan_Chengpin_Ruku(){
        $this->_modelDefault = &FLEA::getSingleton('Model_Chengpin_Cprk');
		$this->_modelExample = &FLEA::getSingleton('Model_Chengpin_Cprk'); 
		// 定义模板中的主表字段
		$this->fldMain = array(
			'cprkDate' => array('title' => '入库日期', "type" => "calendar", 'value' => date('Y-m-d')), 
			// 入库单号，自动生成
			'cprkCode' => array('title' => '入库单号', 'type' => 'text', 'readonly' => true, 'value' => ''), 
			'orderCode'=>array('title'=>'订单编号','type'=>'text','readonly' => true, 'value' => ''),

			'supplierId' => array('title' => '供应商', 'type' => 'select', 'value' => '', 'model' => 'Model_Jichu_Supplier'),

			'kind' => array('title' => '入库类别', 'type' => 'select', 'options' => array(
				array('text' => '正常入库', 'value' => '正常入库'),
				// array('text' => '其他1', 'value' => '其他1'),
				// array('text' => '其他2', 'value' => '其他2'),
				)),
			
			'unit'=>array('title'=>'入库单位','type'=>'select','options'=>array(
                 array('text'=>'米','value'=>'米'),
                 array('text'=>'码','value'=>'码'),
				)),
			
			// 定义了name以后，就不会以memo作为input的id了
			'memo' => array('title' => '备注', 'type' => 'textarea', 'disabled' => true, 'name' => 'rukuMemo'), 
			// 下面为隐藏字段
			'rukuId' => array('type' => 'hidden', 'value' => ''),
			'orderId'=> array('type' => 'hidden', 'value' => ''),

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
			'cntYaohuo' => array('type' => 'bttext', "title" => '要货数量', 'name' => 'cntYaohuo[]','readonly' => true),
            'cntDuan'=>array('type' => 'bttext', "title" => '卷/包数', 'name' => 'cntDuan[]'),
            'cnt' => array('type' => 'bttext', "title" => '数量', 'name' => 'cnt[]'),
			'dajuanKind'=>array('type'=>'btdajuanselect','title'=>'打卷方式','name'=>'dajuanKind[]'),
			//'pihao' => array('type' => 'bttext', 'title' => '批号', 'name' => 'pihao[]'),
			//'danjia' => array('type' => 'bttext', "title" => '单价', 'name' => 'danjia[]'),
			//'money' => array('type' => 'bttext', "title" => '金额(元)', 'name' => 'money[]', 'readonly' => true), 
			// 'kuweiId'=>array('type'=>'btkuweiselect','title'=>'库位选择','name'=>'kuweiId[]'),
			'memo' => array('type' => 'bttext', "title" => '备注', 'name' => 'memo[]'), 
			'id' => array('type' => 'bthidden', 'name' => 'id[]'),
			'ord2proId'=>array('type'=>'bthidden','name'=>'ord2proId[]'),
			); 
		// 表单元素的验证规则定义
		$this->rules = array('chukuDate' => 'required',
			//'depId' => 'required',
			'supplierId' => 'required',
			//'kuweiId' => 'required',
			'kind' => 'required',
			'unit'=>'required',
			);
	}

	function actionListForAdd(){
        $this->authCheck('9-2'); 
        FLEA::loadClass('TMIS_Pager'); 
        ///构造搜索栏
        $arr = TMIS_Pager::getParamArray(array(
			//'dateFrom'=>date('Y-m-d',mktime(0,0,0,date('m'),date('d')-30,date('Y'))),
			//'dateTo'=>date('Y-m-d'),
			'orderCode'=>'',
			// 'proGuige'=>'',
			// 'huaxing'=>''
		));

	    $str = "select
				x.orderCode,
				x.orderDate,
				x.clientId,
				x.traderId,
				x.clientOrder,
				x.isCheck,
				x.id,
				sum(y.cntYaohuo) as cntTotal,
				y.orderId,
				y.productId,
				z.kind
				from trade_order x
				left join trade_order2product y on y.orderId = x.id
				left join jichu_product z on y.productId=z.id
                where 1 and x.isCheck=1 and z.kind in ('针织','其他')";
        
        if($arr['orderCode']!='') $str.=" and x.orderCode like '%{$arr['orderCode']}%'";
        $str .= " group by x.id order by orderDate desc, orderCode desc";

        $pager =& new TMIS_Pager($str);
	    $rowset =$pager->findAll();
       
        if (count($rowset) > 0) foreach($rowset as &$value) {
			// dump($value);exit;
			$value['_edit'] = "<a href='".$this->_url('AddByAjax',array(
				'orderId'=>$value['orderId'],
				'fromAction'=>$_GET['action'],
				//'dajuanKind'=>$dajuanKind,
				))."'>入库</a>";
			$value['cntTotal']=round($value['cntTotal'],2);
			//$v['orderCode']=$this->getOrderTrace(($v['orderCode']), $v['orderId']);
			//修改要货数格式
			//$v['cntYaohuo'].='&nbsp;'.$v['unit'];
		}

		// 显示信息
		$arrFieldInfo = array("_edit" => '操作',
			"orderDate" => "订单日期",
			'orderCode' => '订单编号',
			'cntTotal'=>'要货总数',
			// ''=>'',
			);

		$smarty = &$this->_getView();
		$smarty->assign('title', '订单查询'); 
		// $smarty->assign('pk', $this->_modelDefault->primaryKey);
		$smarty->assign('arr_field_info', $arrFieldInfo);
		$smarty->assign('add_display', 'none');
		$smarty->assign('arr_condition', $arr);
		$smarty->assign('arr_field_value', $rowset);
		$smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action']), $arr));
		$smarty->assign('arr_js_css', $this->makeArrayJsCss(array('grid', 'calendar')));
		$smarty->display('TableList.tpl'); 

	}
     


	//添加新的入库登记，通过弹出窗口的形式来加载
	function actionAddByAjax(){
		//根据传递过来的orderid 找到对应的明细信息 $_GET['orderId']
        $Trade_Order2Product = &FLEA::getSingleton('Model_Trade_Order2Product');
        $res=$Trade_Order2Product->findAll(array('orderId'=>$_GET['orderId']));
        //dump($res);exit;
        //加载fldMain中的信息
        $this->fldMain['orderId']['value']=$res[0]['orderId'];
        $this->fldMain['orderCode']['value']=$res[0]['Order']['orderCode'];
        $this->fldMain['cprkCode']['value']=$this->_getNewCode('CR', 'chengpin_cprk', 'cprkCode'); 
        
        foreach($res as $k=>&$v){
            $temp = array();
			foreach($this->headSon as $kk => &$vv) {
				$temp[$kk] = array('value' => $v['Products'][$kk]);
			}
			$temp['productId']=array('value'=>$v['productId']);
			$temp['cntYaohuo']=array('value'=>$v['cntYaohuo']);
            $temp['ord2proId']=array('value'=>$v['id']);
            $temp['id']=array('value'=>'');
			$rowsSon[] = $temp;
        }
        //dump($rowsSon);exit;
        // 主表信息字段
		$fldMain = $this->fldMain; 
        $headSon = $this->headSon;
		
		// 主表区域信息描述
		$areaMain = array('title' => '成品入库基本信息', 'fld' => $fldMain); 
		// 从表区域信息描述
		$smarty = &$this->_getView();
		$smarty->assign('areaMain', $areaMain);
		$smarty->assign('headSon', $headSon);
		$smarty->assign('rowsSon', $rowsSon);
		$smarty->assign('rules', $this->rules);
		$smarty->display('Main2Son/T.tpl');
	}


	function actionSave(){
        //dump($_POST);exit;
        $chengpin_cprk2product = array();
		foreach ($_POST['productId'] as $key => $v) {
			// 当没有记录 或者 没有输入数量时 跳出本次循环 防止多保存
			if (empty($_POST['productId'][$key]) || empty($_POST['cnt'][$key])) continue;
			$chengpin_cprk2product[] = array('id' => $_POST['id'][$key], // 主键id
				'productId' => $_POST['productId'][$key],                // 产品id
				'cnt' => $_POST['cnt'][$key],                            // 要货数量
				'dajuanKind' => $_POST['dajuanKind'][$key],              //打卷方式
				'cntDuan'=> $_POST['cntDuan'][$key],                     //段数
				'ord2proId'=>$_POST['ord2proId'][$key],                  //订单明细id
				'memo' => $_POST['memo'][$key],                          // 备注
				'unit'=>$_POST['unit'],                                  //计量单位
				// 'kuweiId'=>$_POST['kuweiId'][$key],                 //库位
				);
		} 
		//dump($chengpin_cprk2product);exit;
        // yuanliao_cgrk 表 的数组
		$chengpin_cprk = array('id' => $_POST['ruKuId'], // 主键id
			'cprkCode' => $_POST['cprkCode'], // 入库单号
			//'songhuoCode' => $_POST['songhuoCode'], // 送货单号
			'cprkDate' => $_POST['cprkDate'], // 入库日期
			'orderId' => $_POST['orderId'], // 订单id
			'supplierId' => $_POST['supplierId'], // 供应商id
			'memo' => $_POST['rukuMemo'], // 入库备注
			'kind' => $_POST['kind'], // 入库类型
			//'kuwei' => $_POST['kuweiId'], // 库位
			// ''=>$_POST[''],
			); 
		// 表之间的关联
		$chengpin_cprk['Products'] = $chengpin_cprk2product; 
		// dump($yuanliao_cgrk);exit;
		// 保存 并返回yuanliao_cgrk表的主键
		// dump($yuanliao_cgrk);exit;
		$itemId = $this->_modelExample->save($chengpin_cprk);
		if ($itemId) {
			if ($_POST['Submit'] == '保存并新增下一个') {
				js_alert('保存成功！', '', $this->_url('add'));
			}else {
				js_alert('保存成功！', '', $this->_url('right'));
			}
		}else die('保存失败!');
	}

    function actionRight(){
    	$this->authCheck('9-3');
        FLEA::loadClass('TMIS_Pager'); 
        
       	$serachArea = TMIS_Pager::getParamArray(array('dateFrom' => date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-30, date("Y"))),
				'dateTo' => date("Y-m-d"),
				'supplierId' => '', 
				// 'traderId' => '',
				// 'isCheck' => 0,
				'orderCode'=>'',
				'key' => '',
				));
        
        $sql = "select y.cprkCode,
		               y.cprkDate,
		               y.supplierId,
		               y.orderId,
		               y.kind,
		               x.id,
		               x.rukuId as rukuId,
		               x.cntDuan,
		               x.unit,
		               x.productId,
		               x.ord2proId,
		               x.dajuanKind,
		               x.cnt,
		               x.memo 
			               from chengpin_cprk2product x
	                       left join chengpin_cprk y on (x.rukuId = y.id)";

	    $str = "select
				x.*,
				y.compName,
				z.proCode,
				z.proName,
				z.zhonglei,
				z.guige,
				z.color,
				c.orderCode
				from (" . $sql . ") x
				left join jichu_supplier y on x.supplierId = y.id
				left join jichu_product z on x.productId = z.id
				left join trade_order c on c.id=x.orderId
                where 1";

		$str .= " and cprkDate >= '$serachArea[dateFrom]' and cprkDate<='{$serachArea[dateTo]}'";
        if ($serachArea['key'] != '') $str .= " and (z.proName like '%{$serachArea[key]}%'
											or z.proCode like '%{$serachArea[key]}%'
											or z.guige like '%{$serachArea[key]}%')"; 
		// if($serachArea['isCheck'] != '')   $str .= " and x.isCheck = '{$serachArea[isCheck]}'";
		if ($serachArea['orderCode'] != '') $str .= " and c.orderCode like '%{$serachArea[orderCode]}%'";
		if ($serachArea['supplierId'] != '') $str .= " and x.supplierId = '{$serachArea[supplierId]}'"; 
        $str .= " order by cprkDate desc, cprkCode desc";
        
        //dump($str);exit;
        $pager = &new TMIS_Pager($str);
		$rowset = $pager->findAll(); 

		//dump($rowset);exit;
        if (count($rowset) > 0) foreach($rowset as &$value) {
			// dump($value);exit;
			$value['_edit'] = "<a href='" . $this->_url('View', array('id' => $value['id'])) . "' target='_blank' title='$title'>打印</a> | ";
			if ($value["guozhangId"]) {
				$tip = "ext:qtip='已过账禁止修改'";
				$value['_edit'] .= "<a href='javascript:void(0)' style='color:black' $tip>修改</a> | ";
				$value['_edit'] .= "<a $tip  >删除</a>";
			}else {
				$value['_edit'] .= "<a href='" . $this->_url('Edit', array('id' => $value['rukuId'])) . "'>修改</a> | "; 
				// $value['_edit'].="<a href='".$this->_url('Edit',array('id'=>$value['ruKuId']))."'>修改</a> | ";
				$value['_edit'] .= "<a href='" . $this->_url('Remove', array('id' => $value['rukuId'])) . "'  onclick=\"return confirm('确认删除吗?')\" >删除</a>";
			} 
			// 设定颜色
			if ($value['isGuozhang'] == 1) {
				$value['_bgColor'] = "lightgreen";
			}

			$value['cnt'] = round($value['cnt'], 2);
		} 
        // 左边信息
		$arrFieldInfo = array("_edit" => '操作',
			"cprkDate" => "入库日期",
			'cprkCode' => '入库单号',
			'orderCode'=>'订单编号',
			"compName" => "供应商",
			'proCode' => '产品编码',
			'proName' => '品名',
			'zhonglei' => '种类',
			'guige' => '规格',
			'color' => '颜色',
			'cntDuan'=>'段数',
			'cnt' => '数量', 
			//'dajuanKind'=>'打卷方式',
			// ''=>'',
			);

		$smarty = &$this->_getView();
		$smarty->assign('title', '订单查询'); 
		$smarty->assign('arr_field_info', $arrFieldInfo);
		$smarty->assign('add_display', 'none');
		$smarty->assign('arr_condition', $serachArea);
		$smarty->assign('arr_field_value', $rowset);
		$smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action']), $serachArea));
		$smarty->assign('arr_js_css', $this->makeArrayJsCss(array('grid', 'calendar')));
		$smarty->display('TableList.tpl');
    }

    function actionEdit(){
        $arr = $this->_modelDefault->find(array('id' => $_GET['id'])); 
		//dump($arr);exit;

		foreach ($this->fldMain as $k => &$v) {
			$v['value'] = $arr[$k]; 
			// $arr[$k] =
		}
        //dump($this->fldMain);exit;
		$this->fldMain['rukuId']['value'] = $arr['id'];
        //根据orderId 查询orderCode
        $sql = "select orderCode from trade_order where id='{$arr['orderId']}'";
        $res=$this->_modelDefault->findBySql($sql);
        $this->fldMain['orderCode']['value'] =$res[0]['orderCode'];
        //从子表中取出unit
        $this->fldMain['unit']['value'] =$arr['Products'][0]['unit'];

		$areaMain = array('title' => '入库基本信息', 'fld' => $this->fldMain); 
		// 入库明细处理
		foreach($arr['Products'] as &$v) {
			// 根据productId 取得产品相关信息
			$sql = "select * from jichu_product where id='{$v['productId']}'";
			$_temp = $this->_modelDefault->findBySql($sql); //dump($_temp);exit;
			$v['proCode'] = $_temp[0]['proCode'];
			$v['proName'] = $_temp[0]['proName'];
			$v['guige'] = $_temp[0]['guige'];
			$v['color'] = $_temp[0]['color'];
            //根据ord2proId 取得cntYaohuo
            $sql = "select cntYaohuo from trade_order2product where id='{$v['ord2proId']}'";
			$_temp = $this->_modelDefault->findBySql($sql);
			$v['cntYaohuo'] = $_temp[0]['cntYaohuo'];
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
    
    // 编辑界面利用ajax删除
	function actionRemoveByAjax() {
		$m = &FLEA::getSingleton('Model_Chengpin_Cprk2product');
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

	function actionReport(){
		//echo '123';exit;
		$this->authCheck('9-6');
		FLEA::loadClass("TMIS_Pager");
		$arr = &TMIS_Pager::getParamArray(array(
			    "dateFrom" => date('Y-m-01'),
				"dateTo" => date('Y-m-d'), 
				)); 

		$strCon = " and 1";
		$strGroup = "orderId,ord2proId,productId";
		$sqlUnion = "select {$strGroup},
			sum(cntFasheng) as cntInit,
			sum(moneyFasheng) as moneyInit,
			sum(duanFasheng) as duanInit,
			0 as duanRuku,0 as cntRuku,0 as moneyRuku,0 as duanChuku,0 as cntChuku,0 as moneyChuku
			from `chengpin_kucun` where dateFasheng<'{$arr['dateFrom']}' 
			 {$strCon} group by {$strGroup} 
			union 
			select {$strGroup},
			0 as cntInit,0 as moneyInit,0 as duanInit,
			sum(duanFasheng) as duanRuku,
			sum(cntFasheng) as cntRuku,
			sum(moneyFasheng) as moneyRuku,
			0 as duanChuku,0 as cntChuku,0 as moneyChuku
			from `chengpin_kucun` where 
			dateFasheng>='{$arr['dateFrom']}' and dateFasheng<='{$arr['dateTo']}'
			and rukuId>0  {$strCon} group by {$strGroup} 
			union 
			select {$strGroup},
			0 as cntInit,0 as moneyInit,0 as duanInit,
			0 as duanRuku,
			0 as cntRuku,
			0 as moneyRuku,
			sum(duanFasheng*-1) as duanChuku,
			sum(cntFasheng*-1) as cntChuku,
			sum(moneyFasheng*-1) as moneyChuku 
			from `chengpin_kucun` where 
			dateFasheng>='{$arr['dateFrom']}' and dateFasheng<='{$arr['dateTo']}'
			and chukuId>0  {$strCon} group by {$strGroup}";
        //dump($sqlUnion);exit;
		$sql = "select 
			{$strGroup},
			y.proCode,y.proName,y.guige,y.color,
			sum(cntInit) as cntInit,
			sum(moneyInit) as moneyInit,
			sum(cntRuku) as cntRuku,
			sum(moneyRuku) as moneyRuku,
			sum(cntChuku) as cntChuku,
			sum(moneyChuku) as moneyChuku
			from ({$sqlUnion}) as x
			left join jichu_product y on x.productId=y.id
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
		$arrFieldInfo = array(
			//"supplierName" => '供应商',
			"proCode" => "产品编码",
			//'kuwei'=>'kuwei',
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
	
	//打印
    function actionView() {
		// dump($_GET['id']);exit;
		$chengpin_cprk2product = &FLEA::getSingleton('Model_Chengpin_Cprk2product');
		$rowset = $chengpin_cprk2product->find($_GET['id']); 
		//dump($rowset);exit;
		$smarty = &$this->_getView();
		$smarty->assign("arr_field_value", $rowset);
		$smarty->display('Chengpin/RukuView.tpl');
	}
}
?>