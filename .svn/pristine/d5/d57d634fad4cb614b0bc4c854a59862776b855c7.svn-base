<?php
FLEA::loadClass('Controller_Cangku_Chuku');
class Controller_Cangku_Chengpin_CkWithMadan extends Controller_Cangku_Chuku {
	var $fldMain;
	var $headSon;
	var $rules; //表单元素的验证规则
	// /构造函数
	function __construct() {
		parent::__construct();
		unset($this->fldMain);
		unset($this->headSon);
		unset($this->rules);
		$this->_state = '成品';
		$this->_head = 'XSCKA';
		$this->_kind='生产入库';

		$this->_modelDefault = &FLEA::getSingleton('Model_Cangku_Chengpin_Chuku');
		$this->_modelExample = &FLEA::getSingleton('Model_Cangku_Chengpin_Chuku'); 
		$this->_modelMain = &FLEA::getSingleton('Model_Cangku_Chengpin_Chuku');
		$this->_modelSon = &FLEA::getSingleton('Model_Cangku_Chengpin_Chuku2Product');
		//得到库位信息
		$sql = "select * from jichu_kuwei where 1";
		$rowset = $this->_modelMain->findBySql($sql);
		foreach($rowset as &$v) {
			// *根据要求：options为数组,必须有text和value属性
			$rowsKuwei[] = array('text' => $v['kuweiName'], 'value' => $v['kuweiName']);
		} 
		//出库主信息
		$this->fldMain = array(
			'chukuCode' => array('title' => '出库单号', 'type' => 'text', 'readonly' => true, 'value' => $this->_getNewCode($this->_head, $this->_modelMain->qtableName, 'chukuCode')),
			'chukuDate' => array('title' => '出库日期', 'type' => 'calendar', 'value' => date('Y-m-d')),
			'kind' => array('type' => 'text', 'value' => '销售出库', 'readonly' => true,'title'=>'出库类型'),
			'orderCode' =>array('title' => '相关订单', 'type' =>'text', 'readonly' => true),
			'clientName'=>array('title' => '客户选择', 'type' =>'text', 'readonly' => true),
			'kuwei' => array('title' => '库位选择', 'type' => 'select', 'value' => '','options'=>$rowsKuwei),
			// 'departmentId'=> array('title' => '领用部门','type' => 'select', 'value' => '','model'=>'Model_jichu_department'),
			// 'peolingliao' => array('title' => '领用人', 'type' => 'text', 'value' => ''),
			// 定义了name以后，就不会以memo作为input的id了
			'memo'=>array('title'=>'备注说明','type'=>'textarea','disabled'=>true,'name'=>'memo'),
			// 下面为隐藏字段
			'id' => array('type' => 'hidden', 'value' => '','name'=>'chukuId'),
			'clientId' => array('type' => 'hidden', 'value' => ''),
			'orderId' => array('type' => 'hidden', 'value' => ''),
			'type' => array('type' => 'hidden', 'value' => $this->_type),
			'creater' => array('type' => 'hidden', 'value' => $_SESSION['REALNAME']),
		);

		// /从表表头信息
		// /type为控件类型,在自定义模板控件
		// /title为表头
		// /name为控件名
		// /bt开头的type都在webcontrolsExt中写的,如果有新的控件，也需要增加
		$this->headSon = array(
			'_edit' => array('type' => 'btBtnRemove', "title" => '+5行', 'name' => '_edit[]'),
			// 'productId' => array('type' => 'btchanpinpopup', "title" => '产品选择', 'name' => 'productId[]'),
			'proCode' => array('type' => 'bttext', "title" => '产品编码','name' =>'proCode[]','readonly'=>true),
			'proName'=>array('type'=>'bttext',"title"=>'品种','name'=>'proName[]','readonly'=>true),
			'guige'=>array('type'=>'bttext',"title"=>'规格','name'=>'guige[]','readonly'=>true),
			'color'=>array('type'=>'bttext',"title"=>'颜色','name'=>'color[]','readonly'=>true),
			//'pihao'=>array('type'=>'bttext',"title"=>'批号','name'=>'pihao[]'),
			'dengji' => array('type' => 'btSelect', "title" => '等级', 'name' => 'dengji[]','options'=>array(
				array("text"=>'一等品',"value"=>'一等品',),
				array("text"=>'二等品',"value"=>'二等品',),
				array("text"=>'等外品',"value"=>'等外品',),				
			)), 
			'cntJian' => array('type' => 'bttext', "title" => '件数', 'name' => 'cntJian[]','readonly'=>true), 
			'cntOrg' => array('type' => 'bttext', "title" => '数量', 'name' => 'cntOrg2[]'),
			'unit'=>array('type'=>'btSelect','title'=>'单位','name'=>'unit2[]','options'=>array(
					array('text'=>'公斤','value'=>'公斤'),
					array('text'=>'米','value'=>'米'),
					array('text'=>'码','value'=>'码'),
					array('text'=>'磅','value'=>'磅'),
					array('text'=>'条','value'=>'条'),
			)),
			'cnt' => array('type' => 'bttext', "title" => '公斤数', 'name' => 'cnt[]','readonly'=>true), 
			'cntM' => array('type' => 'bttext', "title" => '米数', 'name' => 'cntM[]','readonly'=>true), 
			'Madan' => array('type' => 'btBtnMadan', "title" => '码单', 'name' => 'Madan[]'),
			'memo' => array('type' => 'bttext', "title" => '备注', 'name' => 'memo[]'),
			// ***************如何处理hidden?
			'id' => array('type' => 'bthidden', 'name' => 'id[]'),
			'danjia' => array('type' => 'bthidden', 'name' => 'danjia[]'),
			'money' => array('type' => 'bthidden', 'name' => 'money[]'),
			'ord2proId' => array('type' => 'bthidden', 'name' => 'ord2proId[]'),
			'ruku2proId' => array('type' => 'bthidden', 'name' => 'ruku2proId[]'),
			'productId' => array('type' => 'bthidden', 'name' => 'productId[]'),
		);

		// 表单元素的验证规则定义
		$this->rules = array(
			'orderId' => 'required',
			'clientId' => 'required',
			'kuweiId' => 'required'
		);

	}

