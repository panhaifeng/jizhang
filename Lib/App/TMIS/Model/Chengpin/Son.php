<?php
/*
*需要形成收发存报表的子表模型需要从此类继承
*主要就是在子表crud时关联修改库存表的记录
*变化点：需要复制到库存表中字段有变化
		库存汇总的字段有变化
*要求:
1，出入库记录必须是主从表结构,且主键都为id
1,库存表为必须字段：、
	rukuId
	chukuId
	dateFasheng,发生日期
  `cntFasheng` decimal(15,2) NOT NULL COMMENT '发生数量,入库为+，出库为-',
  `moneyFasheng` decimal(15,2) NOT NULL COMMENT '金额'
2，库存汇总字段在出入库表和库存表中必须一致，不能有的是productId,有的是productId等
3，库存Model不需要定义belongTo属性，减少程序员工作量，但是需要在子表类定义时需要制定外键字段
4, 库存表中的索引必须建立完善，大数据下必须保证速度
5, 收发存报表必须先调用
*
*/
load_class('FLEA_Db_TableDataGateway');
class TMIS_Model_Chengpin_Son extends FLEA_Db_TableDataGateway {
	//库存model标识符
	var $modelKucun = 'Model_Chengpin_Kucun';
	//是否出库，如果是出库，记录生成时数量需要*-1
	var $isChuku = false;
	//子表记录中的主表映射名,用来取得主表记录
	var $mappingName = 'Ruku';
	//日期字段,必须在主表中
	var $fldDate = 'cprkDate';
	//数量,金额字段,必须在子表中,且数量字段可以是多个
	var $fldCnt = 'cnt';
	var $fldMoney = 'money';
	var $fldDuan='cntDuan';
	//其他需要复制到库存表中的字段，主要是汇总字段(分主从表,字段名必须一致)，也可能是数量字段（不参与库存计算)
	var $fldCopyMain = array('orderId','ord2proId');
	var $fldCopySon = array('productId');

	//记录新增后在库存表中插入记录
	function _afterCreateDb(&$row) {
		//dump($row);exit; //$row指插入子表的数据
		$son = $this->find(array('id'=>$row['id']));
		//dump($son);exit;
		$main = $son[$this->mappingName];
		$mKucun = & FLEA::getSingleton($this->modelKucun);
		//如果是出库
		//形成需要插入库存表中的记录
		$_row = array(
			'dateFasheng'=>$main[$this->fldDate],
		);
		if($this->isChuku) {
			$_row['chukuId'] = $row['id'];
			$_row['cntFasheng'] = $son[$this->fldCnt]*-1;
			$_row['moneyFasheng'] =$son[$this->fldMoney] *-1;
			$_row['duanFasheng'] =$son[$this->fldDuan]*-1;
		} else {
			$_row['rukuId'] = $row['id'];
			$_row['cntFasheng'] = $son[$this->fldCnt];
			$_row['moneyFasheng'] =$son[$this->fldMoney];
			$_row['duanFasheng'] =$son[$this->fldDuan];
		}
		//先取得主表中的相关字段,再取得从表中的相关记录
		foreach($this->fldCopyMain as & $v) {
			$_row[$v]=$main[$v];
		}
		foreach($this->fldCopySon as & $v) {
			$_row[$v]=$son[$v];
		}
		$mKucun->create($_row);
	}

	//修改之后,删掉原来的数据，重新生成即可
	function _afterUpdateDb(&$row) {
		$m = & FLEA::getSingleton($this->modelKucun);
		//找到匹配的库存记录
		$con = array($this->isChuku?'chukuId':'rukuId'=>$row['id']);
		$r = $m->find($con);
		$pkv = $r['id'];
		$m->removeByPkv($pkv);
		$this->_afterCreateDb($row);
	}

