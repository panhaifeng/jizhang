<?php
class TMIS_Controller extends FLEA_Controller_Action {
/**
*代表模块代码的变量
*默认为0,表示不需要进行权限判断
 */
	var $funcId=0;
	var $_modelExample;
	//显示左右分割的iframe框架
	function actionIndex() {
		$smarty = & $this->_getView();
		$smarty->assign('arr_left_list', $this->arrLeftHref);
		//$smarty->assign('title', $this->rightCaption);
		$smarty->assign('caption', $this->leftCaption);
		$smarty->assign('controller', $this->_controllerName);
		$smarty->assign('action', 'right');
		$smarty->display('MainContent.tpl');
	}

	#根据主键删除,并返回到action=right
	function actionRemove() {
		$from = $_GET['fromAction']?$_GET['fromAction']:$_GET['from'];
		//dump($from);die;
		//dump($_GET['id']);die;
		if ($this->_modelExample->removeByPkv($_GET['id'])) {
			if($from=='') redirect($this->_url("right"));
			else js_alert(null,"window.parent.showMsg('成功删除')",$this->_url($from));
		}
		else js_alert('出错，不允许删除!',$this->_url($from));

	}
	//利用ajax删除订单明细，在订单编辑界面中使用,需要定义subModel
	function actionRemoveByAjax() {
		$m = $this->_subModel;
		if($m->removeByPkv($_REQUEST['id'])) {
			echo json_encode(array('success'=>true));
			exit;
		}
	}

	//新增
	function actionAdd() {
		$this->_edit(array());
	}
	//修改
	function actionEdit() {
		$aRow=$this->_modelExample->find($_GET[id]);
		// dump($aRow);exit;
		$this->_edit($aRow);
	}

	function _edit($arr) {
		$smarty = & $this->_getView();
		$smarty->assign('aRow',$arr);
		$smarty->display($this->_tplEdit);
	}
	//保存
	function actionSave() {
		//dump($_POST);die;
		$id = $this->_modelExample->save($_POST);
		redirect($this->_url($_POST['Submit']=='保存并新增下一个' ? 'add':"Right"));
	}

	//调用通用的编辑模板进行编辑
	function actionCommonEdit() {
		if($_POST['modelName']) {
			$m = & FLEA::getSingleton($_GET['modelName']);
		} else $m= $this->_modelExample;

		$arrF = $this->_editField();
		$aRow=$m->find($_GET[id]);
		$tpl = '_edit.tpl';
		$smarty = & $this->_getView();
		$smarty->assign('field',$arrF);
		$smarty->assign('aRow',$aRow);
		$smarty->display($tpl);
	}
	//修改保存
	function actionCommonSave() {
		if($_POST['modelName']) {
			$m = & FLEA::getSingleton($_GET['modelName']);
		} else $m= $this->_modelExample;
		$m->update($_POST);
		js_alert(null,'window.parent?(window.parent.location.href=window.parent.location.href):(window.location.href="'.$this->_url('right').'")');
	}
	/**
	 *get the arr data from $pager(TMIS_pager),  and conver to the json data which can be used in extjs datgrid
	 */
	function getJsonDataOfExt(& $pager) {
		$rowset = $pager->findAll();
		return '{"totalCount": ' . $pager->totalCount . ',"rows": ' . json_encode($rowset) . '}';
	}

	/**
	 *根据纪录总数和纪录数组构造Ext中的records数据格式
	 */
	function buildExtRecords($cnt,& $arr) {
		return '{"totalCount": ' . $cnt . ',"rows": ' . json_encode($arr) . '}';
	}

