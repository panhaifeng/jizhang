<?php
FLEA::loadClass('TMIS_Controller');
class Controller_Main extends Tmis_Controller {
	var $_modelExample;
	var $_modelAcmOa;
	function Controller_Main() {
		$this->_modelExample = &FLEA::getSingleton('Model_OaMessage');
		$this->_modelAcmOa = &FLEA::getSingleton('Model_Acm_User2message');
	}
	function actionIndex() {
		// dump($_SESSION['REALNAME']);exit;
		if (!$_SESSION['REALNAME']) {
			redirect(url("Login"));
			exit;
		} 
		// 判断企业为★用户
		// dump($_SESSION);exit;
		// 判断企业为★用户
		$jinyan = 0;
		$msg = "";
		$dj = 0;
		$jingyanStr = $this->GetJingyan(); 
		// dump($jingyanStr);exit;
		$jinyan = $jingyanStr['jingyan'];
		$msg = $jingyanStr['msg'];
		$dj = $jingyanStr['dj'];
		$userMsg = $jingyanStr['userMsg']; 
		// 如果$_SESSION【’SN】为1，显示工具箱链接，没有则不显示
		// dump($_SESSION['SN']);exit;
		if ($_SESSION['SN']) {
			$tool = "<a href='" . url('tool', 'Index') . "' target='_blank' title='打开工具箱' style='color:white;text-decoration: underline;'>工具箱</a>" . '&nbsp;&nbsp;';
		}else {
			$tool = "";
		}

		require_once('Config/NewLogin_config.php');
		// $login = $_login_config;
		$_login = $_login_config['Login'];
		$_login_Ip = $_login_config['Login_Ip'];
		$_outTime = $_login_config['timeOut'];

		//如果设置了远程地址,获取远程数据，
		if($_login_Ip!=''){
			$_Url = str_replace(PHP_EOL, '',$_login_Ip);
			//设置超时时间
			$context['http'] = array(
				'timeout'=>$_outTime > 0 ? $_outTime : 3,
				'method' => 'POST'
	    	);
			$json = file_get_contents($_Url, false, stream_context_create($context));
			$_l = json_decode($json,true);
			if($_l['success']){
				$login = $_l['config'];
			}
		}
		$teamCode = FLEA::getAppInf('teamCode');
		if($login['cdUrl']&&$teamCode){
			$list_url = $login['cdUrl'].$teamCode;
		}
        // 取得老板驾驶舱的配置信息
		$canShowBoss = $this->getBossReportParam();
		$smarty = &$this->_getView();
		$smarty->assign('menu_json', json_encode($tree));
		$smarty->assign('action', $action);
		$smarty->assign("adUrl", $login['advertise']);
		$smarty->assign("isShowAd", $login['isShowAd']);
		$smarty->assign("adImage", $login['adImage']);
		$smarty->assign("adName", $login['adName']);
		// $smarty->assign('dj',$dj);
		// $smarty->assign('userJy',$userJy);
		// $smarty->assign('userMsg',$userMsg);
		$smarty->assign('isShowBoss',$canShowBoss['isShow']?1:0);
		$smarty->assign("list_url", $list_url);
		$smarty->assign('tool', $tool);
		$smarty->display('Main.tpl');
	}

	function actionGetMenu() {
		$f = &FLEA::getAppInf('menu');
		include $f;
		$m = &FLEA::getSingleton('Model_Acm_Func');
		$menu = array('children' => $_sysMenu);
		foreach($_sysMenu as &$v) {
			$m->changeVisible($v, array('userName' => $_SESSION['USERNAME']));
		}

		$mC = &FLEA::getSingleton('TMIS_Controller');
		$sys = $mC->getSysSet();
		foreach($_sysMenu as &$v) {
			$m->changeVisibleBySys($v, $sys);
		} 
		// dump($_sysMenu);exit;
		echo json_encode($_sysMenu);
	}

