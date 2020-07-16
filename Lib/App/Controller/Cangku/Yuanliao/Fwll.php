<?php
FLEA::loadClass('Controller_Cangku_Chuku');
class Controller_Cangku_Yuanliao_Fwll extends Controller_Cangku_Chuku {
	function __construct() {
		// $this->_kuwei = '仓库';//库位
		$this->_state = '原料';
		$this->_head = 'FWLLA';//单据前缀
		$this->_kind='发外领料';
		// $this->_state = '切断后';

		parent::__construct();

		$oldHeadSon = $this->headSon;

		//浏览界面的字段
		$this->fldRight = array(
			"chukuDate" => "出库日期",

			// "kind" => "类别",
			'kuwei' => '库位',
			'orderCode' => '订单号',
			'jiagonghuName' => '领用单位',
			'proCode' => '产品编码',
			'proName' => '品名',
			'guige' => '规格',
			'dengji' => '等级',
			'color' => '颜色',
			'ganghao'=>'缸号(批号)',
			'cnt' => '领用数量(kg)',
			'cntJian' => '件数',
			'chukuCode' => array("text"=>'出库单号','width'=>150),
			'memo' => '备注'
		);

		unset($this->fldMain['depId']);
		$this->fldMain = $this->fldMain + array('jiagonghuId' => array('title' => '领料单位', 'type' => 'select', 'value' => '', 'model' => 'Model_Jichu_Jiagonghu'));
		// $this->fldMain = array(
		// 	'chukuCode' => array('title' => '出库单号', 'type' => 'text', 'readonly' => true, 'value' => $this->_getNewCode($this->_head, $this->_modelMain->qtableName, 'chukuCode')),
		// 	'chukuDate' => array('title' => '出库日期', "type" => "calendar", 'value' => date('Y-m-d')),
		// 	'kind' => array('title' => '出库类别', 'type' => 'text', 'value' => $this->_kind, 'readonly' => true),
		// 	'jiagonghuId' => array('title' => '领料单位', 'type' => 'select', 'value' => '', 'model' => 'Model_Jichu_Jiagonghu'),
		// 	'lingliaoren' => array('title' => '领料人', 'type' => 'text', 'value' => ''),
		// 	'kuwei' => array('title' => '库位选择', 'type' => 'select', 'value' => '','options'=>$rowsKuwei),
		// 	'memo' => array('title' => '出库备注', 'type' => 'textarea', 'disabled' => true, 'name' => 'chukuMemo'),
		// 	'id' => array('type' => 'hidden', 'value' => $_GET['id'],'name'=>'chukuId'),
		// );

		// /从表表头信息
		$this->headSon = array(
			'_edit' => array('type' => 'btBtnRemove', "title" => '+5行', 'name' => '_edit[]'),
			// 'plan2touliaoId' => array(
			// 	'title' => '生产计划', //表头文字
			// 	'type' => 'BtPopup',
			// 	'value' => '',
			// 	'name'=>'plan2touliaoId[]',
			// 	'text'=>'',//现在在文本框中的文字
			// 	'url'=>url('Shengchan_Plan','popupTl'),//弹出地址,回调函数在sonTpl中进行定义,主要是对缓存中的关联数组进行重写
			// 	'textFld'=>'planCode',//显示在text中的字段
			// 	'hiddenFld'=>'id',//显示在hidden控件中的字段
			// 	'inTable'=>1,
			// ),
			'ord2proId' => array(
				'type' => 'btPopup',
				"title" => '选择订单',
				'name' => 'ord2proId[]',
				'textFld' => 'orderCode',
				'hiddenFld' => 'ord2proId',
				'url'	=>url('Trade_Order','PopupLy'),
			),
			'productId' => array('type' => 'btProductpopup', "title" => '产品编码', 'name' => 'productId[]'),
			// 'dengji' => array('type' => 'btselect', 'title' => '等级', 'name' => 'dengji[]','options'=>$optionDengji),
			'proName' => array('type' => 'bttext', "title" => '品名', 'name' => 'proName[]', 'readonly' => true),
			'guige' => array('type' => 'bttext', "title" => '规格', 'name' => 'guige[]', 'readonly' => true),
			'color' => array('type' => 'bttext', "title" => '颜色', 'name' => 'color[]', 'readonly' => true),
			// 'unit'=>array('type'=>'bttext',"title"=>'单位','name'=>'unit[]','readonly'=>true),
			'ganghao' => array('type' => 'bttext', 'title' => '缸号(批号)', 'name' => 'ganghao[]'),
			'cnt' => array('type' => 'bttext', "title" => '领用数量(kg)', 'name' => 'cnt[]'),
			'cntJian' => array('type' => 'bttext', "title" => '件数', 'name' => 'cntJian[]'),
			//'danjia' => array('type' => 'bttext', "title" => '单价', 'name' => 'danjia[]'),
			//'money' => array('type' => 'bttext', "title" => '金额(元)', 'name' => 'money[]'),
			'memo' => array('type' => 'bttext', "title" => '备注', 'name' => 'memo[]'),
			// ***************如何处理hidden?
			'id' => array('type' => 'bthidden', 'name' => 'id[]'),
			'planId' => array('type' => 'bthidden', 'name' => 'planId[]'),
			// 'plan2proId' => array('type' => 'bthidden', 'name' => 'plan2proId[]'),
		);

	}

