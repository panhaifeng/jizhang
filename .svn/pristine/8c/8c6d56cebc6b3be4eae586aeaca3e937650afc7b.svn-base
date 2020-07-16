<?php
FLEA::loadClass('TMIS_Controller');
class Controller_Jichu_Jiagonghu extends Tmis_Controller {
	var $_modelExample;
	var $title = "加工户资料";
	var $funcId = 26;
	var $_tplEdit='Jichu/JiagonghuEdit.tpl';
	function __construct() {
		$this->_modelExample = & FLEA::getSingleton('Model_Jichu_Jiagonghu');
		// $this->_modelGongxu = & FLEA::getSingleton('Model_Jichu_Gongxu');
		$this->fldMain = array(
        	'id'=>array('title'=>'','type'=>'hidden','value'=>''),
        	'compCode'=>array('title'=>'加工户编号','type'=>'text','value'=>''),
        	'compName'=>array('title'=>'加工户名称','type'=>'text','value'=>''),
//         	'gongxuId'=>array('title'=>'加工户类型','type'=>'select','value'=>'','options'=>$this->_modelGongxu->getOptions()),
        	'people'=>array('title'=>'联系人','type'=>'text','value'=>''),
        	'isStop'=>array('title'=>'停止往来','type'=>'select','value'=>'','options'=>array(
        			array('text'=>'否','value'=>0),
        			array('text'=>'是','value'=>1)
        		)),
        	'address'=>array('title'=>'地址','type'=>'text','value'=>''),
        	'tel'=>array('title'=>'电话','type'=>'text','value'=>''),
        	'memo'=>array('title'=>'备注','type'=>'textarea','value'=>''),
			'kind'=>array('title'=>'类型','type'=>'select','value'=>'','options'=>array(
					array('text'=>'外加工','value'=>0),
					array('text'=>'本厂','value'=>1)
			)),
        );

        $this->rules = array(
			'compCode'=>'required repeat',
			'compName'=>'required'
		);
	}

	function actionRight() {
	//dump($_GET['kind']);exit;
		$title = '加工户档案';
		///////////////////////////////模板文件
		$tpl = 'TableList.tpl';

		$hasZhizao = & FLEA::getAppInf('hasZhizao');
		//dump($hasZhizao);
		///////////////////////////////表头
		$arr_field_info = array(
			'_edit'=>'操作',
			"compCode" =>array('text'=>"编码",'align'=>'left'),
			"compName" =>"名称",
			// "Gongxu.itemName"=>'加工户类型',
			'people'=>'联系人',
			"tel" =>"电话",
			"address" =>"地址",
			'memo'=>'备注',
			'paixu'=>'排序'
		);

		///////////////////////////////模块定义
		//$this->authCheck('6-5');
		FLEA::loadClass('TMIS_Pager');
		$arr = TMIS_Pager::getParamArray(array(
			'key'=>''
		));
		// $condition[]=array('isSupplier',0,'=');
		if($arr['key']!='') {
			$condition[] = array('compCode',"%{$arr['key']}%",'like','or');
			$condition[] = array('compName',"%{$arr['key']}%",'like');
		}
		$pager =& new TMIS_Pager($this->_modelExample,$condition,'paixu');
		$rowset =$pager->findAll();
		//dump($rowset);exit;
		if(count($rowset)>0) foreach($rowset as & $v) {
			///////////////////////////////
			//$this->makeEditable($v,'memoCode');
			    $this->makeEditable($v, 'paixu');
				$v['_edit'] = $this->getEditHtml($v['id']) . '&nbsp;&nbsp;' . $this->getRemoveHtml($v['id']);
				if($v['isStop']==1)$v['_bgColor']="#cce8f8";
			}
		$smarty = & $this->_getView();
		$smarty->assign('title', $title);
		$smarty->assign('arr_field_info',$arr_field_info);
		$smarty->assign('arr_field_value',$rowset);
		$smarty->assign('arr_condition', $arr);
		//$smarty->assign('hasZhizao',$hasZhizao);
		$smarty->assign('arr_js_css',$this->makeArrayJsCss(array('calendar')));
		$smarty->assign('page_info',$pager->getNavBar($this->_url($_GET['action'],$arr))."<font color='blue'>蓝色表示停止往来</font>");
		$smarty->display($tpl);
	}


