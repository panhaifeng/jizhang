<?php
load_class('TMIS_TableDataGateway');
class Model_Jichu_Employ extends TMIS_TableDataGateway {
	var $tableName = 'jichu_employ';
	var $primaryKey = 'id';
	var $primaryName = 'employName';
	var $belongsTo = array (
		array(
			'tableClass' => 'Model_Jichu_Department',
			'foreignKey' => 'depId',
			'mappingName' => 'Dep'
		)
	);
	function getTrader(){
	    $str="select x.* from jichu_employ x
		left join jichu_department y on y.id=x.depId
		where depName='销售部' or depName='业务部'
		and isFire=0";
		//根据用户得到业务员
		$mUser = & FLEA::getSingleton('Model_Acm_User');
		$canSeeAllOrder = $mUser->canSeeAllOrder($_SESSION['USERID']);
		if(!$canSeeAllOrder) {//如果不能看所有订单，得到当前用户的客户业务员
			 $traderId = $mUser->getTraderIdByUser($_SESSION['USERID']);
			 if($traderId)$str .= " and x.id in($traderId)";
		}
		$row = $this->findBySql($str);	    
	    return $row;
	}
	function getCaigouyuan(){
	    $str="select x.* from jichu_employ x
		left join jichu_department y on y.id=x.depId
		where depName='采购部'";
		//echo $str;
		$rowset = $this->findBySql($str);
	    return $rowset;
	}
    function getSelect(){
    	$row=$this->getTrader();
    	//dump($row);exit;
    	foreach($row as &$v){
           $arr[]=array('value'=>$v[$this->primaryKey],'text'=>$v[$this->primaryName]);
    	}
    	//dump($arr);exit;
        return $arr;
    }


}
?>