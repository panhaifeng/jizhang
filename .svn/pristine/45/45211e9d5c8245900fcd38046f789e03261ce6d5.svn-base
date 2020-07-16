<?php
FLEA::loadClass('TMIS_Controller');
class Controller_Jichu_Supplier extends Tmis_Controller {
	var $_modelExample;
	var $title = "供应商资料";
	var $funcId = 26;
	var $_tplEdit = 'Jichu/SupplierEdit.tpl';
	function Controller_Jichu_Supplier() {
		// if(!//$this->authCheck()) die("禁止访问!");
		$this->_modelExample = &FLEA::getSingleton('Model_Jichu_Supplier');
		$this->_modelEmploy = &FLEA::getSingleton('Model_Jichu_Employ');
		$this->_modelTaitou = &FLEA::getSingleton('Model_Jichu_SupplierTaitou');
	}

	function actionRight() {
		// dump($_GET['kind']);exit;
		$title = '坯纱供应商档案编辑';
		// /////////////////////////////模板文件
		$tpl = 'TableList.tpl';
		// /////////////////////////////表头
		$arr_field_info = array('_edit' => '操作',
			"compCode" => "编码",
			"compName" => "名称",
			// "zhujiCode" =>"助记码",
			"people" => "联系人",
			"address" => "地址",
			"tel" => "电话"
			);
		// /////////////////////////////模块定义
		$this->authCheck('6-2');
		FLEA::loadClass('TMIS_Pager');
		$arr = TMIS_Pager::getParamArray(array('key' => ''
				));
		$condition = array();
		if ($arr['key'] != '') {
			$condition[] = array('compCode', "%{$arr['key']}%", 'like', 'or');
			$condition[] = array('compName', "%{$arr['key']}%", 'like');
		}
		// dump($_POST['traderId']); dump($_GET['traderId']);exit;
		// if($arr['traderId']!=0) {
		// $condition[] = array('traderId',"{$arr['traderId']}",'=');
		// }
		$pager = &new TMIS_Pager($this->_modelExample, $condition,'(compCode+0) asc');
		$rowset = $pager->findAll();
		if (count($rowset) > 0) foreach($rowset as &$v) {
			// /////////////////////////////
			// $this->makeEditable($v,'memoCode');
			// 添加抬头设置的操作
			$v['_edit'] = " <a href='" . $this->_url('setTaitou', array('supplierId' => $v['id'],

					'no_edit' => 1,
					'TB_iframe' => 1
					)) . "' class='thickbox'  title='开票抬头设置'>开票抬头设置</a>";
			$v['_edit'] .= "&nbsp;&nbsp;" . $this->getEditHtml($v['id']) . '&nbsp;&nbsp;' . $this->getRemoveHtml($v['id']);
		}
		$smarty = &$this->_getView();
		$smarty->assign('title', $title);
		$smarty->assign('arr_field_info', $arr_field_info);
		$smarty->assign('arr_field_value', $rowset);
		$smarty->assign('arr_condition', $arr);

		$smarty->assign('arr_js_css', $this->makeArrayJsCss(array('calendar')));
		$smarty->assign('page_info', $pager->getNavBar($this->_url($_GET['action'], $arr)));
		$smarty->display($tpl);
	}
	function actionRight1() {
		$smarty = &$this->_getView();
		// 首先提供字段映射数组，使客户端表现出表格
		$arrField = array("compCode" => "编码",
			"compName" => "名称",
			"zhujiCode" => "助记码",
			// "people" =>"联系人",
			"address" => "地址",
			"tel" => "电话",
			"mobile" => "手机",
			"fax" => "传真",
			"taxId" => "税号",
			"accountId" => "账号",
			"comeFrom" => "来源",
			"memo" => "备注"
			);
		// 提供搜索项目,
		$arrCon = array('key' => '',
			// 'clientId'=>'',
			// 'lzId'=>'',
			// 'huaxing'=>''
			);
		$smarty->assign("arrField", $arrField);
		$smarty->assign("arrCon", $arrCon);
		// 然后提供数据，渲染表格,要考虑分页，排序，搜索等变化
		$smarty->display("TableList4.tpl");
		exit;
	}
	// 覆盖父类方法，因为这里可能要根据传过来的key进行过滤.
	function actionGetExtData() {
		$condition = array();
		if ($_POST['key'] != '') {
			$condition[] = array('compCode', "%{$_POST['key']}%", 'like', 'or');
			$condition[] = array('compName', "%{$_POST['key']}%", 'like');
		}
		if ($_GET['for'] == 'export') {
			$arrField = array();
			foreach($_GET['head'] as $key => $v) {
				$arrField[$_GET['f'][$key]] = $v;
			}
			// dump($arrField);exit;
			$rowset = $this->_modelExample->findAll($condition);
			foreach($rowset as &$v) {
				$v['traderName'] = $v['Trader']['employName'];
			}
			$smarty = &$this->_getView();
			$smarty->assign('arr_field_info', $arrField);
			$smarty->assign('arr_field_value', $rowset);
			$smarty->assign('title', '供应商档案');
			header("Content-type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=供应商档案.xml");
			$smarty->display('Export2Excel.tpl');
			exit;
		}
		FLEA::loadClass('TMis_Pager');
		$pager = &new TMIS_Pager($this->_modelExample, $condition);
		$rowset = $pager->findAll();
		foreach($rowset as &$v) {
			$v['traderName'] = $v['Trader']['employName'];
		}
		echo json_encode(array('total' => $pager->count, 'rows' => $rowset));
	}

