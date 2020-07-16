<?php
FLEA::loadClass('Tmis_Controller');
class Controller_TestKucun extends Tmis_Controller {
	function Controller_TestKucun() {
		$this->_modelExample = &FLEA::getSingleton('Model_Yuanliao_TestMainRuku');

		$this->_mRuku = &FLEA::getSingleton('Model_Yuanliao_TestMainRuku');
		$this->_mRukuSon = &FLEA::getSingleton('Model_Yuanliao_TestSonRuku');

		$this->_mChuku = &FLEA::getSingleton('Model_Yuanliao_TestMainChuku');
		$this->_mChukuSon = &FLEA::getSingleton('Model_Yuanliao_TestSonChuku');
	}

	function actionIndex() {
		echo "<li><a href='" . $this->_url('truncateRuku') . "'>清空入库</a></li>";
		echo "<li><a href='" . $this->_url('truncateChuku') . "'>清空出库</a></li>";
		echo "<li><a href='" . $this->_url('truncateKucun') . "'>清空库存</a></li>";
		echo "<br /><br />";
		echo "<li><a href='" . $this->_url('makeRuku') . "'>生成入库测试数据</a></li>";
		echo "<li><a href='" . $this->_url('makeChuku') . "'>生成出库测试数据</a></li>";

		echo "<br /><br />";
		echo "<li><a href='" . $this->_url('RefreshKucunR') . "'>重新生成入库库存表数据</a></li>";
		echo "<li><a href='" . $this->_url('RefreshKucunC') . "'>重新生成出库库存表数据</a></li>";
		echo "<li><a href='" . $this->_url('sfc') . "'>收发存sql</a></li>";
		echo "<br /><br />";

		$smarty = $this->_getView();
		$smarty->assign('arr_field_info1', array('mainId' => 'mainId',
				'sonId' => 'sonId',
				'rukuDate' => 'rukuDate',
				'supplierId' => 'supplierId',
				'productId' => 'productId',
				'pihao' => 'pihao',
				'kuwei' => 'kuwei',
				'cnt' => 'cnt',
				'money' => 'money',
				'_edit' => '操作'
				));
		$sql = "select 
		x.id as sonId, 
		y.id as mainId,
		y.kuwei,
		y.rukuDate as rukuDate,
		y.supplierId as supplierId,
		x.productId,
		x.pihao,
		x.cnt,
		x.money
		from yuanliao_cgrk2product x
		left join yuanliao_cgrk y on x.rukuId=y.id
		";
		$rowset1 = $this->_mRuku->findBySql($sql);
		foreach($rowset1 as &$v) {
			$v['_edit'] = "<a href='" . $this->_url('EditRuku', array('id' => $v['sonId']
					)) . "'>修改子表</a>";
			$v['_edit'] .= " <a href='" . $this->_url('EditRukuMain', array('id' => $v['mainId']
					)) . "'>修改主表</a>";
			$v['_edit'] .= " <a href='" . $this->_url('RemoveRuku', array('id' => $v['sonId']
					)) . "'>删除子表</a>";
			$v['_edit'] .= " <a href='" . $this->_url('RemoveRukuMain', array('id' => $v['mainId']
					)) . "'>删除主表</a>";
		}
		$smarty->assign('arr_field_value1', $rowset1);

		$smarty->assign('arr_field_info2', array('mainId' => 'mainId',
				'sonId' => 'sonId',
				'chukuDate' => 'chukuDate',
				'supplierId' => 'supplierId',
				'productId' => 'productId',
				'pihao' => 'pihao',
				'kuwei' => 'kuwei',
				'cnt' => 'cnt',
				'money' => 'money',
				'_edit' => '操作'
				));
		$sql = "select 
		x.id as sonId, 
		y.id as mainId,
		y.kuwei,
		y.chukuDate as chukuDate,
		y.supplierId as supplierId,
		x.productId,
		x.pihao,
		x.cnt,
		x.money
		from yuanliao_llck2product x
		left join yuanliao_llck y on x.chukuId=y.id
		";
		$rowset2 = $this->_mRuku->findBySql($sql);
		foreach($rowset2 as &$v) {
			$v['_edit'] = "<a href='" . $this->_url('EditChuku', array('id' => $v['sonId']
					)) . "'>修改子表</a>";
			$v['_edit'] .= " <a href='" . $this->_url('EditChukuMain', array('id' => $v['mainId']
					)) . "'>修改主表</a>";
			$v['_edit'] .= " <a href='" . $this->_url('RemoveChuku', array('id' => $v['sonId']
					)) . "'>删除子表</a>";
			$v['_edit'] .= " <a href='" . $this->_url('RemoveChukuMain', array('id' => $v['mainId']
					)) . "'>删除主表</a>";
		}

		$smarty->assign('arr_field_value2', $rowset2);

		$smarty->assign('arr_field_info3', array('id' => 'id',
				'rukuId' => 'rukuId',
				'chukuId' => 'chukuId',
				'dateFasheng' => 'dateFasheng',
				'supplierId' => 'supplierId', 
				// 'kind'=>'kind',
				'kuwei' => 'kuwei', 
				// d d'type'=>'type',
				'productId' => 'productId',
				'pihao' => 'pihao',
				'cntFasheng' => 'cnt',
				'moneyFasheng' => 'money'
				));
		$sql = "select 
		*
		from yuanliao_kucun";
		$rowset3 = $this->_mRuku->findBySql($sql);
		$smarty->assign('arr_field_value3', $rowset3);
		$smarty->display('test.tpl');
	}