    /**
     * @desc 排序设置
     * @author pan
     * @date 2019/03/13  15:10
     */
    public function actionAjaxEdit()
    {
        $fildName = $_GET['fieldName'];
        $value = $_GET['value'];
        $id = $_GET['id'];
        if ($fildName == 'paixu') {
            $sql = "UPDATE jichu_jiagonghu SET paixu='{$value}' WHERE id=$id";
        }
        $result = $this->_modelExample->execute($sql);
        if ($result) {
            echo json_encode(array('success' => true, 'msg' => '修改成功'));
        } else {
            echo json_encode(array('success' => false, 'msg' => '修改成功'));
        }
    }

	function actionSave() {
		$str1="SELECT count(*) as cnt FROM `jichu_jiagonghu` where id<>'{$_POST['id']}' and (compCode='".$_POST['compCode']."' or compName='".$_POST['compName']."')";//dump($str1);exit;
			$ret=$this->_modelExample->findBySql($str1);
		if($ret[0]['cnt']>0) {
			js_alert('加工户名称或加工户代码重复!',null,$this->_url('Edit',array('id'=>$_POST['id'])));
		}

		//首字母自动获取
		FLEA::loadClass('TMIS_Common');
		$letters=strtoupper(TMIS_Common::getPinyin($_POST['compName']));
		$_POST['letters']=$letters;
		// dump($_POST);exit;
		$id = $this->_modelExample->save($_POST);
		js_alert(null,"window.parent.showMsg('保存成功!')",$this->_url($_POST['fromAction']==''?'add':$_POST['fromAction']));
	}

	// function actionRemove() {
	// 	//如果已使用该加工户，禁止删除
 //    	if($_GET['id']>0){
	//         $url=$this->_url($_GET['fromAction']==''?'right':$_GET['fromAction']);
	//         //判断是否坯纱入库中使用了该产品
	//         $str="select count(*) as cnt from pisha_llck where jiagonghuId='{$_GET['id']}'";
	//         $temp=$this->_modelExample->findBySql($str);
	//         if($temp[0]['cnt']>0){
	//             js_alert('坯纱出库已使用该加工户，禁止删除','',$url);
	//         }
 //     	}
	// 	parent::actionRemove();
	// }

	function actionEdit(){
		$row = $this->_modelExample->find(array('id'=>$_GET['id']));
	    $this->fldMain = $this->getValueFromRow($this->fldMain,$row);
	    // dump($row);dump($this->fldMain);exit;
	    $smarty = & $this->_getView();
	    $smarty->assign('fldMain',$this->fldMain);
	    $smarty->assign('rules',$this->rules);
	    $smarty->assign('title','加工户信息编辑');
	    $smarty->assign('aRow',$row);
	    $smarty->display('Main/A1.tpl');
	}

	function actionAdd($Arr){
		$smarty = & $this->_getView();
	    $smarty->assign('fldMain',$this->fldMain);
	    $smarty->assign('title','加工户信息编辑');
	    $smarty->assign('rules',$this->rules);
	    $smarty->display('Main/A1.tpl');
	}
	function actionPopup() {
		FLEA::loadClass('TMIS_Pager');
		$arr = TMIS_Pager::getParamArray(
				array(
						'key' => '',
				));
		$str = "select * from jichu_jiagonghu where 1 ";
		if($arr['key']) $str.=" and compName like'%{$arr['key']}%'";
		$str .= " order by compName asc"; //dump($str);exit;
		$rowset = $this->_modelExample->findBySql($str);
		$smarty = &$this->_getView();
		$smarty->assign('title', '选择加工户');
		$pk = $this->_modelExample->primaryKey;
		$smarty->assign('pk', $pk);
		$arr_field_info = array(
			"compCode" =>array('text'=>"编码",'align'=>'left'),
			"compName" =>"名称",
			'people'=>'联系人',
			"tel" =>"电话",
			"address" =>"地址",
			'memo'=>'备注'
		);
		$smarty->assign('arr_field_info', $arr_field_info);
		$smarty->assign('arr_field_value', $rowset);
		$smarty->assign('arr_condition', $arr);
		$smarty->assign('add_display', 'none');
		$smarty->display('Popup/MultSel.tpl');
	}

	// 得到加工户自动完成控件的提示信息 2016-01-22 by zhujunjie
	function actionGetJsonByKey() {
		if($_GET['code'])$key = $_GET['code'];
		if(isset($_REQUEST['q']))$key =$_REQUEST['q']?$_REQUEST['q']:'';
		$sql = "select * from jichu_jiagonghu where (
		compName like '%{$key}%'  or compCode like '%{$key}%'
		) order by compName";
		$arr = $this->_modelExample->findBySql($sql);
		$data=array();
		foreach ($arr as & $v) {
		$data[]=array($v['compName'],array('name'=>$v['compName'],'id'=>$v['id']));
		}
		echo json_encode($data);exit;
	}


}
?>