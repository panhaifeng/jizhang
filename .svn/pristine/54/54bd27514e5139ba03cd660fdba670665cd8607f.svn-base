<?php
FLEA::loadClass('TMIS_Controller');
FLEA::loadClass('TMIS_Common');
class Controller_Cangku_Yl_Ruku extends Tmis_Controller {
	var $title = "原料仓库-入库单";
	function Controller_Cangku_Yl_Ruku() {
		$this->_modelExample = & FLEA::getSingleton('Model_Cangku_Yl_Ruku');
		$this->_modelRuku = & FLEA::getSingleton('Model_Cangku_Yl_Ruku');
		$this->_modelRuku2yl = & FLEA::getSingleton('Model_Cangku_Yl_Ruku2Yl');
		$this->_modelPro = & FLEA::getSingleton('Model_Jichu_Yl');
		$this->_modelExpense = & FLEA::getSingleton('Model_Caiwu_Expense');
	}
	function actionRight(){
		$this->authCheck('2-1');
		$arrFieldInfo = array(
			"rukuDate" =>"入库日期",
			"rukuNum" =>"入库单号",
			"songhuCode" =>"送货单号",
			"compName" =>'供应商',
			//'ylCode'=>'编码',
			'ylName'=>'原料名称',
			'guige'=>'规格',
			'kuweiName'=>'库位',
			'len'=>'长度',
			'zhishu'=>'数量',
			"cnt" =>'重量',
			'danjia' => '单价',
			'money'=>'金额',
			'memo' => '入库备注',
			'ylMemo' => '原料备注',
			'_edit'=>'操作'
		);
		$this->_showMingxi($arrFieldInfo);
	}
	#采购中查看入库明细
	function actionMingxi(){
		$arrFieldInfo = array(
			"rukuNum" =>"入库单号",
			"rukuDate" =>"日期",
			"compName" =>'供应商',
			'ylCode'=>'原料编码',
			'ylName'=>'原料名称',
			'guige'=>'规格',
			//'color'=>'颜色',
			//"cnt" =>'数量',
			'zhishu'=>'数量',
			'danjia' => '单价',
			'money'=>'金额',
			'memo'=>'备注',
			'ylMemo'=>'原料备注'
		);
		$this->_showMingxi($arrFieldInfo);
	}

