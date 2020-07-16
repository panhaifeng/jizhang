<?php
/*
$name = select控件名称
$params = 一种情况是指定(selected=$item.productId)
		  另一种是选择产品之后返回的产品信息数组
		  itemname = 项目名称(比如客户, 供应商, 产品...)
		  textName = <option>之前的值
		  controller = 控制器名称
		  selected = 选中项目的id
		  disabled = 锁定
*/
function _ctlSelect($name,$params){	
	$itemName 	= $params[itemname];
	$textName 	= $params[textName];
	$controller = $params[controller];
	$selected 	= $params[selected]; 
	
	if ($params[disabled]) $dis = ' disabled';
	$html = "<select name='$name' id='$name' warning='请选择".$itemName."' onclick=\"popDialog(this,'$controller')\"".$dis.">";
	if (is_array($params[selected])) {
		$html .= "<option value='$selected[value]'>$selected[text]</option>";
	} else {
		$model = & FLEA::getSingleton('Model_'.$controller);
		$arr = $model->find(array(id=>$selected));
		$html .= "<option value='$arr[id]'>$arr[$textName]</option>";
	}
	$html .= "</select>";
	return $html;	
}
?>