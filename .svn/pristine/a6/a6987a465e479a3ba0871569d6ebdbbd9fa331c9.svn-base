<?php
FLEA::loadClass('TMIS_Controller');
class Controller_Sample_Caiyang extends Tmis_Controller{
	function Controller_Sample_Caiyang(){
	    $this->_modelExample=& FLEA::getSingleton('Model_Sample_Caiyang');

	}

	function actionRight(){
	    $this->authCheck('51-5');
	    $title='采样查询';
	    FLEA::loadClass('TMIS_Pager');
	    $arr = TMIS_Pager::getParamArray(array(
			'dateFrom'=>date('Y-m-d',mktime(0,0,0,date('m'),date('d')-30,date('Y'))),
			'dateTo'=>date('Y-m-d'),
			'caiyangren'=>'',
			'clientId'=>'',
			'proCode'=>'',
			'guige'=>'',
	    ));
	    $sql="select x.*,y.proCode,y.guige,y.color,y.menfu,y.chengfen,y.kezhong,y.gexing,a.employName from sample_caiyang x
		left join sample_db y on x.sampleId=y.id
		left join jichu_employ a on x.caiyangren=a.employCode
		left join jichu_client b on x.clientName=b.compName
		where y.isXiajia=1";
	    if($arr['dateFrom']!='')$sql.=" and x.chukuDate >='{$arr['dateFrom']}' ";
	    if($arr['dateTo']!='')  $sql.=" and x.chukuDate <='{$arr['dateTo']}' ";
	    if($arr['caiyangren']!='')  $sql.=" and x.caiyangren='{$arr['caiyangren']}' ";
	    if($arr['clientId']!='')  $sql.=" and b.id='{$arr['clientId']}' ";
	    if($arr['proCode']!='')  $sql.=" and y.proCode like '%{$arr['proCode']}%' ";
	    if($arr['guige']!='')  $sql.=" and y.guige like '%{$arr['guige']}%' ";
	    $sql.=" order by id desc";
	    $pager=& new TMIS_Pager($sql);
	    $rowset=$pager->findAll();

	    $arr_field_info=array(
			'_edit'  => '操作',
			'proCode'=>'条码',
			'guige'=>'规格',
			'color'=>array('text'=>'颜色','width'=>70),
			'chengfen'=>array('text'=>'成分','width'=>100),
			'menfu'=>array('text'=>'门幅','width'=>70),
			'kezhong'=>array('text'=>'克重','width'=>70),
			'cntJian'=>array('text'=>'件数','width'=>70),
			'cntM'=>array('text'=>'米数','width'=>70),
			'ganghao'=>array('text'=>'缸号','width'=>70),
			'employName'=>array('text'=>'采样人','width'=>70),
			'clientName'=>'客户名称',
			'chukuCnt'=>array('text'=>'采样数量(KG)','width'=>90),
			'danjia'=>array('text'=>'单价','width'=>70),
			'money'=>array('text'=>'金额','width'=>70),
			'yunfei'=>array('text'=>'运费','width'=>70),
			'chukuDate'=>array('text'=>'采样日期','width'=>80),
			'creater'=>array('text'=>'创建人','width'=>70),
			'memo'=>'备注',

	    );
	    foreach($rowset as & $v){
	    	$v['danjia'] = round($v['danjia'],6);
	    	$v['money'] = round($v['money'],6);
	    	$v['yunfei'] = round($v['yunfei'],6);
				$v['shazhi']="<strong>".$v['shazhi']."</strong>";
				if($v['employName'] == ''){
					$v['_edit']="修改&nbsp;&nbsp;".$this->getRemoveHtml(array(
								'id'=>$v['id'],
								'fromAction'=>$_GET['action']
				    ));
				}else{
					$v['_edit']=$this->getEditHtml(array(
								'id'=>$v['id'],
								'fromAction'=>$_GET['action']
				     ))."&nbsp;&nbsp;".$this->getRemoveHtml(array(
								'id'=>$v['id'],
								'fromAction'=>$_GET['action']
				    ));
				}

	    }
	    $rowset[] = $this->getHeji($rowset,array('chukuCnt','cntJian','cntM','money','yunfei'),'_edit');
	    $smarty = & $this->_getView();
	    $smarty->assign('arr_field_value',$rowset);
	    $smarty->assign('arr_field_info',$arr_field_info);
	    $smarty->assign('title',$title);
	    $smarty->assign('arr_condition', $arr);
	    $smarty->assign('add_display',array());
	    $smarty->assign('arr_js_css',$this->makeArrayJsCss(array('calendar')));
	    $smarty->assign('page_info',$pager->getNavBar($this->_url($_GET['action'],$arr)));
	    $smarty->display('TableList.tpl');
	}
	function actionSave(){
	    //dump($_POST);exit;
	    if($_POST['sampleId']==''){
				js_alert('没有条码信息!','window.history.go(-1)');exit;
	    }

	    if($_POST['chukuDate']=='0000-00-00')$_POST['chukuDate']=date('Y-m-d');
	    //创建人默认操作员
	    $_POST['creater']=$_SESSION['REALNAME'];

	    // $str="select kucunCnt FROM sample_db WHERE id ='{$_POST['sampleId']}' ";
	    // $rr=mysql_fetch_assoc(mysql_query($str));
	    // $str2="select sum(chukuCnt) as cnt FROM sample_caiyang WHERE  sampleId ='{$_POST['sampleId']}' and id<>{$_POST['id']}";
	    // $rs=mysql_fetch_assoc(mysql_query($str2));
	    // $rs['cnt']+=$_POST['chukuCnt'];
	    // if($rr['kucunCnt']<$rs['cnt'])js_alert('采样数量不能大于库存数','',$this->_url('add'));

	    $id = $this->_modelExample->save($_POST);
	    if($id>0){
		  	if($_POST['submit']=='保存并返回'){
					js_alert(null,"window.parent.showMsg('保存成功!')",$this->_url('right'));
		  	}else{
					js_alert(null,"window.parent.showMsg('保存成功!')",$this->_url('add'));
		  		}
	    }else die("保存失败！");
	}
	function _edit($arr){
	    $this->authCheck('51-4');
	    // dump($arr);
	    if(is_array($arr)){
				$sql="select sum(chukuCnt) as cnt from sample_caiyang where proCode='{$arr['proCode']}' and id<>{$arr['id']}";
				$s=mysql_fetch_assoc(mysql_query($sql));
				// dump($sql);

				//查找总入库数
				$sql="select sum(kucunCnt) as cnt from sample_db where proCode='{$arr['proCode']}'";
				$t=mysql_fetch_assoc(mysql_query($sql));
				// dump($t);exit;

				// if(is_array($s))
			    $arr['sampleInfo']['kucunCnt']=$t['cnt']-$s['cnt'];
	    }

	    $arr['danjia'] = round($arr['danjia'],6);
	    $arr['money'] = round($arr['money'],6);
	    $arr['yunfei'] = round($arr['yunfei'],6);
	     // dump($arr);exit;
	    $smarty = & $this->_getView();
	    $smarty->assign('rowset',$arr);
	    $smarty->display('Sample/CaiyangEdit.tpl');
	}

