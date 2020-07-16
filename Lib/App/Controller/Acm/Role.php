<?php
FLEA::loadClass('TMIS_Controller');
class Controller_Acm_Role extends Tmis_Controller {
	var $_modelRole;
	var $funcId = 22;
	function Controller_Acm_Role() {
		$this->_modelRole = FLEA::getSingleton('Model_Acm_Role');
	}
	function actionRight() {
		$this->authCheck('7-1-2');
		if (isset($_POST[key]) && $_POST[key] != "") $condition = "roleName like '%$_POST[key]%'";
		FLEA::loadClass('TMIS_Pager');
		$arr = TMIS_Pager::getParamArray(array('key' => ''
				));
		$condition = array();
		if ($arr['key'] != '') {
			$condition[] = array('id', "%{$arr['key']}%", 'like', 'or');
			$condition[] = array('roleName', "%{$arr['key']}%", 'like');
		}
		$pager = new TMIS_Pager($this->_modelRole, $condition);
		$rowSet = $pager->findAll();
		if (count($rowSet) > 0) {
			foreach($rowSet as &$v) {
				// $row[ass] = "<a href='?controller=Acm_Role&action=assignFunc&id=$row[id]'>分配权限</a>";
				$v['_edit'] .= $this->getEditHtml(array('id' => $v['id'],
						'fromAction' => $_GET['action']
						)) . '&nbsp;&nbsp;' . $this->getRemoveHtml(array('text' => '删除',
						'id' => $v['id']
						));
			}
		}
		$smarty = &$this->_getView();
		$smarty->assign("title", "组管理");
		$smarty->assign('arr_condition', $arr);
		$smarty->assign('pk', 'id'); 
		// $smarty->assign('arr_edit_info',$arr_edit_info);
		$smarty->assign("arr_field_info", array("id" => "编号",
				"roleName" => "角色名",
				'_edit' => '操作'
				));
		$smarty->assign("arr_field_value", $rowSet); 
		// $smarty->assign('search_display', 'none');
		$smarty->assign("page_info", $pager->getNavBar($this->_url('right')));
		$smarty->display("TblList.tpl"); 
		// $smarty->display("Acm/RoleList.tpl");
	}
	function actionAdd() {
		$this->_edit(array());
	}
	function actionEdit() {
		$aRole = $this->_modelRole->find($_GET[id]);
		$this->_edit($aRole);
	}
	function actionSave() {
		if (empty($_POST['id'])) {
			$sql = "SELECT count(*) as cnt FROM `acm_roledb` where roleName='" . $_POST['roleName'] . "'";
			$rr = mysql_fetch_assoc(mysql_query($sql)); 
			// dump($rr);exit;
			if ($rr['cnt'] > 0) {
				js_alert('角色名称重复!', null, $this->_url('add'));
			}
		}else {
			// 修改时判断是否重复
			$str1 = "SELECT count(*) as cnt FROM `acm_roledb` where id!=" . $_POST['id'] . " and (roleName='" . $_POST['roleName'] . "')";
			$ret = mysql_fetch_assoc(mysql_query($str1));
			if ($ret['cnt'] > 0) {
				js_alert('角色名称重复!', null, $this->_url('Edit', array('id' => $_POST['id'])));
			}
		}
		$this->_modelRole->save($_POST);
		if ($_POST['fromAction'] != '')
			$url = $this->_url($_POST['fromAction']);
		else {
			if ($_POST['Submit'] == '保存')
				$url = $this->_url('right');
			else $url = $this->_url('add');
		}
		js_alert(null, "window.parent.showMsg('保存成功!')", $url);
	}
	function actionRemove() {
		$this->_modelRole->removeByPkv($_GET[id]);
		redirect($this->_url('right'));
	}
	function _edit($arr) {
		// //$this->authCheck($this->funcId);
		$smarty = $this->_getView();
		$smarty->assign('title', '组管理编辑');
		$smarty->assign('aRole', $arr);
		$smarty->display('Acm/RoleEdit.tpl');
	}

	function actionAssignFunc() {
		// 为角色分配权限
		// //$this->authCheck($this->funcId);
		$aRole = $this->_modelRole->find($_GET[id]);
		$smarty = $this->_getView();
		$smarty->assign("aRole", $aRole);
		$smarty->display("Acm/AssignFunc.tpl");
	} 
	// 分配权限时，点击某个role,需要获得该role所有的funcId
	function actionGetJsonRole() {
		$row = $this->_modelRole->find(array('id' => $_GET['roleId'])); 
		// 每个节点的path
		$mFunc = FLEA::getSingleton('Model_Acm_Func');
		if ($row['funcs']) foreach($row['funcs'] as &$v) {
			$path = $mFunc->getPath($v);
			$v['path'] = array_col_values($path, 'id');
		} 
		// dump($row);exit;
		echo json_encode($row['funcs']);
	}
	/**
	 * 从关联表中删除指定的关联纪录
	 */
	function actionRemoveAssign() {
		// 取得关联对象
		$link = $this->_modelRole->getLink('funcs'); 
		// dump($link); exit;
		// 生成sql语句
		$sql = "delete from {$link->joinTable} 
			where {$link->foreignKey}='{$_GET[$link->foreignKey]}'
			and {$link->assocForeignKey} = '{$_GET[$link->assocForeignKey]}'"; 
		// echo $sql;exit;
		if (!$link->dbo->execute($sql)) {
			js_alert('', '', $this->_getBack());
		}
		redirect($this->_url('assignfunc', array('id' => $_GET[roleId]))); 
		// 执行sql语句
	}

	/**
	 * 保存分配结果
	 */
	function actionSaveAssign() {
		// check the existence of the $_POST[funcId];
		$modelFunc = FLEA::getSingleton('Model_Acm_Func');
		$aFunc = $modelFunc->find($_POST[funcId]);

		if (!$aFunc) {
			js_alert('权限不存在!请核实后重新输入!',
				'',
				$this->_url('assignfunc', array('id' => $_POST[roleId]))
				);
		} 
		// if the parentId have been assigned, then cancel
		if ($modelFunc->isAssigned($_POST[funcId], $_POST[roleId])) {
			js_alert('父权限已经被分配过!您不需要再进行分配!',
				'',
				$this->_url('assignfunc', array('id' => $_POST[roleId]))
				);
		} 
		// begin assign 1,get the funcs that were assigned befor ,then merge with new func
		$aRole = $this->_modelRole->find($_POST[roleId]);

		$arr = count($aRole[funcs]) > 0 ? array_col_values($aRole[funcs], 'id') : array();
		$arr = array_unique(array_merge($arr, array($_POST[funcId]))); 
		// begin save
		$link = &$this->_modelRole->getLink('funcs');
		$link->saveAssocData($arr, $_POST[roleId]);

		/**
		 * $str = "insert into  {$link->joinTable} (
		 * {$link->foreignKey},{$link->assocForeignKey}
		 * ) values(
		 * '$_POST[roleId]','$_POST[funcId]'
		 * )";
		 * $this->_modelRole->execute($str);
		 */
		redirect($this->_url('assignfunc', array('id' => $_POST[roleId])));
	}
}

?>