	function actionMakeRuku() {
		// 上月物料1
		$row1 = array('supplierId' => 1,
			'rukuDate' => '2014-03-02',
			'kuwei' => 'A',
			'Products' => array(
				array('productId' => 1,
					'pihao' => 'P1',
					'cnt' => 100,
					'money' => 1000, 
					// 'kuwei'=>'A'
					)
				)
			); 
		// 上月物料2
		$row2 = array('supplierId' => 1,
			'rukuDate' => '2014-03-02',
			'kuwei' => 'A',
			'Products' => array(
				array('productId' => 2,
					'pihao' => 'P1',
					'cnt' => 100,
					'money' => 1000,
					'kuwei' => 'A'
					)
				)
			); 
		// 本月物料1
		$row3 = array('supplierId' => 1,
			'rukuDate' => '2014-04-01',
			'kuwei' => 'A',
			'Products' => array(
				array('productId' => 1,
					'pihao' => 'P1',
					'cnt' => 200,
					'money' => 2000,
					'kuwei' => 'A'
					)
				)
			); 
		// 本月物料1在不同日期的入库
		$row4 = array('supplierId' => 1,
			'rukuDate' => '2014-04-02',
			'kuwei' => 'A',
			'Products' => array(
				array('productId' => 1,
					'pihao' => 'P1',
					'cnt' => 300,
					'money' => 3000,
					'kuwei' => 'A'
					)
				)
			); 
		// 多条检录
		$row5 = array('supplierId' => 1,
			'rukuDate' => '2014-04-02',
			'kuwei' => 'A',
			'Products' => array(
				array('productId' => 1,
					'pihao' => 'P1',
					'cnt' => 100,
					'money' => 1000,
					'kuwei' => 'A'
					),
				array('productId' => 2,
					'pihao' => 'P2',
					'cnt' => 200,
					'money' => 2000,
					'kuwei' => 'B'
					)
				)
			);

		$rowset = array($row1, $row2, $row3, $row4); 
		// $rowset = array($row5);
		$this->_mRuku->saveRowset($rowset);
		js_alert(null, 'window.history.go(-1)');
	}
	function actionMakeChuku() {
		// 上月物料1
		$row1 = array('supplierId' => 1,
			'chukuDate' => '2014-03-02',
			'kuwei' => 'A',
			'Products' => array(
				array('productId' => 1,
					'pihao' => 'P1',
					'cnt' => 10,
					'money' => 100,
					'kuwei' => 'A'
					)
				)
			); 
		// 上月物料2
		$row2 = array('supplierId' => 1,
			'chukuDate' => '2014-03-02',
			'kuwei' => 'A',
			'Products' => array(
				array('productId' => 2,
					'pihao' => 'P1',
					'cnt' => 10,
					'money' => 100,
					'kuwei' => 'A'
					)
				)
			); 
		// 本月物料1
		$row3 = array('supplierId' => 1,
			'chukuDate' => '2014-04-01',
			'kuwei' => 'A',
			'Products' => array(
				array('productId' => 1,
					'pihao' => 'P1',
					'cnt' => 20,
					'money' => 200,
					'kuwei' => 'A'
					)
				)
			); 
		// 本月物料1在不同日期的入库
		$row4 = array('supplierId' => 1,
			'chukuDate' => '2014-04-02',
			'kuwei' => 'A',
			'Products' => array(
				array('productId' => 1,
					'pihao' => 'P1',
					'cnt' => 30,
					'money' => 300,
					'kuwei' => 'A'
					)
				)
			);

		$row5 = array('supplierId' => 1,
			'chukuDate' => '2014-04-02',
			'kuwei' => 'A',
			'Products' => array(
				array('productId' => 1,
					'pihao' => 'P1',
					'cnt' => 10,
					'money' => 100,
					'kuwei' => 'A'
					),
				array('productId' => 1,
					'pihao' => 'P1',
					'cnt' => 20,
					'money' => 200,
					'kuwei' => 'A'
					)
				)
			);

		$rowset = array($row1, $row2, $row3, $row4); 
		// $rowset = array($row5);
		$this->_mChuku->saveRowset($rowset);
		js_alert(null, 'window.history.go(-1)');
	} 
	// 修改入库记录
	function actionEditRuku() {
		$row = $this->_mRukuSon->find(array('id' => $_GET['id']));
		$row['cnt'] += 1;
		$row['money'] += 1;
		$this->_mRukuSon->save($row);
		js_alert(null, 'window.history.go(-1)');
	}
	function actionEditRukuMain() {
		$row = $this->_mRuku->find(array('id' => $_GET['id']));
		foreach($row['Products'] as &$v) {
			$v['cnt'] += 11;
			$v['money'] += 11;
		}
		$this->_mRuku->save($row);
		js_alert(null, 'window.history.go(-1)');
	} 
	// 修改出库记录
	function actionEditChuku() {
		$row = $this->_mChukuSon->find(array('id' => $_GET['id']));
		$row['cnt'] += 1;
		$row['money'] += 1;
		$this->_mChukuSon->save($row);
		js_alert(null, 'window.history.go(-1)');
	}
	function actionEditChukuMain() {
		$row = $this->_mChuku->find(array('id' => $_GET['id']));
		foreach($row['Products'] as &$v) {
			$v['cnt'] += 11;
			$v['money'] += 11;
		}
		$this->_mChuku->save($row);
		js_alert(null, 'window.history.go(-1)');
	} 
	// 删除入库记录
	function actionRemoveRuku() {
		$this->_mRukuSon->removeByPkv($_GET['id']);
		js_alert(null, 'window.history.go(-1)');
	}
	function actionRemoveRukuMain() {
		$this->_mRuku->removeByPkv($_GET['id']);
		js_alert(null, 'window.history.go(-1)');
	} 
	// 删除出库记录
	function actionRemoveChuku() {
		$this->_mChukuSon->removeByPkv($_GET['id']);
		js_alert(null, 'window.history.go(-1)');
	}
	function actionRemoveChukuMain() {
		$this->_mChuku->removeByPkv($_GET['id']);
		js_alert(null, 'window.history.go(-1)');
	}

