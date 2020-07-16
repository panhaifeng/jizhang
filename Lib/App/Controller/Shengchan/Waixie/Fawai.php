<?php
/*********************************************************************\
*  Copyright (c) 1998-2013, TH. All Rights Reserved.
*  Author :li
*  FName  :Fawai.php
*  Time   :2015/04/13 10:20:59
*  Remark :原料发到加工户后整登记入库的记录
*          该工序的入库记录，同时也相当于上一个工序的产量
*          沃丰后整理的产量不会拉回本厂，会直接送到下一个加工户，存在分批送
\*********************************************************************/
FLEA::loadClass('TMIS_Controller');
class Controller_Shengchan_Waixie_Fawai extends TMIS_Controller {

	/**
	 * 主表model实例化
	 * @var class
	*/
	var $_modelExample;

	/**
	 * 子表model实例化
	 * @var class
	*/
	var $_subModel;

	/**
	 * 编辑界面的原型：主信息原型
	 * @var Array
	*/
	var $fldMain;

	/**
	 * 编辑界面的原型：子信息
	 * @var Array
	*/
	var $headSon;

	/**
	 * 编辑界面的原型：有效性验证
	 * @var Array
	*/
	var $rules;

	/**
	 * 构造函数
	*/
	function __construct() {
		//主表model
	    $this->_modelExample = &FLEA::getSingleton('Model_Shengchan_Waixie_Fawai');
	    //子表model
	    $this->_subModel = &FLEA::getSingleton('Model_Shengchan_Waixie_FawaiSon');
	}

