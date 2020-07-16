<?php
/*
*2014-3-26 by jeff : select需要被bootstrap控件使用，所以这里改动为select1
*/
function _ctlBtSupplierSelect($name,$params){  
    $itemName   = $params['itemName'];
    // $opts    = $params['options'];
    $selected = $params['value'];

    //得到库位信息
    $m = & FLEA::getSingleton('Model_Jichu_Supplier');
    $sql = "select * from jichu_supplier where 1";
    $opts = $m->findBySql($sql);
    $html = "<select name='{$itemName}' id='{$itemName}' class='form-control'>
    ";
    $html .= "<option value=''>请选择</option>
    ";
    foreach($opts as &$v) {
        $html.= "<option value='{$v['id']}'";
        if($selected==$v['id']) $html.=" selected ";
        $html.=">{$v['compName']}</option>
        ";
    }
    $html .= "</select>";
    // dump($html);
    return $html;   
}
?>