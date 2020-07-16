<?php
FLEA::loadClass('Tmis_Controller');
class Controller_Caiwu_Yf_Guozhang extends Tmis_Controller {
	var $_tplEdit = 'Caiwu/Yf/GuozhangEdit.tpl';
    function __construct() {
        $this->_modelExample = & FLEA::getSingleton('Model_Caiwu_Yf_Guozhang');
        $this->_modelRuku2Pro = & FLEA::getSingleton('Model_Cangku_Ruku2Product');

        //搭建过账界面
        $this->fldMain = array(
			'guozhangDate' => array('title' => '过账日期', "type" => "calendar", 'value' => date('Y-m-d')),
			// 'btnRukuYuanliao' => array('title' => '选择入库', 'type' => 'BtnRukuYuanliao', 'value' => ''),
			//如果使用popup控件，url,jsTpl,onSelFunc3个属性必须指定
			'ruku2ProId' => array(
				'title' => '选择入库', 
				'type' => 'popup', 
				'value' => '',
				'name'=>'ruku2ProId',
				'text'=>'选择入库',
				'url'=>url('Cangku_Yuanliao_Ruku','PopupOnGuozhang'),//弹出地址,回调函数在sonTpl中进行定义,主要是对缓存中的关联数组进行重写
				// 'url'=>url('jichu_client','popup'),//弹出地址
				//'jsTpl'=>'Caiwu/Yf/jsGuozhang.tpl',//需要载入的模板文件，这个文件中不需要使用{literal},因为是通过file_get_content获得的
				//'onSelFunc'=>'onSelRuku',//选中后需要执行的回调函数名,需要在jsTpl中书写
				'textFld'=>'rukuCode',//显示在text中的字段
				'hiddenFld'=>'id',//显示在hidden控件中的字段
			),
			// 'ruku2ProId1' => array(
			// 	'title' => '选择入库1', 
			// 	'type' => 'popup', 
			// 	'value' => '',
			// 	'name'=>'ruku2ProId1',
			// 	'text'=>'选择入库1',
			// 	'url'=>url('Cangku_Yuanliao_Ruku','popup'),//弹出地址,回调函数在sonTpl中进行定义,主要是对缓存中的关联数组进行重写
			// 	// 'url'=>url('jichu_client','popup'),//弹出地址
			// 	//'jsTpl'=>'Caiwu/Yf/jsGuozhang.tpl',//需要载入的模板文件，这个文件中不需要使用{literal},因为是通过file_get_content获得的
			// 	//'onSelFunc'=>'onSelRuku',//选中后需要执行的回调函数名,需要在jsTpl中书写
			// 	'textFld'=>'rukuCode',//显示在text中的字段
			// 	'hiddenFld'=>'id',//显示在hidden控件中的字段
			// ),
			
			'supplierId' => array('title' => '供应商', 'type' => 'selectgys', 'value' => '','model'=>'Model_Jichu_Supplier'),
			'qitaMemo' => array('title' => '描述', 'type' => 'text', 'value' => '','readonly'=>true),
			'rukuDate' => array('title' => '入库日期', 'type' => 'text', 'value' => '','readonly'=>true),
			'cnt' => array('title' => '数量', 'type' => 'text', 'value' => '','readonly'=>true),
			'danjia' => array('title' => '单价', 'type' => 'text', 'value' => ''),
			'_money' => array('title' => '金额', 'type' => 'text', 'value' => '','readonly'=>true,'addonEnd'=>'元'),
			'zhekouMoney' => array('title' => '折扣金额', 'type' => 'text', 'value' => '','addonEnd'=>'元'),
			'money' => array('title' => '入账金额', 'type' => 'text', 'value' => '','addonEnd'=>'元'),
			'memo' => array('title' => '备注', 'type' => 'textarea', 'value' => ''),
			'id' => array('type' => 'hidden', 'value' => ''),
			'creater' => array('type' => 'hidden', 'value' => $_SESSION['REALNAME']),
			'kind' => array('type' => 'hidden', 'value' => ''),
			'productId' => array('type' => 'hidden', 'value' => ''),
			'rukuId' => array('type' => 'hidden', 'value' => ''),
		);

		// 表单元素的验证规则定义
		$this->rules = array(
			'guozhangDate' => 'required',
			'supplierId' => 'required',
			'money' => 'required number',
			'zhekouMoney' => 'number'
		);
    }

