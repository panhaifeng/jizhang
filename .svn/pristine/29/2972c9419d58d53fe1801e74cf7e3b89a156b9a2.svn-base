<?php
load_class('TMIS_TableDataGateway');
class Model_Jichu_Client extends TMIS_TableDataGateway {
	var $tableName = 'jichu_client';
	var $primaryKey = 'id';
	var $primaryName = 'compName';
	var $sortByKey = ' letters';
	var $optgroup = true;

	var $belongsTo = array(
		array(
			'tableClass' => 'Model_Jichu_Employ',
			'foreignKey' => 'traderId',
			'mappingName' => 'Trader'
		)

	);
	var $hasMany = array(
		array(
			'tableClass' => 'Model_Jichu_ClientTaitou',
			'foreignKey' => 'clientId',
			'mappingName' => 'Client'
		)

	);

	//把传递过来的数据组织成下拉框的选项
	function options($rowset,$params){
		//判断显示助记码还是客户全称
		$kg = & FLEA::getAppInf('khqcxs');
		//判断是否按照汇总的方式显示
		$letterTrue=$this->optgroup==true?true:false;
		//按照助记码的显示方式不汇总
		if(!$kg)$letterTrue=false;
		$letter='';
		$ret = "<option value='' style='color:#ccc'>选择客户</option>";
		foreach($rowset as $k => & $v) {
			//根据开关变量khqcxs判断是显示客户全称还是显示客户助记码
			if($kg) $temp = $v['compName'];
			else {
				if($v['compCode']=='') $temp = $v['compName'];
				else $temp = $v['compCode'];
			}
			//$temp = 'aa';
			//$ret .= "<option value='{$v['id']}'>{$temp}</options>";
			//开始添加汇总信息
			if($letterTrue){
				if($letter!=substr($v['letters'],0,1)){
					$letter=substr($v['letters'],0,1);
					if($letter!='')$ret .="<optgroup label='{$letter}'>";
				}
			}
			//拼接下拉框
			$ret .= "<option value='{$v['id']}'";
			if($params['selected']==$v['id']) $ret .= " selected";
			$ret .= ">{$temp}</option>";

			//开始添加汇总信息
			if($letterTrue){
				if($letter!=substr($rowset[$k+1]['letters'],0,1)){
					if($letter!='')$ret .="</optgroup>";
				}
			}
		}

		return $ret;
	}
}
?>