	function actionTongji(){
		// dump($_GET);exit;
		// dump($_POST);exit;
	   $this->authCheck('51-6');
	   // 搜索与导出的时间
	   if(count($_POST)==0&&$_GET['dateFrom']==''){
	       $_POST['dateFrom']=date('Y-m-01');
	       $_POST['dateTo']=date('Y-m-d');
	   }else if($_GET['dateFrom']!=''){
	   	   $_POST['dateFrom']=$_GET['dateFrom'];
	   	   $_POST['dateTo']=$_GET['dateTo'];
	   }
	   // dump($_POST);exit;
	    $str="select distinct caiyangren from sample_caiyang where 1 ";
	    if($_POST['dateFrom']!='') $str.=" and chukuDate >='{$_POST['dateFrom']}' ";
	    if($_POST['dateTo']!='')   $str.=" and chukuDate <='{$_POST['dateTo']}' ";
	    $str.=" and caiyangren!='000' order by id desc";
	    $result=mysql_query($str);
	    while($arr=mysql_fetch_array($result)){
		 $code[]=$arr['caiyangren'];
	    }
	   // dump($code);exit;
	    foreach($code as $k=> & $v){
		 $sql="select x.*,a.guige,a.menfu,a.kezhong,a.chengfen,a.color,a.proCode,a.kucunWei,y.employName from sample_caiyang x
		    left join sample_db a on x.sampleId=a.id
		    left join jichu_employ y on x.caiyangren=y.employCode
		    where x.caiyangren='{$v}' ";
		 if($_POST['dateFrom']!='') $sql.=" and x.chukuDate >='{$_POST['dateFrom']}' ";
		 if($_POST['dateTo']!='')   $sql.=" and x.chukuDate <='{$_POST['dateTo']}' ";
		 $sql.=" order by x.id desc";
		 $rows=null;
		 $name=null;
		 $res=mysql_query($sql);
		 while($aRow=mysql_fetch_assoc($res)){
		    if($aRow['isTanli']==1)$aRow['isTanli']='是';
		    else $aRow['isTanli']='否';

		    $aRow['money'] = round($aRow['money'],6);
	    	$aRow['yunfei'] = round($aRow['yunfei'],6);
	    	$aRow['memo'] = $aRow['memo'];

		    $rows[]=$aRow;
		    $name=$aRow['employName'];
		 }
		 $rowset[]=array(
		     'employName'=>$name,
		     'caiyangInfo'=>$rows
		 );
	     }
	     $arr=array(
	     	dateFrom=>$_POST['dateFrom'],
	        dateTo=>$_POST['dateTo'],
	     );
	     // dump($arr);exit;
	     $arrCol = array(
	     	'chukuDate'=>'日期',
	     	'proCode'=>'产品编号',
	     	'guige'=>'规格',
	     	'color'=>'颜色',
	     	'menfu'=>'门幅',
	     	'kezhong'=>'克重',
	     	'clientName'=>'客户',
	     	'chukuCnt'=>'采样数量',
	     	'money'=>'金额',
	     	'yunfei'=>'运费',
	     	'memo'=>'备注',
	     	);
	     // dump($rowset);exit;
	    $smarty = & $this->_getView();
	    $smarty->assign('title','采样统计');
	    $smarty->assign('arr_condition',$arr);
	    $smarty->assign('fn_export','fn_export');
		// $smarty->assign('page_info',$pager->getNavBar($this->_url('right')));
		$smarty->assign('fn_export',$this->_url($_GET['action'],array('export'=>1)));
		if($_GET['export']!=1) {
	    $smarty->assign('rowset',$rowset);
		$smarty->display('sample/CaiyangTongji.tpl');
		} else {
			//处理导出操作
			foreach ($rowset as & $value) {
				foreach ($value['caiyangInfo'] as & $v) {
					$arrRow[]=$v;
				}
			}
			$arrCol['employName']='采样人';
			$smarty->assign('arr_field_value',$arrRow);
	    	$smarty->assign('arr_field_info',$arrCol);
			header("Content-type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=test.xls");
			$smarty->display('Export2Excel.tpl');
		}
	}

