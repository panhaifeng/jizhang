<?php
//外协通知单控制器
FLEA::loadClass('Tmis_Controller');
class Controller_Shengchan_Waixie_JiaGongFei extends Tmis_Controller{
    var $title ;
	var $fldMain;
	var $rules; //表单元素的验证规则

    function __construct() {
		$this->_modelDefault = &FLEA::getSingleton('Model_Shengchan_Waixie');
		$this->_modelExample = &FLEA::getSingleton('Model_Caiwu_Yf_Guozhang');
		//$this->jichu_employ= &FLEA::getSingleton('Model_Jichu_Employ');
		// 定义模板中的主表字段
		 $this->fldMain = array(
			'danjia' => array('title' => '单价', 'type' => 'text', 'value' => ''),
			'id' => array('type' => 'hidden', 'value' => ''),
		);

		// 表单元素的验证规则定义
		$this->rules = array(
			'waixieCode' => 'required',
			'danjia' => 'required',
			'money' => 'required number',
			'supplierId' =>'required',
		);
	}

	function actionSave(){
		$ret=$this->_modelExample->find(array('id'=>$_POST['id']));
		$money=$_POST['danjia']*$ret['cnt'];
		$arr=array(
				'id'=>$_POST['id'],
				'danjia'=>$_POST['danjia'],
				'money'=>$money,
				'_money'=>$money,
		);
// 		dump($arr);exit;
		$id=$this->_modelExample->save($arr);
		if($_GET['kind']=='后整理加工'){
	    	$sqlfw = "update cangku_fawai2product set danjia='{$_POST['danjia']}',money='{$money}' where id='{$ret['ruku2ProId']}'";
	    	$this->_modelDefault->execute($sqlfw);
    	}else{
    		$sqlzz = "update shengchan_chanliang set danjia='{$_POST['danjia']}',money='{$money}' where id='{$ret['ruku2ProId']}'";
    		$this->_modelDefault->execute($sqlzz);
    	}
    	js_alert(null,'window.parent.showMsg("操作成功");',$this->_url($_POST['id']>0?'right':'add'));

	}