	/**
	 * 生成编辑界面的数据集
	 * 在actionAdd,actionEdit时需要用到
	 * Time：2015/04/13 10:46:49
	 * @author li
	 * @return Array
	*/
	function prototype(){
		//加载加工户的model
		$jgh = &FLEA::getSingleton('Model_Jichu_jiagonghu');

		//主表信息原型
		$this->fldMain = array(
			'rukuCode' => array('title'=>'发外编号',"type" =>"text",'readonly' => true,'value' => $this->_getNewCode('FW','cangku_fawai','rukuCode')),
			'rukuDate' => array('title' => '发生日期', 'type' => 'calendar', 'value' => date('Y-m-d')),
			'creater' => array('title' => '创建人', 'type' => 'text', 'value' => $_SESSION['REALNAME'].'','readonly' => true),
			'jghFromId' => array('title' => '发出加工户', 'type' => 'select', 'value' => '','options'=>$jgh->getOptions()),
			'jiagonghuId' => array('title' => '后整厂', 'type' => 'select', 'value' => '','options'=>$jgh->getOptions()),
			'memo' => array('title' => '备注', 'type' => 'textarea'),
			'id' => array('type' => 'hidden', 'value' => '','name'=>'mainId'),
			);
		// /从表表头信息
		// /type为控件类型,在自定义模板控件
		// /title为表头
		// /name为控件名
		// /bt开头的type都在webcontrolsExt中写的,如果有新的控件，也需要增加
		$this->headSon = array(
			'_edit' => array('type' => 'btBtnCopy', "title" => '+5行', 'name' => '_edit[]'),
			'ord2proId' => array(
				'type' => 'btPopup',
				"title" => '选择订单',
				'name' => 'ord2proId[]',
				'textFld' => 'orderCode',
				'hiddenFld' => 'ord2proId',
				'url'	=>url('Trade_Order','PopupFw'),
			),
			// 'proCode' => array('type' => 'bttext', "title" => '产品编号', 'name' => 'proCode[]', 'readonly' => true),
			'productId' => array(
				'title' => '产品编号',
				'type' => 'btPopup',
				'value' => '',
				'name'=>'productId[]',
				'text'=>'选择入库',
				'url'=>url('jichu_chanpin','popup'),//弹出地址,回调函数在sonTpl中进行定义,主要是对缓存中的关联数组进行重写
				'textFld'=>'proCode',//显示在text中的字段
				'hiddenFld'=>'id',//显示在hidden控件中的字段
				'inTable'=>1,
			),
			'proName' => array('type' => 'bttext', "title" => '品名', 'name' => 'proName[]', 'readonly' => true),
			'guige' => array('type' => 'bttext', "title" => '规格', 'name' => 'guige[]', 'readonly' => true),
			'color' => array('type' => 'bttext', "title" => '颜色', 'name' => 'color[]','readonly' => true),
			'menfu' => array('type' => 'bttext', "title" => '门幅', 'name' => 'menfu[]','readonly' => true),
			'kezhong' => array('type' => 'bttext', "title" => '克重', 'name' => 'kezhong[]','readonly' => true),
			'gongxuId'=>array('type'=>'btselect',"title"=>'后整工序','model'=>'Model_Jichu_Gongxu','action'=>'getOptions','name'=>'gongxuId[]'),
            'type' => array('type' => 'btselect', "title" => '区分', 'name' => 'type[]', 'value'=>'','options' =>array(
                array('text'=>'A','value'=>'A'),
                array('text'=>'B','value'=>'B'),
                array('text'=>'C','value'=>'C'),
                array('text'=>'D','value'=>'D'),
            )),
			'unit' => array('type' => 'btselect', "title" => '单位', 'name' => 'unit[]', 'value'=>'公斤','options' =>array(
				array('text'=>'公斤','value'=>'公斤'),
				array('text'=>'米','value'=>'米'),
				array('text'=>'码','value'=>'码'),
				array('text'=>'磅','value'=>'磅'),
				array('text'=>'条','value'=>'条'),
			)),
			'cntJian' => array('type' => 'bttext', "title" => '件数', 'name' => 'cntJian[]'),
			'cnt' => array('type' => 'bttext', "title" => '数量', 'name' => 'cnt[]'),
			'dengjidanjia' => array('type' => 'bttext', "title" => '单价', 'name' => 'dengjidanjia[]'),
			'memo' => array('type' => 'bttext', "title" => '备注', 'name' => 'memo[]'),
			/*'dengji' => array('type' => 'btselect', "title" => '等级', 'name' => 'dengji[]', 'value'=>'一等品','options' =>array(
				array('text'=>'一等品','value'=>'一等品'),
				array('text'=>'二等品','value'=>'二等品'),
				array('text'=>'等外品','value'=>'等外品'),
			)),*/
			//-------------------处理hidden--------------------
			'id' => array('type' => 'bthidden', 'name' => 'id[]'),
			'planId' => array('type' => 'bthidden', 'name' => 'planId[]'),
			'orderId' => array('type' => 'bthidden', 'name' => 'orderId[]'),
		);
		// 表单元素的验证规则定义
		$this->rules = array(
			'jiagonghuId' => 'required',
			'gongxuId' => 'required',
			'rukuDate' => 'required',
			'jghFromId'=>'required'
		);
	}

	/**
	 * 发外登记方法
	 * actionAdd
	 * Time：2015/04/13 10:45:24
	 * @author li
	*/
	function actionAdd(){
		$this->authCheck('8-6');
		//加载原型数据集
		$this->prototype();

		// 从表信息字段,默认3行
		for($i = 0;$i < 3;$i++) {
			$rowsSon[] = array();
		}
		$areaMain = array('title' => '发外基本信息', 'fld' => $this->fldMain);

		$smarty = &$this->_getView();
		$smarty->assign('areaMain', $areaMain);
		$smarty->assign('headSon', $this->headSon);
		$smarty->assign('rowsSon', $rowsSon);
		$smarty->assign('rules', $this->rules);
		$smarty->assign('sonTpl', "Waixie/FawaiEdit.tpl");
		$smarty->display('Main2Son/T1.tpl');
	}


    /**
     * 保存
     * Time：2015/04/13 18:55:10
     * @author li
    */
    function actionSave(){
        $this->prototype();
        //有效性验证,没有明细信息禁止保存
        //开始保存
        $pros = array();
        foreach($_POST['productId'] as $key => & $v) {
            if($v=='' || $_POST['cnt'][$key]=='') continue;
            $temp = array();
            foreach($this->headSon as $k=>&$vv) {
                $temp[$k] = $_POST[$k][$key];
            }
            $pros[]=$temp;
        }
        if(count($pros)==0) {
            js_alert('请选择有效的产品明细信息!','window.history.go(-1)');
            exit;
        }
        $row = array();
        foreach($this->fldMain as $k=>&$vv) {
            $name = $vv['name']?$vv['name']:$k;
            $row[$k] = $_POST[$name];
        }

        $row['Son'] = $pros;
         //dump($row);exit;
        if(!$this->_modelExample->save($row)) {
            js_alert('保存失败','window.history.go(-1)');
            exit;
        }
        //跳转
        js_alert(null,'window.parent.showMsg("保存成功!")',url($_POST['fromController'],$_POST['fromAction'],array()));
        exit;
    }

