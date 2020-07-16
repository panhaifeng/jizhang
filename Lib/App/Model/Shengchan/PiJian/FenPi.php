<?php
load_class('TMIS_TableDataGateway');
class Model_Shengchan_PiJian_FenPi extends TMIS_TableDataGateway {
	var $tableName = 'shengchan_planpj_fenpi';
	var $primaryKey = 'id';
	//var $primaryName = 'planCode';
	function addTimesPrint($pkv) {
		$str = "update shengchan_planpj_fenpi set timesPrint=timesPrint+1 where id=$pkv";
		mysql_query($str) or die(mysql_error());
	}
}
?>