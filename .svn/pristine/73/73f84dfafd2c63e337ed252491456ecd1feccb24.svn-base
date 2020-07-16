<?php

function arrInSampleOptions($kind='0') {
	if (empty($emptyText)) $emptyText='请选择';
	if($kind==4)$kind=1;
	$sql="select itemName,itemCode from jichu_sample where kind=".$kind;
	$result=mysql_query($sql);
	while($row = mysql_fetch_array($result)){
	    $rowName[]=$row;
	}
	return $rowName;
}
function _ctlSampleoptions($name, $params){    
	if ($params['model']!="") {
		$modelName = "Model_" . $params['model'];
		$_model = FLEA::getSingleton($modelName);
		$arr = arrInSampleOptions($params['kind']);
	} elseif ($params['inf']!="") {
		$arr=FLEA::getAppInf($params['inf']);
	}
	if($params['kind']=='1'){
	    $index=0;
	    $ret="";
	    if(count($arr)>0){
		foreach ($arr as $k => $value){
		    $index++;
		    $ret.=$value['itemName']."<input type='checkbox' name='hzl[]' onClick='setnull()' id='hzl[]' value='";
		    if(!is_array($params[selected])) $ret.=$value['itemName']."' ";
		    foreach($params[selected] as $key => $v){
			if($v == $value['itemName']){
			    $ret.=$value['itemName']."' checked ";
			    break;
			}
			else if($key==count($params[selected])-1)
			    $ret.=$value['itemName']."' ";

		    }
		    $ret.=" style='width:25px'>&nbsp;&nbsp;";
		    if($index%6==0)$ret.="<br><br>";
		    
		 }
	    }
	    return $ret;
	}
	if($params['kind']=='0')$title='选择成分';
	if($params['kind']=='2')$title='选择格型';
	if($params['kind']=='3')$title='选择组织';
	if($params['kind']=='4')$title='后整理工艺';
	$ret = "<option value=\"\" style=\"color:#ccc\">{$title}</option>\n";
	if(count($arr)>0) {
		$i =0;
		foreach ($arr as $cap => $value) {
			$ret .= "<option value=\"$value[itemName]\"";
			if($i==0 && $value[itemName]=='') $ret .= " style=\"color:#ccc\"";
			//if selected is a var
			if (!is_array($params[selected])) {
				$ret .= ($value[itemName] == $params[selected]) ? " selected" : "";
			} else {
				//$tempArr = array_col_values($params[selected],$_model->primaryKey);
				//if (in_array($value,array_col_values($params[selected],$_model->primaryKey))) {
					//$ret .= " selected";
				//}
			}
			
			$ret .= ">$value[itemName]</option>\n";
			$i++;
		}
	}
	//dump($ret);
	return $ret;
}
?>