	function actionWelcome() {
		$smarty = & $this->_getView();
		$dateFrom=date('Y-m-d',mktime(0,0,0,date('m'),date('d')-3,date('Y')));
		$dateTo=date('Y-m-d');

		//得到最近的5条生产通知
		$str="SELECT * FROM `oa_message` where kindName='行政通知' order by id desc limit 0,10";
		$tongzhi=$this->_modelExample->findBySql($str);
		if(count($tongzhi)>0) {
			foreach($tongzhi as & $v) {
				$title=$v['title'];
				if(strlen($v['title'])>20) {
					$content=$this->changToHtml($title);
					$title=$this->cSubstr($content,0,20)."......";
				}
				//dump($title);exit;
				$v['title'] = "<a href='javascript:void(0)' class='tongzhi' title='查看通知' id='{$v['id']}'>{$title}</a>";
				if($v['buildDate']>=$dateFrom&&$v['buildDate']<=$dateTo){
					$v['title'].="<img src='Resource/Image/new.gif' width='28' height='11' />";
				}
			}
		}
		$smarty->assign('tongzhi_xingzheng',$tongzhi);

		//生产通知
		$str="SELECT * FROM `oa_message` where kindName='生产通知' order by id desc limit 0,10";
		$dingdan=$this->_modelExample->findBySql($str);
		if(count($dingdan)>0) {
			foreach($dingdan as & $v1) {
				$v1['title'] = "<a href='javascript:void(0)' class='tongzhi' title='查看通知' id='{$v1['id']}'>{$v1['title']}</a>";
				if($v1['buildDate']>=$dateFrom&&$v1['buildDate']<=$dateTo){
					$v1['title'].="<img src='Resource/Image/new.gif' width='28' height='11' />";
				}
			}
		}
		$smarty->assign('tongzhi_biandong',$dingdan);

		//近7天需要交货预警
		$dateFrom = date('Y-m-d',mktime(0,0,0,date('m'),date('d'),date('Y')));
		$dateTo = date('Y-m-d',mktime(0,0,0,date('m'),date('d')+7,date('Y')));
		$sql="SELECT x.*,y.orderCode,z.employName
		FROM `trade_order2product` x
		left join trade_order y on x.orderId=y.id
		left join jichu_employ z on z.id=y.traderId 
		where x.dateJiaohuo>='{$dateFrom}' and x.dateJiaohuo <= '{$dateTo}'
		group by x.orderid order by dateJiaohuo";
		//echo $sql;exit;
		$order=$this->_modelExample->findBySql($sql);
		if(count($order)>0) {
			foreach($order as & $v) {
				$temp=round((strtotime($v['dateJiaohuo'])-strtotime($dateFrom))/86400);
				$v['orderCode']="{$v['employName']},{$v['orderCode']},{$v['dateJiaohuo']}交货，距今{$temp}天";
			}
		}
		$smarty->assign('tongzhi_jiaohuo',$order);

		//已逾期订单
		// $sql="SELECT x.*,y.orderCode FROM `trade_order2product` x left join trade_order
		// y on x.orderId=y.id where x.dateJiaohuo<='{$dateFrom}' and y.isOver=0
		// and not exists(SELECT * FROM `chengpin_cpck` where orderId=x.orderId)
		// group by x.orderId order by x.dateJiaohuo desc";
		// //echo $sql;exit;
		// $row=$this->_modelExample->findBySql($sql);
		// if(count($row)>0) {
		// 	foreach($row as & $v) {
		// 		$temp=abs(round((strtotime($v['dateJiaohuo'])-strtotime($dateFrom))/86400));
		// 		$v['orderCode']="{$v['orderCode']},{$v['dateJiaohuo']}交货，已逾期{$temp}天";
		// 	}
		// }
		// $smarty->assign('tongzhi_yuqi',$row);

		//近七天发货
		/*$dt = date('Y-m-d',mktime(0,0,0,date('m'),date('d')-7,date('Y')));
		$sql="SELECT x.*,sum(x.cntM) as cntM1,y.orderCode,z.huaxing FROM `chengpin_cpck` x
		left join trade_order y on y.id=x.orderId
		left join trade_order2product z on z.id=x.ord2proId
		 where 1 and x.cpckDate>='{$dt}' and x.cpckDate<='{$dateFrom}' group by x.cpckDate,x.ord2proId";
		$fahuo=$this->_modelExample->findBySql($sql);
		*/
		//dump($fahuo);exit;
		//报表中心
		$f = & FLEA::getAppInf('menu');
		include $f;
		$m= & FLEA::getSingleton('Model_Acm_Func');
		$baoBiao=array();
		foreach($_sysMenu as & $v) {
			if($v['text']=='报表中心') {
				$res=$m->changeVisible($v,array('userName'=>$_SESSION['USERNAME']));
				if($res){
					foreach($v['children'] as  &$vv){
					if(!isset($vv['hidden'])){
						$baoBiao[]=$vv;
					}
				}
				}
				
			}
		}
		$smarty->assign('baobiao',$baoBiao);
		// dump($baoBiao);exit;
		$smarty->display('Welcome.tpl');
	}