	/**
	 * 按码单出库跳转
	*/
	function actionCkMadan(){
		//dump($_GET);exit;
		$mm = &FLEA::getSingleton('Model_Cangku_Ruku');
		$rk_arr = $mm->find(array('id' => $_GET['rukuId']));
		//查找订单信息
		$sql="select x.orderCode,x.clientId,y.compName from trade_order x
		left join jichu_client y on y.id=x.clientId
		where x.id='{$rk_arr['orderId']}'";
		$tt=$this->_modelExample->findBySql($sql);

		//创建数组，组织需要显示的信息
		$arr=array(
			'orderId'=>$rk_arr['orderId'],
			'orderCode'=>$tt[0]['orderCode'],
			'clientId'=>$tt[0]['clientId'],
			'clientName'=>$tt[0]['compName'],
			'kuwei'=>$rk_arr['kuwei'],
			'memo'=>$rk_arr['memo'],
		);
		// dump($arr);exit;
		foreach ($this->fldMain as $k => &$v) {
			$v['value'] = $arr[$k]?$arr[$k]:$v['value'];
		}
		// dump($this->fldMain);exit;
		// //加载库位信息的值
		$areaMain = array('title' => '码单出库基本信息', 'fld' => $this->fldMain);

		// 入库明细处理
		$product=array();
		foreach($rk_arr['Products'] as &$v) {
			$temp=array();
			/**
			* 查找是否存在码单，不存在码单的不显示
			*/
			$sql="select count(id) as id from cangku_madan where ruku2proId='{$v['id']}' and chuku2proId=0";
			$_temp = $this->_modelExample->findBySql($sql);
			if(!$_temp[0]['id']>0){
				unset($v);continue;
			}
			
			/**
			* 产品明细信息
			*/
			$sql = "select * from jichu_product where id='{$v['productId']}'";
			$_temp = $this->_modelExample->findBySql($sql); //dump($_temp);exit;
			$temp['proCode'] = $_temp[0]['proCode'];
			// $temp['zhonglei'] = $_temp[0]['zhonglei'];
			$temp['proName'] = $_temp[0]['proName'];
			// $temp['proName'] = $_temp[0]['proName'];
			$temp['guige'] = $_temp[0]['guige'];
			$temp['color'] = $_temp[0]['color'];

			$temp['pihao'] = $v['pihao'];
			$temp['dengji'] = $v['dengji'];
			$temp['productId'] = $v['productId'];
			$temp['ord2proId'] = $v['ord2proId'];
			$temp['ruku2proId'] = $v['id'];

			//查找订单相关信息
			$sql="select * from trade_order2product  where id='{$v['ord2proId']}'";
			$_temp = $this->_modelExample->findBySql($sql); 
			$temp['danjia']=$_temp[0]['danjia'];
			
			$product[]=$temp;
		}

		$rowsSon = array();
		foreach($product as &$v) {
			$temp = array();
			foreach($this->headSon as $kk => &$vv) {
				$temp[$kk] = array('value' => $v[$kk]);
			}
			$rowsSon[] = $temp;
		}
		// dump($rowsSon);exit;
		

		$smarty = &$this->_getView();
		$smarty->assign('areaMain', $areaMain);
		$smarty->assign('headSon', $this->headSon);
		$smarty->assign('rowsSon', $rowsSon);
		$smarty->assign('fromAction', $_GET['fromAction']);
		$smarty->assign('sonTpl', 'Cangku/Chengpin/MadanCKJs.tpl');
		$smarty->assign('rules', $this->rules);
		// $this->_beforeDisplayEdit($smarty);
		$smarty->display('Main2Son/T1.tpl');
	}

