<?php
/*********************************************************************\
*  Copyright (c) 1998-2013, TH. All Rights Reserved.
*  Author :Tomhour
*  FName  :BtPopupForAdd.php
*  Time   :2017年5月8日 13:51:39
*  Remark :shen
\*********************************************************************/
function _ctlBtPopupForAdd($name,$params){
    //传递的参数
    $itemName   = $params['itemName'];
    $readonly = $params['readonly']?"readonly":'';
    $disabled = $params['disabled']?"disabled":'';
    $value = $params['value']?$params['value']:'';
    $text = $params['text']?$params['text']:'';
    $tip = $params['tip']?$params['tip']:'';
    $dialogWidth = $params['dialogWidth']?$params['dialogWidth']:'0';
    $url = $params['url']?$params['url']:'';//弹出地址
    $urlAdd = $params['urlAdd']?$params['urlAdd']:'';//弹出地址
    // $jsTpl = $params['jsTpl']?$params['jsTpl']:'';//需要载入的模板文件，这个文件中不需要使用{literal},因为是通过file_get_content获得的
    // $onSelFunc = $params['onSelFunc']?$params['onSelFunc']:'';//选中后需要执行的回调函数名,需要在jsTpl中书写
    $textFld =  $params['textFld']?$params['textFld']:'';//显示在text中的字段
    $hiddenFld =  $params['hiddenFld']?$params['hiddenFld']:'';//显示在hidden控件中的字段
    $inTable =  $params['inTable']?$params['inTable']:0;//显示在hidden控件中的字段
  //$js_function = $params['js_function']?$params['js_function']:'';

    // if($url=='') {
    //   dump($params);
    //   js_alert("{$itemName}对应的url属性没有指定!");
    //   exit;
    // }
    // if($textFld=='') {
    //   dump($params);
    //   js_alert("{$itemName}对应的textFld属性没有指定!");
    //   exit;
    // }
    // if($hiddenFld=='') {
    //   dump($params);
    //   js_alert("{$itemName}对应的hiddenFld属性没有指定!");
    //   exit;
    // }
    $html .= '
    <div class="input-group input-group-sm clsPop">
      <span class="input-group-btn" id="btnPopForAdd" name="btnPopForAdd" url="'.$urlAdd.'" textFld="'.$textFld.'" hiddenFld="'.$hiddenFld.'" dialogWidth="'.$dialogWidth.'" tip="'.$tip.'" style="width:20px;" >
        <button class="btn btn-default btn-sm" type="button">
          <span class="glyphicon glyphicon-plus"></span>
        </button>
      </span>
    </div>';
    if($inTable) $html= "<div style='width:150px;'>
    {$html}
    </div>";
  return $html;
}
?>