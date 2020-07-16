<?php
/*********************************************************************\
*  Copyright (c) 1998-2013, TH. All Rights Reserved.
*  Author :zcc
*  FName  :Plan.php
*  Time   :2014/08/30 09:18:48
*  Remark :
\*********************************************************************/
FLEA::loadClass('TMIS_Controller');
class Controller_Shengchan_PiJian_Plan extends Tmis_Controller {
	var $fldMain;
	var $headSon;
	var $rules; //表单元素的验证规则
	// /构造函数
	function Controller_Shengchan_PiJian_Plan() {

		$this->_modelExample = &FLEA::getSingleton('Model_Shengchan_PiJian_Plan');
		$this->_modelPlan2Son = &FLEA::getSingleton('Model_Shengchan_PiJian_Plan2Son');
		$this->_modelPlan2SonFenPi = &FLEA::getSingleton('Model_Shengchan_PiJian_FenPi');

		$this->_modelpro = &FLEA::getSingleton('Model_Jichu_Product');
		$this->_modelOrder = &FLEA::getSingleton('Model_Trade_Order');
		$this->_modelOrd2pro = &FLEA::getSingleton('Model_Trade_Order2Product');
		
		$this->fldMain = array(
			// /*******2个一行******
			'proCode' => array('title' => '产品编码', "type" => "text", 'value' =>'','readonly'=>true),
			'proName' => array('title' => '品名', "type" => "text", 'value' =>'','readonly'=>true),
			// 'guige' => array('title' => '规格', "type" => "text", 'value' =>'','readonly'=>true),
			// 'color' => array('title' => '颜色', "type" => "text", 'value' =>'','readonly'=>true),
			// 'menfu' => array('title' => '门幅', "type" => "text", 'value' =>'','readonly'=>true),
			// 'kezhong' => array('title' => '克重', "type" => "text", 'value' =>'','readonly'=>true),
			'pishuCnt' => array('title' => '总匹数', "type" => "text", 'value' =>'','readonly'=>true),
			// 下面为隐藏字段
			'ord2proId' => array('type'=>'hidden', 'value'=>'','name'=>'ord2proId'),
			'shengchanId' => array('type'=>'hidden', 'value'=>'','name'=>'shengchanId'),
			'planCode' => array('title' => '坯检计划号','type'=>'hidden', 'value'=>'','name'=>'planCode','value'=>"系统自动生成",'readonly'=>true),
			'id' => array('type'=>'hidden', 'value'=>'','name'=>'planId'),
			'flags' => array('type'=>'hidden','value'=>'','name'=>'flags')
		);
		$this->headSon = array(
			'_edit' => array('type' => 'btBtnRemove', "title" => '+5行', 'name' => '_edit[]','value'=>'1',),
			'pishu' => array('type' => 'bttext', "title" => '匹数', 'name' => 'pishu[]','width'=>'150px',),
			// 'fenpi' => array('type' => 'btBtnKucun', "title" => '分匹', 'name' => 'fenpi[]', ),
			'id' => array('type' => 'bthidden', 'name' =>	 'id[]'),
			//'ord2proId' => array('type' => 'bthidden', 'name' => 'ord2proId[]','defaultValue'=>$_GET['ord2proId']),
		);
		$this->fenpiSon =array(
			//'_edit' => array('type' => 'btBtnRemove', "title" => '+5行', 'name' => '_edit[]',),
			'ExpectCode' => array('type' => 'bttext', "title" => '预计条码', 'name' => 'ExpectCode[]','readonly'=>true),
			'fenpiDate' => array('type' => 'btcalendar', "title" => '分匹日期', 'name' => 'fenpiDate[]','defaultValue' => date('Y-m-d'),'inTable'=>'true'),
			'jianhao' => array('type' => 'bttext', "title" => '件号', 'name' => 'jianhao[]',),
			// 'ganghao' => array('type' => 'bttext', "title" => '缸号', 'name' => 'ganghao[]',),
			// 'cntKg' => array('type' => 'bttext', "title" => '预计重量', 'name' => 'cntKg[]',),
			// 'cntM' => array('type' => 'bttext', "title" => '预计米数', 'name' => 'cntM[]',),
			'id'=>array('type'=>'bthidden','name'=>'fenpiId[]'),
		);
	}
	/**
	 * ps ：根据生产计划列出坯检计划
	 * Time：2017/06/22 10:13:47
	 * @author zcc
	 * @param 参数类型
	 * @return 返回值类型
	*/
	function actionListForAdd() {
		$this->authCheck('13-1');
		// dump($_POST);exit;
		// /构造搜索区域的搜索类型
		FLEA::loadClass('TMIS_Pager');
		$serachArea = TMIS_Pager::getParamArray(array(
			'dateFrom' => date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-30, date("Y"))),
			'dateTo' => date("Y-m-d"),
			//'isSet' => 0, //默认初始化的时候是 未设置
			//'clientId' => '',
			//'traderId' => '',
			// 'isCheck' => 0,
			'orderCode' => '',
			'planCode' => '',
			'key' => '',
		));

		$sql = "SELECT x.id as planId,x.planCode,x.zhcntKg as pibuCnt,x.planDate,p.proCode,p.proName,p.guige,p.color,p.menfu,p.kezhong,y.cntYaohuo,y.dateJiaohuo,y.unit,y.id as ord2proId,o.orderCode
			FROM shengchan_plan x
			left join trade_order2product y on y.id = x.order2proId
			left join trade_order o on o.id = y.orderId
			left join jichu_product p on p.id = x.productId
			where 1";
		// if($serachArea['isSet']==0) {
		// 	$sql .= " and z.id is null";
		// } elseif($serachArea['isSet']==1) {
		// 	$sql .= " and z.id>0";
		// }
		$sql .= " and x.planDate >= '$serachArea[dateFrom]' and x.planDate<='$serachArea[dateTo]'";	
		if ($serachArea['key'] != '') $sql .= " and (p.proName like '%{$serachArea['key']}%'
											or p.proCode like '%{$serachArea['key']}%'
											or p.guige like '%{$serachArea['key']}%')";
		if ($serachArea['planCode'] != '') $sql .= " and x.planCode like '%{$serachArea['planCode']}%'";
		if ($serachArea['clientId'] != '') $sql .= " and y.clientId = '{$serachArea['clientId']}'";
		if ($serachArea['traderId'] != '') $sql .= " and y.traderId = '{$serachArea['traderId']}'";
		if ($serachArea['orderCode'] != '') $sql .= " and o.orderCode like '%{$serachArea['orderCode']}%'";
		$sql .= " group by x.id order by planCode desc";
		$pager = &new TMIS_Pager($sql);
		$rowset = $pager->findAll();
		//dump($rowset);exit();
		if (count($rowset) > 0) foreach($rowset as &$value) {
			$value['_edit'] = "<a href='" . $this->_url('Add', array(
				'planId' => $value['planId'],
				'fromAction'=>$_GET['action']
			)) . "' title='$title'>设置计划</a>";
		}
		// 合计行
		$heji = $this->getHeji($rowset, array('cnt'), '_edit');
		$rowset[] = $heji;
		// 显示信息
		$arrFieldInfo = array(
			'_edit'=>'操作',
			'planCode'=>'计划编号',
			'planDate'=>'计划日期',
			'orderCode' =>'订单编号',
			'proCode' => '产品编码',
			'proName' => '品名',
			'guige' => '规格',
			'color'=>'颜色',
			'menfu'=>'门幅',
			'kezhong'=>'克重',

			"cntYaohuo" => '要货数量',
			"unit" => '单位',
			"dateJiaohuo" => '交期',
			// "danjia" => '单价',
			// "money" => '金额',
			// "memo" =>'产品备注'
		);
		$smarty = &$this->_getView();
		$smarty->assign('title', '订单查询');
		$smarty->assign('arr_field_info', $arrFieldInfo);
		$smarty->assign('add_display', $isShowAdd?'display':'none');
		$smarty->assign('arr_condition', $serachArea);
		$smarty->assign('arr_field_value', $rowset);
		// $msg = "<font color='red'>数量总计:{$zongji['cnt']},金额总计:{$zongji['money']}</font>";
		$smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action']), $serachArea).$msg);
		$smarty->assign('arr_js_css', $this->makeArrayJsCss(array('grid', 'calendar')));
		$smarty->display('TableList.tpl');
	}
	/**
	 * ps ：计划登记界面
	 * Time：2017/06/22 11:05:28
	 * @author zcc
	*/
	function actionAdd(){
		$sql = "SELECT p.proCode,
						p.proName,
						p.guige,
						p.color,
						p.menfu,
						p.kezhong,
						y.cntYaohuo,
						y.dateJiaohuo,
						y.unit,
						y.id as ord2proId
			FROM trade_order2product y
			left join jichu_product p on p.id = y.productId
			where 1 and y.id = {$_GET['ord2proId']}";
		$row = $this->_modelExample->findBySql($sql);	
		$arr = $row[0];
		foreach ($this->fldMain as $k => &$v) {
			$v['value'] = $arr[$k]?$arr[$k]:$v['value'];
		}
		
		$fldMain = $this->fldMain;
		// dump($fldMain);exit();
		$headSon = $this->headSon;
		// 从表信息字段,默认5行
		for($i = 0;$i < 1;$i++) {
			$rowsSon[] = array();
		}
		for($i = 0;$i < 1;$i++){
			$fenpiSon[] = array();
		}
		// 主表区域信息描述
		$areaMain = array('title' => $this->_kind.'基本信息', 'fld' => $fldMain);
		$smarty = &$this->_getView();
		$smarty->assign('areaMain', $areaMain);
		$smarty->assign('headSon', $headSon);
		$smarty->assign('rowsSon', $rowsSon);
		$smarty->assign('rules', $this->rules);
		$smarty->assign('fenpiSon',$this->fenpiSon);
		$smarty->assign('rowzSon',$fenpiSon);
		$smarty->assign('other_button',"<input class='btn btn-info' type='submit' id='Submit1' name='Submit1' value=' 保存并打印 ' accesskey='S' onClick=$('#submitValue').val('保存并打印条码')>");
		$smarty->assign('sonTpl', 'Shengchan/PiJian/_JsPiJian.tpl');
		$smarty->assign("otherInfoTpl",'Shengchan/PiJian/_FenPi.tpl');
		// $this->_beforeDisplayAdd($smarty);
		$smarty->display('Shengchan/PiJian/PiJianT1.tpl');//专用模版请勿其他页面套用
	}
	/**
	 * ps ：保存方法
	 * Time：2017/06/28 09:11:56
	 * @author zcc
	*/
	function actionSave(){
		if ($_POST['planCode']=='系统自动生成'||$_POST['planCode']=='') {
			$_POST['planCode'] = $this->_getNewCode('PJH','shengchan_planpj','planCode');
		}
		$son = array();
		foreach ($_POST['pishu'] as $key => $v) {
			// 当没有记录 或者 没有输入数量时 跳出本次循环 防止多保存
			if (empty($_POST['pishu'][$key])) continue;
			$temp = array(
				'id'=>$_POST['id'][$key],
				'jitai'=>$_POST['jitai'][$key]+0,
				'pishu'=>$_POST['pishu'][$key],
			);
			// foreach($this->headSon as $k=>&$vv) {
			// 	$temp[$k] = $_POST[$k][$key];
			// }
			//dump($temp);exit();
			$son[] = $temp;
		}
		$ExpectCode = array();
		foreach ($_POST['ExpectCode'] as $key => $value) {
			if(empty($_POST['ExpectCode'][$key])) continue;
			$tt = array(
                'ExpectCode' => $_POST['ExpectCode'][$key],
			);
			$ExpectCode[] = $tt;
		}
		if(count($son)==0||count($ExpectCode)==0){
			js_alert('请生成有效的分匹计划!','window.history.go(-1)');
			exit;
		}
		$row = array(
			'id'=>$_POST['planId'],
			'shengchanId' =>$_POST['shengchanId'],
			'ord2proId' =>$_POST['ord2proId'],
			'jitaiCnt' =>$_POST['jitaiCnt'],
			'pishuCnt' =>$_POST['pishuCnt'],
			'planCode' =>$_POST['planCode'],
			'planDate' =>date('Y-m-d'),
			'Son' =>$son,
		);
		// dump($fenpis);dump($row);exit();
		$id = $this->_modelExample->save($row);
		if($_POST['planId']>0){
			$id = $_POST['planId'];
		}
		if($_POST['planId']>0){
			$sql="SELECT ExpectCode from shengchan_planpj_fenpi where planId='{$_POST['planId']}'";
			$FenpiInfo = $this->_modelExample->findBySql($sql);
		}
		$FenpiInfo = array_col_values($FenpiInfo,'ExpectCode');
		$fenpis = array();
		foreach ($_POST['ExpectCode'] as $key => $v) {
			
			if (empty($_POST['ExpectCode'][$key]) || empty($_POST['jianhao'][$key])) continue;
			$fenpi = array(
				'id' =>$_POST['fenpiId'][$key],
				'planId' =>$id,
				'shengchanId' =>$_POST['shengchanId'],
				'ord2proId' =>$_POST['ord2proId'],
				'ExpectCode' =>$_POST['ExpectCode'][$key],
				'fenpiDate' =>$_POST['fenpiDate'][$key],
				'ganghao' =>$_POST['ganghao'][$key],
				'jitai' =>$_POST['jitaiName'][$key],
				'jianhao' =>$_POST['jianhao'][$key],
				'cntKg' =>$_POST['cntKg'][$key]+0,
				'cntM' =>$_POST['cntM'][$key]+0,
				// 'isbuda'=>$_POST['isbuda'][$key]
			);

			if($_POST['planId']){
				if(!in_array($v, $FenpiInfo)){
                 	$fenpi['isbuda'] = 1; 
				}
			}
			$fenpis[] = $fenpi;
			
		}
		$idfp = $this->_modelPlan2SonFenPi->saveRowset($fenpis);
		if($_POST['id']!=''){
			$newId=$_POST['id'];
		}
	    else{
		    $newId=mysql_insert_id();
	    } 
	    if($id>0){
			if($_POST['submitValue']=='保存并打印条码'){
				$fpData = $this->_modelPlan2SonFenPi->findAll(array('planId'=>$id),null,null,'id');
				foreach ($fpData as $k => &$v) {
					$tmp[] = $v['id'];
				}
				$ids = implode(',',$tmp);
		     	js_alert(null,"window.parent.showMsg('保存成功!')",$this->_url('print',array('ids'=>$ids,'temp'=>'order','ord2proId'=>$_POST['ord2proId'])));
		 	}else{
		 		if($_POST['flags']=='plans'){
                    js_alert(null,'window.parent.showMsg("保存成功!")',$this->_url('Right'));
		 		}else{
		 			js_alert(null,'window.parent.showMsg("保存成功!")',url('Trade_Order','Right'));
		 		}
				
		 	}
		 }

		//跳转
		// js_alert(null,'window.parent.showMsg("保存成功!")',$this->_url('right'));
		// exit;

	}
	/**
	 * ps ：计划查询
	 * Time：2018年12月12日 17:12:30
	 * @author shen
	 * @param 参数类型
	 * @return 返回值类型
	*/
	function actionRight(){
		$this->authCheck('1-3');
		FLEA::loadClass('TMIS_Pager');
		$arr = TMIS_Pager::getParamArray(array(
			'dateFrom' => date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-30, date("Y"))),
			'dateTo' => date("Y-m-d"),
			'proCode' => '',
			'orderCode' =>'',
			'zhiji' =>'',
			'ExpectCode' =>'',
			'guige'=>'',
			'clientId'=>'',
			'traderId'=>''

		));
		$sql = "SELECT x.*,
				f.jitai,f.ExpectCode,f.fenpiDate,f.jianhao,f.cntKg,
				f.ganghao,p.proCode,p.proName,p.menfu,p.kezhong,p.guige,t.orderCode,c.compName,e.employName
			FROM shengchan_planpj x
			left join trade_order2product s on s.id = x.ord2proId
			left join shengchan_planpj_fenpi f on f.planId = x.id
			left join jichu_product p on p.id = s.productId
			left join trade_order t on t.id = s.orderId
			left join jichu_client c on c.id=t.clientId
			left join jichu_employ e on e.id=t.traderId
			where 1
			";
		if($arr['dateFrom']!=''){
			$sql.=" and f.fenpiDate >= '{$arr['dateFrom']}' and f.fenpiDate<='{$arr['dateTo']}'";
		}
		if($arr['ExpectCode']) $sql.=" and f.ExpectCode like '%{$arr['ExpectCode']}%'";
		if($arr['proCode']) $sql.=" and p.proCode like '%{$arr['proCode']}%'";
		if($arr['orderCode']) $sql.=" and t.orderCode like '%{$arr['orderCode']}%'";
		if($arr['guige']) $sql.=" and p.guige like '%{$arr['guige']}%'";
	    if($arr['clientId']) $sql.=" and c.id='{$arr['clientId']}'";
	    if($arr['traderId']) $sql.=" and e.id='{$arr['traderId']}'";
		$sql .= " order by fenpiDate desc,orderCode asc,s.productId asc,jianhao asc";
		// dump($sql);exit();
		$pager = &new TMIS_Pager($sql);
		$rowset = $pager->findAll();
		if (count($rowset)>0) foreach($rowset as & $v) {
			$v['_edit'] .= " ".$this->getEditHtml($v['id']);
			// $v['_edit'] .= " ".$this->getRemoveHtml($v['id']);
			$v['_edit'] .= "&nbsp;<a href='".$this->_url('Remove',array('id'=>$v['id']))."' onclick='return confirm(\"您确认要整单删除吗?\")'>删除</a>";
		}
		$zongji = $this->getHeji($rowset,array('cntKg'),'_edit');
		$rowset[] = $zongji;
		$smarty = & $this->_getView();
		$smarty->assign('title', $this->title);
		$arr_field_info = array(
			"_edit" => "操作",
			"fenpiDate" =>"时间",
			'orderCode' =>array('text'=>"订单编号",'width'=>120),
			"proCode" =>"产品编码",
			"ExpectCode" =>"条码",
			"jianhao" =>"件号",
			"compName"=>"客户",
			"employName"=>"业务员",
			"guige"     =>"规格",
			"menfu"     =>"门幅",
			"kezhong"   =>"克重"
			// "shengchanCode" =>"计划编号",
			// "jitai" =>"机台",
			// "ganghao" =>"缸号",
			// "cntKg" =>"预计公斤数",
			//"cntM" =>"预计米数",
			
		);
		$smarty->assign('arr_field_info', $arr_field_info);
		$smarty->assign('add_display', 'none');
		$smarty->assign('arr_condition', $arr);
		$smarty->assign('arr_field_value', $rowset);
		// $msg = "<font color='red'>数量总计:{$zongji['cnt']},金额总计:{$zongji['money']}</font>";
		$smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action']), $arr).$msg);
		$smarty->assign('arr_js_css', $this->makeArrayJsCss(array('grid', 'calendar')));
		$this->_beforeDisplayRight($smarty);
		$smarty->display('TableList.tpl');
	}
	/**
	 * ps ：
	 * Time：2017/06/28 13:02:03
	 * @author zcc
	 * @param 参数类型
	 * @return 返回值类型
	*/
	function actionEdit(){
		$arr = $this->_modelExample->find($_GET['id']);
		$sql = "SELECT s.planCode as planCode2,p.proCode,p.proName,p.guige,p.color,p.menfu,p.kezhong
			FROM trade_order2product x 
			left join jichu_product p on p.id = x.productId
			left join shengchan_planpj s on s.ord2proId = x.id
			where s.id = {$arr['id']}";
		$row = 	$this->_modelExample->findBySql($sql);
		$arr['planCode2'] = $row[0]['planCode2'];
		$arr['proCode'] = $row[0]['proCode'];
		$arr['proName'] = $row[0]['proName'];
		$arr['guige'] = $row[0]['guige'];
		$arr['color'] = $row[0]['color'];
		$arr['menfu'] = $row[0]['menfu'];
		$arr['kezhong'] = $row[0]['kezhong'];
		if($_GET['flags']&&$_GET['flags']=='order'){  //订单查询页面传过来的分批修改flags参数
			$arr['flags'] = $_GET['flags'];
		}else{
			$arr['flags'] = 'plans';
		}
		//dump($arr);exit();
		foreach ($this->fldMain as $k => &$v) {
			$v['value'] = $arr[$k]?$arr[$k]:$v['value'];
		}
		foreach ($arr['Son'] as &$v) {
			$temp = array();
			foreach($this->headSon as $kk => &$vv) {
				$temp[$kk] = array('value' => $v[$kk]);
			}
			$rowsSon[] = $temp;
		}
		$fenpi = $this->_modelPlan2SonFenPi->findAll(array('shengchanId'=>$arr['shengchanId'],'planId'=>$arr['id']));
		foreach ($fenpi as &$va) {
			$va['jitaiName'] = $va['jitai'];
			$temp = array();
			foreach($this->fenpiSon as $kk => &$vv) {
				$temp[$kk] = array('value' => $va[$kk]);
			}
			$fenpiSon[] = $temp;
		}
		//dump($fenpiSon);exit();
		//dump($row);exit();
		$smarty = &$this->_getView();
		$smarty->assign('areaMain',array('title' => $this->_kind.'基本信息', 'fld' => $this->fldMain));
		$smarty->assign('headSon', $this->headSon);
		$smarty->assign('rowsSon', $rowsSon);
		$smarty->assign('rules', $this->rules);
		$smarty->assign('fenpiSon',$this->fenpiSon);
		$smarty->assign('rowzSon',$fenpiSon);
		$smarty->assign('create','none');//让生成按钮不显示
		//$smarty->assign('other_button',"<input class='btn btn-info' type='submit' id='Submit1' name='Submit1' value=' 保存并打印 ' accesskey='S' onClick=$('#submitValue').val('保存并打印条码')>");
		$smarty->assign('sonTpl', 'Shengchan/PiJian/_JsPiJian.tpl');
		$smarty->assign("otherInfoTpl",'Shengchan/PiJian/_FenPi.tpl');
		$smarty->display('Shengchan/PiJian/PiJianT1.tpl');//专用模版请勿其他页面套用
	}
	/**
	 * ps ：打印列表
	 * Time：2017/06/28 15:42:21
	 * @author zcc
	*/
	function actionPrintList(){
		$this->authCheck('1-4');
		FLEA::loadClass('TMIS_Pager');
		$arr = TMIS_Pager::getParamArray(array(
			'dateFrom' => date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-30, date("Y"))),
			'dateTo' => date("Y-m-d"),
			'clientId' => '',
			'proCode' => '',
			// 'planCode' =>'',
			'orderCode' =>'',
			'ExpectCode' =>'',
            'ganghao'    =>'',
            'guige'    =>'',
            'traderId' =>''
		));
		$sql = "SELECT x.*,
				f.jitai,f.ExpectCode,f.fenpiDate,f.jianhao,f.cntKg,f.id as fenpiId,f.timesPrint,f.ganghao,p.id as productId,
				p.proCode,p.proName,p.guige,s.menfu,s.kezhong,t.orderCode,t.traderId,t.clientId
			FROM shengchan_planpj x
			left join trade_order2product s on s.id = x.ord2proId
			left join shengchan_planpj_fenpi f on f.planId = x.id
			left join jichu_product p on p.id = s.productId
			left join trade_order t on t.id = s.orderId
			where 1 
			";
		if($arr['dateFrom']!=''){
			$sql.=" and f.fenpiDate >= '{$arr['dateFrom']}' and f.fenpiDate<='{$arr['dateTo']}'";
		}
		if($arr['ExpectCode']) $sql.=" and f.ExpectCode like '%{$arr['ExpectCode']}%'";
		if($arr['proCode']) $sql.=" and p.proCode like '%{$arr['proCode']}%'";
		if($arr['orderCode']) $sql.=" and t.orderCode like '%{$arr['orderCode']}%'";
		if($arr['ganghao']) $sql.=" and f.ganghao like '%{$arr['ganghao']}%'";
		if($arr['guige']) $sql.=" and p.guige like '%{$arr['guige']}%'";
		if ($arr['traderId'] != '') $sql .= " and t.traderId = '$arr[traderId]'";
		if ($arr['clientId'] != '') $sql .= " and t.clientId = '$arr[clientId]'";
		$sql.=" group by s.id,s.productId";
		$sql .= " order by fenpiDate desc,orderCode asc,f.jitai asc,s.productId asc,jianhao asc";
		//dump($sql);exit();
		$pager = &new TMIS_Pager($sql);
		$rowset = $pager->findAll();
		if (count($rowset)>0) foreach($rowset as & $v) {
			//$v['print'] = "<input type='checkbox' name='ck' id='ck' value='{$v['fenpiId']}' />";
			$sql="select employName from jichu_employ where id='{$v['traderId']}'";
			$tradeInfo = $this->_modelExample->findBySql($sql);
			$v['employName'] = $tradeInfo[0]['employName'];		
			$sql="select compName from jichu_client where id='{$v['clientId']}'";
			$clientInfo = $this->_modelExample->findBySql($sql);
			$v['compName'] = $clientInfo[0]['compName'];	
			$v['mingxi'] = "<a href='".$this->_url('SearchDetail',array('ord2proId'=>$v['ord2proId'],'productId'=>$v['productId'],'width'=>'700',
                        'no_edit'=>1,
                        'TB_iframe'=>1,))."' class='thickbox' title='明细'>明细</a>";
		}
		$smarty = & $this->_getView();
		$smarty->assign('title', $this->title);
		$arr_field_info = array(
			//'print'=>array("text"=>'<a href="javascript:void(0)" id="ckAll">全选</a>','width'=>'50'),
			'mingxi'=>array('text'=>'操作','width'=>'50px'),
			"fenpiDate" =>"时间",
			"orderCode" =>"订单编号",
			//"ExpectCode" =>"条码",
			"compName"  =>'客户',
			"employName"=>"业务员",
			"proCode" =>"产品编码",
			"guige" =>"规格",
			"menfu" =>"门幅",
			"kezhong" =>"克重",
			//"jianhao" =>"件号",
			//'timesPrint' =>'打印次数',
			//"cntM" =>"预计米数",
			
		);
		$smarty->assign('arr_field_info', $arr_field_info);
		$smarty->assign('add_display', 'none');
		$smarty->assign('arr_condition', $arr);
		$smarty->assign('arr_field_value', $rowset);
		// $msg = "<font color='red'>数量总计:{$zongji['cnt']},金额总计:{$zongji['money']}</font>";
		$smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action']), $arr).$msg);
		$smarty->assign('arr_js_css', $this->makeArrayJsCss(array('grid', 'calendar')));
		//$smarty->assign('sonTpl', 'Shengchan/PiJian/_jscheckprint.tpl');
		$this->_beforeDisplayRight($smarty);
		$smarty->display('TableList.tpl');
	}
    
    /**
	 * ps ：明细
	 * Time：2019/06/05 09:05:10
	 * @author pan
	*/
	function actionSearchDetail(){
        FLEA::loadClass('TMIS_Pager');
        $arr = TMIS_Pager::getParamArray(array(
            'ord2proId'=>'',
            'productId'=>'',
            'no_edit'=>'',
        ));
        $sql = "SELECT x.*,
				f.jitai,f.ExpectCode,f.fenpiDate,f.jianhao,f.cntKg,f.id as fenpiId,f.timesPrint,f.ganghao,
				p.proCode,p.proName,p.guige,s.menfu,s.kezhong,t.orderCode,t.traderId,t.clientId
			FROM shengchan_planpj x
			left join trade_order2product s on s.id = x.ord2proId
			left join shengchan_planpj_fenpi f on f.planId = x.id
			left join jichu_product p on p.id = s.productId
			left join trade_order t on t.id = s.orderId
			where 1 
			";
	    $sql .=" and x.ord2proId='{$arr['ord2proId']}' and s.productId='{$arr['productId']}'";
		$sql .= " order by fenpiDate desc,orderCode asc,f.jitai asc,s.productId asc,jianhao asc";
		//dump($sql);exit();
		$pager = &new TMIS_Pager($sql,null,null,20);
		$rowset = $pager->findAll();
		// dump($rowset);die;
		if (count($rowset)>0) foreach($rowset as & $v) {
			$v['print'] = "<input type='checkbox' name='ck' value='{$v['fenpiId']}' id='ck'/>";
			$sql="select employName from jichu_employ where id='{$v['traderId']}'";
			$tradeInfo = $this->_modelExample->findBySql($sql);
			$v['employName'] = $tradeInfo[0]['employName'];		
			$sql="select compName from jichu_client where id='{$v['clientId']}'";
			$clientInfo = $this->_modelExample->findBySql($sql);
			$v['compName'] = $clientInfo[0]['compName'];	
			//$v['mingxi'] = "<a href='".$this->_url('SearchDetail',array('ord2proId'=>$v['ord2proId'],'fenpiId'=>$v['fenpiId']))."' class='thickbox' title='明细'>明细</a>";
		}
		$smarty = & $this->_getView();
		$smarty->assign('title', $this->title);
		$arr_field_info = array(
			'print'=>array("text"=>'<a href="javascript:void(0)" id="ckAll">全选</a>','width'=>'50'),
			//'print'=>array("text"=>"<input type='checkbox' id='checkes' onclick='doCheckAll()' title=''>全选",'width'=>'50'),
			//'mingxi'=>'操作',
			"fenpiDate" =>"时间",
			"orderCode" =>"订单编号",
			"ExpectCode" =>"条码",
			"compName"  =>'客户',
			"employName"=>"业务员",
			"proCode" =>"产品编码",
			"guige" =>"规格",
			"menfu" =>"门幅",
			"kezhong" =>"克重",
			"jianhao" =>"件号",
			'timesPrint' =>'打印次数',
			//"cntM" =>"预计米数",
			
		);
		$smarty->assign('arr_field_info', $arr_field_info);
		$smarty->assign('add_display', 'none');
		$smarty->assign('arr_condition', $arr);
		$smarty->assign('arr_field_value', $rowset);
		//$smarty->assign('checkAll', array('check'=>'__ALL__','temp'=>'biaoqian')+$arr);
		// $msg = "<font color='red'>数量总计:{$zongji['cnt']},金额总计:{$zongji['money']}</font>";
		$smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action'],$arr).$msg));
		$smarty->assign('arr_js_css', $this->makeArrayJsCss(array('grid', 'calendar')));
		$smarty->assign('arrInfo',$arr);
		$smarty->assign('sonTpl', 'Shengchan/PiJian/_jscheckprint.tpl');
		$this->_beforeDisplayRight($smarty);
		$smarty->display('TableList.tpl');
	}
	/**
	 * ps ：打印
	 * Time：2017/06/28 16:05:10
	 * @author zcc
	*/
	function actionPrint(){
		$this->authCheck('1-4');
		$temp=$_GET['temp'];
		/*if($_GET['check']=='__ALL__'){
			$sqlId="SELECT id from shengchan_planpj_fenpi where ord2proId='{$_GET['ord2proId']}'";
			$pjId = $this->_modelExample->findBySql($sqlId);
			$pjId = array_col_values($pjId,'id');
			$_GET['ids'] = implode(',', $pjId);
		}*/
		$sqls="SELECT count(*) as cntAll 
			from shengchan_planpj_fenpi 
			where 
			ord2proId='{$_GET['ord2proId']}' 
			and isbuda=0";
			// dump($sqls);die;
		$cntAll = $this->_modelExample->findBySql($sqls);

		$sql = "SELECT o.orderCode,o.id as sid,x.jitai,x.jianhao,x.cntKg,x.id,
			p.proName,p.guige,o2.menfu,o2.kezhong,x.ExpectCode,e.employName,x.ganghao,x.planId as pjPlanId,x.timesPrint
			FROM shengchan_planpj_fenpi x 
			left join trade_order2product o2 on o2.id =x.ord2proId
			left join trade_order o on o.id = o2.orderId
			left join jichu_product p on p.id = o2.productId
			left join jichu_employ e on e.id =o.traderId
			where x.id in ({$_GET['ids']})";
		$row = $this->_modelExample->findBySql($sql);
		$pages = count($row);
		foreach ($row as $k=> &$v) {
			$sql = "select c.codeAtOrder
			        from trade_order o 
			        left join jichu_client c on o.clientId=c.id
			        where o.id='{$v['sid']}'";
			$cod = $this->_modelExample->findBySql($sql);
			$v['codeAtOrder'] = $cod[0]['codeAtOrder'];
			$v['orderCode'] = $v['orderCode'].'-'.$v['codeAtOrder'];
			$v['date'] = date('Y-m-d');
			$m = & FLEA::getSingleton("TMIS_Common");
			$v['Code'] = $m->getPinyin($v['employName']);
			$this->_modelPlan2SonFenPi->addTimesPrint($v['id']);
			//获得总匹数 检验计划查询中
			// $sql = "SELECT pishuCnt FROM shengchan_planpj where id = '{$v['pjPlanId']}'";
			// $PiJian = $this->_modelExample->findBySql($sql);
			// $v['pishuCnt'] = $PiJian[0]['pishuCnt'];
			$v['month'] = date('m');
			$v['day'] = date('d');
			// if($v['timesPrint']>0){
			// 	$v['curr'] = $k+$pages+$v['timesPrint'];
			// }else{
			// 	$v['curr'] = $k+1;
			// }
			// $v['curr'] = $k+1;
			$v['pageNum'] = $v['jianhao'].'/'.$cntAll[0]['cntAll']; 
			$v['menfu'] = $v['menfu'].'cm';
			$v['kezhong'] = $v['kezhong'].'g/m2';
		}
		$chunk_result = array_chunk($row, 5);
		$showPrint = array_chunk($row, 8); //构造页面上预览用的数组 2019年8月15日 08:28:20 shen

		$smarty = &$this->_getView();
		$smarty->assign('aRow', $row);
		$smarty->assign('batchRes', $chunk_result);
		$smarty->assign('temp',$temp);
		$smarty->assign('showPrint',$showPrint);
		/*$smarty->display('Shengchan/PiJian/printLab.tpl');*/	
		$smarty->display('Shengchan/PiJian/printLab_Jr.tpl');
	}
	//整单删除
	function actionRemove(){
		$this->authCheck('1-3');
		//删除fenpi 表中的数据
		$sql = "DELETE FROM  shengchan_planpj_fenpi where planId = '{$_GET['id']}'";
		$this->_modelPlan2SonFenPi->execute($sql);
		$from = $_GET['fromAction']?$_GET['fromAction']:$_GET['from'];
		if ($this->_modelExample->removeByPkv($_GET['id'])) {
			if($from=='') redirect($this->_url("right"));
			else js_alert(null,"window.parent.showMsg('成功删除')",$this->_url($from));
		}
		else js_alert('出错，不允许删除!',$this->_url($from));
	}
	function actionRemoveByAjax(){
		$id=$_POST['id'];
		$r = $this->_modelPlan2Son->removeByPkv($id);
		if(!$r) {
			// js_alert('删除失败');
			echo json_encode(array('success'=>false,'msg'=>'删除失败'));
			exit;
		}
		echo json_encode(array('success'=>true));
	}
	function actionRemove2Ajax(){
		$id=$_POST['id'];
		//查看是否只有一个唯一的分批，如果是，就不能删除
		$sql="select planId,ord2proId from shengchan_planpj_fenpi where id='{$id}'";
        $fenpiInfo = $this->_modelExample->findBySql($sql);
        $sql1="select count(*) as couts from shengchan_planpj_fenpi where ord2proId='{$fenpiInfo[0]['ord2proId']}' and planId='{$fenpiInfo[0]['planId']}'";
        $counts = $this->_modelExample->findBySql($sql1);
        if($counts[0]['couts']==1){
        	echo json_encode(array('success'=>false,'msg'=>'删除失败,至少保留一条明细！'));
        	exit;
        }
		$r = $this->_modelPlan2SonFenPi->removeByPkv($id);
		if(!$r) {
			// js_alert('删除失败');
			echo json_encode(array('success'=>false,'msg'=>'删除失败'));
			exit;
		}
		echo json_encode(array('success'=>true));
	}
	function actionAdd2(){
		$sql = "SELECT x.id as planId,x.planCode,x.zhcntKg as pibuCnt,x.planDate,p.proCode,p.proName,p.guige,p.color,p.menfu,p.kezhong,y.cntYaohuo,y.dateJiaohuo,y.unit
			FROM shengchan_plan x
			left join trade_order2product y on y.id = x.order2proId
			left join jichu_product p on p.id = x.productId
			where 1 and x.id = {$_GET['planId']}";
		// dump($sql);exit();
		$row = $this->_modelExample->findBySql($sql);	
		$arr = $row[0];
		$smarty = &$this->_getView();
		$smarty->assign('areaMain', $areaMain);
		$smarty->assign('headSon', $headSon);
		$smarty->assign('rowsSon', $rowsSon);
		$smarty->assign('rules', $this->rules);
		//$smarty->assign('sonTpl', 'Cangku/Chengpin/MadanCKJs.tpl');

		$this->_beforeDisplayAdd($smarty);
		$smarty->display('Shengchan/PiJian/PlanEdit.tpl');
	}
	function actionGetCodeNum(){
		$sql = "SELECT
			*, substring_index(ExpectCode, '-' ,-1) AS CODE,jianhao
		FROM
			shengchan_planpj_fenpi
		WHERE
			ord2proId = {$_GET['ord2proId']}
		ORDER BY
			CODE+0 DESC";
		$rowset = $this->_modelExample->findBySql($sql);

		$arr = array(
			'success'=>true,
			'num'=>$rowset[0]['CODE'],
			'jianhao'=>$rowset[0]['jianhao']
		);
		echo json_encode($arr);exit;
	}
}
?>