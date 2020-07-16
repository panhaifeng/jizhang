<?php
FLEA::loadClass('TMIS_Controller');
class Controller_Acm_SetParamters extends Tmis_Controller {
	var $_modelExample;
	var $title = "系统参数设置";
	var $funcId = 8;
	var $_tplEdit = "Acm/ParamtersEdit.tpl";

	function Controller_Acm_SetParamters() {
		/**
		 * if(!//$this->authCheck()) die("禁止访问!");
		 */
		$this->_modelExample = &FLEA::getSingleton('Model_Acm_SetParamters');
	}

	function actionRight() {
		// //////////////////////////////标题
		$title = '系统参数编辑编辑'; 
		// /////////////////////////////模板文件
		$tpl = 'Acm/ParamtersEdit.tpl';
		$this->authCheck('7-4');

		$rowItem = array();
		$sql = "select * from sys_set where 1 order by id";
		$rowset = $this->_modelExample->findBySql($sql);
		if (count($rowset) > 0) {
			foreach($rowset as $v) {
				$rowItem[$v['item']] = $v['value'];
			}
		} 
		// dump($rowItem);exit;
		$smarty = &$this->_getView();
		$smarty->assign('title', $title);
		$smarty->assign('arr_field_info', $arr_field_info);
		$smarty->assign('rowset', $rowset);
		$smarty->assign('row', $rowItem); 
		// $smarty->assign('arr_condition', $arr);
		 
		// $smarty->assign('arr_js_css',$this->makeArrayJsCss(array('calendar')));
		// $smarty->assign('page_info',$pager->getNavBar($this->_url($_GET['action'],$arr))."<font color='red'>黄色代表停止往来</font>");
		$smarty->display($tpl);
	} 
	// 常用单价设置
	function actionRight1() {
		// //////////////////////////////标题
		$title = '常用单价设置'; 
		// /////////////////////////////模板文件
		$tpl = 'Acm/ParamtersEdit1.tpl';
		$this->authCheck('7-3');

		$rowItem = array();
		$sql = "select * from sys_set where 1 order by id";
		$rowset = $this->_modelExample->findBySql($sql);
		if (count($rowset) > 0) {
			foreach($rowset as $v) {
				$rowItem[$v['item']] = $v['value'];
			}
		} 
		// dump($rowItem);exit;
		$smarty = &$this->_getView();
		$smarty->assign('title', $title);
		$smarty->assign('arr_field_info', $arr_field_info);
		$smarty->assign('rowset', $rowset);
		$smarty->assign('row', $rowItem); 
		// $smarty->assign('arr_condition', $arr);
		 
		// $smarty->assign('arr_js_css',$this->makeArrayJsCss(array('calendar')));
		// $smarty->assign('page_info',$pager->getNavBar($this->_url($_GET['action'],$arr))."<font color='red'>黄色代表停止往来</font>");
		$smarty->display($tpl);
	}

