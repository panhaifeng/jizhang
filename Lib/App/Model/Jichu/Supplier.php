<?php
load_class('TMIS_TableDataGateway');
class Model_Jichu_Supplier extends TMIS_TableDataGateway {
	var $tableName = 'jichu_supplier';
	var $primaryKey = 'id';
	var $primaryName = 'compName';
	var $sortByKey = 'convert(trim(compName) USING gbk)';
        var $belongsTo = array(
		array(
			'tableClass' => 'Model_Jichu_Employ',
			'foreignKey' => 'traderId',
			'mappingName' => 'Trader'
		)

	);
	var $hasMany = array(
		array(
			'tableClass' => 'Model_Jichu_SupplierTaitou',
			'foreignKey' => 'supplierId',
			'mappingName' => 'Supplier'
		)

	);
     //获取这个供应商购买过的坯纱规格
     function getPishaDesc($supplierId){
         $sql="SELECT * from pisha_cgrk where supplierId='{$supplierId}' group by guigeDesc";
          $query=mysql_query($sql);
	    while($re=mysql_fetch_assoc($query)){
		$row[]=$re;
	    }
	    return $row;
     }


}
?>