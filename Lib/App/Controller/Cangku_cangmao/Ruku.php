<?php
FLEA::loadClass('TMIS_Controller');
class Controller_Cangku_Ruku extends Tmis_Controller {
	var $title = "半成品仓库管理-入库";
	var $_kind2=1;
	function Controller_Cangku_Ruku() {
		$this->_modelRuku = & FLEA::getSingleton('Model_Cangku_Ruku');
		$this->_modelExample = & FLEA::getSingleton('Model_Cangku_Ruku');
		$this->_modelRuku2Pro = & FLEA::getSingleton('Model_Cangku_RukuProduct');
		$this->_modelRuku->_head="BR";
		$this->_arrFieldInfo = array(
			'rukuNum' => '入库单号',
			'rukuDate' => '入库日期',
			'compName'=>'供应商',
			'proCode'=>'编码',
			'proName'=>'名称',
			'guige'=>'规格',
			'pihao'=>'批号',
			'cnt'=>array('text'=>'数量(吨)','width'=>80),
			'cntTui'=>array('text'=>'退/出纱','width'=>80),
			'cntSj'=>array('text'=>'实际吨数','width'=>80),
			'danjia'=>array('text'=>'吨价','width'=>80),
			'money'=>array('text'=>'金额','width'=>80),
			'kind'=>array('text'=>'入库类型','width'=>80),
			'kuweiName'=>"库位",
			// 'cntCi'=>'次品数',
			
			//'money'=>'金额',
			//'danjiaJG'=>'加工费',
			//'name'=>'车间名称',
			// 'memo'=>'备注',
			'_edit' => array('text'=>'操作','width'=>150),
		);
		$this->_fieldList=array(
			'proCode'=>array('type'=>'text','text'=>'编号' ,'hiddenName'=>'proId'),
			'proName'=>array('type'=>'text','text'=>'品名'),
			'guige'=>array('type'=>'text','text'=>'规格'),
			'pihao'=>array('type'=>'text','text'=>'批号'),
			'cnt'        =>array('type'=>'text','text'=>'数量(吨)'),
	        'danjia' =>array('type'=>'text','text'=>'吨价'),
	        'money' =>array('type'=>'text','text'=>'金额'),
	        'kuweiId' =>array('type'=>'select','text'=>'库位','conType'=>'TmisOptions','model'=>'Jichu_Kuwei','selected'=>'kuweiId'),
	        'czMoneyDate' =>array('type'=>'calendar','text'=>'仓租费开始日期'),
	        'czDanjia' =>array('type'=>'text','text'=>'仓租单价'),
	        'zxDanjia' =>array('type'=>'text','text'=>'装卸单价'),
	        'memo'  =>array('type'=>'text','text'=>'备注')    
		);
	}

