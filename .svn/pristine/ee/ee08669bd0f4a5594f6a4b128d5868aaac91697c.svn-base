<?php
FLEA::loadClass('TMIS_Controller');
class Controller_Cangku_Yl_Chuku extends Tmis_Controller {
	var $_modelChuku;
	var $displayTaihao;
	var $title = '原料出库';

	function Controller_Cangku_Yl_Chuku() {
		$this->_modelExample = & FLEA::getSingleton('Model_Cangku_Yl_Chuku');
		$this->_mYlChuku = & FLEA::getSingleton('Model_Cangku_Yl_Chuku');
		$this->_mYlChuku2Yl = & FLEA::getSingleton('Model_Cangku_Yl_Chuku2Yl');
		$this->_mYl = & FLEA::getSingleton('Model_Jichu_Yl');
	}

	#仓库出库-查询
	function actionRight(){
		$this->authCheck('2-4');
		FLEA::loadClass('TMIS_Pager');
		$modelName = "Model_Jichu_Chejian";
		$str = "select
			x.id,
			x.chukuId,
			y.chukuNum,
			y.chukuDate,
			x.cnt,
			x.danjia,
			x.len,
			x.zhishu,
			y.memo,
			a.depName,
			z.ylName,
			z.guige,
			z.ylCode,
			k.kuweiName,
			z.unit from cangku_yl_chuku y
			left join cangku_yl_chuku2yl x on y.id=x.chukuId
			left join jichu_yl z on x.ylId=z.id
			left join jichu_department a on y.depId=a.id
			left join jichu_kuwei k on k.id=x.kuweiId
			where y.kind=0";
		$arrGet = TMIS_Pager::getParamArray(array(
			'dateFrom'=>date('Y-m-01'),
			'dateTo'=>date('Y-m-d'),
			'depId'=>'',
			'key'=>''
		));

		$str .= " and y.chukuDate >= '$arrGet[dateFrom]' and y.chukuDate<='$arrGet[dateTo]'";
		if ($arrGet['depId'] != '')  $str .= " and y.depId='$arrGet[depId]'";
		if ($arrGet[key] != '') {
			$str .= " and (z.ylName like '%$arrGet[key]%' or z.ylCode like '%$arrGet[key]%'
						or z.guige like '%$arrGet[key]%')";
		}
		$str .= " order by y.chukuDate desc";
        $pager =& new TMIS_Pager($str);
		$rowset =$pager->findAll();
		$rowset2=$this->_mYlChuku->findBySql($str);
		if (count($rowset)>0) foreach($rowset as & $value) {
			$value['money'] = round($value['danjia'] * $value['cnt'],2);
			$value['operator'] = $value['Operator']['userName'];
			$value['_edit'] = "<a href='".$this->_url('View',array(id=>$value[chukuId]))."' target='_blank' title='$title'>打印</a> | ".$this->getEditHtml($value['chukuId'])." | ".$this->getRemoveHtml($value['chukuId'])."";
			$tcnt += $value[cnt];
			if ($value[money] == 0) $value[money] = $value[cnt] * $value[danjia];
			$tmoney += $value[money];
		
		}
		//$heji=$this->getHeji($rowset,array('len','cnt','zhishu'),'chukuDate');
		//$rowset[]=$heji;
//	     $sql="
//			select sum(len)  as len,
//				  sum(cnt) as cnt,
//				  sum(zhishu) as zhishu
//			from  cangku_yl_chuku2yl  where 1";
//		$zongji = mysql_fetch_assoc(mysql_query($sql));
        $zongji=$this->getHeji($rowset2,array('len','cnt','zhishu'));
		$zongji['chukuDate'] = '总计';
		$heji=$this->getHeji($rowset,array('len','cnt','zhishu'),'chukuDate');
		$rowset[]=$heji;
		$rowset[]=$zongji;
		//模板变量设置
		$smarty = & $this->_getView();
		$arrFieldInfo = array(
			"chukuDate" =>"出库日期",
			"chukuNum" =>"出库单号",
			
			"depName" =>'部门',
			'ylCode'=>'编码',
			'ylName'=>'产品名称',
			'guige'=>'规格',
			'kuweiName'=>'库位',
			'len'=>'长度',
			"cnt" =>'重量',
			'zhishu'=>'数量',
                        'memo'=>'备注',
			//'danjia' => '单价',
			//'money'=>'金额',
			//'memo'=>'备注',
			'_edit'=>'操作'
		);

