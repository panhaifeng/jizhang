<?php
/*********************************************************************\
*  Copyright (c) 1998-2013, TH. All Rights Reserved.
*  Author :Jeff
*  FName  :Init.php
*  Time   :2014/05/12 13:55:16
*  Remark :库存初始化模块
\*********************************************************************/
FLEA::loadClass('Controller_Cangku_Ruku');
class Controller_Cangku_Yuanliao_Init extends Controller_Cangku_Ruku {
	// **************************************构造函数 begin********************************
	function __construct() {
		// $this->_kuwei = '原料仓库';//库位
		$this->_head = 'INITA';
		$this->_kind='初始化';
		$this->_state = '原料';
		$this->_single = 'true'; //标记库位是否分组
		$this->_modelDefault = &FLEA::getSingleton('Model_Cangku_Yuanliao_Ruku');
		$this->_modelExample = &FLEA::getSingleton('Model_Cangku_Yuanliao_Ruku');
		// $this->_modelExample = &FLEA::getSingleton('Model_Cangku_Yuanliao_Ruku');
		// $this->_modelExample = &FLEA::getSingleton('Model_Cangku_Yuanliao_Ruku');
		$this->_modelMain = &FLEA::getSingleton('Model_Cangku_Yuanliao_Ruku');
		$this->_modelSon = &FLEA::getSingleton('Model_Cangku_Yuanliao_Ruku2Product');
		parent::__construct();

		unset($this->fldMain['songhuoCode']);
		unset($this->fldRight['songhuoCode']);
		//浏览界面的字段
		$this->fldRight = array(
			//'id'=>'id',
			"rukuDate" => "入库日期",
			'cnt' => array("text"=>'数量(折合公斤数)','width'=>115),
			'ganghao'=>'缸号(缸号)',
			// 'state' => '状态',
			// 'proKind' => '种类',
			'proCode' => '产品编码',
			'proName' => '品名',
			'guige' => '规格',
			'color' => '颜色',
			'cntM' => '数量(kg)',
			'danjia' => '单价',
			'money' => '金额',
			'cntJian' => '件数',
			"compName" => "供应商",
			'songhuoCode' => '送货单号',
			'rukuCode' => array("text"=>'入库单号','width'=>150),
			'memo' => '备注',
			"kind" => "类别",
			'kuwei' => '库位'
		);
	}

	//浏览界面显示新增按钮
	function actionRight() {
		$this->authCheck('3-1-1');
		FLEA::loadClass('TMIS_Pager');
		// /构造搜索区域的搜索类型
		$serachArea = TMIS_Pager::getParamArray(array(
			'dateFrom' => date("Y-m-01"),
			'dateTo' => date("Y-m-d"),
			'supplierId' => '',
			'key' => '',
			'ganghao'=>'',
		));
		$sql = "select
			y.rukuCode,
			y.kuwei,
			y.rukuDate,
			y.supplierId,
			y.memo as rukuMemo,
			y.kind,
			y.songhuoCode,
			x.id,
			x.pihao,
			x.ganghao,
			x.rukuId,
			x.productId,
			x.cnt,
			x.danjia,
			x.money,
			x.memo,
			x.cntJian,
			b.proCode,
			b.proName,
			b.guige,
			b.color,
			b.kind as proKind,
			a.compName as compName
			from cangku_common_ruku y
			left join cangku_common_ruku2product x on y.id=x.rukuId
			left join jichu_supplier a on y.supplierId=a.id
			left join jichu_product b on x.productId=b.id
			where y.kind='{$this->_kind}'";
		$sql .= " and rukuDate >= '{$serachArea['dateFrom']}' and rukuDate<='{$serachArea['dateTo']}'";
		if ($serachArea['key'] != '') $sql .= " and (b.proName like '%{$serachArea['key']}%'
											or b.proCode like '%{$serachArea['key']}%'
											or b.color like '%{$serachArea['key']}%'
											or x.pihao like '%{$serachArea['key']}%'
											or b.guige like '%{$serachArea['key']}%')";
		if ($serachArea['rukuCode'] != '') $sql .= " and y.rukuCode like '%{$serachArea['rukuCode']}%'";
		if ($serachArea['supplierId'] != '') $sql .= " and y.supplierId = '{$serachArea['supplierId']}'";
		if($serachArea['ganghao'] != ''){
			$sql .=" and x.ganghao like '%{$serachArea['ganghao']}%'";
		}
		$sql .= " order by y.rukuCode desc";
		// dump($sql);exit;
		//得到总计
		$zongji = $this->getZongji($sql,array('cnt'=>'x.cnt','money'=>'x.money'));

		$pager = &new TMIS_Pager($sql);
		$rowset = $pager->findAll();
		if (count($rowset) > 0) foreach($rowset as &$value) {
			$value['_edit'] = "<a href='" . $this->_url('View', array('id' => $value['rukuId'])) . "' target='_blank' title='$title'>打印</a>";
			//$tip = "ext:qtip='已过账禁止修改'";
			if($value['kind']=='采购退货') {
				$value['_edit'] .= " <a href='".url('Cangku_Yuanliao_Cgth','Edit',array(
					'id'=>$value['rukuId'],
					'fromAction' => $_GET['action']
				))."'>修改</a>";
			} else {
				$value['_edit'] .= " <a href='".url('Cangku_Yuanliao_Ruku','Edit',array(
					'id'=>$value['rukuId'],
					'fromAction' => $_GET['action']
				))."'>修改</a>";
			}
			$value['_edit'] .= " ".$this->getRemoveHtml($value['rukuId']);

			if($value['cnt']<0) $value['_bgColor'] = 'pink';
		}
		// 合计行
		$heji = $this->getHeji($rowset, array('cnt'), '_edit');
		$rowset[] = $heji;
		// 左边信息
		$arrFieldInfo = array("_edit" => '操作')+$this->fldRight;
		// array_unshift($arrFieldInfo,);
		// dump($arrFieldInfo);exit;


		$smarty = &$this->_getView();
		$smarty->assign('title', '订单查询');
		$smarty->assign('arr_field_info', $arrFieldInfo);
		// $smarty->assign('add_display', $isShowAdd?'display':'none');
		$smarty->assign('arr_condition', $serachArea);
		$smarty->assign('arr_field_value', $rowset);
		$msg = "<font color='red'>数量总计:{$zongji['cnt']},金额总计:{$zongji['money']}</font>";
		$smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action']), $serachArea).$msg);
		$smarty->assign('arr_js_css', $this->makeArrayJsCss(array('grid', 'calendar')));
		$smarty->display('TableList.tpl');
	}

	//浏览时显示新增按钮
	// function _beforeDisplayRight(&$smarty) {
	// 	dump($smarty);exit;
	// 	$smarty->assign('add_display','');
	// 	// $f = & $smarty->_tpl_vars['arr_field_info'];
	// 	// unset($f['depName']);
	// 	// unset($f['danjia']);
	// 	// unset($f['money']);
	// 	// $f['memo'] = "备注";
	// 	// $areaMain = & $smarty->_tpl_vars['areaMain'];
	// 	// // dump($smarty->_tpl_vars);dump($areaMain);exit;
	// 	// $orderId= $areaMain['fld']['orderId']['value'];
	// 	// $sql = "select orderCode from trade_order where id='{$orderId}'";
	// 	// // dump($sql);
	// 	// $_rows = $this->_modelExample->findBySql($sql);

	// 	// $areaMain['fld']['orderId']['text'] = $_rows[0]['orderCode'];
	// }
}