<?php
FLEA::loadClass('TMIS_Controller');
class Controller_Test extends Tmis_Controller {
	var $_modelExample;
	function Controller_Test() {
		$this->_modelExample = &FLEA::getSingleton('Model_Trade_Order');
	} 
	// 测试数据库容易出现的几种错误
	function actionTestSql() {
		$data = array(
			array('text' => '查询时数据表不存在', 'title' => '出错原因：程序员忘记上传或实施人员忘记更新数据信息', 'url' => $this->_url('SqlOfNoData')),
			array('text' => '查询时数据字段不存在', 'title' => '出错原因：程序员忘记上传或实施人员忘记更新数据信息', 'url' => $this->_url('SqlOfNoField')),
			array('text' => 'sql语句出错', 'title' => '出错原因：程序员代码存在问题', 'url' => $this->_url('SqlOfError')),
			array('text' => '保存失败', 'title' => '出错原因：程序员代码存在问题或者客户保存的数据有问题', 'url' => $this->_url('SqlOfSaveError')),
			);

		foreach($data as &$v) {
			echo "<a href='{$v['url']}' title='{$v['title']}'>{$v['text']}</a>" . "<br><br><br>";
		}
	} 
	// 数据库不存在
	function actionSqlOfNoData() {
		FLEA::loadClass('TMIS_Pager');
		$sql = "select * from test_data where 1";
		$pager = &new TMIS_Pager($sql);
		$rowset = $pager->findAll();
	} 
	// 数据库字段不存在
	function actionSqlOfNoField() {
		FLEA::loadClass('TMIS_Pager');
		$sql = "select unit2 from trade_order where id<>''";
		$pager = &new TMIS_Pager($sql);
		$rowset = $pager->findAll();
	} 
	// 数据库语句写的有问题
	function actionSqlOfError() {
		FLEA::loadClass('TMIS_Pager');
		$sql = "select * from trade_order where id<>'' name<>''";
		$pager = &new TMIS_Pager($sql);
		$rowset = $pager->findAll();
	} 
	// 数据库保存时出错
	function actionSqlOfSaveError() {
		$arr = array(
			// 'id'=>'1',
			'orderCode' => null,
			'memo' => null,
			);
		$this->_modelExample->save($arr);
	} 
	// 测试代码容易出现的几种错误
	function actionTestCode() {
		$data = array(
			array('text' => '访问地址不存在', 'title' => '出错原因：程序员地址写错或上传遗漏或程序获取地址出错', 'url' => $this->_url('right')),
			array('text' => '模板地址不存在', 'title' => '出错原因：程序员地址写错或上传遗漏', 'url' => $this->_url('CodeOfTpl')), 
			// array('text'=>'模板加载出现问题','title'=>'出错原因：程序员代码存在问题或者出现异常数据','url'=>$this->_url('CodeOfSmarty')),
			);

		foreach($data as &$v) {
			echo "<a href='{$v['url']}' title='{$v['title']}'>{$v['text']}</a>" . "<br><br><br>";
		}
	} 
	// 模板地址不存在
	function actionCodeOfTpl() {
		$tpl = 'Test/TestEdit.tpl';
		$smarty = &$this->_getView();
		$smarty->display($tpl);
	} 
	// 访问模板出错
	function actionCodeOfSmarty() {
		$tpl = 'CommonEdit.tpl';
		$smarty = &$this->_getView();
		$smarty->display($tpl);
	}
}

?>