	function actionTzViewDetails() {
		// dump($_GET);exit;
		$row = $this->_modelExample->findAll(array('id' => $_GET['id']));
		if ($_SESSION['USERID'] != '') {
			if ($row[0]['kindName'] != '订单变动通知') {
				$sql = "SELECT count(*) as cnt,kind,id FROM `acm_user2message` where messageId='{$_GET['id']}' and userId='{$_SESSION['USERID']}'";
				$rr = mysql_fetch_assoc(mysql_query($sql));

				if ($rr['cnt'] == 0) {
					$arr = array('userId' => $_SESSION['USERID'],
						'messageId' => $_GET['id'],
						'kind' => 0,
						);
				}else if ($rr['kind'] == 1) {
					$arr = array('id' => $rr['id'],
						'kind' => 0,
						);
				}

				if ($arr && $_SESSION['USERID'] != '')$this->_modelAcmOa->save($arr);
			}
		}
		$smarty = &$this->_getView();
		$smarty->assign('title', '查看通知');
		$smarty->assign("row", $row[0]);
		$smarty->display('OaViewDetails.tpl');
	} 
	// 处理弹出窗口后下次不在弹出消息的问题
	function actionTzViewDetailsByAjax() {
		if ($_SESSION['USERID'] == '')exit;
		$userId = $_SESSION['USERID'];
		$sql = "SELECT x.* FROM `oa_message` x 
		left join oa_message_class y on y.className=x.kindName
		where y.isWindow=0
		and not exists(select * from acm_user2message z where z.messageId=x.id and z.userId='{$userId}')";
		$rr = $this->_modelExample->findBySql($sql);
		foreach($rr as &$v) {
			// if($v['kindName']=='行政通知') {
			$arr[] = array('userId' => $_SESSION['USERID'],
				'messageId' => $v['id'],
				'kind' => 1,
				); 
			// }
		}
		if ($arr)$this->_modelAcmOa->saveRowset($arr);
		echo json_encode(array('success' => true));
		exit;
	}

	function changToHtml($val) { // 将特殊字元转成 HTML 格式
		$val = htmlspecialchars($val);
		$val = str_replace("\011", ' &nbsp;&nbsp;&nbsp;', str_replace('  ', ' &nbsp;', $val));
		$val = ereg_replace("((\015\012)|(\015)|(\012))", '<br />', $val);
		return $val;
	}
	function cSubstr($str, $start, $len) { // 截取中文字符串
		$temp = "<span title='" . $str . "'>" . mb_substr($str, $start, $len, 'utf-8') . "</span>";
		return $temp;
	} 
	
	function actionGetTongzhiByAjax() {
		$userId = $_SESSION['USERID'];
		$sql = "SELECT x.*,count(*) as cnt FROM `oa_message`  x
		left join oa_message_class y on y.className=x.kindName
		where y.isWindow=0
		and not exists(select * from acm_user2message z where z.messageId=x.id and z.userId='{$userId}')";
		$rr = $this->_modelExample->findBySql($sql); 
		// dump($rr);exit;
		// if($rr[0]['cnt']>0){
		echo json_encode($rr[0]);
		exit; 
		// }
	} 
	
	function actionGetMailByAjax() {
		$userId = $_SESSION['USERID'];
		$sql = "SELECT count(*) as cnt FROM mail_db where accepterId='{$userId}' and timeRead='0000-00-00 00:00:00'"; 
		// dump($sql);exit;
		$rr = $this->_modelExample->findBySql($sql);
		echo json_encode($rr[0]);
		exit;
	}

