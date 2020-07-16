<?php
FLEA::loadClass('TMIS_Controller');
class Controller_Jichu_Zhiji extends Tmis_Controller {
	var $_modelExample;

	function __construct() {
		$this->_modelExample = & FLEA::getSingleton('Model_Jichu_Zhiji');

		$this->fldMain = array(
        	'id'=>array('title'=>'','type'=>'hidden','value'=>''),
        	'zhijiCode'=>array('title'=>'机台编号','type'=>'text','value'=>''),
        	'zhijiName'=>array('title'=>'机台名称','type'=>'text','value'=>''),
        	'orderLine'=>array('title'=>'排列顺序','type'=>'text','value'=>''),        	
        	'memo'=>array('title'=>'备注','type'=>'textarea','value'=>''),
        );

        $this->rules = array(
			'zhijiCode'=>'required repeat',
			'zhijiName'=>'required',
			'orderLine'=>'number'
		);
	}

	function actionRight() {
		$title = '机台档案';
		///////////////////模板文件
		$tpl = 'TableList.tpl';
		///////////////////////表头
		$arr_field_info = array(
			'_edit'=>'操作',
			"zhijiCode" =>"机台编码",
			"zhijiName" =>"机台名称",
			'orderLine'=>'排列顺序',
			'memo'=>'备注'
		);
		
		///////////////////////////////模块定义
		// $this->authCheck('6-9');
		FLEA::loadClass('TMIS_Pager');
		$arr = TMIS_Pager::getParamArray(array(
			'key'=>''
		));
		
		if($arr['key']!='') {
			$condition[] = array('zhijiCode',"%{$arr['key']}%",'like','or');
			$condition[] = array('zhijiName',"%{$arr['key']}%",'like');
		}
		
		$pager =& new TMIS_Pager($this->_modelExample,$condition,'orderLine,zhijiCode');
		$rowset =$pager->findAll();
		// dump($rowset);exit;
		foreach($rowset as & $v) {
			$this->makeEditable($v,'orderLine');
			$v['_edit'] = $this->getEditHtml($v['id']) . '&nbsp;&nbsp;' . $this->getRemoveHtml($v['id']);
		}
		$smarty = & $this->_getView();
		$smarty->assign('title', $title);
		$smarty->assign('arr_field_info',$arr_field_info);
		$smarty->assign('arr_field_value',$rowset);
		$smarty->assign('arr_condition', $arr);
		$smarty->assign('page_info',$pager->getNavBar($this->_url($_GET['action'],$arr)));
		$smarty->display($tpl);
	}


	function actionRemove() {
		//如果已使用该加工户，禁止删除
    	if($_GET['id']>0){
	        $url=$this->_url($_GET['fromAction']==''?'right':$_GET['fromAction']);
	        //判断是否坯纱入库中使用了该产品
	        // $str="select count(*) as cnt from shengchan_plan2product_gongxu where jitaiId='{$_GET['id']}'";
	        // $temp=$this->_modelExample->findBySql($str);
	        // if($temp[0]['cnt']>0){
	        //     js_alert('生产计划中已使用该机台，禁止删除','',$url);
	        // }
     	}
		parent::actionRemove();
	}

	function actionEdit(){
		$row = $this->_modelExample->find(array('id'=>$_GET['id']));
	    $this->fldMain = $this->getValueFromRow($this->fldMain,$row);
	    // dump($row);dump($this->fldMain);exit;
	    $smarty = & $this->_getView();
	    $smarty->assign('fldMain',$this->fldMain);
	    $smarty->assign('rules',$this->rules);
	    $smarty->assign('title','织机信息编辑');
	    $smarty->assign('aRow',$row);
	    $smarty->display('Main/A1.tpl');
	}

	function actionAdd($Arr){
		$smarty = & $this->_getView();
	    $smarty->assign('fldMain',$this->fldMain);
	    $smarty->assign('title','织机信息编辑');
	    $smarty->assign('rules',$this->rules);
	    $smarty->display('Main/A1.tpl');
	}

	function actionSave(){
		$row = array();
		foreach($this->fldMain as $k=>&$vv) {
			$name = $vv['name']?$vv['name']:$k;
			$row[$k] = $_POST[$name];
		}
		// dump($row);exit;
		if(!$this->_modelExample->save($row)) {
			js_alert('保存失败','window.history.go(-1)');
			exit;
		}
		//跳转
		js_alert(null,'window.parent.showMsg("保存成功!")',$this->_url('right'));
		exit;
	}
}
?>