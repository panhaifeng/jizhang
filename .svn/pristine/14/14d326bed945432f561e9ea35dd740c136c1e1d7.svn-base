<?php
FLEA::loadClass('TMIS_Controller');
class Controller_Cangku_Yl_Kucun extends Tmis_Controller {
	function Controller_Cangku_Yl_Kucun() {
		$this->_modelExample = & FLEA::getSingleton('Model_Cangku_Yl_Kucun');
		$this->_mYl = & FLEA::getSingleton('Model_Jichu_Yl');
		$this->_modelRuku = & FLEA::getSingleton("Model_Cangku_Yl_Ruku");
		$this->_mYlChuku = & FLEA::getSingleton("Model_Cangku_Yl_Chuku");
	}

	//库存查询
	function actionRight() {
		//直接跳转到收发存报表
		redirect($this->_url('month'));
		exit;
		////////////////////////////////标题
		$title = '库存报表';
		///////////////////////////////模板文件
		$tpl = 'TableList2.tpl';
		///////////////////////////////表头
		$arr_field_info = array(
			'ylCode'=>'原料编码',
			'ylName'=>'名称',
			'guige'=>'规格',
			'unit'=>'单位',
			'kucunLen'=>'库存长度',
			'kucunCnt'=>'库存重量',
			'kucunZhishu'=>'库存数量',
			//'kucunMoney'=>'库存金额'
		);
		///////////////////////////////搜索条件
		$arrCon = array(
			'key'=>''
		);
		///////////////////////////////模块定义
		$this->authCheck('2-5');
		FLEA::loadClass('TMIS_Pager');
		$arr = TMIS_Pager::getParamArray($arrCon);

		/************构造condition**********/
		$sql = "select x.*,y.kucunCnt,
			y.kucunMoney,y.kucunZhishu,y.kucunLen
			from jichu_yl x
			left join cangku_yl_init y on x.id=y.ylId
			where 1
		";
		if($arr['key']!='') {
			$sql .= " and (zhujiCode like '%{$arr['key']}%' or ylCode like '%{$arr['key']}%' or ylName like '%{$arr['key']}%' or guige like '%{$arr['key']}%') group by x.ylCode order by x.ylCode asc";
		} else {
			$sql .= " group by x.ylCode order by x.ylCode asc";
		}
		$rowset=$this->_modelExample->findBySql($sql);
//		dump($rowset);exit;
		if(count($rowset)>0) foreach($rowset as & $v){

		}
		$heji=$this->getHeji($rowset,array('kucunLen','kucunCnt','kucunZhishu'),'ylCode');
		$rowset[]=$heji;
		$smarty = & $this->_getView();
		$smarty->assign('title', $title);
		$smarty->assign('add_display','none');
		$smarty->assign('print_display','print');
		$smarty->assign('arr_field_info',$arr_field_info);
		$smarty->assign('arr_field_value',$rowset);
		$smarty->assign('arr_condition', $arr);

		/*///////////////grid,thickbox,///////////////*/
		$smarty->assign('arr_js_css',$this->makeArrayJsCss(array('calendar','thickbox')));
		//$smarty->assign('page_info',$pager->getNavBar($this->_url($_GET['action'],$arr)));
		$smarty-> display($tpl);
	}

	//初始化
	function actionInit() {
		////////////////////////////////标题
		$title = '原料库存初始化';
		///////////////////////////////模板文件
		$tpl = 'TableList2.tpl';
		///////////////////////////////表头
		$arr_field_info = array(
			'ylCode'=>'物料编码',
			'ylName'=>'名称',
			'guige'=>'规格',
			'initCnt'=>'初始库存',
			'initMoney'=>'初始金额'
		);
		///////////////////////////////搜索条件
		$arrCon = array(
			'key'=>''
		);
		///////////////////////////////模块定义
		$this->authCheck(55);
		FLEA::loadClass('TMIS_Pager');
		$arr = TMIS_Pager::getParamArray($arrCon);

		/************构造condition**********/
		$sql = "select x.*,y.initCnt,y.initMoney from jichu_yl x
			left join cangku_yl_init y on x.id=y.ylId where 1";
		if($arr['key']!='') $sql .= " and (zhujiCode like '%{$arr['key']}%' or ylCode like '%{$arr['key']}%' or ylName like '%{$arr['key']}%' or guige like '%{$arr['key']}%')";


		$pager =& new TMIS_Pager($sql);
        $rowset =$pager->findAll();
		if(count($rowset)>0) foreach($rowset as & $v){
			if($v['initCnt']==0) $v['initCnt']='';
			if($v['initMoney']==0) $v['initMoney']='';
			///////////////////////////////
			$this->makeEditable($v,'initCnt');
			$this->makeEditable($v,'initMoney');
			$v['_edit'] = $this->getEditHtml($v['id']) . ' | ' . $this->getRemoveHtml($v['id']);

		}
		$smarty = & $this->_getView();
		$smarty->assign('title', $title);
		$smarty->assign('add_display','none');
		$smarty->assign('arr_field_info',$arr_field_info);
		$smarty->assign('arr_field_value',$rowset);
		$smarty->assign('arr_condition', $arr);

		/*///////////////grid,thickbox,///////////////*/
		$smarty->assign('arr_js_css',$this->makeArrayJsCss(array('calendar','grid')));
		$smarty->assign('page_info',$pager->getNavBar($this->_url($_GET['action'],$arr))."<font color='red'>单击数量和金额可进行修改</font>");
		$smarty-> display($tpl);
	}