	function actionRight() {
		// $this->authCheck('4-1-2');
		$title = '应付款查询';
		$tpl = 'TblList.tpl';		
		FLEA::loadClass('TMIS_Pager');
		$arr = TMIS_Pager::getParamArray(array(
			'dateFrom'=>date('Y-m-d',mktime(0,0,0,date('m')-1,date('d'),date('Y'))),
			'dateTo'=>date('Y-m-d'),
			'supplierId'=>'',
			// 'orderCode'=>'',
			// 'product'=>'',
			// 'guige'=>'',
			// 'orderId'=>'',
			'no_edit'=>'',
		));
		/*$sql="select x.*,z.compName,a.guige as guigeDesc,a.proName from caiwu_yf_guozhang x 
			left join jichu_product a on a.id=x.productId
			left join jichu_supplier z on z.id=x.supplierId
			where 1 and x.kind not in('后整理加工','织造加工','加工过账','其它过账')";*/

		$sql="select x.*,z.compName,a.guige as guigeDesc,a.proName from caiwu_yf_guozhang x 
					left join jichu_product a on a.id=x.productId
					left join jichu_supplier z on z.id=x.supplierId
					where 1 and x.kind not in('后整理加工','织造加工','加工过账','其它过账','生产入库')";
		if($arr['orderId']>0){
			$arr['dateFrom']='';
			$arr['dateTo']='';
			$sql.=" and x.orderId='{$arr['orderId']}'";
		}
		if($arr['dateFrom']!='')$sql.=" and x.guozhangDate >='{$arr['dateFrom']}' and x.guozhangDate<='{$arr['dateTo']}'";
		if($arr['supplierId']!='')$sql.=" and x.supplierId='{$arr['supplierId']}'";
		if($arr['orderCode']!='')$sql.=" and y.orderCode like '%{$arr['orderCode']}%'";
		if($arr['product']!='')$sql.=" and a.product like '%{$arr['product']}%'";
		if($arr['guige']!='')$sql.=" and a.guige like '%{$arr['guige']}%'";
		//$sql.=" order by x.id desc";
		$sql.=" order by x.guozhangDate desc";
		// $sql.=" order by x.rukuDate desc";
		
// 		dump($sql);exit;
		$pager =& new TMIS_Pager($sql);
		$rowset =$pager->findAll();
// dump($rowset);die;
		foreach($rowset as & $v) {
			$v['_edit']= "<a href='".$this->_url('Edit',array(
				'id'=>$v['id'],
			))."' title='过账编辑'>修改</a>". '&nbsp;&nbsp;' . $this->getRemoveHtml($v['id']);

			//核销的情况下不能修改删除
			if($v['yifukuan']>0 || $v['yishouPiao']>0)$v['_edit']="<span title='已核销，禁止操作'>修改&nbsp;&nbsp;删除</span>";
		}
		$rowset[] = $this->getHeji($rowset, array('cnt','money'), $_GET['no_edit']==1?'compName':'_edit');
		// dump($rowset);exit;
		$arrField = array(
			"_edit"=>'操作',
			"compName"=>'供应商',
			"guozhangDate"=>'过账日期',
			// "kind"=>'类别',
			// "rukuNum"=>'入库编号',
			"rukuDate"=>'入库日期',
			// "proName"=>'品名',
			// "guigeDesc"=>'规格',
			"qitaMemo"=>array('text'=>'描述','width'=>70),
			// "unit"=>array('text'=>'单位','width'=>70),
			"cnt"=>array('text'=>'数量','width'=>70),
			"danjia"=>array('text'=>'单价','width'=>70),
			"_money"=>array('text'=>'金额','width'=>70),
			"zhekouMoney"=>array('text'=>'折扣金额','width'=>70),
			"money"=>array('text'=>'应付金额','width'=>70),
			// "huilv"=>array('text'=>'汇率','width'=>70),
			"memo"=>"备注",
			"creater"=>"制单人",
		);

		$smarty = & $this->_getView();
		$smarty->assign('title', $title);
		$smarty->assign('arr_field_info',$arrField);
		$smarty->assign('arr_field_value',$rowset);
		$smarty->assign('arr_condition', $arr);
		$smarty->assign('add_display', 'none');
		$smarty->assign('page_info',$pager->getNavBar($this->_url($_GET['action'],$arr)));
		$smarty->display($tpl);
	}