	function _showMingxi($arrFieldInfo){
		FLEA::loadClass('TMIS_Pager');
		$modelName = "Model_Jichu_Supplier";
		$str = "select
				a.id,
				a.rukuId,
				x.rukuNum,
				x.rukuDate,
				x.supplierId,
				x.songhuCode,
				x.memo,
				a.cnt,
				a.danjia,
				a.money,
				a.memo as ylMemo,
				a.guozhangId,
				a.len,
				a.zhishu,
				a.danwei,
				y.compName,
				z.ylName,
				z.ylCode,
				z.guige,
				k.kuweiName,
				z.unit from cangku_yl_ruku x
				left join cangku_yl_ruku2yl a on x.id=a.rukuId
				left join jichu_supplier y on x.supplierId=y.id
				left join jichu_yl z on a.ylId=z.id 
				left join jichu_kuwei k on k.id=a.kuweiId where x.kind=0";


		$arrGet = TMIS_Pager::getParamArray(array(
			'dateFrom' => date('Y-m-01'),
			'dateTo' => date("Y-m-d"),
			'supplierId'=>'',
			//'orderType'=>0,
			'ylName'=>''
		));

		$str .= " and rukuDate >= '$arrGet[dateFrom]' and rukuDate<='$arrGet[dateTo]'";
		if ($arrGet['supplierId'] != '')  $str .=" and x.supplierId='$arrGet[supplierId]'";

		if ($arrGet['orderType'] != 0) $str .=" and orderType = $arrGet[orderType]";

		/*if ($arrGet['key'] != '')  $str .= " and (x.rukuNum like '%$arrGet[key]%'
											or z.ylCode like '%$arrGet[key]%'
											or z.ylName like '%$arrGet[key]%'
											or z.guige like '%$arrGet[key]%')";*/
		if ($arrGet['ylName'] != '')  $str .=" and z.ylName like '%{$arrGet['ylName']}%'";

		$str .= " order by rukuDate desc, rukuNum desc";

		$rowset = $this->_modelRuku->findBySql($str);
		if (count($rowset)>0) foreach($rowset as & $value) {
			if($value['guozhangId']>0) $value['_bgColor']='lightgreen';
			if($value['danwei']==0){
				$value['money']=round($value['danjia']*$value['cnt'],3);
				//	dump($value['cnt']);
			}else{
				$value['money']=round($value['danjia']*$value['zhishu'],3);
			}
			$value['_edit'] = "<a href='".$this->_url('View200',array(id=>$value[rukuId]))."' target='_blank' title='$title'>打印</a>";
			$value['_edit'] .= " | <a href='".$this->_url('Edit',array(id=>$value[rukuId],'guozhangId'=>$value['guozhangId']))."'>修改</a> | <a href='".$this->_url('Remove',array(id=>$value[rukuId],'guozhangId'=>$value['guozhangId']))."' onclick=\"return confirm('确认删除吗?')\">删除</a>";
			$tcnt += $value['cnt'];
			if ($value['money'] == 0) $value['money'] = $value['cnt'] * $value['danjia'];
			$tmoney += $value['money'];
		}
		$heji=$this->getHeji($rowset,array('len','cnt','zhishu','money'),'rukuDate');
		$rowset[]=$heji;
		//dump($rowset);
		$note='<font color=red>绿色表示该入库记录已过账</font>';
		//dump($rowset);
		//模板变量设置
		$smarty = & $this->_getView();


		$smarty->assign('title','原料入库查询');
		$smarty->assign('pk', $this->_modelRuku->primaryKey);
		$smarty->assign('arr_field_info',$arrFieldInfo);
		//$smarty->assign('add_display', 'none');
		$smarty->assign('arr_condition',$arrGet);
		$smarty->assign('page_info',$note);
		$smarty->assign('arr_field_value',$rowset);
		$smarty->display('TableList.tpl');
	}

	function actionSearchThickbox(){
		FLEA::loadClass('TMIS_Pager');
		$modelName = "Model_Jichu_Supplier";
		$viewCangkuRuku = "select
							y.rukuNum,
							y.rukuDate,
							y.supplierId,
							y.memo AS orderMemo,
							x.id,
							x.rukuId AS rukuId,
							x.productId,
							x.danjia,
							x.cnt,
							x.money,
							x.money2,
							x.memo from (cangku_ruku2product x
							left join cangku_ruku y on(x.rukuId = y.id))";
		$str = "select
				y.id,
				x.rukuId,
				x.rukuNum,
				x.rukuDate,
				x.productId,
				x.supplierId,
				x.cnt,
				x.danjia,
				x.money,
				x.money2,
				x.orderMemo,
				x.memo,
				y.compName,
				z.proName,
				z.pinpai,
				z.guige,
				z.unit from (".$viewCangkuRuku.") x
				left join jichu_supplier y on x.supplierId=y.id
				left join jichu_product z on x.productId=z.id where 1";

		$arr = array();
		$arr['productId'] = $_GET['productId'];
		$arrGet = TMIS_Pager::getParamArray($arr);

		$str .= " and productId = '$arrGet[productId]'";
		$str .= " order by id desc";

		$pager =& new TMIS_Pager($str,'','',15);
		$rowset =$pager->findAllBySql($str);

		if (count($rowset)>0) foreach($rowset as & $value) {
			$tcnt += $value[cnt];
			if ($value[money] == 0) $value[money] = $value[cnt] * $value[danjia];
			$tmoney += $value[money];
			$tmoney2 += $value[money2];
		}

		//dump($rowset);
		//模板变量设置
		$smarty = & $this->_getView();
		$arrFieldInfo = array(
			"rukuNum" =>"单号",
			"rukuDate" =>"日期",
			"compName" =>'供应商',
			'proName'=>'产品名称',
			'pinpai'=>'品牌',
			'guige'=>'规格',
			"cnt" =>'数量',
			//'danjia' => '单价'
			//'money'=>'金额',
			//'money2'=>'其它金额',
			//'_edit'=>'操作'
		);

		$smarty->assign('title','仓库入库 - 查询');
		$smarty->assign('pk', $this->_modelRuku->primaryKey);
		$smarty->assign('arr_field_info',$arrFieldInfo);
		$smarty->assign('nav_display', 'none');
		//$smarty->assign('add_display', 'none');
		//$smarty->assign('arr_condition',$arrGet);
		$smarty->assign('arr_field_value',$rowset);
		$smarty->assign('page_info',$pager->getNavBar($this->_url('SearchThickbox',$arrGet)));
		$smarty->display('TableList.tpl');
	}