	//初始化时用，
	function actionAjaxEdit() {
		$row['ylId'] = $_GET['id'];
		$row[$_GET['fieldName']] = $_GET['value'];
		$sql = "select id,count(*) cnt from cangku_yl_init where ylId='{$_GET['id']}' group by ylId";
		//echo $sql;
		$re = mysql_fetch_assoc(mysql_query($sql));
		if ($re['cnt']>0){
			$row['id'] = $re['id'];
		}
		//dump($row);exit;
		if ($this->_modelExample->save($row)) {
			$this->_modelExample->changeYlKucun($row['ylId']);
			echo "{successful:true,msg:'成功!'}";
		} else {
			echo "{successful:false,msg:'出错!'}";
		}
		exit;
	}

	//库存调整
	function actionChangeKucun() {
		////////////////////////////////标题
		$title = '原料库存调整';
		///////////////////////////////模板文件
		$tpl = 'TableList2.tpl';
		///////////////////////////////表头
		$arr_field_info = array(
			'ylCode'=>'物料编码',
			'ylName'=>'名称',
			'guige'=>'规格',
			'unit'=>'单位',
			'kucunCnt'=>'库存数',
			'kucunMoney'=>'库存金额',
			'_edit'=>'调整'
		);
		///////////////////////////////搜索条件
		$arrCon = array(
			'key'=>''
		);
		///////////////////////////////模块定义
		$this->authCheck(132);
		FLEA::loadClass('TMIS_Pager');
		$arr = TMIS_Pager::getParamArray($arrCon);

		/************构造condition**********/
		$sql = "select x.*,y.kucunCnt,y.kucunMoney from jichu_yl x
			left join cangku_yl_init y on x.id=y.ylId where 1";
		if($arr['key']!='') $sql .= " and (zhujiCode like '%{$arr['key']}%' or ylCode like '%{$arr['key']}%' or ylName like '%{$arr['key']}%' or guige like '%{$arr['key']}%')";


		$pager =& new TMIS_Pager($sql);
        $rowset =$pager->findAll();
		if(count($rowset)>0) foreach($rowset as & $v){
			//if($v['initCnt']==0) $v['initCnt']='';
			//if($v['initMoney']==0) $v['initMoney']='';
			///////////////////////////////
			//$this->makeEditable($v,'initCnt');
			//$this->makeEditable($v,'initMoney');
			$v['_edit'] = '<a href="'.$this->_url('change',array(
				'ylId'=>$v['id'],
				'TB_iframe'=>1
			)).'" class="thickbox" title"调整库存">调整</a>';

		}
		$smarty = & $this->_getView();
		$smarty->assign('title', $title);
		$smarty->assign('add_display','none');
		$smarty->assign('arr_field_info',$arr_field_info);
		$smarty->assign('arr_field_value',$rowset);
		$smarty->assign('arr_condition', $arr);

		/*///////////////grid,thickbox,///////////////*/
		$smarty->assign('arr_js_css',$this->makeArrayJsCss(array('calendar','thickbox')));
		$smarty->assign('page_info',$pager->getNavBar($this->_url($_GET['action'],$arr)));
		$smarty-> display($tpl);
	}

