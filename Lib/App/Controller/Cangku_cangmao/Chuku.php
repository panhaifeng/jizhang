<?php
FLEA::loadClass('TMIS_Controller');
FLEA::loadClass('TMIS_Common');
class Controller_Cangku_Chuku extends Tmis_Controller {
	var $_modelChuku;
    var $_modelProduct;
	var $displayTaihao;
	var $title = '半成品仓库出库';
      var $_kind2=1;
      var $_arrFieldInfo= array(
			'_edit' => array('text'=>'操作','width'=>120),
			'chukuNum' => '出库单号',
			'chukuDate' => '出库日期',
			'orderCode' => '订单号',
			'compName'=>'客户名称',
			// 'operator' => '制单人',
			'proCode'=>'编码',
			'proName'=>'品名',
			'guige'=>'规格',
			'kuweiName'=>'库位',
			// 'danjia'=>'吨价',
			// 'money' =>'金额',
			'cnt'=>'数量（吨）',
			'tuishaCnt'=>'退纱数量',
			'sjCkCnt'=>'实际出库',
			'memo'=>'备注',
		);
	function Controller_Cangku_Chuku() {
		$this->_modelExample = & FLEA::getSingleton('Model_Cangku_Chuku');
		$this->_modelChuku = & FLEA::getSingleton('Model_Cangku_Chuku');
		$this->_modelRuku = & FLEA::getSingleton('Model_Cangku_Ruku');
        $this->_modelProduct= & FLEA::getSingleton('Model_Jichu_Product');
		$this->_modelChukuProduct = & FLEA::getSingleton('Model_Cangku_ChukuProduct');
		$this->_modelExpense=& FLEA::getSingleton('Model_Caiwu_Expense');
		$this ->_modelOrder = & FLEA::getSingleton('Model_Trade_Order');
		$this->_modelRuku2Pro = & FLEA::getSingleton('Model_Cangku_RukuProduct');
		$this->_modelChuku->_head="BC";
		$this->_fieldList=array(
			'proCode'=>array('type'=>'text','text'=>'产品编号' ,'hiddenName'=>'proId'),
			'proName'=>array('type'=>'text','text'=>'产品名称','hiddenName'=>'order2productId'),
			'guige'=>array('type'=>'text','text'=>'产品规格'),
			'guigeOrd'=>array('type'=>'text','text'=>'订单规格'),
                   'unit'=>array('type'=>'text','text'=>'单位'),
                   'danjia' =>array('type'=>'text','text'=>'单价',"onChange"=>"setXsws(this)"),
                   'cnt'        =>array('type'=>'text','text'=>'长度'),
                   'money' =>array('type'=>'text','text'=>'金额'),
                   'memo'  =>array('type'=>'text','text'=>'备注')    
		);
	}
	function actionRight(){
		$this->authCheck('3-4');
		// $arrFieldInfo = array(
		// 	'_edit' => '操作',
		// 	'chukuNum' => '出库单号',
		// 	'chukuDate' => '出库日期',
		// 	'compName'=>'客户名称',
		// 	'operator' => '制单人',
		// 	'proCode'=>'编码',
		// 	'proName'=>'名称',
		// 	'guige'=>'规格',
		// 	'unit'=>'单位',
		// 	'danjia'=>'单价',
		// 	'money' =>'金额',
		// 	'chukuOrder'=>'出库流转单号',
		// 	//'kuweiName'=>'库位',
		// 	'length'=>'长度',
		// 	// 'cntCi'=>'次品数',
		// 	//'danjia'=>'单价',
		// 	//'money'=>'金额',
		// 	'memo'=>'备注',
		// );
		$this->_showMingxi($this->_arrFieldInfo);
	}
	
	//销售模块中查看出库明细
	function actionMingxi(){
		$this->_showMingxi($arrFieldInfo = array(
			'chukuNum' => '单号',
			'chukuDate' => '日期',
			'compName'=>'客户名称',
			//'typeName' => '送货人',
			'operator' => '制单人',
			'proName'=>'产品名称',
			'cnt'=>'数量',
			'danjia'=>'单价',
			'money'=>'金额',
			'memo'=>'备注'
		));
	}

