<?php
load_class('TMIS_TableDataGateway');
class Model_Shengchan_Waixie extends TMIS_TableDataGateway {
    var $tableName = 'shengchan_waixie';
	var $primaryKey = 'id';
    
    var $hasMany = array (
		array(
			'tableClass' => 'Model_Shengchan_Waixie2Product',
			'foreignKey' => 'waixieId',
			'mappingName' => 'waixie',
		)
    );

     //取得新外协单号
	function getNewWaixieCode() {
		$arr=$this->find(null,'waixieCode desc','waixieCode');
		$max = substr($arr['waixieCode'],2);
		$temp = date("ymd")."001";
		if ($temp>$max) return 'WX'.$temp;
		$a = substr($max,-3)+1001;
		return 'WX'.substr($max,0,-3).substr($a,1);
	}

}
?>