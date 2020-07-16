<?php
//外协通知单控制器
FLEA::loadClass('Tmis_Controller');
class Controller_Shengchan_Waixie_JiaGongFei extends Tmis_Controller{
    var $title ;
	var $fldMain;
	var $rules; //表单元素的验证规则 

    function __construct() {
		$this->_modelDefault = &FLEA::getSingleton('Model_Shengchan_Waixie');
		$this->_modelExample = &FLEA::getSingleton('Model_Shengchan_Waixie');
		//$this->jichu_employ= &FLEA::getSingleton('Model_Jichu_Employ');
		// 定义模板中的主表字段
		 $this->fldMain = array(
			'waixie2proId' => array(
				'title' => '外协单号', 
				'type' => 'popup', 
				'value' => '',
				'name'=>'waixie2proId',
				'text'=>'请选择',
				'url'=>url('Shengchan_Waixie_JiaGongFei','PopupOnWaixieCode'),
				//'jsTpl'=>'Caiwu/Ys/jsGuozhang.tpl',//需要载入的模板文件，这个文件中不需要使用{literal},因为是通过file_get_content获得的
				//'onSelFunc'=>'onSelChuku',//选中后需要执行的回调函数名,需要在jsTpl中书写
				'textFld'=>'waixieCode',//显示在text中的字段
				'hiddenFld'=>'id',//显示在hidden控件中的字段
			),
			'proName' => array('title' => '品名', 'type' => 'text', 'value' => '','readonly'=>true),
			'guige' => array('title' => '规格', 'type' => 'text', 'value' => '','readonly'=>true),
			'jiagonghu' => array('title' => '加工户', 'type' => 'text', 'value' => '', 'readonly'=>true),
			'cnt' => array('title' => '数量', 'type' => 'text', 'value' => '','readonly'=>true),
			'danjia' => array('title' => '单价', 'type' => 'text', 'value' => '','addonEnd'=>'元'),
			'money' => array('title' => '金额', 'type' => 'text', 'value' => '','addonEnd'=>'元','readonly'=>true),
			
		);

		// 表单元素的验证规则定义
		$this->rules = array(
			'waixieCode' => 'required',
			'danjia' => 'required',
			'money' => 'required number'
		);
	}

	function actionSave(){
        //dump($_POST);exit;
        $waixie2pro= &FLEA::getSingleton('Model_Shengchan_Waixie2Product');
        $arr=array('id'=>$_POST['waixie2proId'],'danjia'=>$_POST['danjia'],'money'=>$_POST['money']);
        $res=$waixie2pro->save($arr);
        if ($res) {
			if ($_POST['Submit'] == '保存并新增下一个') {
				js_alert('保存成功！', '', $this->_url('Add'));
			}else {
				js_alert('保存成功！', '', $this->_url('right'));
			}
		}else die('保存失败!');
	}

