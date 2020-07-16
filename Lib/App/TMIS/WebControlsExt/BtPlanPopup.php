<?php
/*
*2014-3-26 by jeff : select需要被bootstrap控件使用，所以这里改动为select1
*/
function _ctlBtPlanPopup($name,$params){
	// dump($params);
	$itemName 	= $params['itemName'];
	$readonly = $params['readonly']?"readonly":'';
	$disabled = $params['disabled']?"disabled":'';
  $value = $params['value']?$params['value']:'';

  if($value!='') {
  	$m = & FLEA::getSingleton('Model_Trade_Order');
  	$sql = "select y.* from trade_order2product x
    left join trade_order y on x.orderId=y.id
    where x.id='{$value}'";
    // dump($sql);
  	$temp = $m->findBySql($sql);
  	// dump($temp);
  	$planCode = $temp[0]['orderCode'];
  }
    
	// $html = "<input name='orderName' class='form-control' type='text' id='orderName' value='{$orderName}' disabled />";	
	// $html.= "<button type='button' class='btn btns btn-primary' id='btnorderName' name='btnorderName' style='border-radius:0px 2px 2px 0px; border:0px;'>...</button>";
	// $html.="<input name='{$itemName}' type='hidden' id='{$itemName}' value='{$value}'>";

  // $html = '
  // <div class="input-group input-group-sm">
  //   <input name=\'planCode\' class=\'form-control\' type=\'text\' id=\'planCode\' value="'.$planCode.'" disabled />
  //   <span class="input-group-btn" id="btnPlanCode" name="btnPlanCode">
  //     <button class="btn btn-default" type="button">...</button>
  //   </span>
  //   <input name="'.$itemName.'" type="hidden" id="'.$itemName.'" value="'.$value.'">
  // </div>';

  $html = "
  <div style='width:150px;'>
      <div class='input-group span12'>      
        <input name='planCode' class='form-control'  type='text' id='planCode' value='{$planCode}' readonly placeholder='单击选择' />
        <span class=\"input-group-btn\" name='btnPlanCode' id='btnPlanCode'>
          <button class=\"btn btn-default\" type=\"button\">...</button>
        </span>
        <input name='{$itemName}' type='hidden' id='plan2proId' value='{$value}' />
      </div>
  </div>
  ";
  // dump($html);
	return $html;	
}
?>