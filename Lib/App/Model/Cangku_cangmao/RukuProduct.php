<?php
load_class('TMIS_TableDataGateway');
class Model_Cangku_RukuProduct extends TMIS_TableDataGateway {
	var $tableName = 'cangku_ruku2product';
	var $primaryKey = 'id';
	var $belongsTo = array (
		array(
			'tableClass' => 'Model_Jichu_Product',
			'foreignKey' => 'productId',
			'mappingName' => 'Products'
		),
		array(
			'tableClass' => 'Model_Cangku_Ruku',
			'foreignKey' => 'ruKuId',
			'mappingName' => 'Ruku'
		),
		
		array(
			'tableClass' => 'Model_Jichu_Kuwei',
			'foreignKey' => 'kuweiId',
			'mappingName' => 'Kuwei'
		)
	);

	#改变加工户库存
	function changeKucun($arr){
		//日期，$rukuId，数量：入库数量表cangku_ruku2product，库存表cangku_kucun
		//删除的情况
		if($arr['type']=="remove"){
			$sql="delete from cangku_kucun where ruku2proId='{$arr['id']}'";
			mysql_query($sql) or die(mysql_error());
		}else{
			// dump($arr);exit;
			//删除传递过来的日期之后的所有数据，重新生成库存信息
			$sql="delete from cangku_kucun where ruku2proId='{$arr['id']}' and kucunDate >= '{$arr['date']}'";
			mysql_query($sql) or die(mysql_error());

			//查找传递日期前最近的库存作为初始化库存，没有的话查找入库的数量作为库存，
			$sql="select * from cangku_kucun where ruku2proId ='{$arr['id']}' order by kucunDate desc limit 0,1";
			$init=$this->findBySql($sql);
			/*//如果库存表里存在记录
			if($init[0]['id']>0){
				
			}else{//库存表里不存在记录，则需要重新生成所有的库存信息

			}*/
			$rowset=array();
			//查找所有在日期内的入库记录
			$sql="select y.rukuDate,x.cnt from cangku_ruku2product x
				left join cangku_ruku y on y.id=x.rukuId
				where (x.id='{$arr['id']}' or x.ruku2ProId='{$arr['id']}') and y.rukuDate >= '{$arr['date']}'";
				// echo $sql;exit;
			$row1=$this->findBySql($sql);
			// dump($row1);exit;
			foreach($row1 as & $v){
				$rowset[$v['rukuDate']]+=round($v['cnt'],4);
			}

			//查找所有在日期内的出库记录
			$sql="select y.chukuDate,x.cnt from cangku_chuku2product x
				left join cangku_chuku y on y.id=x.chukuId
				where x.ruku2proId='{$arr['id']}' and y.chukuDate >= '{$arr['date']}'";
			$row2=$this->findBySql($sql);
			foreach($row2 as & $v){
				$rowset[$v['chukuDate']]-=round($v['cnt'],4);
			}
			// dump($rowset);//exit;
			//计算各个时间节点的库存情况，形成数组，保存在库存表里
			$kunCun=$init[0]['kucunCnt'];
			$kuncun_arr=array();
			$kucun = & FLEA::getSingleton("Model_Cangku_KucunCz");
			foreach($rowset as $key => & $v){
				$kunCun=$kunCun+$v;
				$kuncun_arr[]=array(
					'ruku2proId'=>$arr['id'],
					'kucunDate'=>$key,
					'kucunCnt'=>$kunCun
				);
			}
			// dump($kuncun_arr);exit;
			if($kuncun_arr)$kucun->saveRowset($kuncun_arr);

		}

	}


	//取得入库成品中某一车间, 某一原料的所有数量
	function getRukuYlCnt($ylId, $chejianId) {
		//echo($ylId); exit;
		$initDate = '2009-4-1';		//设定期初日期

		$count = 0;		//本函数返回变量

		//找出含有$ylId的产品
		$modelProduct = FLEA::getSingleton('Model_Jichu_Product'); 
		$arrProId = $modelProduct->getArrProId($ylId);

		if (count($arrProId)>0) foreach($arrProId as & $v) {
			$condition[] = array('productId',$v['productId']);
			$condition[] = array('Ruku.chejianId',$chejianId);
			$condition[] = array('Ruku.rukuDate',$initDate, '>=');
			$rowset = $this->findAll($condition, null, null, "productId,cnt");
			//$rowset = $this->findAll($condition, null, null);	//指定到某一车间 
			//dump($rowset); //exit;
			if (count($rowset)>0) foreach($rowset as & $item) {
				$count += $item['cnt']*$v['ylCnt'];
			}
			$condition = array();
		}
		return $count;
	}

	//取得入库成品中某一原料的所有数量
	function getRukuYlCntAll($ylId) {
		$initDate = '2009-4-1';		//设定期初日期
		$count = 0;					//本函数返回变量

		//找出含有$ylId的产品
		$modelProduct = FLEA::getSingleton('Model_Jichu_Product'); 
		$arrProId = $modelProduct->getArrProId($ylId);

		if (count($arrProId)>0) foreach($arrProId as & $v) {
			$condition[] = array('productId',$v['productId'], '=');
			$condition[] = array('Ruku.rukuDate',$initDate, '>=');
			$rowset = $this->findAll($condition, null, null, "productId,cnt");
			if (count($rowset)>0) foreach($rowset as & $item) {
				$count += $item['cnt']*$v['ylCnt'];
			}
			$condition = array();
		}
		return $count;

	}
}
?>