	function GetJingyan() {
		// 判断企业为★用户
		// dump($_SESSION);exit;
		// 判断企业为★用户
		$jinyan = 0;
		$msg = "";
		$dj = 0;
		$str = "SELECT * FROM jifen_comp where 1";
		$re = mysql_fetch_assoc(mysql_query($str));
		if ($re) {
			$jinyan = $re['initJingyan'] + $re['jingyan'];
		}

		if ($jinyan < 501) {
			$msg = '<img src="Resource/Image/star_level1.gif" width="16" height="16" style="vertical-align:middle">';
		}
		if ($jinyan > 500 && $jinyan < 8001) {
			$msg = '<img src="Resource/Image/star_level1.gif" width="16" height="16" style="vertical-align:middle">' . '<img src="Resource/Image/star_level1.gif" width="16" height="16" style="vertical-align:middle">';
		}
		if ($jinyan > 8000 && $jinyan < 40001) {
			$msg = '<img src="Resource/Image/star_level1.gif" width="16" height="16" style="vertical-align:middle">' . '<img src="Resource/Image/star_level1.gif" width="16" height="16" style="vertical-align:middle">';
			$msg .= '<img src="Resource/Image/star_level1.gif" width="16" height="16" style="vertical-align:middle">';
		}
		if ($jinyan > 40000 && $jinyan < 4000001) {
			$msg = '<img src="Resource/Image/star_level1.gif" width="16" height="16" style="vertical-align:middle">' . '<img src="Resource/Image/star_level1.gif" width="16" height="16" style="vertical-align:middle">';
			$msg .= '<img src="Resource/Image/star_level1.gif" width="16" height="16" style="vertical-align:middle">' . '<img src="Resource/Image/star_level1.gif" width="16" height="16" style="vertical-align:middle">';
		}
		if ($jinyan > 4000000) {
			$msg = '<img src="Resource/Image/star_level1.gif" width="16" height="16" style="vertical-align:middle">' . '<img src="Resource/Image/star_level1.gif" width="16" height="16" style="vertical-align:middle">';
			$msg .= '<img src="Resource/Image/star_level1.gif" width="16" height="16" style="vertical-align:middle">' . '<img src="Resource/Image/star_level1.gif" width="16" height="16" style="vertical-align:middle">';
			$msg .= '<img src="Resource/Image/star_level1.gif" width="16" height="16" style="vertical-align:middle">';
		} 
		// 判断用户的级别
		$userJy = 0;
		$userMsg = "";
		$sql = "SELECT * FROM jifen_user where 1 and remoteUserId = '{$_SESSION['USERID']}'";
		$rr = mysql_fetch_assoc(mysql_query($sql));
		if ($rr) {
			$userJy = $rr['remoteJingyan'];
		}
		if ($userJy < 501) {
			$userMsg = '<img src="Resource/Image/user-1.gif" width="16" height="16" title="普通用户" style="vertical-align:middle">';
			$dj = floor($userJy / 50);
		}
		if ($userJy > 500 && $userJy < 5501) {
			$userMsg = '<img src="Resource/Image/user-2.gif" width="16" height="16" title="白银用户" style="vertical-align:middle">';
			$dj = floor(($userJy-500) / 500) + 10;
		}
		if ($userJy > 5500 && $userJy < 15501) {
			$userMsg = '<img src="Resource/Image/user-3.gif" width="16" height="16" title="黄金用户" style="vertical-align:middle">';
			$dj = floor(($userJy-5500) / 1000) + 20;
		}
		if ($userJy > 15500 && $userJy < 35501) {
			$userMsg = '<img src="Resource/Image/user-4.gif" width="16" height="16" title="蓝钻用户" style="vertical-align:middle">';
			$dj = floor(($userJy-15500) / 2000) + 30;
		}
		if ($userJy > 35500) {
			$userMsg = "金钻";
		}

		$msg = '<a href="Help/jifenHelp.html?width=800&height=420&TB_iframe=1" class="thickbox" title="经验值计算规则及排名">' . $msg . '</a>';
		$dj = '<a href="Help/jifenHelp.html?width=800&height=420&TB_iframe=1" class="thickbox" title="经验值计算规则及排名">' . $dj . '</a>'; 
		// $userJy = '<a href="Help/jifenHelp.html" target="_blank" title="获取积分帮助">'.$userJy.'</a>';
		$userMsg = '<a href="Help/jifenHelp.html?width=800&height=420&TB_iframe=1" class="thickbox" title="经验值计算规则及排名">' . $userMsg . '</a>'; 
		// dump();exit;
		return array('msg' => $msg,
			'dj' => $dj,
			'compJy' => $jinyan,
			'jingyan' => $userJy,
			'userMsg' => $userMsg
			);
	}
	function actionTest1() {
		// unset($_SESSION['SEARCH']);
		foreach($_SESSION['SEARCH'] as $key => &$v) {
			// unset($_SESSION['SEARCH'][$key]['toDel']);
		}
		dump($_SESSION);
		exit;
	}
	function actionTest() {
		$title = '色纱回收列表'; 
		// /////////////////////////////模板文件
		$tpl = 'TblList.tpl'; 
		// /////////////////////////////模块定义
		FLEA::loadClass('TMIS_Pager');

		$arr = TMIS_Pager::getParamArray(array('days' => '',
				'years' => '' 
				// 'orderCode'=>'',
				// 'orderId'=>'',
				// 'zhishu'=>'',
				// 'colorSha'=>'',
				// 'ranchangId'=>''
				// 'huaxing'=>''
				));
		if ($arr['orderCode'] != '') {
			$condition[] = array('Order.orderCode', "%{$arr['orderCode']}%", 'like');
		}
		if ($arr['orderId'] != '') {
			$condition[] = array('orderId', $arr['orderId'], '=');
		}
		if ($arr['zhishu'] != '') $condition[] = array('zhishu', "%{$arr['zhishu']}%", 'like');
		if ($arr['colorSha'] != '') $condition[] = array('colorSha', "%{$arr['colorSha']}%", 'like');
		if ($arr['ranchangId'] != '') $condition[] = array('ranchangId', $arr['ranchangId']); 
		// dump($condition);
		$m = &FLEA::getSingleton('Model_Shengchan_Sesha_Income');
		$pager = &new TMIS_Pager($m, $condition, 'id desc');
		$rowset = $pager->findAll(); 
		// dump($rowset);exit;
		if (count($rowset) > 0) {
			foreach ($rowset as &$v) {
				$v['_edit'] = "<a href='" . $this->_url('HuishouShow', array('orderId' => $v['orderId'],
						'ranchangId' => $v['ranchangId'],
						'no_edit' => 1,
						'keepThis' => 'true',
						'width' => 700,
						'baseWindow' => 'parent',
						'TB_iframe' => 1
						)) . "' class='thickbox' ext:qtip='<table border=1><tr><td>aaa</td><td>bbbb</td></tr><tr><td>aaa</td><td>bbbb</td></tr></table>'>实收统计</a>";
				$v['_edit'] .= '&nbsp;' . $this->getEditHtml(array('hsCode' => $v['hsCode'],
						'fromAction' => $_GET['action'])); 
				// $v['_edit'] = "<a href='".$this->_url('CommonEdit',array('id'=>$v['id'],'TB_iframe'=>1))."' title='修改' class='thickbox'>修改</a>";
				$v['_edit'] .= '&nbsp;' . $this->getRemoveHtml($v['id']);
				$v['Order']['orderCode'] = $this->getOrderTrace(($v['Order']['orderCode']), $v['orderId']);
			}
		}
		if ($_GET['no_edit'] == 1) {
			$rowset[] = $this->getHeji($rowset, array('cntKg', 'jingzhong'), 'incomeDate');
		}else {
			$rowset[] = $this->getHeji($rowset, array('cntKg', 'jingzhong'), '_edit');
		}

		foreach($rowset as &$v) {
			$v['cntKg'] = "<strong>{$v['cntKg']}</strong>";
			$v['jingzhong'] = "<strong>{$v['jingzhong']}</strong>";
		} 
		// dump($rowset);exit;
		$caiwu = &FLEA::getAppInf('hasCaiwu');
		$arr_field_info = array('_edit' => '操作',
			"incomeDate" => array('text' => '收纱日期', 'width' => 100),
			"initCode" => "染单号阿斯顿发的散发水电费",
			"hsCode" => "回收单号",
			"Ranchang.compName" => "染厂",
			"Order.orderCode" => "流转单号",
			"zhishu" => "支数",
			"colorSha" => "颜色",
			"cntKg" => "投料数(kg)",
			"jingzhong" => "净重(kg)", 
			// "cntJian" =>"件数",
			// "vatNum" =>"缸号",
			);

		$smarty = &$this->_getView();
		$smarty->assign('title', $title);
		$smarty->assign('add_display', 'none');
		$smarty->assign('arr_field_info', $arr_field_info); 
		// $smarty->assign('left_width','600');
		$smarty->assign('arr_field_value', $rowset); 
		// dump($rowset);exit;
		if ($_GET['no_edit'] != 1) {
			$smarty->assign('arr_condition', $arr);
			$smarty->assign('add_url', $this->_url('ListOrderForAdd'));
			$smarty->assign('page_info', $pager->getNavBar($this->_url($_GET['action'], $arr)) . "<font color='red'>测试文字</font>");
			$smarty->assign('print_href', 'adfads');
			$smarty->assign('fn_export', true);
		} 
		// $smarty->display($tpl);
		$smarty->display('TblList.tpl');
	} 
	// 根据id取得通知内容，返回为json
	function actionGetContentByAjax() {
		$row = $this->_modelExample->findAll(array('id' => $_GET['id']));
		if ($_SESSION['USERID'] != '') {
			if ($row[0]['kindName'] == '行政通知') {
				$sql = "SELECT count(*) as cnt FROM `acm_user2message` where messageId='{$_GET['id']}' and userId='{$_SESSION['USERID']}'";
				$rr = mysql_fetch_assoc(mysql_query($sql));
				if ($rr['cnt'] == 0) {
					$arr = array('userId' => $_SESSION['USERID'],
						'messageId' => $_GET['id'],
						);
					if ($arr && $arr['userId'] != '')
						$this->_modelAcmOa->save($arr); 
					// $dbo=&FLEA::getDBO(false);dump($dbo->log);exit;
				}
			}
		}
		$row = $this->_modelExample->find(array('id' => $_GET['id']));
		echo json_encode($row);
		exit;
	}