	function actionRight(){
        FLEA::loadClass('TMIS_Pager'); 
		// /构造搜索区域的搜索类型
		$serachArea = TMIS_Pager::getParamArray(array('dateFrom' => date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-30, date("Y"))),
				'dateTo' => date("Y-m-d"), 
				// 'supplierId' => '',
				'jiagonghuId' => '',
				'orderCode' => '',
				'key' => '',
				));

		$str = "select 
		             x.waixieCode,
		             x.waixieDate,
		             x.jiagonghuId,
		             y.compName,
		             b.proName,
		             b.guige,
		             b.cntSend,
                     b.danjia,
                     b.money,
                     b.waixieId,
                     b.id
		             from shengchan_waixie x
		             left join jichu_jiagonghu y on y.id=x.jiagonghuId
		             left join shengchan_waixie2product b on b.waixieId=x.id
		             where b.danjia>0";

		$str .= " and waixieDate >= '$serachArea[dateFrom]' and waixieDate<='{$serachArea[dateTo]}'";
		if ($serachArea['key'] != '') $str .= " and (b.guige like '%{$serachArea[key]}%' or 
			                                         b.proName like '%{$serachArea[key]}%')";
        if ($serachArea['jiagonghuId'] != '') $str .= " and x.jiagonghuId='{$serachArea[jiagonghuId]}'";
        if ($serachArea['orderCode'] != '') $str .= " and x.waixieCode='{$serachArea[orderCode]}'";

		$str .= " order by waixieDate desc, waixieCode desc"; 
		//dump($str);exit;
		$pager = &new TMIS_Pager($str);
		$rowset = $pager->findAll(); 
        //dump($rowset);exit;
        if(count($rowset)>0)foreach ($rowset as $key => &$value) {
        	//id为shengchan_waixie2product的主键
        	$value['_edit'] = $this->getEditHtml($value['id']);
			//$value['_edit'] .= " ".$this->getRemoveHtml($value['id']);
			$value['_edit'] .= " <a href='".$this->_url('Remove',array('id'=>$value['id']))."' onclick=\"return confirm('确认删除吗?')\">删除</a>";
        }
        
        // 合计行
		$heji = $this->getHeji($rowset, array('cntSend','money'), '_edit');
		$rowset[] = $heji; 

		// 标题栏信息
		$arrFieldInfo = array(
			'_edit'=>'操作',
			"waixieDate" => "外协日期",
			'waixieCode' => '外协编号', 
			"compName" =>"加工户",
			'proName' => '品名',
			'guige' => '规格',
			"cntSend" =>'发出数量',
			'danjia'=>'单价',
            'money'=>'金额',
			// "orderMemo" =>'订单备注',
			// "memo" =>'产品备注'
			);
        
		$smarty = &$this->_getView();
		$smarty->assign('title', '产量查询');
		$smarty->assign('arr_field_info', $arrFieldInfo);
		$smarty->assign('add_display', 'none');
		$smarty->assign('arr_condition', $serachArea);
		$smarty->assign('arr_field_value', $rowset);
		
         //得到总计
		$sql = "select sum(a.cntSend) as cnt , sum(a.money) as money from shengchan_waixie2product a
		        left join shengchan_waixie b on a.waixieId=b.id
		where danjia>0 and b.waixieDate >= '$serachArea[dateFrom]' and b.waixieDate<='{$serachArea[dateTo]}'";
		if ($serachArea['key'] != '') $sql .= " and (a.guige like '%{$serachArea[key]}%' or 
			                                         a.proName like '%{$serachArea[key]}%')";
        
        if ($serachArea['orderCode'] != '') $sql .= " and b.waixieCode ='{$serachArea[orderCode]}'";
		//dump($sql);exit;
		$zongji = $this->_modelExample->findBySql($sql);
		$zongji = $zongji[0];
		$smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action']), $serachArea)."<font color='red'>发出数量总计:{$zongji['cnt']};总金额:{$zongji['money']}</font>");

		$smarty->assign('arr_js_css', $this->makeArrayJsCss(array('grid', 'calendar')));
		$smarty->display('TableList.tpl');
	}
    
	function actionRemove() {
		//dump($_GET);die;
		$pk=$this->_modelDefault->primaryKey;
		$sql="update shengchan_waixie2product set danjia=0 where id={$_GET['id']}";
		mysql_query($sql);
		redirect($this->_url('right'));
	}

    function actionAdd(){
    	$smarty = &$this->_getView();
		$smarty->assign('fldMain', $this->fldMain);
		$smarty->assign('rules', $this->rules);
		$smarty->assign('title', '加工费单价设置');
		$smarty->assign('sonTpl', 'Waixie/waixie.tpl');
		$smarty->display('Main/A1.tpl');
    }

    function actionPopupOnWaixieCode(){
        FLEA::loadClass('TMIS_Pager'); 
        // /构造搜索区域的搜索类型
		$serachArea = TMIS_Pager::getParamArray(array('dateFrom' => date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-30, date("Y"))),
				'dateTo' => date("Y-m-d"),
				//'jiagonghuId' => '',//要增加
				//'traderId' => '',
				'orderCode' => '',
				'key' => '',
				)); 

		$str="select 
		             x.waixieCode,
		             x.waixieDate,
		             y.compName,
		             b.proName,
		             b.guige,
		             b.cntSend,
		             b.id
		             from shengchan_waixie x
		             left join jichu_jiagonghu y on y.id=x.jiagonghuId
		             left join shengchan_waixie2product b on b.waixieId=x.id
		             where b.danjia=0";

		$str .= " and waixieDate >= '$serachArea[dateFrom]' and waixieDate<='$serachArea[dateTo]'";
		if ($serachArea['key'] != '') $str .= " and (b.proName like '%$serachArea[key]%'
						or b.proName like '%$serachArea[key]%'
						or b.guige like '%$serachArea[key]%')";
		if ($serachArea['orderCode'] != '') $str .= " and x.waixieCode like '%$serachArea[orderCode]%'";
		$str .= " order by waixieDate desc, waixieCode desc"; 
		// dump($str);exit;
		$pager = &new TMIS_Pager($str);
		$rowset = $pager->findAll();
		//dump($rowset);exit;
        if (count($rowset) > 0) foreach($rowset as $i => &$v) {
			$v['cntSend'] = round($v['cntSend'], 2);
		}
		
		$arrFieldInfo = array(
			"waixieCode" => "外协单号",
			"waixieDate" => "外协日期",
			"compName" => "加工户",
			'proName' => '产品名称',
			'guige' => '规格', 
			'proName' => '品名', 
			// 'unit'=>'单位',
			"cntSend" => '发出数量', 
			// "danjia" =>'单价',
			// "money" =>'金额'
			);


    	$smarty = &$this->_getView();
		$smarty->assign('title', '选择客户');
		$pk = $this->_modelExample->primaryKey;
		$smarty->assign('pk', $pk);
		$smarty->assign('add_display', 'none');
		$smarty->assign('arr_field_info', $arrFieldInfo);
		$smarty->assign('arr_field_value', $rowset);
		$smarty->assign('arr_js_css', $this->makeArrayJsCss(array('thickbox')));
		$smarty->assign('arr_condition', $serachArea);
		$smarty->assign('page_info', $pager->getNavBar($this->_url('PopupOnWaixieCode', $serachArea)));
		$smarty->display('Popup/CommonNew.tpl');
    }

    function actionEdit(){
    	//dump($_GET);exit;
    	$waixie2pro = &FLEA::getSingleton('Model_Shengchan_Waixie2Product');
    	$res=$waixie2pro->findAll(array('id'=>$_GET['id']));
    	//dump($res);exit;
        $this->fldMain['waixie2proId']['value']=$_GET['id'];//waixie2pro的id，标识跟新的记录
        $this->fldMain['waixie2proId']['text']=$res[0]['waixie2pro']['waixieCode'];//显示的外协单号
        $this->fldMain['proName']['value']=$res[0]['proName'];
        $this->fldMain['guige']['value']=$res[0]['guige'];
        $this->fldMain['cnt']['value']=$res[0]['cntSend'];
        $this->fldMain['danjia']['value']=round($res[0]['danjia'],2);
        $this->fldMain['money']['value']=round($res[0]['money'],2);
        //加工户的显示
        $jiagonghu = &FLEA::getSingleton('Model_Jichu_Jiagonghu');
        $str=$jiagonghu->find(array('id'=>$res[0]['waixie2pro']['jiagonghuId']));
        $this->fldMain['jiagonghu']['value']=$str['compName'];

    	$smarty = &$this->_getView();
		$smarty->assign('fldMain', $this->fldMain);
		$smarty->assign('rules', $this->rules);
		$smarty->assign('title', '加工费单价设置');
		$smarty->assign('sonTpl', 'Waixie/waixie.tpl');
		$smarty->display('Main/A1.tpl');
    }

} 

?>