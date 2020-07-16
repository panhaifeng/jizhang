<?php
function _ctlBtDajuanSelect($name,$params){	
	$itemName 	= $params['itemName'];
	// $opts 	= $params['options'];
	$selected = $params['value'];

 //    //得到库位信息
 //    $m = & FLEA::getSingleton('Model_Jichu_Kuwei');
 //    $sql = "select * from jichu_kuwei where 1";
 //    $opts = $m->findBySql($sql);
 //    $html = "<select name='{$itemName}' id='{$itemName}' class='form-control'>
 //    ";
 //    $html .= "<option value=''>库位</option>
 //    ";
 //    foreach($opts as &$v) {
 //    	$html.= "<option value='{$v['id']}'";
 //    	if($selected==$v['id']) $html.=" selected ";
 //    	$html.=">{$v['kuweiName']}</option>
 //        ";
 //    }
 //    $html .= "</select>";
 //    // dump($html);
	// return $html;	
    $html="<select name='{$itemName}' id='{$itemName}' class='form-control'>";
    $html.= "<option value='0'";
    if($selected=='0') $html.=" selected ";
        $html.=">单卷单匹</option>";
    $html.= "<option value='1'";
    if($selected=='1') $html.=" selected ";
        $html.=">打包</option>";
     $html.= "</select>";
    return $html;
}
?>