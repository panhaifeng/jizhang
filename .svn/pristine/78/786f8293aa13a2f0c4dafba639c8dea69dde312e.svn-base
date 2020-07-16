<?php
FLEA::loadClass('Controller_Caiwu_Yf_Fukuan');
class Controller_Shengchan_Waixie_Fukuan extends Controller_Caiwu_Yf_Fukuan {
	var $_modelExample;
	var $_tplEdit='Caiwu/Yf/FukuanEdit.tpl';
	function __construct() {
		parent::__construct();
		 //搭建过账界面
        $this->fldMain = array(
        	'creater' => array('type' => 'text', 'value' => $_SESSION['REALNAME'],'title'=>'制单人','readonly'=>true),
        	'fukuanCode' => array('type' => 'text', 'value' => '','title'=>'付款单号'),
			'fukuanDate' => array('title' => '付款日期', "type" => "calendar", 'value' => date('Y-m-d')),
			'supplierId' => array('title' => '加工户', 'type' => 'selectgys', 'value' => '','model'=>'Model_Jichu_Jiagonghu'),
			'fkType' => array('title' => '付款方式', 'type' => 'combobox', 'value' => '','options'=>$this->_modelExample->typeOptions()),
			'money' => array('title' => '本次付款', 'type' => 'text', 'value' => '','addonEnd'=>'元'),
			'memo' => array('title' => '备注', 'type' => 'textarea', 'value' => ''),
			'id' => array('type' => 'hidden', 'value' => ''),
        	'kind' => array('type' => 'hidden', 'value' => '1'),
		);
	}
	function actionRight() {
		// $this->authCheck('4-1-6');
		FLEA::loadclass('TMIS_Pager');
		$arr=TMIS_Pager::getParamArray(array(
			'dateFrom'=>date('Y-m-d',mktime(0,0,0,date('m'),date('d')-30,date('Y'))),
			'dateTo'=>date('Y-m-d'),
			'jiagonghuId'=>'',
			'key'=>'',
			'no_edit'=>'',
		));
		$sql="select x.*,a.compName from caiwu_yf_fukuan x
			left join jichu_jiagonghu a on a.id=x.supplierId
			where 1 and x.kind=1";
		if($arr['jiagonghuId']!='')$sql.=" and x.supplierId='{$arr['jiagonghuId']}'";
		if($arr['dateFrom']!=''){
			$sql.=" and x.fukuanDate >= '{$arr['dateFrom']}'";
		}
		if($arr['dateTo']!=''){
			$sql.=" and x.fukuanDate <='{$arr['dateTo']}'";
		}
		if($arr['key']!=''){
			$sql.=" and x.fukuanCode like '%{$arr['key']}%'";
		}
		$sql.=" order by x.id desc";
		$page=& new TMIS_Pager($sql);
		$rowset=$page->findAll();
		//dump($rowset);exit;
		if(count($rowset)>0)foreach($rowset as & $v) {
				$v['_edit'].=$this->getEditHtml($v['id']).'&nbsp;&nbsp;'.$this->getRemoveHtml($v['id']);
		}

		$rowset[] = $this->getHeji($rowset, array('money'), $_GET['no_edit']==1?'fukuanCode':'_edit');
		
		$arr_field_info=array(
			'_edit'=>'操作',
			'fukuanCode'=>array('text'=>'付款单号','width'=>100),
			'fukuanDate'=>'付款日期',
			'compName'=>'加工供应商',
			'fkType'=>'付款方式',
			'money'=>'金额',
			'memo'=>'备注',
		);
		$smarty=& $this->_getView();
		$smarty->assign('arr_condition',$arr);
		$smarty->assign('add_display','none');
		$smarty->assign('arr_field_info',$arr_field_info);
		$smarty->assign('arr_field_value',$rowset);
		$smarty->assign('page_info',$page->getNavBar($this->_url($_GET['action'],$arr)).$note);
		$smarty->display('TblList.tpl');
	}
}
?>