	function actionSetCaiyangren(){
	   $this->authCheck('51-11');
		$sonTpl="Sample/SetCaiyangren.tpl";
		$tpl="TblList.tpl";
	    FLEA::loadClass('TMIS_Pager');
	    $sql="select x.*,y.depName as depName from jichu_employ x
		    left join jichu_department y on x.depId=y.id
		     where 1";
	    $arr = TMIS_Pager::getParamArray(array(
			'key'=>'',
			'depId'=>''
	    ));
	    if($arr['key']!='') $sql.=" and employName like '%{$arr['key']}%' or employCode like '%{$arr['key']}%'";
	     if($arr['depId']!='') $sql.=" and depId='{$arr['depId']}'";
	    $sql.=" order by x.id";
	    $pager=& new TMIS_Pager($sql);
	    $rowset=$pager->findAll();
		foreach($rowset as & $v){
			if($v['isCaiyang']==1)$checked='checked=true';
			else $checked='';
			$v['isCaiyang']='<input type="checkbox" name="isCaiyang[]" value="'.$v['id'].'" '.$checked.' title="点击设置" onClick="setEmploy(this)">';
		}

		$arr_info=array(
			'employCode'=>'员工编码',
			'employName'=>'员工名称',
			'depName'=>'部门名称',
			'isCaiyang'=>'可以采样',
		);
	    //dump($rowset);exit;
	    $smarty = & $this->_getView();
	    $smarty->assign('title','采样人设置');
	    $smarty->assign('add_display','none');
		$smarty->assign('arr_field_value',$rowset);
	    $smarty->assign('arr_field_info',$arr_info);
	    $smarty->assign('arr_condition', $arr);
	    $smarty->assign('page_info',$pager->getNavBar($this->_url($_GET['action'],$arr)));
	    $smarty->assign('rowset',$rowset);
		$smarty->assign('sonTpl', $sonTpl);
	    $smarty->display($tpl);
	}