	/**
    * @desc ：获取维护到期情况
    * Time：2019/05/28 16:13:51
    */
    function actionGetMaintenanceInfo(){
	    // 易客宝中的客户名称
	    $ykbName = & FLEA::getAppInf('ykbName');
	    $obj_api = FLEA::getSingleton('Api_RequestYkb');
	    if($ykbName){
	    	$r = $obj_api->callApi(array(
	        'params'=>json_encode(array('compName'=>$ykbName)),
	        // 'isDebug'=>1
	    	));
	    }
	    $ret = json_decode($r,true);
	    $maintenance['success'] = true;
	    $maintenance['showRemind'] = $ret['data'][0]['expire'];
	    $current = time();
		$maintenance['interval'] = ($ret['data'][0]['end_time'] - $current)/(60*60*24);// 间隔天数;
		$maintenance['end_day'] = $ret['data'][0]['end_day'];
	    $maintenance['msg'] = $ret['data'][0]['msg'];
	    // 获取是否有当天的已读记录
		$mMaintenance = & FLEA::getSingleton('Model_Acm_Maintenance');
		if($maintenance['showRemind']){
			if($maintenance['interval']<=0){// 已过期，搜索当天是否有提醒记录，有则不再提醒
				$rows = $mMaintenance->find(array('remindDate'=>date('Y-m-d'),'userId'=>$_SESSION['USERID']));
				if($rows['id']>0) $maintenance['showRemind'] = false;
			}else{// 未过期，但是在30天以内。查找今天前是否有记录生成，有则不再提醒
				$condition[] = array('remindDate',date('Y-m-d'),'<=');
				$condition[] = array('userId',$_SESSION['USERID'],'=');
				$rows = $mMaintenance->find($condition);
				if($rows['id']>0) $maintenance['showRemind'] = false;
			}
		}
		// dump($maintenance);die;
	    echo json_encode($maintenance);exit;
    }