	function actionRightYif() {
		// $this->authCheck('4-1-2');
		$title = '应付款查询';
		$tpl = 'TblList.tpl';		
		FLEA::loadClass('TMIS_Pager');
		$arr = TMIS_Pager::getParamArray(array(
			// 'dateFrom'=>date('Y-m-d',mktime(0,0,0,date('m')-1,date('d'),date('Y'))),
			// 'dateTo'=>date('Y-m-d'),
			'supplierId'=>'',
			'orderCode'=>'',
			'product'=>'',
			'guige'=>'',
			'orderId'=>'',
			'no_edit'=>'',
		));
		$sql="select x.*,z.compName,y.orderCode from caiwu_yf_guozhang x 
			left join trade_order y on x.orderId=y.id
			left join trade_order2product a on a.id=x.ord2proId
			left join jichu_jiagonghu z on z.id=x.supplierId
			where 1";

		if($arr['orderId']>0){
			$arr['dateFrom']='';
			$arr['dateTo']='';
			$sql.=" and x.orderId='{$arr['orderId']}'";
		}
		if($arr['dateFrom']!='')$sql.=" and x.guozhangDate >='{$arr['dateFrom']}' and x.guozhangDate<='{$arr['dateTo']}'";
		if($arr['supplierId']!='')$sql.=" and x.supplierId='{$arr['supplierId']}'";
		if($arr['orderCode']!='')$sql.=" and y.orderCode like '%{$arr['orderCode']}%'";
		if($arr['product']!='')$sql.=" and a.product like '%{$arr['product']}%'";
		if($arr['guige']!='')$sql.=" and a.guige like '%{$arr['guige']}%'";
		$sql.=" order by x.id asc";
		// dump($sql);exit;
		$pager =& new TMIS_Pager($sql);
		$rowset =$pager->findAll();
		
		$rowset[] = $this->getHeji($rowset, array('yifukuan','money'), $_GET['no_edit']==1?'compName':'_edit');
		// dump($rowset);exit;
		$arrField = array(
			// "_edit"=>'操作',
			"compName"=>'加工供应商',
			"guozhangDate"=>'日期',
			"kind"=>'类别',
			"orderCode"=>'生产编号',
			"product"=>$this->getManuCodeName(),
			"guige"=>'规格',
			// "unit"=>array('text'=>'单位','width'=>70),
			// "cnt"=>array('text'=>'数量','width'=>70),
			// "danjia"=>array('text'=>'单价','width'=>70),
			"zhekouMoney"=>array('text'=>'折扣金额','width'=>70),
			"money"=>array('text'=>'应付金额','width'=>70),
			"yifukuan"=>array('text'=>'已付款','width'=>70),
			"memo"=>"备注",
			// "creater"=>"制单人",
		);

		$smarty = & $this->_getView();
		$smarty->assign('title', $title);
		$smarty->assign('arr_field_info',$arrField);
		$smarty->assign('arr_field_value',$rowset);
		$smarty->assign('arr_condition', $arr);
		$smarty->assign('add_display', 'none');
		$smarty->assign('page_info',$pager->getNavBar($this->_url($_GET['action'],$arr)));
		$smarty->display($tpl);
	}	