	function actionSave() {
		// dump($_POST);exit;
		if ($_POST) {
			foreach($_POST['value'] as $key => &$v) {
				// if($v=='') $row[]
				$rowset[] = array("item" => $_POST['item'][$key],
					"value" => $_POST['value'][$key],
					"itemName" => $_POST['itemName'][$key],
					);
			} 
			// dump($rowset);exit;
			if (count($rowset) > 0) {
				foreach($rowset as $vv) {
					if ($vv['value'] != '') {
						// $str1="select count(*) as cnt from sys_set where item='WJGXB'";
						$str1 = "select count(*) as cnt from sys_set where item='{$vv['item']}'";
						$re = mysql_fetch_assoc(mysql_query($str1));

						if ($re['cnt'] > 0) {
							$sql = "update `sys_set` set value='{$vv['value']}' where item='{$vv['item']}'";
							$this->_modelExample->execute($sql);
						}else {
							$sql1 = "insert into sys_set(item,itemName,value) values ('{$vv['item']}','{$vv['itemName']}','{$vv['value']}')"; 
							// echo $sql1;exit;
							$this->_modelExample->execute($sql1);
						}
					}else {
						$str = "delete from sys_set where item='{$vv['item']}'";
						$this->_modelExample->execute($str);
					}
				}

				js_alert(null, 'window.parent.showMsg("保存成功!");window.history.go(-1);');
			}
		}
	} 
	// 设置坯纱管理模式
	function actionSetPisha() {
		$title = '设置坯纱管理模式';
		$tpl = 'Acm/setPishaMode.tpl';
		$this->authCheck();
		$rowItem = array();
		$sql = "select * from sys_set where 1 order by id";
		$rowset = $this->_modelExample->findBySql($sql);
		if (count($rowset) > 0) {
			foreach($rowset as $v) {
				// dump($v);exit;
				$rowItem[$v['item']] = $v['value'];
			}
		}
		$smarty = &$this->_getView();
		$smarty->assign('title', $title);
		$smarty->assign('arr_field_info', $arr_field_info);
		$smarty->assign('rowset', $rowset);
		$smarty->assign('row', $rowItem);
		$smarty->display($tpl);
	} 
	// 保存坯纱管理模式
	function actionSavePishMode() {
		if ($_POST) {
			$sql = "select count(*) as cnt from sys_set where item='pishaMode'";
			$r = $this->_modelExample->findBySql($sql);
			if ($r[0]['cnt'] > 0)$str = "UPDATE sys_set set `value`='{$_POST['sel']}' where item='pishaMode'";
			else $str = "insert into sys_set(item,itemName,value) values ('pishaMode','坯纱模式:0本厂1染厂','{$_POST['sel']}')"; 
			// dump($str);exit;
			$this->_modelExample->execute($str);

			$sql = "select count(*) as cnt from sys_set where item='pishaInit'";
			$rr = $this->_modelExample->findBySql($sql);
			if ($rr[0]['cnt'] > 0)$str1 = "UPDATE sys_set set `value`='{$_POST['pishaInit']}' where item='pishaInit'";
			else $str1 = "insert into sys_set(item,itemName,value) values ('pishaInit','坯纱初始化','{$_POST['pishaInit']}')";
			$this->_modelExample->execute($str1);
			js_alert(null, 'window.parent.showMsg("保存成功!");window.history.go(-1);');
		}
	} 
	// 工艺计划一览开关
	function actionSetGyPlan() {
		$sql = "select * from sys_set where item='Plan_Gongyi'";
		$row = $this->_modelExample->findBySql($sql); 
		// dump($row);exit;
		$smarty = &$this->_getView();
		$smarty->assign('title', '工艺计划开关');
		$smarty->assign('row', $row[0]);
		$smarty->display('Acm/SetGyPlan.tpl');
	} 
	// 保存工艺计划一览开关数据
	function actionSaveGyPlan() {
		// dump($_POST);exit;
		$str = "select count(*) as cnt from sys_set where item='Plan_Gongyi'";
		$row = mysql_fetch_assoc(mysql_query($str));
		if ($row['cnt'] > 0) {
			$str = "UPDATE sys_set set `value`='{$_POST['plan']}' where item='Plan_Gongyi'";
			$this->_modelExample->execute($str);
			js_alert(null, 'window.parent.showMsg("保存成功!");window.history.go(-1);');
		}else {
			$sql1 = "insert into sys_set(item,itemName,value) values ('{$_POST['item']}','{$_POST['itemName']}','{$_POST['plan']}')";
			$this->_modelExample->execute($sql1);
			js_alert(null, 'window.parent.showMsg("保存成功!");window.history.go(-1);');
		}
	} 
	// 设置积分系统的网站路径
	function actionSetNetByJifen() {
		// 积分系统的网站路径
		$title = '积分系统的网站路径';
		$tpl = 'Acm/setNetByJifen.tpl';
		$this->authCheck();
		$rowItem = array();
		$sql = "select * from sys_set where 1 order by id";
		$rowset = $this->_modelExample->findBySql($sql);
		if (count($rowset) > 0) {
			foreach($rowset as $v) {
				$rowItem[$v['item']] = $v['value'];
			}
		}
		$smarty = &$this->_getView();
		$smarty->assign('title', $title);
		$smarty->assign('arr_field_info', $arr_field_info);
		$smarty->assign('rowset', $rowset);
		$smarty->assign('row', $rowItem);
		$smarty->display($tpl);
	} 
	// 设置厂编显示名称,有些厂以花型作为厂编，所以下面需要显示花型,
	// 有些厂有厂编，下面需要显示厂编。
	// 这个功能移动到工具箱中
	// function actionSetManuCode() {
	// $sql="select * from sys_set where item='manuCodeName'";
	// $rowset = $this->_modelExample->findBySql($sql);
	// $smarty = & $this->_getView();
	// $smarty->assign('row',$rowset[0]);
	// $smarty->display('Acm/SetManuCode.tpl');
	// }
	// 单一参数设置时的通用保存action
	function actionCommonSave() {
		$this->_modelExample->save($_POST);
		js_alert(null, 'window.parent.showMsg("保存成功")', $this->_url('SetManuCode'));
	}
}

?>