	/**
	 * @desc ：生成维护提醒记录
	 * Time：2019/10/18 16:37:36
	 * @author Wuyou
	*/
	function actionCreateMaintenance(){
		$mMaintenance = & FLEA::getSingleton('Model_Acm_Maintenance');
		$row = array(
			'userId'     => $_SESSION['USERID']+0,
			'kind'       => $_POST['interval']>0?0:1,
			'remindDate' => date('Y-m-d')
		);
		$mMaintenance->save($row);
		echo json_encode(array('success'=>true));exit;
	}


   /**
	 * @desc ：老板驾驶舱报表
	 * Time：2018/06/06 13:42:04
	 * @author Wuyou
	*/
	function actionBossReport(){
		$textField = $_REQUEST['type']=='Saler'?'业务员':($_REQUEST['type']==''?'客户':'月份');
		$_column = array(
			'textField' =>$textField,
			'nxCntM'    =>'内销下单(M)',
			'nxMoney'   =>'金额(RMB)',
			'wxCntM'    =>'外销下单(M)',
			'wxMoney'   =>'金额(RMB)',
			'sum'       =>'小计(RMB)',
		);
		
		$_column2 = array(
			'textField' =>$textField,
			'一月'    	=>'一月',
			'二月'  	=>'二月',
			'三月'   =>'三月',
			'四月'    =>'四月',
			'五月'    =>'五月',
			'六月'     =>'六月',
			'七月'   =>'七月',
			'八月'   =>'八月',
			'九月'    =>'九月',
			'十月'      =>'十月',
			'十一月'      =>'十一月',
			'十二月'    =>'十二月',
			'sum'    =>'合计（ M ）',
		);


		$mOrder = & FLEA::getSingleton('Model_Trade_Order');

		// 获得最小年份
		$sql = "SELECT min(orderDate) as minDate FROM trade_order WHERE orderDate<>'0000-00-00'";
		$temp = $mOrder->findBySql($sql);
		$cntYear = date('Y') - substr($temp[0]['minDate'], 0,4);
		$yearArr = array();
		for ($i=0; $i <= $cntYear; $i++) {
			$yearArr[] = $temp[0]['minDate'] + $i;
		}
		$year = isset($_REQUEST['year'])?$_REQUEST['year']:date('Y');
		
		//构造映射关系数组
		$months = array('1'=>'一月','2'=>'二月','3'=>'三月','4'=>'四月','5'=>'五月','6'=>'六月','7'=>'七月','8'=>'八月','9'=>'九月','10'=>'十月','11'=>'十一月','12'=>'十二月');
		$month = array('一月','二月','三月','四月','五月','六月','七月','八月','九月','十月','十一月','十二月');
		$chartArr =  $MonthArr = array();
		$groupBy = $_REQUEST['type']=='Saler'?'b.traderId':'b.clientId';
		$groupArr = $_REQUEST['type']=='Saler'?"traderId":"clientId";
		$showText = $_REQUEST['type']=='Saler'?"employName":"compName";
        $sql = "SELECT
			 	sum(if(t.unit='M',t.cntYaohuo,round(t.cntYaohuo*0.9144,1))) as cntM,
				round(
					sum(
						IF (
							t.unit = 'Y',
							t.danjia / 0.9144 * t.cntYaohuo * b.huilv,
							t.danjia  * t.cntYaohuo * b.huilv
						)
					),
					2
				) AS money,
				LEFT (b.orderDate, 7) AS month,c.compName,
				b.clientId,b.traderId,d.employName
                FROM trade_order2product t
                LEFT JOIN trade_order b on t.orderId=b.id
                LEFT JOIN jichu_client c on c.id=b.clientId
                LEFT JOIN jichu_employ d on d.id=b.traderId
                WHERE 1
                and b.orderDate like '{$year}%'
                GROUP BY left(b.orderDate,7),{$groupBy}";
        $re = $mOrder->findBySql($sql);
        foreach ($re as $kk => $vv) {
        	if($_REQUEST['type']=='Saler' && !$vv['traderId']) continue;
            $index = substr($vv['month'], 5,2)/1;
            $dataArr[$vv[$groupArr]][$months[$index]] = $vv['money']+0;
            $dataArr[$vv[$groupArr]]['textField'] = $vv[$showText] ;
            $dataArr[$vv[$groupArr]]['sum'] = $dataArr[$vv[$groupArr]]['sum']+$vv['money'] ;
        }
        $rowset = array_values($dataArr);
        $heji = $this->getHeji($rowset,array('一月','二月','三月','四月','五月','六月','七月','八月','九月','十月','十一月','十二月','sum'),'textField');
	    $rowset[] = $heji;

     	$arr = array(
        	'cntM' => '数量M',
        	'money' => '金额',
        );
        $default = array();
        for($i=1;$i<=12;$i++){
        	$default[$i] = 0;
        }
        foreach ($arr as $key => &$value) {
    		$dataBar_month = array();
            $legend[] = $value;
            $dataBar_month = $default;
        	foreach ($re as $kk => $vv) {
                $index = substr($vv['month'], 5,2)/1;
                $dataBar_month[$index] = $dataBar_month[$index]+$vv[$key];
            }
    		$monthData[] = array('name'=>$value,'type'=>'bar','data'=>array_values($dataBar_month));
		}
        $MonthArr = array('xData'=>$month,'valueData'=>$monthData,'legendData'=>$legend);
        //搜索条件
		$arr_condition = array('year'=>isset($_REQUEST['year'])?$_REQUEST['year']:date('Y'));
		
		// 为试用版时，显示倒计时提醒
		$canShowBoss = $this->getBossReportParam();

		//获取小程序二维码
		// $miniPro = $this->getMiniUrl();

		$smarty = & $this->_getView();
		$smarty->assign('yearArr',$yearArr);
		$smarty->assign('chartArr',$rowset);
		$smarty->assign('canShowBoss',$canShowBoss);
		$smarty->assign('MonthArr',$MonthArr);
		$smarty->assign('arr_condition',$arr_condition);
		$smarty->assign('_column',$_column);
		$smarty->assign('_column2',$_column2);
		$smarty->assign('type',(isset($_REQUEST['type']) && $_REQUEST['type']!='')?$_REQUEST['type']:'Client');
		$smarty->assign('miniUrl',$miniPro['miniUrl']);
		$smarty->assign('currPro',$miniPro['currPro']);
		$smarty->display('BossReport.tpl');
	}

