<?php
/*
*2014-3-26 by jeff 
*/
function _ctlBtCalendar($name,$params){	
	$itemName 	= $params['itemName'];
	$readonly = $params['readonly']?"readonly":'';
	$disabled = $params['disabled']?"disabled":'';
    $value = $params['value']?$params['value']:'';
    $inTable = $params['inTable'];
    // dump($inTable);
    // 
    if(!$inTable) {
    	$html = '<div class="input-group input-group-sm">';
		$html .= "<input type='text' class='form-control' name='{$itemName}' id='{$itemName}' value='{$value}' {$disabled} {$readonly}  onClick='calendar()' />";
		//加上日期图标
		$html .= '<span class="input-group-btn">
	        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" tabindex="-1" id="btnCalendar">
	                <span class="caret"></span>
	                <span class="sr-only">Toggle Dropdown</span>
	              </button>
	      </span>';
		$html .= "</div>";
    } else {
    	// $html = "<input type=\"text\" name=\"{$itemName}\" class=\"form-control\" id=\"{$itemName}\" value=\"{$value}\"  onClick=\"calendar()\" />";

    	$html = "<div style='width:150px'>";
    	$html .= '<div class="input-group input-group-sm">';
		$html .= "<input type='text' class='form-control' name='{$itemName}' id='{$itemName}' value='{$value}' {$disabled} {$readonly}  onClick='calendar()' />";
		//加上日期图标
		$html .= '<span class="input-group-btn">
	        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" tabindex="-1" id="btnCalendar">
	                <span class="caret"></span>
	                <span class="sr-only">Toggle Dropdown</span>
	              </button>
	      </span>';
		$html .= "</div>";
		$html .= "</div>";
    }
    
	return $html;	
}
?>