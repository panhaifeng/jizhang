<?php
function _ctlBtnRukuYuanliao($name,$params){
	// dump($params);
	$itemName 	= $params['itemName'];
	$readonly = $params['readonly']?"readonly":'';
	$disabled = $params['disabled']?"disabled":'';
    $value = $params['value']?$params['value']:'';

    if($value!='') {
    	$m = & FLEA::getSingleton('Model_Yuanliao_Cgrk2product');
    	$sql = "select * from yuanliao_cgrk2product where id='{$value}'";
    	$temp = $m->findBySql($sql);
    	//dump($temp);exit;
    	$rukuId = $temp[0]['rukuId'];
        $cgrk=& FLEA::getSingleton('Model_Yuanliao_Cgrk');
        $sql="select * from yuanliao_cgrk where id='{$rukuId}'";
        $res=$cgrk->findBySql($sql);
        //dump($res);exit;
        $rukuCode=$res[0]['rukuCode'];
    }

    // $orderName = $params['orderName']?$params['orderName']:'';
	$html = "<input name='rukuCode' class='form-control' type='text' id='rukuCode' value='{$rukuCode}' disabled />";
	$html.= "<button type='button' class='btn btns btn-primary' id='btnRukuYuanliao' name='btnRukuYuanliao' style='border-radius:0px 2px 2px 0px; border:0px;'>...</button>";
	$html.="<input name='{$itemName}' type='hidden' id='{$itemName}' value='{$value}'>";
    // dump($html);
	return $html;
}
?>