	function actionSetEmploy(){
	    if($_GET['id']!=''){
		$sql="update jichu_employ set isCaiyang={$_GET['isCaiyang']} where id={$_GET['id']}";
		$id=mysql_query($sql);
		if($id>0){
		     echo json_encode(array(
			'success'=>true,
			));
			exit;
		}else{
		    echo json_encode(array(
			'success'=>false,
			));
			exit;
		}
	    }
	}

	function actionGetCodeInfo(){
	  $str="select *,sum(kucunCnt) as kucun from sample_db where proCode='{$_GET['proCode']}'";
	  $row=mysql_fetch_assoc(mysql_query($str));

	  if(is_array($row)){
			if($row['isXiajia']==0){
			    echo json_encode(array(
				'success'=>false,
				'msg'=>'已下架！'
				));
				exit;
			}

			$sql="select sum(chukuCnt) as cnt from sample_caiyang where proCode='{$_GET['proCode']}'";
			if($_GET['id']!='')$sql.=" and id<>{$_GET['id']}";
			$s=mysql_fetch_assoc(mysql_query($sql));
			// echo($sql);
			$row['kucun']-=$s['cnt'];

			//查找单价
			$sql="select danjia from jichu_product where proCode='{$_GET['proCode']}'";
			$s=mysql_fetch_assoc(mysql_query($sql));
			$row['danjia']=$s['danjia'];

			echo json_encode($row);
    }else{
			echo json_encode(array(
				'success'=>false,
				'msg'=>'条码不存在！'
			));
    }
	}

