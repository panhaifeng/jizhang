<?php
function _ctlActionoptions($name, $params)	{
	$emptyText = $params['emptyText']?$params['emptyText']:'选择业务员';
	$ret = "<option value='' style='color:#CCC'>{$emptyText}</option>";

	$m = & FLEA::getSingleton("Model_Jichu_Action");
	$arr = $m->getAction();

	//如果$params['selected']为已离职的，也需要显示出来
	if($params['selected']) {
		//如果传递过来的是数组，则特殊处理
		if (!is_array($params['selected'])) {
			$s = $m->find(array('id'=>$params['selected']));
			if($s['isStop']==1) {
			$arr[] = $s;
		}
		}else{
			$actionId=array_col_values($params['selected'],'id');
			$condition['in()'] = array('id'=>$actionId);
			$s = $m->findAll($condition);

			foreach($s as & $v){
				if($v['isStop']==1){
					$arr[] = $v;
				}
			}
		}
	}
	//	dump($arr);exit;
	if($arr) foreach ($arr as $key => & $value) {
		$ret .= "<option value='{$value['id']}'";
		if (!is_array($params['selected'])) {
			if($params['selected']==$value['id']) $ret .= " selected";
		}else{
			if (in_array($value['id'],array_col_values($params['selected'],'id'))) {
					$ret .= " selected";
			}
		}

		if($value['isStop']) $ret.= " style='background-color:pink;'";
		$ret .= ">{$value['itemName']}";
		if($value['isStop']) $ret.= "(暂停)";
		$ret .= "</option>";
	}
	return $ret;
}
?>