	#入库登记
	function actionRight(){
		$this->authCheck('3-2');
		FLEA::loadClass('TMIS_Pager');
		$arrGet = TMIS_Pager::getParamArray(array(
			'dateFrom' =>date("Y-m-d",mktime(0,0,0,date("m")-1,date("d"),date("Y"))),
			'dateTo' => date("Y-m-d"),
			'supplierId'=>'',
         	'kuweiId'=>'',
         	'productId'=>'',
			'key'=>''
		));
		$str="select x.*,y.chuku2proId,y.cnt,y.danjia,y.WACP,z.proCode,z.proName,z.guige,z.unit,k.kuweiName ,y.pihao,y.money,y.id as rk2ProId,y.ruku2ProId,c.compName,y.guozhangId
			from cangku_ruku x
			left join cangku_ruku2product y on y.rukuId=x.id
			left join jichu_product z on z.id=y.productId
			left join jichu_kuwei k on k.id=y.kuweiId
			left join jichu_supplier c on c.id=x.supplierId
		    where 1";
		if($arrGet['dateFrom']!='')$str.=" and x.rukuDate>='$arrGet[dateFrom]'";
		if($arrGet['dateTo']!='')$str.=" and x.rukuDate<='$arrGet[dateTo]'";
		if($arrGet['kuweiId']!=''){
         	$str.=" and y.kuweiId='{$arrGet['kuweiId']}'";
         }
         if($arrGet['productId']!=''){
         	$str.=" and y.productId='{$arrGet['productId']}'";
         }
         if($arrGet['supplierId']!=''){
         	$str.=" and x.supplierId='{$arrGet['supplierId']}'";
         }
		if($arrGet['key']!='')$str.=" and(x.rukuNum like '%{$arrGet['key']}%' or z.proCode like '%{$arrGet['key']}%' or z.proName like '%{$arrGet['key']}%' or k.kuweiName like '%{$arrGet['key']}%')";
		// $str.=" and ";
		$str.=" order by rukuDate desc";
		// echo $str;exit;
		$pager =& new TMIS_Pager($str);
		$rowset =$pager->findAll();
		$rowset2=$this->_modelExample->findBySql($str);
		if($_REQUEST['isPrint']||$_REQUEST['isExport']){
			$rowset = $rowset2;
		}
		$mPro = & FLEA::getSingleton('Model_Jichu_Product');
		// dump($rowset);exit;
		if (count($rowset)>0) foreach($rowset as & $value) {
			//$value[name] = $value[Chejian][name];
			$arr = array();
			$value['cnt']=round($value['cnt'],2);
			$value['danjia']=round($value['danjia'],6);
			$value['cntCi']=round($value['cntCi'],2);
			$value['money']=$value['cnt']*$value['danjia'];

			$isTuisha=false;
			$value['kind2']=$value['kind'];
			if($value['kind']==0){
                  $value['kind'] = "正常入库";
			}elseif($value['kind']==1){
				$value['kind'] = "初始化";
				$value['_bgColor']="lightgreen";
			}elseif($value['kind']==9){ 
			}elseif($value['kind']==2){
				$isTuisha=true;
				$value['kind'] = "退纱";
				$value['_bgColor']="lightpink";
			}elseif($value['kind']==3){
				$value['kind'] = "调库";
				$value['_bgColor']="lightgray";
			}elseif($value['kind']==9){ 
				$value['kind'] = "其他";
			}

			
			
			$title = join("<br>",$arr);

			
			//退纱
			if($isTuisha==false){
				//修改
				$edit = "<a href='".$this->_url('Edit',array('id'=>$value['id']))."'>修改</a>";
				//退纱
				$tuisha=" | <a href='".$this->_url('Tuisha',array(
					'parentId'=>$value['rk2ProId'],
					'TB_iframe'=>1
				))."' class='thickbox' title='退纱编辑'>退纱</a>";
				//如果该记录是其他入库记录的附属记录，则不能退纱。
				if($value['ruku2ProId']>0){
					$temp=$this->_modelRuku2Pro->find($value['ruku2ProId']);
					$tuisha=" | <span title='此记录为{$temp['Ruku']['rukuNum']}的调出记录,不能退纱操作'>退纱</span>";
				}
			}else{
				$edit="<a href='".$this->_url('Tuisha',array(
					'id'=>$value['rk2ProId'],
					'TB_iframe'=>1
				))."' class='thickbox' title='退纱编辑'>修改</a>";
				$tuisha=" | <span title='此记录为{$value['kind']}记录,不能退纱操作'>退纱</span>";
			}

			$remove= " | <a href='".$this->_url('Remove',array('id'=>$value['id']))."' onclick=\"return confirm('入库单号".$value['rukuNum']."记录将全部删除，是否确认?')\">删除</a>";
			//查找是否过账，如果已过帐
			if($value['guozhangId']>0){
				$edit="<span title='已过帐，禁止操作'>修改</span>";
				$remove=" | 删除";
			}

			if($value['chuku2proId']>0){
				$sql="select y.chukuNum from cangku_chuku2product x
					left join cangku_chuku y on y.id=x.chukuId
					where x.id='{$value['chuku2proId']}'";
					// echo $sql;exit;
				$res=$this->_modelExample->findBySql($sql);
				$edit="<span title='客户退货自动生成记录，修改删除操作请到出库查询界面操作，出库单号：{$res[0]['chukuNum']}'>修改</span>";
				$remove=" | 删除";
			}

			$value['_edit']=$edit.$remove.$tuisha;

			//查找对应的退纱信息
			$sql="select sum(cnt) as cntTui from cangku_ruku2product where ruku2ProId='{$value['rk2ProId']}'";
			$res=mysql_fetch_assoc(mysql_query($sql));
			// $value['cntTui']=$res['cntTui'];
			$value['cntTui']=$res['cntTui']==0?'':round(abs($res['cntTui']),4);
			if($value['kind2']==0)$value['cntSj']=$value['cnt']-$value['cntTui'];
		}
//	    $sql="
//			select sum(cnt)  as cnt ,
//				  sum(cntCi) as cntCi
//			from  cangku_ruku2product  where 1
//		";
		//$zongji = mysql_fetch_assoc(mysql_query($sql));
		$zongji=$this->getHeji($rowset2,array('cnt','money','cntTui'));
	    $zongji['rukuNum'] = '总计';
		$heji=$this->getHeji($rowset,array('cnt','money','cntTui','cntSj'),'rukuNum');
		$rowset[]=$heji;
		// $rowset[]=$zongji;
		//dump($rowset);
		// $arrFieldInfo = array(
		// 	'rukuNum' => '入库单号',
		// 	'rukuDate' => '入库日期',
		// 	'proCode'=>'编码',
		// 	'proName'=>'名称',
		// 	'guige'=>'规格',
		// 	'unit'=>'单位',
		// 	'cnt'=>'数量',
		// 	'length'=>"长度",
		// 	'rukuType'=>"入库类型",
		// 	'kuweiName'=>"库位",
		// 	// 'cntCi'=>'次品数',
		// 	'danjia'=>'单价',
		// 	//'money'=>'金额',
		// 	//'danjiaJG'=>'加工费',
		// 	//'name'=>'车间名称',
		// 	'memo'=>'备注',
		// 	'_edit' => '操作'
		// );
		if($_REQUEST['isExport']){
			unset($arrFieldInfo["_edit"]);
			header("Content-type:application/vnd.ms-excel");
			header("Content-Disposition:attachment;filename=xsfx.xls");
		}
		// echo "javascript:window.open('".$this->_url("Right",array("isPrint"=>1))."')";exit;
		$smarty = & $this->_getView();
		$smarty->assign('title', '物料入库查询');
		$smarty->assign('pk', $this->_modelRuku->primaryKey);
		$smarty->assign('arr_field_info', $this->_arrFieldInfo);
		$smarty->assign('arr_field_value',$rowset);
		$smarty->assign('arr_condition', $arrGet);
		$smarty->assign('arr_js_css',$this->makeArrayJsCss(array('tip','calendar')));
		$smarty->assign('page_info',$pager->getNavBar($this->_url('right',$arrGet))."<font color='red'>红色表示退纱</font><font color='green'>绿色表示初始化库存</font><font color='gray'>灰色表示客户退库自动生成的调库记录</font>");
		// $smarty->assign('fn_export',$this->_url("right",array("isExport"=>1)));
		$smarty->assign('print_href',"javascript:window.open(\"".$this->_url("Right",array("isPrint"=>1))."\");javascript:void(0)");
		if($_REQUEST['isPrint']){
			$smarty->display("print.tpl");
		}elseif($_REQUEST['isExport']){
			
			$smarty->display('Export2Excel.tpl');
		}else{
			$smarty->display('TableList.tpl');
		}
	}

