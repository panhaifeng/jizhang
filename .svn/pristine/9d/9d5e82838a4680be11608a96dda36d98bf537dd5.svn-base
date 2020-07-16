<?php

FLEA::loadClass('Tmis_Controller');
// / 父类，被Controller_Shengchan_Yuliao_ruku继承
class Controller_Shengchan_Shangji extends Tmis_Controller {
	var $_modelDefault;
	var $_modelExample;
	var $fldRight;//浏览时需要显示的字段
	 
	/**
	 * 构造函数
	 */
	function __construct() {
		$this->_modelDefault = &FLEA::getSingleton('Model_Shengchan_Shangji');
		$this->_modelExample = &FLEA::getSingleton('Model_Shengchan_Shangji'); 
		$this->_modelOrder = &FLEA::getSingleton('Model_Trade_Order');
		$this->_modelOrd2pro = &FLEA::getSingleton('Model_Trade_Order2Product');
	}

	/**
	 * 列出所有未安排上机的订单明细
	 */
	function actionListForAdd() {		
		// dump($_POST);exit;
		// /构造搜索区域的搜索类型
		FLEA::loadClass('TMIS_Pager'); 
		$serachArea = TMIS_Pager::getParamArray(array(
			// 'dateFrom' => date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-30, date("Y"))),
			// 'dateTo' => date("Y-m-d"),
			'isSet' => 0, //默认初始化的时候是 未设置
			'clientId' => '',
			'traderId' => '',
			// 'isCheck' => 0,
			'orderCode' => '',
			'key' => '',
		));
		// dump($serachArea);exit;
		$sql = "select
		x.*,
		y.orderCode,
		a.proCode,
		a.proName,
		a.guige,
		a.color,
		a.menfu,
		a.kezhong
		from trade_order2product x
		left join trade_order y on x.orderId=y.id
		left join shengchan_shangji z on x.id=z.ord2proId
		left join jichu_product a on x.productId=a.id
		where 1
		";
		if($serachArea['isSet']==0) {
			$sql .= " and z.id is null";
		} elseif($serachArea['isSet']==1) {
			$sql .= " and z.id>0";
		}
		// $sql .= " and orderDate >= '$serachArea[dateFrom]' and orderDate<='$serachArea[dateTo]'";
		if ($serachArea['key'] != '') $sql .= " and (a.proName like '%{$serachArea['key']}%'
											or a.proCode like '%{$serachArea['key']}%'
											or a.guige like '%{$serachArea['key']}%')";
		// if ($serachArea['isCheck'] < 2) $sql .= " and x.isCheck = '$serachArea[isCheck]'";
		if ($serachArea['orderCode'] != '') $sql .= " and y.orderCode like '%{$serachArea['orderCode']}%'";
		if ($serachArea['clientId'] != '') $sql .= " and x.clientId = '{$serachArea['clientId']}'";
		if ($serachArea['traderId'] != '') $sql .= " and x.traderId = '{$serachArea['traderId']}'";
		$sql .= " group by x.id order by orderDate desc, orderCode desc"; 

		//得到总计
		// $zongji = $this->getZongji($sql,array('cnt'=>'x.cnt','money'=>'x.money'));