	function actionMonth() {
		////////////////////////////////标题
		$title = '原料库存调整';
		///////////////////////////////模板文件
		$tpl = 'TableList2.tpl';
		///////////////////////////////表头
		
		///////////////////////////////搜索条件
		$arrCon = array(
			'dateFrom'=>date('Y-m-01'),
			'dateTo'=>date('Y-m-d'),
			'key'=>''
		);
		///////////////////////////////模块定义
		$this->authCheck('2-5');
		FLEA::loadClass('TMIS_Pager');
		$arr = TMIS_Pager::getParamArray($arrCon);

		//期初
		//wkdl的库存没有初始化功能，故不需要考虑init表
		$sql = "select x.ylId,sum(x.cnt) as cnt,sum(x.zhishu) zhishu,sum(x.len) as len
			from cangku_yl_ruku2yl x 
			left join cangku_yl_ruku y on x.rukuId=y.id
			inner join jichu_yl z on x.ylId=z.id 
			where y.rukuDate<'{$arr['dateFrom']}'";
		if($arr['key']!='') {
			$sql .= " and (z.zhujiCode like '%{$arr['key']}%' or z.ylCode like '%{$arr['key']}%' or z.ylName like '%{$arr['key']}%' or z.guige like '%{$arr['key']}%')";
		}
		$sql .= " group by x.ylId";
		$initRuku = $this->_modelExample->findBySql($sql);
		foreach($initRuku as & $v) {
			$ret[$v['ylId']]['initCntRuku'] = $v['cnt'];
			$ret[$v['ylId']]['initLenRuku'] = $v['len'];
			$ret[$v['ylId']]['initZhishuRuku'] = $v['zhishu'];
		}


		$sql = "select x.ylId,sum(x.cnt) as cnt,sum(x.zhishu) zhishu,sum(x.len) as len
			from cangku_yl_chuku2yl x 
			left join cangku_yl_chuku y on x.chukuId=y.id
			inner join jichu_yl z on x.ylId=z.id 
			where y.chukuDate<'{$arr['dateFrom']}'";
		if($arr['key']!='') {
			$sql .= " and (z.zhujiCode like '%{$arr['key']}%' or z.ylCode like '%{$arr['key']}%' or z.ylName like '%{$arr['key']}%' or z.guige like '%{$arr['key']}%')";
		}
		$sql .= " group by x.ylId";
		$initChuku = $this->_modelExample->findBySql($sql);
		foreach($initChuku as & $v) {
			// $ret[$v['ylId']]['initChuku'] = $v['cnt'];
			$ret[$v['ylId']]['initCntChuku'] = $v['cnt'];
			$ret[$v['ylId']]['initLenChuku'] = $v['len'];
			$ret[$v['ylId']]['initZhishuChuku'] = $v['zhishu'];
		}
		//期初为0的过滤掉
		foreach($ret as $k=>& $v) {
			if($v['initCntRuku']==$v['initCntChuku'] && $v['initLenRuku']==$v['initLenChuku'] && $v['initZhishuRuku']==$v['initZhishuChuku']) continue;
			$ret1[$k] = $v;
		}
		$ret = $ret1;

		//本期入库
		$sql = "select x.ylId,sum(x.cnt) as cnt,sum(x.zhishu) zhishu,sum(x.len) as len
			from cangku_yl_ruku2yl x 
			left join cangku_yl_ruku y on x.rukuId=y.id
			inner join jichu_yl z on x.ylId=z.id 
			where y.rukuDate>='{$arr['dateFrom']}' and y.rukuDate<='{$arr['dateTo']}'";
		if($arr['key']!='') {
			$sql .= " and (z.zhujiCode like '%{$arr['key']}%' or z.ylCode like '%{$arr['key']}%' or z.ylName like '%{$arr['key']}%' or z.guige like '%{$arr['key']}%')";
		}
		$sql .= " group by x.ylId";
		$ruku = $this->_modelExample->findBySql($sql);
		foreach($ruku as & $v) {
			// $ret[$v['ylId']]['cntRuku'] = $v['cnt'];
			$ret[$v['ylId']]['cntRuku'] = $v['cnt'];
			$ret[$v['ylId']]['lenRuku'] = $v['len'];
			$ret[$v['ylId']]['zhishuRuku'] = $v['zhishu'];
		}

		//本期出库
		$sql = "select x.ylId,sum(x.cnt) as cnt,sum(x.zhishu) zhishu,sum(x.len) as len
			from cangku_yl_chuku2yl x 
			left join cangku_yl_chuku y on x.chukuId=y.id
			inner join jichu_yl z on x.ylId=z.id 
			where y.chukuDate>='{$arr['dateFrom']}' and y.chukuDate<='{$arr['dateTo']}'";
		if($arr['key']!='') {
			$sql .= " and (z.zhujiCode like '%{$arr['key']}%' or z.ylCode like '%{$arr['key']}%' or z.ylName like '%{$arr['key']}%' or z.guige like '%{$arr['key']}%')";
		}
		$sql .= " group by x.ylId";
		$initChuku = $this->_modelExample->findBySql($sql);
		foreach($initChuku as & $v) {
			// $ret[$v['ylId']]['cntChuku'] = $v['cnt'];
			$ret[$v['ylId']]['cntChuku'] = $v['cnt'];
			$ret[$v['ylId']]['lenChuku'] = $v['len'];
			$ret[$v['ylId']]['zhishuChuku'] = $v['zhishu'];
		}

		// dump($ret);exit;
		foreach($ret as $k=>&$v) {			
			$v['cntInit'] = round($v['initCntRuku']-$v['initCntChuku'],2);
			$v['lenInit'] = round($v['initLenRuku']-$v['initLenChuku'],2);
			$v['zhishuInit'] = round($v['initZhishuRuku']-$v['initZhishuChuku'],2);

			$v['cntKucun'] = round($v['cntInit']+$v['cntRuku']-$v['cntChuku'],2);
			$v['lenKucun'] = round($v['lenInit']+$v['lenRuku']-$v['lenChuku'],2);
			$v['zhishuKucun'] = round($v['zhishuInit']+$v['zhishuRuku']-$v['zhishuChuku'],2);

			$sql = "select * from jichu_yl where id='{$k}'";
			$temp = $this->_modelExample->findBySql($sql);
			// dump($sql);dump($temp);exit;
			$v['ylId'] = $k;
			$v['ylCode'] = $temp[0]['ylCode'];
			$v['ylName'] = $temp[0]['ylName'];
			$v['guige'] = $temp[0]['guige'];
			$v['unit'] = $temp[0]['unit'];			
		}
		// dump($ret);exit;
		//$ret = array_column_sort($ret,'ylCode');
		$ret[] = $this->getHeji($ret,array('lenInit','cntInit','zhishuInit','lenRuku','cntRuku','zhishuRuku','lenChuku','cntChuku','zhishuChuku','lenKucun','cntKucun','zhishuKucun'),'ylCode');
		$arr_field_info = array(
			'ylCode'=>'物料编码',
			'ylName'=>'名称',
			'guige'=>'规格',
			'unit'=>'单位',
			// 'kucunCnt'=>'期初数',
			// 'kucunMoney'=>'库存金额',
			// '_edit'=>'调整'
			'lenInit'=>'期初长度',
			'cntInit'=>'期初重量',
			'zhishuInit'=>'期初数量',

			'lenRuku'=>'本期入库长度',
			'cntRuku'=>'本期入库重量',
			'zhishuRuku'=>'本期入库数量',

			'lenChuku'=>'本期出库长度',
			'cntChuku'=>'本期出库重量',
			'zhishuChuku'=>'本期出库数量',

			'lenKucun'=>'期末长度',
			'cntKucun'=>'期末重量',
			'zhishuKucun'=>'期末数量',
		);
		/************构造condition**********/
		// $sql = "select x.*,y.kucunCnt,y.kucunMoney from jichu_yl x
		// 	left join cangku_yl_init y on x.id=y.ylId where 1";
		// if($arr['key']!='') $sql .= " and (zhujiCode like '%{$arr['key']}%' or ylCode like '%{$arr['key']}%' or ylName like '%{$arr['key']}%' or guige like '%{$arr['key']}%')";


		// $pager =& new TMIS_Pager($sql);
  //       $rowset =$pager->findAll();
		// if(count($rowset)>0) foreach($rowset as & $v){
		// 	//if($v['initCnt']==0) $v['initCnt']='';
		// 	//if($v['initMoney']==0) $v['initMoney']='';
		// 	///////////////////////////////
		// 	//$this->makeEditable($v,'initCnt');
		// 	//$this->makeEditable($v,'initMoney');
		// 	$v['_edit'] = '<a href="'.$this->_url('change',array(
		// 		'ylId'=>$v['id'],
		// 		'TB_iframe'=>1
		// 	)).'" class="thickbox" title"调整库存">调整</a>';

		// }
		$smarty = & $this->_getView();
		$smarty->assign('title', $title);
		$smarty->assign('add_display','none');
		$smarty->assign('arr_field_info',$arr_field_info);
		$smarty->assign('arr_field_value',$ret);
		$smarty->assign('arr_condition', $arr);

		/*///////////////grid,thickbox,///////////////*/
		$smarty->assign('arr_js_css',$this->makeArrayJsCss(array('calendar','thickbox')));
		// $smarty->assign('page_info',$pager->getNavBar($this->_url($_GET['action'],$arr)));
		$smarty-> display($tpl);
	}