	// 查看详细
	function actionView() {
		$pk=$this->_modelRuku->primaryKey;
		$rowset=$this->_modelRuku->find($_GET[$pk]);
		//dump($rowset);
		$mPro = & FLEA::getSingleton('Model_Jichu_Product');
		if (count($rowset)>0) if (count($rowset[Pro])>0) {
			foreach($rowset[Pro] as & $value) {
				//dump($value);exit;
				$row = $mPro->findByField('id', $value[productId]);
				//$row['Pro'
				$value['proCode'] = $row['proCode'];
				$value['proName'] = $row['proName'];
				$value['guige'] = $row['guige'];
				$value['color'] = $row['color'];
				$value['unit'] = $row['unit'];
				if ($value['money'] == 0)	$value['money'] = $value['cnt'] * $value['danjia'];
				$totalCnt += $value['cnt'];
				$totalCi += $value['cntCi'];
				$totalMoney += $value['money'];
				$taihao .= $value['memo']." / ";
				$value['cnt']=round($value['cnt']
					,2);
				$value['cntCi']=round($value['cntCi'],2);
			}

		}
		$smarty = & $this->_getView();
		$smarty->assign("arr_field_value",$rowset);
		$smarty->assign("total_cnt", $totalCnt);
		$smarty->assign("totalCi", $totalCi);
		$smarty->assign("total_money", $totalMoney);
		$smarty->display('Cangku/RukuView.tpl');
	}