	//删除,关联删除库存记录即可
	function _afterRemoveDbByPkv ($id){
		$m = & FLEA::getSingleton($this->modelKucun);
		//找到匹配的库存记录
		$con = array($this->isChuku?'chukuId':'rukuId'=>$id);
		$r = $m->find($con);
		$pkv = $r['id'];
		$m->removeByPkv($pkv);
	} 

	//重新生成库存表数据
	function refreshKucun() {
		$fld = $this->isChuku?'chukuId':'rukuId';
		$mKucun = & FLEA::getSingleton($this->modelKucun);

		//删除库存表中数据
		$sql = "delete from {$mKucun->qtableName} where $fld>0";
		$this->execute($sql);

		//重新生成数据
		$sql = "select * from {$this->qtableName} where 1";
		$rowset = $this->findBySql($sql);
		foreach($rowset as & $v) $this->_afterCreateDb($v);
	}

	//生成收发存报表的sql语句
	function getSfcSql() {
		//得到库存表名
		$mKucun = & FLEA::getSingleton($this->modelKucun);
		$tblKucun = $mKucun->qtableName;

		$strDateFrom = '{$arr[\'dateFrom\']}';
		$strDateTo = '{$arr[\'dateTo\']}';
		$strCon = '{$strCon}';
		$strGroup = '{$strGroup}';

		//汇总字段
		$strG = join(',',array_merge($this->fldCopyMain,$this->fldCopySon));

		//期初
		$sqlInit = "select {$strGroup},
		sum(cntFasheng) as cntInit,
		sum(moneyFasheng) as moneyInit,
		0 as cntRuku,0 as moneyRuku,0 as cntChuku,0 as moneyChuku
		from {$tblKucun} where dateFasheng<'{$strDateFrom}' 
		 {$strCon}";
		$sqlInit .= " group by {$strGroup}";
		//dump($sqlInit);

		//本期入库
		$sqlRuku = "select {$strGroup},
		0 as cntInit,0 as moneyInit,
		sum(cntFasheng) as cntRuku,
		sum(moneyFasheng) as moneyRuku,
		0 as cntChuku,0 as moneyChuku
		from {$tblKucun} where 
		dateFasheng>='{$strDateFrom}' and dateFasheng<='{$strDateTo}'
		and rukuId>0  {$strCon}";
		$sqlRuku .= " group by {$strGroup}";
		//dump($sqlRuku);

		//本期入库
		$sqlChuku = "select {$strGroup},
		0 as cntInit,0 as moneyInit,
		0 as cntRuku,
		0 as moneyRuku,
		sum(cntFasheng*-1) as cntChuku,
		sum(moneyFasheng*-1) as moneyChuku 
		from {$tblKucun} where 
		dateFasheng>='{$strDateFrom}' and dateFasheng<='{$strDateTo}'
		and chukuId>0  {$strCon}";
		$sqlChuku .= " group by {$strGroup}";
		//dump($sqlChuku);

		//对sql进行加工
		$sqlUnion = "{$sqlInit} 
		union 
		{$sqlRuku} 
		union 
		{$sqlChuku}";
		
		$sql = "select 
		{$strGroup},
		sum(cntInit) as cntInit,
		sum(moneyInit) as moneyInit,
		sum(cntRuku) as cntRuku,
		sum(moneyRuku) as moneyRuku,
		sum(cntChuku) as cntChuku,
		sum(moneyChuku) as moneyChuku 
		from (".'{$sqlUnion}'.") as x
		group by {$strGroup}
		having sum(cntInit)<>0 or sum(moneyInit)<>0 
		or sum(cntRuku)<>0 or sum(moneyRuku)<>0
		or sum(cntChuku)<>0 or sum(moneyChuku)<>0";

		$str = '请将以下代码复制到你的控制器中进行二次修改:
		$strGroup="'.$strG.'";
		';
		$str.= '$sqlUnion="'.$sqlUnion.'";
		';
		$str.='$sql="'.$sql.'";';
		dump($str);exit;
		//return $str;
	}
}
?>