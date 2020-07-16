<?php
/*********************************************************************\
*  Copyright (c) 1998-2013, TH. All Rights Reserved.
*  Author :Zy
*  FName  :Cgru.php
*  Time   :2014/05/13 18:31:40
*  Remark :成品采购入库	控制器
\*********************************************************************/
FLEA::loadClass('Controller_Cangku_Ruku');
class Controller_Cangku_Chengpin_Cgru extends Controller_Cangku_Ruku {
	// var $fldMain;
	// var $headSon;
	// var $rules;//表单元素的验证规则
	// **************************************构造函数 begin********************************
	function __construct() {
		$this->_state = '成品';
		$this->_head = 'CGRKA';
		$this->_kind='采购入库';
		$this->_modelDefault = &FLEA::getSingleton('Model_Cangku_Chengpin_Ruku');
		$this->_modelExample = &FLEA::getSingleton('Model_Cangku_Chengpin_Ruku');
		$this->_modelMain = &FLEA::getSingleton('Model_Cangku_Chengpin_Ruku');
		$this->_modelSon = &FLEA::getSingleton('Model_Cangku_Chengpin_Ruku2Product');

		//浏览界面的字段
		$this->fldRight = array(
			"rukuDate" => "入库日期",
			"kind" => "类别",
			'kuwei' => '库位',
			// 'state' => '状态',
			// 'proKind' => '种类',
			'proCode' => '产品编码',
			'proName' => '品名',
			'guige' => '规格',
			'color' => '颜色',
			// 'pihao'=>'批号',
			// 'color' => '颜色',
			'cntJian' => '件数',
			'cnt' => '公斤数',
			'cntM' => '米数',
			// 'danjia' => '单价',
			// 'money' => '金额',
			// "orderCode" => "相关订单",
			// 'songhuoCode' => '送货单号',
			'rukuCode' => array("text"=>'入库单号','width'=>150),
			'memo' => '备注'
		);
		//得到库位信息
		// 生成库位 名称信息
		$m = & FLEA::getSingleton('Model_Jichu_Client');
		$sql = "select * from jichu_kuwei where 1";
		$rowset = $m->findBySql($sql);
		foreach($rowset as &$v) {
			// *根据要求：options为数组,必须有text和value属性
			$rowsKuwei[] = array('text' => $v['kuweiName'], 'value' => $v['kuweiName']);
		}

		// 定义模板中的主表字段
		$this->fldMain = array(
			// /*******2个一行******
			'rukuDate' => array('title' => '入库日期', "type" => "calendar", 'value' => date('Y-m-d')),
			// 入库单号，自动生成
			'rukuCode' => array('title' => '入库单号', 'type' => 'text', 'readonly' => true, 'value' => ''),

			/*'supplierId' => array('title' => '供应商', 'type' => 'selectgys', 'value' => '', 'model' => 'Model_Jichu_Supplier'),*/

			/**
			 * ps ：改成弹出框
			 * Time：2015/11/24 09:55:21
			 * @author Zhujunjie
			 * @param 参数类型
			 * @return 返回值类型
			*/
			'supplierId' => array(
					'type' => 'popup',
					"title" => '供应商',
					'name' => 'supplierId',
					'url'=>url('Jichu_supplier','Popup'),
					'textFld'=>'compName',
					'hiddenFld'=>'id',
			),

			// 'songhuoCode' => array('title' => '送货单号', 'type' => 'text', 'value' => ''),
			'kind' => array('title' => '入库类别', 'type' => 'text', 'value' => $this->_kind, 'readonly' => true),
			'kuwei' => array('title' => '库位选择', 'type' => 'kuwei', 'value' => ''),
			// 'state' => array('title' => '状态', 'type' => 'text', 'value' =>$this->_state, 'readonly'=>true),
			// /*******2个一行******
			// 定义了name以后，就不会以memo作为input的id了
			'memo' => array('title' => '备注', 'type' => 'textarea', 'disabled' => true, 'name' => 'rukuMemo'),
			// 下面为隐藏字段
			'id' => array('type'=>'hidden', 'value'=>$_GET['id'],'name'=>'rukuId'),
			// 'isGuozhang' => array('type' => 'hidden', 'value' => ''),
		);
		// /从表表头信息
		// /type为控件类型,在自定义模板控件
		// /title为表头
		// /name为控件名
		// /bt开头的type都在webcontrolsExt中写的,如果有新的控件，也需要增加
$this->headSon = array(
			'_edit' => array('type' => 'btBtnCopy', "title" => '+5行', 'name' => '_edit[]'),
// 			'productId' => array('type' => 'btProductpopup', "title" => '产品编码', 'name' => 'productId[]'),
			'productId'=>array(
					'title' => '色纱品名',
					'type' => 'btpopup',
					'value' => '',
					'name'=>'productId[]',
					'text'=>'色纱品名',
					'url'=>url('Jichu_chanpin','popup'),//弹出地址,回调函数在sonTpl中进行定义,主要是对缓存中的关联数组进行重写
					'textFld'=>'proCode',//显示在text中的字段
					'hiddenFld'=>'id',//显示在hidden控件中的字段
					'disabled'=>'true'
			),
			'proName' => array('type' => 'bttext', "title" => '品名', 'name' => 'proName[]', 'readonly' => true),
			'guige' => array('type' => 'bttext', "title" => '规格', 'name' => 'guige[]', 'readonly' => true),
			'color' => array('type' => 'bttext', "title" => '颜色', 'name' => 'color[]', 'readonly' => true),
			'menfu' => array('type' => 'bttext', "title" => '门幅', 'name' => 'menfu[]', 'readonly' => true),
			'kezhong' => array('type' => 'bttext', "title" => '克重', 'name' => 'kezhong[]', 'readonly' => true),
			'ganghao' => array('type' => 'bttext', 'title' => '缸号', 'name' => 'ganghao[]'),
			'cntJian' => array('type' => 'bttext', "title" => '件数', 'name' => 'cntJian[]'),
			'cntOrg' => array('type' => 'bttext', "title" => '数量', 'name' => 'cntOrg[]'),
			//'cntM' => array('type' => 'bttext', "title" => '米数', 'name' => 'cntM[]'),
			'unit'=>array('type'=>'btSelect','title'=>'单位','name'=>'unit[]','options'=>array(
				array('text'=>'公斤','value'=>'公斤'),
				array('text'=>'米','value'=>'米'),
				array('text'=>'码','value'=>'码'),
				array('text'=>'磅','value'=>'磅'),
				array('text'=>'条','value'=>'条'),
				)),
			'cnt'=>array('type' => 'bttext', "title" => '折合公斤数', 'name' => 'cnt[]'),
			'danjia' => array('type' => 'bttext', "title" => '单价', 'name' => 'danjia[]'),
			'bizhong' => array('title' => '交易币种', 'type' => 'btSelect','name'=>'bizhong[]', 'value' => '',
				'options' => array(
					array('text' => 'RMB', 'value' => 'RMB'),
					array('text' => 'USD', 'value' => 'USD'),
					)),
			'money' => array('type' => 'bttext', "title" => '金额(元)', 'name' => 'money[]'),
			'jiagonghuId' => array('title' => '整理厂', 'type' => 'btselectNew', 'value' => '','name' => 'jiagonghuId[]', 'model' => 'Model_Jichu_Jiagonghu','orderBy'=>'paixu'),
			'memo' => array('type' => 'bttext', "title" => '备注', 'name' => 'memo[]'),
			// ***************如何处理hidden?
			'id' => array('type' => 'bthidden', 'name' => 'id[]'),
			'ord2proId' => array('type' => 'bthidden', 'name' => 'ord2proId[]'),
		);
		// 表单元素的验证规则定义
		$this->rules = array('rukuDate' => 'required',
			// 'orderDate'=>'required',
			'orderId' => 'required',
			'kuwei' => 'required',
			//'jiagonghuId[]'=>'required'
			// 'traderId'=>'required'
		);

	}
	/* function actionRemove() {
		dump($_GET);die;
		dump($_GET['comdId']);die;
		$mCprk = & FLEA::getSingleton('Model_Cangku_Chengpin_Ruku');
		if($mCprk->removeByPkv($_GET['comdId']))
			js_alert("","",$this->_url('right'));
	} */
	//新增时调整子模板
	function _beforeDisplayAdd(&$smarty) {
		$smarty->assign('sonTpl', 'Cangku/Chengpin/jsRuku.tpl');
		$smarty->assign('sonTpl2', 'Cangku/Chengpin/MadanRukuJs.tpl');
	}
	function actionAdd(){
		$this->authCheck('3-2-9');
				// 主表信息字段
		$fldMain = $this->fldMain;
	    //找到所有属于成品的库位
		$kuwei = $this->_state;
		$str = "select kuweiName from jichu_kuwei where type='{$kuwei}'";
		$res = $this->_modelExample->findBySql($str);
		$fldMain['kuwei']['value'] = $res[0]['kuweiName'];
		// *入库号的默认值的加载*
		$fldMain['rukuCode']['value'] = $this->_getNewCode($this->_head, $this->_modelMain->qtableName, 'rukuCode');
		// 判断是否需要过账 0要 1否
		// $fldMain['isGuozhang']['value'] = 1;
		// dump($fldMain);exit;
		$headSon = $this->headSon;
		// 从表信息字段,默认5行 //整理厂默认 佳程整理
		$sql = "SELECT * FROM jichu_jiagonghu WHERE compName='佳程整理'";
		$temp = $this->_modelExample->findBySql($sql);
		for($i = 0;$i < 5;$i++) {
			//$rowsSon[]['jiagonghuId']['value'] = $temp[0]['id'];;
			$rowsSon[] = array();
		}
		// 主表区域信息描述
		$areaMain = array('title' => $this->_kind.'基本信息', 'fld' => $fldMain);
		// 从表区域信息描述
		$smarty = &$this->_getView();
		$smarty->assign('areaMain', $areaMain);
		$smarty->assign('headSon', $headSon);
		$smarty->assign('rowsSon', $rowsSon);
		$smarty->assign('rules', $this->rules);
		$smarty->assign('sonTpl', 'Cangku/Chengpin/_callbackCgrk.tpl');
		//$this->_beforeDisplayAdd($smarty);
		// dump($smarty);exit;
		$smarty->display('Main2Son/T1.tpl');
	}
	function actionRight(){
		FLEA::loadClass('TMIS_Pager');
		// /构造搜索区域的搜索类型
		$serachArea = TMIS_Pager::getParamArray(array(
			'dateFrom' => date("Y-m-01"),
			'dateTo' => date("Y-m-d"),
			// 'supplierId' => '',
			'key' => '',
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
			x.rukuId,
			x.productId,
			x.cnt,
			x.danjia,
			x.money,
			x.memo,
			x.cntJian,
			x.cntM,
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
			where y.kuwei in ('成品仓库','疵品仓库')
			";
		$sql .= " and rukuDate >= '{$serachArea['dateFrom']}' and rukuDate<='{$serachArea['dateTo']}'";
		if ($serachArea['key'] != '') $sql .= " and (b.proName like '%{$serachArea['key']}%'
											or b.proCode like '%{$serachArea['key']}%'
											or b.guige like '%{$serachArea['key']}%')";
		if ($serachArea['rukuCode'] != '') $sql .= " and y.rukuCode like '%{$serachArea['rukuCode']}%'";
		if ($serachArea['supplierId'] != '') $sql .= " and y.supplierId = '{$serachArea['supplierId']}'";
		$sql .= " order by y.rukuCode desc";
		//得到总计
		$zongji = $this->getZongji($sql,array('cnt'=>'x.cnt','money'=>'x.money'));
        //dump($sql);exit;
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
			} elseif($value['kind']=='生产入库') {
				$value['_edit'] .= " <a href='".url('Cangku_Chengpin_Ruku','Edit',array(
					'id'=>$value['rukuId'],
					'fromAction' => $_GET['action']
				))."'>修改</a>";
				// 设置码单
				// 查找是否存在码单
				// $sql="select id from cangku_madan where ruku2proId='{$value['id']}' limit 0,1";
				// $temp=$this->_modelExample->findBySql($sql);
				// $color='';
				// $title='';
				// if($temp[0]['id']>0){
				// 	$color="green";
				// 	$title="码单已设置";
				// }
				// $value['_edit'] .=" <a style='color:{$color}' href='".url('Cangku_Chengpin_Ruku','SetMadan',array('ruku2proId'=>$value['id']))."' title='{$title}'>码单</a>";
			} elseif($value['kind']=='成品初始化') {
				$value['_edit'] .= " <a href='".url('Cangku_Chengpin_RukuInit','Edit',array(
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

            //码单导出
            if($value['kind']=='生产入库'){
            	$sql="select id from cangku_madan where ruku2proId='{$value['id']}' limit 0,1";
				$temp=$this->_modelExample->findBySql($sql);
				$color='';
				$title='';
				if($temp[0]['id']>0){
					//有码单入库信息
					$value['_edit'] .=" <a style='color:{$color}' href='".url('Cangku_Chengpin_Madan','SelexportRuku',array('id'=>$value['id']))."' target='_blank' title='{$title}'>码单导出</a>";
				}
            }
			if($value['cnt']<0) $value['_bgColor'] = 'pink';
		}
		// 合计行
		$heji = $this->getHeji($rowset, array('cnt'), '_edit');
		$rowset[] = $heji;
		// 左边信息
		$arrFieldInfo = array(
			'_edit'=>array("text"=>'操作','width'=>170),
			// 'id'=>'从表id',
			// 'rukuId'=>'主表id',
			"rukuDate" => "入库日期",
			"kind" => "类别",
			'kuwei' => '库位',
			// 'state' => '状态',
			// 'proKind' => '种类',
			'proCode' => '产品编码',
			'proName' => '品名',
			'guige' => '规格',
			'color' => '颜色',
			//'pihao'=>'批号',
			'cntJian' => '件数',
			'cnt' => '数量',
			'danjia' => '单价',
			'money' => '金额',
			// "compName" => "供应商",
			'songhuoCode' => '送货单号',
			'rukuCode' => array("text"=>'入库单号','width'=>150),
			'memo' => '备注'
	    );
		// array_unshift($arrFieldInfo,);
		// dump($arrFieldInfo);exit;


		$smarty = &$this->_getView();
		$smarty->assign('title', '订单查询');
		$smarty->assign('arr_field_info', $arrFieldInfo);
		$smarty->assign('add_display', $isShowAdd?'display':'none');
		$smarty->assign('arr_condition', $serachArea);
		$smarty->assign('arr_field_value', $rowset);
		$msg = "<font color='red'>数量总计:{$zongji['cnt']},金额总计:{$zongji['money']}</font>";
		$smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action']), $serachArea).$msg);
		$smarty->assign('arr_js_css', $this->makeArrayJsCss(array('grid', 'calendar')));
		$this->_beforeDisplayRight($smarty);
		$smarty->display('TableList.tpl');
		// $smarty->display('TblListMore.tpl');
	}

	// /**
	//  * 添加码单信息
	//  * Time：2014/06/25 15:30:23
	//  * @author li
	// */
	// function actionSetMadan(){
	// 	//$this->authCheck();
	// 	$_GET['ruku2proId']=(int)$_GET['ruku2proId'];
	// 	//查找所有已设置的码单信息
	// 	$madan = & FLEA::getSingleton('Model_Cangku_Chengpin_Madan');
	// 	$madan->clearLinks();
	// 	//查找所有码单
	// 	$madanArr = $madan->findAll(array('ruku2proId'=>$_GET['ruku2proId']));
	// 	$temp=array();
	// 	foreach ($madanArr as $key => & $v) {
	// 		//标示已出库的
	// 		if($v['chuku2proId']>0)$v['readonly']=true;
	// 		$temp[$v['number']-1]=$v;
	// 	}
	// 	$madanArr=$temp;
	// 	//dump($madanArr);exit;
 //        // dump(json_encode($madanArr));exit;
	// 	$smarty = & $this->_getView();
	// 	$smarty->assign('title', "设置码单");
	// 	$smarty->assign('ruku2proId', $_GET['ruku2proId']);
	// 	$smarty->assign('madanRows', json_encode($madanArr));
	// 	$smarty->assign('arr_field_value', $madanArr[0]);
	// 	$smarty->display("Cangku/Chengpin/RkDajuanEdit.tpl");
	// }

	/**
	 * 保存码单信息
	 * Time：2014/06/25 17:15:18
	 * @author li
	*/
	function actionSaveMadanByAjax(){
		// dump($_POST);exit;
		$_P = json_decode($_POST['jsonStr'],true);
		// dump($_P);
		$madan_arr = array();//需要保存的码单信息
		$madan_clear = array();//需要删除的码单信息

		foreach ($_P as $key => & $v) {
			//数量不存在，说明该码单不需要保存
			if(empty($v['cntFormat']) && empty($v['cnt_M'])&&empty($v['cntMadan'])){
				//如果id存在，则说明该码单需要在数据表中删除
				if($v['id']>0){
					$madan_clear[]=$v['id'];
				}
				continue;
			}
			//入库明细表id
			$madan_arr[]=array(
				'id'=>$v['id']+0,
				'ruku2proId'=>$_POST['ruku2proId'],
				'number'=>$v['number'],
				'cntFormat'=>$v['cntFormat'],
				'cnt'=>$v['cnt'],
				'cntM'=>$v['cntM'],
				'cnt_M'=>$v['cnt_M'],
				'cntMadan'=>$v['cntMadan'],
				'lot'=>$v['lot'].'',
				'menfu'=>$_POST['menfu'],
				'kezhong'=>$_POST['kezhong'],
			);
		}


		// dump($madan_arr);

		// //如果码单信息存在，则保存
		// if(count($madan_arr)>0){
		// 	$madan = & FLEA::getSingleton('Model_Cangku_Chengpin_Madan');
		// 	$madan->saveRowset($madan_arr);
		// }
		// //处理需要清空的数据
		// $strSonId=join(',',$madan_clear);
		// if($strSonId!=''){
		// 	$sql="delete from cangku_madan where id in ({$strSonId})";
		// 	$this->_subModel->execute($sql);
		// }

		//
		echo json_encode(array(
			'success'=>true,
			'msg'=>'操作完成',
			'madan'=>$madan_arr,
		));exit;

	}
	/**
	 * 删除码单信息
	 * Time：2014/06/25 17:15:18
	 * @author li
	 */
	function actionRemoveMadanByAjax(){
		$madan = &FLEA::getSingleton('Model_Cangku_Chengpin_Madan');
		if($madan->removeByPkv($_POST['id'])){
			echo json_encode(array(
					'state'=>1
			));
		}else{
			echo json_encode(array(
					'state'=>0
			));
		}
	}

    function actionRkMadan(){
    	//dump($_GET);die;
    	//$this->authCheck();
		$_GET['ruku2proId']=(int)$_GET['ruku2proId'];
		//dump($_GET['ruku2proId']);die;
		//查找所有已设置的码单信息
		$madan = & FLEA::getSingleton('Model_Cangku_Chengpin_Madan');
		$madan->clearLinks();
		//查找所有码单
		$madanArr = $madan->findAll(array('ruku2proId'=>$_GET['ruku2proId']));
		//dump($madanArr);die;
		$temp=array();
		foreach ($madanArr as $key => & $v) {
			//标示已出库的
			if($v['chuku2proId']>0)$v['readonly']=true;
			$temp[$v['number']-1]=$v;
		}
		$madanArr=$temp;
		//dump($madanArr);exit;
        // dump(json_encode($madanArr));exit;
		$smarty = & $this->_getView();
		$smarty->assign('title', "设置码单");
		$smarty->assign('ruku2proId', $_GET['ruku2proId']);
		$smarty->assign('madanRows', json_encode($madanArr));
		$smarty->assign('arr_field_value', $madanArr[0]);
		$smarty->display("Cangku/Chengpin/RkDajuanEdit.tpl");
    }

    function actionSave(){
//         dump($_POST);die;
		//根据headSon,动态组成明细表数据集
		$cangku_common_ruku2product = array();
		foreach ($_POST['productId'] as $key => $v) {
			// 当没有记录 或者 没有输入数量时 跳出本次循环 防止多保存
			if (empty($_POST['productId'][$key]) || empty($_POST['cntOrg'][$key])) continue;
			if($_POST['productId'][$key]!=''){
				if($_POST['jiagonghuId'][$key]==''){
					js_alert('请选择整理厂!','window.history.go(-1)');
               		exit();
				}
			}
			$temp = array();
			foreach($this->headSon as $k=>&$vv) {
				$temp[$k] = $_POST[$k][$key];
					}
				$cangku_common_ruku2product[] = $temp;
			}
			//dump($cangku_common_ruku2product);die;
		//如果没有选择物料，返回
		if(count($cangku_common_ruku2product)==0) {
			js_alert('请选择有效物料并输入有效数量!','window.history.go(-1)');
			exit;
		}
		// cangku_common_ruku 表 的数组
		foreach($this->fldMain as $k=>&$vv) {
			$name = $vv['name']?$vv['name']:$k;
			$cangku_common_ruku[$k] = $_POST[$name];
		}
		// 表之间的关联
		$cangku_common_ruku['Products'] = $cangku_common_ruku2product;
		//dump($cangku_common_ruku2product);exit;
		//dump($cangku_common_ruku);exit;
        $cangku_common_ruku['creater'] = $_SESSION['REALNAME'];
		// 保存 并返回cangku_common_ruku表的主键
		$itemId = $this->_modelExample->save($this->notNull($cangku_common_ruku));
		if (!$itemId) {
			echo "保存失败";
			exit;
		}
		js_alert(null, 'window.parent.showMsg("保存成功!")', url('Cangku_Chengpin_Ruku','right'));

    }

     function actionEdit() {
         	//dump($_GET['id']);die;
    	$arr = $this->_modelMain->find(array('id' => $_GET['id'])); //dump($arr);exit;
		//设置主表id的值
    	 //dump($arr);exit;
		foreach ($this->fldMain as $k => &$v) {
			$v['value'] = $arr[$k]?$arr[$k]:$v['value'];
		}
		//dump($this->fldMain);//exit;
		//处理客户信息
		//$this->areaMain['']
		// 入库明细处理
		foreach($arr['Products'] as &$v) {
			$sql = "select * from jichu_product where id='{$v['productId']}'";
			$_temp = $this->_modelDefault->findBySql($sql); //dump($_temp);exit;
			$v['proCode'] = $_temp[0]['proCode'];
			$v['proName'] = $_temp[0]['proName'];
			$v['guige'] = $_temp[0]['guige'];
			$v['color'] = $_temp[0]['color'];
			$v['menfu'] = $_temp[0]['menfu'];
			$v['kezhong'] = $_temp[0]['kezhong'];
		}
		foreach($arr['Products'] as &$v) {
			$temp = array();
			foreach($this->headSon as $kk => &$vv) {
				$temp[$kk] = array('value' => $v[$kk]);
			}
			$temp['productId']['text'] = $v['proCode'];
			$rowsSon[] = $temp;
		}
		//补齐5行
		$cnt = count($rowsSon);
		for($i=5;$i>$cnt;$i--) {
			$rowsSon[] = array();
		}
        // dump($rowsSon);exit;
		$smarty = &$this->_getView();
		$smarty->assign('areaMain',array('title' => $this->_kind.'基本信息', 'fld' => $this->fldMain));
		$smarty->assign('headSon', $this->headSon);
		$smarty->assign('rowsSon', $rowsSon);
		$smarty->assign('rules', $this->rules);
		$smarty->assign('sonTpl', 'Cangku/Yuanliao/jsLlck.tpl');
		$this->_beforeDisplayEdit($smarty);
		$smarty->display('Main2Son/T1.tpl');
    }
}
?>