	function actionSave(){
		// dump($_POST);exit;
		foreach($this->fldMain as $k=>&$vv) {
			$name = $vv['name']?$vv['name']:$k;
			$arr[$k] = $_POST[$name];
		}	
		// $arr=array(
		// 	'id'=>$_POST['id'],
		// 	'supplierId'=>$_POST['supplierId']+0,
		// 	'kind'=>$_POST['kind'],
		// 	'guozhangDate'=>$_POST['guozhangDate'],
		// 	'rukuDate'=>$_POST['rukuDate'],
		// 	'rukuId'=>$_POST['rukuId'],
		// 	'rukuCode'=>$_POST['rukuId'],
		// 	'ruku2ProId'=>$_POST['ruku2ProId'],
		// 	'productId'=>$_POST['productId'],
		// 	// 'kuweiId'=>$_POST['kuweiId'],
		// 	'qitaMemo'=>$_POST['qitaMemo'],
		// 	'cnt'=>$_POST['cnt'],
		// 	'danjia'=>$_POST['danjia'],
		// 	'money'=>$_POST['money'],
		// 	'_money'=>$_POST['_money'],
		// 	'memo'=>$_POST['memo'],
		// 	'creater'=>$_POST['creater'],
		// 	'memo'=>$_POST['memo'],
		// 	'zhekouMoney'=>$_POST['zhekouMoney']+0,
		// );
// 		dump($arr);echo 1;exit;
		$id=$this->_modelExample->save($arr);
		$guozhangId=$_POST['id']>0?$_POST['id']:$id;
		//改变入库表中的过账id
		// if($_POST['ruku2ProId']){
		// 	$arr_rk=array(
		// 		'id'=>$_POST['ruku2ProId'],
		// 		'guozhangId'=>$guozhangId,
		// 	);
		// 	$this->_modelRuku2Pro->update($arr_rk);
		// }
		js_alert(null,'window.parent.showMsg("操作成功");',$this->_url($_POST['id']>0?'right':'add'));
	}

	
	//应付款报表
	function actionReport(){
		// $this->authCheck('4-1-7');
		$tpl = $_GET['print']?'Print.tpl':'TblList.tpl';
		FLEA::loadclass('TMIS_Pager');
		$arr=TMIS_Pager::getParamArray(array(
			'dateFrom'=>date('Y-m-01'),
			'dateTo'=>date('Y-m-d'),
			'supplierId'=>'',
		));
		//得到期初发生
		//应付款表中查找,日期为期初日期
		//按照加工商汇总
		$sql="select sum(money) as fsMoney,supplierId from caiwu_yf_guozhang where guozhangDate < '{$arr['dateFrom']}' and isJiagong<>1";
		if($arr['supplierId']!=''){
			$sql.=" and supplierId='{$arr['supplierId']}'";
		}
		$sql.=" group by supplierId order by supplierId";
		$rowset = $this->_modelExample->findBySql($sql);
		foreach($rowset as & $v){
			//期初金额
			$row[$v['supplierId']]['initMoney']=$v['fsMoney']+0;//期初余额
			$row[$v['supplierId']]['initIn']=$v['fsMoney']+0;
		}
		//得到起始日期前的收款金额
		//从付款表中查找
		//按照加工商汇总
		$sqlIncome = "SELECT sum(money) as FukuMoney,supplierId FROM `caiwu_yf_fukuan` where  fukuanDate < '{$arr['dateFrom']}' and kind=0";
		if($arr['supplierId']!=''){
			$sqlIncome.=" and supplierId='{$arr['supplierId']}'";
		}
		$sqlIncome.=" group by supplierId order by supplierId";
		$rowset = $this->_modelExample->findBySql($sqlIncome);
		foreach($rowset as & $v){
			//期初金额
			$row[$v['supplierId']]['initMoney']=round($row[$v['supplierId']]['initMoney']-$v['FukuMoney']+0,2);//期初余额=期初发生-期初已付款
			$row[$v['supplierId']]['initOut']=$v['FukuMoney'];
		}
		
		//得到本期的已付款
		//付款表中查找
		//按照加工户汇总
		$str="SELECT sum(money) as moneyfukuan,supplierId from caiwu_yf_fukuan where 1 and kind=0";
		if($arr['dateFrom']!=''){
			$str.=" and fukuanDate>='{$arr['dateFrom']}' and fukuanDate<='{$arr['dateTo']}'";
		}
		if($arr['supplierId']!=''){
			$str.=" and supplierId='{$arr['supplierId']}'";
		}
		$str.=" group by supplierId order by supplierId";
		//echo $str;exit;
		$fukuan=$this->_modelExample->findBySql($str);
		foreach($fukuan as & $v1){
			$row[$v1['supplierId']]['fukuanMoney']=$v1['moneyfukuan']+0;
		}

		//得到本期发生
		//应付款表中查找
		//按照加工户汇总
		$sql="select sum(money) as fsMoney,supplierId from caiwu_yf_guozhang where 1  and isJiagong<>1";
		if($arr['dateFrom']!=''){
			$sql.=" and guozhangDate>='{$arr['dateFrom']}' and guozhangDate<='{$arr['dateTo']}'";
		}
		if($arr['supplierId']!=''){
			$sql.=" and supplierId='{$arr['supplierId']}'";
		}
		$sql.=" group by supplierId order by supplierId";
		$rowset = $this->_modelExample->findBySql($sql);
		foreach($rowset as & $v2){
			$row[$v2['supplierId']]['fsMoney']=$v2['fsMoney']+0;
		}

		//得到本期发票
		$str1="SELECT sum(money) as faPiaoMoney,supplierId FROM `caiwu_yf_fapiao` where 1 and kind=0";
		if($arr['dateFrom']!=''){
			$str1.=" and fapiaoDate>='{$arr['dateFrom']}' and fapiaoDate<='{$arr['dateTo']}'";
		}
		if($arr['supplierId']!=''){
			$str1.=" and supplierId='{$arr['supplierId']}'";
		}
		$str1.=" group by supplierId order by supplierId";
		$fukuan=$this->_modelExample->findBySql($str1);
		foreach ($fukuan as $v2){
			$row[$v2['supplierId']]['faPiaoMoney']=$v2['faPiaoMoney']+0;
		}
		//dump($row);exit;
		$mClient=& FLEA::getSingleton('Model_jichu_supplier');
		if(count($row)>0){
			foreach($row as $key => & $v){
				$c=$mClient->find(array('id'=>$key));
				$v['supplierId']=$key;
				$v['compName']=$c['compName'];
				
				$v['weifuMoney']=$v['initMoney']+$v['fsMoney']-$v['fukuanMoney'];
			}
		}
		
		$heji=$this->getHeji($row,array('initMoney','fukuanMoney','faPiaoMoney','weifuMoney','fsMoney'),'compName');
		$no = 1;
		foreach($row as $key=>& $v){
		    $v['no']         =  $no++;
			$v['fukuanMoney']="<a href='".url('caiwu_Yf_Fukuan','right',array(
						'supplierId'=>$v['supplierId'],
						'dateFrom'=>$arr['dateFrom'],
						'dateTo'=>$arr['dateTo'],
						'width'=>'700',
						'no_edit'=>1,
						'TB_iframe'=>1,))."' class='thickbox' title='付款明细'>".$v['fukuanMoney']."</a>";
			$v['faPiaoMoney']="<a href='".url('caiwu_Yf_Fapiao','right',array(
						'supplierId'=>$v['supplierId'],
						'dateFrom'=>$arr['dateFrom'],
						'dateTo'=>$arr['dateTo'],
						'width'=>'700',
						'no_edit'=>1,
						'TB_iframe'=>1,))."' class='thickbox' title='收票明细'>".$v['faPiaoMoney']."</a>";
			$v['fsMoney']="<a href='".url('caiwu_Yf_Guozhang','right',array(
						'supplierId'=>$v['supplierId'],
						'dateFrom'=>$arr['dateFrom'],
						'dateTo'=>$arr['dateTo'],
						'width'=>'700',
						'no_edit'=>1,
						'TB_iframe'=>1,))."' class='thickbox' title='应付明细'>".$v['fsMoney']."</a>";

			//查看对账单
			// $v['duizhang']="<a href='".$this->_url('Duizhang',array(
			// 		'dateFrom'=>$arr['dateFrom'],
			// 		'dateTo'=>$arr['dateTo'],
			// 		'supplierId'=>$v['supplierId'],
			// 		'no_edit'=>1,
			// ))."' target='_blank'>查看</a>";
		}
		$row[]=$heji;
		
		$arrFiled=array(
            'no'      => '序号',
			'compName'=>"供应商",
			"initMoney" =>"期初余额",
			"fsMoney" =>"本期发生",
			"fukuanMoney" =>"本期付款",
			"weifuMoney" =>"本期结余",
			"faPiaoMoney" =>"本期收票",
			// 'duizhang'=>'对账单',
			// 'hexiao'=>'核销',
		);
		if($_GET['print']){
			unset($arrFiled['duizhang']);
		}
		$smarty = & $this->_getView();
		$smarty->assign('title', $title);
		$smarty->assign('arr_field_info',$arrFiled);
		$smarty->assign('arr_condition',$arr);
		$smarty->assign('add_display','none');
		$smarty->assign('arr_field_value',$row);
		$smarty->assign('heji',$heji);
		$smarty->assign('print_href',$this->_url($_GET['action'],array(
			'print'=>1
		)));
		$smarty->assign('title','应付款报表');
		if($_GET['print']) {
			//设置账期显示
			$smarty->assign('arr_main_value',array(
				'账期'=>$arr['dateFrom'] . ' 至 ' . $arr['dateTo']
			));
		}
		$smarty->display($tpl);
	}