	function authCheck($funcId = 0,$isReturn=false) {
		$warning = "<div align=center style='font-size:12px; color=#cc0000'><img src='Resource/Image/warning.gif' style='vertical-align:middle;'>&nbsp&nbsp您没有登录或没有当前模块访问权限!</img></div>";
		if ($funcId === 0) {//检查是否登录
			if($_SESSION['USERID']>0) return true;
			if ($isReturn) return false;
			$_SESSION['TARGETURL'] = $_SERVER[REQUEST_URI];
			if ($isReturn) return false;
			redirect(url('Login','logout'));
			exit;
		}

		//处理$funcId>0的情况
		if(empty($_SESSION['USERID'])) {//没有登录,显示登录界面
		//保存当前地址到session
			$_SESSION['TARGETURL'] = $_SERVER[REQUEST_URI];
			if ($isReturn) return false;
			redirect(url('Login','logout'));
			exit;
		}

		$mUser = FLEA::getSingleton('Model_Acm_User');
		$user = $mUser->find($_SESSION[USERID]);//dump($user);exit;
		if($user[userName]=='admin') {//管理员直接跳过
			return true;
		}

        /*$mFunc = FLEA::getSingleton('Model_Acm_Func');
        if ($funcId == -1) {//从Acm_FuncDb中搜索controller和action匹配的记录
            $sql = "select count(*) cnt,id from acm_funcdb where LOWER(controller)='".strtolower($_GET['controller'])."' and LOWER(action)='".strtolower($_GET['action'])."'";
            $r = mysql_fetch_assoc(mysql_query($sql));
            if($r['cnt']==0) {
                if(!$isReturn) die('没有定义该功能模块！请在模块定义中定义该功能！点击自动增加 [增加]');
                return false;
            }
            $funcId=$r['id'];
        }*/
        
		$userRoles = $mUser->getRoles($_SESSION[USERID]);
		if(count($userRoles)==0) {
			if (!$isReturn) die($warning);
			return false;
		}
		$roles = join(',',array_col_values($userRoles,'id'));
		//各个组是否享有对当前节点的访问权限
		$sql = "select count(*) cnt from acm_func2role where (menuId like '{$funcId}-%' or menuId='{$funcId}') and roleId in({$roles})";
		$_r = $mUser->findBySql($sql);
		if($_r[0]['cnt']==0) {
			if (!$isReturn) die($warning);
			return false;
		}
		return true;
	}

	//显示登陆界面
	function showLogin() {
		$smarty = & $this->_getView();
		$smarty->assign("aProduct",$Arr);
		$pk=$this->_modelExample->primaryKey;
		$primary_key=(isset($_GET[$pk])?$pk:"");
		$smarty->assign("pk",$primary_key);
		$smarty->display('JiChu/ProductEdit.tpl');
	}

	//根据传入的js_func数组,得到需要载入的js文件和css文件列表
	function makeArrayJsCss($js_func=null) {
		$arrCss = array(
			'Main.css',
			'page.css'
		);
		$arrJs = array(
			'jquery.js',
			'jquery.query.js',//可以取得url中的get参数
			'Common.js'
		);
		if(is_string($js_func)) $arr_js_func[] = $js_func;
		else $arr_js_func = & $js_func;
		if (is_array($arr_js_func)) foreach($arr_js_func as & $v) {
				if ($v=='tip') {
					$arrJs[] = 'tip.js';
				}
				if ($v=='calendar') {
					$arrJs[] = 'calendar.js';
				}
				if ($v=='grid') {
					$arrJs[] = 'TmisGrid.js';
				}
				if ($v=='suggest') {
					$arrJs[] = 'TmisSuggest.js';
				}
				if ($v=='thickbox') {
				//$arrJs[] = 'thickbox/thickbox-compressed.js';
					$arrJs[] = 'thickbox/thickbox.js';
					//$arrJs[] = 'jquery.form.js';
					$arrCss[] = 'thickbox.css';
				}
				if ($v=='noRight') {
					$arrJs[] = 'NoRight.js';
				}
			}
		//dump($arrJs);
		return array(
		jsFile=>$arrJs,
		cssFile=>$arrCss
		);
	}

	//可编辑的grid进行修改动作中，控件失去焦点时通过ajax调用.
	//传入id,fieldName,value3个值
	//返回json数据对象{success:true,msg:'成功'}
	//注意$this->_modelExample必须为相应的model.
	function actionAjaxEdit() {
		$row['id'] = $_GET['id'];
		$row[$_GET['fieldName']] = $_GET['value'];
		if ($this->_modelExample->update($row)) {
			$arr = array('success'=>true,'msg'=>'成功!');
		//echo "{'success':true,'msg':'成功!'}";
		} else {
			$arr = array('success'=>false,'msg'=>'出错!');
		//echo "{'success':false,'msg':'出错!'}";
		}
		echo json_encode($arr);
		exit;
	}