		$smarty->assign('title','领料出库查询');
		$smarty->assign('pk', $this->_mYlChuku->primaryKey);
		$smarty->assign('arr_field_info',$arrFieldInfo);
		//$smarty->assign('add_display', 'none');
		$smarty->assign('arr_condition',$arrGet);
		$smarty->assign('arr_field_value',$rowset);
		$smarty->display('TableList.tpl');
	}

	#查看详细
	function actionView() {
		$pk=$this->_mYlChuku->primaryKey;
		$rowset=$this->_mYlChuku->find($_GET[$pk]);
		//dump($rowset);
		//$mPro = & FLEA::getSingleton('Model_Jichu_Product');
		$rowCount = 0;
		if (count($rowset)>0) if (count($rowset['Yl'])>0) {
			foreach($rowset['Yl'] as & $value) {
				$row = $this->_mYl->findByField('id', $value['ylId']);
				$value['ylCode'] = $row['ylCode'];
				$value['ylName'] = $row['ylName'];
				$value[color] = $row[color];
				$value[guige] = $row[guige];
				//$value[chexing] = $row[chexing];
				$value[unit] = $row[unit];

				if ($value[money] == 0)	$value[money] = $value[cnt] * $value[danjia];

				$totalCnt += $value[cnt];
				$totalMoney += $value[money];
				//$totalMoney2 += $value[money2];
				//$taihao .= $value[memo]." / ";

				//打印分页
				$value[pageBreak] = "";
				$rowCount++;
				if ($rowCount == 22) {
					$value[pageBreak] = "</table><p class='pageBreak'></p><table class='tableHaveBorder' cellspacing='0' cellpadding='3'>";
					$rowCount = 0;
				}
			}
			/**/
			$i = count($rowset['Yl']);
			$rowset['Yl'][$i]['ylCode'] = "<strong>合计</strong>";
			$rowset['Yl'][$i][cnt] = $totalCnt;
			$rowset['Yl'][$i][money] = $totalMoney;
		}

		//dump($rowset); exit;
		$smarty = & $this->_getView();
		$smarty->assign('title', '领料单');
		$smarty->assign("arr_field_value",$rowset);
		$smarty->assign("total_cnt", $totalCnt);
		$smarty->assign("total_money", $totalMoney);
		//$smarty->assign("total_money2", $totalMoney2);
		//$smarty->assign("deliveryman", $rowset[Deliveryman][employName]);
		$smarty->assign("user_name", $_SESSION['REALNAME']);
		$smarty->display('Cangku/Yl/ChukuView.tpl');
	}

	#添加单价
	function actionAddPrice() {
		$this->authCheck(); //财务
		FLEA::loadClass('TMIS_Pager');
		$modelName = "Model_Jichu_Client";
		$viewCangkuChuku = "select
							y.chukuNum,
							y.chukuDate,
							y.clientId,
							y.memo AS orderMemo,
							x.id,
							x.chukuId AS chukuId,
							x.ylId,
							x.danjia,
							x.cnt,
							x.money,
							x.memo from (cangku_yl_chuku2product x
							left join cangku_chuku y on(x.chukuId = y.id))";
		$str = "select
			x.id,
			x.chukuId,
			x.chukuNum,
			x.chukuDate,
			x.clientId,
			x.cnt,
			x.danjia,
			x.money,
			x.memo,
			y.compName,
			z.proName,
			z.color,
			z.guige,
			z.unit from (".$viewCangkuChuku.") x
			left join jichu_client y on x.clientId=y.id
			left join jichu_product z on x.productId=z.id where 1";
		$arr = array();
		//$arr[dateFrom] = date('Y-m-01');
		$arr[dateFrom] = date('Y-m-d');
		$arr[dateTo] = date('Y-m-d');
		$arr[clientId] = '';
		$arr[key] = '';
		$arrGet = TMIS_Pager::getParamArray($arr);

		$str .= " and chukuDate >= '$arrGet[dateFrom]' and chukuDate<='$arrGet[dateTo]'";
		if ($arrGet[clientId] != '')  $str .= " and x.clientId='$arrGet[clientId]'";
		if ($arrGet[key] != '') {
			$str .= " and (x.memo like '%$arrGet[key]%'
						or z.proName like '%$arrGet[key]%'
						or z.color like '%$arrGet[key]%'
						or z.guige like '%$arrGet[key]%')";
		}

		$str .= " order by chukuDate desc";

		//echo($str); exit;

		$rowset = $this->_mYlChuku->findBySql($str);
		//dump($rowset); exit;
		if (count($rowset)>0) foreach($rowset as & $value) {
			//echo($value['cnt']); exit;
			$tcnt += $value['cnt'];
			if ($value['money'] == 0) $value['money'] = $value['cnt'] * $value['danjia'];
			$tmoney += $value['money'];

			$this->makeEditable($value, 'danjia', $value['cnt']);
			$this->makeEditable($value, 'money');
		}
		#合计行
		$i = count($rowset);
		$rowset[$i][chukuNum] = '合计1';
		$rowset[$i][cnt] = $tcnt;
		$rowset[$i][money] = $tmoney;


		//模板变量设置
		$smarty = & $this->_getView();
		$arrFieldInfo = array(
			"chukuNum" =>"单号",
			"chukuDate" =>"日期",
			"compName" =>'客户',
			'proName'=>'产品名称',
			'guige'=>'规格',
			'color'=>'颜色',
			"cnt" =>'数量',
			'danjia' => '单价',
			'money'=>'金额',
			'memo'=>'备注',
			//'_edit'=>'操作'
		);

		$smarty->assign('title','销售出库 - 查询');
		$smarty->assign('pk', $this->_mYlChuku->primaryKey);
		$smarty->assign('arr_field_info',$arrFieldInfo);
		$smarty->assign('add_display', 'none');
		$smarty->assign('arr_condition',$arrGet);
		$smarty->assign('arr_field_value',$rowset);
		$smarty->display('TableList.tpl');
	}

	#利润分析
	function actionGainAnalyse() {
		FLEA::loadClass('TMIS_Pager');
		$arr = array();
		//$arr[dateFrom] = date('Y-m-01');
		$arr[dateFrom] = date('Y-m-d');
		$arr[dateTo] = date('Y-m-d');
		//$arr[clientId] = '';
		//$arr[key] = '';
		$arrGet = TMIS_Pager::getParamArray($arr);
		$condition[] = array('Chuku.chukuDate', $arrGet[dateFrom], '>=');
		$condition[] = array('Chuku.chukuDate', $arrGet[dateTo], '<=');
		$mRPs = & FLEA::getSingleton('Model_Cangku_ChukuProduct');
		$pager =& new TMIS_Pager($mRPs,$condition,'', 100);
		$rowset =$pager->findAll();
		//dump($rowset); exit;
		if (count($rowset)>0) foreach($rowset as & $value) {
			$value[chukuNum] = $value[Chuku][chukuNum];
			$value[chukuDate] = $value[Chuku][chukuDate];

			$mClient = & FLEA::getSingleton('Model_Jichu_Client');
			$row = $mClient->findByField('id', $value[Chuku][clientId]);
			if (count($row)>0) $value[compName] = $row[compName];

			$value[proName] = $value[Products][proName];
			$value[pinpai] = $value[Products][pinpai];
			$value[guige] = $value[Products][guige];

			if ($value[money] == 0) $value[money] = $value[danjia]*$value[cnt];
			$value[wacp] = $value[WACP]*$value[cnt];	//成本
			//echo("加权平均价:".$value[WACP]);
			//echo("数量:".$value[cnt]."<br>");
			$value[gain] = $value[money]- $value[wacp];	//利润

			$tcnt += $value[cnt];
			$tmoney += $value[money];
			$tmoney2 += $value[money2];
			$twacp += $value[wacp];
			$tgain += $value[gain];
		}
		#合计行
		$i = count($rowset);
		$rowset[$i][chukuNum] = '合计';
		$rowset[$i][cnt] = $tcnt;
		$rowset[$i][money] = $tmoney;
		$rowset[$i][money2] = $tmoney2;
		$rowset[$i][wacp] = $twacp;
		$rowset[$i][gain] = $tgain;


		//模板变量设置
		$smarty = & $this->_getView();
		$arrFieldInfo = array(
			"chukuNum" =>"单号",
			"chukuDate" =>"日期",
			"compName" =>'客户',
			'proName'=>'产品名称',
			'pinpai'=>'品牌',
			'guige'=>'规格',
			"cnt" =>'数量',
			//'danjia' => '单价',
			//'money'=>'金额',
			//'wacp'=>'成本',
			//'gain'=>'利润',
			//'money2'=>'其它金额',
		);

		$smarty->assign('title','报表中心 - 利润分析');
		$smarty->assign('pk', $this->_mYlChuku->primaryKey);
		$smarty->assign('arr_field_info',$arrFieldInfo);
		$smarty->assign('add_display', 'none');
		$smarty->assign('arr_condition',$arrGet);
		$smarty->assign('arr_field_value',$rowset);
		$smarty->assign('page_info',$pager->getNavBar($this->_url('right',$arrGet)));
		$smarty->display('TableList.tpl');

	}



	function actionAdd() {
		$this->authCheck('2-3');
		$this->_edit(array(chukuNum=>$this->_mYlChuku->getNewChukuNum()));
	}

	function actionEdit() {
		$pk=$this->_mYlChuku->primaryKey;
		$arr=$this->_mYlChuku->find($_GET[$pk]);

		if (count($arr['Yl'])>0) foreach($arr['Yl'] as & $v){
			//$mPro = & FLEA::getSingleton('Model_Jichu_Product');
			$row = $this->_mYl->find($v['ylId']);
			$v['Yl'] = $row;
			/*$v['ylCode']	= $row['ylCode'];
			$v['ylName']	= $row['ylName'];
			$v['guige']		= $row['guige'];
			$v['color']		= $row['color'];*/
		}
		//dump($arr);
		//dump($arr); exit;
		$this->_edit($arr);
	}

	function actionSave() {
		if($_POST['chukuId']!='')$rr=$this->_modelExample->find(array('id'=>$_POST['chukuId']));
		for ($i=0;$i<count($_POST['cnt']);$i++){
			if(empty($_POST['ylId'][$i])/* || empty($_POST['cnt'][$i])*/) continue;
			$arr[] = array(
				'id' => $_POST['id'][$i],
				'ylId' => $_POST['ylId'][$i],
				'len' => $_POST['len'][$i],
				'zhishu' => $_POST['zhishu'][$i],
				'cnt' => $_POST['cnt'][$i],
				'danwei' => $_POST['danwei'][$i],
				'kuweiId' => $_POST['kuweiId'][$i],
				'memo' => $_POST['memo'][$i],
				'ifRemove' => $_POST['ifRemove'][$i]
			);
		}

		$row=array(
				'id'			=>$_POST['chukuId'],
				'kind'			=>$_POST['kind']+0,
				'chukuNum'		=>$_POST['chukuNum'],
				'chukuDate'	=>empty($_POST['chukuDate'])?date("Y-m-d"):$_POST['chukuDate'],
				'depId'	=>$_POST[depId],
				'operatorId' => $_POST['operatorId'],
				'memo'		=>$_POST['orderMemo'],
				'Yl'		=> $arr
		);
		//dump($_POST);exit;
		//dump($row); exit;
       	$chukuId = $this->_mYlChuku->save($row);

		//改变库存
		if(count($rr['Yl'])) foreach($rr['Yl'] as & $v) {
			$this->_modelExample->changeYlKucun($v['ylId']);
		}
		if($arr) foreach($arr as & $v) {
			$this->_modelExample->changeYlKucun($v['ylId']);
		}

		if($_POST['Submit']=='保存') {
			if(!$_POST['from']) js_alert('保存成功！','',$this->_url('right'));
			else js_alert('保存成功！','',$this->_url($_POST['from']));
		} else {
			if($_POST['from']=='right1') js_alert('保存成功！','',$this->_url('add1'));
			else js_alert('保存成功！','',$this->_url('add'));
		}
	}

	#加权平均单价
	function _getWACP($productId) {
		$mRPs = FLEA::getSingleton('Model_Cangku_RukuProduct');
		$rowset = $mRPs->findAllByField('productId', $productId);
		//dump($rowset);
		if (count($rowset)>0) foreach($rowset as & $value) {
			$tMoney += $value[danjia]*$value[cnt];
			$tCnt += $value[cnt];
		}
		if ($tCnt != 0)	$WACP = round($tMoney/$tCnt,2);
		else $WACP = 0;
		//echo($WACP); exit;
		return $WACP;
	}

	#取得最小进货价
	function _getMisDanjia($productId) {
		$mRPs = FLEA::getSingleton('Model_Cangku_RukuProduct');
		$rowset = $mRPs->findAllByField('productId', $productId);
		$misDanjia = 100000;
		if (count($rowset)>0) foreach($rowset as & $value) {
			//$misDanjia = $value['danjia'];
			if ($value['danjia']<$misDanjia) $misDanjia = $value['danjia'];
		}
		return $misDanjia;
	}

	/* Ajax返回输入单价是否低于成本价. */
	function actionAjaxGetChengben() {
		$danjia = $_POST[danjia];
		$chengben = $this->_getWACP($_POST[productId]);
		//$chengben = $this->_getMisDanjia($_POST[productId]);
		if ($danjia < $chengben) echo 0;	//0代表低于成本价.
		else echo 1;	//正常.
		exit;
	}

	//打印对账单
	function actionPrintCheckForm() {
		$clientId=$_GET[clientId];
		$dateFrom = $_GET[dateFrom];
		$dateTo = $_GET[dateTo];

		//dump($_GET); exit;

		$str = "select x.*,y.compName,x.danjia*x.cnt as money from view_cangku_chuku x inner join jichu_client y on x.clientId=y.id where x.clientId='$clientId' and chukuDate>='$dateFrom' and chukuDate<='$dateTo'";

		$m = FLEA::getSingleton('Model_Jichu_Client');
		$arr = $m->find($clientId);

		$modelProduct = & FLEA::getSingleton('Model_Jichu_Product');

		$rowset = $m->findBySql($str);

		//dump($rowset); //exit;
		foreach ($rowset as & $value) {
			$rowProduct = $modelProduct->find($value[productId]);
			if (count($rowProduct)>0) {
				$value[proName] = $rowProduct[proName];
				$value[guige] = $rowProduct[guige];
				$value[color] = $rowProduct[color];
			}

			$totalCnt += $value[cnt];
			$totalMone += $value[money];
		}

		//加入合计数量

		$rowset[] = array(
			"chukuDate" =>"<b>合计</b>",
			"cnt"=>"<b>".$totalCnt."</b>",
			"money"=>"<b>".$totalMone."</b>"
		);

		/*
		$arrFieldInfo = array(
			"chukuDate" =>"日期",
			"chukuNum" =>"单号",
			"compName" =>"客户",
			"proName"=>"品名",
			"guige"=>"规格",
			"color"=>"颜色",
			"cnt"=>"数量",
			"danjia"=>"单价",
			"money"=>"金额"
		);
		*/

		//dump($rowset); exit;

		$smarty = & $this->_getView();
		$smarty->assign('title', '应收款对帐单');
		$smarty->assign('compName',$arr[compName]);
		//$smarty->assign('arr_field_info',$arrFieldInfo);
		$smarty->assign('arr_field_value',$rowset);
		$smarty->display('Caiwu/Ar/ArCheckForm.tpl');
	}

	function _edit($arr, $tag=0) {
		//dump($arr); exit;
		$smarty = & $this->_getView();
		$smarty->assign('title', '领料出库登记');
		$smarty->assign('operator_id', $_SESSION['USERID']);
		$smarty->assign('real_name', $_SESSION['REALNAME']);
		$smarty->assign("arr_field_value",$arr);
		$smarty->assign('default_date',date("Y-m-d"));
		$pk=$this->_mYlChuku->primaryKey;
		$primary_key=(isset($_GET[$pk])?$pk:"");
		$smarty->assign("pk",$primary_key);
		$smarty->display('Cangku/Yl/ChukuEdit.tpl');
	}

	function actionRemove() {
		//改变库存
		$chuku = $this->_modelExample->find(array('id'=>$_GET['id']));
		//dump($ruku);exit;
		if ($this->_modelExample->removeByPkv($_GET[id])) {
			if($chuku['Yl']) foreach($chuku['Yl'] as & $v) {
				$this->_modelExample->changeYlKucun($v['ylId']);
			}
		}

		if($_GET['from']=='') redirect($this->_url("right"));
		else redirect($this->_url($_GET['from']));
	}

}
?>