	function actionChange() {
		$sql = "select x.*,y.kucunCnt,y.kucunMoney,y.id as kucunId from jichu_yl x
			left join cangku_yl_init y on x.id=y.ylId where x.id='{$_GET['ylId']}'";
		$re = mysql_fetch_assoc(mysql_query($sql));
		$tpl='Cangku/Yl/ChangeKucun.tpl';
		$smarty = & $this->_getView();
		$smarty->assign('row',$re);
		$smarty->display($tpl);
	}

	function actionChangeSave() {
		//dump($_POST);exit;
		$sql = "select * from cangku_yl_init where id='{$_POST['kucunId']}'";
		$re = mysql_fetch_assoc(mysql_query($sql));

		$cnt = $re['kucunCnt']-$_POST['kucunCnt'];
		if($cnt!=0) $danjia = ($re['kucunMoney']-$_POST['kucunMoney'])/$cnt;
		else $danjia=0;

		//生成库存调整记录,作为其他出库
		$row=array(
			'kind'			=>1,
			'memo'		=>'库存调整',
			'chukuDate'	=>date("Y-m-d"),
			'Yl'		=>array(
				array(
					'ylId' => $_POST[ylId],
					'cnt' => $cnt,
					'danjia' => $danjia
				)
			)
		);
		$m = & FLEA::getSingleton("Model_Cangku_Yl_Chuku");
		$m->create($row);

		//改变库存
		$m->changeYlKucun($_POST['ylId']);
		js_alert('调整成功!调库记录在其他出库中进行查询!','window.parent.location.href=window.parent.location.href;window.parent.tb_remove();');
	}

