<?php
/**
 * 实施人员用的后台配置程序，
 *可进行动态密码卡的设置，
 * 可进行功能权限的定义。
 *可查看db_change
 */
FLEA::loadClass('TMIS_Controller');
class Controller_Tool extends Tmis_Controller {
	var $m;
	function Controller_Tool() {
		$this->m = &FLEA::getSingleton('Model_Jichu_Client');
		// echo 1;exit;
	}
	function actionIndex() {
		$smarty = &$this->_getView();
		$smarty->display('Tool/Index.tpl');
	}
	// 利用ajax获得工具栏的操作目录
	function actionGetToolMenu() {
		$menu = array(
			// array('text'=>'开关管理','leaf'=>true,'src'=>'?controller=Tool&action=Kaiguan'),
			array('text' => '动态密码卡管理', 'expanded' => false, 'leaf' => true, 'src' => '?controller=Tool&action=dongtai'),
			array('text' => '设置弹窗信息', 'expanded' => false, 'leaf' => true, 'src' => '?controller=Tool&action=setTanchuang'),
			array('text' => '导入excel', 'expanded' => false, 'leaf' => true, 'src' => '?controller=Tool&action=ImpotExcel'),
			array('text' => '更新件数', 'expanded' => false, 'leaf' => true, 'src' => '?controller=Tool&action=Jian'),
			array('text' => '基础库位表更新', 'expanded' => false, 'leaf' => true, 'src' => '?controller=Tool&action=JichuKuweiReset'),
			array('text' => '生产入库表库位字段更新', 'expanded' => false, 'leaf' => true, 'src' => '?controller=Tool&action=ShengchanRukuReset'),
			array('text' => '生产出库表库位字段更新', 'expanded' => false, 'leaf' => true, 'src' => '?controller=Tool&action=ShengchanChukuReset'),
			array('text' => '生产库存表库位字段更新', 'expanded' => false, 'leaf' => true, 'src' => '?controller=Tool&action=ShengchanKucunReset'),
			// array('text' => '数据补丁，首字母', 'expanded' => false, 'leaf' => true, 'src' => '?controller=Tool&action=building'),
			// array('text'=>'tbllist测试','expanded'=> false,'leaf'=>true,'src'=>'?controller=main&action=test'),
			);
		echo json_encode($menu);
	}

	/**
	*
	* @author pan
	* @return null
	*/
    function actionJichuKuweiReset(){
		 set_time_limit(0);
         ini_set('memory_limit', '2048M');
		 $kuwei = & FLEA::getSingleton('Model_Jichu_Kuwei');	
		 $sql = "update jichu_kuwei set kuweiName='原料金马本厂' where kuweiName='01'";
		 $kuwei->execute($sql);
		 $sql1 = "update jichu_kuwei set kuweiName='成品金马本厂' where kuweiName='02'";
		 $kuwei->execute($sql1);
		 dump('基础库位表更新成功');exit;
    }
	/**
	*
	* @author pan
	* @return null
	*/
    function actionShengchanRukuReset(){
         set_time_limit(0);
         ini_set('memory_limit', '2048M');
		 $ruku = & FLEA::getSingleton('Model_Cangku_Chengpin_Ruku');	
		 $ruku2pro = & FLEA::getSingleton('Model_Cangku_Chengpin_Ruku2Product');
		 $sql = " update cangku_common_ruku set kuwei='原料金马本厂' where kuwei='01'";
	 	 $sql1 = " update cangku_common_ruku set kuwei='成品金马本厂' where kuwei='02'";
	 	 $ruku->execute($sql);
	 	 $ruku->execute($sql1); 
         dump('入库表更新成功');exit;

    }

    //将niuzai_jinma中的成品收发存报表移值到niuzai_jinma_new的成品收发存报表中
    function actionCpKucunJz(){
          set_time_limit(0);
          $kucun = & FLEA::getSingleton('Model_Cangku_Kucun');
          //找出所有的成品库存记录
          $sql = "select * 
                  from cangku_common_kucun1 
                  where 1 and kuwei in ('成品金马本厂','成品老佳程仓库','新佳程整理厂','碧宇染厂','双燕整理厂','丛零整理厂')";
          $arr = $kucun->findBySql($sql);
          foreach ($arr as $key => &$value) {
          	 $sqlNew = "insert into cangku_common_kucun values ('{$value['id']}','{$value['dateFasheng']}','{$value['rukuId']}','{$value['chukuId']}','{$value['kind']}','{$value['kuwei']}','{$value['pihao']}','{$value['ganghao']}','{$value['dengji']}','{$value['supplierId']}','{$value['productId']}','{$value['cntJian']}','{$value['cntFasheng']}','{$value['danjiaFasheng']}','{$value['moneyFasheng']}')";
          	 $kucun->execute($sqlNew);
          }
          echo "更新成功！";
          
    }

