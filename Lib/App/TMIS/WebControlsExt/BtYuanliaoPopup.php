<?php
function _ctlBtYuanliaopopup($name,$params){
	// dump($params);
	$itemName 	= $params['itemName'];
	$value = $params['value'];
	$kind = $params['kind'];

	//得到产品信息
	if($value!='') {
		$sql = "select * from jichu_product where id='{$value}'";
		$m = & FLEA::getSingleton('Model_Jichu_Product');
		$temp = $m->findBySql($sql);
		$proCode = $temp[0]['proCode'];
		$proName = $temp[0]['proName'];
		$guige = $temp[0]['guige'];
		$unit = $temp[0]['unit'];
	}
	// $proCode = $params['proCode'];


	// $html = "
	// <div class='col-md-12'>
	//     <div class='input-group'>
	//       <input name='{$itemName}' type='hidden' id='{$itemName}' value='{$value}' />
	//       <input name='proCode' class='form-control'  type='text' id='proCode' value='{$proCode}' disabled />
	//       <span class='input-group-addon'>
	//       	<button class='btn btn-default' type='button'>...</button>
	//       </span>
	//     </div>
	// </div>
	// ";
	        // 
	$html = "
	<div style='width:100px;'>
	    <div class='input-group span12'>
	      <input name='_proKind' type='hidden' id='_proKind' value='{$kind}' />
	      <input name='{$itemName}' type='hidden' id='{$itemName}' value='{$value}' />
	      <input name='proCode' class='form-control'  type='text' id='proCode' value='{$proCode}' readonly placeholder='单击选择' />
	      <span class='input-group-addon' style='padding:0px;'>
	      	<button class='btn btn-primary' style='height:28px; border-radius:0px 2px 2px 0px; border:0px;' type='button' name='btnYuanliao' id='btnYuanliao'>...</button>
	      </span>
	    </div>
	</div>
	";
	// return 1;
	return $html;	
}
?>