	//月报表
	// function actionMonth() {
	// 	////////////////////////////////标题
	// 	$title = '收发存汇总报表';
	// 	///////////////////////////////模板文件
	// 	$tpl = 'TableList2.tpl';
	// 	///////////////////////////////表头
	// 	$arr_field_info = array(
	// 		'ylCode'=>'物料编码',
	// 		'ylName'=>'名称',
	// 		'guige'=>'规格',
	// 		'unit'=>'单位',
	// 		'cntInit'=>'期初数',
	// 		'moneyInit'=>'期初金额',
	// 		'cntRuku'=>'入库数',
	// 		'moneyRuku'=>'入库金额',
	// 		'cntChuku'=>'出库数',
	// 		'moneyChuku'=>'出库金额',
	// 		'cntKucun'=>'库存数',
	// 		'moneyKucun'=>'库存金额'
	// 	);
	// 	///////////////////////////////搜索条件
	// 	$arrCon = array(
	// 		'date1'=>0,
	// 		'dateFrom'=>date('Y-m-01'),
	// 		'dateTo'=>date('Y-m-d'),
	// 		'key'=>''
	// 	);

	// 	///////////////////////////////模块定义
	// 	$this->authCheck(134);
	// 	FLEA::loadClass('TMIS_Pager');
	// 	$arr = TMIS_Pager::getParamArray($arrCon);
	// 	//dump($arr);
	// 	if($arr['date1']==0){//本月
	// 	    $arr['dateFrom']=date('Y-m-01');
	// 	    $arr['dateTo']=date('Y-m-d');
	// 	}elseif($arr['date1']==1){//上月
	// 	    $arr['dateFrom']=date("Y-m-d",mktime(0, 0 , 0,date("m")-1,1,date("Y")));
	// 	    $arr['dateTo']=date("Y-m-d",mktime(23,59,59,date("m"),0,date("Y")));
	// 	}else{//本季
	// 	    $arr['dateFrom']=date('Y-m-d', mktime(0, 0, 0,date('n')-(date('n')-1)%3,1,date('Y')));
	// 	    $getMonthDays = date("t",mktime(0, 0 , 0,date('n')+(date('n')-1)%3,1,date("Y")));
	// 	    $arr['dateTo']=date('Y-m-d', mktime(23,59,59,date('n')+(date('n')-1)%3,$getMonthDays,date('Y')));
	// 	}
	// 	/************构造condition**********/
	// 	$sql = "select x.*,y.initCnt,y.initMoney from cangku_yl_init y left join jichu_yl x on x.id=y.ylId where 1";
	// 	if($arr['key']!='') $sql .= " and (zhujiCode like '%{$arr['key']}%' or ylCode like '%{$arr['key']}%' or ylName like '%{$arr['key']}%' or guige like '%{$arr['key']}%')";
	// 	$pager =& new TMIS_Pager($sql);
 //        $rowset =$pager->findAll();
	// 	//dump($rowset);
	// 	if(count($rowset)>0) foreach($rowset as & $v){
	// 		//得到期初
	// 		$sql = "select sum(x.cnt) as cnt,sum(x.cnt*x.danjia) as money from cangku_yl_ruku2yl x
	// 			left join cangku_yl_ruku y on x.rukuId=y.id where x.ylId='{$v['id']}' and y.rukuDate<'{$arr['dateFrom']}'";
	// 		$r = mysql_fetch_assoc(mysql_query($sql));
	// 		$sql = "select sum(x.cnt) as cnt,sum(x.cnt*x.danjia) as money from cangku_yl_chuku2yl x
	// 			left join cangku_yl_chuku y on x.chukuId=y.id where x.ylId='{$v['id']}' and y.chukuDate<'{$arr['dateFrom']}'";
	// 		$r1 = mysql_fetch_assoc(mysql_query($sql));
	// 		$v['cntInit'] = $v['initCnt']+$r['cnt']-$r1['cnt'];
	// 		$v['moneyInit'] = $v['initMoney']+$r['money']-$r1['money'];

	// 		//得到本月入库
	// 		$sql = "select sum(x.cnt) as cnt,sum(x.cnt*x.danjia) as money from cangku_yl_ruku2yl x
	// 			left join cangku_yl_ruku y on x.rukuId=y.id where x.ylId='{$v['id']}' and y.rukuDate>='{$arr['dateFrom']}' and y.rukuDate<='{$arr['dateTo']}'";
	// 		$r = mysql_fetch_assoc(mysql_query($sql));
	// 		$v['cntRuku'] = $r['cnt'];
	// 		$v['moneyRuku'] = $r['money'];

	// 		//本月出库
	// 		$sql = "select sum(x.cnt) as cnt,sum(x.cnt*x.danjia) as money from cangku_yl_chuku2yl x
	// 			left join cangku_yl_chuku y on x.chukuId=y.id where x.ylId='{$v['id']}' and y.chukuDate>='{$arr['dateFrom']}' and y.chukuDate<='{$arr['dateTo']}'";
	// 		$r = mysql_fetch_assoc(mysql_query($sql));
	// 		$v['cntChuku'] = $r['cnt'];
	// 		$v['moneyChuku'] = $r['money'];

	// 		//本月库存
	// 		$v['cntKucun'] = $v['cntInit']+$v['cntRuku']-$v['cntChuku'];
	// 		$v['moneyKucun']  =$v['moneyInit']+$v['moneyRuku']-$v['moneyChuku'];
	// 	}

