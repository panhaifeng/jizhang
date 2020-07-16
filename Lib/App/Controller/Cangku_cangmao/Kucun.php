<?php
FLEA::loadClass('TMIS_Controller');
class Controller_Cangku_Kucun extends Tmis_Controller {
	var $_modelExample;
	var $_kind2=1;
	var $_cntField="cnt";
	function Controller_Cangku_Kucun() {
		// $this->_kind2=$_REQUEST['kind'];
		$this->_modelExample = & FLEA::getSingleton('Model_Cangku_Kucun');
		$this->_mProduct = & FLEA::getSingleton('Model_Jichu_Product');
		$this->_mRuku = & FLEA::getSingleton("Model_Cangku_Ruku");
		$this->_mChuku = & FLEA::getSingleton("Model_Cangku_Chuku");
	}

	//库存查询
	function actionRight() {
		redirect($this->_url('month'));
		exit;
		////////////////////////////////标题
		$title = '成品库存报表';
		///////////////////////////////模板文件
		$tpl = 'TableList2.tpl';
		///////////////////////////////表头
		$arr_field_info = array(
			'proCode'=>'产品编码',
			'proName'=>'品名',
			'guige'=>'规格',
			'unit'=>'单位',
			'kucunCnt'=>'库存数',
			'cntCi'=>'次品库存'
			//'kucunMoney'=>'库存金额'
		);
		///////////////////////////////搜索条件
		$arrCon = array(
			'key'=>''
		);
		///////////////////////////////模块定义
		$this->authCheck('3-5');
		FLEA::loadClass('TMIS_Pager');
		$arr = TMIS_Pager::getParamArray($arrCon);

		/************构造condition**********/
		$sql = "select x.*,y.kucunCnt,y.kucunMoney,y.initCnt,y.initMoney,y.cntCi from jichu_product x
			left join cangku_init y on x.id=y.productId where (y.kucunCnt>0 or y.cntCi>0)";
		if($arr['key']!='') {
                    $sql .= " and (zhujiCode like '%{$arr['key']}%' or proCode like '%{$arr['key']}%' or proName like '%{$arr['key']}%' or guige like '%{$arr['key']}%') group by x.proCode order by x.proCode asc";
                } else {
                    $sql .= " group by x.proCode order by x.proCode asc";
                }

		//echo $sql;
		$rowset=$this->_modelExample->findBySql($sql);
		if(count($rowset)>0) foreach($rowset as & $v){
                      $v['kucunCnt']=round($v['kucunCnt'],2);
			$v['kucunCnt']=$v['kucunCnt']+$v['initCnt'];
			$v['kucunMoney']=$v['kucunMoney']+$v['initMoney'];
			$v['cnt']=round($v['cnt'],2);
			$v['cntCi']=round($v['cntCi'],2);

                      
		}
		$heji=$this->getHeji($rowset,array('kucunCnt','cntCi'),'proCode');
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

	function actionMonth1() {
		////////////////////////////////标题
		$title = '原料库存调整';
		///////////////////////////////模板文件
		$tpl = 'TableList.tpl';
		///////////////////////////////表头
		
		///////////////////////////////搜索条件
		$arrCon = array(
			'dateFrom'=>date('Y-m-01'),
			'dateTo'=>date('Y-m-d'),
			'key'=>''
		);
		///////////////////////////////模块定义
		$this->authCheck('3-5');
		FLEA::loadClass('TMIS_Pager');
		$arr = TMIS_Pager::getParamArray($arrCon);

		//期初
		//wkdl的库存没有初始化功能，故不需要考虑init表
		$sql = "select x.productId,sum(x.cnt) as cnt,sum(x.cntCi) cntCi
			from cangku_ruku2product x 
			left join cangku_ruku y on x.rukuId=y.id
			inner join jichu_product z on x.productId=z.id 
			where y.rukuDate<'{$arr['dateFrom']}' and y.kind2={$this->_kind2}";
		if($arr['key']!='') {
			$sql .= " and (z.zhujiCode like '%{$arr['key']}%' or z.proCode like '%{$arr['key']}%' or z.proName like '%{$arr['key']}%' or z.guige like '%{$arr['key']}%')";
		}
		$sql .= " group by x.productId";
		$initRuku = $this->_modelExample->findBySql($sql);
		foreach($initRuku as & $v) {
			$ret[$v['productId']]['initCntRuku'] = $v['cnt'];
			// $ret[$v['productId']]['initLenRuku'] = $v['len'];
			$ret[$v['productId']]['initCntCiRuku'] = $v['cntCi'];
		}


		$sql = "select x.productId,sum(x.cnt) as cnt,sum(x.cntCi) cntCi
			from cangku_chuku2product x 
			left join cangku_chuku y on x.chukuId=y.id
			inner join jichu_product z on x.productId=z.id 
			where y.chukuDate<'{$arr['dateFrom']}' and y.kind2={$this->_kind2}";
		if($arr['key']!='') {
			$sql .= " and (z.zhujiCode like '%{$arr['key']}%' or z.proCode like '%{$arr['key']}%' or z.proName like '%{$arr['key']}%' or z.guige like '%{$arr['key']}%')";
		}
		$sql .= " group by x.productId";
		$initChuku = $this->_modelExample->findBySql($sql);
		foreach($initChuku as & $v) {
			// $ret[$v['productId']]['initChuku'] = $v['cnt'];
			$ret[$v['productId']]['initCntChuku'] = $v['cnt'];
			// $ret[$v['productId']]['initLenChuku'] = $v['len'];
			$ret[$v['productId']]['initCntCiChuku'] = $v['cntCi'];
		}
		//期初为0的过滤掉
		foreach($ret as $k=>& $v) {
			if($v['initCntRuku']==$v['initCntChuku'] && $v['initCntCiRuku']==$v['initCntCiChuku']) continue;
			$ret1[$k] = $v;
		}
		$ret = $ret1;

		//本期入库
		$sql = "select x.productId,sum(x.cnt) as cnt,sum(x.cntCi) cntCi
			from cangku_ruku2product x 
			left join cangku_ruku y on x.rukuId=y.id
			inner join jichu_product z on x.productId=z.id 
			where y.rukuDate>='{$arr['dateFrom']}' and y.rukuDate<='{$arr['dateTo']}' and y.kind2={$this->_kind2}";
		if($arr['key']!='') {
			$sql .= " and (z.zhujiCode like '%{$arr['key']}%' or z.proCode like '%{$arr['key']}%' or z.proName like '%{$arr['key']}%' or z.guige like '%{$arr['key']}%')";
		}
		$sql .= " group by x.productId";
		$ruku = $this->_modelExample->findBySql($sql);
		foreach($ruku as & $v) {
			// $ret[$v['productId']]['cntRuku'] = $v['cnt'];
			$ret[$v['productId']]['cntRuku'] = $v['cnt'];
			// $ret[$v['productId']]['lenRuku'] = $v['len'];
			$ret[$v['productId']]['cntCiRuku'] = $v['cntCi'];
		}

		//本期出库
		$sql = "select x.productId,sum(x.cnt) as cnt,sum(x.cntCi) cntCiRuku
			from cangku_chuku2product x 
			left join cangku_chuku y on x.chukuId=y.id
			inner join jichu_product z on x.productId=z.id 
			where y.chukuDate>='{$arr['dateFrom']}' and y.chukuDate<='{$arr['dateTo']}' and y.kind2 = {$this->_kind2}";
		if($arr['key']!='') {
			$sql .= " and (z.zhujiCode like '%{$arr['key']}%' or z.proCode like '%{$arr['key']}%' or z.proName like '%{$arr['key']}%' or z.guige like '%{$arr['key']}%')";
		}
		$sql .= " group by x.productId";
		$initChuku = $this->_modelExample->findBySql($sql);
		foreach($initChuku as & $v) {
			// $ret[$v['productId']]['cntChuku'] = $v['cnt'];
			$ret[$v['productId']]['cntChuku'] = $v['cnt'];
			// $ret[$v['productId']]['lenChuku'] = $v['len'];
			$ret[$v['productId']]['cntCiChuku'] = $v['cntCi'];
		}

		// dump($ret);exit;
		foreach($ret as $k=>&$v) {			
			$v['cntInit'] = round($v['initCntRuku']-$v['initCntChuku'],2);
			// $v['lenInit'] = round($v['initLenRuku']-$v['initLenChuku'],2);
			$v['cntCiInit'] = round($v['initCntCiRuku']-$v['initCntCiChuku'],2);

			$v['cntKucun'] = round($v['cntInit']+$v['cntRuku']-$v['cntChuku'],2);
			// $v['lenKucun'] = round($v['lenInit']+$v['lenRuku']-$v['lenChuku'],2);
			$v['cntCiKucun'] = round($v['cntCiInit']+$v['cntCiRuku']-$v['cntCiChuku'],2);

			$sql = "select * from jichu_product where id='{$k}'";
			$temp = $this->_modelExample->findBySql($sql);
			// dump($sql);dump($temp);exit;
			$v['productId'] = $k;
			$v['proCode'] = $temp[0]['proCode'];
			$v['proName'] = $temp[0]['proName'];
			$v['guige'] = $temp[0]['guige'];
			$v['unit'] = $temp[0]['unit'];			
		}
		// dump($ret);exit;
		//$ret = array_column_sort($ret,'proCode');
		$ret[] = $this->getHeji($ret,array('cntInit','cntCiInit','cntRuku','cntCiRuku','cntChuku','cntCiChuku','cntKucun','cntCiKucun'),'proCode');
		$arr_field_info = array(
			'proCode'=>'物料编码',
			'proName'=>'名称',
			'guige'=>'规格',
			'unit'=>'单位',
			// 'kucunCnt'=>'期初数',
			// 'kucunMoney'=>'库存金额',
			// '_edit'=>'调整'
			// 'lenInit'=>'期初长度',
			'cntInit'=>'期初数',
			//'cntCiInit'=>'期初次品',

			// 'lenRuku'=>'本期入库长度',
			'cntRuku'=>'本期入库',
			//'cntCiRuku'=>'本期入库次品',

			// 'lenChuku'=>'本期出库长度',
			'cntChuku'=>'本期出库',
			//'cntCiChuku'=>'本期出库次品',

			// 'lenKucun'=>'期末长度',
			'cntKucun'=>'期末库存',
			//'cntCiKucun'=>'期末次品',
		);
		/************构造condition**********/
		// $sql = "select x.*,y.kucunCnt,y.kucunMoney from jichu_product x
		// 	left join cangku_yl_init y on x.id=y.productId where 1";
		// if($arr['key']!='') $sql .= " and (zhujiCode like '%{$arr['key']}%' or proCode like '%{$arr['key']}%' or proName like '%{$arr['key']}%' or guige like '%{$arr['key']}%')";


		// $pager =& new TMIS_Pager($sql);
  //       $rowset =$pager->findAll();
		// if(count($rowset)>0) foreach($rowset as & $v){
		// 	//if($v['initCnt']==0) $v['initCnt']='';
		// 	//if($v['initMoney']==0) $v['initMoney']='';
		// 	///////////////////////////////
		// 	//$this->makeEditable($v,'initCnt');
		// 	//$this->makeEditable($v,'initMoney');
		// 	$v['_edit'] = '<a href="'.$this->_url('change',array(
		// 		'productId'=>$v['id'],
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
       function actionMonth(){
       	$this->authCheck('8-5');
             FLEA::loadClass("TMIS_Pager");
             $arr = & TMIS_Pager::getParamArray(array(
             	"dateFrom"=> date('Y-m-d',mktime(0,0,0,date('m')-1,date('d'),date('Y'))),
             	"dateTo"=> date('Y-m-d'),
             	'supplierId'=>'',
             	'kuweiId'=>'',
             	"key" =>""
             ));
             
             if($arr['key']){
             	$con.=" and (b.proName like '%{$arr['key']}%' or b.guige like '%{{$arr['key']}}%')";
             }
             if($arr['kuweiId']!=''){
             	$con.=" and x.kuweiId='{$arr['kuweiId']}'";
             }
             if($arr['supplierId']!=''){
             	$con.=" and y.supplierId='{$arr['supplierId']}'";
             }
             // if($arr['dateFrom']!=''){
             // 	$con.=" and ";
             // }

             //查找所有在dateTo之前所有库存不为0的ruku2proId，这些数据需要查找库存
             $sql="select * from (select ruku2proId,kucunDate,kucunCnt from cangku_kucun where kucunDate<='{$arr['dateTo']}' order by kucunDate desc) a group by ruku2proId having kucunDate>='{$arr['dateFrom']}' or (kucunDate<'{$arr['dateFrom']}' and kucunCnt<>0)";
             $res=$this->_modelExample->findBySql($sql);
             // dump($sql);exit;
             $id_str=join(',',array_col_values($res,'ruku2proId'));
             if($id_str=='')$id_str='null';

             //查找入库对应的坯纱信息
            $sql="select x.productId,x.kuweiId,y.supplierId,b.proName,b.proCode,b.guige,z.kuweiName,a.compName from cangku_ruku2product x
            	left join cangku_ruku y on y.id=x.rukuId
            	left join jichu_kuwei z on z.id=x.kuweiId
            	left join jichu_product b on b.id=x.productId
            	left join jichu_supplier a on a.id=y.supplierId
            	where x.id in ($id_str)";
            $sql.=$con;
            $sql.=" group by y.supplierId,x.productId,x.kuweiId order by y.supplierId,x.productId,x.kuweiId";
	        // dump($sql);exit;
             $pager = & new TMIS_Pager($sql);
             $rowset = $pager->findAll();
             foreach($rowset as & $v){
             	//查找期初入库信息
             	$sql="select sum(x.cnt) as cnt from cangku_ruku2product x
             		left join cangku_ruku y on y.id=x.rukuId
             		where y.rukuDate < '{$arr['dateFrom']}' 
             		and y.supplierId='{$v['supplierId']}' 
             		and x.kuweiId='{$v['kuweiId']}'
             		and x.productId='{$v['productId']}'";
             		// echo $sql;exit;
             	$temp=mysql_fetch_assoc(mysql_query($sql));
             	$v['cntInit']=$temp['cnt']==0?'':round($temp['cnt'],4);
             	// $v['cntInit']=$v['cntInit']==0?'':round($v['cntInit'],4);

             	//查找期初出库信息
             	$sql="select sum(a.cnt) as cnt from cangku_chuku2product a
             		left join cangku_chuku b on b.id=a.chukuId
             		left join cangku_ruku2product x on x.id=a.ruku2proId
             		left join cangku_ruku y on y.id=x.rukuId
             		where b.chukuDate < '{$arr['dateFrom']}' 
             		and y.supplierId='{$v['supplierId']}' 
             		and x.kuweiId='{$v['kuweiId']}'
             		and x.productId='{$v['productId']}'";
             		// echo $sql;exit;
             	$temp=mysql_fetch_assoc(mysql_query($sql));
             	$v['cntInit']-=$temp['cnt'];
             	$v['cntInit']=$v['cntInit']==0?'':round($v['cntInit'],4);

             	//查找本期入库
             	$sql="select sum(x.cnt) as cnt from cangku_ruku2product x
             		left join cangku_ruku y on y.id=x.rukuId
             		where y.rukuDate >= '{$arr['dateFrom']}' and y.rukuDate <= '{$arr['dateTo']}'
             		and y.supplierId='{$v['supplierId']}' 
             		and x.kuweiId='{$v['kuweiId']}'
             		and x.productId='{$v['productId']}'";
             		// echo $sql;exit;
             	$temp=mysql_fetch_assoc(mysql_query($sql));
             	$v['cntRuku']=$temp['cnt']==0?'':round($temp['cnt'],4);
             	$v['cntRuku2']=$v['cntRuku'];

             	//查找本期出库
             	$sql="select sum(a.cnt) as cnt from cangku_chuku2product a
             		left join cangku_chuku b on b.id=a.chukuId
             		left join cangku_ruku2product x on x.id=a.ruku2proId
             		left join cangku_ruku y on y.id=x.rukuId
             		where b.chukuDate >= '{$arr['dateFrom']}' and b.chukuDate <= '{$arr['dateTo']}' 
             		and y.supplierId='{$v['supplierId']}' 
             		and x.kuweiId='{$v['kuweiId']}'
             		and x.productId='{$v['productId']}'";
             		// echo $sql;exit;
             	$temp=mysql_fetch_assoc(mysql_query($sql));
             	$v['cntChuku']=$temp['cnt']==0?'':round($temp['cnt'],4);
             	$v['cntChuku2']=$v['cntChuku'];

             	$v['cntKucun']=$v['cntInit']+$v['cntRuku2']-$v['cntChuku2'];


             	//查询明细
             	$v['cntRuku']="<a href='".url('Cangku_Ruku','right',array(
             		'supplierId'=>$v['supplierId'],
             		'productId'=>$v['productId'],
             		'kuweiId'=>$v['kuweiId'],
             		'width'=>'800',
             		'no_edit'=>1,
             		'TB_iframe'=>1,
             	))."' class='thickbox' title='入库明细'>{$v['cntRuku']}</a>";

             	$v['cntChuku']="<a href='".url('Cangku_Chuku','right',array(
             		'supplierId2'=>$v['supplierId'],
             		'productId'=>$v['productId'],
             		'kuweiId'=>$v['kuweiId'],
             		'width'=>'800',
             		'no_edit'=>1,
             		'TB_iframe'=>1,
             	))."' class='thickbox' title='入库明细'>{$v['cntChuku']}</a>";
             }

             $heji=$this->getHeji($rowset,array('cntRuku2','cntChuku2','cntInit','cntKucun'),'compName');
             $heji['cntRuku']=$heji['cntRuku2'];
             $heji['cntChuku']=$heji['cntChuku2'];
             $rowset[]=$heji;
             $arr_field_info = array(
				'compName'=>array('text'=>'供应商','width'=>200),
				'proName'=>'名称',
				'guige'=>'规格',
				'kuweiName'=>'库位',
				'cntInit'=>'期初数',
				'cntRuku'=>'本期入库',
				'cntChuku'=>'本期出库',
				'cntKucun'=>'期末库存'
			);

             $smarty= $this->_getView();
             $smarty->assign("arr_condition",$arr);
             $smarty->assign("arr_field_info",$arr_field_info);
             $smarty->assign("arr_field_value",$rowset);
             $smarty->assign("page_info",$pager->getNavBar($this->_url("month",$arr)));
             $smarty->assign("add_display","none");
             $smarty->display("TableList.tpl");
       }
       //月末结转并保存加权单价
       function actionComputeDanjia(){
	       $this->authCheck('8-6');
	       $dateFrom = date('Y-m-d',mktime(0,0,0,date('m'),'01',date('Y')));
	       $dateTo = date('Y-m-d',mktime(0,0,0,date('m')+1,0,date('Y')));
	       $htmlstr="结转目的:<br/>
	       1,对出库记录生成加权平均单价,从而生成领用金额<br/>
	       2,结转后，将锁定账期，禁止在锁定的账期内修改删除或者新增记录<br/>
	       3,如果需要对已锁定账期的记录进行修改，只能使用红冲记录来进行调账<br/>
	       <br/>结转步骤:<br/>
	       1,确认要结转的账期,当前需要结转的账期为:".$dateFrom."至". $dateTo."<br/>
	       2,<a href='".$this->_url("saveJqDanjia",array('dateFrom'=>$dateFrom,'dateTo'=>$dateTo))."'>生成加权单价</a><br/>
	       3,锁定账期";
	       echo $htmlstr;
       }
       function actionSaveJqDanjia(){
             $sql = "select group_concat(y.id) as ids,sum(y.danjia*y.cnt) as money,round(sum(y.danjia*y.cnt)/sum(cnt),2) as jqdj from cangku_ruku x 
             left join cangku_ruku2product y on x.id=y.rukuId
             where kind2={$this->_kind2} and rukuDate>='{$_REQUEST['dateFrom']}'
             and rukuDate<='{$_REQUEST['dateTo']}' and rukuType<>1
             ";
             $ret = $this->_modelExample->findBySql($sql);
             $sql="update cangku_ruku2product set WACP='{$ret[0]['jqdj']}' where id in ({$ret[0]['ids']})";
             mysql_query($sql) or die(mysql_error());  
             //算出加权平均单价后保存
             $sql = "update cangku_chuku2product x 
             left join cangku_chuku y on x.chukuId= y.id
             set WACP='{$ret[0]['jqdj']}',danjia='{$ret[0]['jqdj']}' 
             where y.chukuDate>='{$_REQUEST['dateFrom']}' 
             and y.chukuDate<='{$_REQUEST['dateTo']}' 
             and kind2={$this->_kind2}
             ";
             mysql_query($sql) or die(mysql_error());  
             echo "操作完成";

       }
	//初始化
	function actionInit() {
		////////////////////////////////标题
		$title = '成品库存初始化';
		///////////////////////////////模板文件
		$tpl = 'TableList2.tpl';
		///////////////////////////////表头
		$arr_field_info = array(
			'proCode'=>'物料编码',
			'proName'=>'品名',
			'guige'=>'规格',
			'initCnt'=>'初始库存',
			'initMoney'=>'初始金额'
		);
		///////////////////////////////搜索条件
		$arrCon = array(
			'key'=>''
		);
		///////////////////////////////模块定义
		$this->authCheck(139);
		FLEA::loadClass('TMIS_Pager');
		$arr = TMIS_Pager::getParamArray($arrCon);

		/************构造condition**********/
		$sql = "select x.*,y.initCnt,y.initMoney from jichu_product x
			left join cangku_init y on x.id=y.productId where x.kind=1";
		if($arr['key']!='') $sql .= " and (zhujiCode like '%{$arr['key']}%' or proCode like '%{$arr['key']}%' or proName like '%{$arr['key']}%' or guige like '%{$arr['key']}%')";


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
		$row['productId'] = $_GET['id'];
		$row[$_GET['fieldName']] = $_GET['value'];
		$sql = "select id,count(*) cnt from cangku_init where productId='{$_GET['id']}'";
		//echo $sql;
		$re = mysql_fetch_assoc(mysql_query($sql));
		if ($re['cnt']>0){
			$row['id'] = $re['id'];
		}
		//dump($row);exit;
		if ($this->_modelExample->save($row)) {
			$this->_modelExample->changeKucun($row['productId']);
			echo "{successful:true,msg:'成功!'}";
		} else {
			echo "{successful:false,msg:'出错!'}";
		}
		exit;
	}

	//库存调整
	function actionChangeKucun() {
		////////////////////////////////标题
		$title = '成品库存调整';
		///////////////////////////////模板文件
		$tpl = 'TableList2.tpl';
		///////////////////////////////表头
		$arr_field_info = array(
			'proCode'=>'产品编码',
			'proName'=>'品名',
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
		$this->authCheck(148);
		FLEA::loadClass('TMIS_Pager');
		$arr = TMIS_Pager::getParamArray($arrCon);

		/************构造condition**********/
		$sql = "select x.*,y.kucunCnt,y.kucunMoney,y.id as kucunId from jichu_product x
			left join cangku_init y on x.id=y.productId where x.kind=1";
		if($arr['key']!='') $sql .= " and (zhujiCode like '%{$arr['key']}%' or proCode like '%{$arr['key']}%' or proName like '%{$arr['key']}%' or guige like '%{$arr['key']}%')";


		$pager =& new TMIS_Pager($sql);
        $rowset =$pager->findAll();
		if(count($rowset)>0) foreach($rowset as & $v){
			//if($v['initCnt']==0) $v['initCnt']='';
			//if($v['initMoney']==0) $v['initMoney']='';
			///////////////////////////////
			//$this->makeEditable($v,'initCnt');
			//$this->makeEditable($v,'initMoney');
			$v['_edit'] = '<a href="'.$this->_url('change',array(
				'productId'=>$v['id'],
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

	function actionChange() {
		$sql = "select x.*,y.kucunCnt,y.kucunMoney,y.id as kucunId from jichu_product x
			left join cangku_init y on x.id=y.productId where x.id='{$_GET['productId']}'";
		$re = mysql_fetch_assoc(mysql_query($sql));
		$tpl='Cangku/ChangeKucun.tpl';
		$smarty = & $this->_getView();
		$smarty->assign('row',$re);
		$smarty->display($tpl);
	}

	function actionChangeSave() {
		//dump($_POST);exit;
		$sql = "select * from cangku_init where id='{$_POST['kucunId']}'";
		$re = mysql_fetch_assoc(mysql_query($sql));
		//dump($re);
		$cnt = $re['kucunCnt']-$_POST['kucunCnt'];
		//echo $cnt;exit;
		if($cnt!=0) $danjia = ($re['kucunMoney']-$_POST['kucunMoney'])/$cnt;
		else $danjia=0;
		//echo $danjia;exit;
		//生成库存调整记录,作为其他出库
		$row=array(
			'kind'			=>1,
			'memo'		=>'库存调整',
			'chukuDate'	=>date("Y-m-d"),
			'Pro'		=>array(
				array(
					'productId' => $_POST[productId],
					'cnt' => $cnt,
					'danjia' => $danjia
				)
			)
		);
		//dump($row);exit;
		$m = & FLEA::getSingleton("Model_Cangku_Chuku");
		$m->create($row);

		//改变库存
		$m->changeKucun($_POST['productId']);
		js_alert('调整成功!调库记录在其他出库中进行查询!','window.parent.location.href=window.parent.location.href;window.parent.tb_remove();');
	}

	//月报表
	// function actionMonth() {
	// 	////////////////////////////////标题
	// 	$title = '收发存汇总表';
	// 	///////////////////////////////模板文件
	// 	$tpl = 'TableList2.tpl';
	// 	///////////////////////////////表头
	// 	$arr_field_info = array(
	// 		'proCode'=>'物料编码',
	// 		'proName'=>'品名',
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
	// 		'dateFrom'=>date('Y-m-01'),
	// 		'dateTo'=>date('Y-m-d'),
	// 		'key'=>''
	// 	);
	// 	///////////////////////////////模块定义
	// 	$this->authCheck(152);
	// 	FLEA::loadClass('TMIS_Pager');
	// 	$arr = TMIS_Pager::getParamArray($arrCon);

	// 	/************构造condition**********/
	// 	$sql = "select x.*,y.initCnt,y.initMoney from cangku_init y left join jichu_product x on x.id=y.productId where 1";
	// 	if($arr['key']!='') $sql .= " and (zhujiCode like '%{$arr['key']}%' or proCode like '%{$arr['key']}%' or proName like '%{$arr['key']}%' or guige like '%{$arr['key']}%')";
	// 	$pager =& new TMIS_Pager($sql);
 //        $rowset =$pager->findAll();
	// 	//dump($rowset);
	// 	if(count($rowset)>0) foreach($rowset as & $v){
	// 		//得到期初
	// 		$sql = "select sum(x.cnt) as cnt,sum(x.cnt*x.danjia) as money from cangku_ruku2product x
	// 			left join cangku_ruku y on x.rukuId=y.id where x.productId='{$v['id']}' and y.rukuDate<'{$arr['dateFrom']}'";
	// 		$r = mysql_fetch_assoc(mysql_query($sql));
	// 		$sql = "select sum(x.cnt) as cnt,sum(x.cnt*x.danjia) as money from cangku_chuku2product x
	// 			left join cangku_chuku y on x.chukuId=y.id where x.productId='{$v['id']}' and y.chukuDate<'{$arr['dateFrom']}'";
	// 		$r1 = mysql_fetch_assoc(mysql_query($sql));
	// 		$v['cntInit'] = $v['initCnt']+$r['cnt']-$r1['cnt'];
	// 		$v['moneyInit'] = $v['initMoney']+$r['money']-$r1['money'];

	// 		//得到本月入库
	// 		$sql = "select sum(x.cnt) as cnt,sum(x.cnt*x.danjia) as money from cangku_ruku2product x
	// 			left join cangku_ruku y on x.rukuId=y.id where x.productId='{$v['id']}' and y.rukuDate>='{$arr['dateFrom']}' and y.rukuDate<='{$arr['dateTo']}'";
	// 		$r = mysql_fetch_assoc(mysql_query($sql));
	// 		$v['cntRuku'] = $r['cnt']+0;
	// 		$v['moneyRuku'] = $r['money']+0;

	// 		//本月出库
	// 		$sql = "select sum(x.cnt) as cnt,sum(x.cnt*x.danjia) as money from cangku_chuku2product x
	// 			left join cangku_chuku y on x.chukuId=y.id where x.productId='{$v['id']}' and y.chukuDate>='{$arr['dateFrom']}' and y.chukuDate<='{$arr['dateTo']}'";
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
	// 			'productId'=>$v['id']
	// 		))."' title='入库明细' target='_blank'>{$v['cntRuku']}</a>";

	// 		$v['cntChuku'] = "<a href='".$this->_url('showChuku',array(
	// 			'dateFrom'=>$arr['dateFrom'],
	// 			'dateTo'=>$arr['dateTo'],
	// 			'productId'=>$v['id']
	// 		))."' target='_blank'>{$v['cntChuku']}</a>";
	// 	}

	// 	$rowset[] = $this->getHeji($rowset,array('moneyInit','moneyRuku','moneyChuku','moneyKucun'),'proCode');
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
				a.rukuId,
				x.rukuNum,
				x.rukuDate,
				a.cnt,
				a.danjia,
				a.money,
				x.memo,
				z.proName,
				z.guige,
				z.unit from cangku_ruku x
				left join cangku_ruku2product a on x.id=a.rukuId
				left join jichu_product z on a.productId=z.id where a.productId='{$_GET['productId']}' and x.rukuDate>='{$_GET['dateFrom']}' and x.rukuDate<='{$_GET['dateTo']}'";
		//if ($arrGet['supplierId'] != '')  $str .=" and x.supplierId='$arrGet[supplierId]'";
		//if ($arrGet['orderType'] != 0) $str .=" and orderType = $arrGet[orderType]";
		if ($arrGet['key'] != '')  $str .= " and (x.memo like '%$arrGet[key]%'
											or z.proName like '%$arrGet[key]%'
											or z.guige like '%$arrGet[key]%')";
		$str .= " order by rukuDate desc, rukuNum desc";

		$rowset = $this->_mRuku->findBySql($str);
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
			'proName'=>'产品名称',
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
			a.compName,
			z.proName,
			z.guige,
			z.unit from cangku_chuku y
			left join cangku_chuku2product x on y.id=x.chukuId
			left join jichu_product z on x.productId=z.id
			left join jichu_client a on y.clientId=a.id
			where x.productId='{$_GET['productId']}' and y.chukuDate>='{$_GET['dateFrom']}' and y.chukuDate<='{$_GET['dateTo']}'";

		if ($arrGet['depId'] != '')  $str .= " and y.depId='$arrGet[depId]'";
		if ($arrGet[key] != '') {
			$str .= " and (z.proName like '%$arrGet[key]%'
						or z.guige like '%$arrGet[key]%')";
		}
		$str .= " order by y.chukuDate desc";

		$rowset = $this->_mChuku->findBySql($str);
		if (count($rowset)>0) foreach($rowset as & $value) {
			if($value['chukuNum']=='') $value['chukuNum'] = $value['memo'];
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
			"compName" =>'客户',
			'proName'=>'产品名称',
			'guige'=>'规格',
			//'color'=>'颜色',
			"cnt" =>'数量',
			'danjia' => '单价',
			'money'=>'金额'
		);

		$smarty->assign('title','成品出库明细');
		$smarty->assign('pk', $this->_mProductChuku->primaryKey);
		$smarty->assign('arr_field_info',$arrFieldInfo);
		//$smarty->assign('add_display', 'none');
		$smarty->assign('arr_condition',$arrGet);
		$smarty->assign('arr_field_value',$rowset);
		$smarty->display('TableList.tpl');
	}

	//出入库流水账
	function actionAccount(){
		$this->authCheck(153);
		FLEA::loadClass('TMIS_Pager');
		$arrGet = TMIS_Pager::getParamArray(array(
			//'date1'=>0,
			"dateFrom" =>date("Y-m-01"),
			"dateTo" =>date("Y-m-d"),
			'key'=>''
		));
		//取得入库数
		$ret = array();
		$ruku="select x.rukuDate as dateRecord,y.cnt as cntRuku,y.productId,z.proCode,z.proName,z.guige,z.unit from cangku_ruku x
		    left join cangku_ruku2product y on y.rukuId=x.id
			left join jichu_product z on z.id=y.productId
		    where 1
		";
		if($arrGet['dateFrom']!='')$ruku.=" and x.rukuDate>='$arrGet[dateFrom]'";
		if($arrGet['dateTo']!='')$ruku.=" and x.rukuDate<='$arrGet[dateTo]'";
		if($arrGet['key']!='') {
			$ruku.=" and( z.proCode like '%$arrGet[key]%' or z.proName like '%$arrGet[key]%' or z.guige like '%$arrGet[key]%')";
		}
		$query = mysql_query($ruku) or die(mysql_error());
		while ($re= mysql_fetch_assoc($query)){
			$ret[] = $re;
		}

		//取得出库数
		$chuku="select x.chukuDate as dateRecord,y.cnt as cntChuku,y.productId,z.proCode,z.proName,z.guige,z.unit from cangku_chuku x
		    left join cangku_chuku2product y on y.chukuId=x.id
			left join jichu_product z on z.id=y.productId
		    where 1
		";
		if($arrGet['dateFrom']!='')$chuku.=" and x.chukuDate>='$arrGet[dateFrom]'";
		if($arrGet['dateTo']!='')$chuku.=" and x.chukuDate<='$arrGet[dateTo]'";
		if($arrGet['key']!='') {
			$chuku.=" and( z.proCode like '%$arrGet[key]%' or z.proName like '%$arrGet[key]%' or z.guige like '%$arrGet[key]%')";
		}
		$query = mysql_query($chuku) or die(mysql_error());
		while ($re= mysql_fetch_assoc($query)){
			$ret[] = $re;
		}

		$ret = array_column_sort($ret,'dateRecord');
		$arrFieldInfo = array(
			"dateRecord"=>'日期',
			"proCode"=>"编码",
			"proName"=>"品名",
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
	    $this->authCheck(154);
		FLEA::loadClass('TMIS_Pager');
		$arrGet = TMIS_Pager::getParamArray(array(
			'key'=>''
		));
		$str="select x.productId,x.kucunCnt,z.proCode,z.proName,z.guige,z.unit,z.cntMax from cangku_init x
		    left join jichu_product z on z.id=x.productId
		     where x.kucunCnt>z.cntMax";
		if($arrGet['key']!='')$str.=" and( z.proCode like '%$arrGet[key]%' or z.proName like '%$arrGet[key]%' or z.guige like '%$arrGet[key]%')";
		//echo $str;exit;
		$pager=& new TMIS_Pager($str);
		$rowset=$pager->findAll();
		foreach($rowset as & $v){
			$v['stock']=$v['kucunCnt']-$v['cntMax'];
		}

		$arrFieldInfo = array(
			"proCode"=>"编码",
			"proName"=>"品名",
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
	    $this->authCheck(155);
		FLEA::loadClass('TMIS_Pager');
		$arrGet = TMIS_Pager::getParamArray(array(
			'key'=>''
		));
		$str="select x.productId,x.kucunCnt,z.proCode,z.proName,z.guige,z.unit,z.cntMin from cangku_init x
		    left join jichu_product z on z.id=x.productId
		     where x.kucunCnt<z.cntMin";
		if($arrGet['key']!='')$str.=" and( z.proCode like '%$arrGet[key]%' or z.proName like '%$arrGet[key]%' or z.guige like '%$arrGet[key]%')";
		//echo $str;exit;
		$pager=& new TMIS_Pager($str);
		$rowset=$pager->findAll();
		foreach($rowset as & $v){
		    $v['short']=$v['cntMin']-$v['kucunCnt'];
		}
		//dump($rowset);
		$arrFieldInfo = array(
			"proCode"=>"编码",
			"proName"=>"品名",
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
		$title = '成品库存报表';
		///////////////////////////////模板文件
		$tpl = 'Cangku/KucunPrint.tpl';
		///////////////////////////////表头
		$arr_field_info = array(
			'proCode'=>'产品编码',
			'proName'=>'品名',
			'guige'=>'规格',
			'unit'=>'单位',
			'kucunCnt'=>'库存数',
			'cntCi'=>'次品库存'
		);
		$this->authCheck();
		FLEA::loadClass('TMIS_Pager');
		$arr = TMIS_Pager::getParamArray($arrCon);

		/************构造condition**********/
		$sql = "select x.*,y.kucunCnt,y.kucunMoney,y.initCnt,y.initMoney,y.cntCi from jichu_product x
			left join cangku_init y on x.id=y.productId where 1";
		if($_GET['key']!='') $sql .= " and (zhujiCode like '%{$_GET['key']}%' or proCode like '%{$_GET['key']}%' or proName like '%{$_GET['key']}%' or guige like '%{$_GET['key']}%')";

		//echo $sql;
		$rowset=$this->_modelExample->findBySql($sql);
		if(count($rowset)>0) foreach($rowset as & $v){
			$v['kucunCnt']=$v['kucunCnt']+$v['initCnt'];
			$v['kucunMoney']=$v['kucunMoney']+$v['initMoney'];
			$v['cnt']=round($v['cnt'],2);
			$v['cntCi']=round($v['cntCi'],2);
		}
		$rowset[]=$this->getHeji($rowset, array('kucunCnt','cntCi'), 'proCode');
		//dump($rowset);exit;
		$smarty = & $this->_getView();
		$smarty->assign('title', $title);
		$smarty->assign('add_display','none');
		$smarty->assign('print_display','print');
		$smarty->assign('arr_field_info',$arr_field_info);
		$smarty->assign('aRow',$rowset);
		$smarty->assign('arr_condition', $arr);

		/*///////////////grid,thickbox,///////////////*/
		$smarty->assign('arr_js_css',$this->makeArrayJsCss(array('calendar','thickbox')));
		//$smarty->assign('page_info',$pager->getNavBar($this->_url($_GET['action'],$arr)));
		$smarty-> display($tpl);
	}
}
?>