	function actionBuildForm() {
		$ret = array('success' => true,
			'action' => $this->_url('extSave'), // 提交到的action
			'columns' => 2, // 指定fildSet中一行显示几列字段
			'data' => array('必填字段' => array(// 此处表明需要构建一个fieldsets
					'compCode' => array('text' => '供应商编码'// 标签 , 'vtype' => 'alphanum'// 验证类型可选择'alpha',alphanum,'email','url', , 'allowBlank' => false// 是否允许为空
						),
					'compName' => array('text' => '供应商名称',
						'allowBlank' => false// 是否允许为空
						// 'vtype'=>'alphanum'//验证类型可选择'alpha',alphanum,'email','url',
						),
					'traderId' => array('type' => 'combox', // 可选择combox,searchField,text,hidden,checkbox,radio,textarea
						'tmisType' => 'TmisCombox.trader', // 业务逻辑控件类型
						'text' => '业务员',
						'allowBlank' => false
						),
					'id' => array('type' => 'hidden'
						// 'vtype'=>'alphanum'//验证类型可选择'alpha',alphanum,'email','url',
						)
					),
				'选填字段' => array('zhujiCode' => array('text' => '助记码',
						'vtype' => 'alphanum'
						),
					'tel' => array('text' => '电话',
						'type' => 'numberfield'
						),
					'mobile' => array('text' => '手机',
						'type' => 'numberfield'
						),
					'fax' => array('text' => '传真',
						'type' => 'numberfield'
						),
					'accountId' => array('text' => '账号'
						),
					'taxId' => array('text' => '税号'
						),
					'comeFrom' => array('text' => '来源'
						),
					'address' => array('text' => '地址'
						),
					'memo' => array('text' => '备注',
						'type' => 'textarea'
						)
					)
				)
			);
		echo json_encode($ret);
		exit;
	}
	function actionGetAssocPeo() {
		$sql = "select * from  jichu_client2peo where clientId='{$_POST['clientId']}'";
		$rowset = $this->_modelExample->findBySql($sql);
		echo json_encode($rowset);
		exit;
	}

	function actionSave() {
		if (empty($_POST['id'])) {
			$sql = "SELECT count(*) as cnt FROM `jichu_supplier` where compCode='" . $_POST['compCode'] . "' or compName='" . $_POST['compName'] . "'";
			$rr = $this->_modelExample->findBySql($sql);
			// dump($rr);exit;
			if ($rr[0]['cnt'] > 0) {
				js_alert('坯纱供应商名称或坯纱供应商代码重复!', null, $this->_url('add'));
			}
		}else {
			// 修改时判断是否重复
			$str1 = "SELECT count(*) as cnt FROM `jichu_supplier` where id!=" . $_POST['id'] . " and (compCode='" . $_POST['compCode'] . "' or compName='" . $_POST['compName'] . "')";
			$ret = $this->_modelExample->findBySql($str1);
			if ($ret[0]['cnt'] > 0) {
				js_alert('坯纱供应商名称或坯纱供应商代码重复!', null, $this->_url('Edit', array('id' => $_POST['id'])));
			}
		}
		// parent::actionSave();
		$id = $this->_modelExample->save($_POST);
		if ($_POST['id'] == '')
			js_alert(null, "window.parent.showMsg('保存成功!')", $this->_url('add'));
		else
			js_alert(null, "window.parent.showMsg('保存成功!')", $this->_url('right'));
	}