	//修改时要显示订单号
	function _beforeDisplayEdit(& $smarty) {
		$sonTpl=& $smarty->_tpl_vars['sonTpl'];
		$sonTpl="Cangku/Chengpin/MadanCKJs.tpl";
		$rowsSon = & $smarty->_tpl_vars['rowsSon'];
		$areaMain = & $smarty->_tpl_vars['areaMain'];
		// dump($smarty->_tpl_vars);dump($areaMain);exit;
		$orderId= $areaMain['fld']['orderId']['value'];
		$sql = "select orderCode,clientId from trade_order where id='{$orderId}'";
		// dump($sql);
		$_rows = $this->_modelExample->findBySql($sql);

		$areaMain['fld']['orderCode']['value'] = $_rows[0]['orderCode'];

		//查找客户信息
		$sql="select compName from jichu_client where id='{$_rows[0]['clientId']}'";
		$_rows = $this->_modelExample->findBySql($sql);
		$areaMain['fld']['clientName']['value'] = $_rows[0]['compName'];

		//查找码单对应的ruku2proId信息
		foreach($rowsSon as & $v){
			if(empty($v['id']['value']))continue;
			$sql="select ruku2proId,number,id from cangku_madan where chuku2proId='{$v['id']['value']}'";
			$temp=$this->_modelExample->findBySql($sql);
			// dump($temp);exit;
			$v['ruku2proId']['value']=$temp[0]['ruku2proId'];
			//处理已选择的码单信息
			$v['Madan']['value']=join(',',array_col_values($temp,'id')).';'.join(',',array_col_values($temp,'number'));
		}

		// dump($rowsSon);exit;
	}

	/**
	 * 按照码单出库
	*/
	function actionListMadanCk(){
		//dump(123);exit;
		FLEA::loadClass('TMIS_Pager'); 
		$arr = TMIS_Pager::getParamArray(array(
			'kuweiId'=>'',
			'clientId'=>'',
			'orderCode'=>'',
			'key'=>'',
			// 'guige'=>'',
			// 'color'=>'',
		));
		//查找码单没有出完的入库id
		$str="select distinct ruku2proId from cangku_madan where chuku2proId=0";
		$temp=$this->_modelExample->findBySql($str);
		$ruku2proId = join(',',array_col_values($temp,'ruku2proId'));
		$ruku2proId=='' && $ruku2proId="null";
		//查找数据
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
			where y.kind='{$this->_kind}'
			";
		
		$sql.=" and x.id in({$ruku2proId})";
		$arr['kuweiId']!='' && $sql.=" and y.kuweiId='{$arr['kuweiId']}'";
		//$arr['clientId']!='' && $sql.=" and a.clientId='{$arr['clientId']}'";
		//$arr['orderCode']!='' && $sql.=" and x.orderCode like '%{$arr['orderCode']}%'";
		if ($arr['key'] != '') $sql .= " and (y.rukuCode like '%{$arr['key']}%'
			or b.proName like '%{$arr['key']}%'
			or b.proCode like '%{$arr['key']}%'
			or b.guige like '%{$arr['key']}%')";
		//排序
		$sql.=" order by y.rukuCode desc,x.id desc";
		//dump($sql);exit;
		$pager = &new TMIS_Pager($sql);
		$rowset = $pager->findAll();

