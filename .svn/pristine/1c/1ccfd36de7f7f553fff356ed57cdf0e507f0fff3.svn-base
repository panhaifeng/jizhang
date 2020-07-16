<?php
load_class('TMIS_TableDataGateway');
class Model_Jichu_Color extends TMIS_TableDataGateway {
	var $tableName = 'jichu_color';
	var $primaryKey = 'id';
	var $primaryName = 'compName';

	//把传递过来的数据组织成下拉框的选项
	function options($rowset,$params){
		$ret = "<option value='' style='color:#ccc'>选择颜色</option>";
		foreach($rowset as $k => & $v) {
			if($v['compName']=='') $temp = $v['compCode'];
			else $temp = $v['compName'];
		
			//拼接下拉框
			$ret .= "<option value='{$v['id']}'";
			if($params['selected']==$v['id']) $ret .= " selected";
			$ret .= ">{$temp}</option>";
		}
		return $ret;
	}

}
?>