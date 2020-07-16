<?php
/*********************************************************************\
*  Copyright (c) 1998-2013, TH. All Rights Reserved.
*  Author :Jeff
*  FName  :Plan.php
*  Time   :2014/05/13 18:31:40
*  Remark :生产计划控制器
*  change : removeByPkv可能会有删除限制
\*********************************************************************/
FLEA::loadClass('TMIS_Controller');
class Controller_Shengchan_Huitian extends Tmis_Controller {
	// /构造函数
	function Controller_Shengchan_Huitian() {
		$this->_modelPlan = &FLEA::getSingleton('Model_Shengchan_Plan');
		$this->_modelExample = &FLEA::getSingleton('Model_Shengchan_Huitian');
		$this->_modelhui2bu = &FLEA::getSingleton('Model_Shengchan_Huitian2Bu');
		// 定义模板中的主表字段
		$this->fldMain = array(
			'orderCode' => array('title' => '订单单号', "type" => "text", 'readonly' => true, 'value' => ''),
			'proCode' => array('title' => '产品编号', 'type' => 'text', 'name' => 'proCode', 'readonly' => true),
			'proName' => array('type' => 'text', "title" => '品名', 'name' => 'proName', 'readonly' => true),
			'guige' => array('type' => 'text', "title" => '规格', 'name' => 'guige', 'readonly' => true),
			'color' => array('type' => 'text', "title" => '颜色', 'name' => 'color', 'readonly' => true),
			'cntYaohuo' => array('type' => 'text', "title" => '订单数量', 'name' => 'cntYaohuo', 'readonly' => true),
			'finalDate' => array('type' => 'text', "title" => '交期', 'name' => 'finalDate', 'readonly' => true),
			// 下面为隐藏字段
			'id' => array('type' => 'hidden', 'value' => ''),
			'planId' => array('type' => 'hidden', 'value' => '','name'=>'planId'),
			'creater' => array('type' => 'hidden', 'value' => $_SESSION['REALNAME']),
			); 
	} 

	function actionRight() {
		$this->authCheck('2-6');
		FLEA::loadClass('TMIS_Pager');
		//TMIS_Pager::clearCondition();
		$arr = TMIS_Pager::getParamArray(array(
			'dateFrom' => date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-30, date("Y"))),
			'dateTo' => date("Y-m-d"),
			'orderCode'=>'',
			'planCode' => '',
			'key'=>''
		)); 
		//sql语句，查找信息
		$sql="select x.*,o.orderCode,o.overflow,y.cntYaohuo,p.menfu,p.kezhong,p.proCode,p.proName,p.guige,p.color from shengchan_plan x
				left join trade_order2product y on x.order2proId=y.id
				left join trade_order o on o.id=y.orderId
				left join jichu_product p on p.id=x.productId
				where 1";
		$sql .= " and planDate >= '$arr[dateFrom]' and planDate<='$arr[dateTo]'";
		if($arr['orderCode']!='') $sql.=" and o.orderCode like '%{$arr['orderCode']}%'";
		if($arr['planCode']!='') $sql.=" and x.planCode like '%{$arr['planCode']}%'";
		if($arr['key']!='') $sql.=" and p.guige like '%{$arr['key']}%'";
		$sql.=" order by x.planCode desc";

		$pager = &new TMIS_Pager($sql);
		$rowset = $pager->findAll();
		if (count($rowset) > 0) foreach($rowset as &$v) {
			$v['_edit'] = "<a href='".$this->_url('Add',array(planId=>$v['id'],'fromAction'=>$_GET['action']))."'>回填数据</a>";
			
		}
		$smarty = &$this->_getView(); 
		// 左侧信息
		$arrFieldInfo = array(
			"_edit" => '操作',
			"planDate" => "日期",
			"orderCode"=>"订单号",
			'planCode' => '计划单号',
// 			'overflow' =>'溢短装',
			"proCode" => '产品编号',
			'cntYaohuo' =>'订单数量',
			'proName' =>'品名',
			'guige' =>'规格',
			'color' =>'颜色',
			'menfu' =>'门幅',
			'kezhong' =>'克重',
			'xianchang' =>'线长',
			'chengfen' =>'成分',
// 			'planMemo'=>'贸易部要求',
		); 
