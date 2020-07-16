<?php
/*
*2014-3-26 by jeff : select需要被bootstrap控件使用，所以这里改动为select1
*/
function _ctlBtText($name,$params){
	$itemName 	= $params['itemName'];
	$readonly = $params['readonly']?"readonly":'';
	$disabled = $params['disabled']?"disabled":'';
    $value = $params['value']?$params['value']:'';
    $titleText = $params['titleText'];
    $addonPre = $params['addonPre'];
    $addonEnd = $params['addonEnd'];
    $showTitle = $params['showTitle'];
    $style = $params['style']?$params['style']:'';
    $style!='' && $style="style='{$style}'";

    // dump($params);//exit;
    if(!$addonPre && !$addonEnd) {
        $title = $showTitle?$value:'';
		$html = "<input type='text' class='form-control' {$style} name='{$itemName}' id='{$itemName}' value='{$value}' {$disabled} {$readonly} title='{$title}' />";
    } else {
    	//有头尾附加文字的使用inputgroup
    	$html = '<div class="input-group input-group-sm">';
    	if($addonPre) $html.= '<span class="input-group-addon">'.$addonPre.'</span>';
    	$html.="<input type='text' title='{$titleText}' class='form-control' {$style} name='{$itemName}' id='{$itemName}' value='{$value}' {$disabled} {$readonly} title='{$title}'/>";
    	if($addonEnd) $html.= '<span class="input-group-addon">'.$addonEnd.'</span>';
		$html.='</div>';
    }
	return $html;
}
?>