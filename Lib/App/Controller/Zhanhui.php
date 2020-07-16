<?php
FLEA::loadClass('TMIS_Controller');
class Controller_Zhanhui extends Tmis_Controller {
    function Controller_Zhanhui() {    	
		$this->_mTrader =& FLEA::getSingleton('Model_Zhanhui_Trader');
		$this->_mCard =& FLEA::getSingleton('Model_Zhanhui_Card');
		$this->_modelExample =& FLEA::getSingleton('Model_Zhanhui_Card');
		$this->_mJiyang =& FLEA::getSingleton('Model_Zhanhui_Jiyang');
    }

	//数据补丁,产品信息导入后，id和erp的product表的id有变化，需要根据产品编码寻找对应关系，修改相关的proId字段
	function actionPatch() {
		$sql = "select x.id,y.proCode from e7_zhongheng.zhanhui_product2card x
		left join e7_zhanhui.jichu_product y on x.productId=y.id";
		$rowset = $this->_mCard->findBySql($sql);
		$ret = array();
		foreach($rowset as & $v) {
			$sql = "select * from jichu_product where proCode='{$v['proCode']}'";
			$_r = $this->_mCard->findBySql($sql);
			$ret[] = array(
				'id'=>$v['id'],
				'proCode'=>$v['proCode'],
				'productId'=>$_r[0]['id']
			);
			//$sql = "update zhanhui_product2card set productId='{$_r[0]['id']}'";
		}
		//select x.proCode,x.proKind,x.proColor,x.guige,x.chengfen,x.menfu,x.kezhong,x.id,y.* from e7_zhongheng.jichu_product x left join e7_zhanhui.jichu_product y on x.proCode=y.proCode where x.id<>y.id
		//目前未发现产品编码相等，id不相等的记录

		//
	}

	function actionRight() {
		$this->authCheck("9-1");
		FLEA::loadClass('TMIS_Pager');
		$arr = TMIS_Pager::getParamArray(array(
			'dateFrom' => date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-30, date("Y"))),
			'dateTo' => date("Y-m-d"),
			'traderId'=>'',
		));

		$sql = "select
		x.traderId,y.employName,y.userId,
		count(*) as cntPro,x.cardId,
		z.dt,z.prefix,z.picName,z.name,z.tel,z.compName,z.addr
		from zhanhui_product2card x
		left join zhanhui_trader y on x.traderId=y.id
		left join zhanhui_card z on x.cardId=z.id
		where 1";