	function actionAdd(){
		$this->authCheck('3-1-8');
		parent::actionAdd();
	}

	function actionRight(){
		$this->authCheck('3-1-9');
		$curState=$this->_state;
		FLEA::loadClass('TMIS_Pager');
		// /构造搜索区域的搜索类型
		$serachArea = TMIS_Pager::getParamArray(array(
			'dateFrom' => date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-30, date("Y"))),
			'dateTo' => date("Y-m-d"),
			 'clientId' => '',
			// 'traderId' => '',
			// 'isCheck' => 0,
			'key' => '',
			'ganghao'=>'',
			'orderCode'=>'',
		));

		$sql = "select
			y.clientId,
			y.chukuCode,
			y.kuwei,
			y.chukuDate,
			y.memo as chukuMemo,
			y.orderId,
			y.kind,
			y.clientId,
			x.id,
			x.chukuId,
			x.pihao,
			x.ganghao,
			x.productId,
			x.cnt,
			x.cntJian,
			x.cntM,
			x.unit,
			x.cntOrg,
			x.money,
			x.memo,
			x.ord2proId,
			b.proCode,
			b.proName,
			b.guige,
			b.dengji,
			b.color,
			b.menfu,
			b.kezhong,
			b.kind as proKind,
			c.depName,
			d.compName as jiagonghuName,
			g.id as guozhangId,
			f.compName as kehuName,
			o.orderCode
			from cangku_common_chuku y
			left join cangku_common_chuku2product x on y.id=x.chukuId
			left join jichu_client f on y.clientId=f.id
			left join jichu_product b on x.productId=b.id
			left join jichu_department c on y.depId=c.id
			left join jichu_jiagonghu d on y.jiagonghuId=d.id
			left join caiwu_ar_guozhang g on g.chukuId=y.id
			left join shengchan_plan p on x.planId=p.id
			left join trade_order o on o.id=p.orderId
			where y.kind='{$this->_kind}' ";

		/* 入库分为：初始化，成品初始化，采购入库，生产入库；
		    出库分为：销售出库，领料出库，发外领料，其他出库
		** 子类大多都是调用父类的right方法，通过子类中的_kind字段，来区分是查询的哪一种出入库类型；对于原料的销售出库与成品的销售出库，通过再$curState即_state来区分
		*/

        //$sql .=" and y.kuwei in ('{$_arrKuwei}')";
        //用来区分是原料与成品的销售出库
		if($curState=='成品'&&$this->_kind=='销售出库'){
			$sql .=" and y.depId =0 ";
		}elseif($this->_kind=='发外领料'){
            //发外领料时，领料单位是加工户  ,如果depId <>0 将查询不到，因此区分开来
			$sql.=" and y.depId =0 ";
		}else{
			$sql .=" and y.depId <>0 ";
		}
		$sql .= " and y.chukuDate >= '{$serachArea['dateFrom']}' and y.chukuDate<='{$serachArea['dateTo']}'";
		if ($serachArea['key'] != '') $sql .= " and (y.chukuCode like '%{$serachArea['key']}%'
			or b.proName like '%{$serachArea['key']}%'
			or b.proCode like '%{$serachArea['key']}%'
			or b.guige like '%{$serachArea['key']}%')";
		if ($serachArea['clientId'] != '') $sql .= " and y.clientId = '{$serachArea[clientId]}'";
		if($serachArea['ganghao'] != ''){
			$sql .=" and x.ganghao like '%{$serachArea['ganghao']}%'";
		}
		if($serachArea['orderCode'] != ''){
			$sql .=" and o.orderCode like '%{$serachArea['orderCode']}%'";
		}
		$sql .= " order by  chukuCode desc,y.chukuDate desc";
		// dump($sql);exit;
		// dump($curState);exit;

		//得到总计
		$zongji = $this->getZongji($sql,array('cnt'=>'x.cnt','money'=>'x.money'));

		$pager = &new TMIS_Pager($sql);
		$rowset = $pager->findAll();
		// dump($rowset);exit;
		if (count($rowset) > 0) foreach($rowset as &$value) {
			//之前是按照生产计划做的生产领用，现在需求改为：目前因为人员问题，没有人在做生产计划这块，所以改为按订单来做生产领用
        	//这里的订单编号为目前按照订单来的订单编号
        	if($value['ord2proId']!=0){
        		  $sqlOrdCode = "select orderCode 
                           from trade_order o
                           left join trade_order2product p on o.id=p.orderId
                           where p.id='{$value['ord2proId']}'";
	              $orderCode = $this->_modelExample->findBySql($sqlOrdCode);
	              $value['orderCode'] = $orderCode[0]['orderCode'];
        	}
			//如果过账了，则禁止删除与修改
            if(!$value['guozhangId']){
                  //如果库位是成品仓库，则判断orderId是否为0
				if($value['kuwei']=='成品仓库'){
					//判断orderId是否为0, 为0表示不是根据订单的， 不为0表示是根据订单的
	                if($value['orderId']!=0){
	                    //判断是否是码单出库(码单是生产入库产生的，生产入库是根据订单来的)
	                    $str1="select id from cangku_common_chuku2product where chukuId='{$value['chukuId']}'";
	                    $res1=$this->_modelExample->findBySql($str1);
	                    $res1=join(',',array_col_values($res1,'id'));
// 	                    dump($res1);
	                    $str="select count(id) as id from cangku_madan where chuku2proId in ('{$res1}')";
	                    $res=$this->_modelExample->findBySql($str);
// 	                    dump($res);die;
	                    if($res[0]['id']>0){
					        //码单出库
					        $value['_edit'] = "<a href='" . $this->_url('View', array('id' => $value['chukuId'])) . "' target='_blank' title='$title'>打印</a>";
					        $value['_edit'] .= " "."<a href='" . url('Cangku_Chengpin_CkWithMadan','edit', array('id' => $value['chukuId'],'fromAction' => $_GET['action'])) . "'>修改</a>";
						    $value['_edit'] .= " ".$this->getRemoveHtml($value['chukuId']);
							$value['_edit'].=" "."<a href='".url('Cangku_Chengpin_Madan','SelexportChuku',array(
								'id'=>$value['chukuId']
							))."'>码单导出</a>";
				        }else{
				        	//表示是销售出库(根据订单)
				        	$value['_edit'] = "<a href='" . $this->_url('View', array('id' => $value['chukuId'])) . "' target='_blank' title='$title'>打印</a>";
					        $value['_edit'] .= " ".$this->getEditHtml($value['chukuId']);
						    $value['_edit'] .= " ".$this->getRemoveHtml($value['chukuId']);
				        }


				    }else{
				    	//表示是销售出库(不根据订单)
						$value['_edit'] = "<a href='" . $this->_url('View', array('id' => $value['chukuId'])) . "' target='_blank' title='$title'>打印</a>";
					    $value['_edit'] .= " "."<a href='" . url('Cangku_Chengpin_otherChuku','edit', array('id' => $value['chukuId'],'fromAction' => $_GET['action'])) . "'>修改</a>";
						$value['_edit'] .= " ".$this->getRemoveHtml($value['chukuId']);
				      }
			    }else{
				//表示不是成品，是原料
				$value['_edit'] = "<a href='" . $this->_url('View', array('id' => $value['chukuId'])) . "' target='_blank' title='$title'>打印</a>";
				$value['_edit'] .= " ".$this->getEditHtml($value['chukuId']);
			    $value['_edit'] .= " ".$this->getRemoveHtml($value['chukuId']);
			}
        }else{
            $value['_edit'] = "<a href='" . $this->_url('View', array('id' => $value['chukuId'])) . "' target='_blank' title='$title'>打印</a>";
            $tip = "ext:qtip='已过账'";
			$value['_edit'] .= " <a href='javascript:void(0)' style='color:black' $tip>修改</a>";
			$value['_edit'] .= " <a $tip  >删除</a>";
        }


			$value['cnt']=round($value['cnt'],2);
			$value['cntOrg']=round($value['cntOrg'],2);

			//得到客户
			if($value['clientId']) {
				$m = & FLEA::getSingleton('Model_Jichu_Client');
				$c = $m->find(array('id'=>$value['clientId']));
				$value['clientName'] = $c['compName'];
			}
		}
		// 合计行
		$heji = $this->getHeji($rowset, array('cnt'), '_edit');
		$rowset[] = $heji;
		// 显示信息
		$arrFieldInfo = array("_edit" => '操作')+$this->fldRight;

		$smarty = &$this->_getView();
		$smarty->assign('title', '订单查询');
		// $smarty->assign('pk', $this->_modelDefault->primaryKey);
		$smarty->assign('arr_field_info', $arrFieldInfo);
		$smarty->assign('add_display', 'none');
		$smarty->assign('arr_condition', $serachArea);
		$smarty->assign('arr_field_value', $rowset);
		$msg = "<font color='red'>数量总计:{$zongji['cnt']},金额总计:{$zongji['money']}</font>";
		$smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action']), $serachArea).$msg);
		$smarty->assign('arr_js_css', $this->makeArrayJsCss(array('grid', 'calendar')));
		$this->_beforeDisplayRight($smarty);
		$smarty->display('TableList.tpl');
	}
}