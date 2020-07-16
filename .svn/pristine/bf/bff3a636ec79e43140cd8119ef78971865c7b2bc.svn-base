<?php
load_class('TMIS_TableDataGateway');
class Model_Jichu_Jiagonghu extends TMIS_TableDataGateway {
	var $tableName = 'jichu_jiagonghu';
	var $primaryKey = 'id';
	var $primaryName = 'compName';
	var $sortByKey = 'convert(trim(compName) USING gbk)';    

	//获取加工户的options
	function getOptions(){
		$rows[] = array('text'=>'请选择','value'=>'0');
		$rowset = $this->findAll(null,'paixu');
		foreach ($rowset as $key => & $v) {
			$rows[] = array('text'=>$v['compName'],'value'=>$v['id']);
		}
		return $rows;
	}
}
?>