	function actionAdd() {
		$this->authCheck('3-1');
		//dump($_GET);exit;
		$this->_edit(array(rukuNum=>$this->_modelRuku->getNewRukuNum(),kind=>$_GET[kind],kind2=>$this->_kind2,'Pro'=>array(array())));
	}

	function actionEdit() {
		$pk=$this->_modelRuku->primaryKey;
		$arr=$this->_modelRuku->find($_GET[$pk]);
		// dump($arr); exit;
		if (count($arr['Pro'])>0) foreach($arr['Pro'] as & $v){
			$mPro = & FLEA::getSingleton('Model_Jichu_Product');
			$row = $mPro->find($v[productId]);
			$v['Product'] = $row;
			$v[proName]	= $row[proName];
			$v[guige]		= $row[guige];
			$v[color]		= $row[color];
			$v['cnt']=round($v['cnt'],4);
			$v['danjia']=round($v['danjia'],6);
			$v['czDanjia']=round($v['czDanjia'],6);
			$v['zxDanjia']=round($v['zxDanjia'],6);
			$v['proCode'] = $v['Product']['proCode'];
			$v['guige'] = $v['Product']['guige'];
            $v['proId'] =  $v['Product']['id'];
            $v['unit']   = $v['Product']['unit'];
		}
		// dump($arr['Pro']);exit;
		$this->_edit($arr);
	}