// 		dump($rowset);exit;
		$smarty->assign('title', '计划查询');
		$smarty->assign('arr_field_info', $arrFieldInfo);
		$smarty->assign('add_display', 'none');
		$smarty->assign('arr_condition', $arr);
		$smarty->assign('arr_field_value', $rowset);
		$smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action']), $arr));
		$smarty->display('TblList.tpl');
	}


	function actionAdd() {
		// 从表区域信息描述
		$smarty = &$this->_getView();
		$sql="select x.*,o.orderCode,o.overflow,y.cntYaohuo,p.menfu,p.kezhong,p.proCode,p.proName,p.guige,p.color from shengchan_plan x
				left join trade_order2product y on x.order2proId=y.id
				left join trade_order o on o.id=y.orderId
				left join jichu_product p on p.id=x.productId
				where 1 and x.id='{$_GET['planId']}'";
		$ret=$this->_modelExample->findBySql($sql);
		$this->fldMain['orderCode']['value'] =$ret[0]['orderCode'];
		$this->fldMain['proCode']['value'] =$ret[0]['proCode'];
		$this->fldMain['proName']['value'] =$ret[0]['proName'];
		$this->fldMain['guige']['value'] =$ret[0]['guige'];
		$this->fldMain['color']['value'] =$ret[0]['color'];
		$this->fldMain['cntYaohuo']['value'] =$ret[0]['cntYaohuo'];
		$this->fldMain['finalDate']['value'] =$ret[0]['finalDate'];
		$this->fldMain['planId']['value'] =$_GET['planId'];
		$ret=$this->_modelExample->find(array('planId'=>$_GET['planId']));
		$this->fldMain['id']['value'] =$ret['id'];
		$areaMain = array('title' => '生产计划基本信息', 'fld' => $this->fldMain);
		/*foreach ($ret['Huitians'] as &$v){
			if($v['spic']!=''){
				$v['spic']="<span class='input-group-addon'>
                                <input id='isdelete[]' type='checkbox' value='".$v['id']."' name='isdelete[]'>删除原
                                <a href='?controller=Shengchan_Huitian&action=DlFile&id=".$v['id']."'>
                                    <span class='' title='附件'>附件</span>
                                </a>
                            </span>";
			}
			$mingxi[$v['kind']][]=$v;
		}*/
		if(empty($mingxi['pb'])) $mingxi['pb']=array(array());
		if(empty($mingxi['cp'])) $mingxi['cp']=array(array());
		if(empty($mingxi['sj'])) $mingxi['sj']=array(array());
 		//dump($mingxi);exit;
		$smarty->assign('areaMain', $areaMain);
		$smarty->assign('ret', $ret);
		$smarty->assign('Row', $mingxi);
		$smarty->display('Shengchan/Plan/HuitianEdit.tpl');
	}

	function _edit($areaMain) {
		
		$smarty = &$this->_getView();
		$smarty->assign('areaMain', $areaMain);
		$smarty->display('Shengchan/Plan/HuitianEdit.tpl');
	}

	function actionSave(){
// 		dump($_POST);exit;
		//处理上传文件
		$temp=array();$arr=array();
		foreach ($_FILES as $k=> &$v){
			for($i=0;$i<count($v['name']);$i++){
				foreach ($v as $key=> &$value){
					$temp[$key]=$value[$i];
				}
				$arr[][$k]=$temp;
			}
		}
		foreach ($arr as &$v){
			$temp1='';
			foreach ($v as $kk=>&$vv){
				$temp1=$kk;
			}
			$dizhi['path']=$v[$kk];
			$filePath[$kk][]= $this->_importAttac($dizhi);
			
		}
// 		dump($filePath);exit;
		foreach ($_POST['pbDate'] as $i=>&$v){
			if(empty($filePath['pbPlan'][$i]['filePath'])) continue;
			$row[]=array(
					'id'=>$_POST['pbId'][$i],
					'huiDate'=>$_POST['pbDate'][$i],
					'spic'=>$filePath['pbPlan'][$i]['filePath'],
					'kind'=>'pb',
			);
		}
		foreach ($_POST['cpDate'] as $i=>&$v){
			if(empty($filePath['cpPlan'][$i]['filePath'])) continue;
			$row[]=array(
					'id'=>$_POST['cpId'][$i],
					'huiDate'=>$_POST['cpDate'][$i],
					'spic'=>$filePath['cpPlan'][$i]['filePath'],
					'kind'=>'cp',
			);
		}
		foreach ($_POST['sjDate'] as $i=>&$v){
			if(empty($filePath['sjPlan'][$i]['filePath'])) continue;
			$row[]=array(
					'id'=>$_POST['sjId'][$i],
					'huiDate'=>$_POST['sjDate'][$i],
					'spic'=>$filePath['sjPlan'][$i]['filePath'],
					'kind'=>'sj',
			);
		}
		//删除源文件
		foreach ($_POST['isdelete'] as &$d){
			if($d!='') $this->_deleteFile($d);
		}
		$ret=array(
				'id'=>$_POST['id'],
				'planId'=>$_POST['planId'],
				'menfu_bu'=>$_POST['menfu_bu'],
				'kezhong_bu'=>$_POST['kezhong_bu'],
				'menfu_cp'=>$_POST['menfu_cp'],
				'kezhong_cp'=>$_POST['kezhong_cp'],
				'menfu_cs'=>$_POST['menfu_cs'],
				'kezhong_cs'=>$_POST['kezhong_cs'],
				'damenfu'=>$_POST['damenfu'],
				'dakezhong'=>$_POST['dakezhong'],
				'jxsuolv'=>$_POST['jxsuolv'],
				'wxsuolv'=>$_POST['wxsuolv'],
				'Huitians'=>$row				
		);
// 		dump($ret);exit;
		$id=$this->_modelExample->save($ret);
		//跳转
		js_alert(null,'window.parent.showMsg("保存成功!")',$this->_url('right'));
		exit;
	}
	function _importAttac($dizhi){
// 	 	dump($dizhi);exit;
		//上传路径
		$path="upload/huitian/";
		$targetFile='';
		$tt = false;//是否上传文件成功
		//禁止上传的文件类型
		$upBitType = array(
				'application/x-msdownload',//exe,dll
				'application/octet-stream',//bat
				'application/javascript',//js
		);
	
		//处理上传代码
		if($dizhi['path']['name']!=''){
			//附件大小不能超过10M
			$max_size=10;//M
			$max_size2=$max_size*1024*1024;
			// dump($_FILES);echo $max_size;exit;
			if($dizhi['path']['size']>$max_size2){
				// js_alert("附件超过{$max_size}M，请上传小于{$max_size}M的附件","",$this->_url($_POST['fromAction']!=''?$_POST['fromAction']:'ListForAdd'));exit;
				return array('success'=>false,'msg'=>"附件上传失败，请上传小于{$max_size}M的附件");
			}
				
			//限制类型
			if(in_array($dizhi['path']['type'],$upBitType)){
				return array('success'=>false,'msg'=>"该文件类型不允许上传");
			}
				
			//上传附件信息
			if ($dizhi['path']['name']!="") {
				$tempFile = $dizhi['path']['tmp_name'];
				//处理文件名
				$pinfo=pathinfo($dizhi['path']['name']);
				$ftype=$pinfo['extension'];
	
				$fileName=md5(uniqid(rand(), true)).' '.$dizhi['path']['name'];
				// dump($fileName);exit;
				// $_POST['actionAttac']=$fileName;
				$targetFile=$path.$fileName;//目标路径
				$tt=move_uploaded_file($tempFile,iconv('UTF-8','gb2312',$targetFile));
				// dump($tt);exit;
				if($tt==false && $targetFile!='')$msg="上传失败，请重新上传附件";
			}
		}		
		return array('filePath'=>$targetFile,'success'=>$tt,'msg'=>$msg);
	}
	function _deleteFile($id){
// 		dump($id);exit;
		$str="SELECT * FROM shengchan_huitian2bu WHERE id={$id}";
		$re=mysql_fetch_assoc(mysql_query($str));
		// dump($re);exit;
		if($re['spic']!=''){
			$str="update shengchan_huitian2bu set spic='' WHERE id={$id}";
			mysql_query($str);
		
			$pathhave=$re['spic'];
			header('Content-Type:text/html;charset=utf-8');
			unlink(iconv('UTF-8','gb2312',$pathhave));
		}
	}
	//下载文件
	function actionDlFile(){
		//dump($_GET);exit;
		if($_GET['id']){
			$str="SELECT spic FROM shengchan_huitian2bu WHERE id='{$_GET['id']}'";
			$re=mysql_fetch_assoc(mysql_query($str));
			// dump($re);exit;
			if($re['spic']!=''){
				$this->getFile($re['spic']);
			}
		}
		js_alert('','window.history.go(-1)');
	
	}
	function actionRemoveByAjax(){
// 		dump($_POST);exit;
		$id=$_POST['id'];
		$r = $this->_modelhui2bu->removeByPkv($id);
		if(!$r) {
			// js_alert('删除失败');
			echo json_encode(array('success'=>false,'msg'=>'删除失败'));
			exit;
		}
		echo json_encode(array('success'=>true));
	}
	function actionMingxi(){
		FLEA::loadClass('TMIS_Pager');
		//TMIS_Pager::clearCondition();
		$arr = TMIS_Pager::getParamArray(array(
				'dateFrom' => date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-30, date("Y"))),
				'dateTo' => date("Y-m-d"),
				'orderCode'=>'',
				'planCode' => '',
				'ord2proId'=>'',
				'key'=>''
		));
		//sql语句，查找信息
		$sql="select y.*,x.huiDate,x.kind,substring(x.spic,48) as spic,x.id as huiId,p.planDate,p.planCode,o.orderCode,jp.proCode,jp.proName,jp.guige,jp.color from shengchan_huitian y
				left join shengchan_huitian2bu x on y.id=x.huitianId
				left join shengchan_plan p on p.id=y.planId
				left join trade_order2product op on p.order2proId=op.id
				left join trade_order o on o.id=op.orderId
				left join jichu_product jp on jp.id=p.productId
				where 1";
		$sql .= " and planDate >= '$arr[dateFrom]' and planDate<='$arr[dateTo]'";
		if($arr['orderCode']!='') $sql.=" and o.orderCode like '%{$arr['orderCode']}%'";
		if($arr['planCode']!='') $sql.=" and p.planCode like '%{$arr['planCode']}%'";
		if($arr['ord2proId']!='') $sql.=" and p.order2proId like '%{$arr['ord2proId']}%'";
		if($arr['key']!='') $sql.=" and jp.guige like '%{$arr['key']}%'";
		$sql.=" order by p.planCode desc,x.kind";
		
		$pager = &new TMIS_Pager($sql);
		$rowset = $pager->findAll();
		if (count($rowset) > 0) foreach($rowset as &$v) {
			if($v['spic'])
			$v['_edit'] = "<a href='".$this->_url('DlFile',array(id=>$v['huiId']))."'>下载</a>";
			if($v['kind']=='pb'){
				$v['kind']='坯布';
				$v['menfu']=$v['menfu_bu'];
				$v['kezhong']=$v['kezhong_bu'];
				$v['damenfu']='';
				$v['dakezhong']='';
				$v['jxsuolv']='';
				$v['wxsuolv']='';
			}
			else if($v['kind']=='cp'){
				$v['kind']='成品';
				$v['menfu']=$v['menfu_cp'];
				$v['kezhong']=$v['kezhong_cp'];
				$v['jxsuolv']='';
				$v['wxsuolv']='';
			}
			else{
				$v['kind']='缩检';
				$v['menfu']=$v['menfu_cs'];
				$v['kezhong']=$v['kezhong_cs'];
				$v['damenfu']='';
				$v['dakezhong']='';
			}
		}
		$smarty = &$this->_getView();
		// 左侧信息
		$arrFieldInfo = array(
				"_edit" => '附件',
				"spic" => '附件名称',
				"planDate" => "计划日期",
				"orderCode"=>"订单号",
				'planCode' => '计划单号',
				"proCode" => '产品编号',
				'proName' =>'品名',
				'guige' =>'规格',
				'kind' =>'类型',
				'menfu' =>'门幅',
				'kezhong' =>'克重',
				'damenfu' =>'打卷门幅',
				'dakezhong' =>'打卷克重',
				'jxsuolv'=>'经向缩率',
				'wxsuolv'=>'纬向缩率',
		);
		// 		dump($rowset);exit;
		$smarty->assign('title', '计划查询');
		$smarty->assign('arr_field_info', $arrFieldInfo);
		$smarty->assign('add_display', 'none');
		$smarty->assign('arr_condition', $arr);
		$smarty->assign('arr_field_value', $rowset);
		$smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action']), $arr));
		$smarty->display('TblList.tpl');
	}
}
?>