	function actionRight(){
// 		$this->authCheck('4-4-2');
        $title = '应付款查询';
		$tpl = 'TblList.tpl';
		FLEA::loadClass('TMIS_Pager');
		$arr = TMIS_Pager::getParamArray(array(
			'dateFrom'=>date('Y-m-d',mktime(0,0,0,date('m')-1,date('d'),date('Y'))),
			'dateTo'=>date('Y-m-d'),
			'jiagonghuId'=>'',
			// 'orderCode'=>'',
			// 'product'=>'',
			// 'guige'=>'',
			// 'orderId'=>'',
			'no_edit'=>'',
			'key'=>''
		));
		$sql="select x.*,z.compName,a.guige as guigeDesc,a.proName from caiwu_yf_guozhang x
			left join jichu_product a on a.id=x.productId
			left join jichu_jiagonghu z on z.id=x.supplierId
			where 1";

		/*if($arr['orderId']>0){
			$arr['dateFrom']='';
			$arr['dateTo']='';
			$sql.=" and x.orderId='{$arr['orderId']}'";
		}*/
		//2015-7-24 by jiang 如果是查询界面日期按入库日期搜索  如果是报表点击明细  日期按过账日期搜索

		//2016-02-26 by zhujunjie 查询界面日期按过账日期来排序
		if($arr['dateFrom']!=""){
			$sql.=" and x.guozhangDate >='{$arr['dateFrom']}' and x.guozhangDate<='{$arr['dateTo']}'";
		}

		if($arr['jiagonghuId']!='')$sql.=" and x.supplierId='{$arr['jiagonghuId']}'";
		if($arr['orderCode']!='')$sql.=" and y.orderCode like '%{$arr['orderCode']}%'";
		if($arr['product']!='')$sql.=" and a.product like '%{$arr['product']}%'";
		if($arr['guige']!='')$sql.=" and a.guige like '%{$arr['guige']}%'";
		if($arr['key']!='')$sql.=" and x.qitaMemo like '%{$arr['key']}%'";

		//按照过账日期降序
		$sql.=" and x.isJiagong=1 order by x.guozhangDate desc,x.id desc";
// 		dump($sql);exit;
		$pager =& new TMIS_Pager($sql);
		$rowset =$pager->findAll();
		$rowsetAll=$this->_modelExample->findBySql($sql);
 		//dump($rowsetAll);die;
		foreach($rowset as & $v) {
			if($v['kind']=='其它过账'){
				$v['_edit']= "<a href='".$this->_url('AddByOther',array(
					'id'=>$v['id'],
				))."' title='过账编辑'>修改</a>". '&nbsp;&nbsp;' . "<a href='".$this->_url('Remove',array(
					'id'=>$v['id'],
				))."' onclick='return confirm(\"您确认要删除吗?\")'>删除</a>";
			}else{
				$v['_edit']= "<a href='".$this->_url('Edit',array(
					'id'=>$v['id'],
				))."' title='过账编辑'>修改</a>". '&nbsp;&nbsp;' . "<a href='".$this->_url('Remove',array(
					'id'=>$v['id'],
					'ruku2ProId'=>$v['ruku2ProId'],
				))."' onclick='return confirm(\"您确认要删除吗?\")'>删除</a>";
			}


			//核销的情况下不能修改删除
			if($v['yifukuan']>0 || $v['yishouPiao']>0)$v['_edit']="<span title='已核销，禁止操作'>修改&nbsp;&nbsp;删除</span>";
		}
		$rowset[] = $this->getHeji($rowset, array('cnt','_money'), $_GET['no_edit']==1?'compName':'_edit');
		$zongji=$this->getHeji($rowsetAll, array('cnt','_money'), 'orderDate');
		$zongji['_edit']='<b>总计</b>';
		$rowset[]=$zongji;

		$arrField = array(
			"_edit"=>'操作',
			"compName"=>'加工户',
			"guozhangDate"=>'过账日期',
			"kind"=>'类别',
			// "rukuNum"=>'入库编号',
			"rukuDate"=>'入库日期',
			// "proName"=>'品名',
			// "guigeDesc"=>'规格',
			"qitaMemo"=>array('text'=>'描述','width'=>70),
			// "unit"=>array('text'=>'单位','width'=>70),
			"cnt"=>array('text'=>'数量','width'=>70),
			"danjia"=>array('text'=>'单价','width'=>70),
			"_money"=>array('text'=>'金额','width'=>70),
// 			"zhekouMoney"=>array('text'=>'折扣金额','width'=>70),
// 			"money"=>array('text'=>'应付金额','width'=>70),
			// "huilv"=>array('text'=>'汇率','width'=>70),
// 			"memo"=>"备注",
			"creater"=>"制单人",
		);

		$smarty = & $this->_getView();
		$smarty->assign('title', $title);
		$smarty->assign('arr_field_info',$arrField);
		$smarty->assign('arr_field_value',$rowset);
		$smarty->assign('arr_condition', $arr);
		$smarty->assign('add_display', 'none');
		$smarty->assign('page_info',$pager->getNavBar($this->_url($_GET['action'],$arr)));
		$smarty->display($tpl);
	}

	function actionRemove() {
	if($_GET['kind']=='后整理加工'){
	    	$sqlfw = "update cangku_fawai2product set danjia=0,money=0 where id='{$_GET['ruku2ProId']}'";
	    	$this->_modelDefault->execute($sqlfw);
    	}else{
    		$sqlzz = "update shengchan_chanliang set danjia=0,money=0 where id='{$_GET['ruku2ProId']}'";
    		$this->_modelDefault->execute($sqlzz);
    	}
		parent::actionRemove();
	}

