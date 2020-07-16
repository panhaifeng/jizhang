<?php
FLEA::loadClass('TMIS_TableDataGateway');
class Model_Acm_User extends TMIS_TableDataGateway {
	var $tableName = 'acm_userdb';
	var $primaryKey = 'id';
	var $primaryName = 'realName';
	var $manyToMany = array(
		array(
			'tableClass' => 'Model_Acm_Role' ,
			'mappingName' => 'roles',
			'joinTable' => 'acm_user2role',
			'foreignKey' => 'userId',
			'assocForeignKey' => 'roleId'
		),
		array(
			'tableClass' => 'Model_Jichu_Employ' ,
			'mappingName' => 'traders',
			'joinTable' => 'acm_user2trader',
			'foreignKey' => 'userId',
			'assocForeignKey' => 'traderId'
		)
	);

	/*var $hasMany = array(
		array(
			'tableClass' => 'Model_Acm_User2trader',
			'foreignKey' => 'userId',
			'mappingName' => 'TraderId'
		)
	);*/

	function getRoles($userId) {
		$arr = $this->find($userId);
		return $arr[roles];
	}

	//一个用户可能对应多个业务员，所以在搜索的时候会用到in ******************
    //是否允许查看所有订单
	//如果是admin或者组名为'管理员'，返回true;
	//模块中有个功能叫'浏览所有订单',如果能访问该权限则返回true;
	function canSeeAllOrder($userId) {
		$user = $this->find($userId);
		//dump($user);exit;
		if ($user[userName]=='admin') return true;
		$funcId= '100-1';
		$roles = $this->getRoles($userId);
		foreach( $roles as & $v) {
		    $sql = "select count(*) cnt from acm_func2role where roleId='{$v['id']}' and menuId='{$funcId}'";
		    $r=$this->findBySql($sql);
		    if($r[0]['cnt']>0) return true;
		}
		return false;

	}

	#得到当前用户被允许查看哪个业务员的订单
	#存在一个用户对应多个业务员的情况，所以这里返回一个数组
	function getTraderIdOfCurUser($userId){
		$sql = "select traderId
		from acm_user2trader
		where userId='{$userId}'";
		$temp = $this->findBySql($sql);
		return array_col_values($temp,'traderId');
		// $user = $this->find($userId);
		// $mTrader = & FLEA::getSingleton('Model_JiChu_Employ');
		// $trader = $mTrader->find(array(employName=>$user[realName]));
		// return $trader['id'];
	}
	//一个用户可能对应多个业务员，所以在搜索的时候会用到in 
	//查找当前匹配的业务员有哪些
	function getTraderIdByUser($userId,$type=false){
		$user = $this->find($userId);
		if($type==true){
			//type==true 返回类型为数组
			return array_col_values($user['traders'],'id');
		}else{
			//type==false 返回类型为字符串
			if(count($user['traders'])==0)return 'null';
			$traders=join(',',array_col_values($user['traders'],'id'));
			return $traders;
		}
	}
}
?>