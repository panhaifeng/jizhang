<?php
/*
*2014-3-26 by jeff : select需要被bootstrap控件使用，所以这里改动为select1
*/
function _ctlBtSelect($name,$params){	
	$itemName 	= $params['itemName'];
	$opts 	= $params['options'];
	$selected = $params['value'];
    $disabled = $params['disabled']?"disabled":'';
    //$selected = 1;
    // dump($params);
    $model = $params['model'];
 
    if($model!='') {
        if(count($opts)==0) { //根据model取得所有的基础档案数据
            $m = & FLEA::getSingleton($model);
            $rowset = $m->findAll();
            foreach($rowset as & $v) {
                $opts[] = array('text'=>$v[$m->primaryName],'value'=>$v[$m->primaryKey]);
            }
            // dump($opts);exit;
        }       
    }

    $html = "<select name='{$itemName}' id='{$itemName}' {$disabled} class='form-control'>";
    $html .= "<option value=''>请选择</option>";
    foreach($opts as &$v) {
    	$html.= "<option value='{$v['value']}'";
    	if($selected==$v['value']) $html.=" selected ";
    	if(isset($v['disabled']) && !empty($v['disabled'])) $html.=" disabled = 'disabled'";
    	$html.=">{$v['text']}</option>";
    }
    $html .= "</select>";
	return $html;	
}
?>