		//dump($sql);exit;
		$pager = &new TMIS_Pager($sql);
		$rowset = $pager->findAll(); 
		if (count($rowset) > 0) foreach($rowset as &$value) {
			$value['_edit'] = "<a href='" . $this->_url('Add', array(
				'ord2proId' => $value['id'],
				'fromAction'=>$_GET['action']
			)) . "' title='$title'>设置上机计划</a>";
			//$tip = "ext:qtip='已过账禁止修改'";
			// $value['_edit'] .= " ".$this->getEditHtml($value['rukuId']);
			// $value['_edit'] .= " ".$this->getRemoveHtml($value['rukuId']);
		} 
		// 合计行
		$heji = $this->getHeji($rowset, array('cnt'), '_edit');
		$rowset[] = $heji; 
		// 显示信息
		$arrFieldInfo = array(
			'_edit'=>'上机计划',
			'orderCode'=>'生产编号',
			'proCode' => '编码',
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
		// array_unshift($arrFieldInfo,);
		// dump($arrFieldInfo);exit;
		

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
	 * 新增
	 */
	function actionAdd() {	
		// /从表表头信息
		//机器options
		$sql = "select * from jichu_zhiji where 1";
		$rowset = $this->_modelDefault->findBySql($sql);
		$arrZhiji = array();
		foreach($rowset as & $v) {
			$arrZhiji[] = array('text'=>$v['zhijiCode'],'value'=>$v['zhijiCode']);
		}
		//状态options
		$arrState = array(
			array('text'=>'待上机','value'=>0),
			array('text'=>'生产中','value'=>1),
			array('text'=>'ok','value'=>2),
		);
		$headSon = array(
			'_edit' => array('type' => 'btBtnRemove', "title" => '+5行', 'name' => '_edit[]'),
			'zhijiCode' => array('type' => 'btSelect', "title" => '织机', 'name' => 'zhijiCode[]', 'options'=>$arrZhiji),
			'yongsha' => array('type' => 'bttext', "title" => '用纱', 'name' => 'yongsha[]', ), 
			'dateShangji' => array('type' => 'btCalendar', "title" => '预计上机', 'name' => 'dateShangji[]','inTable'=>true ),
			'dateLiaoji' => array('type' => 'btCalendar', "title" => '预计了机', 'name' => 'dateLiaoji[]','inTable'=>true ),  
			'yaoqiu'=>array('type'=>'bttext',"title"=>'要求','name'=>'yaoqiu[]'),
			'state'=>array('type'=>'btSelect',"title"=>'状态','name'=>'state[]','options'=>$arrState),
			'dateShangjiReal' => array('type' => 'btCalendar', 'title' => '实际上机', 'name' => 'dateShangjiReal[]','inTable'=>true),
			'dateLiaojiReal' => array('type' => 'btCalendar', "title" => '实际了机', 'name' => 'dateLiaojiReal[]','inTable'=>true), 
			'id' => array('type' => 'bthidden', 'name' => 'id[]'),
			'ord2proId' => array('type' => 'bthidden', 'name' => 'ord2proId[]','defaultValue'=>$_GET['ord2proId']),
		); 	

		if($_POST) {
			$rowset = array();
			foreach($_POST['id'] as $k =>& $v) {
				if($_POST['zhijiCode'][$k]=='') continue;
				$temp = array();
				foreach($headSon as $kk =>&$vv) {
					$temp[$kk] = $_POST[$kk][$k];
				}
				$rowset[] = $temp;
			}
			
			//确定数组的数量,当数量大于5个的时候执行
			$number=count($rowset);
			if($number>5){
			//遍历二维数组$rowset,将数组中 ord2proId为空 的遍历到数组中
				foreach ($rowset as &$v) {
					if($v['ord2proId']==''){
						$v['ord2proId']=$rowset[0]['ord2proId'];
					}
				}
			}
			$this->_modelDefault->saveRowset($rowset);
			js_alert(null, 'window.parent.showMsg("保存成功!")', url($_POST['fromController'],$_POST['fromAction']));
			exit;
		}
		//头信息

		$ord2pro = $this->_modelOrd2pro->find(array('id'=>$_GET['ord2proId']));
		$product = $ord2pro['Products'];
		$fldMain = array(
			// /*******2个一行******
			'proCode' => array('title' => '产品编码', "type" => "text", 'value' =>$product['proCode'],'readonly'=>true), 
			'proName' => array('title' => '品名', "type" => "text", 'value' =>$product['proName'],'readonly'=>true), 
			'guige' => array('title' => '规格', "type" => "text", 'value' =>$product['guige'],'readonly'=>true), 
			'color' => array('title' => '颜色', "type" => "text", 'value' =>$product['color'],'readonly'=>true), 
			'menfu' => array('title' => '门幅', "type" => "text", 'value' =>$product['menfu'],'readonly'=>true), 
			'kezhong' => array('title' => '克重', "type" => "text", 'value' =>$product['kezhong'],'readonly'=>true), 
			// 下面为隐藏字段
			// 'ord2proId' => array('type'=>'hidden', 'value'=>$_GET['ord2proId'],'name'=>'ord2proId'),
			// 'plan' => array('type'=>'hidden', 'value'=>$_GET['ord2proId'],'name'=>'ord2proId'),
		); 
		
		
		//得到已安排上机的记录
		$sql = "select * from shengchan_shangji where ord2proId='{$_GET['ord2proId']}'";
		$rowset = $this->_modelDefault->findBySql($sql);
		foreach($rowset as $k =>& $v) {
			$temp = array();
			foreach($headSon as $kk=>&$vv) {
				$temp[$kk] = array('value' => $v[$kk]);
			}
			$rowsSon[] = $temp;
		}

		// 补齐5行
		$len = count($rowsSon);
		for($i = 5;$i>$len;$i--) {
			$rowsSon[] = array();
		} 

		// dump($rowsSon);dump($headSon);
		// 主表区域信息描述
		$areaMain = array('title' => $this->_kind.'产品信息', 'fld' => $fldMain); 
		// 从表区域信息描述
		$smarty = &$this->_getView();
		$smarty->assign('areaMain', $areaMain);
		$smarty->assign('headSon', $headSon);
		$smarty->assign('rowsSon', $rowsSon);
		$smarty->assign('action_save', 'Add');
		$smarty->assign('rules', $rules);
		$smarty->display('Main2Son/T1.tpl');
	} 	
	/**
	 * 显示已完成任务
	 *$isShowAdd:是否显示新增按钮
	 */
	function actionRight($isShowAdd) {
		FLEA::loadClass('TMIS_Pager');
		$serachArea = TMIS_Pager::getParamArray(array(
			// 'dateFrom' => date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-30, date("Y"))),
			// 'dateTo' => date("Y-m-d"),
			'clientId' => '',
			'traderId' => '',
			// 'isCheck' => 0,
			'orderCode' => '',
			'key' => '',
		));
		$sql = "select
		z.*,(substring(z.zhijiCode,2,2)+0) as Code,
		x.cntYaohuo,x.unit,x.dateJiaohuo,x.id as ord2proId,
		a.proCode,
		a.proName,
		a.guige,
		a.color,
		a.menfu,
		a.kezhong,
		y.orderCode
		from shengchan_shangji z		
		inner join trade_order2product x on x.id=z.ord2proId
		left join trade_order y on x.orderId=y.id
		left join jichu_product a on x.productId=a.id
		where z.state<2
		";
		// $sql .= " and orderDate >= '$serachArea[dateFrom]' and orderDate<='$serachArea[dateTo]'";
		if ($serachArea['key'] != '') $sql .= " and (y.orderCode like '%{$serachArea['key']}%'
											or a.proName like '%{$serachArea['key']}%'
											or a.proCode like '%{$serachArea['key']}%'
											or a.guige like '%{$serachArea['key']}%')";
		// if ($serachArea['isCheck'] < 2) $sql .= " and x.isCheck = '$serachArea[isCheck]'";
		if ($serachArea['orderCode'] != '') $sql .= " and y.orderCode like '%{$serachArea['orderCode']}%'";
		if ($serachArea['clientId'] != '') $sql .= " and y.clientId = '{$serachArea['clientId']}'";
		if ($serachArea['traderId'] != '') $sql .= " and y.traderId = '{$serachArea['traderId']}'";
		$sql .= " order by Code,z.dateShangji"; 
		
		$pager = &new TMIS_Pager($sql,null,null,200);
		$rowset = $pager->findAll(); 
		if (count($rowset) > 0) foreach($rowset as &$v) {
			// $v['_edit'] = "<a href='" . $this->_url('Add', array(
			// 	'ord2proId' => $v['id'],
			// 	'fromAction' =>"right",
			// )) . "' >调整</a>";

			if($v['dateJiaohuo']=='0000-00-00') $v['dateJiaohuo'] = '';
			if($v['dateShangji']=='0000-00-00') $v['dateShangji'] = '';
			if($v['dateLiaoji']=='0000-00-00') $v['dateLiaoji'] = '';
			if($v['dateShangjiReal']=='0000-00-00') $v['dateShangjiReal'] = '';
			if($v['dateLiaojiReal']=='0000-00-00') $v['dateLiaojiReal'] = '';

			if($v['state']==0) {
				$v['state'] = '未上机';
			} elseif($v['state']==1) {
				$v['state'] = '生产中';
			}else {
				$v['state'] = '已了机';
			}
			if($_GET['export']!=1) {
				$v['state'] .= " <a href='" . $this->_url('Add', array(
					'ord2proId' => $v['ord2proId'],
					'fromAction' =>$_GET['action'],
				)) . "' >调整</a>";
			}
			$v['_edit'] = " <a href='" . $this->_url('Remove', array(
					'id' => $v['id'],
					'ord2proId' => $v['ord2proId'],
				)) . "' >删除</a>";
			
			// //如果机台上上面的一样，不显示
			// if($v['zhijiCode']==$zhijiCode) {
			// 	$v['zhijiCode'] = '';
			// } else {
			// 	$zhijiCode = $v['zhijiCode'];
			// }

			//状态调整

			// dump($v);dump($zhijiCode);
		} 
		// 左边信息
		$arrFieldInfo = array(
			'_edit'=>'操作',
			'zhijiCode'=>array("text"=>'织机',"width"=>50),
			'orderCode'=>'生产编号',
			'proCode' => '产品编码',
			'proName' => '品名',
			'guige' => '规格',
			'color'=>array("text"=>'颜色',"width"=>50),
			'menfu'=>'门幅',
			'kezhong'=>'克重',
			"cntYaohuo" => '要货数量',
			"unit" => '单位',
			"dateJiaohuo" => '订单交期',
			"yaoqiu" => '要求',
			"yongsha" => '用纱',
			"state" => '状态',
			"dateShangji" => '预计上机',
			"dateLiaoji" => '预计了机',
			"dateShangjiReal" => '实际上机',
			"dateLiaojiReal" => '了机时间',
			// "danjia" => '单价',
			// "money" => '金额', 
			// "memo" =>'产品备注'
		);

		$smarty = &$this->_getView();
		$smarty->assign('title', '金马劲飞布业纺织上机计划');
		$smarty->assign('arr_field_info', $arrFieldInfo);
		$smarty->assign('add_display', $isShowAdd?'display':'none');
		$smarty->assign('arr_condition', $serachArea);
		$smarty->assign('arr_field_value', $rowset);
		$smarty->assign('fn_export',$this->_url($_GET['action'],array(
			'export'=>1
		)));
		// $smarty->assign('export_href',);
		// $msg = "<font color='red'>数量总计:{$zongji['cnt']},金额总计:{$zongji['money']}</font>";
		$smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action']), $serachArea).$msg);
		$smarty->assign('arr_js_css', $this->makeArrayJsCss(array('grid', 'calendar')));
		if($_GET['export']!=1) {
			// $smarty->assign('page_info',$pager->getNavBar());
			$smarty->display('TableList.tpl');
			exit;
		}
		// $smarty->display('TableList.tpl'); 
		//导出
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/force-download");
		header("Content-Type: application/download");
		header("Content-Disposition: attachment;filename=test.xls");
		header("Content-Transfer-Encoding: binary");
		$smarty->display('Export2Excel.tpl');
		// $smarty->display('TblListMore.tpl');
	}