		foreach ($rowset as $key => & $v) {
			$v['_edit']="<a href='".url('Cangku_Chengpin_CkWithMadan','CkMadan',array(
				'ruku2proId'=>$v['id'],
				'rukuId'=>$v['rukuId'],
				'fromAction'=>$_GET['action']
				))."'>整单出库</a>";
			$v['cnt']=round($v['cnt'],1);
			//$value['cntOrg']=round($value['cntOrg'],2);
		}
		$smarty = &$this->_getView(); 
		// 左侧信息
		$arrFieldInfo = array(
			'_edit'=>'操作',
			"rukuDate" => "入库日期",
			'rukuCode' => array("text"=>'入库单号','width'=>150),
			"kind" => "类别",
			'kuwei' => '库位',
			// 'state' => '状态',
			// 'proKind' => '种类',
			'proCode' => '产品编码',
			'proName' => '品名',
			'guige' => '规格',
			'color' => '颜色',
			//'pihao'=>'批号',
			// 'color' => '颜色',
			'cnt' => '数量', 
			//'danjia' => '单价', 
			//'money' => '金额', 
			//"compName" => "供应商",
			//'songhuoCode' => '送货单号',
			
			'memo' => '备注'
		);
		$smarty->assign('title', '计划查询');
		$smarty->assign('arr_field_info', $arrFieldInfo);
		$smarty->assign('add_display', 'none');
		$smarty->assign('arr_condition', $arr);
		$smarty->assign('arr_field_value', $rowset);
		$smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action']), $arr));
		$smarty->display('TblList.tpl');
	}

	/**
	 * 显示码单界面，操作需要出库的码单信息
	 * Time：2014/06/27 13:37:30
	 * @author li
	*/
	function actionViewMadan(){
		// dump($_GET);exit;
		$this->authCheck();
		$_GET['ruku2proId']=(int)$_GET['ruku2proId'];
		//查找所有已设置的码单信息
		$madan = & FLEA::getSingleton('Model_Cangku_Chengpin_Madan');
		$madan->clearLinks();
		//查找所有码单
		$madanArr = $madan->findAll(array('ruku2proId'=>$_GET['ruku2proId']));
		$temp=array();
		foreach ($madanArr as $key => & $v) {
			//如果以有出库信息的，默认选中，出库id不同的，表示不能操作了
			$v['isChecked']=$v['chuku2proId']>0?true:false;
			if(!empty($v['chuku2proId']) && $v['chuku2proId']!=$_GET['chuku2proId']){
				$v['disabled']=true;
			}else $v['disabled']=false;

			$temp[$v['number']-1]=$v;
		}
		$madanArr=$temp;
		//组织数组信息
		$row = array('Madan'=>$madanArr);
		// dump($row);exit;
		$smarty = & $this->_getView();
		$smarty->assign('title', "设置码单");
		// $smarty->assign('fromAction', $_GET['fromAction']);
		$smarty->assign('madanRows', json_encode($madanArr));
		$smarty->display("Cangku/Chengpin/CkDajuanEdit.tpl");
	}

	/**
	 * 保存重写，因为这里需要处理码单信息
	*/
	function actionSave(){
		//dump($_POST);exit;
		//开始保存
		$_POST['cntOrg']=$_POST['cntOrg2'];
		$_POST['unit']=$_POST['unit2'];
		$pros = array();
		$madan_clear = array();
		foreach($_POST['productId'] as $key=>&$v) {
			if($v=='' || empty($_POST['cnt'][$key])) continue;
			$temp = array();
			foreach($this->headSon as $k=>&$vv) {
				//查找码单是否设置
				if($k=='Madan'){
					$_madan=explode(';', $_POST[$k][$key]);
					$_madanIdStr=$_madan[0];
					$_madan=explode(',', $_madanIdStr);
					//如果已出库的信息，则需要删除取消掉的码单信息
					if($_POST['id'][$key]>0 && count($_madan)>0){
						$sql="select id from cangku_madan where chuku2proId='{$_POST['id'][$key]}' and id not in({$_madanIdStr})";
						// dump($sql);exit;
						$res=$this->_modelExample->findBySql($sql);
						$madan_clear+=$res;
						// dump($res);exit;
					}
					// dump($vv);exit;
					foreach($_madan as & $cc){
						$temp[$k][]=array('id'=>$cc);
					}
					// dump($temp);exit;
				}else $temp[$k] = $_POST[$k][$key];
			}
			$pros[]=$temp;
		}
		if(count($pros)==0) {
			js_alert('请选择有效的产品明细信息!','window.history.go(-1)');
			exit;
		}
		$row = array();
		foreach($this->fldMain as $k=>&$vv) {
			$name = $vv['name']?$vv['name']:$k;
			$row[$k] = $_POST[$name];
		}

		$row['Products'] = $pros;
        $row['creater'] = $_SESSION['REALNAME'];
		//dump($row);exit;
		if(!$this->_modelExample->save($row)) {
			js_alert('保存失败','window.history.go(-1)');
			exit;
		}

		//该出库单取消出库的码单信息，码单的出库Id 更新为0
		$strSonId=join(',',array_col_values($madan_clear,'id'));
		if($strSonId!=''){
			$sql="update cangku_madan set chuku2proId='0' where id in ({$strSonId})";
			mysql_query($sql);
		}

		//跳转
		js_alert(null,'window.parent.showMsg("保存成功!")',url($_POST['fromController'],$_POST['fromAction'],array()));
		exit;
	}

	// function actionRight(){
	// 	redirect(url("Shengchan_CangkuBu_Cpck",'right'));	exit;
	// }
}
?>