	#查看详细
	function actionView() {
		$pk=$this->_modelRuku->primaryKey;
		$rowset=$this->_modelRuku->find($_GET[$pk]);
	    //dump($rowset); exit;
		//$mPro = & FLEA::getSingleton('Model_Jichu_Product');
		if (count($rowset)>0) if (count($rowset['Yl'])>0) {
			foreach($rowset['Yl'] as & $value) {
				$row = $this->_modelPro->findByField('id', $value['ylId']);
				$value['ylCode'] = $row['ylCode'];
				$value['ylName'] = $row['ylName'];
				$value['guige'] = $row['guige'];
				//$value[color] = $row[color];
				$value['unit'] = $row['unit'];
				//if ($value['money'] == 0)	$value['money'] = $value['cnt'] * $value['danjia'];
				$value['money']=round($value['danjia']*$value['cnt'],2);
				$totalCnt += $value['cnt'];
				$totalMoney += $value['money'];
				$taihao .= $value['memo']." / ";
			}
		}
		$smarty = & $this->_getView();
		$smarty->assign("arr_field_value",$rowset);
		$smarty->assign("total_cnt", $totalCnt);
		$smarty->assign("total_money", $totalMoney);
		$smarty->assign("user_name", $_SESSION['REALNAME']);
		$smarty->display('Cangku/Yl/RukuView.tpl');
	}

	#查看详细(退货)
	function actionView200() {
		$pk=$this->_modelRuku->primaryKey;
		$rowset=$this->_modelRuku->find($_GET[$pk]);
		//dump($rowset); exit;
		//$mPro = & FLEA::getSingleton('Model_Jichu_Product');
		if (count($rowset)>0) if (count($rowset['Yl'])>0) {
			foreach($rowset['Yl'] as & $value) {
				$row = $this->_modelPro->findByField('id', $value['ylId']);
				$value['ylCode'] = $row['ylCode'];
				$value['ylName'] = $row['ylName'];
				$value['guige'] = $row['guige'];
				//$value[color] = $row[color];
				$value['unit'] = $row['unit'];
				//if ($value['money'] == 0)	$value['money'] = $value['cnt'] * $value['danjia'];
				if($value['danwei']==0){
					$value['money']=round($value['danjia']*$value['cnt'],2);
				}else{
					$value['money']=round($value['danjia']*$value['zhishu'],2);
				}
				$totalCnt += $value['cnt'];
				$totalMoney += $value['money'];
				$taihao .= $value['memo']." / ";
			}
		}
		$rowset['Yl'][]=$this->getHeji($rowset['Yl'], array('cnt','money'), 'ylName');
		//dump($rowset);exit;
		$smarty = & $this->_getView();
		$smarty->assign("arr_field_value",$rowset);
		$smarty->assign("total_cnt", $totalCnt);
		$smarty->assign("total_money", $totalMoney);
		$smarty->assign("user_name", $_SESSION['REALNAME']);
		$smarty->display('Cangku/Yl/Ruku200View.tpl');
	}
	function actionAdd() {
		$this->authCheck('2-1');
		$this->_edit(array('rukuNum'=>$this->_modelRuku->getNewRukuNum()));
	}

