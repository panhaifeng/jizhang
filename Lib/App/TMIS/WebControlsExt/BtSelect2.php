<?php
/*
*2014-3-26 by jeff : select需要被bootstrap控件使用，所以这里改动为select1
*/
function _ctlBtSelect2($name,$params){
    $itemName   = $params['itemName'];
    $opts   = $params['options'];
    $optionType   = $params['optionType'];
    $selected = $params['value'];
    $condition = $params['condition'];
    $sortByKey = $params['sort'];
    $disabled = $params['disabled']?"disabled":'';
    $multiple = $params['multiple']?"multiple='multiple'":'';
    $size     = $params['size']?"size='{$params['size']}'":"";
    $emptyText = $params['emptyText']?$params['emptyText']:'请选择';
    $needEmpty = $params['needEmpty']?true:false;
    //增加了新的参数'isSearch'，使得登记界面的选择框具有搜索，2015-09-28，by liuxin
    $isSearch = $params['isSearch']?'select2':'form-control input-sm select2';
    //$selected = 1;`
    $model = $params['model'];
    $inTable =  $params['inTable']?"style='width:auto'":'';//显示在hidden控件中的字段

    if($model!='' && count($opts)==0) {
        //根据model取得所有的基础档案数据
        $m = & FLEA::getSingleton($model);
        $sortByKey = $m->sortByKey!=''?$m->sortByKey:$sortByKey;
        $rowset = $m->findAll($condition,$sortByKey,NULL);
        foreach($rowset as & $v) {
            $opts[] = array('text'=>$v[$m->primaryName],'value'=>$v[$m->primaryKey]);
        }
    }else if(count($opts)==0 && $optionType!=''){
        $_m = & FLEA::getSingleton('TMIS_Option');
        $opts = $_m->getOptions($optionType);
    }

    $html = "<select name='{$itemName}' id='{$itemName}' {$multiple} {$size} {$disabled} class='{$isSearch}' {$inTable}>";

    if($needEmpty){
        $html .= "<option value=''>{$emptyText}</option>";
    }


    //需要汇总显示的，处理
    if($m->optgroup==true){
        FLEA::loadClass('TMIS_Common');
        $letterTrue=true;
        $letter='';
    }

    // 兼容以「,」分隔的字符
    if(!is_array($selected)){
        $selected = explode(',', trim($selected));
    }else{
        $selected = array_col_values($selected,'id');
    }

    foreach($opts as $k => & $v) {
        //处理汇总信息
        if($letterTrue && $v['value']!=''){
            $temp_lett=strtoupper(TMIS_Common::getPinyin($v['text']));
            if($letter!=substr($temp_lett,0,1)){
                $letter=substr($temp_lett,0,1);
                if($k>1)$html .="</optgroup>";
                $html .="<optgroup label='{$letter}'>";
            }
        }
        //处理下拉选项信息
        $html.= "<option value='{$v['value']}'";
        if(in_array($v['value'],$selected)) $html.=" selected ";
        $html.=">{$v['text']}</option>";
    }
    $html .= "</select>";
    return $html;
}
?>