	/**
	 * 已完成任务列表
	 */
	function actionListOver($isShowAdd) {
		FLEA::loadClass('TMIS_Pager');
		$serachArea = TMIS_Pager::getParamArray(array(
			// 'dateFrom' => date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-30, date("Y"))),
			// 'dateTo' => date("Y-m-d"),
			'clientId' => '',
			'traderId' => '',
			// 'isCheck' => 0,
			'orderCode' => '',
			'key' => '',
		));
		$sql = "select
		z.*,
		x.cntYaohuo,x.unit,x.dateJiaohuo,x.id as ord2proId,
		a.proCode,
		a.proName,
		a.guige,
		a.color,
		a.menfu,
		a.kezhong,
		y.orderCode
		from shengchan_shangji z		
		inner join trade_order2product x on x.id=z.ord2proId
		left join trade_order y on x.orderId=y.id
		left join jichu_product a on x.productId=a.id
		where z.state=2
		";
		// $sql .= " and orderDate >= '$serachArea[dateFrom]' and orderDate<='$serachArea[dateTo]'";
		if ($serachArea['key'] != '') $sql .= " and (y.orderCode like '%{$serachArea['key']}%'
											or a.proName like '%{$serachArea['key']}%'
											or a.proCode like '%{$serachArea['key']}%'
											or a.guige like '%{$serachArea['key']}%')";
		// if ($serachArea['isCheck'] < 2) $sql .= " and x.isCheck = '$serachArea[isCheck]'";
		if ($serachArea['orderCode'] != '') $sql .= " and y.orderCode like '%{$serachArea['orderCode']}%'";
		if ($serachArea['clientId'] != '') $sql .= " and y.clientId = '{$serachArea['clientId']}'";
		if ($serachArea['traderId'] != '') $sql .= " and y.traderId = '{$serachArea['traderId']}'";
		$sql .= " order by z.zhijiCode desc"; 
        //dump($sql);exit;
		$pager = &new TMIS_Pager($sql,null,null,200);
		$rowset = $pager->findAll(); 
		if (count($rowset) > 0) foreach($rowset as &$v) {
			// $v['_edit'] = "<a href='" . $this->_url('Add', array(
			// 	'ord2proId' => $v['id'],
			// 	'fromAction' =>"right",
			// )) . "' >调整</a>";

			if($v['dateJiaohuo']=='0000-00-00') $v['dateJiaohuo'] = '';
			if($v['dateShangji']=='0000-00-00') $v['dateShangji'] = '';
			if($v['dateShangjiReal']=='0000-00-00') $v['dateShangjiReal'] = '';
			if($v['dateLiaojiReal']=='0000-00-00') $v['dateLiaojiReal'] = '';

			if($v['state']==0) {
				$v['state'] = '未上机';
			} elseif($v['state']==1) {
				$v['state'] = '生产中';
			}else {
				$v['state'] = '已了机';
			}
			if($_GET['export']!=1) {
				$v['state'] .= " <a href='" . $this->_url('Add', array(
					'ord2proId' => $v['ord2proId'],
					'fromAction' =>$_GET['action'],
				)) . "' >调整</a>";
			}
			
			//如果机台上上面的一样，不显示
			if($v['zhijiCode']==$zhijiCode) {
				$v['zhijiCode'] = '';
			} else {
				$zhijiCode = $v['zhijiCode'];
			}

			//状态调整

			// dump($v);dump($zhijiCode);
		} 
		// exit;
		// 合计行
		// $heji = $this->getHeji($rowset, array('cnt'), '_edit');
		// $rowset[] = $heji; 
		// 左边信息
		$arrFieldInfo = array(
			// '_edit'=>'操作',
			'zhijiCode'=>array("text"=>'织机',"width"=>50),
			'orderCode'=>'生产编号',
			'proCode' => '产品编码',
			'proName' => '品名',
			'guige' => '规格',
			'color'=>array("text"=>'颜色',"width"=>50),
			'menfu'=>'门幅',
			'kezhong'=>'克重',
			"cntYaohuo" => '要货数量',
			"unit" => '单位',
			"dateJiaohuo" => '订单交期',
			"yaoqiu" => '要求',
			"yongsha" => '用纱',
			"state" => '状态',
			"dateShangji" => '预计上机',
			"dateShangjiReal" => '实际上机',
			"dateLiaojiReal" => '了机时间',
			// "danjia" => '单价',
			// "money" => '金额', 
			// "memo" =>'产品备注'
		);