	#查看详细
	function actionView() {
		$pk=$this->_modelChuku->primaryKey;
		$rowset=$this->_modelChuku->find($_GET[$pk]);
		//dump($rowset);
		$mPro = & FLEA::getSingleton('Model_Jichu_Product');
		$rowCount = 0;
		if (count($rowset)>0) if (count($rowset[Pro])>0) {
			foreach($rowset[Pro] as & $value) {
				$row = $mPro->findByField('id', $value[productId]);
				//dump($row);exit;
				$value['proCode'] = $row['proCode'];
				$value['proName'] = $row['proName'];
				$value['color'] = $row['color'];
				$value['guige'] = $row['guige'];
				$value['cnt']=round($value['cnt'],2);
				$value['cntCi']=round($value['cntCi'],2);
				$value[unit] = $row[unit];
				if ($value[money] == 0)	$value[money] = $value[cnt] * $value[danjia];
				$value['cnt1']=$value['cnt']+$value['cntCi'];
				$totalCnt += $value['cnt1'];
				$totalCi+=$value['cntCi'];
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
		}	
		//dump($rowset['Pro']); exit;
		// dump($rowset);exit;
		$smarty = & $this->_getView();
		$smarty->assign('title', '送货单');
		$smarty->assign("arr_field_value",$rowset);
		$smarty->assign("total_cnt", $totalCnt);
		$smarty->assign("total_ci", $totalCi);
		$smarty->assign("total_money", $totalMoney);
		$smarty->assign("total_money2", $totalMoney2);
		$smarty->assign("deliveryman", $rowset[Deliveryman][employName]);
		$smarty->assign("user_name", $_SESSION['REALNAME']);
		$smarty->display('Cangku/ChukuView.tpl');
	}

	function actionAdd() {
		$this->authCheck('3-3');
		$this->_edit(array(chukuNum=>$this->_modelChuku->getNewChukuNum(),kind=>$_GET['kind']));
	}

	function actionEdit() {
		$pk=$this->_modelChuku->primaryKey;
		$arr=$this->_modelChuku->find($_GET[$pk]);
		// dump($arr);exit;
		// $arr ['Order']= $this->_modelOrder->find($arr['orderId']);
		if (count($arr['Pro'])>0) foreach($arr['Pro'] as & $v){
			$mPro = & FLEA::getSingleton('Model_Jichu_Product');
			
			$kw=&FLEA::getSingleton('Model_Jichu_Kuwei');
			$_row=$kw->find($v['kuweiId']);
			$v['kw']=$_row['kuweiName'];
			//查找花型的信息
			$sql="select * from trade_order2product where id='{$v['order2productId']}'";
			$temp=mysql_fetch_assoc(mysql_query($sql));
			$temp['cnt']=round($temp['cnt'],4);

			$row = $mPro->find($temp['productId']);
			$v['guigeOrd']=$temp['guigeOrd'];
			$v['Product'] = $row;
			$v['proCode'] = $row['proCode'];
			$v['proName']	= $row['proName'];
			$v['guige']= $row['guige'];
			$v['color']= $row['color'];
			$v['cnt']=round($v['cnt'],2);
			// $v['cntCi']=round($v['cntCi'],2);
			$v['zxDanjia'] = round($v['zxDanjia'],6);
			$v['proId']= $v['productId'];

			//查找入库信息
			$rrr=$this->_modelRuku2Pro->find($v['ruku2proId']);
			$v['pihao']=$rrr['pihao'];
			$v['kuweiName']=$rrr['Kuwei']['kuweiName'];
			$v['supplierId']=$rrr['Ruku']['supplierId'];
			//查找供应商
			$sql="select * from jichu_supplier where id='{$v['supplierId']}'";
			$resc=mysql_fetch_assoc(mysql_query($sql));
			$v['supplierName']=$resc['compName'];
			// dump($rrr);exit;
			//查找总入库
			$sql="select sum(cnt) as cnt from cangku_ruku2product where id='{$v['ruku2proId']}' or ruku2proId='{$v['ruku2proId']}'";
			$t_rk=mysql_fetch_assoc(mysql_query($sql));
			// dump($sql);exit;
			$v['cntRuku']=round($t_rk['cnt'],4);
			//查找已领料
			$sql="select sum(cnt) as cnt from cangku_chuku2product where ruku2proId='{$v['ruku2proId']}'";
			$t_ck=mysql_fetch_assoc(mysql_query($sql));
			$v['cntChuku']=round($t_ck['cnt'],4);
			$v['yucun']=$v['cntRuku']-$v['cntChuku'];
			$arr['order2productId']=$v['order2productId'];
			// $v['cntKucun']=$re['initCnt']+$re['kucunCnt']+$v['cnt'];
			$temp['danjia']=round($temp['danjia'],6);
			$arr['danjiack']=$temp['danjia'];
			$v['danjiack']=round($v['danjiack'],6);
			if($v['order2productId']>0){
				$arr['proName']="品名：{$v['proName']}；规格：{$v['guige']}；要货数：{$temp['cnt']}；单价{$temp['danjia']}";
			}
		}
		// dump($arr);exit;
		$this->_edit($arr);
	}
	
    
	function actionSave() {			
	    // dump($_POST);exit;	
		if($_POST['chukuId']>0){
			//保存前必须先确认出库单是否存在
			$rr=$this->_modelExample->find(array('id'=>$_POST['chukuId']));
			if(!$rr) {
				js_alert('出库单不存在，请确认！',null,$this->_url('right'));exit;
			}
						
		}		
		//$k=0;

		for ($i=0;$i<count($_POST['ruku2proId']);$i++){
			if(empty($_POST['ruku2proId'][$i]) || empty($_POST['cnt'][$i])) continue;
			$arr[] = array(
				'id'=> $_POST['id'][$i],
				'order2productId'=>$_POST['order2productId']+0,
				'productId'=> $_POST['productId'][$i]+0,
				'ruku2proId'=>$_POST['ruku2proId'][$i],
				//'kuweiId'=>$_POST['kuweiId'][$i],
				'cnt'     =>$_POST['cnt'][$i]+0,
				'zxDanjia'=> $_POST['zxDanjia'][$i]+0,
				'danjiack'=> $_POST['danjiack'][$i]+0,
				// 'memo'=> $_POST['memo'][$i],
			);

		}
		//如果明细为空，整单删除
		if(count($arr)==0 && $_POST['chukuId']>0) {			
			$this->_modelChuku->removeByPkv($_POST['chukuId']);
		} else {
			$row=array(
				'id'=>$_POST['chukuId'],
				// 'kind'=>$_POST['kind']+0,
				'orderId'=>$_POST['orderId']+0,
				'chukuNum'=>$_POST['chukuNum'],
				'chukuDate'=>empty($_POST['chukuDate'])?date("Y-m-d"):$_POST['chukuDate'],
				'clientId'=>$_POST['clientId']+0,
				'operatorId'=>$_POST['operatorId']+0,
				'memo'=>$_POST['chukuMemo'],
				'Pro'=>$arr
			); 
			// dump($row);exit;
			$chukuId = $this->_modelChuku->save($row);
			if($_POST['chukuId']>0) $chukuId=$_POST['chukuId'];

			//取最小的日期
			if($_POST['chukuId']>0){
				$date=strtotime($row['chukuDate'])>strtotime($rr['chukuDate'])?$rr['chukuDate']:$row['chukuDate'];
			}else{
				$date=$row['chukuDate'];
			}
			// dump($date);exit;
			foreach($arr as & $v){
				$this->_modelRuku2Pro->changeKucun(array('id'=>$v['ruku2proId'],'date'=>$date));
			}
		}
		
		if($_POST['Submit']=='保存') {
			if(!$_POST['from']) redirect($this->_url('right'));
			else redirect($this->_url($_POST['from']));
		} else {
			if($_POST['from']=='right1') redirect($this->_url('add1'));
			else redirect($this->_url('add'));
		}
	}

	//打印对账单
	function actionPrintCheckForm() {
		$clientId=$_GET[clientId];
		$dateFrom = $_GET[dateFrom];
		$dateTo = $_GET[dateTo];

		$viewCangkuChuku = "select
							y.chukuNum,
							y.chukuDate,
							y.clientId,
							y.memo AS orderMemo,
							y.orderType,
							x.id,
							x.chukuId AS chukuId,
							x.productId,
							x.danjia,
							x.cnt,
							x.money,
                                                        x.bieming,
                                                        x.guigeClient,
							x.memo from (cangku_chuku2product x
							left join cangku_chuku y on(x.chukuId = y.id))";
		$str = "select
				x.*,y.compName,
				x.danjia*x.cnt as money
				from (".$viewCangkuChuku.") x
				inner join jichu_client y on x.clientId=y.id
				where x.clientId='$clientId' and chukuDate>='$dateFrom' and chukuDate<='$dateTo'";

		$str .= " order by chukuDate desc, chukuNum desc";
		$m = FLEA::getSingleton('Model_Jichu_Client');
		$arr = $m->find($clientId);

		$modelProduct = & FLEA::getSingleton('Model_Jichu_Product');
		$rowset = $m->findBySql($str);

		foreach ($rowset as & $value) {
			if ($value['orderType'] == 1) $value['orderType'] = '普通';
			if ($value['orderType'] == 2) $value['orderType'] = "<span style='font-weight:bold'>退货</span>";
			if ($value['orderType'] == 3) $value['orderType'] = "<span style='font-style:italic'>补发</span>";

			$rowProduct = $modelProduct->find($value[productId]);
			//dump($rowProduct); exit;
			if (count($rowProduct)>0) {
				$value['proName']	= $rowProduct['proName'];
				$value['guige']		= $rowProduct['guige'];
				$value['color']		= $rowProduct['color'];
				$value['unit']		= $rowProduct['unit'];
                                $value['bieming']		= $rowProduct['bieming'];
                                $value['guigeClient']		= $rowProduct['guigeClient'];
			}

			$totalCnt		+= $value[cnt];
			$totalMoney		+= $value[money];
		}

		$totalMoneyCap = TMIS_Common::trans2rmb($totalMoney);	//金额大写
		foreach($rowset as & $v){
			$v['chukuNum2chukuDate']=$v[chukuDate].','.$v[chukuNum];
		}
		//dump($rowset);
		$rowset=array_group_by($rowset,'chukuNum2chukuDate');
		//dump($rowset);
		$smarty = & $this->_getView();
		$smarty->assign('title', '应收款对帐单');
		$smarty->assign('compName',$arr['compName']);
		$smarty->assign('total_money', $totalMoney);
		$smarty->assign('total_money_cap', $totalMoneyCap);
		$smarty->assign('arr_field_value',$rowset);
		$smarty->display('Caiwu/Ar/ArCheckForm.tpl');
	}

	function _edit($Arr) {
		$smarty = & $this->_getView();
		$smarty->assign('title', '成品出库登记');
		$smarty->assign('operator_id', $_SESSION['USERID']);
		$smarty->assign('real_name', $_SESSION['REALNAME']);
		$smarty->assign("arr_field_value",$Arr);
		$smarty->assign("arr_field_info",$this->_fieldList);
		// dump($Arr);exit;
		$smarty->assign('default_date',date("Y-m-d"));
		$pk=$this->_modelChuku->primaryKey;
		$primary_key=(isset($_GET[$pk])?$pk:"");
		$smarty->assign("pk",$primary_key);
		$smarty->display('Cangku/ChukuEdit.tpl');
	}

	function actionRemove() {
		//改变库存
		$ruku = $this->_modelExample->find(array('id'=>$_GET['id']));
		//dump($ruku);exit;
		if($ruku['Pro']) foreach($ruku['Pro'] as & $v) {
			if($v['guozhangId']>0) {
				js_alert('已过账记录禁止删除!',null,$this->_url('right'));
				exit;
			}
		}
		//dump($ruku);exit;
		if ($this->_modelExample->removeByPkv($_GET[id])) {
			if($ruku['Pro']) foreach($ruku['Pro'] as & $v) {
				//如果是退货信息，关联删除入库中的信息
			$sql="select group_concat(rukuId) as rukuId from cangku_ruku2product where chuku2proId='{$v['id']}'";
			$res=$this->_modelRuku2Pro->findBySql($sql);
			if($res[0]['rukuId']!=''){
				$sql="delete from cangku_ruku2product where rukuId in ({$res[0]['rukuId']})";
				$this->_modelRuku2Pro->execute($sql);
				$sql="delete from cangku_ruku where id in ({$res[0]['rukuId']})";
				$this->_modelRuku2Pro->execute($sql);
			}
			//修改库存
				$this->_modelRuku2Pro->changeKucun(array('id'=>$v['ruku2proId'],'date'=>$v['chukuDate']));
			}
		}

		if($_GET['from']=='') redirect($this->_url("right"));
		else redirect($this->_url($_GET['from']));
	}

	function _showMingxi($arrFieldInfo){
		FLEA::loadClass('TMIS_Pager');
		$arrGet = TMIS_Pager::getParamArray(array(
			//'dateFrom' =>date("Y-m-d",mktime(0,0,0,date("m"),date("d")-30,date("Y"))),
			'dateFrom' =>date("Y-m-d",mktime(0,0,0,date("m")-1,date("d"),date("Y"))),
			'dateTo' => date("Y-m-d"),
			'clientId'=>'',
			'kuweiId'=>'',
			'orderCode'=>'',
			'supplierId2'=>'',
         	'productId'=>'',
			// 'leibie'=>0,
			'guigeDesc'=>'',
			'proName'=>'',
			// 'key'=>''
		));
		$str="select 
				x.*,y.guozhangId,
				y.id as chuku2proId,
				y.cnt,
				y.danjia,	
				y.money,
				y.WACP,
				z.proName,
				z.guige,
				z.proCode,
				z.unit,
				a.compName,
				k.kuweiName,
				c.orderCode
			from cangku_chuku x
		       left join cangku_chuku2product y on y.chukuId=x.id
		       left join cangku_ruku2product e on e.id=y.ruku2proId
		       left join cangku_ruku f on f.id=e.rukuId
		       left join jichu_product z on z.id=e.productId
		       left join jichu_client a on a.id=x.clientId
		       left join jichu_kuwei k on e.kuweiId=k.id
		       left join trade_order c on c.id=x.orderId
		       where 1 
		";
		if($arrGet['dateFrom']!='')$str.=" and x.chukuDate>='$arrGet[dateFrom]'";
		if($arrGet['dateTo']!='')$str.=" and x.chukuDate<='$arrGet[dateTo]'";
		if ($arrGet['clientId'] != '')$str.=" and x.clientId='$arrGet[clientId]'";
		if ($arrGet['orderCode'] != '')$str.=" and c.orderCode like '%{$arrGet['orderCode']}%'";
		// if ($arrGet['orderType'] != 0)$str.=" and x.orderType='$arrGet[orderType]'";
		if ($arrGet['guigeDesc'] != '')$str.=" and z.guige like '%{$arrGet['guigeDesc']}%'";
		if ($arrGet['proName'] != '') $str.=" and(z.proName like '%{$arrGet['proName']}%')";
		if($arrGet['kuweiId']!=''){
         	$str.=" and e.kuweiId='{$arrGet['kuweiId']}'";
         }
         if($arrGet['productId']!=''){
         	$str.=" and e.productId='{$arrGet['productId']}'";
         }
         if($arrGet['supplierId']!=''){
         	$str.=" and f.supplierId='{$arrGet['supplierId']}'";
         }
        // if ($arrGet['leibie'] != '') $str.=" and x.kind ='{$arrGet['leibie']}'";
		$str.=" order by chukuDate,x.id desc";
		// echo $str;exit;
		$pager =& new TMIS_Pager($str);
		$rowset =$pager->findAll();
		/*$pager =& new TMIS_Pager($this->_modelChuku,$condition);
		$rowset =$pager->findAll();*/
		// dump($rowset);exit;
		$mPro = & FLEA::getSingleton('Model_Jichu_Product');
		$mUser = & FLEA::getSingleton('Model_Acm_User');
		if (count($rowset)>0) foreach($rowset as & $value) {
			// $value['money']=$value['danjia']*$value['length'];
			$user=$mUser->find($value['operatorId']);
			$value[operator] = $user['realName'];
			//$value['danjia'] = number_format($value['danjia'],3);
			$value['cnt']=round($value['cnt'],2);
			// $value['cntCi']=round($value['cntCi'],2);
			$tempArr = array();
			
			//过账记录禁止操作
			if($value['guozhangId']>0){
				$value['_bgColor'] = 'lightgreen';
				$edit="<span title='已过帐，禁止修改删除操作'>修改</span>";
				$remove="<span title='已过帐，禁止修改删除操作'> | 删除</span>";
			}else{
				$edit= "<a href='".$this->_url('Edit',array(id=>$value[id]))."'>修改</a>";
				if($value['kind']==1){
					$msg="将会同时删除入库表中关联的两条信息！";
				}
				$remove=" | <a href='".$this->_url('Remove',array(id=>$value[id],'from'=>'right'))."' onclick=\"return confirm('{$msg}确认删除整单信息吗?')\">删除</a>";
			}

			//退纱数量
			$sql="select sum(cnt) as cnt from cangku_chuku2product where chuku2proId='{$value['chuku2proId']}'";
			$res=mysql_fetch_assoc(mysql_query($sql));
			$value['tuishaCnt']=$res['cnt']==0?'':abs(round($res['cnt'],4));
			if($value['kind']==0){
				//实际出库数量
				$value['sjCkCnt']=$value['cnt']-$value['tuishaCnt'];
				//退纱
				$tuisha=" | <a href='".$this->_url('Tuisha',array(
					'parentId'=>$value['chuku2proId'],
					'TB_iframe'=>1
				))."' class='thickbox' title='退货编辑'>退货</a>";
			}else{
				//修改
				$edit="<a href='".$this->_url('Tuisha',array(
					'id'=>$value['chuku2proId'],
					'TB_iframe'=>1
				))."' class='thickbox' title='退货编辑'>修改</a>";
				$value['_bgColor']="lightpink";
				$tuisha=" | <span title='退货记录，禁止退纱'>退货</span>";
			}

			$value['_edit']=$edit.$remove.$tuisha;

		}
		// dump($rowset);exit;
		$heji=$this->getHeji($rowset,array('cnt','sjCkCnt'),'chukuNum');
		$rowset[]=$heji;
		$smarty = & $this->_getView();
		$smarty->assign('title','成品出库查询');
		$smarty->assign('pk', $this->_modelChuku->primaryKey);
		$smarty->assign('arr_field_info',$arrFieldInfo);
		$smarty->assign('arr_field_value',$rowset);
		$smarty->assign('arr_condition', $arrGet);
		$smarty->assign('arr_js_css',$this->makeArrayJsCss(array('tip','calendar','thickbox')));
		$smarty->assign('page_info',$pager->getNavBar($this->_url('right',$arrGet))."<font color='green'>绿色表示已入账</font>");
		$smarty->display('TableList.tpl');
	}
	#设置单价
	function actionSetDanjia(){
		$this->authCheck(23);
		$arrFieldInfo = array(
			'chukuNum' => '单号',
			'chukuDate' => '日期',
			'compName'=>'客户名称',
			'operator' => '制单人',
			'proName'=>'产品名称',
			'cnt'=>'数量',
			'danjiaD'=>'订单单价',
			'danjia'=>'单价',
			'money'=>'金额',
			'memo'=>'备注',
			'_edit' => '操作'
		);
		FLEA::loadClass('TMIS_Pager');
		$arrGet = TMIS_Pager::getParamArray(array(
			'dateFrom' =>date("Y-m-d",mktime(0,0,0,date("m")-1,date("d"),date("Y"))),
			'dateTo' => date("Y-m-d"),
			'clientId'=>'',
			'orderCode'=>'',
			'key'=>''
		));
		$str="select x.*,y.order2productId,y.id as id1,y.guozhangId,y.cnt,y.danjia,z.proName,z.guige,a.compName from cangku_chuku x
		    left join cangku_chuku2product y on y.chukuId=x.id
		    left join jichu_product z on z.id=y.productId
		    left join jichu_client a on a.id=x.clientId
		    where 1
		";
		if($arrGet['dateFrom']!='')$str.=" and x.chukuDate>='$arrGet[dateFrom]'";
		if($arrGet['dateTo']!='')$str.=" and x.chukuDate<='$arrGet[dateTo]'";
		if ($arrGet['clientId'] != '')$str.=" and x.clientId='$arrGet[clientId]'";
		if ($arrGet['orderCode'] != '')$str.=" and x.chukuNum like '%{$arrGet[orderCode]}%'";
		if ($arrGet['orderType'] != 0)$str.=" and x.orderType='$arrGet[orderType]'";
		if ($arrGet['key'] != '') $str.=" and x.memo like '%{$arrGet[key]}%'";
		$str.=" order by chukuDate desc";
		$pager =& new TMIS_Pager($str);
		$rowset =$pager->findAll();
		//dump($rowset);
		$mPro = & FLEA::getSingleton('Model_Jichu_Product');
		$mUser = & FLEA::getSingleton('Model_Acm_User');
		if (count($rowset)>0) foreach($rowset as & $value) {
			$str="select * from trade_order2product where id='{$value['order2productId']}'";
			$re=mysql_fetch_assoc(mysql_query($str));
			$value['danjiaD']=round($re['danjia'],2);
			$value['money']=$value['danjia']*$value['cnt'];
			$user=$mUser->find($value['operatorId']);
			$value[operator] = $user['realName'];
			$tempArr = array();
			$value['_edit'] = "<a href='".$this->_url('AddDanjia',array('id'=>$value['id1'],'TB_iframe'=>1))."' class='thickbox' title='设置单价'>设置单价</a>";
		}

		$smarty = & $this->_getView();
		$smarty->assign('title','成品单价设置');
		$smarty->assign('add_display','none');
		$smarty->assign('pk', $this->_modelChuku->primaryKey);
		$smarty->assign('arr_field_info',$arrFieldInfo);
		$smarty->assign('arr_field_value',$rowset);
		$smarty->assign('arr_condition', $arrGet);
		$smarty->assign('arr_js_css',$this->makeArrayJsCss(array('tip','calendar','thickbox')));
		$smarty->assign('page_info',$pager->getNavBar($this->_url('right',$arrGet)));
		$smarty->display('TableList2.tpl');
	}
	function actionAddDanjia(){
		/*$str="select x.* from,y.proName cangku_chuku2product x
		    left join jichu_product y on y.id=x.productId
		    where x.id='{$_GET['id']}'
		";*/
		$rowset=$this->_modelChukuProduct->find($_GET['id']);
		if($rowset['guozhangId']>0)js_alert('该出库记录已入账，不能修改单价，如需修改单价，请先取消入账','parent.location.href=parent.location.href');
		//dump($rowset);
		$str="select * from trade_order2product where id='{$rowset['order2productId']}'";
		$re=mysql_fetch_assoc(mysql_query($str));
		$rowset['danjiaD']=round($re['danjia'],2);
		$smarty = & $this->_getView();
		$smarty->assign('aRow',$rowset);
		$smarty->display('Cangku/SetDanjia.tpl');
	}
	function actionSaveDanjia(){
		//dump($_POST);exit;
		$money=$_POST['danjia']*$_POST['cnt'];
		$str="update cangku_chuku2product set danjia='{$_POST['danjia']}',money='$money' where id='{$_POST['id']}'";
		mysql_query($str);
		//js_alert('设置成功!','window.parent.tb_remove()',$this->_url('SetDanjia'));
		js_alert('设置成功!','window.parent.location.href=window.parent.location.href');
	}
	function actionPopup(){
		FLEA::loadClass("TMIS_Pager");
		$arr = & TMIS_Pager::getParamArray(array(
			'clientId'=>'',
			'key'=>'',
		));
		if($arr['key']){
			$condition.=" and (z.proName like '%{$arr['key']}%' or z.proCode like '%{$arr['key']}%' or x.chukuNum like '%{$arr['key']}%')";
		}
		if($arr['clientId']){
                    $condition.=" and a.clientId={$arr['clientId']}";
		}
		$sql="select 
				x.*,y.guozhangId,
				y.cnt,
				y.danjia,	
				y.money,
				y.WACP,
				z.proName,
				z.guige,
				z.proCode,
				z.unit,
				a.compName,
				k.kuweiName,
				d.depName 
			from cangku_chuku x
		       left join cangku_chuku2product y on y.chukuId=x.id
		       left join jichu_product z on z.id=y.productId
		       left join jichu_client a on a.id=x.clientId
		       left join jichu_kuwei k on y.kuweiId=k.id
                    left join jichu_department d on d.id = x.depId
		       where 1 and x.kind2={$this->_kind2}  $condition
		";
		$pager = & new TMIS_Pager($sql);
		$rowset = $pager->findAll();
		$smarty = $this->_getView();
		$smarty->assign("arr_field_info",array(
                   "chukuNum"=>'出库单号',
                   "chukuDate"=>"出库日期",
                   "proCode"   =>"产品编号",
                   "proName"  =>"产品名称",
                   "compName"=>"客户名称",
                   "danjia"         =>"单价",
                   "cnt"             =>"数量",
                   "money"       =>"金额"
		));
		$smarty->assign("arr_field_value",$rowset);
		$smarty->assign("page_info",$pager->getNavBar($this->_url("Popup",$arr)));
		$smarty->assign("arr_condition",$arr);
		$smarty->assign("add_display","none");
		$smarty->assign('arr_js_css',$this->makeArrayJsCss(array('')));
		$smarty->display("Popup/Common.tpl");
	}


	//退纱编辑
	function actionTuisha(){
		if($_GET['parentId']>0){
			$ck=$this->_modelChukuProduct->find($_GET['parentId']);
			$arr=$this->_modelRuku2Pro->find($ck['ruku2proId']);
			$row=array(
				'kind'=>1,
				'chuku2proId'=>$ck['id'],
				'cnt2'=>$ck['cnt'],
				'kuweiId'=>$arr['kuweiId'],
				'productId'=>$arr['productId'],
				'pihao'=>$arr['pihao'],
				'proCode'=>$arr['Products']['proCode'],
				'proName'=>$arr['Products']['proName'],
				'guige'=>$arr['Products']['guige'],
				'zxDanjia'=>$ck['zxDanjia'],
				'chukuNum'=>$ck['Chuku']['chukuNum'],
				'chukuDate2'=>$ck['Chuku']['chukuDate'],
				'supplierId'=>$arr['Ruku']['supplierId'],
			);
		}elseif($_GET['id']>0){
			//此次退库记录
			$row=$this->_modelChukuProduct->find($_GET['id']);
			//此次退库对应的原始入库记录
			$arr2=$this->_modelRuku2Pro->find($row['ruku2ProId']);
			//此次退库对应的出库记录
			$arr=$this->_modelChukuProduct->find($row['chuku2proId']);
			//查找此次退货对应在重新入库记录
			$arr_tui=$this->_modelRuku2Pro->find(array('chuku2proId'=>$row['id'],'ruku2ProId'=>0));
			// dump($arr2);exit;

			$row['chukuDate2']=$arr['Chuku']['chukuDate'];
			$row['cnt2']=$arr['cnt'];
			$row['chukuNum']=$arr['Chuku']['chukuNum'];
			$row['supplierId']=$arr2['Ruku']['supplierId'];
			$row['kuweiId']=$arr_tui['kuweiId'];
			$row['czDanjia']=$arr_tui['czDanjia'];
			$row['proCode']=$arr2['Products']['proCode'];
			$row['proName']=$arr2['Products']['proName'];
			$row['guige']=$arr2['Products']['guige'];
			$row['pihao']=$arr2['pihao'];

			$row['kind']=$row['Chuku']['kind'];
			$row['chukuDate']=$row['Chuku']['chukuDate'];
		}
		// dump($row);exit;
		$smarty = & $this->_getView();
		$smarty->assign('title', '退纱编辑');
		$smarty->assign('aRow', $row);
		$smarty->display('Cangku/TuishaCkEdit.tpl');
	}

	//保存退纱记录
	function actionSaveTuisha(){
		// dump($_POST);exit;
		$_POST['chukuDate']=empty($_POST['chukuDate'])?date("Y-m-d"):$_POST['chukuDate'];
		$temp=$this->_modelChukuProduct->find($_POST['chuku2proId']);
		// dump($temp);exit;
		if($_POST['chukuId']>0){
			$rr=$this->_modelExample->find($_POST['chukuId']);
		}
		$son[]=array(
				'id'=>$_POST['id'],
				'chuku2proId'=>$_POST['chuku2proId'],
				'order2productId'=>$temp['order2productId']+0,
				'productId'=> $temp['productId']+0,
				'ruku2proId'=>$temp['ruku2proId'],
				'cnt'     =>0-abs($_POST['cnt']),
				'zxDanjia'=> $_POST['zxDanjia']+0,
				'memo'=>$_POST['memo'],
			);
		$arr=array(
			'id'=>$_POST['chukuId'],
			'kind'=>$_POST['kind'],
			'chukuNum'=>$this->_modelChuku->getNewChukuNum(),
			// 'chukuDate'=>$_POST['chukuDate'],
			'orderId'=>$temp['Chuku']['orderId']+0,
			'chukuDate'=>$_POST['chukuDate'],
			'clientId'=>$temp['Chuku']['clientId']+0,
			'operatorId'=>$_SESSION['USERID']+0,
			'memo'=>'退纱操作',
			'Pro'=> $son

		);
		$chukuId = $this->_modelExample->save($arr);
		$sql="select id from cangku_chuku2product where chukuId='{$chukuId}'";
		$temp_ck=$this->_modelChukuProduct->findBySql($sql);
		$chukuId=$temp_ck[0]['id'];
		//需要在入库表里同时保存两条记录，相当于调库操作(不需要过账审核)
		if($_POST['id']>0){
			$chukuId=$_POST['id'];
			$arr_tui=$this->_modelRuku2Pro->findAll(array('chuku2proId'=>$_POST['id']));
			foreach($arr_tui as & $v){
				if($v['ruku2ProId']>0){
					$one_id=$v['id'];
					$ruku_one=$v['rukuId'];
				}
				else{
					$two_id=$v['id'];
					$ruku_two=$v['rukuId'];
				}
			}
		}
		

		//原始入库记录
		$rk_init=$this->_modelRuku2Pro->find($temp['ruku2proId']);
		// dump($rk_init);exit;
		//原始入库记录的调出记录
		$rk_one[]=array(
				'id'=>$one_id,
				'ruku2ProId'=>$rk_init['id'],
				'chuku2proId'=>$chukuId,
				'productId' => $rk_init['productId'],
				"kuweiId"=>$rk_init['kuweiId'],
				'pihao'   =>$rk_init['pihao'],
				'danjia'=>$rk_init['danjia'],
				'money'=>$rk_init['danjia']*(0-abs($_POST['cnt'])),
				'cnt' => 0-abs($_POST['cnt']) ,
				'isGuozhang'=>1,
				// 'zxDanjia' => $_POST['zxDanjia'],
				// 'memo' => ,
			);
		$arr_one=array(
			'id'=>$ruku_one,
			'kind'=>3,
			'rukuNum'=>$this->_modelRuku->getNewRukuNum(),
			'rukuDate'=>$_POST['chukuDate'],
			'supplierId'=>$rk_init['Ruku']["supplierId"]+0,
			'memo'=>'客户退货自动调货的记录，原始入库编号为'.$rk_init['Ruku']['rukuNum'],
			'Pro'=> $rk_one

		);
		//重新入库记录
		$rk_two[]=array(
				'id'=>$two_id,
				// 'ruku2ProId'=>$rk_init['ruku2ProId'],
				'chuku2proId'=>$chukuId,
				'productId' => $rk_init['productId'],
				"kuweiId"=>$_POST['kuweiId'],
				'pihao'   =>$rk_init['pihao'],
				'danjia'=>$rk_init['danjia'],
				'czDanjia'=>$_POST['czDanjia']+0,
				'money'=>$rk_init['danjia']*abs($_POST['cnt']),
				'cnt' => abs($_POST['cnt']) ,
				'isGuozhang'=>1,
				// 'zxDanjia' => $_POST['zxDanjia'],
				// 'memo' => ,
			);
		$arr_two=array(
			'id'=>$ruku_two,
			'kind'=>3,
			'rukuNum'=>$this->_modelRuku->getNewRukuNum(),
			'rukuDate'=>$_POST['chukuDate'],
			'supplierId'=>$rk_init['Ruku']["supplierId"]+0,
			'memo'=>'客户退货自动调货的记录，原始入库编号为'.$rk_init['Ruku']['rukuNum'],
			'Pro'=> $rk_two
		);
		// dump($arr_two);exit;
		
		$this->_modelRuku->save($arr_one);
		$rkId=$this->_modelRuku->save($arr_two);
		//获取id
		$rkId=$ruku_two>0?$ruku_two:$rkId;
		$sql="select id from cangku_ruku2product where rukuId='{$rkId}'";
		// dump($sql);exit;
		$row_rk=mysql_fetch_assoc(mysql_query($sql));
		//取最小的日期
		if($_POST['chukuId']>0){
			$date=strtotime($_POST['chukuDate'])>strtotime($rr['chukuDate'])?$rr['chukuDate']:$_POST['chukuDate'];
		}else{
			$date=$_POST['chukuDate'];
		}
		// dump($date);exit;
		$this->_modelRuku2Pro->changeKucun(array('id'=>$temp['ruku2proId'],'date'=>$date));
		$this->_modelRuku2Pro->changeKucun(array('id'=>$row_rk['id'],'date'=>$date));
		js_alert(null,'window.parent.parent.showMsg("操作成功");window.parent.location.href=window.parent.location.href;');
	}

	function actionTongji(){
		$this->authCheck('7-2');
		FLEA::loadClass('TMIS_Pager');
		$arr = TMIS_Pager::getParamArray(array(
			'dateFrom' =>date("Y-m-d",mktime(0,0,0,date("m")-1,date("d"),date("Y"))),
			'dateTo' => date("Y-m-d"),
			'clientId'=>'',
         	'kuweiId'=>'',
         	'guigeDesc'=>'',
         	'proName'=>'',
			// 'key'=>''
		));
		//sql语句，统计入库信息，按照供应商，规格，品名，统计
		$sql="select y.clientId,c.productId,c.kuweiId,z.guige,z.proName,a.compName,b.kuweiName from cangku_chuku2product x
			left join cangku_chuku y on y.id=x.chukuId
			left join cangku_ruku2product c on c.id=x.ruku2ProId
			left join jichu_product z on z.id=c.productId
			left join jichu_client a on a.id=y.clientId
			left join jichu_kuwei b on b.id=c.kuweiId
			where 1";
		if($arr['dateFrom']!='')$str.=" and y.chukuDate >= '{$arr['dateFrom']}'";
		if($arr['dateTo']!='')$str.=" and y.chukuDate <= '{$arr['dateTo']}'";
		$sql.=$str;
		if($arr['clientId']!='')$sql.=" and y.clientId='{$arr['clientId']}'";
		if($arr['kuweiId']!='')$sql.=" and x.kuweiId='{$arr['kuweiId']}'";
		if($arr['guigeDesc']!='')$sql.=" and z.guige like '%{$arr['guigeDesc']}%'";
		if($arr['proName']!='')$sql.=" and z.proName like '%{$arr['proName']}%'";
		$sql.=" group by y.clientId,x.productId,x.kuweiId order by y.clientId,x.productId,x.kuweiId";
		// echo $sql;exit;
		$pager =& new TMIS_Pager($sql);
		$rowset =$pager->findAll();

		foreach($rowset as & $v){
			//查找改供应商，规格，库位对应的入库数量
			$sql="select sum(x.cnt) as cnt from cangku_chuku2product x
				left join cangku_chuku y on y.id=x.chukuId
				left join cangku_ruku2product c on c.id=x.ruku2ProId
				where y.clientId='{$v['clientId']}' and c.kuweiId='{$v['kuweiId']}' and c.productId='{$v['productId']}' and y.kind<>1";
			$sql.=$str;
			// echo $sql;exit;
			$res=$this->_modelExample->findBySql($sql);
			$v['chukuCnt']=round($res[0]['cnt'],4);
			$v['chukuCnt2']=$v['chukuCnt'];
			//可以查看详细信息
			$v['chukuCnt']="<a href='".$this->_url('right',array(
				'clientId'=>$v['clientId']+0,
				'productId'=>$v['productId']+0,
				'kuweiId'=>$v['kuweiId']+0,
				'no_edit'=>1,
				'TB_iframe'=>1
			))."' class='thickbox' title='明细'>{$v['chukuCnt']}</a>";

			//退库数
			$sql="select sum(x.cnt) as cnt from cangku_chuku2product x
				left join cangku_chuku y on y.id=x.chukuId
				left join cangku_ruku2product c on c.id=x.ruku2ProId
				where y.clientId='{$v['clientId']}' and c.kuweiId='{$v['kuweiId']}' and c.productId='{$v['productId']}' and y.kind=1";
			$sql.=$str;
			$res=$this->_modelExample->findBySql($sql);
			$v['tuiCnt']=$res[0]['cnt']==0?'':abs(round($res[0]['cnt'],4));

			$v['cnt']=$v['chukuCnt2']-$v['tuiCnt'];
		}

		$heji=$this->getHeji($rowset,array('chukuCnt2','tuiCnt','cnt'),'compName');
		$heji['chukuCnt']=$heji['chukuCnt2'];
		$rowset[]=$heji;

		$fieldInfo=array(
			// '_edit'=>'操作',
			'compName'=>'客户',
			'guige'=>'规格',
			'proName'=>'品名',
			'kuweiName'=>'库位',
			'chukuCnt'=>'出库数',
			'tuiCnt'=>'退货数量',
			'cnt'=>'实际出库',
		);

		$smarty = & $this->_getView();
		$smarty->assign('title', '入库统计报表');
		$smarty->assign('arr_field_info', $fieldInfo);
		$smarty->assign('arr_field_value',$rowset);
		$smarty->assign('arr_condition', $arr);
		$smarty->assign('page_info',$pager->getNavBar($this->_url($_GET['action'],$arr)));
		$smarty->display('TableList.tpl');
	}
}
?>