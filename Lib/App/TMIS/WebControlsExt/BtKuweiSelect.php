<?php
/*
*2014-3-26 by jeff : select需要被bootstrap控件使用，所以这里改动为select1
*/
function _ctlBtKuweiSelect($name,$params){	
	$itemName  = $params['itemName'];
    $selected  = $params['value'];
	$kwType    = $params['kwType'];
    // $opts    = $params['options'];
    // dump($isSingle);die;

    //得到库位信息
    $m = & FLEA::getSingleton('Model_Jichu_Kuwei');
    $sql = "select * from jichu_kuwei where 1 ";
    if($kwType) $sql.="and type='{$kwType}'";
    $sql.=" order by type";
    $opts = $m->findBySql($sql);
    $html = "<select name='{$itemName}' id='{$itemName}' class='form-control'>
    ";
    $html .= "<option value=''>请选择</option>
    ";
    foreach($opts as $k=>&$v) {
        if($type!=$v['type'] && !$kwType){
            $type=$v['type'];
            if($type!='')$html .="<optgroup label='{$type}'>";
        }
        $html.= "<option value='{$v['kuweiName']}'";
        if($selected==$v['kuweiName']) $html.=" selected ";
        $html.=">{$v['kuweiName']}</option>";
        
        if($type!=$opts[$k+1]['type']){
            if($type!='')$html .="</optgroup>";
        }    
    }
    $html .= "</select>";
    // dump($html);
	return $html;	
}
?>