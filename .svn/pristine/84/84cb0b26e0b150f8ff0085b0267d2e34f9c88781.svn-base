<?php
FLEA::loadClass('TMIS_Controller');
class Controller_Jichu_Employ extends Tmis_Controller {
	var $_modelExample;
	var $funcId = 27;
	// var $_tplEdit='Jichu/EmployEdit.tpl';
	function Controller_Jichu_Employ() {
		// if(!//$this->authCheck()) die("禁止访问!");
		$this->_modelExample = &FLEA::getSingleton('Model_Jichu_Employ');
	}
	function actionRight() {
		// dump($_GET['kind']);exit;
		$title = '职工档案';
		// /////////////////////////////模板文件
		$tpl = 'TableList.tpl';
		// /////////////////////////////表头
		$arrField = array("_edit" => '操作',
			// "depName" =>"部门",
			"employName" => "姓名",
			"employCode" => "员工代码",
			"codeAtEmploy" => "合同中简称",
			"Dep.depName" => "部门名称",
			'dateEnter' => '入职日期',
			"gongzhong" => "工种",
			"fenlei" => "工种分类",
			"type" => "类别",
			"sexName" => "性别",
			"mobile" => "手机",
			"address" => "地址",
			"shenfenNo" => "身份证号",
			"hetongCode" => "用工合同号",
			);
		// /////////////////////////////模块定义
		$this->authCheck('6-8'); //权限判断
		FLEA::loadClass('TMIS_Pager');
		$arr = TMIS_Pager::getParamArray(array('key' => '',
				'depId' => ''
				));
		$condition = array();
		if ($arr['key'] != '') {
			$condition[] = array('employCode', "%{$arr['key']}%", 'like', 'or');
			$condition[] = array('employName', "%{$arr['key']}%", 'like');
		}
		if ($arr['depId'] != '') $condition[] = array('depId', $arr['depId']);
		// dump($_POST['traderId']); dump($_GET['traderId']);exit;
		// if($arr['traderId']!=0) {
		// $condition[] = array('traderId',"{$arr['traderId']}",'=');
		// }
		$pager = &new TMIS_Pager($this->_modelExample, $condition, "paixu asc");
		$rowset = $pager->findAll();
		// $rowset  = array(
		// array('employName'=>'小刘','employCode'=>'liu')
		// );
		// dump($rowset);exit;
		if (count($rowset) > 0) foreach($rowset as &$v) {
			// /////////////////////////////
			// $this->makeEditable($v,'memoCode');
			if ($v['type'] == '离职') {
				$v['_bgColor'] = 'lightgreen';
			}
			$v['sexName'] = $v['sex'] == 0 ?'男':'女';
			$v['_edit'] = $this->getEditHtml($v['id']) . '&nbsp;&nbsp;' . $this->getRemoveHtml($v['id']);
			if ($v['dateEnter'] == '0000-00-00') $v['dateEnter'] = "&nbsp;";
		}
		// dump($rowset);exit;
		$smarty = &$this->_getView();
		$smarty->assign('title', $title);
		$smarty->assign('arr_field_info', $arrField);
		$smarty->assign('arr_field_value', $rowset);
		$smarty->assign('arr_condition', $arr);


		$smarty->assign('arr_js_css', $this->makeArrayJsCss(array('calendar')));
		$smarty->assign('page_info', $pager->getNavBar($this->_url($_GET['action'], $arr)) . "<font color='red'>绿色表示已经离职</font>");
		$smarty->display($tpl);
	}

	function _edit($Arr) {
		$tpl = 'Jichu/EmployEdit.tpl';

		$employCode = $this->getEmployCode();
		// echo $employCode;exit;
		if (!$Arr)
			$Arr['employCode'] = $employCode;
		// dump($Arr);exit;
		$smarty = &$this->_getView();
		$smarty->assign('aRow', $Arr);
		// $smarty->assign('aRow',$Arr);
		$smarty->display($tpl);
	}