	// 	//增加弹出显示明细的链接
	// 	if($rowset) foreach($rowset as & $v) {
	// 		$v['cntRuku'] = "<a href='".$this->_url('showRuku',array(
	// 			'dateFrom'=>$arr['dateFrom'],
	// 			'dateTo'=>$arr['dateTo'],
	// 			'ylId'=>$v['id']
	// 		))."' title='入库明细' target='_blank'>{$v['cntRuku']}</a>";

	// 		$v['cntChuku'] = "<a href='".$this->_url('showChuku',array(
	// 			'dateFrom'=>$arr['dateFrom'],
	// 			'dateTo'=>$arr['dateTo'],
	// 			'ylId'=>$v['id']
	// 		))."' target='_blank'>{$v['cntChuku']}</a>";
	// 	}

	// 	$rowset[] = $this->getHeji($rowset,array('moneyInit','moneyRuku','moneyChuku','moneyKucun'),'ylCode');
	// 	$smarty = & $this->_getView();
	// 	$smarty->assign('title', $title);
	// 	$smarty->assign('add_display','none');
	// 	$smarty->assign('arr_field_info',$arr_field_info);
	// 	$smarty->assign('arr_field_value',$rowset);
	// 	$smarty->assign('arr_condition', $arr);

	// 	/*///////////////grid,thickbox,///////////////*/
	// 	$smarty->assign('arr_js_css',$this->makeArrayJsCss(array('calendar')));
	// 	$smarty->assign('page_info',$pager->getNavBar($this->_url($_GET['action'],$arr)));
	// 	$smarty-> display($tpl);
	// }

	function actionShowRuku() {
		FLEA::loadClass('TMIS_Pager');
		$modelName = "Model_Jichu_Supplier";
		$str = "select
				y.id,
				a.rukuId,
				x.rukuNum,
				x.rukuDate,
				x.supplierId,
				a.cnt,
				a.danjia,
				a.money,
				x.memo,
				y.compName,
				z.ylName,
				z.guige,
				z.unit from cangku_yl_ruku x
				left join cangku_yl_ruku2yl a on x.id=a.rukuId
				left join jichu_supplier y on x.supplierId=y.id
				left join jichu_yl z on a.ylId=z.id where a.ylId='{$_GET['ylId']}' and x.rukuDate>='{$_GET['dateFrom']}' and x.rukuDate<='{$_GET['dateTo']}'";
		if ($arrGet['supplierId'] != '')  $str .=" and x.supplierId='$arrGet[supplierId]'";
		if ($arrGet['orderType'] != 0) $str .=" and orderType = $arrGet[orderType]";
		if ($arrGet['key'] != '')  $str .= " and (x.memo like '%$arrGet[key]%'
											or z.ylName like '%$arrGet[key]%'
											or z.guige like '%$arrGet[key]%')";
		$str .= " order by rukuDate desc, rukuNum desc";

		$rowset = $this->_modelRuku->findBySql($str);
		if (count($rowset)>0) foreach($rowset as & $value) {
			$tcnt += $value['cnt'];
			if ($value['money'] == 0) $value['money'] = $value['cnt'] * $value['danjia'];
			$tmoney += $value['money'];
		}
		#合计行
		$i = count($rowset);
		$rowset[$i]['rukuNum'] = '合计';
		$rowset[$i]['cnt'] = $tcnt;
		$rowset[$i]['money'] = $tmoney;

		//dump($rowset);
		//模板变量设置
		$smarty = & $this->_getView();
		$arrFieldInfo = array(
			"rukuNum" =>"单号",
			"rukuDate" =>"日期",
			"compName" =>'供应商',
			'ylName'=>'产品名称',
			'guige'=>'规格',
			//'color'=>'颜色',
			"cnt" =>'数量',
			'danjia' => '单价',
			'money'=>'金额',
		);