    /**
     * 查询界面
     * Time：2015/04/13 19:05:09
     * @author li
    */
    function actionRight(){
        $this->authCheck('8-7');
        FLEA::loadClass('TMIS_Pager');
        $arr = TMIS_Pager::getParamArray(array(
            'dateFrom'=>date('Y-m-d',mktime(0,0,0,date('m'),date('d')-30,date('Y'))),
            'dateTo'=>date('Y-m-d'),
            'jiagonghuId'=>'',
            'gongxuId'=>'',
            'zlbType'=>'',
            'key' => '',
            'orderCode'=>'',
        ));

        $sql="select x.*,
                      y.unit,y.cnt,y.cntJian,y.memo as memoSon,y.type,
                      z.proCode,z.proName,z.guige,z.color,z.menfu as productMenfu,z.kezhong as productKezhong,
                      g.name as gxName,
                      j.compName,
                      o.orderCode,
                      o2p.menfu,
                      o2p.kezhong
            from cangku_fawai x
            left join cangku_fawai2product y on x.id=y.fawaiId
            left join jichu_product z on z.id=y.productId
            left join jichu_gongxu g on g.id=y.gongxuId
            left join jichu_jiagonghu j on j.id=x.jiagonghuId
            left join trade_order o on o.id=y.orderId
            left join trade_order2product o2p on o2p.id=y.ord2proId
            where 1";
        if($arr['jiagonghuId']>0){
            $sql.=" and x.jiagonghuId='{$arr['jiagonghuId']}'";
        }
        if($arr['gongxuId']>0){
            $sql.=" and y.gongxuId='{$arr['gongxuId']}'";
        }
        if($arr['key']!=''){
            $sql.=" and (z.proCode like '%{$arr['key']}%'
                        or z.proName like '%{$arr['key']}%'
                        or z.guige like '%{$arr['key']}%'
                        or z.color like '%{$arr['key']}%'
                )";
        }
        if($arr['dateFrom']!=''){
            $sql.=" and x.rukuDate >= '{$arr['dateFrom']}' and x.rukuDate <='{$arr['dateTo']}'";
        }

        if($arr['zlbType']!=''){
            $sql.=" and y.type = '{$arr['zlbType']}'";
        }

        if($arr['orderCode']!='') $sql.=" and o.orderCode like '%{$arr['orderCode']}%'";
        // dump($sql);exit;
        //按照时间排序然后是倒序
        $sql.=" order by x.rukuDate desc";

        //查找计划
        if(!$_GET['export']){
            $pager = &new TMIS_Pager($sql);
            $rowset = $pager->findAll();

            foreach($rowset as &$v) {
                $v['_edit'] = $this->getEditHtml($v['id']);
                //删除操作
                $v['_edit'] .= ' ' .$this->getRemoveHtml($v['id']);

            }

            // 总计信息
            $zongji = $this->getZongji($sql, array('cnt'=>'y.cnt', 'cntJian'=>'y.cntJian'));
            $htmlPageMessage = "&nbsp;&nbsp;<font color='red'> 总计信息：</font>"." 数量:{$zongji['cnt']}"." 件数:{$zongji['cntJian']}";
        }else{
            $rowset =  $this->_modelExample->findBySql($sql);
        }
        $heji = $this->getHeji($rowset,array('cnt','cntJian'),'_edit');

        if($_GET['export']){
            $heji['rukuDate'] = '合计';
        }
        $rowset[]= $heji;

