<?php
FLEA::loadClass('Controller_Cangku_Chuku');
class Controller_Cangku_Yuanliao_Xsck extends Controller_Cangku_Chuku {
	var $fldMain;
	var $headSon;
	var $rules; //表单元素的验证规则
	var $_modelDefault;
	var $_modelExample;
	var $_modelMain;
	var $_modelSon;
	var $_head;//单据前缀
	var $_kind;//入库类型
	function __construct() {
		$this->_head = 'XSCKA';//单据前缀
		$this->_kind='销售出库';
		$this->_state = '原料';


		parent::__construct();
        $this->_arrKuwei = array('坯纱仓库','色纱仓库','其他仓库','氨纶仓库','丝仓库','棉纱库位');
		$this->fldRight = array(
			"chukuDate" => "出库日期",
			'chukuCode' => '出库单号',
			"kuwei" => "库位",
			// "state" => "状态",
			'clientName' => '客户',
			'proCode' => '产品编码',
			'proName' => '品名',
			'guige' => '规格',
			'dengji' => '等级',
			'color' => '颜色',
			'ganghao'=>'缸号(批号)',
			// 'dengji' => '等级',
			'cnt' => '数量(kg)',
			'danjia' => '单价',
			'money' => '金额',
			'cntJian' => '件数',
		);

		$mainOld = $this->fldMain;
		$sonOld = $this->headSon;
		$this->fldMain = array(
			//'orderId' => array('title' => '相关订单', "type" => "orderpopup", 'value' => ''),
			'clientId' => array('title' => '客户', "type" => "clientpopup", 'value' => ''),

			'depName' => array('title' => '部门名称', 'type' => 'text', 'value' => '销售部', 'model' => 'Model_Jichu_Department','readonly' => true)
		) + $this->fldMain;

		$this->headSon = array(
			'_edit' => $sonOld['_edit'],
			'productId' => $sonOld['productId'],
			'ganghao' => $sonOld['ganghao'],
			'cnt' => $sonOld['cnt'],
			'cntJian' => $sonOld['cntJian'],
			'danjia' => array('type' => 'bttext', "title" => '单价', 'name' => 'danjia[]'),
			'money' => array('type' => 'bttext', "title" => '金额(元)', 'name' => 'money[]'),
			'memo' => $sonOld['memo'],
			// ***************如何处理hidden?
			'id' => $sonOld['id'],
			'ord2proId' => array('type' => 'bthidden', 'name' => 'ord2proId[]'),
		);



	}

	//销售出库要选择相关订单，所以需要重新定义onorderSel函数
	function actionAdd($Arr) {
		$this->authCheck('3-1-4');
		// 主表信息字段
		$fldMain = $this->fldMain;

		$headSon = $this->headSon;
		// 从表信息字段,默认5行
		for($i = 0;$i < 5;$i++) {
			$rowsSon[] = array();
		}
		// 主表区域信息描述
		$areaMain = array('title' => '出库基本信息', 'fld' => $fldMain);
		// 从表区域信息描述
		$smarty = &$this->_getView();
		$smarty->assign('areaMain', $areaMain);
		$smarty->assign('headSon', $headSon);
		$smarty->assign('rowsSon', $rowsSon);
		$smarty->assign('rules', $this->rules);
		$smarty->assign('sonTpl', 'Cangku/Yuanliao/jsXsck.tpl');
		$smarty->display('Main2Son/T1.tpl');
	}

    function actionRight(){
    	$this->authCheck('3-1-5');
    	// DUMP($this->_arrKuwei);exit;
		//处理$this->_arrKuwei，用来是区分是成品销售出库，还是色坯纱销售出库
		$curState=$this->_state;
		// $this->authCheck('3-5');
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
			f.compName as kehuName
			from cangku_common_chuku y
			left join cangku_common_chuku2product x on y.id=x.chukuId
			left join jichu_client f on y.clientId=f.id
			left join jichu_product b on x.productId=b.id
			left join jichu_department c on y.depId=c.id
			left join jichu_jiagonghu d on y.jiagonghuId=d.id
			left join caiwu_ar_guozhang g on g.chuku2proId=x.id
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
		$sql .= " order by  chukuCode desc,y.chukuDate desc";
		// dump($sql);exit;
		// dump($curState);exit;

		//得到总计
		$zongji = $this->getZongji($sql,array('cnt'=>'x.cnt','money'=>'x.money'));
		if($_GET['export']!=1) {
            $pager = &new TMIS_Pager($sql);
            $rowset = $pager->findAll();
        }else{
            $this->authCheck('100-8');
            $rowset = $this->_modelExample->findBySql($sql);
        }
		// dump($rowset);exit;
		if (count($rowset) > 0) foreach($rowset as &$value) {
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

		$title = '色坯纱仓库销售出库查询';
		$smarty = &$this->_getView();
		$smarty->assign('title', $title);
		// $smarty->assign('pk', $this->_modelDefault->primaryKey);
		$smarty->assign('arr_field_info', $arrFieldInfo);
		$smarty->assign('add_display', 'none');
		$smarty->assign('arr_condition', $serachArea);
		$smarty->assign('arr_field_value', $rowset);
		$msg = "<font color='red'>数量总计:{$zongji['cnt']},金额总计:{$zongji['money']}</font>";
		$smarty->assign('arr_js_css', $this->makeArrayJsCss(array('grid', 'calendar')));
		$this->_beforeDisplayRight($smarty);

		$smarty->assign('fn_export',$this->_url($_GET['action'],array('export'=>1)+$serachArea));
        if($_GET['export']!=1) {
			$smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action']), $serachArea).$msg);
            $smarty->display('TableList.tpl');
            exit;
        }
        $filename = $title.'-'.time();
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/download");
        header("Content-Disposition: attachment;filename=".$filename.".xls");
        header("Content-Transfer-Encoding: binary");
        $smarty->display('Export2Excel2.tpl');
    }

}