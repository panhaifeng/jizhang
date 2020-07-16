<?php
/*
*2014-4-25 by jeff : 输入框后带一个下拉选择按钮
*需要配合js,相关js库为bootstrap目录中的jeffCombobox.js文件
*/
function _ctlBtCombobox($name,$params){	
	$itemName 	= $params['itemName'];
	$readonly = $params['readonly']?"readonly":'';
	$disabled = $params['disabled']?"disabled":'';
    $value = $params['value']?$params['value']:'';
    $opts 	= $params['options'];
    

    $html = '<div class="input-group input-group-sm">';
	$html .= "<input type='text' class='form-control' name='{$itemName}' id='{$itemName}' value='{$value}' {$disabled} {$readonly} />";
	//加上日期图标
	$html .= '<div class="input-group-btn">
	              <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" tabindex="-1">
	                <span class="caret"></span>
	                <span class="sr-only">Toggle Dropdown</span>
	              </button>
	              <ul class="dropdown-menu pull-right jeffCombobox" role="menu">';
	foreach($opts as & $v) {
		$html.= '<li v="'.$v['value'].'"><a>'.$v['value'].'</a></li>';
	}                
	$html .= 	'</ul>
	            </div>';
	$html .= "</div>";
	// dump($params);dump($opts);dump($html);//exit;
	return $html;
}
?>