	function actionSave() {		
		// dump($_POST);exit;
		if($_POST['rukuId']!='') {
			$rr=$this->_modelExample->find(array('id'=>$_POST['rukuId']));			
			if(!$rr) {
				js_alert('入库单不存在，请确认！',null,$this->_url('right'));exit;
			}			
		}
		for ($i=0;$i<count($_POST['proId']);$i++){
			if(empty($_POST['proId'][$i])) continue;
			$arr[] = array(
				'id' => $_POST['id'][$i],
				'productId' => $_POST['proId'][$i],
				"kuweiId"=>$_POST['kuweiId'][$i],
				// 'colorId' =>$_POST['colorId'][$i],
				// 'length'  =>$_POST['length'][$i]+0,
				'pihao'   =>$_POST['pihao'][$i],
				'danjia'=>$_POST['danjia'][$i],
				'money'=>$_POST['money'][$i],
				'cnt' => $_POST['cnt'][$i]+0 ,
				'czMoneyDate' => $_POST['czMoneyDate'][$i],
				'czDanjia' => $_POST['czDanjia'][$i],
				'zxDanjia' => $_POST['zxDanjia'][$i],
				'memo' => $_POST['memo'][$i],
				'isGuozhang' => $_POST['kind']==1?1:0,
				// 'ifRemove' => $_POST['ifRemove'][$i]
			);
		}
        // dump($arr);exit;
		if(count($arr)==0 && $_POST['rukuId']>0) {
			$this->_modelRuku->removeByPkv($_POST['rukuId']);
		} else {
			//需要保存的信息
			$row=array(
				'id'=>$_POST['rukuId'],
				// 'kind2'      =>$this->_kind2,
				// 'rukuType'=>$_POST['rukuType'],
				'supplierId'=>$_POST["supplierId"]+0,
				'rukuNum'=>$_POST['rukuNum'],
				'rukuDate'=>empty($_POST['rukuDate'])?date("Y-m-d"):$_POST['rukuDate'],
				'kind'=>$_POST['kind']+0,
				'memo'=>$_POST['rukuMemo'],
				// 'kuweiId'=>$_POST["kuweiId"],
				'Pro'=> $arr
			);

			//判断是否需要改变库存信息
			//删除，修改（日期，数量），新增都需要改变库存
			// dump($rr);exit;
			//修改情况下，修改日期，所有的库存都需要改变
			if($rr['rukuDate']!=$row['rukuDate'] && $_POST['rukuId']>0){
				$isAll=true;
			}else if($rr['rukuDate']==$row['rukuDate'] && $_POST['rukuId']>0){
				//查找原来的入库信息，与现在的对比，查看是否需要改变库存信息
				// dump($rr);exit;
				foreach($rr['Pro'] as & $v){
					foreach($arr as & $vv){
						if(!$vv['id'])continue;
						if($v['id']==$vv['id'] && $v['cnt']==$vv['cnt']){
							$ruku2proId[$v['id']]=$v['id'];
						}
					}
				}
			}
			// dump($arr);exit;
			$rukuId = $this->_modelRuku->save($row);
			$rukuId=$_POST['rukuId']>0?$_POST['rukuId']:$rukuId;
		}

		//区分新增与修改的明细id,进行changeKucun使用
		//修改的id
		// dump($ruku2proId);exit;
		$ruku2proId_str=join(',',$ruku2proId);
		//如果日期修改了，则所有的库存都需要改变，制空id
		if($isAll)$ruku2proId_str='';
		//查找需要修改的id
		$sql="select id from cangku_ruku2product where rukuId='{$rukuId}'";
		if($ruku2proId_str!='')$sql.=" and id not in({$ruku2proId_str})";
		//修改的情况，没有修改日期，没有修改数量，则不需要修改库存信息
		// if(!$isAll && $ruku2proId_str=='' && $_POST['rukuId']>0)$sql.=" and 0";
		// echo $sql;exit;
		//新增的id
		$kucun_ord2proId=$this->_modelExample->findBySql($sql);
		// dump($kucun_ord2proId);exit;
		//循环改变库存
		//取最小的日期
		if($_POST['rukuId']>0){
			$date=strtotime($row['rukuDate'])>strtotime($rr['rukuDate'])?$rr['rukuDate']:$row['rukuDate'];
		}else{
			$date=$row['rukuDate'];
		}
		foreach($kucun_ord2proId as & $v){
			$this->_modelRuku2Pro->changeKucun(array('id'=>$v['id'],'date'=>$date));
		}

		

		if($_POST['Submit']=='保存') {
			if(!$_POST['from']) redirect($this->_url('right'));
			else redirect($this->_url($_POST['from']));
		} else {
			if($_POST['from']=='right1') redirect($this->_url('add1'));
			else redirect($this->_url('add'));
		}
	}

	//入库中删除通过ajax实现
	function actionRemoveByAjax(){
		$success=true;
		//判断是否存在出库
		$str="select count(*) as cnt from cangku_chuku2product where ruku2proId='{$_GET['id']}'";
		$res=mysql_fetch_assoc(mysql_query($str));
		if($res['cnt']>0){
			$success=false;
			$msg="已存在出库信息，禁止删除！";
		}
		//判断是否已审核过账
		if($success){
			$str="select count(*) as cnt from caiwu_yf_guozhang where ruku2proId='{$_GET['id']}'";
			$res=mysql_fetch_assoc(mysql_query($str));
			if($res['cnt']>0){
				$success=false;
				$msg="已审核过账信息，禁止删除！";
			}
		}
		//判断是否已审核过账
		if($success){
			$str="select count(*) as cnt from cangku_ruku2product where rukuId=(select rukuId from cangku_ruku2product where id='{$_GET['id']}')";
			$res=mysql_fetch_assoc(mysql_query($str));
			if($res['cnt']<2){
				$success=false;
				$msg="入库明细至少保留一条数据，如果确认删除，请到查询界面删除";
			}
		}
		//删除
		if($success){
			$sql="delete from cangku_ruku2product where id='{$_GET['id']}'";
			mysql_query($sql);
			$this->_modelRuku2Pro->changeKucun(array('id'=>$_GET['id'],'type'=>'remove'));
		}
		echo json_encode(array('success'=>$success,'msg'=>$msg));
	}


