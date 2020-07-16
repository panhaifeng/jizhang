<?php
FLEA::loadClass('TMIS_Controller');
class Controller_Jichu_Color extends Tmis_Controller {
	var $_modelExample;
	var $title = "颜色档案";
	var $funcId = 26;
	function Controller_Jichu_Color() {
		$this->_modelExample = &FLEA::getSingleton('Model_Jichu_Color');

	 	$this->fldMain = array(
            'id'=>array('title'=>'','type'=>'hidden','value'=>''),
            'compCode'=>array('title'=>'颜色编码','type'=>'text','value'=>''),
            'compName'=>array('title'=>'颜色名称','type'=>'text','value'=>''),
            // 'codeAtOrder'=>array('title'=>'颜色简称','type'=>'text','value'=>''),
            'memo'=>array('title'=>'备注','type'=>'textarea','value'=>''),
        );

        $this->rules = array(
            'compCode'=>'required',
            'compName'=>'required',
        );

	}

	function actionRight() {
		// dump($_GET['kind']);exit;
		$title = '颜色档案编辑';
		// /////////////////////////////模板文件
		$tpl = 'TableList.tpl';
		// /////////////////////////////表头
		$arr_field_info = array('_edit' => '操作',
			"compCode" => "编码",
			"compName" => "名称",
			"memo"     => "备注"
			);
		// /////////////////////////////模块定义
		$this->authCheck('6-22');
		FLEA::loadClass('TMIS_Pager');
		$arr = TMIS_Pager::getParamArray(array('key' => ''
				));
		$condition = array();
		if ($arr['key'] != '') {
			$condition[] = array('compCode', "%{$arr['key']}%", 'like', 'or');
			$condition[] = array('compName', "%{$arr['key']}%", 'like');
		}
		$pager = &new TMIS_Pager($this->_modelExample, $condition,'(compCode+0) asc');
		$rowset = $pager->findAll();
		if (count($rowset) > 0) foreach($rowset as &$v) {
			$v['_edit'] .= $this->getEditHtml($v['id']) . '&nbsp;&nbsp;' . $this->getRemoveHtml($v['id']);
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
	

	function actionSave() {
		if (empty($_POST['id'])) {
			$sql = "SELECT count(*) as cnt FROM `jichu_color` where compCode='" . $_POST['compCode'] . "' or compName='" . $_POST['compName'] . "'";
			$rr = $this->_modelExample->findBySql($sql);
			// dump($rr);exit;
			if ($rr[0]['cnt'] > 0) {
				js_alert('颜色名称或颜色代码重复!', null, $this->_url('add'));
			}
		}else {
			// 修改时判断是否重复
			$str1 = "SELECT count(*) as cnt FROM `jichu_color` where id!=" . $_POST['id'] . " and (compCode='" . $_POST['compCode'] . "' or compName='" . $_POST['compName'] . "')";
			$ret = $this->_modelExample->findBySql($str1);
			if ($ret[0]['cnt'] > 0) {
				js_alert('颜色名称或颜色代码重复!', null, $this->_url('Edit', array('id' => $_POST['id'])));
			}
		}
		$id = $this->_modelExample->save($_POST);
		if ($_POST['id'] == '')
			js_alert(null, "window.parent.showMsg('保存成功!')", $this->_url('add'));
		else
			js_alert(null, "window.parent.showMsg('保存成功!')", $this->_url('right'));
	}

	function actionEdit(){
        $row = $this->_modelExample->find(array('id'=>$_GET['id']));
        $this->fldMain = $this->getValueFromRow($this->fldMain,$row);
      
        $smarty = & $this->_getView();
        $smarty->assign('fldMain',$this->fldMain);
        $smarty->assign('rules',$this->rules);
        $smarty->assign('title','颜色信息编辑');
        $smarty->assign('aRow',$row);
        $smarty->assign('flag', $_GET['flag']);
        $smarty->assign('form',array('up'=>true));
        $smarty->display('Main/A2.tpl');
    }

	function actionRemove() {
		parent::actionRemove();
	}

    function actionAdd(){
	  	$row['compCode'] = $this->_autoCode('','','jichu_color','compCode');
        $this->fldMain = $this->getValueFromRow($this->fldMain,$row);

    	$smarty = & $this->_getView();
        $smarty->assign('fldMain',$this->fldMain);
        $smarty->assign('title','颜色信息编辑');
        $smarty->assign('rules',$this->rules);
        $smarty->assign('form',array('up'=>true));
        $smarty->display('Main/A2.tpl');
    }

    //在模式对话框中显示待选择的颜色，返回某个颜色的json对象。
    function actionPopup() {
    	$str = "select * from jichu_color where 1";
    	FLEA::loadClass('TMIS_Pager');
    	$arr = TMIS_Pager::getParamArray(array(
			'key' => '',
			'showModel'=>''
    	));
    	if ($arr[key]!='') $str .= " and compCode like '%$arr[key]%'
    	or compName like '%$arr[key]%'";
    	$str .=" order by compCode desc";
    	$pager =& new TMIS_Pager($str);
    	$rowset =$pager->findAllBySql($str);
    	if(count($rowset)>0) foreach($rowset as & $v){
    	}
    	$arr_field_info = array(
    		"compCode" => "编码",
			"compName" => "颜色",
			"memo" 	   => "备注",
    	);
    	$smarty = & $this->_getView();
    	$smarty->assign('title', '选择颜色');
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

	function actionGetJsonByKey() {
		if($_GET['code'])$key = $_GET['code'];
		if(isset($_REQUEST['q']))$key =$_REQUEST['q']?$_REQUEST['q']:'';
		$sql = "select * from jichu_color where (
		compName like '%{$key}%'  or compCode like '%{$key}%'
		) order by compName";
		$arr = $this->_modelExample->findBySql($sql);
		$data=array();
		foreach ($arr as & $v) {
			$data[]=array($v['compName'],array('name'=>$v['compName'],'id'=>$v['id']));
		}
		echo json_encode($data);exit;
	}

}

?>