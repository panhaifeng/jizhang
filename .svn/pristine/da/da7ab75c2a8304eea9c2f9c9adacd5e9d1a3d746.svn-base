<?php
//外协通知单控制器
FLEA::loadClass('Tmis_Controller');
class Controller_Shengchan_Waixie_Tongzhidan extends Tmis_Controller{
    var $title ;
	var $fldMain;
	var $headSon;
	var $rules; //表单元素的验证规则 

    function __construct() {
		$this->_modelDefault = &FLEA::getSingleton('Model_Shengchan_Waixie');
		$this->_modelExample = &FLEA::getSingleton('Model_Shengchan_Waixie');
		//$this->jichu_employ= &FLEA::getSingleton('Model_Jichu_Employ');
		// 定义模板中的主表字段
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
	}

	function actionSave(){
         //dump($_POST);exit;
        //处理主表数据
        $shengchan_waixie=array(
            'id' => $_POST['waixieId'], // 主键id
            'waixieCode'=>$_POST['waixieCode'],//外协单号
            'waixieDate'=>$_POST['waixieDate'],//外协日期
            'jiaohuoDate'=>$_POST['jiaohuoDate'],//交货日期
            'kind'=>$_POST['kind'],//类型
            'jiagonghuId'=>$_POST['jiagonghuId'],//加工户id

        );

        //处理子表数据
        $shengchan_waixie2product=array();
        
        $shengchan_waixie2product[]=array(
            'id'=>$_POST['id'],//子表主键
            'waixieId'=>$_POST['waixieId'],//子表外键
            'proName'=>$_POST['proName'],//品名
            'guige'=>$_POST['guige'],//规格
            'cntSend'=>$_POST['fachuCnt'],
            'ord2proId'=>$_POST['ord2proId'],//
            'productId'=>$_POST['productId'],
            'ganghao'=>$_POST['ganghao'],
        );
        //用来放要序列化的数据
        $s=array();
        foreach ($_POST as $key => &$value) {
        	//选择要序列化的数据
        	if($key=='waixieId'||$key=='id'||$shengchan_waixie[$key]|| $shengchan_waixie2product[0][$key]) continue;
        	$s[$key]=$value;
        }
        //dump($s);exit;
        $shengchan_waixie2product[0]['strSerial']=serialize($s);
         
        // 表之间的关联
		$shengchan_waixie['waixie'] = $shengchan_waixie2product; //dump($shengchan_waixie);exit;
		// 保存 并返回trade_order表的主键
		// dump($shengchan_waixie);exit;
		$itemId = $this->_modelExample->save($shengchan_waixie);
		if ($itemId) {
			js_alert(null, 'window.parent.showMsg("保存成功！");', $this->_url('right'));
		}else die('保存失败!');
	}

	function actionRight(){
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
		// dump($rowset);exit;
		
		if (count($rowset) > 0) foreach($rowset as &$value) {
            $res=array();
            $res=unserialize($value['strSerial']);
            $value['fachuCnt']=round($res['fachuCnt'],2);
            $value['fachuJianshu']=round($res['fachuJianshu'],2);
            
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
			'kind'=>'单据分类',
			
		); 

		$smarty = &$this->_getView();
		$smarty->assign('title', '外协查询'); 
		$smarty->assign('arr_field_info', $arrFieldInfo);
		$smarty->assign('add_display', $isShowAdd?'display':'none');
		$smarty->assign('arr_condition', $serachArea);
		$smarty->assign('arr_field_value', $rowset);
		// $msg = "<font color='red'>数量总计:{$zongji['cnt']},金额总计:{$zongji['money']}</font>";
		$smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action']), $serachArea));
		$smarty->assign('arr_js_css', $this->makeArrayJsCss(array('grid', 'calendar')));
		$this->_beforeDisplayRight($smarty);
		$this->_getEdit($rowset);
		$smarty->assign('arr_field_value', $rowset);
		$smarty->display('TableList.tpl'); 
	}
    
    function actionEdit(){
    	dump('编辑中>>>');exit;
    }

    function actionPrintWaixie(){
    	dump("导出中>>>");exit;
    }

    
} 

?>