    /**
	*
	* @author pan
	* @return null
	*/
    function actionShengchanChukuReset(){
         set_time_limit(0);
         ini_set('memory_limit', '2048M');
		 $chuku = & FLEA::getSingleton('Model_Cangku_Chengpin_Chuku');	
		 $chuku2pro = & FLEA::getSingleton('Model_Cangku_Chengpin_Chuku2Product');
		 $sql = " update cangku_common_chuku set kuwei='原料金马本厂' where kuwei='01'";
	 	 $sql1 = " update cangku_common_chuku set kuwei='成品金马本厂' where kuwei='02'";
	 	 $chuku->execute($sql);
	 	 $chuku->execute($sql1); 
         dump('出库表更新成功');exit;

    }
    
    /**
	*
	* @author pan
	* @return null
	*/
    function actionShengchanKucunReset(){
		 set_time_limit(0);
         ini_set('memory_limit', '2048M');
		 $kucun = & FLEA::getSingleton('Model_Cangku_Kucun');	
		 $sql = "update cangku_common_kucun set kuwei='原料金马本厂' where kuwei='01'";
		 $kucun->execute($sql);
		 $sql1 = "update cangku_common_kucun set kuwei='成品金马本厂' where kuwei='02'";
		 $kucun->execute($sql1);
		 dump('库存表更新成功');exit;
    }

	/**
	 *
	 * @author li
	 * @return null
	 */
	function actionBuilding() {
		FLEA::loadClass('TMIS_Common');
		$m = FLEA::getSingleton('Model_Jichu_Client');
		// /客户的首字母自动填充
		$sql = "select id,compName from yixiang_client where 1";
		$res = $m->findBySql($sql);
		foreach($res as &$v) {
			$letters = strtoupper(TMIS_Common::getPinyin($v['compName']));
			$sql = "update yixiang_client set letters='{$letters}' where id='{$v['id']}'";
			$m->execute($sql);
		}
		// /员工档案的首字母
		$sql = "select id,compName from jichu_jiagonghu where 1";
		$res = $m->findBySql($sql);
		foreach($res as &$v) {
			$letters = strtoupper(TMIS_Common::getPinyin($v['compName']));
			$sql = "update jichu_jiagonghu set letters='{$letters}' where id='{$v['id']}'";
			$m->execute($sql);
		}
		// /加工户的首字母
		$sql = "select id,employName from jichu_employ where 1";
		$res = $m->findBySql($sql);
		foreach($res as &$v) {
			$letters = strtoupper(TMIS_Common::getPinyin($v['employName']));
			$sql = "update jichu_employ set letters='{$letters}' where id='{$v['id']}'";
			$m->execute($sql);
		}
		echo '补丁完成';
		exit;
	}
	// 开关设置
	function actionKaiguan() {
		if (count($_POST) > 0) {
			$ret = array();
			$m = FLEA::getSingleton('Model_Acm_SetParamters');
			foreach($_POST as $k => &$v) {
				if ($k == 'Submit') continue;
				// 找到相关的记录，取得相对应的id
				$sql = "select id from sys_set where item='{$k}'";
				$_rows = $this->m->findBySql($sql);
				$ret[] = array('id' => $_rows[0]['id'],
					'item' => $k,
					'value' => $v
					);
			}
			$m->saveRowset($ret);
			js_alert(null, "window.parent.showMsg('保存成功')", $this->_url('kaiguan'));
		}
		FLEA::loadClass('TMIS_Common');
		$row = TMIS_Common::getSysSet();
		// dump($row);
		$smarty = &$this->_getView();
		$smarty->assign('aRow', $row);
		$smarty->display('Tool/Kaiguan.tpl');
	}
	// 管理动态密码卡
	function actionDongtai() {
		$sql = "select * from acm_sninfo";
		$rowset = $this->m->findBySql($sql);
		$rowset[] = array();
		$smarty = &$this->_getView();
		$smarty->assign('rowset', $rowset);
		$smarty->display('Tool/Dongtai.tpl');
	}
	function actionSaveDongtai() {
		$m = &FLEA::getSingleton('Model_Acm_Sninfo');
		if ($m->save($_POST)) {
			js_alert(null, 'window.parent.showMsg("保存成功")', $this->_url('dongtai'));
		}
	}