	function actionAdd1() {
		$this->authCheck(128);
		$this->_edit1(array('rukuNum'=>$this->_modelRuku->getNewRukuNum()));
	}
	function actionAdd2(){
		$this->_edit2(array('rukuNum'=>$this->_modelRuku->getNewRukuNum()));
	}
	function actionEdit() {
		$pk=$this->_modelRuku->primaryKey;
		$arr=$this->_modelRuku->find($_GET[$pk]);
		//dump($arr);
		if (count($arr['Yl'])>0) foreach($arr['Yl'] as & $v){
			$mPro = & FLEA::getSingleton('Model_Jichu_Yl');
			$kw = & FLEA::getSingleton('Model_Jichu_kuwei');
			$_row=$kw->find(array('id'=>$v['kuweiId']));
			$v['kuweiName']=$_row['kuweiName'];
			$row = $mPro->find(array('id'=>$v['ylId']));
			if($v['danwei']==0){
				$v['money']=round($v['danjia']*$v['cnt'],3);
			}else{
				$v['money']=round($v['danjia']*$v['zhishu'],3);
			}
			//dump($row);exit;
			$v['Yl'] = $row;
		}
		//dump($arr);exit;
		$this->_edit($arr);
	}
	function actionSave() {
		//dump($_POST);exit;
		if($_POST['rukuId']!='')$rr=$this->_modelExample->find(array('id'=>$_POST['rukuId']));
		for ($i=0;$i<count($_POST['cnt']);$i++){
			if(empty($_POST['ylId'][$i])/* || empty($_POST['cnt'][$i])*/) continue;
			$cnt=$_POST['danwei'][$i]==0?$_POST['cnt'][$i]:$_POST['zhishu'][$i];
			$money=$cnt*$_POST['danjia'][$i];
			$arr[] = array(
				'id'		=> $_POST['id'][$i],
				'ylId'		=> $_POST['ylId'][$i],
				'kuweiId'   => $_POST['kuweiId'][$i],
				'len'		=> $_POST['len'][$i]+0,
				'zhishu'	=> $_POST['zhishu'][$i]+0,
				'danwei'	=> $_POST['danwei'][$i],
				'cnt'		=> $_POST['cnt'][$i]+0,
				'danjia'	=> $_POST['danjia'][$i],
				'money'	=> $money,
				'moneyTrue'	=> $_POST['moneyTrue'][$i]+0,
				'plan2ylId'=> $_POST['plan2ylId'][$i]+0,
				'memo'		=> $_POST['memo'][$i],
				'ifRemove'	=> $_POST['ifRemove'][$i]
			);
			$money+=$_POST['cnt'][$i]*$_POST['danjia'][$i];
		}

		$row=array(
				'id'			=>$_POST['rukuId'],
				'planId'=>$_POST['planId']+0,
				'rukuNum'		=>$_POST['rukuNum'],
				'songhuCode'		=>$_POST['songhuCode'],
				'rukuDate'		=>empty($_POST['rukuDate'])?date("Y-m-d"):$_POST['rukuDate'],
				'supplierId'			=>$_POST['supplierId']+0,
				'memo'			=>trim($_POST['rukuMemo']),
				'kind'=>$_POST['kind']+0,
				'Yl'		=>$arr
		);
		//dump($arr);exit;
		//dump($_POST);dump($row);exit;
		//dump($row);exit;
		$rukuId = $this->_modelRuku->save($row);
		#改变库存
		if(count($rr['Yl'])>0) foreach($rr['Yl'] as & $v) {
			$this->_modelExample->changeYlKucun($v['ylId']);
		}
		if($arr) foreach($arr as & $v) {
			$this->_modelExample->changeYlKucun($v['ylId']);
		}
		//采购入账
		if($_POST['isRuzhang']){
		    $date=date('Y-m-d');
		    $str="insert into caiwu_yf_guozhang(dateGuozhang,supplierId,money)
			 values('$date','$_POST[supplierId]','$money')";
		    mysql_query($str);
		    $id=mysql_insert_id();
		    $str="update cangku_yl_ruku2yl set guozhangId='$id' where rukuId='$rukuId'";
		    mysql_query($str);

		    //调整当前应收
		    if($_POST['supplierId']>0) $this->_modelExpense->changeYf($_POST['supplierId']);
		}

		if($_POST['Submit']=='保存') {
			if($_POST['kind']==2){
			    js_alert('保存成功！','',$this->_url('right2'));
			}else{
			    if(!$_POST['from']) js_alert('保存成功！','',$this->_url('right'));
			    else js_alert('保存成功！','',$this->_url($_POST['from']));
			}
		} else {
			if($_POST['kind']==2){
			    js_alert('保存成功！','',$this->_url('add2'));
			}else{
			    if($_POST['from']=='right1') js_alert('保存成功！','',$this->_url('add1'));
			    else js_alert('保存成功！','',$this->_url('add'));
			}
		}
		//else die('保存失败!');
	}