	//对账单
	/*function actionDuizhang(){
		// dump($_GET);exit;
		$arr=$_GET;
		if(empty($arr['supplierId'])){
			echo "缺少供应加工商信息";exit;
		}
		//查找对账单加工户
		$mClient=& FLEA::getSingleton('Model_jichu_Supplier');
		$jgh=$mClient->find($arr['supplierId']);
		// 对账单设计格式
		//查找期初欠款的情况
		//期初发生
		$sql="select sum(money) as money from caiwu_yf_guozhang where guozhangDate < '{$arr['dateFrom']}' and supplierId='{$arr['supplierId']}'";
		$res1=mysql_fetch_assoc(mysql_query($sql));
		//期初付款
		$sql="select sum(money) as money from caiwu_yf_fukuan where fukuanDate < '{$arr['dateFrom']}' and supplierId='{$arr['supplierId']}'";
		$res2=mysql_fetch_assoc(mysql_query($sql));
		$row['money']=$res1['money']-$res2['money'];
		$row['guozhangDate']="<b>期初余额</b>";

		//本期应付款对账信息
		//查找应付款信息
		$sql="select x.*,y.rukuNum,y.rukuDate,z.proName,z.guige as guigeDesc from caiwu_yf_guozhang x
			left join cangku_ruku y on x.rukuId=y.id
			left join jichu_product z on z.id=x.productId
			where 1";
		if($arr['supplierId']!=''){
			$sql.=" and x.supplierId='{$arr['supplierId']}'";
		}
		if($arr['dateFrom']!=''){
			$sql.=" and x.guozhangDate >= '{$arr['dateFrom']}'";
		}
		if($arr['dateTo']!=''){
			$sql.=" and x.guozhangDate <= '{$arr['dateTo']}'";
		}
		$sql.=" order by guozhangDate";
		$rows = $this->_modelExample->findBySql($sql);
		
		// dump($rows);exit;
		//处理数据
		foreach($rows as  & $v){
			$v['money']=$v['money']==0?'':round($v['money'],2);
			$v['zhekouMoney']=$v['zhekouMoney']==0?'':round($v['zhekouMoney'],2);
			$v['yifukuan']=$v['yifukuan']==0?'':round($v['yifukuan'],2);
			$v['yishouPiao']=$v['yishouPiao']==0?'':round($v['yishouPiao'],2);

			if($_GET['no_edit']!=1){
				if($v['yishouPiao']!=0)$checked='checked';
				else $checked='';
				$v['yishouPiao']="<span name='yishouPiao[]'>{$v['yishouPiao']}</span>";
				$v['yishouPiao'].="<input type='hidden' name='money[]' value='{$v['money']}'>";
				$v['yishouPiao'].="&nbsp;&nbsp;<input type='checkbox' name='yishouPiao_box[]' style='width:16px;height:16px;' value='{$v['id']}' title='点击收票完成' {$checked}>";

				if($v['yifukuan']!=0)$checked2='checked';
				else $checked2='';
				$v['yifukuan']="<span name='yifukuan[]'>{$v['yifukuan']}</span>";
				$v['yifukuan'].="&nbsp;&nbsp;<input type='checkbox' name='yifukuan_box[]' style='width:16px;height:16px;' value='{$v['id']}'  title='点击付款完成' {$checked2}>";
				// $this->makeEditable($v,'yifukuan');
				$v['memo']=trim($v['memo']);
				$this->makeEditable($v,'memo');
			}
		}
		//合并数组
		$rowset=array_merge(array($row),$rows);

		$heji = $this->getHeji($rowset, array('money','yifukuan','yishouPiao','zhekouMoney'), 'guozhangDate');
		if($_GET['no_edit']!=1){
			$heji['yifukuan']='';
			$heji['yishouPiao']='';
		}
		$rowset[]=$heji;
		
		$arr_field_info=array(
			'guozhangDate'=>'日期',
			'rukuNum'=>'入库编号',
			'rukuDate'=>'入库日期',
			'proName'=>'品名',
			'guigeDesc'=>'规格',
			'pihao'=>'批号',
			'cnt'=>'数量',
			'danjia'=>'单价',
			// 'zhekouMoney'=>'折扣金额',
			'money'=>'应付金额',
			'yifukuan'=>'实付金额',
			'yishouPiao'=>'已收发票',
			'memo'=>'备注',
		);
		$smarty=& $this->_getView();
		$smarty->assign('title',"{$jgh['compName']}对账单");
		$smarty->assign('arr_main_value',array('账期'=>$arr['dateFrom'] . ' 至 ' . $arr['dateTo']));
		$smarty->assign('arr_condition',$arr);
		if($_GET['no_edit']!=1){
			$smarty->assign('sonTpl',"caiwu/yf/sonTpl.tpl");
		}
		$smarty->assign('arr_field_info',$arr_field_info);
		$smarty->assign('arr_field_value',$rowset);
		$smarty->display('printOld.tpl');
	}*/


	
	//删除
	function actionRemove(){
		//去掉入库信息中的guozhangid
		// $temp=$this->_modelExample->find($_GET['id']);
		// // dump($temp);exit;
		// $sql="update cangku_ruku2product set guozhangId='0' where id='{$temp['ruku2ProId']}'";
		// $this->_modelExample->execute($sql);

		parent::actionRemove();
	}

