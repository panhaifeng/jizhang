<?php
FLEA::loadClass('Controller_Shengchan_Yuliao_Construct');
class Controller_Shengchan_Yuliao_ruku extends Controller_Shengchan_Yuliao_Construct {
	// var $fldMain;
	// var $headSon;
	// var $rules;//表单元素的验证规则
	// **************************************构造函数 begin********************************
	function Controller_Shengchan_Yuliao_ruku() {
		$this->_modelDefault = &FLEA::getSingleton('Model_Yuanliao_Cgrk');
		$this->_modelExample = &FLEA::getSingleton('Model_Yuanliao_Cgrk'); 
		// 定义模板中的主表字段
		$this->fldMain = array(
			// /*******2个一行******
			'rukuDate' => array('title' => '入库日期', "type" => "calendar", 'value' => date('Y-m-d')), 
			// 入库单号，自动生成
			'rukuCode' => array('title' => '入库单号', 'type' => 'text', 'readonly' => true, 'value' => ''), 
			// /*******2个一行******
			// 'supplierpopup需要显示供应商名称，所以需要定义supplierName属性,value属性作为supplierId用
			'supplierId' => array('title' => '供应商', 'type' => 'select', 'value' => '', 'model' => 'Model_Jichu_Supplier'),

			'songhuoCode' => array('title' => '送货单号', 'type' => 'text', 'value' => '', 'readonly' => true),
			'kind' => array('title' => '入库类别', 'type' => 'text', 'value' => '初始化入库', 'readonly' => true),
			'kuweiId' => array('title' => '库位选择', 'type' => 'select', 'value' => ''), 
			// /*******2个一行******
			// 定义了name以后，就不会以memo作为input的id了
			'memo' => array('title' => '采购备注', 'type' => 'textarea', 'disabled' => true, 'name' => 'rukuMemo'), 
			// 下面为隐藏字段
			'ruKuId' => array('type' => 'hidden', 'value' => ''),
			'isGuozhang' => array('type' => 'hidden', 'value' => ''),
			); 
		// /从表表头信息
		// /type为控件类型,在自定义模板控件
		// /title为表头
		// /name为控件名
		// /bt开头的type都在webcontrolsExt中写的,如果有新的控件，也需要增加
		$this->headSon = array('_edit' => array('type' => 'btBtnRemove', "title" => '+5行', 'name' => '_edit[]'),
			'productId' => array('type' => 'btnYuanliao', "title" => '产品选择', 'name' => 'productId[]'),
			'proName' => array('type' => 'bttext', "title" => '品名', 'name' => 'proName[]', 'readonly' => true),
			'guige' => array('type' => 'bttext', "title" => '规格', 'name' => 'guige[]', 'readonly' => true), 
			// 'unit'=>array('type'=>'bttext',"title"=>'单位','name'=>'unit[]','readonly'=>true),
			'pihao' => array('type' => 'bttext', 'title' => '批号', 'name' => 'pihao[]'),
			'cnt' => array('type' => 'bttext', "title" => '数量(吨)', 'name' => 'cnt[]'), 
			// 'danjia'=>array('type'=>'bttext',"title"=>'单价','name'=>'danjia[]'),
			// 'money'=>array('type'=>'bttext',"title"=>'金额(元)','name'=>'money[]','readonly'=>true),
			// 'kuweiId'=>array('type'=>'btkuweiselect','title'=>'库位选择','name'=>'kuweiId[]'),
			'memo' => array('type' => 'bttext', "title" => '备注', 'name' => 'memo[]'), 
			// ***************如何处理hidden?
			'id' => array('type' => 'bthidden', 'name' => 'id[]'),
			); 
		// 表单元素的验证规则定义
		$this->rules = array('rukuDate' => 'required', 
			// 'orderDate'=>'required',
			'supplierId' => 'required', 
			// 'traderId'=>'required'
			);
		// $this->_modelDefault = & FLEA::getSingleton('Model_Yuanliao_Cgrk');
		// $this->_modelExample = & FLEA::getSingleton('Model_Yuanliao_Cgrk');
		// //定义模板中的主表字段
		// $this->fldMain = array(
		// ///*******2个一行******
		// 'rukuDate'=>array('title'=>'入库日期',"type"=>"calendar",'value'=>date('Y-m-d')),
		// //入库单号，自动生成
		// 'rukuCode'=>array('title'=>'入库单号','type'=>'text','readonly'=>true,'value'=>$this->_modelDefault->getNewRukuNum()),
		// ///*******2个一行******
		// //'supplierpopup需要显示供应商名称，所以需要定义supplierName属性,value属性作为supplierId用
		// 'supplierId'=>array('title'=>'供应商','type'=>'select','value'=>''),
		// 'songhuoCode'=>array('title'=>'送货单号','type'=>'text','value'=>''),
		// 'kuweiId'=>array('title'=>'库位选择','type'=>'select','value'=>''),
		// ///*******2个一行******
		// //定义了name以后，就不会以memo作为input的id了
		// 'memo'=>array('title'=>'采购备注','type'=>'textarea','disabled'=>true,'name'=>'rukuMemo'),
		// //下面为隐藏字段
		// 'ruKuId'=>array('type'=>'hidden','value'=>''),
		// 'isGuozhang'=>array('type'=>'hidden','value'=>''),
		// );
		// ///从表表头信息
		// ///type为控件类型,在自定义模板控件
		// ///title为表头
		// ///name为控件名
		// ///bt开头的type都在webcontrolsExt中写的,如果有新的控件，也需要增加
		// $this->headSon = array(
		// '_edit'=>array('type'=>'btBtnRemove',"title"=>'+5行','name'=>'_edit[]'),
		// 'productId'=>array('type'=>'btyuanliaopopup',"title"=>'产品选择','name'=>'productId[]'),
		// 'proName'=>array('type'=>'bttext',"title"=>'品名','name'=>'proName[]','readonly'=>true),
		// 'guige'=>array('type'=>'bttext',"title"=>'规格','name'=>'guige[]','readonly'=>true),
		// //'unit'=>array('type'=>'bttext',"title"=>'单位','name'=>'unit[]','readonly'=>true),
		// 'pihao'=>array('type'=>'bttext','title'=>'批号','name'=>'pihao[]'),
		// 'cnt'=>array('type'=>'bttext',"title"=>'数量(吨)','name'=>'cnt[]'),
		// // 'danjia'=>array('type'=>'bttext',"title"=>'单价','name'=>'danjia[]'),
		// // 'money'=>array('type'=>'bttext',"title"=>'金额(元)','name'=>'money[]','readonly'=>true),
		// //'kuweiId'=>array('type'=>'btkuweiselect','title'=>'库位选择','name'=>'kuweiId[]'),
		// 'memo'=>array('type'=>'bttext',"title"=>'备注','name'=>'memo[]'),
		// //***************如何处理hidden?
		// 'id'=>array('type'=>'bthidden','name'=>'id[]'),
		// );
		// //表单元素的验证规则定义
		// $this->rules = array(
		// 'rukuDate'=>'required',
		// //'orderDate'=>'required',
		// 'supplierId'=>'required',
		// //'traderId'=>'required'
		// );
		parent::__construct();
		$this->fldMain['songhuoCode'] = array('title' => '送货单号', 'type' => 'text', 'value' => '');
		$this->fldMain['kind'] = array('title' => '入库类别', 'type' => 'select', 'options' => array(
				array('text' => '采购入库', 'value' => '采购入库'),
				array('text' => '采购退货', 'value' => '采购退货'),
				array('text' => '其他入库', 'value' => '其他入库'),
				));
	}

