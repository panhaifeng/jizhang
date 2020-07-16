<?php
/**
*޸by jeff zeng 2007-4-5
*{webcontrol type='SupplierSelect' selected=''}
*以联动select方式显示供应商控件
*因为建立的客户不多，所以改为普通的select控件
*/
function _ctlClientSelect($name,$params)	{	
	$name = $params[id];
	if ($params[disabled]) $dis = ' disabled';
	$html = "<select name='$name' id='$name' check='^0$' warning='请选择客户' onclick=\"popClient(this)\"".$dis.">";
	if (is_array($params[selected])) {
		$html .= "<option value='$selected[id]'>$selected[compName]</option>";
	} else {
		$mClient = & FLEA::getSingleton('Model_Jichu_Client');
		$arr = $mClient->find(array(id=>$params[selected]));
		$html .= "<option value='$arr[id]'>$arr[compName]</option>";
	}
	$html .= "</select>";
	return $html;	
	
	/*
	$_model = FLEA::getSingleton('Model_Jichu_Client');
	$selected=$params[selected];
	if (is_array($selected)) $selectedId = $selected[id];
	else $selectedId=$selected;
	if ($params[disabled]) $dis = ' disabled';
	if ($params[id]!="") $id =$params[id];
	else $id='clientId';
	$html = "<select name='$id' id='$id'".$dis." check='^0$' warning='请选择客户'>";
	$html .= "<option value=''>选择</option>";
	$arr = $_model->findAll();
	if(count($arr)>0) foreach($arr as $v) {
		$html .= "<option value='$v[id]'";
		if($selectedId==$v[id]) {
			$html .= " selected";
		}
		$html .= ">$v[compName]</option>";
	}	
	$html .= "</select>";
	return $html;*/
}
?>