// 		//2013-7-18 by jeff,特殊权限中增肌了一个查看所有展会信息的模块411
// 		$canExport = $this->authCheck(411,true);
// 		if(!$canExport) {//是否只允许看自己的订单
// 			$sql.=" and y.userId='{$_SESSION['USERID']}'";
// 		}
		
		if($arr['dateFrom']!=''){
			$sql .= " and z.dt >= '$arr[dateFrom]' and z.dt<='$arr[dateTo]'";
		}

		if ($arr['traderId']>0) $sql .= " and x.traderId='{$arr['traderId']}'";
		$sql .= " group by x.cardId order by z.id desc";
		//dump();exit;
		$pager =& new TMIS_Pager($sql,null,null,10);
        $rowset =$pager->findAll();
		if(count($rowset)>0) foreach($rowset as & $v){
			//取得图片路径
			$path = 'Upload/Zhanhui/'.$v['prefix'].'/b'.$v['picName'].'.jpg';
			if(!file_exists($path)) {
				$path = 'Upload/Zhanhui/'.$v['prefix'].'/'.$v['picName'].'.jpg';
			}
			$v['pic'] = "<a href='".$this->_url('showMore',array(
				'cardId'=>$v['cardId'],
				'path'=>$path,
				'TB_iframe'=>1
			))."' class='thickbox' title='寄样明细'><img src='{$path}' width='150' height='80' border=0/></a>";
			$v['cntPro'] .= " [ <a href='".$this->_url('showMore',array(
				'cardId'=>$v['cardId'],
				'path'=>$path,
				'TB_iframe'=>1
			))."' class='thickbox' title='寄样明细'>点击看详细</a> ]";

			//如果业务员无法取到，从jichu_employ中寻找
			if($v['employName']=='') {
				$sql= "select employName from jichu_employ where id='{$v['traderId']}'";
				$temp= $this->_modelExample->findBySql($sql);
				$v['employName'] = $temp[0]['employName'];
			}
			//dump($path);exit;
		}
		$smarty = & $this->_getView();
		$smarty->assign('title', $this->title);
		$pk = $this->_modelExample->primaryKey;
		$smarty->assign('pk', $pk);
		$arr_field_info = array(
			'pic'=>"小图",
			"traderId" =>"traderId",
			"employName" =>"业务员",
			"dt" =>"扫描时间",
			"cntPro" =>"寄样个数",
			//"_edit" =>"详细"
		);
		$smarty->assign('arr_field_info',$arr_field_info);
		$smarty->assign('arr_field_value',$rowset);
		$smarty->assign('arr_condition',$arr);
		$smarty->assign('page_info',$pager->getNavBar($this->_url($_GET['action'],$arr)));
		$smarty->assign('arr_js_css',$this->makeArrayJsCss(array('thickbox')));
		$smarty->assign('add_display','none');
		if($canExport) {
			$smarty->assign('other_button','<a href="'.$this->_url('export2excel').'">导出</a>');
		}
		$smarty-> display('TableList.tpl');
	//	if($this->authCheck(198,true)) echo "<a href='".$this->_url('export2excel',$arr)."'>导出</a>";
	}
	//导出为excel
	function actionExport2excel() {
		FLEA::loadClass('TMIS_Pager');
		$arr = TMIS_Pager::getParamArray(array(
			'zhanhuiTrader'=>''
		));

		$sql = "select
		y.employName as traderName,y.userId,
		x.cardId,
		z.dt,z.prefix,z.picName,z.name,z.tel,z.compName,z.addr
		from zhanhui_product2card x
		left join zhanhui_trader y on x.traderId=y.id
		left join zhanhui_card z on x.cardId=z.id
		where 1";
		if ($arr['zhanhuiTrader']>0) $sql .= " and x.traderId='{$arr['zhanhuiTrader']}'";
		$sql .= " group by x.cardId";
		// dump($sql);
		$rowset = $this->_modelExample->findBySql($sql);
		// dump($rowset);exit;
		if(count($rowset)>0) foreach($rowset as & $v){
			//取得图片路径
			$path = 'Upload/Zhanhui/'.$v['prefix'].'/b'.$v['picName'].'.jpg';
			if(!file_exists($path)) {
				$path = 'Upload/Zhanhui/'.$v['prefix'].'/'.$v['picName'].'.jpg';
			}
			$v['path'] = "http://58.216.243.34/e7_zhongheng/".$path;


			//取得所有的寄样信息
			$sql = "select y.* from zhanhui_product2card x
			left join jichu_product y on x.productId=y.id 
			where x.cardId='{$v['cardId']}'";
			// dump($sql);exit;
			$_rows = $this->_modelExample->findBySql($sql);
			//如果不够11行，补齐
			if(count($_rows)<11) {
				for($i=count($_rows);$i<11;$i++) {
					$_rows[] = array('proCode'=>'&nbsp;');
				}
			}
			$_rows[] = array('proCode'=>'&nbsp;');
			$v['P'] = $_rows;
		}
		// dump($rowset[0]);exit;
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/force-download");
		header("Content-Type: application/download");
		header("Content-Disposition: attachment;filename=test.xls");
		header("Content-Transfer-Encoding: binary");
		$smarty = & $this->_getView();		
		$smarty->assign('rowset',$rowset);
		$smarty->display('Zhanhui/Export2Excel.tpl');
	}
	//显示名片大图片和详细寄样信息
	function actionShowMore() {
		$cardId = $_GET['cardId'];
		if(empty($cardId)) {
			die('必须指定cardId');
		}
		$sql = "select y.*
		from zhanhui_product2card x
		left join jichu_product y on x.productId=y.id
		where x.cardId='{$cardId}'";
		$rowset = $this->_mCard->findBySql($sql);
		$smarty = & $this->_getView();
		$smarty->assign('rowset',$rowset);
		$smarty-> display('Zhanhui/ShowMore.tpl');
	}

	//处理笔记本传输过来的展会信息
	//主要保存zhanhui_card和zhanhui_product2card表信息
	function actionSaveZhanhui() {
		$m = $this->_mCard;
		$n = $this->_mJiyang;
		$ret = json_decode($_POST['json'],true);
		if(!is_array($ret)) {
			echo '出错，出入的参数错误';
			exit;
		}
		//根据proCode取得productId
		foreach($ret as & $v) {
			foreach($v['Jiyang'] as & $vv) {
				$sql = "select * from jichu_product where proCode='{$vv['proCode']}'";
				$_r = $m->findBySql($sql);
				if(!$_r[0]['id']){
				$sql="insert into jichu_product2card (productId,proCode,cardId,traderId)values('{$vv['productId']}','{$vv['proCode']}','{$vv['cardId']}','{$vv['traderId']}') ";	
				mysql_query($sql);
				}else{
					$vv['productId'] = $_r[0]['id'];
				}
			}
		}
		$m->createRowset($ret);
		echo json_encode(array(
			'success'=>true
		));
	}


	//业务员列表
	function actionListTrader() {
		$this->authCheck("9-2");
		FLEA::loadClass('TMIS_Pager');
		$arr = TMIS_Pager::getParamArray(array(
			'key'=>''
		));

		$sql = "select x.*,y.realName from zhanhui_trader x
		left join acm_userdb y on x.userId=y.id where 1";
		if ($arr['key']!='') $sql .= " and employName like '%{$arr['key']}%'";

		$pager =& new TMIS_Pager($sql,null,null,10);
        $rowset =$pager->findAll();
		if(count($rowset)>0) foreach($rowset as & $v){
			$v['_edit'] = "<a href='".$this->_url('editTrader',array(
				'id'=>$v['id']
			))."'>修改</a>";
			$v['_edit'].= " <a href='".$this->_url('removeTrader',array(
				'id'=>$v['id']
			))."' onclick='return confirm(\"确认删除吗？\")'>删除</a>";
		}
		$smarty = & $this->_getView();
		$smarty->assign('title', $this->title);
		$pk = $this->_modelExample->primaryKey;
		$smarty->assign('pk', $pk);
		$arr_field_info = array(
			"employName" =>"参展业务员",
			"realName"=>"关联用户",
			//"cntPro" =>"寄样个数",
			"_edit" =>"详细"
		);
		$smarty->assign('arr_field_info',$arr_field_info);
		$smarty->assign('arr_field_value',$rowset);
		$smarty->assign('arr_condition',$arr);
		$smarty->assign('page_info',$pager->getNavBar($this->_url($_GET['action'],$arr)));
		$smarty->assign('arr_js_css',$this->makeArrayJsCss(array('thickbox')));
		$smarty->assign('add_url',$this->_url('EditTrader'));
		$smarty-> display('TableList.tpl');
	}
	function actionEditTrader() {
		if($_SERVER['REQUEST_METHOD']=='POST') {
			$this->_mTrader->save($_POST);
			js_alert('保存成功',null,$this->_url('listTrader'));
			exit;
		}
		$smarty = & $this->_getView();
		$sql = "select * from zhanhui_trader where id='{$_GET['id']}'";
		$row = $this->_mCard->findBySql($sql);
		$smarty->assign('aRow',$row[0]);
		$smarty-> display('Zhanhui/TraderEdit.tpl');
	}

	function actionRemoveTrader() {
		//判断是否有展会信息存在
		$sql = "select count(*) cnt from zhanhui_product2card where traderId='{$_GET['id']}'";
		$temp = $this->_mTrader->findBySql($sql);
		// dump($temp);exit;
		if($temp[0]['cnt']>0) {
			js_alert('已有展会信息存在，禁止删除',null,$this->_url('ListTrader'));
			exit;
		}
		$this->_mTrader->removeByPkv($_GET['id']);
		js_alert(null,null,$this->_url('ListTrader'));
	}

	//从客户端上传名片文件
	#展会信息导入,正常部署时，这部分代码应该写在众恒的erp中。而不是在展会的系统中。
	function actionImportCard(){
		//js_alert(null,null,'htt');
		$smarty=& $this->_getView();
		$smarty->assign('pre',rawurlencode($_GET['pre']));
		//$smarty->assign('arr_condition',$arr);
		//$smarty->assign('add_display','none');
		//$smarty->assign('arr_field_info',$arr_field_info);
		//$smarty->assign('arr_field_value',$rowset);
		//$smarty->assign('page_info',$pager->getNavBar($this->_url($_GET['action'],$arr)));
		$smarty->display('Zhanhui/ImportCard.tpl');
	}
	//处理上传过来的图片文件，正常部署时，这部分代码应该写在众恒的erp中。
	function actionSaveImportCard() {
		//设置上传的图片放入哪个目录下，uploads目录下应该按照展会和机器的不同创建不同目录，比如
		//uploads/2012-10-29_A，前面是展会时间，后面是表示A机器
		$prefix=$_GET['pre'];//此前缀应该是由客户机器从前台传入。
		$targetFolder = 'Upload/Zhanhui/'.$prefix;
		if(!file_exists($targetFolder)) mkdir($targetFolder, 0777, true);//确保目录被创建
		$fileTypes = array('txt','jpg','jpeg','gif','png'); // File extensions

		//$verifyToken = md5('unique_salt' . $_POST['timestamp']);
		if (!empty($_FILES)) {
			$tempFile = $_FILES['Filedata']['tmp_name'];
			//$targetPath = $_SERVER['DOCUMENT_ROOT'] . $targetFolder;
			$targetFile = rtrim($targetFolder,'/') . '/' . $_FILES['Filedata']['name'];

			$fileParts = pathinfo($_FILES['Filedata']['name']);
			if (in_array($fileParts['extension'],$fileTypes)) {
				move_uploaded_file($tempFile,$targetFile);
				echo '1';
			} else {
				echo 'Invalid file type.';
			}
		}
	}

	#被寄产品统计
	function actionTongji(){
		$this->authCheck("9-3");
		FLEA::loadClass('TMIS_Pager');
		$arr=& TMIS_Pager::getParamArray(array(
			'key'=>''
		));
		$str="select count(*) as cnt,
			x.proCode,y.proName,y.guige,y.color,y.memo,y.proCode as proCode1
			from zhanhui_product2card x
			left join jichu_product y on y.id=x.productId
			where 1
		";
		if($arr['key']!='')$str.=" and(y.proCode like '%{$arr['key']}%' or y.proName like '%{$arr['key']}%')";
		$str.=" group by x.productId order by x.id desc";
		//echo $str;
		$pager=& new TMIS_Pager($str);
		$rowset=$pager->findAll();
		//2013-10-30 by jeff，有部分数据proCode字段为空,需要从jichu_product中取
		foreach($rowset as & $v) {
			if($v['proCode']=='') $v['proCode'] = $v['proCode1'];
			if($v['proName']=='') $v['memo'] = "<font color=red>此产品在ERP基础资料中已删除！</font>";
		}
		$arr_field_info=array(
			'proCode'=>'编码<font color=red>*</font>',
			'proName'=>'品种<font color=red>*</font>',
			'guige'=>'规格',
			'color'=>'颜色',
			'memo'=>'备注',
			'cnt'=>'次数'
		);
		$smarty=& $this->_getView();
		$smarty->assign('arr_condition',$arr);
		$smarty->assign('title','展会被寄品统计');
		$smarty->assign('add_display','none');
		$smarty->assign('arr_field_info',$arr_field_info);
		$smarty->assign('arr_field_value',$rowset);
		$smarty->assign('page_info',$pager->getNavBar($this->_url($_GET['action'],$arr)));
		$smarty->assign('arr_js_css',$this->makeArrayJsCss(array('thickbox')));
		$smarty->display('TableList.tpl');
	}

	//将erp中的业务员信息导入到展会系统中
	function actionExportTraderImfo(){
		$m = $this->_mTrader;
		$sql = "select * from zhanhui_trader where isExport='0'";
		$re = $m->findBySql($sql);
		echo json_encode(array(
			'rowset'=>$re,
			'success'=>true
		));
		$sql = "update zhanhui_trader set isExport = '1' where id in '{$re['id']}'";
		$m->executeBySql($sql);
	}
}


?>