	function actionAdd() {
		// echo 1;exit;
		// dump($this->fldMain);exit;
		$smarty = &$this->_getView();
		$smarty->assign('fldMain', $this->fldMain);
		$smarty->assign('rules', $this->rules);
		$smarty->assign('title', '过账信息编辑');
		$smarty->assign('sonTpl', 'caiwu/yf/sonTpl.tpl');
		$smarty->display('Main/A1.tpl');
	}

	function actionEdit() {
		$row = $this->_modelExample->find(array('id' => $_GET['id']));
		// //查找库位
		// $sql="select kuweiName from jichu_kuwei where id='{$row['kuweiId']}'";
		// // echo $sql;exit;
		// $temp = $this->_modelExample->findBySql($sql);
		// $row['kuweiName']=$temp[0]['kuweiName'];

		//查找产品
		// $row['proName']=$row['Product']['proName'];
		// $row['guige']=$row['Product']['guige'];

		// $row['rkYuanliao']="";

		$this->fldMain = $this->getValueFromRow($this->fldMain, $row); 

		//处理出库单号
		$mRuku = & FLEA::getSingleton('Model_Cangku_Ruku');
		$ruku = $mRuku->find(array('id'=>$row['rukuId']));
		// dump($row);dump($ruku);exit;
		$this->fldMain['ruku2ProId']['text'] = $ruku['rukuCode'];
		// $this->fldMain['clientId']['clientName']=$row['Client']['compName'];

		// dump($row);exit;
		$smarty = &$this->_getView();
		$smarty->assign('fldMain', $this->fldMain);
		$smarty->assign('rules', $this->rules);
		$smarty->assign('title', '过账信息编辑');
		$smarty->assign('aRow', $row);
		$smarty->assign('sonTpl', 'caiwu/yf/sonTpl.tpl');
		$smarty->display('Main/A1.tpl');
	}
}
?>