	// **************************************构造函数 end********************************
	// function actionInit(){
	// //不用过账
	// $isGuozhang=1;
	// $this->actionAdd($isGuozhang);
	// }
	// function actionAdd($Arr){
	// $this->authCheck('3-2');
	// $this->_edit(array(isGuozhang=>$Arr));
	// }
	// /// 编辑订单基本信息
	// function _edit($Arr) {
	// //dump($Arr);exit; 
	// //生成供应商 名称信息
	// $m_jichu_employ=& FLEA::getSingleton('Model_Jichu_Supplier');
	// $sql = "select * from jichu_supplier where 1";
	// $rowset = $m_jichu_employ->findBySql($sql);
	// foreach($rowset as & $v) {
	// // *根据要求：options为数组,必须有text和value属性
	// $rowsSupplier[] = array('text'=>$v['compName'],'value'=>$v['id']);
	// }
	// //生成库位 名称信息
	// $m_jichu_kuwei=& FLEA::getSingleton('Model_Jichu_Kuwei');
	// $sql = "select * from jichu_kuwei where 1";
	// $rowset = $m_jichu_kuwei->findBySql($sql);
	// foreach($rowset as & $v) {
	// // *根据要求：options为数组,必须有text和value属性
	// $rowsKuwei[] = array('text'=>$v['kuweiName'],'value'=>$v['id']);
	// }
	// //主表信息字段
	// $fldMain=$this->fldMain;
	// // *在主表字段中加载供应商选项*
	// $fldMain['supplierId']['options'] =$rowsSupplier;
	// // *在主表字段中加载库位选项*
	// $fldMain['kuweiId']['options'] =$rowsKuwei;
	// // *入库号的默认值的加载*
	// $fldMain['rukuCode']['value'] = $this->_modelDefault->getNewRukuNum(); 
	// //判断是否需要过账 0要 1否
	// $fldMain['isGuozhang']['value']=$Arr['isGuozhang']; 
	// //dump($fldMain);exit;
	// $headSon = $this->headSon;
	// //从表信息字段,默认5行
	// for($i=0;$i<5;$i++) {
	// $rowsSon[] = array(
	// // 'proCode'=>array('value'=>''),                //产品编码（中文）
	// // 'proName'=>array('value'=>''),               //产品名称（英文与数字）
	// // 'guige'=>array('value'=>''),                 //规格
	// // 'pihao'=>array('value'=>''),                  //批号
	// // 'cnt'=>array('value'=>''),                   //数量
	// // 'danjia'=>array('value'=>''),                //单价
	// // 'money'=>array('value'=>''),                 //金额
	// // 'memo'=>array('value'=>''),                  //备注
	// // 'kuweiName'=>array('value'=>''),              //库位名称
	// // //从表中有时需要hidden控件的。
	// // 'id'=>array('value'=>''),
	// // 'productId'=>array('value'=>''),
	// );
	// } 
	// //主表区域信息描述
	// $areaMain = array('title'=>'入库基本信息','fld'=>$fldMain); 
	// //从表区域信息描述
	// $smarty = & $this->_getView();
	// $smarty->assign('areaMain',$areaMain);
	// $smarty->assign('headSon',$headSon);
	// $smarty->assign('rowsSon',$rowsSon);
	// $smarty->assign('rules',$this->rules);
	// $smarty->display('Main2Son/T.tpl');
	// } 
	// ///保存
	// function actionSave(){
	// //dump($_POST);exit;
	// $yuanliao_cgrk2product=array();
	// foreach ($_POST['productId'] as $key => $v) {
	// // 当没有记录 或者 没有输入数量时 跳出本次循环 防止多保存
	// if(empty($_POST['productId'][$key]) || empty($_POST['cnt'][$key])) continue;
	// $yuanliao_cgrk2product[] = array(
	// 'id'=>$_POST['id'][$key],                     //主键id
	// 'productId'=>$_POST['productId'][$key],       //产品id
	// 'cnt'=>$_POST['cnt'][$key],                   //要货数量
	// 'pihao'=>$_POST['pihao'][$key],
	// //'unit'=> $_POST['unit'][$key],                //单位
	// // 'danjia'=>$_POST['danjia'][$key],             //单价
	// // 'money'=>$_POST['money'][$key],               //金额
	// 'memo'=>$_POST['memo'][$key],                 //备注
	// 'kuweiId'=>$_POST['kuweiId'],                 //库位
	// );
	// }
	// //yuanliao_cgrk 表 的数组
	// $yuanliao_cgrk = array(
	// 'id'=>$_POST['ruKuId'],                         //主键id
	// 'rukuCode'=>$_POST['rukuCode'],                //入库单号
	// 'songhuoCode'=>$_POST['songhuoCode'],                //送货单号
	// 'rukuDate'=>$_POST['rukuDate'],                 //入库日期
	// 'isGuozhang'=>$_POST['isGuozhang'],             //是否过账
	// 'supplierId'=>$_POST['supplierId'],             //供应商id
	// 'memo'=>$_POST['rukuMemo'],                     //入库备注
	// //''=>$_POST[''],
	// );
	// //表之间的关联
	// $yuanliao_cgrk['Products']= $yuanliao_cgrk2product;
	// //dump($yuanliao_cgrk);exit;
	// //保存 并返回yuanliao_cgrk表的主键
	// $itemId=$this->_modelExample->save($yuanliao_cgrk);
	// if($itemId){
	// if($_POST['Submit'] == '保存并新增下一个'){
	// js_alert('保存成功！','',$this->_url('add'));
	// } else {
	// js_alert('保存成功！','',$this->_url('right'));
	// }
	// }else die('保存失败!');
	// }
	// **********************************入库查询 begin************************* 
	// function actionRight(){
	// //echo "还没写呢";exit;
	// $this->authCheck('3-3');
	// FLEA::loadClass('TMIS_Pager');
	// ///构造搜索区域的搜索类型
	// $serachArea=TMIS_Pager::getParamArray(array(
	// 'dateFrom' =>date("Y-m-d",mktime(0,0,0,date("m"),date("d")-30,date("Y"))),
	// 'dateTo' => date("Y-m-d"),
	// 'supplierId' => '',
	// // 'traderId' => '',
	// // 'isCheck' => 0,
	// //'rukuCode' =>'',
	// 'key'=>'',
	// ));
	// ///查询sql语句
	// //     $sql = "select *
	// //                    from yuanliao_cgrk";
	// //   $str = "select
	// // x.*,
	// // y.compName
	// // from (".$sql.") x
	// // left join jichu_supplier y on x.supplierId = y.id
	// //             where 1";
	// $sql = "select y.rukuCode,
	// y.rukuDate,
	// y.supplierId,
	// y.memo as rukuMemo,
	// y.isGuozhang,
	// x.id,
	// x.ruKuId as ruKuId,
	// x.productId,
	// x.cnt,
	// x.memo
	// from yuanliao_cgrk2product x
	// left join yuanliao_cgrk y on (x.ruKuId = y.id)";
	// $str = "select
	// x.ruKuId,
	// x.rukuCode,
	// x.rukuDate,
	// x.supplierId,
	// x.productId,
	// x.cnt,
	// x.rukuMemo,
	// x.memo,
	// x.isGuozhang,
	// x.id,
	// y.compName,
	// z.proCode,
	// z.proName,
	// z.zhonglei,
	// z.guige,
	// z.color
	// from (".$sql.") x
	// left join jichu_supplier y on x.supplierId = y.id
	// left join jichu_yuanliao z on x.productId = z.id
	// where 1";
	// $str .= " and rukuDate >= '$serachArea[dateFrom]' and rukuDate<='{$serachArea[dateTo]}'";
	// if ($serachArea['key'] != '')  $str .= " and (x.rukuCode like '%{$serachArea[key]}%'
	// or z.pinming like '%{$serachArea[key]}%'
	// or z.proCode like '%{$serachArea[key]}%'
	// or z.guige like '%{$serachArea[key]}%')";
	// //if($serachArea['isCheck'] != '')   $str .= " and x.isCheck = '{$serachArea[isCheck]}'";
	// if($serachArea['rukuCode'] != '') $str .=" and x.rukuCode like '%{$serachArea[rukuCode]}%'";
	// if ($serachArea['supplierId'] != '') $str .= " and x.supplierId = '{$serachArea[supplierId]}'";
	// //if ($serachArea['traderId'] != '') $str .= " and x.traderId = '{$serachArea[traderId]}'";
	// $str .= " order by rukuDate desc, rukuCode desc";
	// $pager = & new TMIS_Pager($str);
	// $rowset = $pager->findAll();
	// //dump($rowset);exit;
	// //$yuanliao_cgrk2product = & FLEA::getSingleton('Model_Yuanliao_Cgrk2product');
	// if (count($rowset)>0) foreach($rowset as & $value) {
	// $value['_edit'] = "<a href='".$this->_url('View',array('id'=>$value['id']))."' target='_blank' title='$title'>打印</a> | ";
	// if($value["guozhangId"]){
	// $tip = "ext:qtip='已过账禁止修改'";
	// $value['_edit'].="<a href='javascript:void(0)' style='color:black' $tip>修改</a> | ";
	// $value['_edit'].="<a $tip  >删除</a>";
	// }else{
	// $value['_edit'].="<a href='".$this->_url('Edit',array('id'=>$value['ruKuId']))."'>修改</a> | ";
	// $value['_edit'].="<a href='".$this->_url('Remove',array('id'=>$value['ruKuId']))."'  onclick=\"return confirm('确认删除吗?')\" >删除</a> |";
	// }
	// if($value['isGuozhang']==1){
	// $value['_bgColor']="lightgreen";
	// }
	// $value['cnt']=round($value['cnt'],2);
	// }
	// #合计行
	// $heji = $this->getHeji($rowset,array('cnt'),'_edit');
	// $rowset[] = $heji;
	// //dump($rowset);exit;
	// //左边信息
	// $arrFieldInfo = array(
	// "_edit"=>'操作',
	// "rukuDate" =>"入库日期",
	// 'rukuCode'=>'入库单号',
	// "compName" =>"供应商",
	// 'proCode'=>'产品编码',
	// 'proName'=>'品名',
	// 'zhonglei'=>'种类',
	// 'guige'=>'规格',
	// 'color'=>'颜色',
	// 'cnt'=>'数量',
	// //''=>'',
	// );
	// $smarty = & $this->_getView();
	// $smarty->assign('title','订单查询');
	// //$smarty->assign('pk', $this->_modelDefault->primaryKey);
	// $smarty->assign('arr_field_info',$arrFieldInfo);
	// //$smarty->assign('arr_field_info2',$arrField);
	// //$smarty->assign('sub_field','Products');
	// $smarty->assign('add_display', 'none');
	// $smarty->assign('arr_condition', $serachArea);
	// $smarty->assign('arr_field_value',$rowset);
	// $smarty->assign("page_info",$pager->getNavBar($this->_url($_GET['action']), $serachArea));
	// $smarty->assign('arr_js_css',$this->makeArrayJsCss(array('grid','calendar')));
	// $smarty->display('TableList.tpl');
	// //$smarty->display('TblListMore.tpl');
	// }
	// **********************************入库查询 end************************* 
	// //*********************************编辑 begin******************
	// function actionEdit(){
	// //echo '123';exit;
	// $arr= $this->_modelDefault->find(array('id'=>$_GET['id']));
	// //dump($arr);exit; 
	// foreach ($this->fldMain as $k=>&$v) {
	// $v['value'] = $arr[$k];
	// // $arr[$k] =
	// }
	// //dump($this->fldMain);exit;
	// $this->fldMain['rukuCode']['value'] = $arr['rukuCode'];
	// //$this->fldMain['orderId']['orderMemo'] = $arr['memo'];
	// $this->fldMain['rukuDate']['value'] = $arr['rukuDate'];
	// $this->fldMain['supplierId']['value'] = $arr['supplierId'];
	// $this->fldMain['songhuoCode']['value'] = $arr['songhuoCode'];
	// $this->fldMain['ruKuId']['value'] = $arr['id'];
	// $this->fldMain['isGuozhang']['value'] = $arr['isGuozhang'];
	// $this->fldMain['memo']['value'] = $arr['memo']; 
	// //生成供应商信息
	// $sql = "select * from jichu_supplier where 1";
	// $rowset = $this->_modelDefault->findBySql($sql);//dump( $rowset);exit;
	// foreach($rowset as & $v) {
	// $rowsSupplier[] = array('text'=>$v['compName'],'value'=>$v['id']);
	// }
	// $this->fldMain['supplierId']['options'] = $rowsSupplier; 
	// //生成库位信息
	// $sql = "select * from jichu_kuwei where 1";
	// $rowset = $this->_modelDefault->findBySql($sql);//dump( $rowset);exit;
	// foreach($rowset as & $v) {
	// $rowsKuwei[] = array('text'=>$v['kuweiName'],'value'=>$v['id']);
	// }
	// $this->fldMain['kuweiId']['options'] = $rowsKuwei; 
	// //加载库位信息的值
	// $this->fldMain['kuweiId']['value']=$arr['Products'][0]['kuweiId'];
	// $areaMain = array('title'=>'入库基本信息','fld'=>$this->fldMain);
	// //dump($areaMain);exit; 
	// //入库明细处理
	// //dump($arr['Products']);exit;
	// foreach($arr['Products'] as & $v) {
	// // dump($v);exit;
	// $sql = "select * from jichu_yuanliao where id='{$v['productId']}'";
	// $_temp = $this->_modelDefault->findBySql($sql);//dump($_temp);exit;
	// $v['proCode'] = $_temp[0]['proCode'];
	// $v['proName'] = $_temp[0]['proName'];
	// $v['guige'] = $_temp[0]['guige'];
	// //dump($_temp);exit;
	// //$v['money'] = round($v['danjia']*$v['cntYaohuo'],2);
	// // $v['']
	// }
	// //dump($arr['Products']);exit;
	// foreach($arr['Products'] as & $v) {
	// $temp = array();
	// foreach($this->headSon as $kk=>&$vv) {
	// $temp[$kk] = array('value'=>$v[$kk]);
	// }
	// $rowsSon[] = $temp;
	// }
	// $smarty = & $this->_getView();
	// $smarty->assign('areaMain',$areaMain);
	// $smarty->assign('headSon',$this->headSon);
	// $smarty->assign('rowsSon',$rowsSon);
	// $smarty->assign('rules',$this->rules);
	// $smarty->display('Main2Son/T.tpl');
	// }
	// //*********************************编辑 end******************
	// //**************************打印 begin*******************
	// function actionView(){
	// $yuanliao_cgrk2product = & FLEA::getSingleton('Model_Yuanliao_Cgrk2product');
	// $rowset=$yuanliao_cgrk2product->find($_GET['id']);
	// $smarty = & $this->_getView();
	// $smarty->assign("arr_field_value",$rowset);
	// $smarty->display('Yuanliao/RukuView.tpl');
	// }
	// //**************************打印 end*******************
}

?>