	// 导出菜单目录
	function actionExport() {
		echo("<a href='" . $this->_url('View') . "'>导出</a>");
	}
	function actionView() {
		include('Config/menu.php');
		$smarty = &$this->_getView();
		$smarty->assign('row', $_sysMenu);
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/force-download");
		header("Content-Type: application/download");
		header("Content-Disposition: attachment;filename=test.xls");
		header("Content-Transfer-Encoding: binary");
		$smarty->display('Tool/MenuView.tpl');
	}
	// 设置弹窗内容，如果这里设置了，登录成功后，会弹出一个对话框，强制用户观看。
	function actionSetTanchuang() {
		// $this->authCheck('8');
		$sql = "select * from sys_pop";
		$row = mysql_fetch_assoc(mysql_query($sql));
		$tpl = 'Tool/PopEdit.tpl';
		$smarty = &$this->_getView();
		$smarty->assign('aRow', $row);
		$smarty->display($tpl);
	}
	function actionSavePop() {
		$m = &FLEA::getSingleton('Model_Sys_Pop');
		$id = $m->save($_POST);
		js_alert('保存成功,提交的信息将会在用户登录的第一时间弹出显示，客户必须关闭弹窗才可继续操作！', '', $this->_url('SetTanchuang'));
	}
	// 利用ajax取得弹窗的内容
	function actionGetPopByAjax() {
		$d = date('Y-m-d');
		$sql = "select * from sys_pop where dateFrom<='{$d}' and dateTo>='{$d}'";
		// dump($sql);exit;
		$row = mysql_fetch_assoc(mysql_query($sql));
		if (!$row) {
			$arr = array('success' => false
				);
		}else {
			$arr = array('success' => true,
				'data' => $row
				);
		}
		echo json_encode($arr);
	}


	/**
	* ***************************读取excel文件*******************************************
	* 导入excel文档,比如产品资料，客户档案等
	*/
	function actionImpotExcel() {
		if($_POST) {
			// dump($_FILES);exit;
			// $filePath = 'product.xls';
			$filePath = $_FILES['file']['tmp_name'];
			if($error) {
				js_alert('文件上传失败');
			}
			$arr = $this->_readExcel($filePath);
			// dump($arr);exit;
			// 以下为数据处理过程
			$ret = array();
			$arrDup = array();//产品编码重复
			foreach($arr as $k => &$v) {
				//第一行为表头，跳过
				if ($k == 0) continue;
				//空行跳过
				if(trim($v[0])=='') continue;
				//检查是否重复
				$_k = trim($v[0]) . '';
				if($ret[$_k]) {
					$arrDup[] = $_k;
					continue;
				}

				//建立字段映射关系
				$ret[$_k] = array(
					'proCode' => $_k,
					'proName' => trim($v[1]) . '',
					'guige' => trim($v[2]) . '',
					'chengFen' => trim($v[3]) . '',
					'menfu' => trim($v[4]) . '',
					'kezhong' => trim($v[5]) . '',
					'kind'=>'针织',
					'memo'=>'excel资料导入'
				);
			}
			// dump($arrDup);exit;
			$m = & FLEA::getSingleton('Model_Jichu_Product');
			$m->createRowset($ret);
			echo "成功!以下编码有重复:";
			dump($arrDup);
			exit;
		}

		echo '<form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">';
		echo '<input type="file" name="file" id="file"  style="width:100%">';
		echo '<input type="submit" id="Submit" name="Submit" value="保存">';
		echo '</form>';

	}

	// 读取某个excel文件的某个sheet数据，
	function _readExcel($filePath, $sheetIndex = 0) {
		set_time_limit(0);
		include "Lib/PhpExcel/PHPExcel.php";

		$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
		$cacheSettings = array('memoryCacheSize' => '16MB');
		PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

		$PHPExcel = new PHPExcel();
		// 如果是2007,需要$PHPReader = new PHPExcel_Reader_Excel2007();
		$PHPReader = new PHPExcel_Reader_Excel5();
		if (!$PHPReader->canRead($filePath)) {
			echo 'no Excel';
			return ;
		}
		$PHPExcel = $PHPReader->load($filePath);
		/**
		 * *读取excel文件中的第一个工作表
		 */
		$currentSheet = $PHPExcel->getSheet($sheetIndex);
		/**
		 * *取得共有多少列,若不使用此静态方法，获得的$col是文件列的最大的英文大写字母
		 */
		$allColumn = PHPExcel_Cell::columnIndexFromString($currentSheet->getHighestColumn());

		/**
		 * *取得一共有多少行
		 */
		$allRow = $currentSheet->getHighestRow();
		// 输出
		$ret = array();
		for($currow = 1;$currow <= $allRow;$currow++) {
			$_row = array();
			for($curcol = 0;$curcol < $allColumn;$curcol++) {
				$result = $currentSheet->getCellByColumnAndRow($curcol, $currow)->getValue();
				$_row[] = $result;
			}
			$ret[] = $_row;
		}
		return $ret;
	}