	/*********************************************************************\
	*  Copyright (c) 1998-2013, TH. All Rights Reserved.
	*  Author :li
	*  FName  :Controller.php
	*  Time   :2014/05/13 17:00:30
	*  Remark :用于有效性验证，验证重复问题
	\*********************************************************************/
	function actionRepeat(){
		if($_GET['field']=='' || $_GET['fieldValue']==''){
			exit;
		}
		//查找是否存在
		$con=array();
		$con[]=array($_GET['field'],$_GET['fieldValue'],'=');
		if($_GET['id']>0)$con[]=array('id',$_GET['id'],'<>');
		$temp=$this->_modelExample->findAll($con);
		$success = count($temp)>0?false:true;

		echo json_encode(array('success'=>$success));
	}

	//将普通显示的字段以可编辑的形式显示出来
	function makeEditable(& $arr,$fieldName) {
		$title = $arr[$fieldName]=='' ? '无' : $arr[$fieldName] ;
		$arr[$fieldName] = '<span onclick="gridEdit(this,\''.$fieldName.'\','.$arr[id].')" title="点击修改" onmouseover="this.style.cssText = \'background: #278296;\'" onmouseout="this.style.cssText = \'background:#efefef\'" style="background:#efefef">'.$title.'</span>';
	}

	function getEditHtml($pkv,$action='Edit') {
        if(!is_array($pkv)) return "<a href='".$this->_url($action,array(id=>$pkv,'fromAction'=>$_GET['action']))."'>修改</a>";
		if(!$pkv['fromAction']) $pkv['fromAction']= $_GET['action'];
        return "<a href='".$this->_url($action,$pkv)."'>修改</a>";
    }

    //返回"删除"操作按钮
    function getRemoveHtml($pkv,$action='Remove') {
        if(!is_array($pkv)) return "<a href='".$this->_url($action,array(
			'id'=>$pkv,
			'from'=>$_GET['action'],
			'fromAction'=>$_GET['action']
		))."' onclick='return confirm(\"您确认要删除吗?\")'>删除</a>";
		if(!$pkv['text']) $pkv['text']='删除';
		if(!$pkv['msg']) $pkv['msg']='您确认要删除吗';
		if(!$pkv['fromAction']) $pkv['fromAction']=$_GET['action'];
        return "<a href='".$this->_url($action,$pkv)."' onclick='return confirm(\"{$pkv['msg']}?\")'>{$pkv['text']}</a>";
    }

	#清空搜索条件
	function clearCondition() {
		FLEA::loadClass('TMIS_Pager');
		TMIS_Pager::clearCondition();
	}


	#对rowset的某几个字段进行合计,
	#firstField表示需要显示为"合计"字样的字段
	#返回合计行的数据
	function getHeji(&$rowset,$arrField,$firstField='') {
		$str = "\$newRow[\"" . join('"]["',explode('.',$firstField)) . '"]="<b>合计</b>";';
		eval($str);
		foreach($rowset as & $v) {
			foreach($arrField as & $f) {
				$newRow[$f] += $v[$f];
				$newRow[$f] = $newRow[$f];
			}
		}
		$newRow['_bgColor']='#F2F4F6';
		$newRow['mark']='heji';
		return $newRow;
	}


	//根据配置文件中的khqcxs变量判断是否优先显示客户助记码
	//key可以是clientId或者compName
	function changeClient($key) {
		$kg = & FLEA::getAppInf('khqcxs');
		$m = & FLEA::getSingleton('Model_Jichu_Client');
		if(is_numeric($key)) {//如果是clientId
			$client = $m->find(array('id'=>$key));
		} else {
			$client = $m->find(array('compName'=>$key));
		//dump($client);exit;
		}
		if($kg) return $client['compName'];
		if($client['zhujiCode']=='') return $client['compName'];
		//echo $client['zhujiCode'];exit;
		return $client['zhujiCode'];
	}