	#添加原料单价
	function actionAddPrice() {
		$this->authCheck(42);
		$viewCangkuRuku = "select
							y.rukuNum,
							y.rukuDate,
							y.supplierId,
							y.memo AS orderMemo,
							x.id,
							x.rukuId AS rukuId,
							x.ylId,
							x.danjia,
							x.cnt,
							x.money,
							x.memo from (cangku_yl_ruku2yl x
							left join cangku_yl_ruku y on(x.rukuId = y.id))";
		$str = "select
			x.id,
			x.rukuId,
			x.rukuNum,
			x.rukuDate,
			x.supplierId,
			x.cnt,
			x.danjia,
			x.money,
			x.memo,
			y.compName,
			z.ylName,
			z.guige,
			z.unit from (".$viewCangkuRuku.") x
			left join jichu_supplier y on x.supplierId=y.id
			left join jichu_yl z on x.ylId=z.id where 1";

		FLEA::loadClass('TMIS_Pager');
		$arrGet = TMIS_Pager::getParamArray(array(
				'dateFrom'		=>date('Y-m-d'),
				'dateTo'		=>date('Y-m-d'),
				'supplierId'	=>'',
				'key'			=>''
			)
		);

		$str .= " and rukuDate >= '$arrGet[dateFrom]' and rukuDate<='$arrGet[dateTo]'";
		if ($arrGet['supplierId'] != '')  $str .= " and x.supplierId='$arrGet[supplierId]'";
		if ($arrGet['key'] != '') {
			$str .= " and (x.memo like '%$arrGet[key]%'
						or z.proName like '%$arrGet[key]%'
						or z.guige like '%$arrGet[key]%')";
		}
		$str .= " order by rukuDate desc";

		$rowset = $this->_modelRuku2yl->findBySql($str);
		if (count($rowset)>0) foreach($rowset as & $value) {
			$tcnt += $value['cnt'];
			$this->makeEditable($value, 'danjia');
		}

		#合计行
		$i = count($rowset);
		$rowset[$i]['rukuNum'] = '合计';
		$rowset[$i]['cnt'] = $tcnt;


		//模板变量设置
		$smarty = & $this->_getView();
		$arrFieldInfo = array(
			"rukuNum"	=>"单号",
			"rukuDate"	=>"日期",
			"compName"	=>'供应商',
			'ylName'	=>'原料名称',
			'guige'		=>'规格',
			'unit'		=>'单位',
			"cnt"		=>'数量',
			'danjia'	=> '单价',
			'memo'		=>'备注'
		);

