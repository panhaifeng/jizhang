<?php
//外协定型通知单
FLEA::loadClass('Controller_Shengchan_Waixie_Tongzhidan');
class Controller_Shengchan_Waixie_Dingxing extends Controller_Shengchan_Waixie_Tongzhidan {
	
	function __construct() {
	    parent::__construct();
	    $this->fldMain['kind'] = array('title'=>'类型','type'=>'select','value'=>'','options'=>array(
					array('text' => '外协定型', 'value' => '外协定型'),
					array('text' => '外协水洗', 'value' => '外协水洗'),
					array('text' => '外协染色', 'value' => '外协染色'),
		    		array('text' => '外协印花', 'value' => '外协印花'),
		    		array('text' => '外协拉绒', 'value' => '外协拉绒'),
					));
	}

	function actionAdd(){
        $this->authCheck('8-4');
        //坯布数据
		$pibuData = array(
			'xiajiMenfu' => array('title' => '下机门幅', 'type' => 'text',  'name' => 'xiajiMenfu','addonEnd' => 'cm'),
			'xiajiKezhong' => array('title' => '下机克重', 'type' => 'text',  'name' => 'xiajiKezhong','addonEnd'=>'g/m2'),
			'fachuCnt' => array('title' => '发出数量', 'type' => 'text', 'name' => 'fachuCnt','addonEnd'=>'KG'),
			'fachuJianshu' => array('title' => '发出件数', 'type' => 'text',  'name' => 'fachuJianshu','addonEnd'=>'件'),
			); 

		//成布要求
		$chengbuYaoqiu=array(
            'yaoqiuMenfu'=>array('title'=>'要求门幅','type'=>'text','name'=>'yaoqiuMenfu','addonEnd' => 'cm'),
            'yaoqiuKezhong'=>array('title'=>'要求克重','type'=>'text','name'=>'yaoqiuKezhong','addonEnd' => 'g/m2'),
            'yaoqiuSuolvJingxiang'=>array('title'=>'要求缩率经向','type'=>'text','name'=>'yaoqiuSuolvJingxiang','addonEnd' => '%','addonPre' => '-'),
            'yaoqiuSuolvWeixiang'=>array('title'=>'要求缩率维向','type'=>'text','name'=>'yaoqiuSuolvWeixiang','addonEnd' => '%','addonPre' => '-'),
			);
		//成品实际数据
        $chengpinShijiData=array(
            'shijiMenfu'=>array('title'=>'实际门幅','type'=>'text','name'=>'shijiMenfu','addonEnd' => 'cm'),
            'shijiKezhong'=>array('title'=>'实际克重','type'=>'text','name'=>'shijiKezhong','addonEnd' => 'g/m2'),
            'shijiSuolvJingxiang'=>array('title'=>'实际缩率经向','type'=>'text','name'=>'shijiSuolvJingxiang','addonEnd' => '%','addonPre' => '-'),
            'shijiSuolvWeixiang'=>array('title'=>'实际缩率维向','type'=>'text','name'=>'shijiSuolvWeixiang','addonEnd' => '%','addonPre' => '-'),
            'xihouMenfu'=>array('title'=>'洗后门幅','type'=>'text','name'=>'xihouMenfu','addonEnd' => 'cm'),
            'xihouKezhong'=>array('title'=>'洗后克重','type'=>'text','name'=>'xihouKezhong','addonEnd' => 'g/m2'),
        	);

        // 主表信息字段
		$fldMain = $this->fldMain;
        
        // 主表区域信息描述
		$areaMain = array('title' => '外协基本信息', 'fld' => $fldMain);
        
        $arr_item1=array('title' => '坯布数据 ', 'fld' => $pibuData);
        $arr_item2=array('title' => '成布要求 ', 'fld' => $chengbuYaoqiu);
        $arr_item3=array('title' => '成品实际数据 ', 'fld' => $chengpinShijiData);

        $smarty = &$this->_getView();
		$smarty->assign('areaMain', $areaMain);
		$smarty->assign('rules', $this->rules);
        $smarty->assign("arr_item1", $arr_item1);
		$smarty->assign("arr_item2", $arr_item2);
		$smarty->assign("arr_item3", $arr_item3);
		//$smarty->assign("tbl_son_width", '120%');
		//$smarty->assign("otherInfoTpl", 'Trade/otherInfoTpl.tpl');
		$smarty->assign('sonTpl', 'Waixie/jsSell.tpl');
		$smarty->display('Main2Son/waixie.tpl');
	}
	function actionRight(){
		$this->fldMain = array(
				'waixieCode' => array('title' => '外协单号', "type" => "text", 'readonly' => true, 'value' => $this->_modelDefault->getNewWaixieCode()),
		
				'waixieDate' => array('title' => '外协日期', 'type' => 'calendar', 'value' => date('Y-m-d')),
		
				'jiaohuoDate' => array('title' => '交货日期', 'type' => 'calendar', 'value' => date('Y-m-d')),
					
				'kind'=>array('title'=>'类型','type'=>'text','value'=>'','readonly'=>true),
		
				'jiagonghuId' => array('title' => '加工户', 'type' => 'select', 'value' => '', 'model' => 'Model_Jichu_Jiagonghu'),
		
				'ord2proId' => array(
						'title' => '相关订单',
						'type' => 'popup',
						'value' => '',
						'name'=>'ord2proId',
						'text'=>'',
						'url'=>url('Shengchan_Waixie_Dingxing','Popup'),
						//'jsTpl'=>'Cangku/Chengpin/jsRuku.tpl',//需要载入的模板文件，这个文件中不需要使用{literal},因为是通过file_get_content获得的
						'onSelFunc'=>'onSel',//选中后需要执行的回调函数名,需要在jsTpl中书写
						'textFld'=>'orderCode',//显示在text中的字段
						'hiddenFld'=>'ord2proId',//显示在hidden控件中的字段
				),
		
				'proName'=>array('title'=>'品名','type'=>'text','value'=>''),
		
				'guige'=>array('title'=>'规格','type'=>'text','value'=>''),
		
				'ganghao'=>array('title'=>'缸号','type'=>'text','value'=>''),
		
				// 下面为隐藏字段
				'waixieId' => array('type' => 'hidden', 'value' => ''),
				'productId'=>array('type'=>'hidden','value'=>''),
				'id'=> array('type' => 'hidden', 'value' => ''),
		);
		
		// 表单元素的验证规则定义
		$this->rules = array(
				'jiagonghuId' => 'required',
				'proName' => 'required',
				'guige' => 'required',
				'kind'=> 'required',
				//'traderId' => 'required'
		);
		//dump('查询中。。。');exit;
		//$this->authCheck('8-5');
		FLEA::loadClass('TMIS_Pager');
		// /构造搜索区域的搜索类型
		$serachArea = TMIS_Pager::getParamArray(array(
				'dateFrom' => date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-30, date("Y"))),
				'dateTo' => date("Y-m-d"),
				//'clientId' => '',
				'jiagonghuId' => '',
				//'isCheck' => 2,
				'orderCode' => '',
				'key' => '',
		));
		// dump($serachArea);exit;
		$sql = "select y.*,
                       x.kind,
                       x.waixieDate,
                       x.jiaohuoDate,
                       x.jiagonghuId,
				       c.orderId
			               from shengchan_waixie2product y
			               left join shengchan_waixie x on x.id=y.waixieId
		                  left join trade_order2product c on c.id=y.ord2proId ";
		$str = "select
				x.*,
				y.compName,
				z.orderCode
				from (" . $sql . ") x
				left join jichu_jiagonghu y on x.jiagonghuId = y.id
				left join trade_order z on x.orderId = z.id
                where 1";
	
		$str .= " and waixieDate >= '$serachArea[dateFrom]' and waixieDate<='$serachArea[dateTo]'";
		if ($serachArea['key'] != '') $str .= " and (x.proName like '%$serachArea[key]%'
		or x.guige like '%$serachArea[key]%')";
		if ($serachArea['orderCode'] != '') $str .= " and z.orderCode like '%$serachArea[orderCode]%'";
		// if ($serachArea['clientId'] != '') $str .= " and x.clientId = '$serachArea[clientId]'";
		if ($serachArea['jiagonghuId'] != '') $str .= " and x.jiagonghuId = '$serachArea[jiagonghuId]'";
		$str .= " order by waixieDate desc";
		//dump($str);exit;
		$pager = &new TMIS_Pager($str);
		$rowset = $pager->findAll();
		$rowset2 = $this->_modelExample->findBySql($str);
	
		if (count($rowset2) > 0) foreach($rowset2 as &$value) {
			$res=array();
			$res=unserialize($value['strSerial']);
			$value['fachuCnt']=round($res['fachuCnt'],2);
			$value['fachuJianshu']=round($res['fachuJianshu'],2);
			$value['_edit'] = "<a href='" . url('Shengchan_Waixie_Dingxing','PrintWaiXie', array('id' => $value['waixieId'])) . "' target='_blank' title='$title'>导出</a>";
			$value['_edit'] .= " <a href='".url('Shengchan_Waixie_Dingxing','Edit',array(
					'id'=>$value['waixieId'],
					'fromAction' => $_GET['action']
			))."'>修改</a>";
			$value['_edit'] .= " ".$this->getRemoveHtml($value['waixieId']);
		}
		 //dump($rowset2);exit;
		if (count($rowset) > 0) foreach($rowset as &$value) {
			$res=array();
			$res=unserialize($value['strSerial']);
			$value['fachuCnt']=round($res['fachuCnt'],2);
			$value['fachuJianshu']=round($res['fachuJianshu'],2);
			$value['_edit'] = "<a href='" . url('Shengchan_Waixie_Dingxing','PrintWaiXie', array('id' => $value['waixieId'])) . "' target='_blank' title='$title'>导出</a>";
			$value['_edit'] .= " <a href='".url('Shengchan_Waixie_Dingxing','Edit',array(
					'id'=>$value['waixieId'],
					'fromAction' => $_GET['action']
			))."'>修改</a>";
			$value['_edit'] .= " ".$this->getRemoveHtml($value['waixieId']);
		}
		//dump($rowset);exit;
		// 右侧信息
		$arrFieldInfo = array(
				"_edit" => array("text"=>'操作','width'=>120),
				"waixieDate" => "下单日期",
				"jiaohuoDate" => "交货日期",
				'orderCode' => '生产编号',
				"compName" => "加工户",
				'proName'=>'品名',
				'guige'=>'规格',
				'fachuCnt'=>'数量',
				'fachuJianshu'=>'件数',
				'ganghao'=>'缸号',
				'kind'=>'单据分类',
					
		);	
		$arrFieldInfoo = array(
				//"_edit" => array("text"=>'操作','width'=>120),
				"waixieDate" => "下单日期",
				"jiaohuoDate" => "交货日期",
				'orderCode' => '生产编号',
				"compName" => "加工户",
				'proName'=>'品名',
				'guige'=>'规格',
				'fachuCnt'=>'数量',
				'fachuJianshu'=>'件数',
				'ganghao'=>'缸号',
				'kind'=>'单据分类',
					
		);
		$heji = $this->getHeji($rowset, array('fachuCnt','fachuJianshu'), '_edit');
		$rowset[] = $heji;
		$zongji = $this->getHeji($rowset2, array('fachuCnt','fachuJianshu'), 'waixieDate');
		$zongji['waixieDate'] = "<b>总计</b>";
		//$rowset[] = $zongji;
		$rowset2[] = $zongji;
		foreach ($rowset2 as &$v){
 			$v['waixieDate'] = strip_tags($v['waixieDate']);
 		}
		
		$smarty = &$this->_getView();
		$smarty->assign('title', '外协查询');
		$smarty->assign('arr_field_info', $arrFieldInfo);
		//$smarty->assign('add_display', $isShowAdd?'display':'none');
		$smarty->assign('arr_condition', $serachArea);
		$smarty->assign('arr_field_value', $rowset);
		$smarty->assign('arr_js_css', $this->makeArrayJsCss(array('grid', 'calendar')));
		$smarty->assign('arr_field_value', $rowset);
		$smarty->assign("page_info",$pager->getNavBar($this->_url($_GET['action'],$serachArea))."<a href='".$this->_url($_GET['action'],array('export'=>1))."'>导出</a>".'<center>'.$mm.'</center>');
		if($_GET['export']!=1) {
			$smarty->display('TableList.tpl');
		} else {
			$smarty->assign('arr_field_info',$arrFieldInfoo);
			$smarty->assign('arr_field_value',$rowset2);
			header("Content-type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=test.xls");
			$smarty->display("Export2Excel.tpl");
		}
	}
    function _getEdit(& $rowset){
    	//dump(123);exit;
        if (count($rowset) > 0) foreach($rowset as &$value) {
    //     	if($value['kind']=='外协定型'){
    //             $value['_edit'] = "<a href='" . url('Shengchan_Waixie_Dingxing','PrintWaiXie', array('id' => $value['waixieId'])) . "' target='_blank' title='$title'>导出</a>";
    //             $value['_edit'] .= " <a href='".url('Shengchan_Waixie_Dingxing','Edit',array(
				// 	'id'=>$value['waixieId'],
				// 	'fromAction' => $_GET['action']
				// ))."'>修改</a>";
    //             $value['_edit'] .= " ".$this->getRemoveHtml($value['waixieId']);
    //     	}
    //     	//当kind不同时，在下面重写url
    //     	else{
    //             $value['_edit'] = "<a href='" . url('Shengchan_Waixie_Tongzhidan','PrintWaiXie', array('id' => $value['waixieId'])) . "' target='_blank' title='$title'>导出</a>";
    //             $value['_edit'] .= " <a href='".url('Shengchan_Waixie_Tongzhidan','Edit',array(
				// 	'id'=>$value['waixieId'],
				// 	'fromAction' => $_GET['action']
				// ))."'>修改</a>";
    //             $value['_edit'] .= " ".$this->getRemoveHtml($value['waixieId']);

    //     	}

        	 $value['_edit'] = "<a href='" . url('Shengchan_Waixie_Dingxing','PrintWaiXie', array('id' => $value['waixieId'])) . "' target='_blank' title='$title'>导出</a>";
             $value['_edit'] .= " <a href='".url('Shengchan_Waixie_Dingxing','Edit',array(
					'id'=>$value['waixieId'],
					'fromAction' => $_GET['action']
				))."'>修改</a>";
            $value['_edit'] .= " ".$this->getRemoveHtml($value['waixieId']);
            
		}
		//dump($rowset);exit;
    }

    function actionEdit(){
    	//dump('定型编辑');exit;
    	// dump($_GET);exit;
    	// $shengchan_waixie2product = &FLEA::getSingleton('Model_Shengchan_Waixie');
    	$arr = $this->_modelExample->find(array('id' => $_GET['id'])); 
    	// dump($arr);exit;
    	//处理$this->fldMain中的控件
    	foreach ($this->fldMain as $k => &$v) {
			$v['value'] = $arr[$k]?$arr[$k]:$v['value']; 
		}
		//设置waixieId的值
		$this->fldMain['waixieId']['value'] = $arr['id'];
		//dump($this->fldMain);exit;
        //设置id的值
		$this->fldMain['id']['value'] = $arr['waixie'][0]['id'];
		//处理productId
		$this->fldMain['productId']['value'] = $arr['waixie'][0]['productId'];
		//设置proName的值
		$this->fldMain['proName']['value']=$arr['waixie'][0]['proName'];
		//设置guige的值
		$this->fldMain['guige']['value']=$arr['waixie'][0]['guige'];
		//设置缸号
		$this->fldMain['ganghao']['value']=$arr['waixie'][0]['ganghao'];
		//处理ord2proId控件
		$ord2pro = &FLEA::getSingleton('Model_Trade_Order2Product');
        $res=$ord2pro->find(array('id'=>$arr['waixie'][0]['ord2proId']));
        $this->fldMain['ord2proId']['text']=$res['Order']['orderCode'];//显示的字段
         $this->fldMain['ord2proId']['value']=$arr['waixie'][0]['ord2proId'];
        // dump($res);exit;

		//反序列化
		$s=array();
		$s=unserialize($arr['waixie'][0]['strSerial']);
		//dump($s);exit;

        // 主表区域信息描述
		$areaMain = array('title' => '外协基本信息', 'fld' => $this->fldMain);
		
         //坯布数据
		$pibuData = array(
			'xiajiMenfu' => array('title' => '下机门幅', 'type' => 'text',  'name' => 'xiajiMenfu','addonEnd' => 'cm'),
			'xiajiKezhong' => array('title' => '下机克重', 'type' => 'text',  'name' => 'xiajiKezhong','addonEnd'=>'g/m2'),
			'fachuCnt' => array('title' => '发出数量', 'type' => 'text', 'name' => 'fachuCnt','addonEnd'=>'KG'),
			'fachuJianshu' => array('title' => '发出件数', 'type' => 'text',  'name' => 'fachuJianshu','addonEnd'=>'件'),
			); 

		foreach ($pibuData as $key => $value) {
			$pibuData[$key]['value']=$s[$key];
		}
		//设置发出数量
        $pibuData['fachuCnt']['value']=$arr['waixie'][0]['cntSend'];

		//成布要求
		$chengbuYaoqiu=array(
            'yaoqiuMenfu'=>array('title'=>'要求门幅','type'=>'text','name'=>'yaoqiuMenfu','addonEnd' => 'cm'),
            'yaoqiuKezhong'=>array('title'=>'要求克重','type'=>'text','name'=>'yaoqiuKezhong','addonEnd' => 'g/m2'),
            'yaoqiuSuolvJingxiang'=>array('title'=>'要求缩率经向','type'=>'text','name'=>'yaoqiuSuolvJingxiang','addonEnd' => '%','addonPre' => '-'),
            'yaoqiuSuolvWeixiang'=>array('title'=>'要求缩率维向','type'=>'text','name'=>'yaoqiuSuolvWeixiang','addonEnd' => '%','addonPre' => '-'),
			);
        foreach ($chengbuYaoqiu as $key => $value) {
			$chengbuYaoqiu[$key]['value']=$s[$key];
		}
        

		//成品实际数据
        $chengpinShijiData=array(
            'shijiMenfu'=>array('title'=>'实际门幅','type'=>'text','name'=>'shijiMenfu','addonEnd' => 'cm'),
            'shijiKezhong'=>array('title'=>'实际克重','type'=>'text','name'=>'shijiKezhong','addonEnd' => 'g/m2'),
            'shijiSuolvJingxiang'=>array('title'=>'实际缩率经向','type'=>'text','name'=>'shijiSuolvJingxiang','addonEnd' => '%','addonPre' => '-'),
            'shijiSuolvWeixiang'=>array('title'=>'实际缩率维向','type'=>'text','name'=>'shijiSuolvWeixiang','addonEnd' => '%','addonPre' => '-'),
            'xihouMenfu'=>array('title'=>'洗后门幅','type'=>'text','name'=>'xihouMenfu','addonEnd' => 'cm'),
            'xihouKezhong'=>array('title'=>'洗后克重','type'=>'text','name'=>'xihouKezhong','addonEnd' => 'g/m2'),
        	);
        foreach ($chengpinShijiData as $key => $value) {
			$chengpinShijiData[$key]['value']=$s[$key];
		}

        $arr_item1=array('title' => '坯布数据 ', 'fld' => $pibuData);
        $arr_item2=array('title' => '成布要求 ', 'fld' => $chengbuYaoqiu);
        $arr_item3=array('title' => '成品实际数据 ', 'fld' => $chengpinShijiData);

        $smarty = &$this->_getView();
		$smarty->assign('areaMain', $areaMain);
		$smarty->assign('rules', $this->rules);
        $smarty->assign("arr_item1", $arr_item1);
		$smarty->assign("arr_item2", $arr_item2);
		$smarty->assign("arr_item3", $arr_item3);
		//$smarty->assign("tbl_son_width", '120%');
		$smarty->assign('sonTpl', 'Waixie/jsSell.tpl');
		$smarty->display('Main2Son/waixie.tpl');
    }

    function actionPrintWaixie(){
    	//dump("定型导出。。。");exit;
    	$arr = $this->_modelExample->find(array('id' => $_GET['id']));
    	//取出数据 
    	$jianggonghu=&FLEA::getSingleton('Model_Jichu_Jiagonghu');
    	$res=$jianggonghu->find(array('id'=>$arr['jiagonghuId']));
    	$s=unserialize($arr['waixie'][0]['strSerial']);
    	//dump($s);exit;
    	$waixie2pro=$arr['waixie'][0];

		$this->exportWithXml('Waixie/Dingxing.xml',array(
			'arr'=>$arr,
			's'=>$s,
			'res'=>$res,
			'waixie2pro'=>$waixie2pro,
		));
    }

    function actionPopup() {
		FLEA::loadClass('TMIS_Pager');
		$arr = TMIS_Pager::getParamArray(array('dateFrom' => date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-30, date("Y"))),
				'dateTo' => date("Y-m-d"),
				'key' => '',
				));

		$sql = "select y.clientId,
                     y.orderDate,
                     y.orderCode,
                     y.isCheck,
                     x.productId,
                     x.id,
                     x.cntYaohuo,
                     x.orderId as orderId
                     from trade_order2product x
                     left join trade_order y on (x.orderId = y.id)";

		$str = "select
        x.cntYaohuo,
        x.orderId,
        x.id as ord2proId,
        x.orderCode,
        x.orderDate,
        x.productId,
        x.isCheck,
        y.compName,
        z.proCode,
        z.proName,
        z.zhonglei,
        z.guige,
        z.color,
        z.menfu,
        z.kezhong
        from (" . $sql . ") x
        left join jichu_client y on x.clientId = y.id
        left join jichu_product z on x.productId = z.id
                where 1 ";

		$str .= " and orderDate >= '{$arr[dateFrom]}' and orderDate<='{$arr[dateTo]}'";
		if ($arr['key'] != '') $str .= " and (x.orderCode like '%{$arr[key]}%'
                      or z.proName like '%{$arr[key]}%'
                      or z.proCode like '%{$arr[key]}%'
                      or z.guige like '%{$arr[key]}%')";
        $str .="order by orderDate desc";
		$pager = &new TMIS_Pager($str);
		$rowset = $pager->findAllBySql($str); 
		// if(count($rowset)>0)foreach ($rowset as $i=> & $v ) {
		// }
		// dump($rowset);exit;
		// 标题栏信息
		$arrFieldInfo = array(
			"orderDate" => '订单日期',
			"orderCode" => '生产编号',
			// "compName" => "客户",
			'proCode' => '产品编码',
			'proName' => '品名',
			'zhonglei' => '种类',
			'guige' => '规格',
			'color' => '颜色',
			'cntYaohuo' => '要货数量', 
			// ''=>'',
			);

		$smarty = &$this->_getView();
		$smarty->assign('title', '订单选择');
		$smarty->assign('add_display', 'none');
		$smarty->assign('arr_field_info', $arrFieldInfo);
		$smarty->assign('arr_field_value', $rowset);
		$smarty->assign('arr_js_css', $this->makeArrayJsCss(array('thickbox')));
		$smarty->assign('arr_condition', $arr);
		$smarty->assign('page_info', $pager->getNavBar($this->_url('Popup', $arr)));
		$smarty->display('Popup/CommonNew.tpl');
	}
/* 	function actionUpdate(){
		$str="select proName,guige from shengchan_waixie2product";
		$rowset = $this->_modelExample->findBySql($str);
// 		dump($rowset);die;
		foreach($rowset as &$v){
			//dump($v);die;
			$sql="select id from jichu_product where proName='".$v['proName']."' and guige='".$v['guige']."'";
			dump($sql);die;
			$row = $this->_modelExample->findBySql($sql);
			//dump($row);die;
		}
		
		echo "修复成功!!!!";
	} */

}
?>