        $smarty = &$this->_getView();
        // 左侧信息
        $arrFieldInfo = array(
            "_edit" => '操作',
            'rukuDate'=>'发生日期',
            'compName'=>'加工户',
            'gxName'=>'工序',
            'orderCode'=>'订单号',
            'proCode'=>'产品编号',
            "proName" => array('text'=>"品种", 'width'=>220),
            'guige' => array('text'=>'规格', 'width'=>220),
            'menfu' => '门幅',
            'kezhong' => '克重',
            'type'    => '区分',
            'memoSon' => array('text'=>'备注','width'=>120),
            'cntJian'=>array('type'=>'Number', 'text'=>'件数'),
            'cnt'=>array('type'=>'Number', 'text'=>'数量'),
            'unit'=>'单位',
            'rukuCode'=>'发外单号',
            'color' => '颜色',
            'creater'=>'操作人',
        );

        if($_GET['export']){
            unset($arrFieldInfo['_edit']);
        }

        $smarty->assign('title', '计划查询');
        $smarty->assign('arr_field_info', $arrFieldInfo);
        $smarty->assign('add_display', 'none');
        $smarty->assign('arr_condition', $arr);
        $smarty->assign('arr_field_value', $rowset);
        if($_GET['export']==1){
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: application/force-download");
            header("Content-Type: application/download");
            header("Content-Disposition: attachment;filename={$_GET['exportName']}.xls");
            header("Content-Transfer-Encoding: binary");
            $smarty->display('Export2Excel2.tpl');
            exit;
        }
        $smarty->assign('fn_export',$this->_url($_GET['action'],$arr+array('export'=>1, 'exportName'=>'后整发外理')));
        $smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action'], $arr)).$htmlPageMessage);
        $smarty->display('TblList.tpl');
    }

	/**
	 * 修改界面
	 * Time：2015/04/13 19:49:08
	 * @author li
	*/
	function actionEdit(){
		$this->authCheck('8-6');
		$this->prototype();
		$arr = $this->_modelExample->find(array('id' => $_GET['id']));
		$arr['gongxuId'] = $arr['Son'][0]['gongxuId'];

		foreach ($this->fldMain as $k => &$v) {
			$v['value'] = $arr[$k]?$arr[$k]:$v['value'];
		}
		//加载库位信息的值
		$areaMain = array('title' => $this->_kind.'基本信息', 'fld' => $this->fldMain);

		// 入库明细处理
		$rowsSon = array();
		foreach($arr['Son'] as &$v) {
			$sql = "select * from jichu_product where id='{$v['productId']}'";
			$_temp = $this->_modelExample->findBySql($sql); //dump($_temp);exit;
			$v['proCode'] = $_temp[0]['proCode'];
			$v['proName'] = $_temp[0]['proName'];
			$v['guige'] = $_temp[0]['guige'];
			$v['color'] = $_temp[0]['color'];
			$v['menfu'] = $_temp[0]['menfu'];
			$v['kezhong'] = $_temp[0]['kezhong'];

		}
		foreach($arr['Son'] as &$v) {
			$temp = array();
			foreach($this->headSon as $kk => &$vv) {
				$temp[$kk] = array('value' => $v[$kk]);
			}
			$temp['productId']['text'] = $v['proCode'];
			$rowsSon[] = $temp;
		}
		//填充订单显示的信息
		foreach ($rowsSon as $key => & $v) {
			if(!$v['orderId']['value'])continue;
			// dump($v);exit;
			$sql = "SELECT x.orderCode from trade_order x where x.id='{$v['orderId']['value']}'";
			$_temp = $this->_modelExample->findBySql($sql);
			$v['ord2proId'] && $v['ord2proId']['text']=$_temp[0]['orderCode'];
		}
		//补齐5行
		$cnt = count($rowsSon);
		for($i=3;$i>$cnt;$i--) {
			$rowsSon[] = array();
		}
		// dump($rowsSon);exit;

		$smarty = &$this->_getView();
		$smarty->assign('areaMain', $areaMain);
		$smarty->assign('headSon', $this->headSon);
		$smarty->assign('rowsSon', $rowsSon);
		$smarty->assign('fromAction', $_GET['fromAction']);
		$smarty->assign('rules', $this->rules);
		$smarty->assign('sonTpl', "Waixie/FawaiEdit.tpl");
		$smarty->display('Main2Son/T1.tpl');
	}
}
?>