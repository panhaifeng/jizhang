<?php
/*********************************************************************\
*  Copyright (c) 1998-2013, TH. All Rights Reserved.
*  Author :zcc
*  FName  :Yanbugong.php
*  Time   :2016/10/26 15:18:11
*  Remark :验布工档案
\*********************************************************************/
FLEA::loadClass('TMIS_Controller');
class Controller_Jichu_Yanbugong extends Tmis_Controller {
	var $_modelExample;
	var $title = "修布工档案";
	//var $funcId = 8;
	var $_tplEdit='Jichu/yanbuEdit.tpl';
	function Controller_Jichu_Yanbugong() {
		$this->_modelExample = & FLEA::getSingleton('Model_Jichu_Yanbugong');
		$this->fldMain = array(
			// 'traderId' => array('title' => '本厂负责人', "type" => "text", 'value' => ''),
			'userCode' => array('title' => '代码', "type" => "text", 'value'=>''),
			'userName' => array('title' => '姓名', "type" => "text", 'value' => ''),
			'address' => array('title' => '地址', "type" => "text", 'value' => ''),
			// 'banci' => array('title' => '班次', "type" => "select", 'options' =>array(
			// 		array('text'=>'甲','value'=>'0'),
   //      			array('text'=>'乙','value'=>'1'),
   //      			array('text'=>'丙','value'=>'2'),
   //      			array('text'=>'加班','value'=>'3'),
			// 	)),
			'type'=>array('title'=>'类型','type'=>'select','value'=>'','options'=>array(
        			array('text'=>'正式','value'=>'正式'),
        			array('text'=>'试用','value'=>'试用'),
        			array('text'=>'临时','value'=>'临时'),
        			array('text'=>'离职','value'=>'离职'),
        		)),
			'dateEnter'=>array('title'=>'入职日期','type'=>'calendar','value' => date('Y-m-d')),
			'shenfenNo' => array('title' => '身份证号', "type" => "text", 'value' => ''),
			'hetongCode' => array('title' => '工合同号', "type" => "text", 'value' => ''),
			'password' => array('title' => '操作密码', "type" => "password", 'value' => ''),
			'id' => array('type' => 'hidden', 'value' => ''), 
		);

		 $this->rules = array(
			'userCode'=>'required',
			'userName'=>'required'
		);
	}
	function actionAdd(){
		$row = $this->_modelExample->find(array('id'=>$_GET['id']));
        $row['dateEnter']=$row['dateEnter']=='0000-00-00'?'':$row['dateEnter'];
        $row['dateLeave']=$row['dateLeave']=='0000-00-00'?'':$row['dateLeave'];

        $this->fldMain = $this->getValueFromRow($this->fldMain,$row);
        // dump($row);dump($this->fldMain);exit;
        $smarty = & $this->_getView();
        $smarty->assign('fldMain',$this->fldMain);
        $smarty->assign('rules',$this->rules);
        $smarty->assign('title','验布工信息编辑');
        $smarty->assign('aRow',$row);
        $smarty->display('Main/A1.tpl');
	}
	function actionEdit(){
		$row = $this->_modelExample->find(array('id'=>$_GET['id']));
        $row['dateEnter']=$row['dateEnter']=='0000-00-00'?'':$row['dateEnter'];
        $row['dateLeave']=$row['dateLeave']=='0000-00-00'?'':$row['dateLeave'];

        $this->fldMain = $this->getValueFromRow($this->fldMain,$row);
        //dump($row);dump($this->fldMain);exit;
        $smarty = & $this->_getView();
        $smarty->assign('fldMain',$this->fldMain);
        $smarty->assign('rules',$this->rules);
        $smarty->assign('title','验布工信息编辑');
        $smarty->assign('aRow',$row);
        $smarty->display('Main/A1.tpl');
	}