		$smarty = &$this->_getView();
		// $smarty->assign('title', '订单查询'); 
		$smarty->assign('arr_field_info', $arrFieldInfo);
		$smarty->assign('title', '已了机清单');
		$smarty->assign('add_display', $isShowAdd?'display':'none');
		$smarty->assign('arr_condition', $serachArea);
		$smarty->assign('arr_field_value', $rowset);
		$smarty->assign('fn_export',$this->_url($_GET['action'],array(
			'export'=>1
		)));
		// $smarty->assign('export_href',);
		// $msg = "<font color='red'>数量总计:{$zongji['cnt']},金额总计:{$zongji['money']}</font>";
		$smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action']), $serachArea).$msg);
		$smarty->assign('arr_js_css', $this->makeArrayJsCss(array('grid', 'calendar')));
		if($_GET['export']!=1) {
			// $smarty->assign('page_info',$pager->getNavBar());
			$smarty->display('TableList.tpl');
			exit;
		}
		// $smarty->display('TableList.tpl'); 
		//导出
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/force-download");
		header("Content-Type: application/download");
		header("Content-Disposition: attachment;filename=test.xls");
		header("Content-Transfer-Encoding: binary");
		$smarty->display('Export2Excel.tpl');
		// $smarty->display('TblListMore.tpl');
	}

	/**
	 * 编辑界面利用ajax删除
	 */
	function actionRemoveByAjax() {
		$m = & $this->_modelDefault;
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
	/**
	 * ps ：
	 * Time：2016/07/19 15:45:28
	 * @author Sjj
	 * @param 参数类型
	 * @return 返回值类型
	*/
	function actionRemove(){
		$m = & $this->_modelDefault;
		$r = $m->removeByPkv($_GET['id']);
		js_alert(null,'window.parent.showMsg("删除成功!")', $this->_url('Right'));

	}

	
}

?>