	//取得系统配置数组
	function getSysSet() {
		FLEA::loadClass('TMIS_Common');
		return TMIS_Common::getSysSet();
	}


	//根据前缀，自动从表中产生$head.yymmddxxx的流水号
	function _getNewCode($head,$tblName,$fieldName) {
		$m = & FLEA::getSingleton('Model_Acm_User');
		$sql = "select {$fieldName} from {$tblName} where {$fieldName} like '{$head}_________' order by {$fieldName} desc limit 0,1";

		$_r = $m->findBySql($sql);
		$row = $_r[0];

		$init = $head .date('ymd').'001';
		if(empty($row[$fieldName])) return $init;
		if($init>$row[$fieldName]) return $init;

		//自增1
		$max = substr($row[$fieldName],-3);
		$pre = substr($row[$fieldName],0,-3);
		return $pre .substr(1001+$max,1);
	}


	//显示产品：不能厂名字不能，纺织行业为花型颜色，
	function getManuCodeName() {
		$sys=$this->getSysSet();
		return $sys['Product_Memo']==''?'产品描述':$sys['Product_Memo'];
	}

	//下载附件
	function getFile($file_add){
		if($file_add!=''){
				$fn=iconv('utf-8',"gb2312",$file_add);
				$index=strpos($fn,' ');
				$name=substr($fn,$index);//处理文件名
				//dump($name);exit;
				$file = $fn;
				header('Content-Description: File Transfer');
				header('Content-Type: application/octet-stream');
				header('Content-Disposition: attachment; filename="'.$name.'"');
				header('Content-Transfer-Encoding: binary');
				header('Expires: 0');
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Pragma: public');
				header('Content-Length: ' . filesize($file));
				readfile($file);
			}
	}

	//使用通用编辑模板进行修改前，需要用数据库中的记录覆盖表中的value字段
	//返回的标签中增加了value属性
	function getValueFromRow($fldMain,$row) {
		foreach($fldMain as $key=>&$v) {
			$v['value'] = $row[$key];
		}
		return $fldMain;
	}

	/**
	 * 有时数组中存在null，会导致保存时报错，这个函数自动将所有null改为空格
	 */
	function notNull($row) {
		if($row===null) {
			return $row.'';
		}
		if(!is_array($row) && $row!==null) return $row;
		if(is_array($row)) {
			foreach($row as & $v) $v=$this->notNull($v);
		}
		return $row;
	}


	/**
	 * 对sql语句进行处理，形成可以得到总计的sql语句 
	 * 对传入的Sql进行处理,将select 和 from之间使用 sum(字段1),sum(字段2)进行替换，
	 * affField必须为以下格式：array('cnt(索引1)'=>'x.cnt(字段1)','money(索引2)'=>'x.money(字段2)')
	 * sql中暂时不能有group by,2014-5-16 由 小刘发现并提出
	 */
	function getZongji($sql,$arrField) {
		//去掉回车
		$sql1 = strtolower(str_replace(PHP_EOL, ' ', $sql));
		//去掉tab
		$sql1 = str_replace('	',' ',$sql1);
		if(strtolower(substr($sql1,0,7))!='select ') {
		    echo "sql语句必须以select开头!";
		    exit;
		}
		if(strpos(strtolower($sql1)," from ")===false) {
		    echo "sql语句中必须有from!";
		    exit;
		}
		$indexS = strpos($sql1, 'select');
		$indexF = strpos($sql1, ' from ');

		$s = substr($sql1,0+strlen($index),$indexS);
		foreach($arrField as $k=>&$v) {
			$sum[] = "sum({$v}) as {$k}";
		}
		$sum = join(',',$sum);
		$f = substr($sql1,$indexF);
		$sql = "select {$sum} {$f}";

		$rows = $this->_modelExample->findBySql($sql);
		$ret = $rows[0];
		$ret['sql'] = $sql;
		return $ret; 
	}