	function actionSave() {
		// dump($_POST);exit;
		if (empty($_POST['id'])) {
			$sql = "SELECT count(*) as cnt FROM `jichu_employ` where employCode='" . $_POST['employCode'] . "' or employName='" . $_POST['employName'] . "'";
			$rr = $this->_modelExample->findBySql($sql);
			// dump($rr);exit;
			if ($rr[0]['cnt'] > 0) {
				js_alert('员工名称或代码重复!', null, $this->_url('add'));
			}
		}else {
			// 修改时判断是否重复
			$str1 = "SELECT count(*) as cnt FROM `jichu_employ` where id!=" . $_POST['id'] . " and (employCode='" . $_POST['employCode'] . "' or employName='" . $_POST['employName'] . "')";
			$ret = $this->_modelExample->findBySql($str1);
			if ($ret[0]['cnt'] > 0) {
				js_alert('员工名称或代码重复!', null, $this->_url('Edit', array('id' => $_POST['id'])));
			}
		}

		$_POST['fenlei'] = $_POST['fenlei'] == ''?"":$_POST['fenlei'];
		$_POST['isFire'] = 0;
		$_POST['paixu'] = 0;
		if ($_POST['type'] == "试用") $_POST['paixu'] = 1;
		if ($_POST['type'] == "临时") $_POST['paixu'] = 2;
		if ($_POST['type'] == "离职") {
			$_POST['paixu'] = 3;
			$_POST['employCode'] = $_POST['employCode'] . "_LZ";
			$_POST['employName'] = $_POST['employName'] . "_LZ";
		}
		if ($_POST['type'] == "离职") $_POST['isFire'] = 1;
		$arr = array('id' => $_POST['id'],
			'employCode' => $_POST['employCode'],
			'codeAtEmploy' => $_POST['codeAtEmploy'],
			'employName' => $_POST['employName'],
			'sex' => $_POST['sex'] + 0,
			'DepId' => $_POST['DepId'],
			'gongzhong' => $_POST['gongzhong'],
			'fenlei' => $_POST['fenlei'],
			'type' => $_POST['type'],
			'mobile' => $_POST['mobile'],
			'address' => $_POST['address'],
			'dateEnter' => $_POST['dateEnter'],
			'dateLeave' => $_POST['dateLeave'],
			'shenfenNo' => $_POST['shenfenNo'],
			'hetongCode' => $_POST['hetongCode'],
			'isFire' => $_POST['isFire'],
			'paixu' => $_POST['paixu']
			);
		// dump($_POST);dump($arr);exit;
		$this->_modelExample->save($arr);
		if ($_POST['id'] == '')
			js_alert(null, "window.parent.showMsg('保存成功!')", $this->_url('add'));
		else
			js_alert(null, "window.parent.showMsg('保存成功!')", $this->_url('right'));
	}

	function actionRemove() {
		if ($_GET['id'] != "") {
			$sql = "SELECT count(*) as cnt FROM `trade_order` where traderId =" . $_GET['id'];
			// dump($sql);exit;
			$re = $this->_modelExample->findBySql($sql);
			// dump($re);exit;
			if ($re[0]['cnt'] > 0) {
				js_alert('此员工有业务订单，禁止删除！', null, $this->_url('right'));
			}
		}
		parent::actionRemove();
	}

	function getEmployCode() {
		$begin = "001";
		$str = "SELECT * FROM `jichu_employ` order by (employCode) desc limit 0,1";
		// echo $str;exit;
		$re = mysql_fetch_assoc(mysql_query($str));
		if ($re['employCode'] != '') {
			$max = $re['employCode'];
			$next = $max + 1001;
			return substr($next, 1);
		}else {
			return $begin;
		}
	}
	//用于展会导入数据
	function actionGetTraders() {
		$str="select * from JiChu_Employ where depId=(select id from jichu_department where depName='业务部') and (dateLeave='0000-00-00' and type='正式')";

		$rows=$this->_modelExample->findBySql($str);

		echo json_encode($rows);
		exit;
	}
}

?>