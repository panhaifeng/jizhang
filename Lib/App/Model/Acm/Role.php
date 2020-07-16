<?php
FLEA::loadClass('TMIS_TableDataGateway');
class Model_Acm_Role extends TMIS_TableDataGateway {
	var $tableName = 'acm_roledb';
	var $primaryKey = 'id';
	var $primaryName = 'roleName';
	var $manyToMany = array(		
		array (
			'tableClass' => 'Model_Acm_User' ,			
			'mappingName' => 'users',
			'joinTable' => 'acm_user2role',
			'foreignKey' => 'roleId',
			'assocForeignKey' => 'userId'
		),
//		array (
//			'tableClass' => 'Model_Acm_Func' ,
//			'mappingName' => 'funcs',
//			'joinTable' => 'acm_func2role',
//			'foreignKey' => 'roleId',
//			'assocForeignKey' => 'funcId'
//		)
	);	
	function getFuncs($funcId) {
		$arr = $this->find($funcId);
		return $arr['funcs'];
	}
}
?>