	//查看报表样品登记信息
	function actionSelcaiyang(){
	   // dump($_GET);exit;
	    FLEA::loadClass('TMIS_Pager');
	    $arr = TMIS_Pager::getParamArray(array(
			'dateFrom'=>date('Y-m-d',mktime(0,0,0,date('m'),date('d')-30,date('Y'))),
			'dateTo'=>date('Y-m-d'),
			'key'=>''
	    ));
	    $title='样品采样信息';
	    $sql="select x.*,a.employName from sample_caiyang x
		left join jichu_employ a on x.caiyangren=a.employCode
		left join jichu_client b on x.clientName=b.compName
		where x.proCode='{$_GET['proCode']}'
		and kucunWei='{$_GET['kucunWei']}'
		and ganghao='{$_GET['ganghao']}'
		and chukuDate >= '{$_GET['dateFrom']}'
		and chukuDate <= '{$_GET['dateTo']}'
		order by x.id desc";
    $pager=& new TMIS_Pager($sql);
    $rowset=$pager->findAll();
    $arr_field_info=array(
		'proCode'=>'产品编号',
		'kucunWei'=>'库存位置',
		'ganghao'=>'缸号',
		'employName'=>'采样人',
		'clientName'=>'客户名称',
		'chukuCnt'=>'采样数量',
		'chukuDate'=>'采样日期',
		'creater'=>'创建人',
		'memo'=>'备注',

	    );
	    foreach($rowset as & $v){
				$v['shazhi']="<strong>".$v['shazhi']."</strong>";
	    }
	    //dump($rowset);exit;
	    $heji=$this->getHeji($rowset,array('chukuCnt'),'proCode');
	    //dump($heji);exit;
	    $rowset[]=$heji;
	    $smarty = & $this->_getView();
	    $smarty->assign('arr_field_value',$rowset);
	    $smarty->assign('arr_field_info',$arr_field_info);
	    $smarty->assign('title',$title);
	    $smarty->assign('arr_condition', $arr);
	    $smarty->assign('add_display','');
	    $smarty->assign('arr_js_css',$this->makeArrayJsCss(array('calendar')));
	    $smarty->assign('page_info',$pager->getNavBar($this->_url($_GET['action'],$arr)));
	    $smarty->display('TableList.tpl');
	}
	//新增多条
	function actionAddMany() {
		$arr=array(array());
		$smarty = & $this->_getView();
	    $smarty->assign('rowset',$arr);
	    $smarty->display('Sample/CaiyangMany.tpl');
	}
	/**
	 * ps ：保存多条
	 * Time：2015/07/20 15:19:10
	 * @author jiang
	 * @param 参数类型
	 * @return 返回值类型
	*/
	function actionsaveMany(){
		// dump($_POST);
		foreach ($_POST['chukuCnt'] as $key => $value) {
			if(empty($_POST['chukuCnt'][$key])||empty($_POST['sampleId'][$key])) continue;
			$arr[]=array(
				'id'=>$_POST['id'][$key],
				'sampleId'=>$_POST['sampleId'][$key]+0,
				'proCode'=>$_POST['proCode'][$key].'',
				'ganghao'=>$_POST['ganghao'][$key].'',
				'kucunWei'=>$_POST['kucunWei'][$key].'',
				'cntJian'=>$_POST['cntJian'][$key]+0,
				'cntM'=>$_POST['cntM'][$key]+0,
				'chukuCnt'=>$_POST['chukuCnt'][$key]+0,
				'danjia'=>$_POST['danjia'][$key]+0,
				'money'=>$_POST['money'][$key]+0,
				'yunfei'=>$_POST['yunfei'][$key]+0,
				'memo'=>$_POST['memo'][$key].'',
				'caiyangren'=>$_POST['caiyangren'].'',
				'clientName'=>$_POST['clientName'].'',
				'chukuDate'=>$_POST['chukuDate'],
				'caiCode'=>$_POST['caiCode'].'',
				'creater'=>$_SESSION['REALNAME'].'',
				);
		}
		// dump($arr);exit;
		$this->_modelExample->saveRowset($arr);
		js_alert(null,"window.parent.showMsg('保存成功!')",$this->_url('right'));
	}
	  //采样编码，唯一
  	function _getNewCode() {
	    $head = date('y');
	    $fieldName = "caiCode";
	    $sql = "select {$fieldName} from sample_caiyang where {$fieldName} like '{$head}____' order by {$fieldName} desc limit 0,1";

	    $_r = $this->findBySql($sql);
	    $row = $_r[0];

	    $init = $head .'0001';
	    if(empty($row[$fieldName])) return $init;
	    if($init>$row[$fieldName]) return $init;

	    //自增1
	    $max = substr($row[$fieldName],-4);
	    $pre = substr($row[$fieldName],0,-4);
	    // dump($pre);dump($pre);exit;
	    return $pre .substr(10001+$max,1);
  }
}
?>
