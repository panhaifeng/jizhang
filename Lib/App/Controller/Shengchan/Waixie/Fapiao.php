<?php
FLEA::loadClass('Controller_Caiwu_Yf_Fapiao');
////////////////////////////////////////控制器名称
class Controller_Shengchan_Waixie_Fapiao extends Controller_Caiwu_Yf_Fapiao {
	var $_modelExample;
	var $_tplEdit='Caiwu/Yf/fapiaoEdit.tpl';
	function __construct() {
		parent::__construct();
		$this->fldMain = array(
        	'creater' => array('type' => 'text', 'value' => $_SESSION['REALNAME'],'title'=>'制单人','readonly'=>true),
        	'fapiaoCode' => array('type' => 'text', 'value' => '','title'=>'发票号'),
			'fapiaoDate' => array('title' => '发票日期', "type" => "calendar", 'value' => date('Y-m-d')),
			'supplierId' => array('title' => '加工户', 'type' => 'select', 'value' => '','model'=>'Model_Jichu_Jiagonghu'),
			'money' => array('title' => '发票金额', 'type' => 'text', 'value' => '','addonEnd'=>'元'),
			'memo' => array('title' => '备注', 'type' => 'textarea', 'value' => ''),
			'id' => array('type' => 'hidden', 'value' => ''),
        	'kind' => array('type' => 'hidden', 'value' =>'1'),
		);
		
	}
	function actionRight() {
		$this->authCheck('4-1-4');
		FLEA::loadclass('TMIS_Pager');
		$arr=TMIS_Pager::getParamArray(array(
				'dateFrom'=>date('Y-m-d',mktime(0,0,0,date('m'),date('d')-30,date('Y'))),
				'dateTo'=>date('Y-m-d'),
				'jiagonghuId'=>'',
				'key'=>'',
				'no_edit'=>'',
		));
		$sql="select x.*,a.compName from caiwu_yf_fapiao x
			left join jichu_jiagonghu a on a.id=x.supplierId
			where 1 and x.kind=1";
		if($arr['jiagonghuId']!='')$sql.=" and x.supplierId='{$arr['jiagonghuId']}'";
		if($arr['dateFrom']!=''){
			$sql.=" and x.fapiaoDate >= '{$arr['dateFrom']}'";
		}
		if($arr['dateTo']!=''){
			$sql.=" and x.fapiaoDate <='{$arr['dateTo']}'";
		}
	
		if($arr['key']!=''){
			$sql.=" and x.fapiaoCode like '%{$arr['key']}%'";
		}
		$sql.=" order by x.id desc";
		$page=& new TMIS_Pager($sql);
		$rowset=$page->findAll();
		//dump($rowset);exit;
		if(count($rowset)>0)foreach($rowset as & $v) {
			$v['_edit'].=$this->getEditHtml($v['id']).'&nbsp;&nbsp;'.$this->getRemoveHtml($v['id']);
		}
	
		$rowset[] = $this->getHeji($rowset, array('money'), $_GET['no_edit']==1?'fapiaoCode':'_edit');
	
		$arr_field_info=array(
				'_edit'=>'操作',
				'fapiaoCode'=>array('text'=>'发票编码','width'=>100),
				'fapiaoDate'=>'收票日期',
				'compName'=>'加工供应商',
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