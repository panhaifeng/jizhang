<?php
FLEA::loadClass('TMIS_Controller');
class Controller_Acm_User extends Tmis_Controller {
	var $_modelUser;
	var $funcId = 1;
	function Controller_Acm_User() {
		// if(!//$this->authCheck()) die("禁止访问!");
		$this->_modelUser = FLEA::getSingleton('Model_Acm_User');
	}

	function actionChangePwd() {
		// //$this->authCheck(20);
		$realName = $_SESSION['REALNAME'];
		$userName = $_SESSION['USERNAME'];
		$userId = $_SESSION['USERID'];
		$aUser = $this->_modelUser->find($userId);
		$smarty = $this->_getView();
		$smarty->assign('aUser', $aUser);
		$smarty->assign('title', "修改密码");
		$smarty->display('Acm/ChangePwd.tpl');
	}
	function actionRight() {
		$this->authCheck('7-1-1');
		if (isset($_POST[key]) && $_POST[key] != "") $condition = "realName like '%$_POST[key]%'";
		FLEA::loadClass('TMIS_Pager');
		$arr = TMIS_Pager::getParamArray(array('key' => ''
				));
		$condition = array();
		if ($arr['key'] != '') {
			$condition[] = array('id', "%{$arr['key']}%", 'like', 'or');
			$condition[] = array('userName', "%{$arr['key']}%", 'like', 'or');
			$condition[] = array('realName', "%{$arr['key']}%", 'like');
		}
		$pager = new TMIS_Pager($this->_modelUser, $condition);
		$rowSet = $pager->findAll(); 
		// dump($rowSet[0]);
		if ($rowSet) foreach ($rowSet as &$v) {
			$v['roleName'] = join(',', array_col_values($v['roles'], 'roleName'));
			if ($v['userName'] != 'admin') {
				$v['_edit'] = $this->getEditHtml(array('id' => $v['id'])) . '&nbsp;&nbsp;' . $this->getRemoveHtml(array('id' => $v['id']));
			}else {
				$v['_edit'] = "<font color='#cccccc'>禁止操作</font>";
			}
			$text1 = $v['qrCodeVerify']>0?'已开启':'未开启';
			$text2 = $v['qrCodeVerify']>0?'[关闭]':'[开启]';
			$v['_edit2'] = "{$text1}&nbsp;<a href='".$this->_url('SetQrVerify',array('id'=>$v['id'],'qrCodeVerify'=>1-$v['qrCodeVerify']))."'>{$text2}</a>";
		}
		$arrFieldInfo = array("id" => "编号",
			"userName" => "用户名",
			"realName" => "真实姓名",
			"shenfenzheng" => "身份证号",
			"roleName" => "角色",
			"showQrCodeVerify" => "是否验证身份",
			'_edit' => '操作',
			'_edit2'       =>array('text'=>'二维码验证功能','width'=>110)
		);

		$pk = $this->_modelJichuDepartment->primaryKey;
		$smarty = &$this->_getView();
		$smarty->assign('arr_condition', $arr);
		$smarty->assign('arr_edit_info', $arr_edit_info);
		$smarty->assign("arr_field_info", $arrFieldInfo);
		$smarty->assign("title", "用户管理");
		$smarty->assign("arr_field_value", $rowSet); 
		// $smarty->assign('search_display', 'none');
		$smarty->assign('pk', 'id');
		$smarty->assign("page_info", $pager->getNavBar($this->_url('right')));
		$smarty->display("TblList.tpl");
	}
	function actionIndex() {
		$arrLeftList = array("Acm_User" => "用户管理",
			"Acm_Role" => "角色管理",
			"Acm_Func" => "模块定义"
			);

		$smarty = &$this->_getView();
		$smarty->assign('arr_left_list', $arrLeftList);
		$smarty->assign('title', '用户管理');
		$smarty->assign('caption', '权限管理'); 
		// $smarty->assign('child_caption', "应付款凭据录入");
		$smarty->assign('controller', 'Acm_User');
		$smarty->assign('action', 'right');
		$smarty->display('Welcome.tpl');
	}
	function actionAdd() {
		$this->_edit(array());
	}
	function actionEdit() {
		$aUser = $this->_modelUser->find($_GET[id]); 
		// $dbo = FLEA::getDbo(false);
		// dump($dbo->log);exit;
		$this->_edit($aUser);
	}
	function actionSave() {
		if (empty($_POST['id'])) {
			$sql = "SELECT count(*) as cnt FROM `acm_userdb` where userName='" . $_POST['userName'] . "'";
			$rr = $this->_modelUser->findBySql($sql);
			if ($rr[0]['cnt'] > 0) {
				js_alert('用户名重复!', null, $this->_url('add'));
			}
		}else {
			// 修改时判断是否重复
			$str1 = "SELECT count(*) as cnt FROM `acm_userdb` where id!=" . $_POST['id'] . " and (userName='" . $_POST['userName'] . "')";
			$ret = $this->_modelUser->findBySql($str1);
			if ($ret[0]['cnt'] > 0) {
				js_alert('用户名重复!', null, $this->_url('Edit', array('id' => $_POST['id'])));
			}
		}
		$this->_modelUser->save($_POST);
		if ($_POST[Submit] == '确认修改') js_alert('修改密码成功!', 'window.close()');
		js_alert('', 'window.parent.showMsg("保存成功")', $this->_url($_POST['from']?$_POST['from']:'ChangePwd'));
	}
	function actionRemove() {
		$this->_modelUser->removeByPkv($_GET[id]);
		js_alert(null, "window.parent.showMsg('成功删除')", $this->_url('right')); 
		// redirect($this->_url('right'));
	}
	function _edit($Arr) {
		// 查找所有角色
		$role = FLEA::getSingleton('Model_Acm_Role');
		$role->clearLinks();
		$roleArr = $role->findAll();
		$Arr['Role'] = $roleArr; 
		// 判断是否选中角色
		if ($Arr['roles']) {
			foreach ($Arr['Role'] as $key => &$v) {
				foreach ($Arr['roles'] as &$vv) {
					if ($vv['id'] == $v['id']) {
						$v['isChecked'] = 1;
						break;
					}
				}
			}
		} 
		// 查找业务员
		$m = &FLEA::getSingleton("Model_Jichu_Employ");
		$arr = $m->getTrader();
		$Arr['Trader'] = $arr;
		if ($Arr['traders']) {
			foreach ($Arr['traders'] as &$v) {
				if ($v['isFire'] != 0) {
					$v['employName'] .= "<font color='red'>(已离职)</font>";
					$Arr['Trader'][] = $v;
				}
			} 
			// 是否选中业务员
			foreach ($Arr['Trader'] as $key => &$v) {
				foreach ($Arr['traders'] as &$vv) {
					if ($vv['id'] == $v['id']) {
						$v['isChecked'] = 1;
						break;
					}
				}
			}
		} 
		// dump($Arr);exit;
		$smarty = $this->_getView();
		$smarty->assign('aUser', $Arr);
		$smarty->assign('title', "用户管理编辑");
		$smarty->display('Acm/UserEdit.tpl');
	}

	/**
	 * @desc ：关闭/开启二维码验证功能
	 * Time：2019/07/19 12:51:26
	 * @author Wuyou
	*/
	function actionSetQrVerify(){
		$sql = "UPDATE acm_userdb SET qrCodeVerify={$_GET['qrCodeVerify']} WHERE id='{$_GET['id']}'";
		$this->_modelUser->execute($sql);
		$text = $_GET['qrCodeVerify']>0?'开启':'关闭';
		js_alert($text.'成功！','',$this->_url('Right'));
	}
}

?>