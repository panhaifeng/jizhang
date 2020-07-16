<?php
load_class('TMIS_TableDataGateway');
class Model_Cangku_Yl_Chuku extends TMIS_TableDataGateway {
	var $tableName = 'cangku_yl_chuku';
	var $primaryKey = 'id';
	var $primaryName = 'chukuNum';
	var $belongsTo = array (
		/*array(
			'tableClass' => 'Model_Jichu_Chejian',
			'foreignKey'=>'chejianId',
			'mappingName' => 'Chejian'
		),*/
		array(
			'tableClass' => 'Model_Acm_User',
			'foreignKey' => 'operatorId',
			'mappingName' => 'Operator'
		),
		array(
			'tableClass' => 'Model_Jichu_Department',
			'foreignKey' => 'depId',
			'mappingName' => 'Dep'
		)
	);
	var $hasMany = array(
		array(
			'tableClass' => 'Model_Cangku_Yl_Chuku2Yl',
			'foreignKey' => 'chukuId',
			'mappingName' => 'Yl'
		)
	);



	function getNewChukuNum() {
		$head = 'YC';
		$arr=$this->find(array(
			array('chukuNum',$head.date("ym")."___",'like')
		),'chukuNum desc','chukuNum');
		$max = $arr['chukuNum'];
		$temp = $head.date("ym")."001";
		if ($temp>$max) return $temp;
		$a = substr($max,-3)+1001;
		return substr($max,0,-3).substr($a,1);
	}
	#改变加工户库存
	/*function changeKucun(){
		$str="select * from weiwai_kucun where jiagonghuId='$jiagonghuId' and ylId='$ylId'";
		$re=mysql_fetch_assoc(mysql_query($str));
		if($re){
		    $cnt1=$re['cnt']+$cnt;
		    $str1="update weiwai_kucun set cnt='$cnt1' where id='{$re['id']}'";
		    mysql_query($str1);
		}else{
		    $cnt2=$cnt;
		    $str2="insert into weiwai_kucun(jiagonghuId,ylId,cnt) values('$jiagonghuId','$ylId','$cnt2') ";
		    mysql_query($str2);
		}
	}*/
}