	function actionRemove() {
		//改变库存
		$ruku = $this->_modelExample->find(array('id'=>$_GET['id']));
		//dump($ruku);exit;
		if ($this->_modelExample->removeByPkv($_GET[id])) {
			if($ruku['Pro']) foreach($ruku['Pro'] as & $v) {
				$this->_modelExample->changeKucun($v['productId']);
			}
		}

		if($_GET['from']=='') redirect($this->_url("right"));
		else redirect($this->_url($_GET['from']));
	}


	// 编辑订单基本信息
	function _edit($Arr) {
		//dump($Arr);exit;
		// dump($this->_fieldList);exit;
		$smarty = & $this->_getView();
		$smarty->assign('title', '成品入库登记');
		$smarty->assign('user_id', $_SESSION['USERID']);
		$smarty->assign('real_name', $_SESSION['REALNAME']);
		$smarty->assign("arr_field_info",$this->_fieldList);
		$smarty->assign("arr_field_value",$Arr);
		$smarty->assign('default_date',date("Y-m-d"));
		$pk = $this->_modelRuku->primaryKey;
		$primary_key = (isset($_GET[$pk])?$pk:"");
		$smarty->assign("pk",$primary_key);
		$smarty->display('Cangku/RukuEdit.tpl');
	}


	//退纱编辑
	function actionTuisha(){
		if($_GET['parentId']>0){
			$arr=$this->_modelRuku2Pro->find($_GET['parentId']);
			$row=array(
				'kind'=>2,
				'ruku2ProId'=>$arr['id'],
				'cnt2'=>$arr['cnt'],
				'kuweiId'=>$arr['kuweiId'],
				'productId'=>$arr['productId'],
				'pihao'=>$arr['pihao'],
				'proCode'=>$arr['Products']['proCode'],
				'proName'=>$arr['Products']['proName'],
				'guige'=>$arr['Products']['guige'],
				'zxDanjia'=>$arr['zxDanjia'],
				'rukuNum'=>$arr['Ruku']['rukuNum'],
				'rukuDate2'=>$arr['Ruku']['rukuDate'],
				'supplierId'=>$arr['Ruku']['supplierId'],
			);
		}elseif($_GET['id']>0){
			$row=$this->_modelRuku2Pro->find($_GET['id']);
			$arr=$this->_modelRuku2Pro->find($row['ruku2ProId']);
			// dump($arr);exit;

			$row['rukuDate2']=$arr['Ruku']['rukuDate'];
			$row['cnt2']=$arr['cnt'];
			$row['rukuNum']=$arr['Ruku']['rukuNum'];
			$row['supplierId']=$arr['Ruku']['supplierId'];
			$row['kuweiId']=$arr['kuweiId'];
			$row['proCode']=$arr['Products']['proCode'];
			$row['proName']=$arr['Products']['proName'];
			$row['guige']=$arr['Products']['guige'];

			$row['kind']=$row['Ruku']['kind'];
			$row['rukuDate']=$row['Ruku']['rukuDate'];
			$row['guige']=$row['Products']['guige'];
		}
		// dump($row);exit;
		$smarty = & $this->_getView();
		$smarty->assign('title', '退纱编辑');
		$smarty->assign('aRow', $row);
		$smarty->display('Cangku/TuishaEdit.tpl');
	}