		$smarty->assign('title','添加原料单价');
		$smarty->assign('pk', $this->_modelRuku2yl->primaryKey);
		$smarty->assign('arr_field_info',$arrFieldInfo);
		$smarty->assign('add_display', 'none');
		$smarty->assign('arr_condition',$arrGet);
		$smarty->assign('arr_field_value',$rowset);
		$smarty->assign('arr_js_css',$this->makeArrayJsCss(array('grid','calendar')));
		$smarty->display('TableList.tpl');
	}


	#根据入库单号显示入库明细，再抵冲发票时用
	function actionGetWaresJson() {
		$ruku = $this->_modelRuku->find("ruKuNum='$_GET[rukuNum]'");
		$rukuId = $ruku[id];
		//echo $rukuId;exit;
		$modelRuku2Wares = FLEA::getSingleton('Model_Cangku_Ruku2Ware');
		$wares = $modelRuku2Wares->findAll("rukuId='$rukuId'");

		for ($i=0;$i<count($wares);$i++) {
			$wares[$i][rukuNum] = $_GET[rukuNum];
		}
		echo json_encode($wares);exit;
		//dump($wares);exit;
	}

	//打印对账单
	function actionPrintCheckForm() {
		//dump($_GET); EXIT;
		$supplierId=$_GET['supplierId'];
		$dateFrom = $_GET['dateFrom'];
		$dateTo = $_GET['dateTo'];

		$viewCangkuRuku = "select
							y.rukuNum,
							y.rukuDate,
							y.supplierId,
							y.memo AS orderMemo,
							x.id,
							x.rukuId AS rukuId,
							x.ylId,
							x.danjia,
							x.cnt,
							x.money,
							x.memo from (cangku_yl_ruku2yl x
							left join cangku_yl_ruku y on(x.rukuId = y.id))";

		$str = "select x.*,y.compName,x.danjia*x.cnt as money
				from (".$viewCangkuRuku.") x
				inner join jichu_supplier y on x.supplierId=y.id where x.supplierId='$supplierId' and rukuDate>='$dateFrom' and rukuDate<='$dateTo'";

		$m = FLEA::getSingleton('Model_Jichu_Supplier');
		$arr = $m->find($supplierId);

		$modelYl = & FLEA::getSingleton('Model_Jichu_Yl');

		$rowset = $m->findBySql($str);

		$totalCnt = 0;
		$totalMoney = 0;

		if (count($rowset)>0) foreach ($rowset as & $value) {
			$rowYl = $modelYl->find($value['ylId']);
			if (count($rowYl)>0) {
				$value['ylName'] = $rowYl['ylName'];
				$value['guige'] = $rowYl['guige'];
				$value['unit'] = $rowYl['unit'];
			}

			$totalCnt += $value['cnt'];
			$totalMoney += $value['money'];
		}

		$totalMoneyCap = TMIS_Common::trans2rmb($totalMoney);

		$smarty = & $this->_getView();
		$smarty->assign('title', '应付款对帐单');
		$smarty->assign('compName',$arr[compName]);
		$smarty->assign('arr_field_value',$rowset);
		$smarty->assign('total_money_cap', $totalMoneyCap);
		$smarty->assign('total_money', $totalMoney);
		$smarty->display('Caiwu/Yf/YfCheckForm.tpl');
	}


	function _edit($Arr, $tag=0) {
		//dump($Arr); exit;
		$smarty = & $this->_getView();
		$smarty->assign('title', '原料入库登记');
		$smarty->assign('user_id', $_SESSION['USERID']);
		$smarty->assign('real_name', $_SESSION['REALNAME']);
		$smarty->assign("arr_field_value",$Arr);
		$smarty->assign('default_date',date("Y-m-d"));
		$pk = $this->_modelRuku->primaryKey;
		$primary_key = (isset($_GET[$pk])?$pk:"");
		$smarty->assign("pk",$primary_key);
		$smarty->display('Cangku/Yl/RukuEdit.tpl');
	}

	function actionRemove() {
		//改变库存
		$ruku = $this->_modelExample->find(array('id'=>$_GET['id']));
		//dump($ruku);exit;
		if ($this->_modelExample->removeByPkv($_GET[id])) {
			if($ruku['Yl']) foreach($ruku['Yl'] as & $v) {
				$this->_modelExample->changeYlKucun($v['ylId']);
			}
		}
		if($_GET['kind']==2) redirect($this->_url("right2"));
		if($_GET['from']=='') redirect($this->_url("right"));
		else redirect($this->_url($_GET['from']));
	}
}
?>