		$smarty->assign('arr_field_info',$arrFieldInfo);
		$smarty->assign('arr_js_css',$this->makeArrayJsCss());
		$smarty->assign('arr_field_value',$rowset);
		$smarty->assign('title','入库明细');
		$smarty->assign('add_display','none');
		$smarty->display('TableList.tpl');
	}

	function actionShowChuku() {
		FLEA::loadClass('TMIS_Pager');
		$modelName = "Model_Jichu_Chejian";
		$str = "select
			x.id,
			x.chukuId,
			y.chukuNum,
			y.chukuDate,
			x.cnt,
			x.danjia,
			y.memo,
			a.depName,
			z.ylName,
			z.guige,

			z.unit from cangku_yl_chuku y
			left join cangku_yl_chuku2yl x on y.id=x.chukuId
			left join jichu_yl z on x.ylId=z.id
			left join jichu_department a on y.depId=a.id
			where x.ylId='{$_GET['ylId']}' and y.chukuDate>='{$_GET['dateFrom']}' and y.chukuDate<='{$_GET['dateTo']}'";

		if ($arrGet['depId'] != '')  $str .= " and y.depId='$arrGet[depId]'";
		if ($arrGet[key] != '') {
			$str .= " and (z.ylName like '%$arrGet[key]%'
						or z.guige like '%$arrGet[key]%')";
		}
		$str .= " order by y.chukuDate desc";

		$rowset = $this->_mYlChuku->findBySql($str);
		if (count($rowset)>0) foreach($rowset as & $value) {
			$value['money'] = round($value['danjia'] * $value['cnt'],2);
			$value['operator'] = $value['Operator']['userName'];
			//$value['_edit'] = "<a href='".$this->_url('View',array(id=>$value[chukuId]))."' target='_blank' title='$title'>查看详细</a> | ".$this->getEditHtml($value['chukuId'])." | ".$this->getRemoveHtml($value['chukuId'])."";
			$tcnt += $value[cnt];
			if ($value[money] == 0) $value[money] = $value[cnt] * $value[danjia];
			$tmoney += $value[money];
		}
		#合计行
		$i = count($rowset);
		$rowset[$i][chukuNum] = '合计';
		$rowset[$i][cnt] = $tcnt;
		$rowset[$i][money] = $tmoney;


		//模板变量设置
		$smarty = & $this->_getView();
		$arrFieldInfo = array(
			"chukuNum" =>"单号",
			"chukuDate" =>"日期",
			"depName" =>'部门',
			'ylName'=>'产品名称',
			'guige'=>'规格',
			//'color'=>'颜色',
			"cnt" =>'数量',
			'danjia' => '单价',
			'money'=>'金额'
		);

		$smarty->assign('title','原料出库明细');
		$smarty->assign('pk', $this->_mYlChuku->primaryKey);
		$smarty->assign('arr_field_info',$arrFieldInfo);
		//$smarty->assign('add_display', 'none');
		$smarty->assign('arr_condition',$arrGet);
		$smarty->assign('arr_field_value',$rowset);
		$smarty->display('TableList.tpl');
	}
	//出入库流水账
	function actionAccount(){
		$this->authCheck(135);
		FLEA::loadClass('TMIS_Pager');
		$arrGet = TMIS_Pager::getParamArray(array(
			//'date1'=>0,
			"dateFrom" =>date("Y-m-01"),
			"dateTo" =>date("Y-m-d"),
			'key'=>''
		));
		//取得入库数
		$ret = array();
		$ruku="select x.rukuDate as dateRecord,y.cnt as cntRuku,y.ylId,z.ylCode,z.ylName,z.guige,z.unit from cangku_yl_ruku x
		    left join cangku_yl_ruku2yl y on y.rukuId=x.id
			left join jichu_yl z on z.id=y.ylId
		    where 1
		";
		if($arrGet['dateFrom']!='')$ruku.=" and x.rukuDate>='$arrGet[dateFrom]'";
		if($arrGet['dateTo']!='')$ruku.=" and x.rukuDate<='$arrGet[dateTo]'";
		if($arrGet['key']!='') {
			$ruku.=" and( z.ylCode like '%$arrGet[key]%' or z.ylName like '%$arrGet[key]%' or z.guige like '%$arrGet[key]%')";
		}
		$query = mysql_query($ruku) or die(mysql_error());
		while ($re= mysql_fetch_assoc($query)){
			$ret[] = $re;
		}

		//取得出库数
		$chuku="select x.chukuDate as dateRecord,y.cnt as cntChuku,y.ylId,z.ylCode,z.ylName,z.guige,z.unit from cangku_yl_chuku x
		    left join cangku_yl_chuku2yl y on y.chukuId=x.id
			left join jichu_yl z on z.id=y.ylId
		    where 1
		";
		if($arrGet['dateFrom']!='')$chuku.=" and x.chukuDate>='$arrGet[dateFrom]'";
		if($arrGet['dateTo']!='')$chuku.=" and x.chukuDate<='$arrGet[dateTo]'";
		if($arrGet['key']!='') {
			$chuku.=" and( z.ylCode like '%$arrGet[key]%' or z.ylName like '%$arrGet[key]%' or z.guige like '%$arrGet[key]%')";
		}
		$query = mysql_query($chuku) or die(mysql_error());
		while ($re= mysql_fetch_assoc($query)){
			$ret[] = $re;
		}

		$ret = array_column_sort($ret,'dateRecord');
		//dump($ret);exit;

		/*$str="select x.cnt as cntRuku,y.cnt as cntChuku,x.ylId,z.ylCode,z.ylName,z.guige,z.unit from ($ruku) x
		    left join ($chuku) y on x.ylId=y.ylId
		    left join jichu_yl z on z.id=x.ylId
		     where 1";
		if($arrGet['key']!='')$str.=" and( z.ylCode like '%$arrGet[key]%' or z.ylName like '%$arrGet[key]%' or z.guige like '%$arrGet[key]%')";
		//echo $str;exit;
		$pager=& new TMIS_Pager($str);
		$rowset=$pager->findAll();*/
		$arrFieldInfo = array(
			"dateRecord"=>'日期',
			"ylCode"=>"编码",
			"ylName"=>"名称",
			"guige"=>"规格",
			"unit"=>"单位",
			'cntRuku' => '入库数',
			'cntChuku' => '出库数',
		);
		$smarty = & $this->_getView();
		$smarty->assign("arr_field_info",$arrFieldInfo);
		$smarty->assign("title","出入库流水账");
		$smarty->assign("arr_field_value", $ret);
		$smarty->assign("add_display", 'none');
		$smarty->assign('arr_condition',$arrGet);
		//$smarty->assign('page_info',$pager->getNavBar($this->_url('right',$arrGet)));
		$smarty->display("TableList.tpl");
	}
	//超储分析
	function actionOverStock(){
	    $this->authCheck(136);
		FLEA::loadClass('TMIS_Pager');
		$arrGet = TMIS_Pager::getParamArray(array(
			'key'=>''
		));
		//取得入库数
		$ruku="select x.rukuDate,y.cnt,y.ylId from cangku_yl_ruku x
		    left join cangku_yl_ruku2yl y on y.rukuId=x.id
		    where 1
		";
		//取得出库数
		$chuku="select x.chukuDate,y.cnt,y.ylId from cangku_yl_chuku x
		    left join cangku_yl_chuku2yl y on y.chukuId=x.id
		    where 1
		";
		$str="select x.ylId,x.kucunCnt,z.ylCode,z.ylName,z.guige,z.unit,z.cntMax from cangku_yl_init x
		    left join jichu_yl z on z.id=x.ylId
		     where x.kucunCnt>z.cntMax";
		if($arrGet['key']!='')$str.=" and( z.ylCode like '%$arrGet[key]%' or z.ylName like '%$arrGet[key]%' or z.guige like '%$arrGet[key]%')";
		//echo $str;exit;
		$pager=& new TMIS_Pager($str);
		$rowset=$pager->findAll();
		foreach($rowset as & $v){
			$v['stock']=$v['kucunCnt']-$v['cntMax'];
		}

		$arrFieldInfo = array(
			"ylCode"=>"编码",
			"ylName"=>"名称",
			"guige"=>"规格",
			"unit"=>"单位",
			'kucunCnt' => '当前库存',
			'cntMax' => '最高存',
			'stock' => '超储'
		);
		$smarty = & $this->_getView();
		$smarty->assign("arr_field_info",$arrFieldInfo);
		$smarty->assign("title","超储分析");
		$smarty->assign("arr_field_value", $rowset);
		$smarty->assign("add_display", 'none');
		$smarty->assign('arr_condition',$arrGet);
		//$smarty->assign('page_info',$pager->getNavBar($this->_url('right',$arrGet)));
		$smarty->display("TableList.tpl");
	}
	//短缺分析
	function actionShortage(){
	    $this->authCheck(137);
		FLEA::loadClass('TMIS_Pager');
		$arrGet = TMIS_Pager::getParamArray(array(
			'key'=>''
		));
		$str="select x.ylId,x.kucunCnt,z.ylCode,z.ylName,z.guige,z.unit,z.cntMin from cangku_yl_init x
		    left join jichu_yl z on z.id=x.ylId
		     where x.kucunCnt<z.cntMin";
		if($arrGet['key']!='')$str.=" and( z.ylCode like '%$arrGet[key]%' or z.ylName like '%$arrGet[key]%' or z.guige like '%$arrGet[key]%')";
		//echo $str;exit;
		$pager=& new TMIS_Pager($str);
		$rowset=$pager->findAll();
		foreach($rowset as & $v){
		    $v['short']=$v['cntMin']-$v['kucunCnt'];
		}
		//dump($rowset);
		$arrFieldInfo = array(
			"ylCode"=>"编码",
			"ylName"=>"名称",
			"guige"=>"规格",
			"unit"=>"单位",
			'kucunCnt' => '当前库存',
			'cntMin' => '最低存',
			'short' => '短缺'
		);
		$smarty = & $this->_getView();
		$smarty->assign("arr_field_info",$arrFieldInfo);
		$smarty->assign("title","短缺分析");
		$smarty->assign("arr_field_value", $rowset);
		$smarty->assign("add_display", 'none');
		$smarty->assign('arr_condition',$arrGet);
		//$smarty->assign('page_info',$pager->getNavBar($this->_url('right',$arrGet)));
		$smarty->display("TableList.tpl");
	}
	//打印库存报表
	function actionPrint(){
		//dump($_GET);exit;
		$title = '库存报表';
		///////////////////////////////模板文件
		$tpl = 'Cangku/Yl/KucunPrint.tpl';
		///////////////////////////////表头
		$this->authCheck();
		/************构造condition**********/
		$sql = "select x.*,y.kucunCnt,y.kucunMoney from jichu_yl x
			left join cangku_yl_init y on x.id=y.ylId where 1";
		if($_GET['key']!='') $sql .= " and (zhujiCode like '%{$_GET['key']}%' or ylCode like '%{$_GET['key']}%' or ylName like '%{$_GET['key']}%' or guige like '%{$_GET['key']}%')";
		$rowset=$this->_modelExample->findBySql($sql);
		//dump($rowset);exit;
		$rowset[]=$this->getHeji($rowset, array('kucunCnt'), 'ylCode');
		$smarty = & $this->_getView();
		$smarty->assign('title', $title);
		$smarty->assign('aRow',$rowset);
		$smarty->assign('arr_js_css',$this->makeArrayJsCss(array('calendar','thickbox')));
		$smarty-> display($tpl);
	}

}
?>