	/**
	 * @desc ：验布工查询
	 * Time：2016/10/26 15:18:20
	 * @author zcc
	*/
	function actionRight() {
		$title = '验布工档案';
		///////////////////////////////模板文件
		$tpl = 'TableList.tpl';
		///////////////////////////////表头
		$arr_field_info = array(
			'_edit'=>'操作',
			"userCode" =>array('text'=>"编码",'align'=>'left'),
			"userName" =>"名称",
			'dateEnter'=>"入职日期",
			"address" =>"地址",
			//'banci'=>'班次',
			'isFire'=>'是否离职',
			'type'=>'类型',
			"shenfenNo" =>"身份证号",
			"hetongCode" =>"用工合同号"
		);

		///////////////////////////////模块定义
		$this->authCheck('6-21');
		FLEA::loadClass('TMIS_Pager');
		$arr = TMIS_Pager::getParamArray(array(
			'key'=>''
		));
		$condition=array();
		if($arr['key']!='') {
			$condition[] = array('userName',"%{$arr['key']}%",'like','or');
			$condition[] = array('userCode',"%{$arr['key']}%",'like');
		}
		$pager =& new TMIS_Pager($this->_modelExample,$condition,'userCode');
		$rowset =$pager->findAll();
		//dump($rowset);exit;
		if(count($rowset)>0) foreach($rowset as & $v) {
			///////////////////////////////
			//$this->makeEditable($v,'memoCode');
				if($v['banci']==0) $v['banci']='甲';
				if($v['banci']==1) $v['banci']='乙';
				if($v['banci']==2) $v['banci']='丙';
				if($v['banci']==3) $v['banci']='加班';

				if($v['isFire']==0) $v['isFire']='在职';
				if($v['isFire']==1) {
					$v['_bgColor'] = 'lightgreen';
					$v['isFire']='离职';
				}

				$v['_edit'] = $this->getEditHtml($v['id']) . '&nbsp;&nbsp;' . $this->getRemoveHtml($v['id']);
				if($v['dateEnter']=='0000-00-00') $v['dateEnter']="&nbsp;";
			}
		$smarty = & $this->_getView();
		$smarty->assign('title', $title);
		$smarty->assign('arr_field_info',$arr_field_info);
		$smarty->assign('arr_field_value',$rowset);
		$smarty->assign('arr_condition', $arr);
		$smarty->assign('page_info',$pager->getNavBar($this->_url($_GET['action'],$arr)));
		$smarty->display($tpl);
	}

	/**
	 * @desc ：保存
	 * Time：2016/10/26 15:18:53
	 * @author zcc
	*/
	function actionSave() {
		// dump($_POST);exit;
		if(empty($_POST['id'])) {
			$sql = "SELECT count(*) as cnt FROM `jichu_yanbu_user` where userCode='".$_POST['userCode']."' or userName='".$_POST['userName']."'";
			$rr = $this->_modelExample->findBySql($sql);
			//dump($rr);exit;
			if($rr[0]['cnt']>0) {
				js_alert('验布工名称或代码重复!',null,$this->_url('add'));
			}
		} else {
		//修改时判断是否重复
			$str1="SELECT count(*) as cnt FROM `jichu_yanbu_user` where id!=".$_POST['id']." and (userCode='".$_POST['userCode']."' or userName='".$_POST['userName']."')";
			$ret=$this->_modelExample->findBySql($str1);
			if($ret[0]['cnt']>0) {
				js_alert('验布工名称或代码重复!',null,$this->_url('Edit',array('id'=>$_POST['id'])));
			}
		}
		//dump($_POST);exit;
		$_POST['isFire']=0;
		if($_POST['type']=="试用") $_POST['paixu']=1;
		if($_POST['type']=="临时") $_POST['paixu']=2;
		if($_POST['type']=="离职"){
			$_POST['paixu']=3;
			$_POST['userCode']=$_POST['userCode']."_LZ";
			$_POST['userName']=$_POST['userName']."_LZ";
		}
		if($_POST['type']=="离职") $_POST['isFire']=1;
		//dump($_POST);exit;
		$id = $this->_modelExample->save($_POST);
		if($_POST['id']=='')
			js_alert(null,"window.parent.showMsg('保存成功!')",$this->_url('add'));
		else
			js_alert(null,"window.parent.showMsg('保存成功!')",$this->_url('right'));
	}
	
}
?>