	/**
	 * 数据补丁：处理问题： 在应收过账时 取不到出库记录明细中的 单价
	 */
	function actionGetDanjia() {
		FLEA::loadClass('TMIS_Common');
		$m = FLEA::getSingleton('Model_Jichu_Client');
		$sql="select ord2proId from cangku_common_chuku2product where ord2proId<>0";
		$row=$m->findBySql($sql);//dump($row);exit;
        foreach ($row as $key => &$value) {
        	//dump($value['ord2id']);
        	$str="update cangku_common_chuku2product set danjia=(select danjia from trade_order2product where id='{$value['ord2proId']}') where ord2proId='{$value['ord2proId']}'";
        	$m->execute($str);
        }
        dump('跟新成功！');exit;
	}

	/**
	 * 数据补丁：处理问题： 在应收过账查询时 数量为0
	 */
    function actionGetGZcnt(){
    	FLEA::loadClass('TMIS_Common');
    	$m=FLEA::getSingleton('Model_Caiwu_Ys_Guozhang');
		$sql = "SELECT *
FROM `caiwu_ar_guozhang`
WHERE cnt =0
AND guozhangDate >= '2014-07-08'
";
    	//$sql="select chuku2proId from caiwu_ar_guozhang where kind='销售出库'";
    	$row=$m->findBySql($sql);//dump($row);exit;
        foreach ($row as $key => &$value) {
        	// $str="select cnt from cangku_common_chuku2product where id='{$value['chuku2proId']}'";
        	// $res=$m->findBySql($str);//dump($res);exit;
        	$str="update caiwu_ar_guozhang set cnt=(select cntOrg from cangku_common_chuku2product where id='{$value['chuku2proId']}') where id='{$value['id']}' ";
			//dump($str);exit;
            $m->execute($str);
        }
        dump('跟新成功！');exit;
    }

	//by jeff, fixed problem by xue
	function actionZzz() {

		$sql = "SELECT x.id,x.cnt, y.cnt AS cntY
FROM e7_wofeng_test.`caiwu_ar_guozhang` x
LEFT JOIN e7_wofeng.`caiwu_ar_guozhang` y ON x.id = y.id
WHERE x.cnt <> y.cnt";
		$query = mysql_query($sql) or die(mysql_error());
		while ($re = mysql_fetch_assoc($query)){
			$sql = "update caiwu_ar_guozhang set cnt='{$re['cnt']}' where id='{$re['id']}'";
			//dump($sql);exit;
			mysql_query($sql) or die(mysql_error());
		}
		echo 'ok';
	}
	/**
	 * ps ：应收过账新增件数-将件数从出库数据中取过来
	 * Time：2017/03/22 08:31:02
	 * @author Sjj
	 * @param 参数类型
	 * @return 返回值类型
	*/
	function actionCntJian(){
		$this->_modelExample = & FLEA::getSingleton('Model_Caiwu_Ys_Guozhang');
		$sql = "select x.id,y.cntJian
			from caiwu_ar_guozhang x
			left join cangku_common_chuku2product y on x.chuku2proId=y.id
			where 1";
		$rowset = $this->_modelExample->findBySql($sql);
		$res = $this->_modelExample->saveRowset($rowset);
		if($res){
			dump('更新成功！');die;
		}else{
			dump('更新失败！');die;
		}

	}

	//处理坯布发外登记页面没有保存分区字段的数据
	function actionDealFawai(){
		$sql="select * from shengchan_chanliang where 1";
		$modelExam = & FLEA::getSingleton('Model_Shengchan_Waixie_Fawai');
		$rowset=$modelExam->findBySql($sql);
		foreach ($rowset as $key => &$value) {
			$sql1="update cangku_fawai2product set type='{$value['type']}' where chanliangId='{$value['id']}' and chanliangId!=''";
			$modelExam->execute($sql1);
		}
	}
	/**
	 * @desc ：出库的件数应是负数
	 * Time：2017/06/28 13:43:21
	 * @author Baiminghao
	*/
	function actionJian(){
		$sql = "update cangku_common_kucun set cntJian=cntJian*-1 where chukuId>0";
		$res=mysql_fetch_assoc(mysql_query($sql));
		echo "更新成功";
	}

}

?>