	function actionRemove() {
		if ($_GET['id'] != "") {
			$sql = "SELECT count(*) as cnt FROM `cangku_common_ruku` where supplierId=" . $_GET['id'];
			// dump($sql);exit;
			$re = $this->_modelExample->findBySql($sql);
			// dump($re);exit;
			if ($re[0]['cnt'] > 0) {
				js_alert('此供应商已登记入库，不能够删除！', null, $this->_url('right'));
			}
			$str="select count(*) as cnt from caiwu_yf_fapiao where supplierId='{$_GET['id']}'";
			$temp=$this->_modelExample->findBySql($str);
			//dump($temp);die;
			if($temp[0]['cnt']>0){
				js_alert('供应商已登记收票，禁止删除',null,$this->_url('right'));exit;
			}
			$str1="select count(*) as cnt from caiwu_yf_fukuan where supplierId='{$_GET['id']}'";
			$temp=$this->_modelExample->findBySql($str1);
			if($temp[0]['cnt']>0){
				js_alert('供应商已登记付款，禁止删除',null,$this->_url('right'));exit;
			}
			$str2="select count(*) as cnt from caiwu_yf_guozhang where supplierId='{$_GET['id']}'";
			$temp=$this->_modelExample->findBySql($str2);
			if($temp[0]['cnt']>0){
				js_alert('供应商已登记过账，禁止删除',null,$this->_url('right'));exit;
			}
		}
		parent::actionRemove();
	}
	// 根据选择的供应商得到采购过的坯纱及规格
	function actionGetPishaByAjax() {
		$str = "SELECT x.guigeDesc,x.guigeId FROM `pisha_cgrk` x where x.supplierId='{$_GET['supplierId']}' group by guigeDesc";
		$row = $this->_modelExample->findBySql($str);
		echo json_encode($row);
		exit;
	}
	// by_ld
	// 坯纱供应商开票抬头设置
	function actionSetTaitou() {
		$rows = $this->_modelTaitou->findAll(array('supplierId' => $_GET['supplierId']));
		// dump($rows);exit;
		$smarty = &$this->_getView();
		$smarty->assign('title', '开票抬头设置');
		$smarty->assign("aRow", $rows);
		$smarty->display('Jichu/SupplierTaitou.tpl');
	}
	// 保存抬头设置
	function actionSaveTaitou() {
		// dump($_POST);exit;
		$rows = array('taitou' => $_POST['taitou'],
			'supplierId' => $_POST['supplierId'],
			'memo' => $_POST['memo']
			);
		if ($rows) $this->_modelTaitou->save($rows);
		// js_alert(null,'window.parent.parent.showMsg("设置成功");window.parent.location.href=window.parent.location.href');
		js_alert('保存成功！', '', $this->_url('SetTaitou', array('supplierId' => $_POST['supplierId'])));
	}
	// 删除抬头设置
	function actionDelTaitouAjax() {
		if ($_GET['id'] != '') {
			if ($this->_modelTaitou->removeByPkv($_GET['id'])) {
				echo json_encode(array('success' => true));
				exit;
			}
		}
	}
	// 得到供应商自动完成控件的提示信息 2015-05-18 by jiang
	function actionGetJsonByKey() {
		// dump($_GET);exit;
		if($_GET['code'])$key = $_GET['code'];
		if(isset($_REQUEST['q']))$key =$_REQUEST['q']?$_REQUEST['q']:'';
		$sql = "select * from jichu_supplier where (
		compName like '%{$key}%' or zhujiCode like '%{$key}%' or compCode like '%{$key}%'
		) order by compName";
		$arr = $this->_modelExample->findBySql($sql);
		$data=array();
		foreach ($arr as & $v) {
		$data[]=array($v['compName'],array('name'=>$v['compName'],'id'=>$v['id']));
		}
		echo json_encode($data);exit;
	}
	function actionGetcompCodeByAjax(){
		$sql="SELECT * FROM `jichu_supplier` ORDER by LENGTH(compCode) DESC,compCode DESC LIMIT 0,1;";
		$res=$this->_modelExample->findBySql($sql);
        $max=$res[0]['compCode'];
        $num=$max+1;
        $compCode='0';
        $compCode.=$num;
		echo json_encode(array('success'=>true,'compCode'=>$compCode));
		exit;
	}

    function actionAdd(){
    	$smarty = & $this->_getView();
        $smarty->display('Jichu/SupplierAdd.tpl');
    }
    //在模式对话框中显示待选择的客户，返回某个客户的json对象。
    function actionPopup() {
    	$str = "select * from jichu_supplier where 1";
    	FLEA::loadClass('TMIS_Pager');
    	$arr = TMIS_Pager::getParamArray(array(
    			//'traderId' => '',
    			'key' => '',
    			'showModel'=>''
    	));
    	//if ($arr[traderId]!='') $str .= " and traderId='$arr[traderId]'";
    	if ($arr[key]!='') $str .= " and compCode like '%$arr[key]%'
    	or compName like '%$arr[key]%' or zhujiCode like '%$arr[key]%'";
    	$str .=" order by compCode desc";
    	$pager =& new TMIS_Pager($str);
    	$rowset =$pager->findAllBySql($str);
    	if(count($rowset)>0) foreach($rowset as & $v){
    	}
    	$arr_field_info = array(
    		"compCode" => "编码",
			"compName" => "名称",
			// "zhujiCode" =>"助记码",
			"people" => "联系人",
			"address" => "地址",
			"tel" => "电话"
    	);
    	$smarty = & $this->_getView();
    	$smarty->assign('title', '选择供应商');
    	$pk = $this->_modelExample->primaryKey;
    	$smarty->assign('pk', $pk);
    	$smarty->assign('add_display','none');
    	$smarty->assign('arr_field_info',$arr_field_info);
    	$smarty->assign('arr_field_value',$rowset);
    	$smarty->assign('s',$arr);
    	$smarty->assign('page_info',$pager->getNavBar($this->_url($_GET['action'],$arr)));
    	$smarty->assign('arr_condition',$arr);
    	$smarty->assign('clean',true);
    	$smarty-> display('Popup/CommonNew.tpl');
    }


    //在模式对话框中显示待选择的客户，返回某个客户的json对象。
    function actionPopupWithHead() {
        FLEA::loadClass('TMIS_Pager');
        $arr = TMIS_Pager::getParamArray(array(
            //'traderId' => '',
            'key' => '',
            'showModel'=>''
        ));
        $str = "select m.*, t.taitou as head 
                from jichu_supplier m
                left join jichu_supplier_taitou as t on t.supplierId = m.id
                where 1";
        //if ($arr[traderId]!='') $str .= " and traderId='$arr[traderId]'";
        if ($arr[key]!='') $str .= " and compCode like '%$arr[key]%'
    	or compName like '%$arr[key]%' or zhujiCode like '%$arr[key]%'";
        $str .=" order by compCode desc";
        $pager =& new TMIS_Pager($str);
        $rowset =$pager->findAllBySql($str);
        if(count($rowset)>0) foreach($rowset as & $v){
        }
        $arr_field_info = array(
            "compCode" => "编码",
            "compName" => "名称",
            "head" => "抬头",
            // "zhujiCode" =>"助记码",
            "people" => "联系人",
            "address" => "地址",
            "tel" => "电话"
        );
        $smarty = & $this->_getView();
        $smarty->assign('title', '选择供应商');
        $pk = $this->_modelExample->primaryKey;
        $smarty->assign('pk', $pk);
        $smarty->assign('add_display','none');
        $smarty->assign('arr_field_info',$arr_field_info);
        $smarty->assign('arr_field_value',$rowset);
        $smarty->assign('s',$arr);
        $smarty->assign('page_info',$pager->getNavBar($this->_url($_GET['action'],$arr)));
        $smarty->assign('arr_condition',$arr);
        $smarty->assign('clean',true);
        $smarty-> display('Popup/CommonNew.tpl');
    }

}

?>