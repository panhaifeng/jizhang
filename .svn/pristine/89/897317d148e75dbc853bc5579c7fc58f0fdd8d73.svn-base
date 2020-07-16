<?php
/*********************************************************************\
*  Copyright (c) 1998-2013, TH. All Rights Reserved.
*  Author :Tomhour
*  FName  :BtPopup.php
*  Time   :2014/05/12 16:29:25
*  Remark :jeff
\*********************************************************************/
function _ctlBtPopup($name,$params){
  //传递的参数
  $itemName   = $params['itemName'];
  $readonly = $params['readonly']?"readonly":'';
  $disabled = $params['disabled']?"disabled":'';
  $value = $params['value']?$params['value']:'';
  $text = $params['text']?$params['text']:'';
  $url = $params['url']?$params['url']:'';//弹出地址
  // $jsTpl = $params['jsTpl']?$params['jsTpl']:'';//需要载入的模板文件，这个文件中不需要使用{literal},因为是通过file_get_content获得的
  // $onSelFunc = $params['onSelFunc']?$params['onSelFunc']:'';//选中后需要执行的回调函数名,需要在jsTpl中书写
  $textFld =  $params['textFld']?$params['textFld']:'';//显示在text中的字段
  $hiddenFld =  $params['hiddenFld']?$params['hiddenFld']:'';//显示在hidden控件中的字段
  $inTable =  $params['inTable']?$params['inTable']:0;//显示在hidden控件中的字段
  //$js_function = $params['js_function']?$params['js_function']:'';
  //dump($url);exit;
  if($url=='') {
    dump($params);
    js_alert("{$itemName}对应的url属性没有指定!");
    exit;
  }
  if($textFld=='') {
    dump($params);
    js_alert("{$itemName}对应的textFld属性没有指定!");
    exit;
  }
  if($hiddenFld=='') {
    dump($params);
    js_alert("{$itemName}对应的hiddenFld属性没有指定!");
    exit;
  }
  $html .= '
  <div class="input-group input-group-sm clsPop">
    <input name=\'textBox\' class=\'form-control\' type=\'text\' id=\'textBox\' value="'.$text.'" disabled />
    <span class="input-group-btn" id="btnPop" name="btnPop" url="'.$url.'" textFld="'.$textFld.'" hiddenFld="'.$hiddenFld.'">
      <button class="btn btn-default" type="button">...</button>
    </span>
    <input name="'.$itemName.'" type="hidden" id="'.$itemName.'" class="hideId" value="'.$value.'">  
  </div>';
  if($inTable) $html= "<div style='width:150px;'>
  {$html}
  </div>";
  return $html;
}
?>