	// 获得老板驾驶舱显示参数
	function getBossReportParam(){
		// 获得当前角色是否为老板的信息
		$isBoss = 1;
		if($_SESSION['USERNAME']!='admin'){
			$sql = "SELECT * FROM acm_userdb WHERE id = {$_SESSION['USERID']}";
			$user = $this->_modelExample->findBySql($sql);
			$isBoss = $user[0]['isBoss'];
		}
		// 判断是否可以显示老板驾驶舱
		$days = 0;
		if($isBoss){
			/*2019年9月18日 09:52:59 关闭老板驾驶舱配置信息 by shen
			$bossReport = & FLEA::getAppInf('bossReport');
			if(!$bossReport['isShow']){//判断是否为试用版
				$isShow = false;
			}else{//未使用版则计算剩余使用天数，为0了则不显示
				if($bossReport['isTrialVer']){
					$days = (strtotime($bossReport['tryExpDate']) - strtotime(date('Y-m-d')))/24/3600;
					if($days<=0){
						$isShow = false;
					}else{
						$isShow = true;
					}
				}else{
					$isShow = true;
				}
			}*/
			$isShow = true;
		}else{
			$isShow = false;
		}
		return array('isShow'=>$isShow,'isTrialVer'=>$bossReport['isTrialVer'],'days'=>$days);
	}