	//保存退纱记录
	function actionSaveTuisha(){
		// dump($_POST);exit;
		$temp=$this->_modelRuku2Pro->find($_POST['ruku2ProId']);
		if($_POST['rukuId']>0){
			$rr=$this->_modelExample->find($_POST['rukuId']);
		}
		$son[]=array(
				'id'=>$_POST['id'],
				'ruku2ProId'=>$_POST['ruku2ProId'],
				'productId' => $temp['productId'],
				"kuweiId"=>$temp['kuweiId'],
				'pihao'   =>$temp['pihao'],
				'danjia'=>$temp['danjia'],
				'money'=>$temp['danjia']*(0-abs($_POST['cnt'])),
				'cnt' => 0-abs($_POST['cnt']) ,
				'zxDanjia' => $_POST['zxDanjia'],
				'memo' => $_POST['memo'],
			);
		$arr=array(
			'id'=>$_POST['rukuId'],
			'kind'=>$_POST['kind'],
			'rukuNum'=>$this->_modelRuku->getNewRukuNum(),
			'rukuDate'=>$_POST['rukuDate'],
			'supplierId'=>$temp['Ruku']["supplierId"]+0,
			'memo'=>'退纱操作',
			'Pro'=> $son

		);
		// dump($arr);exit;
		$rukuId = $this->_modelRuku->save($arr);
		//取最小的日期
		if($_POST['rukuId']>0){
			$date=strtotime($_POST['rukuDate'])>strtotime($rr['rukuDate'])?$rr['rukuDate']:$_POST['rukuDate'];
		}else{
			$date=$row['rukuDate'];
		}

		$this->_modelRuku2Pro->changeKucun(array('id'=>$_POST['ruku2ProId'],'date'=>$date));
		js_alert(null,'window.parent.parent.showMsg("操作成功");window.parent.location.href=window.parent.location.href;');
	}

	//领料出库登记列表
	function actionListForCk(){
		$title = '辅料入库列表';
		$tpl = 'Popup/MultSel.tpl';
		FLEA::loadClass('TMIS_Pager');
		$arr = TMIS_Pager::getParamArray(array(
			'supplierId'=>'',
			// 'kuanhao'=>'',
			'pihao'=>'',
			'proName'=>'',
			'guigeDesc'=>'',
		));
		$condition=array();
		$sql="select x.*,a.compName as supplierName,y.rukuDate,y.supplierId,z.proName,z.guige,k.kuweiName,y.rukuNum from cangku_ruku2product x
			left join cangku_ruku y on y.id=x.rukuId
			left join jichu_product z on z.id=x.productId
			left join jichu_supplier a on a.id=y.supplierId
			left join jichu_kuwei k on k.id=x.kuweiId
			where 1 and y.kind<>2 and x.ruku2proId=0";
		if($arr['pihao']!='')$sql.=" and x.pihao like '%{$arr['pihao']}%'";
		if($arr['proName']!='')$sql.=" and z.proName like '%{$arr['proName']}%'";
		if($arr['guigeDesc']!='')$sql.=" and z.guige like '%{$arr['guigeDesc']}%'";
		if($arr['supplierId']!='')$sql.=" and y.supplierId = '{$arr['supplierId']}'";
		$sql.=" order by y.rukuDate desc,x.id";
		// dump($sql);exit;
		$pager =& new TMIS_Pager($sql);
		$rows =$pager->findAll();

		foreach($rows as $key => & $v) {
			//查找已退库
			$sql="select sum(cnt) as cnt from cangku_ruku2product where ruku2ProId='{$v['id']}'";
			$temp=mysql_fetch_assoc(mysql_query($sql));
			$v['cnt']-=abs($temp['cnt']);

			//查找已领料
			$sql="select sum(cnt) as cnt from cangku_chuku2product where ruku2ProId='{$v['id']}'";
			$temp=mysql_fetch_assoc(mysql_query($sql));
			$v['llckCnt']=$temp['cnt']==0?'':round($temp['cnt'],3);
			$v['kucunCnt']=$v['cnt']-$v['llckCnt'];
			
			
			//如果是系统初始化，没有货号信息
			if($v['kind']==1)$v['huohao']="仓库初始化";
		}
		// dump($rows);exit;
		$smarty = & $this->_getView();
		$smarty->assign('title', $title);
		$smarty->assign('arr_field_info',array(
			// "huohao" =>"货号",
			// 'caigouCode'=>'采购单号',
			'supplierName'=>'供应商',
			"rukuDate" =>"入库日期",
			"rukuNum" =>"入库单号",
			'pihao'=>'批号',
			'proName'=>'品名',
			'guige'=>'规格',
			// 'color'=>'颜色',
			'cnt'=>'入库数',
			'llckCnt'=>'已领料',
			'kucunCnt'=>'库存',
			'kuweiName'=>'库位',
			// 'danjia'=>'单价',
		));
		$smarty->assign('arr_field_value',$rows);
		$smarty->assign('arr_condition', $arr);
		$smarty->assign('add_display', 'none');
		// $smarty->assign('arr_js_css',$this->makeArrayJsCss(array('calendar')));
		$smarty->display($tpl);
	}

