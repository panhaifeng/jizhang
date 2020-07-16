<?php
/*
*供应商按字母排序的要求 2014、7、4
*/
function _ctlBtSelectGYS($name,$params){	
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
            $rowset = $m->findAll(null,'CONVERT( compName USING gbk ) COLLATE gbk_chinese_ci ASC');
            foreach($rowset as & $v) {
                FLEA::loadClass('TMIS_Common');
                // dump(mb_substr($v['compName'],0,1, 'utf-8'));exit;
                $letters=strtoupper(TMIS_Common::getPinyin(mb_substr($v['compName'],0,1, 'utf-8')));
                $opts[] = array('text'=>$v[$m->primaryName],'value'=>$v[$m->primaryKey],'letters'=>$letters);
            }
            // dump($opts);exit;
        }       
    }

    $html = "<select name='{$itemName}' id='{$itemName}' {$disabled} class='form-control'>";
    $html .= "<option value=''>请选择</option>";
    foreach($opts as $k=>&$v) {
        if($letter!=substr($v['letters'],0,1)){
                    $letter=substr($v['letters'],0,1);
                    if($letter!='')$html .="<optgroup label='{$letter}'>";
                }
    	$html.= "<option value='{$v['value']}'";
    	if($selected==$v['value']) $html.=" selected ";
    	$html.=">{$v['text']}</option>";
        
        if($letter!=substr($opts[$k+1]['letters'],0,1)){
                    if($letter!='')$html .="</optgroup>";
                }
    }
    $html .= "</select>";
	return $html;	
}
?>