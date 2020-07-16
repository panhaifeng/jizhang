<?php
FLEA::loadClass('Tmis_Controller');
class Controller_Shengchan_Chengpin_Chuku extends Tmis_Controller {
	var $fldMain;
	var $headSon;
	var $rules; //表单元素的验证规则

	function Controller_Shengchan_Chengpin_Chuku(){
        $this->_modelDefault = &FLEA::getSingleton('Model_Chengpin_Cpck');
		$this->_modelExample = &FLEA::getSingleton('Model_Chengpin_Cpck'); 
        // 定义模板中的主表字段
		$this->fldMain = array(
			'cpckDate' => array('title' => '出库日期', "type" => "calendar", 'value' => date('Y-m-d')), 
			// 入库单号，自动生成
			'cpckCode' => array('title' => '出库单号', 'type' => 'text', 'readonly' => true, 'value' => ''), 
			'orderCode'=>array('title'=>'订单编号','type'=>'text','readonly' => true, 'value' => ''),

		    'clientId'=>array('title' => '客户选择', 'type' => 'clientpopup', 'clientName' => ''),

			'kind' => array('title' => '出库类别', 'type' => 'select', 'options' => array(
				array('text' => '正常出库', 'value' => '正常出库'),
				array('text' => '其他', 'value' => '其他'),
				// array('text' => '其他2', 'value' => '其他2'),
				)),
			
			'unit'=>array('title'=>'出库单位','type'=>'select','options'=>array(
                 array('text'=>'米','value'=>'米'),
                 array('text'=>'码','value'=>'码'),
				)),
			
			// 定义了name以后，就不会以memo作为input的id了
			'memo' => array('title' => '备注', 'type' => 'textarea', 'disabled' => true, 'name' => 'chukuMemo'), 
			// 下面为隐藏字段
			'chukuId' => array('type' => 'hidden', 'value' => ''),
			'orderId'=> array('type' => 'hidden', 'value' => ''),
            'guozhangId'=>array('type'=>'hidden','value'=>''),
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
			//'dajuanKind'=>array('type'=>'btdajuanselect','title'=>'打卷方式','name'=>'dajuanKind[]'),
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
			'clientId' => 'required',
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
				))."'>出库</a>";
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
		$smarty->assign('arr_field_info', $arrFieldInfo);
		$smarty->assign('add_display', 'none');
		$smarty->assign('arr_condition', $arr);
		$smarty->assign('arr_field_value', $rowset);
		$smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action']), $arr));
		$smarty->assign('arr_js_css', $this->makeArrayJsCss(array('grid', 'calendar')));
		$smarty->display('TableList.tpl'); 

	}
    
    //添加新的出库登记，通过弹出窗口的形式来加载
	function actionAddByAjax(){
		//根据传递过来的orderid 找到对应的明细信息 $_GET['orderId']
        $Trade_Order2Product = &FLEA::getSingleton('Model_Trade_Order2Product');
        $res=$Trade_Order2Product->findAll(array('orderId'=>$_GET['orderId']));
		//加载fldMain中的信息
		//加载fldMain中的信息
        $this->fldMain['orderId']['value']=$res[0]['orderId'];
        $this->fldMain['orderCode']['value']=$res[0]['Order']['orderCode'];
        $this->fldMain['cpckCode']['value']=$this->_getNewCode('CC', 'chengpin_cpck', 'cpckCode'); 
        
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
		$areaMain = array('title' => '成品出库基本信息', 'fld' => $fldMain); 
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
        $chengpin_cpck2product = array();
		foreach ($_POST['productId'] as $key => $v) {
			// 当没有记录 或者 没有输入数量时 跳出本次循环 防止多保存
			if (empty($_POST['productId'][$key]) || empty($_POST['cnt'][$key])) continue;
			$chengpin_cpck2product[] = array('id' => $_POST['id'][$key], // 主键id
				'productId' => $_POST['productId'][$key],                // 产品id
				'cnt' => $_POST['cnt'][$key],                            // 要货数量
				//'dajuanKind' => $_POST['dajuanKind'][$key],              //打卷方式
				'cntDuan'=> $_POST['cntDuan'][$key],                     //段数
				'ord2proId'=>$_POST['ord2proId'][$key],                  //订单明细id
				'memo' => $_POST['memo'][$key],                          // 备注
				'unit'=>$_POST['unit'],                                  //计量单位
				// 'kuweiId'=>$_POST['kuweiId'][$key],                 //库位
				);
		} 
		//dump($chengpin_cpck2product);exit;
        // yuanliao_cgrk 表 的数组
		$chengpin_cpck = array('id' => $_POST['chukuId'], // 主键id
			'cpckCode' => $_POST['cpckCode'], // 入库单号
			//'songhuoCode' => $_POST['songhuoCode'], // 送货单号
			'cpckDate' => $_POST['cpckDate'], // 入库日期
			'orderId' => $_POST['orderId'], // 订单id
			'clientId'=>$_POST['clientId'], //客户id
			//'supplierId' => $_POST['supplierId'], // 供应商id
			'memo' => $_POST['chukuMemo'], // 入库备注
			'kind' => $_POST['kind'], // 入库类型
			//'kuwei' => $_POST['kuweiId'], // 库位
			// ''=>$_POST[''],
			); 
		// 表之间的关联
		$chengpin_cpck['Products'] = $chengpin_cpck2product;
		//dump($chengpin_cpck);exit; 
		// 保存 并返回chengpin_cpck表的主键
		$itemId = $this->_modelExample->save($chengpin_cpck);
		if ($itemId) {
			if ($_POST['Submit'] == '保存并新增下一个') {
				js_alert('保存成功！', '', $this->_url('add'));
			}else {
				js_alert('保存成功！', '', $this->_url('right'));
			}
		}else die('保存失败!');

	}


	function actionRight(){
        $this->authCheck('9-4');
        FLEA::loadClass('TMIS_Pager'); 
        
       	$serachArea = TMIS_Pager::getParamArray(array('dateFrom' => date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-30, date("Y"))),
				'dateTo' => date("Y-m-d"),
				'clientId' => '', 
				// 'traderId' => '',
				// 'isCheck' => 0,
				'orderCode'=>'',
				'key' => '',
				));
        
        $sql = "select y.cpckCode,
		               y.cpckDate,
		               y.clientId,
		               y.orderId,
		               y.kind,
		               x.id,
		               x.chukuId as chukuId,
		               x.cntDuan,
		               x.unit,
		               x.productId,
		               x.ord2proId,
		               x.cnt,
		               x.memo 
			               from chengpin_cpck2product x
	                       left join chengpin_cpck y on (x.chukuId = y.id)";

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
				left join jichu_client y on x.clientId = y.id
				left join jichu_product z on x.productId = z.id
				left join trade_order c on c.id=x.orderId
                where 1";

		$str .= " and cpckDate >= '$serachArea[dateFrom]' and cpckDate<='{$serachArea[dateTo]}'";
        if ($serachArea['key'] != '') $str .= " and (z.proName like '%{$serachArea[key]}%'
											or z.proCode like '%{$serachArea[key]}%'
											or z.guige like '%{$serachArea[key]}%')"; 
		// if($serachArea['isCheck'] != '')   $str .= " and x.isCheck = '{$serachArea[isCheck]}'";
		if ($serachArea['orderCode'] != '') $str .= " and c.orderCode like '%{$serachArea[orderCode]}%'";
		if ($serachArea['clientId'] != '') $str .= " and x.clientId = '{$serachArea[clientId]}'"; 
        $str .= " order by cpckDate desc, cpckCode desc";
        
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
				$value['_edit'] .= "<a href='" . $this->_url('Edit', array('id' => $value['chukuId'])) . "'>修改</a> | "; 
				// $value['_edit'].="<a href='".$this->_url('Edit',array('id'=>$value['ruKuId']))."'>修改</a> | ";
				$value['_edit'] .= "<a href='" . $this->_url('Remove', array('id' => $value['chukuId'])) . "'  onclick=\"return confirm('确认删除吗?')\" >删除</a>";
			} 
			
		} 
        // 左边信息
		$arrFieldInfo = array("_edit" => '操作',
			"cpckDate" => "出库日期",
			'cpckCode' => '出库单号',
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
		$this->fldMain['chukuId']['value'] = $arr['id'];
        //根据orderId 查询orderCode
        $sql = "select orderCode from trade_order where id='{$arr['orderId']}'";
        $res=$this->_modelDefault->findBySql($sql);
        $this->fldMain['orderCode']['value'] =$res[0]['orderCode'];
        //从关联表中 取出compName
        $this->fldMain['clientId']['clientName'] = $arr['Client']['compName'];
        //从子表中取出unit
        $this->fldMain['unit']['value'] =$arr['Products'][0]['unit'];

		$areaMain = array('title' => '出库基本信息', 'fld' => $this->fldMain); 
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
    
    //打印
    function actionView() {
		// dump($_GET['id']);exit;
		$chengpin_cpck2product = &FLEA::getSingleton('Model_Chengpin_Cpck2product');
		$rowset = $chengpin_cpck2product->find($_GET['id']); 
		//dump($rowset);exit;
		$smarty = &$this->_getView();
		$smarty->assign("arr_field_value", $rowset);
		$smarty->display('Chengpin/ChukuView.tpl');
	}

     // 编辑界面利用ajax删除
	function actionRemoveByAjax() {
		$m = &FLEA::getSingleton('Model_Chengpin_Cpck2product');
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

	//出库信息
	function actionPopup(){
        FLEA::loadClass('TMIS_Pager'); 
        
       	$serachArea = TMIS_Pager::getParamArray(array(
       			'dateFrom' => date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-30, date("Y"))),
				'dateTo' => date("Y-m-d"),
				'clientId' => '', 
				'traderId' => '',
				'orderCode'=>'',
				'key' => '',
				));
        
        $sql = "select y.cpckCode,
        				y.cpckCode as textValue,
		               y.cpckDate,
		               y.orderId,
		               x.ord2proId,
		               x.id,
		               x.id as chuku2proId,
		               x.chukuId as chukuId,
		               x.cntDuan,
		               x.unit,
		               x.productId,
		               x.cnt,
		               x.danjia,
		               x.money,
		               x.memo 
			               from chengpin_cpck2product x
	                       left join chengpin_cpck y on (x.chukuId = y.id)";

	    $str = "select
				x.*,
				x.cpckDate as chukuDate,
				y.compName,
				z.proCode,
				z.proName,
				z.zhonglei,
				z.guige,
				z.color,
				z.chengFen,
				z.menfu,
				z.kezhong,
				c.orderCode,
				c.clientId,
				'成品出库' as kind
				from (" . $sql . ") x
				left join jichu_product z on x.productId = z.id
				left join trade_order c on c.id=x.orderId
				left join jichu_client y on c.clientId = y.id
				left join caiwu_ar_guozhang a on a.kind='成品出库' and a.chuku2proId=x.id
                where 1";

		$str .= " and x.cpckDate >= '{$serachArea['dateFrom']}' and x.cpckDate<='{$serachArea['dateTo']}'";
        if ($serachArea['key'] != '') $str .= " and (z.proName like '%{$serachArea['key']}%'
											or z.proCode like '%{$serachArea['key']}%'
											or z.guige like '%{$serachArea['key']}%')"; 
		if ($serachArea['orderCode'] != '') $str .= " and c.orderCode like '%{$serachArea['orderCode']}%'";
		if ($serachArea['clientId'] != '') $str .= " and c.clientId = '{$serachArea['clientId']}'";
		if ($serachArea['traderId'] != '') $str .= " and c.traderId = '{$serachArea['traderId']}'"; 
        $str .= " order by x.cpckDate desc, x.cpckCode desc";
        
        // dump($str);exit;
        $pager = &new TMIS_Pager($str);
		$rowset = $pager->findAll(); 

		//dump($rowset);exit;
        foreach($rowset as & $v) {
			$temp=array();
			if($v['proName']!='')$temp[]='品名:'.$v['proName'];
			if($v['guige']!='')$temp[]=',规格:'.$v['guige'];
			if($v['chengFen']!='')$temp[]=',成分:'.$v['chengFen'];
			if($v['color']!='')$temp[]=',颜色:'.$v['color'];
			if($v['menfu']!='')$temp[]=',门幅:'.$v['menfu'];
			if($v['kezhong']!='')$temp[]=',克重:'.$v['kezhong'];
			$v['qitaMemo']=join(' ',$temp);
			// dump($v);exit;
		} 
        // 左边信息
		$arrFieldInfo = array(
			"cpckDate" => "出库日期",
			'cpckCode' => '出库单号',
			'orderCode'=>'订单编号',
			"compName" => "客户名称",
			'proCode' => '产品编码',
			// 'proName' => '品名',
			// 'guige' => '规格',
			'qitaMemo' => '描述',
			// 'cntDuan'=>'段数',
			'cnt' => '数量', 
			'unit'=>'单位',
			'danjia'=>'单价',
			'money'=>'金额',
			);

		$smarty = &$this->_getView();
		$smarty->assign('title', '订单查询'); 
		$smarty->assign('arr_field_info', $arrFieldInfo);
		$smarty->assign('add_display', 'none');
		$smarty->assign('arr_condition', $serachArea);
		$smarty->assign('arr_field_value', $rowset);
		$smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action']), $serachArea));
		$smarty->display('Popup/CommonNew.tpl');
	}
	
}
?>