  /**
	 * ps ：月度汇总报表
	 * Time：2019-09-03 08:44:05
	 * @author ShenHao
	*/
	function actionGetReportData(){
		$mOrder = & FLEA::getSingleton('Model_Trade_Order');
		$type = $_REQUEST['type'];
		$year = $_REQUEST['year'];
		// dump($_REQUEST);exit;
		$groupBy = $type=='Month'?'left(orderDate,7)':($type=='Client'?'clientId':'traderId');
		$finalGroup = $type=='Month'?'month':($type=='Client'?'clientId':'traderId');
		$orderBy = $type=='Month'?'month':'nxMoney desc,wxMoney desc';
		$textField = $type=='Month'?'month':($type=='Client'?'compName':'employName');
		// 构造SQL
        $sqlNx = "SELECT b.clientId,b.traderId,left(b.orderDate,7) as month,
        				 sum(if(t.unit='M',t.cntYaohuo,round(t.cntYaohuo*0.9144,1))) as nxCntM,
                         round(
							sum(
								IF (
									t.unit = 'Y',
									t.danjia / 0.9144 * t.cntYaohuo * b.huilv,
									t.danjia  * t.cntYaohuo * b.huilv
								)
							),
							2
						) as nxMoney
                        FROM trade_order2product t
                        LEFT JOIN trade_order b on t.orderId=b.id
                        WHERE 1
                        and b.orderDate like '{$year}%'
                        GROUP BY {$groupBy}";

     
        $sql = "SELECT
        				a.compName,e.employName,t.month,t.clientId,t.traderId,
        				round(sum(t.nxCntM),2) as nxCntM,round(sum(t.nxMoney),2) as nxMoney
                from (
		                {$sqlNx}
            	) t
                left join jichu_client a on t.clientId=a.id
                left join jichu_employ e on t.traderId=e.id
	            GROUP BY {$finalGroup}
	            ORDER BY {$orderBy}";
	    $rowset = $mOrder->findBySql($sql);
	    foreach ($rowset as $k => &$v) {
	    	$v['textField'] = $v[$textField];
	    	$v['sum'] = round($v['nxMoney'] + $v['wxMoney'],2);
	    }

	    $heji = $this->getHeji($rowset,array('nxCntM','nxMoney','sum'),'textField');
	    $rowset[] = $heji;
	    // dump($rowset);exit;
	    echo json_encode(array(
	      'success'=>true,
	      'rows'=>$rowset,
	      'total'=>count($rowset)
	    ));
	    exit;
	}

	/**
	 * ps ：月度汇总报表(内外销)
	 * Time：2019-09-03 08:44:05
	 * @author ShenHao
	*/
	function actionGetReportDataNwx(){
		$mOrder = & FLEA::getSingleton('Model_Trade_Order');
		$type = $_REQUEST['type'];
		$year = $_REQUEST['year'];
		// dump($_REQUEST);exit;
		$groupBy = $type=='Month'?'left(orderDate,7)':($type=='Client'?'clientId':'traderId');
		$finalGroup = $type=='Month'?'month':($type=='Client'?'clientId':'traderId');
		$orderBy = $type=='Month'?'month':'nxMoney desc,wxMoney desc';
		$textField = $type=='Month'?'month':($type=='Client'?'compName':'employName');
		// 构造SQL
        $sqlNx = "SELECT b.clientId,b.traderId,left(b.orderDate,7) as month,
        				 sum(if(t.unit='M',t.cntYaohuo,round(t.cntYaohuo*0.9144,1))) as nxCntM,
                         round(
							sum(
								IF (
									t.unit = 'Y',
									t.danjia / 0.9144 * t.cntYaohuo * b.huilv,
									t.danjia  * t.cntYaohuo * b.huilv
								)
							),
							2
						) as nxMoney,0 as wxCntM,0 as wxMoney
                        FROM trade_order2product t
                        LEFT JOIN trade_order b on t.orderId=b.id
                        WHERE b.xsType='内销'
                        and b.orderDate like '{$year}%'
                        GROUP BY {$groupBy}";

        $sqlWx = "SELECT b.clientId,b.traderId,left(b.orderDate,7) as month,
        				 0 as nxCntM,0 as nxMoney,sum(if(t.unit='M',t.cntYaohuo,round(t.cntYaohuo*0.9144,1))) as wxCntM,
                         round(
							sum(
								IF (
									t.unit = 'Y',
									t.danjia / 0.9144 * t.cntYaohuo * b.huilv,
									t.danjia  * t.cntYaohuo * b.huilv
								)
							),
							2
						) as  wxMoney
                        FROM trade_order2product t
                        LEFT JOIN trade_order b on t.orderId=b.id
                        WHERE b.xsType='外销'
                        and b.orderDate like '{$year}%'
                        GROUP BY {$groupBy}";
     
        $sql = "SELECT
        				a.compName,e.employName,t.month,t.clientId,t.traderId,
        				round(sum(t.nxCntM),2) as nxCntM,round(sum(t.wxCntM),2) as wxCntM,
        				sum(t.nxMoney) as nxMoney,sum(t.wxMoney) as wxMoney
                from (
		                {$sqlNx}
		                UNION
		                {$sqlWx}
            	) t
                left join jichu_client a on t.clientId=a.id
                left join jichu_employ e on t.traderId=e.id
	            GROUP BY {$finalGroup}
	            ORDER BY {$orderBy}";
	    $rowset = $mOrder->findBySql($sql);
	    foreach ($rowset as $k => &$v) {
	    	$v['textField'] = $v[$textField];
	    	$v['sum'] = round($v['nxMoney'] + $v['wxMoney'],2);
	    }

	    $heji = $this->getHeji($rowset,array('nxCntM','wxCntM','nxMoney','wxMoney','sum'),'textField');
	    $rowset[] = $heji;
	    // dump($rowset);exit;
	    echo json_encode(array(
	      'success'=>true,
	      'rows'=>$rowset,
	      'total'=>count($rowset)
	    ));
	    exit;
	}
}

?>