    function actionAdd(){
    	$this->authCheck('4-4-1');
    	FLEA::loadClass('TMIS_Pager');
		$arr = TMIS_Pager::getParamArray(array(
			'dateFrom' => date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-30, date("Y"))),
			'dateTo' => date("Y-m-d"),
			'jiagonghuId'=>'',
			'gongxuId'=>'',
			'key' => '',
		));
		$sql.="select * from (";
		$sql.="select x.id,x.rukuDate,x.jiagonghuId,'后整理加工' as kind,y.memo,z.menfu,z.kezhong,
			y.id as fawaiId,y.unit,y.cnt,y.dengjidanjia,y.cntJian,z.proCode,z.proName,z.guige,z.color,g.name as gxName,j.compName,o.orderCode
			from cangku_fawai x
			left join cangku_fawai2product y on x.id=y.fawaiId
			left join jichu_product z on z.id=y.productId
			left join jichu_gongxu g on g.id=y.gongxuId
			left join jichu_jiagonghu j on j.id=x.jiagonghuId
			left join trade_order o on o.id=y.orderId
			where 1 and y.id not in (select ruku2ProId from caiwu_yf_guozhang where kind='后整理加工') and j.kind=0";
		if($arr['dateFrom']!=''){
			$sql.=" and x.rukuDate>='{$arr['dateFrom']}'";
		}
        if($arr['dateTo']!=''){
        	 $sql.=" and x.rukuDate<='{$arr['dateTo']}'";
        }
		if($arr['jiagonghuId']>0){
			$sql.=" and x.jiagonghuId='{$arr['jiagonghuId']}'";
		}
		if($arr['gongxuId']>0){
			$sql.=" and y.gongxuId='{$arr['gongxuId']}'";
		}
		if($arr['key']!=''){
			$sql.=" and (z.proCode like '%{$arr['key']}%'
						or z.guige like '%{$arr['key']}%'
						or z.color like '%{$arr['key']}%'
				)";
		}
		//2015-5-22 by jiang 加工费也包括织造加工
		$sql.=" union
			select 0 as id,x.chanliangDate as rukuDate,x.jiagonghuId,'织造加工' as kind,null as memo,z.menfu,z.kezhong,
					x.id as fawaiId,null as unit,x.cnt,x.dengjidanjia,x.memo as cntjian,z.proCode,z.proName,z.guige,z.color,null as gxName,j.compName,o.orderCode
				from shengchan_chanliang x
				left join jichu_product z on z.id=x.productId
				left join jichu_jiagonghu j on x.jiagonghuId=j.id
				left join trade_order2product op on op.id=x.ord2proId
				left join trade_order o on o.id=op.orderId
			where 1 and x.id not in (select ruku2ProId from caiwu_yf_guozhang where kind='织造加工') and j.kind=0";
		if($arr['jiagonghuId']>0){
			$sql.=" and x.jiagonghuId='{$arr['jiagonghuId']}'";
		}
		if($arr['key']!=''){
			$sql.=" and (z.proCode like '%{$arr['key']}%'
					or z.guige like '%{$arr['key']}%'
					or z.color like '%{$arr['key']}%'
					)";
			}
		if($arr['dateFrom']!=''){
			$sql.=" and x.chanliangDate>='{$arr['dateFrom']}'";
		}
        if($arr['dateTo']!=''){
        	 $sql.=" and x.chanliangDate<='{$arr['dateTo']}'";
        }
		$sql.=") as a where 1 order by rukuDate desc,kind,compName";
 		//dump($sql);exit;
		//查找计划
		$pager = &new TMIS_Pager($sql);
		$rowset = $pager->findAll();

		foreach($rowset as &$v) {
			$v['check']="<input type='checkbox' id='check[]' name='check[]'/>";
			$v['danjia']="<input type='text' id='danjia[]' name='danjia[]' value='{$v['dengjidanjia']}'/>
							<input type='hidden' id='id[]' name='id[]' value='{$v['fawaiId']}'/>
							<input type='hidden' id='kind[]' name='kind[]' value='{$v['kind']}'/>";
		    $v['guozhangDate'] = "<input type='text' id='guozhangDate[]' name='guozhangDate[]' value='".date('Y-m-d')."' onclick='calendar()' />";
		}
		$rowset[]=$this->getHeji($rowset,array('cnt','cntJian'),'kind');

		$smarty = &$this->_getView();
		// 左侧信息
		$arrFieldInfo = array(
			'check'=>array('text'=>"<input type='checkbox' id='checkedAll'/>过账",'width'=>60),
			//'kind'=>'类型',
			'rukuDate'=>'发生日期',
			'compName'=>'加工户',
			'gxName'=>array('text'=>'工序','width'=>50),
			//'orderCode'=>'订单号',
			'proCode'=>'产品编号',
			"proName" => "品种",
            'guige' => '规格',
            'menfu' => '门幅',
            'kezhong' => '克重',
			'memo' => '备注',
			//'color' => '颜色',
			'cntJian'=>array('text'=>'件数','width'=>50),
			'cnt'=>'数量',
			'guozhangDate'=>array('text'=>"过账日期<a href='javascript:void(0) name='setDate' id='setDate'>[批量设置日期]</a>",'width'=>160),
			'danjia'=>'单价',
// 			'unit'=>'单位',
			'creater'=>'操作人',
		);

		$smarty->assign('title', '计划查询');
		$smarty->assign('arr_field_info', $arrFieldInfo);
		$smarty->assign('add_display', 'none');
		$smarty->assign('arr_condition', $arr);
		$smarty->assign('arr_field_value', $rowset);
	    $other_url="<input type='button' id='save' name='save' value='过账'/>";
		$smarty->assign('other_url', $other_url);
		$smarty->assign('sonTpl', 'Shengchan/JiaGongFeiTpl.tpl');
		$smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action'], $arr)));
		$smarty->display('TblList.tpl');
    }

    function actionEdit(){
    	$arr=$this->_modelExample->find(array('id'=>$_GET['id']));
    	foreach ($this->fldMain as $k => &$v) {
    		$v['value'] = $arr[$k]?$arr[$k]:$v['value'];
    	}
    	$smarty = &$this->_getView();
		$smarty->assign('fldMain', $this->fldMain);
		$smarty->assign('rules', $this->rules);
		$smarty->assign('title', '加工费单价设置');
		$smarty->assign('sonTpl', 'Waixie/waixie.tpl');
		$smarty->display('Main/A1.tpl');
    }
    function actionSetDanjia(){
    	//dump($_GET['arr']);exit;
        //将前台传递的json转化成数组
    	$row = json_decode($_GET['arr'],true);
    	foreach ($row  as & $v) {
    		$sql="select * from (
			select x.id,x.rukuDate,x.jiagonghuId,'后整理加工' as kind,x.memo,
			y.id as fawaiId,y.unit,y.cnt,y.cntJian,z.proCode,z.proName,z.guige,z.color,g.name as gxName,j.compName,o.orderCode
			from cangku_fawai x
			left join cangku_fawai2product y on x.id=y.fawaiId
			left join jichu_product z on z.id=y.productId
			left join jichu_gongxu g on g.id=y.gongxuId
			left join jichu_jiagonghu j on j.id=x.jiagonghuId
			left join trade_order o on o.id=y.orderId
			where 1 and y.id not in (select ruku2ProId from caiwu_yf_guozhang where kind='后整理加工')
			union
			select 0 as id,x.chanliangDate as rukuDate,x.jiagonghuId,'织造加工' as kind,null as memo,
					x.id as fawaiId,null as unit,x.cnt,x.memo as cntjian,z.proCode,z.proName,z.guige,z.color,null as gxName,j.compName,o.orderCode
				from shengchan_chanliang x
				left join jichu_product z on z.id=x.productId
				left join jichu_jiagonghu j on x.jiagonghuId=j.id
				left join trade_order2product op on op.id=x.ord2proId
				left join trade_order o on o.id=op.orderId
			where 1 and x.id not in (select ruku2ProId from caiwu_yf_guozhang where kind='织造加工')
    		) as a where 1 and fawaiId='{$v['id']}' and kind='{$v['kind']}'";
    		$ret=$this->_modelExample->findBySql($sql);

    		//客户反映，保存的出错的情况-为了用户体验好点，用该界面 by-zhujunjie
    		if($ret[0]['fawaiId']<1){
    			js_alert("保存出错！",null,$this->_url('add'));
    		}

    		$money=$v['danjia']*$ret[0]['cnt'];
    		$arr=array(
    			'supplierId'=>$ret[0]['jiagonghuId']+0,
    			'rukuId'=>$ret[0]['id']+0,
    			'ruku2ProId'=>$ret[0]['fawaiId'],
    			'guozhangDate'=>$v['guozhangDate'],
    			'kind'=>$ret[0]['kind'].'',
    			'cnt'=>$ret[0]['cnt']+0,
    			'unit'=>$ret[0]['unit'].'',
    			'productId'=>$ret[0]['productId']+0,
    			'danjia'=>$v['danjia']+0,
    			'money'=>$money+0,
    			'qitaMemo'=>$ret[0]['proCode'].' '.$ret[0]['proName'].' '.$ret[0]['guige'].' '.$ret[0]['color'].'',
    			'creater'=>$_SESSION['REALNAME'].'',
    			'rukuDate'=>$ret[0]['rukuDate'],
    			'_money'=>$money+0,
    			'isJiagong'=>1,
    	    );
    		$id=$this->_modelExample->save($arr);
    		if($v['kind']=='后整理加工'){
		    	$sqlfw = "update cangku_fawai2product set danjia='{$v['danjia']}',money='{$money}' where id='{$v['id']}'";
		    	$this->_modelDefault->execute($sqlfw);
	    	}else{
	    		$sqlzz = "update shengchan_chanliang set danjia='{$v['danjia']}',money='{$money}' where id='{$v['id']}'";
	    		$this->_modelDefault->execute($sqlzz);
	    	}
    	}

    	js_alert(null,'window.parent.showMsg("操作成功");',$this->_url('add'));
    }

    /**
     * ps ：设置过账时间
     * Time：2015/07/22 17:01:34
     * @author jin
    */
     function actionSetDate(){
        $smarty = & $this->_getView();
        $smarty->display('Shengchan/setDate.tpl');
    }

    function actionReport(){
    	// $this->authCheck('4-1-7');
    	$tpl = $_GET['print']?'Print.tpl':'TblList.tpl';
    	FLEA::loadclass('TMIS_Pager');
    	$arr=TMIS_Pager::getParamArray(array(
    			'dateFrom'=>date('Y-m-01'),
    			'dateTo'=>date('Y-m-d'),
    			'jiagonghuId'=>'',
    	));
    	//得到期初发生
    	//应付款表中查找,日期为期初日期
    	//按照加工商汇总
    	$sql="select sum(money) as fsMoney,supplierId from caiwu_yf_guozhang where guozhangDate < '{$arr['dateFrom']}' and isJiagong=1";
    	if($arr['jiagonghuId']!=''){
    		$sql.=" and supplierId='{$arr['jiagonghuId']}'";
    	}
    	$sql.=" group by supplierId order by supplierId";
    	$rowset = $this->_modelExample->findBySql($sql);
    	foreach($rowset as & $v){
    		//期初金额
    		$row[$v['supplierId']]['initMoney']=$v['fsMoney']+0;//期初余额
    		$row[$v['supplierId']]['initIn']=$v['fsMoney']+0;
    	}
    	//得到起始日期前的收款金额
    	//从付款表中查找
    	//按照加工商汇总
    	$sqlIncome = "SELECT sum(money) as FukuMoney,supplierId FROM `caiwu_yf_fukuan` where  fukuanDate < '{$arr['dateFrom']}' and kind=1";
    	if($arr['jiagonghuId']!=''){
    		$sqlIncome.=" and supplierId='{$arr['jiagonghuId']}'";
    	}
    	$sqlIncome.=" group by supplierId order by supplierId";
    	$rowset = $this->_modelExample->findBySql($sqlIncome);
    	foreach($rowset as & $v){
    		//期初金额
    		$row[$v['supplierId']]['initMoney']=round($row[$v['supplierId']]['initMoney']-$v['FukuMoney']+0,2);//期初余额=期初发生-期初已付款
    		$row[$v['supplierId']]['initOut']=$v['FukuMoney'];
    	}

    	//得到本期的已付款
    	//付款表中查找
    	//按照加工户汇总
    	$str="SELECT sum(money) as moneyfukuan,supplierId from caiwu_yf_fukuan where 1 and kind=1";
    	if($arr['dateFrom']!=''){
    		$str.=" and fukuanDate>='{$arr['dateFrom']}' and fukuanDate<='{$arr['dateTo']}'";
    	}
    	if($arr['jiagonghuId']!=''){
    		$str.=" and supplierId='{$arr['jiagonghuId']}'";
    	}
    	$str.=" group by supplierId order by supplierId";
    	//echo $str;exit;
    	$fukuan=$this->_modelExample->findBySql($str);
    	foreach($fukuan as & $v1){
    		$row[$v1['supplierId']]['fukuanMoney']=$v1['moneyfukuan']+0;
    	}

    	//得到本期发生
    	//应付款表中查找
    	//按照加工户汇总
    	$sql="select sum(money) as fsMoney,supplierId from caiwu_yf_guozhang where 1 and isJiagong=1";
    	if($arr['dateFrom']!=''){
    		$sql.=" and guozhangDate>='{$arr['dateFrom']}' and guozhangDate<='{$arr['dateTo']}'";
    	}
    	if($arr['jiagonghuId']!=''){
    		$sql.=" and supplierId='{$arr['jiagonghuId']}'";
    	}
    	$sql.=" group by supplierId order by supplierId";
    	$rowset = $this->_modelExample->findBySql($sql);
    	foreach($rowset as & $v2){
    		$row[$v2['supplierId']]['fsMoney']=$v2['fsMoney']+0;
    	}

    	//得到本期发票
    	$str1="SELECT sum(money) as faPiaoMoney,supplierId FROM `caiwu_yf_fapiao` where 1 and kind=1";
    	if($arr['dateFrom']!=''){
    		$str1.=" and fapiaoDate>='{$arr['dateFrom']}' and fapiaoDate<='{$arr['dateTo']}'";
    	}
    	if($arr['jiagonghuId']!=''){
    		$str1.=" and supplierId='{$arr['jiagonghuId']}'";
    	}
    	$str1.=" group by supplierId order by supplierId";
    	$fukuan=$this->_modelExample->findBySql($str1);
    	foreach ($fukuan as $v2){
    		$row[$v2['supplierId']]['faPiaoMoney']=$v2['faPiaoMoney']+0;
    	}
    	//dump($row);exit;
    	$mClient=& FLEA::getSingleton('Model_jichu_Jiagonghu');
    	if(count($row)>0){
    		foreach($row as $key => & $v){
    			$c=$mClient->find(array('id'=>$key));
    			$v['supplierId']=$key;
    			$v['compName']=$c['compName'];

    			$v['weifuMoney']=$v['initMoney']+$v['fsMoney']-$v['fukuanMoney'];
    		}
    	}

    	$heji=$this->getHeji($row,array('initMoney','fukuanMoney','faPiaoMoney','weifuMoney','fsMoney'),'compName');
    	foreach($row as $key=>& $v){
    		$v['fukuanMoney']="<a href='".url('Shengchan_Waixie_Fukuan','right',array(
    				'jiagonghuId'=>$v['supplierId'],
    				'dateFrom'=>$arr['dateFrom'],
    				'dateTo'=>$arr['dateTo'],
    				'width'=>'700',
    				'no_edit'=>1,
    				'TB_iframe'=>1,))."' class='thickbox' title='付款明细'>".$v['fukuanMoney']."</a>";
    		$v['faPiaoMoney']="<a href='".url('Shengchan_Waixie_Fapiao','right',array(
    				'supplierId'=>$v['supplierId'],
    				'dateFrom'=>$arr['dateFrom'],
    				'dateTo'=>$arr['dateTo'],
    				'width'=>'700',
    				'no_edit'=>1,
    				'TB_iframe'=>1,))."' class='thickbox' title='收票明细'>".$v['faPiaoMoney']."</a>";
    		$v['fsMoney']="<a href='".$this->_url('right',array(
    				'jiagonghuId'=>$v['supplierId'],
    				'dateFrom'=>$arr['dateFrom'],
    				'dateTo'=>$arr['dateTo'],
    				'width'=>'700',
    				'no_edit'=>1,
    				'TB_iframe'=>1,))."' class='thickbox' title='应付明细'>".$v['fsMoney']."</a>";

    		//查看对账单
    		// $v['duizhang']="<a href='".$this->_url('Duizhang',array(
    		// 		'dateFrom'=>$arr['dateFrom'],
    		// 		'dateTo'=>$arr['dateTo'],
    		// 		'supplierId'=>$v['supplierId'],
    		// 		'no_edit'=>1,
    		// ))."' target='_blank'>查看</a>";
    	}
    	$row[]=$heji;

    	$arrFiled=array(
    			'compName'=>"供应商",
    			"initMoney" =>"期初余额",
    			"fsMoney" =>"本期发生",
    			"fukuanMoney" =>"本期付款",
    			"weifuMoney" =>"本期结余",
    			"faPiaoMoney" =>"本期收票",
    			// 'duizhang'=>'对账单',
    			// 'hexiao'=>'核销',
    	);
    	if($_GET['print']){
    		unset($arrFiled['duizhang']);
    	}
    	$smarty = & $this->_getView();
    	$smarty->assign('title', $title);
    	$smarty->assign('arr_field_info',$arrFiled);
    	$smarty->assign('arr_condition',$arr);
    	$smarty->assign('add_display','none');
    	$smarty->assign('arr_field_value',$row);
    	$smarty->assign('heji',$heji);
    	$smarty->assign('print_href',$this->_url($_GET['action'],array(
    			'print'=>1
    	)));
    	$smarty->assign('title','应付款报表');
    	if($_GET['print']) {
    		//设置账期显示
    		$smarty->assign('arr_main_value',array(
    				'账期'=>$arr['dateFrom'] . ' 至 ' . $arr['dateTo']
    		));
    	}
    	$smarty->display($tpl);
    }

    /**
     * ps ：其他过账
     * Time：2015/07/23 08:56:31
     * @author jin
    */
    function actionAddByOther(){
    	$this->authCheck('4-4-8');
    	//搭建过账界面
		$fldMain = array(
				'guozhangDate' => array('title' => '过账日期', "type" => "calendar", 'value' => date('Y-m-d')),
				'supplierId' => array('title' => '应付对象', 'type' => 'select', 'value' => '','model'=>'Model_Jichu_Jiagonghu'),
				'qitaMemo' => array('title' => '过账原因', 'type' => 'text', 'value' => ''),
				'rukuDate' => array('title' => '发生日期', 'type' => 'calendar', 'value' => date('Y-m-d'),'readonly'=>true),
				'money' => array('title' => '入账金额', 'type' => 'text', 'value' => '','addonEnd'=>'元'),
				'memo' => array('title' => '备注', 'type' => 'textarea', 'value' => ''),
				'id' => array('type' => 'hidden', 'value' => ''),
				'creater' => array('type' => 'hidden', 'value' => $_SESSION['REALNAME']),
				'kind' => array('type' => 'hidden', 'value' => '其它过账'),
				'isJiagong' => array('type' => 'hidden', 'value' => '1'),
		);

		if($_GET['id']>0){
			$row = $this->_modelExample->find(array('id' => $_GET['id']));
			$fldMain = $this->getValueFromRow($fldMain, $row);
		}

		if($_GET['fromAction']=='')$_GET['fromAction']=$_GET['action'];

		// dump($fldMain);exit;
		$smarty = &$this->_getView();
		$smarty->assign('fldMain', $fldMain);
		$smarty->assign('rules', $this->rules);
		$smarty->assign('title', '过账信息编辑');
		$smarty->assign('form', array('action'=>'SaveOther'));
		$smarty->display('Main/A1.tpl');
    }

    /**
     * ps ：其他入库保存
     * Time：2015/07/23 09:08:55
     * @author jin
    */
    function actionSaveOther(){
    	$_POST['_money']=$_POST['money'];
    	$this->_modelExample->save($_POST);
    	js_alert(null,'window.parent.showMsg("操作成功");',$this->_url($_POST['id']>0?'right':'AddByOther'));
    }

}

?>