	//入库统计报表
	function actionTongji(){
		$this->authCheck('7-1');
		FLEA::loadClass('TMIS_Pager');
		$arr = TMIS_Pager::getParamArray(array(
			'dateFrom' =>date("Y-m-d",mktime(0,0,0,date("m")-1,date("d"),date("Y"))),
			'dateTo' => date("Y-m-d"),
			'supplierId'=>'',
         	'kuweiId'=>'',
         	'guigeDesc'=>'',
         	'proName'=>'',
			// 'key'=>''
		));
		//sql语句，统计入库信息，按照供应商，规格，品名，统计
		$sql="select y.supplierId,x.productId,x.kuweiId,z.guige,z.proName,a.compName,b.kuweiName from cangku_ruku2product x
			left join cangku_ruku y on y.id=x.rukuId
			left join jichu_product z on z.id=x.productId
			left join jichu_supplier a on a.id=y.supplierId
			left join jichu_kuwei b on b.id=x.kuweiId
			where 1";
		if($arr['dateFrom']!='')$str.=" and y.rukuDate >= '{$arr['dateFrom']}'";
		if($arr['dateTo']!='')$str.=" and y.rukuDate <= '{$arr['dateTo']}'";
		$sql.=$str;
		if($arr['supplierId']!='')$sql.=" and y.supplierId='{$arr['supplierId']}'";
		if($arr['kuweiId']!='')$sql.=" and x.kuweiId='{$arr['kuweiId']}'";
		if($arr['guigeDesc']!='')$sql.=" and z.guige like '%{$arr['guigeDesc']}%'";
		if($arr['proName']!='')$sql.=" and z.proName like '%{$arr['proName']}%'";
		$sql.=" group by y.supplierId,x.productId,x.kuweiId order by y.supplierId,x.productId,x.kuweiId";
		$pager =& new TMIS_Pager($sql);
		$rowset =$pager->findAll();

		foreach($rowset as & $v){
			//查找改供应商，规格，库位对应的入库数量
			$sql="select sum(x.cnt) as cnt from cangku_ruku2product x
				left join cangku_ruku y on y.id=x.rukuId
				where y.supplierId='{$v['supplierId']}' and x.kuweiId='{$v['kuweiId']}' and x.productId='{$v['productId']}' and y.kind<>2";
			$sql.=$str;
			$res=$this->_modelExample->findBySql($sql);
			$v['rukuCnt']=round($res[0]['cnt'],4);
			$v['rukuCnt2']=$v['rukuCnt'];
			//可以查看详细信息
			$v['rukuCnt']="<a href='".$this->_url('right',array(
				'supplierId'=>$v['supplierId']+0,
				'productId'=>$v['productId']+0,
				'kuweiId'=>$v['kuweiId']+0,
				'no_edit'=>1,
				'TB_iframe'=>1
			))."' class='thickbox' title='明细'>{$v['rukuCnt']}</a>";

			//退库数
			$sql="select sum(x.cnt) as cnt from cangku_ruku2product x
				left join cangku_ruku y on y.id=x.rukuId
				where y.supplierId='{$v['supplierId']}' and x.kuweiId='{$v['kuweiId']}' and x.productId='{$v['productId']}' and y.kind=2";
			$sql.=$str;
			$res=$this->_modelExample->findBySql($sql);
			$v['tuiCnt']=$res[0]['cnt']==0?'':abs(round($res[0]['cnt'],4));

			$v['cnt']=$v['rukuCnt2']-$v['tuiCnt'];
		}

		$heji=$this->getHeji($rowset,array('rukuCnt2','tuiCnt','cnt'),'compName');
		$heji['rukuCnt']=$heji['rukuCnt2'];
		$rowset[]=$heji;

		$fieldInfo=array(
			// '_edit'=>'操作',
			'compName'=>'供应商',
			'guige'=>'规格',
			'proName'=>'品名',
			'kuweiName'=>'库位',
			'rukuCnt'=>'入库数',
			'tuiCnt'=>'退纱数量',
			'cnt'=>'实际入库',
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