<?php
/*
*2014-3-26 by jeff : select需要被bootstrap控件使用，所以这里改动为select1
*注意，在附加的js模板文件中，onSel函数必须使用结果作为毁掉函数的参数，所以需要使用到bind函数进行事件定义
*/
function _ctlBtClientPopup($name,$params){	
  	/*
  	<input name='clientName' class=‘form-control  type='text' id='clientName' value="{$item.clientName}" disabled />
            <button type='button' class='btn btns btn-primary' id='btnclientName'>...</button>
            <!-- <span class="input-group-addon" id='btnclientName'>...</span> -->
  		  <input name="{$item.name|default:$key}"  type="hidden" id="{$item.name|default:$key}" value="{$item.value}">
  		  */
  		 // dump($params);
  	$itemName 	= $params['itemName'];
  	$readonly = $params['readonly']?"readonly":'';
  	$disabled = $params['disabled']?"disabled":'';
    $value = $params['value']?$params['value']:'';

    //得到客户名称
    if($value!='') {
    	$m = & FLEA::getSingleton('Model_Jichu_Client');
    	$sql = "select compName from jichu_client where id='{$value}'";
    	$temp = $m->findBySql($sql);
      // dump($temp);
    	$clientName = $temp[0]['compName'];
    }
    
    $clientName = $params['clientName']?$params['clientName']:$clientName;

    $html = '
    <div class="input-group input-group-sm">
      <input name=\'clientName\' class=\'form-control\' type=\'text\' id=\'clientName\' value="'.$clientName.'" disabled />
      <span class="input-group-btn" id="btnclientName" name="btnclientName">
        <button class="btn btn-default" type="button">...</button>
      </span>
      <input name="'.$itemName.'" type="hidden" id="'.$itemName.'" value="'.$value.'">
    </div>';
	return $html;	
}
?>