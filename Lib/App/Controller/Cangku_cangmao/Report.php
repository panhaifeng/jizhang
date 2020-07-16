<?php
FLEA::loadClass('TMIS_Controller');
class Controller_Cangku_Report extends Tmis_Controller {
	var $_modelExample;
	var $funcId=106;
	function Controller_Cangku_Report() {		
		//$this->_modelExample = & FLEA::getSingleton('Model_Cangku_Report_Month');		
		$this->_modelProduct =  & FLEA::getSingleton('Model_Jichu_Product');
		//$this->_modelClient =  & FLEA::getSingleton('Model_Jichu_Client');

	}

	#月报表
	function actionMonth() {
		$this->authCheck($this->funcId);
		FLEA::loadClass('TMIS_Pager');
		$arrGet = TMIS_Pager::getParamArray(array(
			dateFrom =>date("Y-m-d",mktime(0,0,0,date("m"),1,date("Y"))),
			dateTo => date("Y-m-d"),
			//clientId=>''
		));

		$str = "select 
				x.id,
				x.wareName,
				x.guige,
				x.unit,
				x.cntMin,
				x.cntMax,
				x.leftId,
				x.rightId,
				i.cntI,
				i.moneyI,
				y.cntR,
				y.moneyR,
				z.cntC,
				z.moneyC from jichu_product x
				left join (
					select 
					initDate,
					productId,
					sum(cntInit) as cntI,
					sum(moneyInit) as moneyI
					from cangku_init 
					where initDate <= '$arrGet[dateFrom]' 
					group by productId
				) i on x.id = i.productId 
				left join (
					select 
					ruKuDate,
					productId,
					sum(cnt) as cntR,
					sum(money) as moneyR
					from view_cangku_ruku 
					where ruKuDate >= '$arrGet[dateFrom]' and ruKuDate <= '$arrGet[dateTo]' 
					group by productId
				) y on x.id = y.productId
				left join (
					select 
					chukuDate,
					productId,
					sum(cnt) as cntC,
					sum(money) as moneyC
					from view_cangku_chuku 
					where chukuDate >= '$arrGet[dateFrom]' and chukuDate <= '$arrGet[dateTo]'  
					group by productId
				) z on x.id = z.productId 
				where (x.rightId - leftId)=1";


		//echo($str); exit;
		/*
		if ($arrGet[productId] != '') $str .= " and id = ".$arrGet[productId];
		if ($arrGet[key] != '') {

			//通过品名找到key_id(必须是唯一),然后配置 id>key_id.leftId and id <key.id.rightId

		}*/

		$pager =& new TMIS_Pager($str);
		$rowset =$pager->findAllBySql($str);


		foreach($rowset as &$value) {
			$value[kucun] = $value[cntI] + $value[cntR] - $value[cntC];
			if (($value[kucun] < $value[cntMin]) || ($value[kucun] > $value[cntMax])) {
				$value[kucun] = "<strong><font color=red>".$value[kucun]."</font></strong>";
			}
		}


		$arrFieldInfo = array(
			"id"=>"货品序号",
			"productName"=>"品名",
			"guige"=>"规格",
			"unit"=>"单位",
			'cntI' => '期初数量',
			'moneyI' => '期初金额',
			'cntR' => '本期入库数量',
			'moneyR' => '本期入库金额',
			'cntC' => '本期出库数量',
			'moneyC' => '本期出库金额',
			"kucun"=>"当前库存",
		);

        $smarty = & $this->_getView();
		$smarty->assign("arr_field_info",$arrFieldInfo);
		$smarty->assign("title","库存月报表");
		$smarty->assign("arr_field_value", $rowset);
		$smarty->assign("add_display", 'none');
		$smarty->assign('arr_condition',$arrGet);
		$smarty->assign('page_info',$pager->getNavBar($this->_url('right',$arrGet)));
		$smarty->display("TableList.tpl");	
	}

	#日报表
	function actionDay() {
		$this->authCheck($this->funcId);
		FLEA::loadClass('TMIS_Pager');
		$arrGet = TMIS_Pager::getParamArray(array(
			"date" =>date("Y-m-d"),
			//clientId=>''
		));

		$str = "select 
				x.id,
				x.proCode,
				x.proName,
				x.guige,
				x.unit,
				x.cntMin,
				x.cntMax,
				i.cntI,
				i.moneyI,
				y.cntR,
				y.moneyR,
				z.cntC,
				z.moneyC from jichu_product x
				left join (
					select 
					initDate,
					productId,
					sum(cntInit) as cntI,
					sum(moneyInit) as moneyI
					from cangku_init 
					where initDate <= '$arrGet[date]' 
					group by productId
				) i on x.id = i.productId 
				left join (
					select 
					rukuDate,
					productId,
					sum(cnt) as cntR,
					sum(money) as moneyR
					from view_cangku_ruku 
					where rukuDate = '$arrGet[date]'  
					group by productId
				) y on x.id = y.productId
				left join (
					select 
					chukuDate,
					productId,
					sum(cnt) as cntC,
					sum(money) as moneyC
					from view_cangku_chuku 
					where chukuDate = '$arrGet[date]'   
					group by productId
				) z on x.id = z.productId 
				where (x.rightId - leftId)=1";


		//echo($str); exit;
		/*
		if ($arrGet[productId] != '') $str .= " and id = ".$arrGet[productId];
		if ($arrGet[key] != '') {

			//通过品名找到key_id(必须是唯一),然后配置 id>key_id.leftId and id <key.id.rightId

		}*/

		$pager =& new TMIS_Pager($str);
		$rowset =$pager->findAllBySql($str);


		foreach($rowset as &$value) {
			$value[kucun] = $value[cntI] + $value[cntR] - $value[cntC];
			if (($value[kucun] < $value[cntMin]) || ($value[kucun] > $value[cntMax])) {
				$value[kucun] = "<strong><font color=red>".$value[kucun]."</font></strong>";
			}
		}


		$arrFieldInfo = array(
			"id"=>"货品序号",
			"productName"=>"名称",
			"guige"=>"规格",
			"unit"=>"单位",
			'cntI' => '期初数量',
			'moneyI' => '期初金额',
			'cntR' => '本期入库数量',
			'moneyR' => '本期入库金额',
			'cntC' => '本期出库数量',
			'moneyC' => '本期出库金额',
			"kucun"=>"当前库存",
		);

        $smarty = & $this->_getView();
		$smarty->assign("arr_field_info",$arrFieldInfo);
		$smarty->assign("title","库存日报表");
		$smarty->assign("arr_field_value", $rowset);
		$smarty->assign("add_display", 'none');
		$smarty->assign('arr_condition',$arrGet);
		$smarty->assign('page_info',$pager->getNavBar($this->_url('right',$arrGet)));
		$smarty->display("TableList.tpl");	
	}
	
}
?>