	/**
	 * 使用xml表格模板导出为excel文件
	 * xml:需要使用的xml表格文件，必须在模板目录中
	 * arr:要传递的模板变量
	 */
	function exportWithXml($xml,$arr) {
		$fieldName = time();
		header("Content-type:application/vnd.ms-excel");
		header("Content-Disposition:attachment;filename={$fieldName}.xls");
		$smarty = & $this->_getView();
		foreach($arr as $k=>&$v) {
			$smarty->assign($k,$v);
		}
		$smarty->display($xml);
	}

	  //PHP stdClass Object转array
		function object_array($array) {
			if(is_object($array)) {
				$array = (array)$array;
			 } if(is_array($array)) {
			 	 foreach($array as $key=>$value) {
			 	 	 $array[$key] = $this->object_array($value);
			 	 	 }
			 }
			 return $array;
		}

	/**
	 * 预留接口，在显示add模板前需要执行的内容
	 * 主要在需要部分修改模板变量时用到。
	 */
	function _beforeDisplayAdd(& $s) {
		// $s = 'aa';
	}

	/**
	 * 预留接口，在显示edit模板前需要执行的内容
	 * 主要在需要部分修改模板变量时用到。
	 */
	function _beforeDisplayEdit(&$s) {

	}

	/**
	 * 预留接口，在显示right模板前需要执行的内容
	 * 主要在需要部分修改模板变量时用到。
	 */
	function _beforeDisplayRight(&$s) {

	}
	function _autoCode($head,$next,$tblName,$fieldName){
		$m = & FLEA::getSingleton('Model_Acm_User');
		$sql = "select {$fieldName} from {$tblName} where {$fieldName} like '{$head}{$next}____' order by {$fieldName} desc limit 0,1";
	
		$_r = $m->findBySql($sql);
		$row = $_r[0];
	
		$init = $head.$next.'0001';
		if(empty($row[$fieldName])) return $init;
		if($init>$row[$fieldName]) return $init;
		// dump($init);exit;
		//自增1
		$max = substr($row[$fieldName],-4);
		$pre = substr($row[$fieldName],0,-4);
		return $pre .substr(10001+$max,1);
	}

	/**
 * @desc  图片上传
 * @author guomin
 * @date 2018/6/8  15:10
 * @param int $max_size 最大容量 M
 * @param string $path 保存路径
 * @param  int $h_default 缩略图默认高度
 * @param string $inputName 图片上传输入框名称
 * @return array
 */
		protected function AddPics($max_size, $path, $h_default, $inputName)
		{
		   if ($_FILES[$inputName]['name'] != '') {
		      $max_size2 = $max_size * 1024 * 1024;
		      if ($_FILES['path']['size'] > $max_size2) {
		         return array('success' => false, 'msg' => "附件上传失败，请上传小于{$max_size}M的附件");
		      }
		      //定义大图和小图的尺寸
		      $size = GetImageSize($_FILES[$inputName]['tmp_name']);

		      if ($size[1] > $h_default) {
		         $width = $h_default / $size[1] * $size[0];
		         $height = $h_default;
		      } else {
		         $width = $size[0];
		         $height = $size[1];
		      }
		      $size = array(
		         'small' => array($width, $height),
		         'big' => array($size[0], $size[1])
		      );
		      FLEA::loadClass('FLEA_Helper_Image');
		      $ext = pathinfo($_FILES[$inputName]['name'], PATHINFO_EXTENSION);
		      $bfileName = $path . 'b' . date('ymdHis') . '.' . $ext;//创建大图文件名和路径
		      $sfileName = $path . 's' . date('ymdHis') . '.' . $ext; //创建小图文件名和路径
		      $img =& FLEA_Helper_Image::createFromFile($_FILES[$inputName]['tmp_name'], $ext);//不能使用getSingleton创建对象，必须有图像实体
		      $img->resampled($size['big'][0], $size['big'][1]);

		      $img->saveAsJpeg($bfileName, 100);

		      $img->resize($size['small'][0], $size['small'][1]);
		      $img->saveAsJpeg($sfileName, 100);
		      $ret = array($bfileName, $sfileName);
		   }
		   return $ret;
		}
}
?>