	function actionTruncateRuku() {
		$sql = "truncate table yuanliao_cgrk";
		$this->_mRuku->execute($sql);

		$sql = "truncate table yuanliao_cgrk2product";
		$this->_mRuku->execute($sql);
		$sql = "truncate table yuanliao_kucun";
		$this->_mRuku->execute($sql);
		echo "成功";
	}

	function actionTruncateChuku() {
		$sql = "truncate table yuanliao_llck";
		$this->_mRuku->execute($sql);

		$sql = "truncate table yuanliao_llck2product";
		$this->_mRuku->execute($sql);
		$sql = "truncate table yuanliao_kucun";
		$this->_mRuku->execute($sql);
		echo "成功";
	}

	function actionTruncateKucun() {
		$sql = "truncate table yuanliao_kucun";
		$this->_mRuku->execute($sql);
		echo "成功";
	}

	function actionRefreshKucunR() {
		$this->_mRukuSon->refreshKucun();
		js_alert(null, 'window.history.go(-1)');
	}

	function actionRefreshKucunC() {
		$this->_mChukuSon->refreshKucun();
		js_alert(null, 'window.history.go(-1)');
	}
	function actionSfc() {
		// 定义日期范围
		$arr = array('dateFrom' => '2014-04-01', 'dateTo' => '2014-04-30'); 
		// 定义搜索条件,以下为简化的搜索条件
		$strCon = " and 1"; 
		// 得到收发存报表的Sql语句,将下面的语句dump出来后,复制到代码中进行二次加工，
		// 因为可能需要对此sql语句进行left join，有太多变化产生，不好封装。
		// 需要传入入库表名和出库表名，还有条件
		$sql = $this->_mRukuSon->getSfcSql(); 
		// 以下sql语句从页面中复制而来,
		$strGroup = "kuwei,supplierId,productId,pihao";
		$sqlUnion = "select {$strGroup},
		sum(cntFasheng) as cntInit,
		sum(moneyFasheng) as moneyInit,
		0 as cntRuku,0 as moneyRuku,0 as cntChuku,0 as moneyChuku
		from `yuanliao_kucun` where dateFasheng<'{$arr['dateFrom']}' 
		 {$strCon} group by {$strGroup} 
		union 
		select {$strGroup},
		0 as cntInit,0 as moneyInit,
		sum(cntFasheng) as cntRuku,
		sum(moneyFasheng) as moneyRuku,
		0 as cntChuku,0 as moneyChuku
		from `yuanliao_kucun` where 
		dateFasheng>='{$arr['dateFrom']}' and dateFasheng<='{$arr['dateTo']}'
		and rukuId>0  {$strCon} group by {$strGroup} 
		union 
		select {$strGroup},
		0 as cntInit,0 as moneyInit,
		0 as cntRuku,
		0 as moneyRuku,
		sum(cntFasheng*-1) as cntChuku,
		sum(moneyFasheng*-1) as moneyChuku 
		from `yuanliao_kucun` where 
		dateFasheng>='{$arr['dateFrom']}' and dateFasheng<='{$arr['dateTo']}'
		and chukuId>0  {$strCon} group by {$strGroup}";
		$sql = "select 
		{$strGroup},
		sum(cntInit) as cntInit,
		sum(moneyInit) as moneyInit,
		sum(cntRuku) as cntRuku,
		sum(moneyRuku) as moneyRuku,
		sum(cntChuku) as cntChuku,
		sum(moneyChuku) as moneyChuku 
		from ({$sqlUnion}) as x
		group by {$strGroup}
		having sum(cntInit)<>0 or sum(moneyInit)<>0 
		or sum(cntRuku)<>0 or sum(moneyRuku)<>0
		or sum(cntChuku)<>0 or sum(moneyChuku)<>0"; 
		// dump($sql);
		// 分页
		// 总计
		// 显示
	}
}

?>