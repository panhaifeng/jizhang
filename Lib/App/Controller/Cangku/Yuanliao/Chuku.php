<?php
FLEA::loadClass('Controller_Cangku_Chuku');
class Controller_Cangku_Yuanliao_Chuku extends Controller_Cangku_Chuku {
	function __construct() {
		// $this->_kuwei = '原料仓库';//库位
		$this->_state = '原料';
		$this->_head = 'LLCKA';//单据前缀
		$this->_kind='领料出库';
		$this->_arrKuwei = array('坯纱仓库','色纱仓库');
		parent::__construct();
		// dump($this->_arrKuwei);exit;
	}

	function actionReport(){
		$this->authCheck('3-1-12');
        $_GET['exportName'] = '原料出库';

        FLEA::loadClass("TMIS_Pager");
        $arr = &TMIS_Pager::getParamArray(array(
            "dateFrom" => date('Y-m-01'),
            "dateTo" => date('Y-m-d'),
            // "proCode" =>"",
            // "proName"=>"",
            "kuweiName"  =>'',
            "key"=>'',
            "color"=>'',
            "ganghao"=>'',
            "guige"=>'',
        ));

        $strC = '';
        $strCon = '';

        //处理库位
        // $strKuwei = join("','",$this->_arrKuwei);

        //找到所有属于原料的库位
        $str="select kuweiName from jichu_kuwei where type='原料'";
        $res=$this->_modelExample->findBySql($str);
        $strKuwei=join("','",array_col_values($res,'kuweiName'));

        $strCon .= " and kuwei in ('{$strKuwei}')";

        if($arr['kuweiName']!='') $strCon.=" and kuwei='{$arr['kuweiName']}'";
        if ($arr['key'] != '') $strC.= " and z.guige like'%{$arr['key']}%'";
        if ($arr['color'] != '') $strC.= " and z.color like'%{$arr['color']}%'";
        if ($arr['guige'] != '') $strC.= " and z.guige like'%{$arr['guige']}%'";
        if ($arr['ganghao'] != '') $strC.= " and x.ganghao like'%{$arr['ganghao']}%'";

        $strGroup="kuwei,productId,ganghao";
        $sqlUnion="select {$strGroup},cntJian,
                        sum(cntFasheng) as cntInit,
                        sum(moneyFasheng) as moneyInit,
                        0 as cntRuku,
                        0 as moneyRuku,
                        0 as cntChuku,
                        0 as cntSaleChuku,
                        0 as moneyChuku
                    from `cangku_common_kucun`
                    where dateFasheng<'{$arr['dateFrom']}'
                     {$strCon}
                     group by {$strGroup}
              union
                    select {$strGroup},cntJian,
                            0 as cntInit,
                            0 as moneyInit,
                            sum(cntFasheng) as cntRuku,
                            sum(moneyFasheng) as moneyRuku,
                            0 as cntChuku,
                            0 as cntSaleChuku,
                            0 as moneyChuku
                    from `cangku_common_kucun`
                    where dateFasheng>='{$arr['dateFrom']}' and dateFasheng<='{$arr['dateTo']}'
                     and rukuId>0  {$strCon}
                     group by {$strGroup}
              union
                    select {$strGroup},cntJian,
                            0 as cntInit,
                            0 as moneyInit,
                            0 as cntRuku,
                            0 as moneyRuku,
                            sum(cntFasheng*-1) as cntChuku,
                            sum(if('销售出库'=kind,cntFasheng*-1,0)) as cntSaleChuku,
                            sum(moneyFasheng*-1) as moneyChuku
                    from `cangku_common_kucun`
                    where dateFasheng>='{$arr['dateFrom']}'
                      and dateFasheng<='{$arr['dateTo']}'
                      and chukuId>0
                      {$strCon}
                    group by {$strGroup}";

        $sql="select {$strGroup},
                    z.guige,
                    sum(cntInit) as cntInit,
                    sum(moneyInit) as moneyInit,
                    sum(cntRuku) as cntRuku,
                    sum(moneyRuku) as moneyRuku,
                    sum(cntChuku) as cntChuku,
                    sum(cntSaleChuku) as cntSaleChuku,
                    sum(moneyChuku) as moneyChuku
              from ({$sqlUnion}) as x
              left join jichu_product z on x.productId=z.id
              where 1 {$strC}
              group by {$strGroup}
              having sum(cntInit)<>0 or sum(moneyInit)<>0
                  or sum(cntRuku)<>0 or sum(moneyRuku)<>0
                  or sum(cntChuku)<>0 or sum(moneyChuku)<>0";

        if(!$_GET['export']) {
            $pager = &new TMIS_Pager($sql);
            $rowset = $pager->findAll();
        }else{
            $rowset = $this->_modelExample->findBySql($sql);
        }

        //得到合计信息
        foreach($rowset as &$v) {
            // dump($v);exit;
            $sql = "select * from jichu_product where id='{$v['productId']}'";
            $temp = $this->_modelMain->findBySql($sql);
            $v['proCode'] = $temp[0]['proCode'];
            $v['proName'] = $temp[0]['proName'];
            $v['guige'] = $temp[0]['guige'];
            $v['color'] = $temp[0]['color'];

            // $sql = "select * from jichu_supplier where id='{$v['supplierId']}'";
            // $temp = $this->_modelMain->findBySql($sql);
            // $v['supplierName'] = $temp[0]['compName'];
            $v['cntKucun'] = round($v['cntInit'] + $v['cntRuku'] - $v['cntChuku'], 2);

            $v['cntUnSaleChuku']  = $v['cntChuku'] - $v['cntSaleChuku'];

            //本期入库和本期出库点击可看到明细
        }
        $heji = $this->getHeji($rowset,array('cntInit','cntRuku','cntChuku','cntSaleChuku','cntUnSaleChuku','cntKucun'),'kuwei');
        if($_GET['export']) $heji['kuwei'] = "合计";


        $canChange= $this->authCheck('100-2',true);
        $canMove=$this->authCheck('100-3',true);

        if(!$_GET['export']){
            //出入库数量形成可弹出明细的链接
            foreach($rowset as & $v) {
                // $cName = str_replace('chuku', 'ruku', strtolower($_GET['controller']));
                if($v['cntRuku']>0){
                    $v['cntRuku'] = "<a href='".url('Cangku_Yuanliao_Ruku','popup',array(
                            'dateFrom'=>$arr['dateFrom'],
                            'dateTo'=>$arr['dateTo'],
                            'kuwei'=>$v['kuwei'],
                            'productId'=>$v['productId'],
                            'pihao'=>$v['pihao'],
                            'ganghao'=>$v['ganghao']
                            //'state'=>$this->_state,
                            // 'supplierId'=>$v['supplierId'],
                        ))."' target='_blank'>{$v['cntRuku']}</a>";
                }

                if($v['cntChuku']<>0){
                    $v['cntChuku'] = "<a href='".url("Cangku_Yuanliao_Chuku",'popup',array(
                            'dateFrom'=>$arr['dateFrom'],
                            'dateTo'=>$arr['dateTo'],
                            'kuwei'=>$v['kuwei'],
                            'pihao'=>$v['pihao'],
                            'productId'=>$v['productId'],
                            'ganghao'=>$v['ganghao']
                            //'state'=>$this->_state,
                            // 'supplierId'=>$v['supplierId'],
                        ))."' target='_blank'>{$v['cntChuku']}</a>";
                }

                $tkArr = array(
                    'kuwei'     => $v['kuwei'],
                    'productId' => $v['productId'],
                    'proCode'   => $v['proCode'],
                    'pihao'     => $v['pihao'],
                    'ganghao'   => $v['ganghao'],
                    'dateFrom'  => $arr['dateFrom'],
                    'dateTo'    => $arr['dateTo'],
                );
                $v['_edit'] = '';
                if($canChange){
                    $v['_edit'] .= "<a href='".$this->_url('ChangeKucun',$tkArr)."' class='thickbox' title='调整库存'>调整库存</a>&nbsp;&nbsp;";
                }
                if($canMove){
                    $v['_edit'] .= "<a href='".url('Cangku_Yuanliao_Kcdb','DiaoBo',$tkArr)."' class='thickbox' title='库存调拨'>库存调拨</a>";
                }


            }
        }

        $rowset[] = $heji;
        // 显示信息
        $arrFieldInfo = array(
            'kuwei' => '库位',
            //'state' => '状态',
            "proCode" => "产品编码",
            'proName' => '品名',
            "guige" => "规格",
            "color" => "颜色",
            // "dengji" => "等级",
            // "supplierName" => '供应商',
            // "pihao"=>'批号',
            'ganghao'=>'缸号(批号)',
            'cntInit' => '期初(kg)',
            'cntJian' => '件数',
            'cntRuku' => '本期入库(kg)',
            //'cntChuku' => '本期出库',
            'cntSaleChuku'  => '销售出库(kg)',
            'cntUnSaleChuku' => '本期出库(非销售)(kg)',
            'cntKucun' => '余存(kg)',
            '_edit'=>'操作',
            // 'cnt'=>'数量',
        );
        if(!$canChange && !$canMove){
            unset($arrFieldInfo['_edit']);
        }
        $smarty = &$this->_getView();
        $smarty->assign('title', '收发存报表');
        // $smarty->assign('pk', $this->_modelDefault->primaryKey);
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
        //得到总计库存
        $sql = "select
        sum(cntFasheng) as cnt,
        sum(moneyFasheng) as money
        from `cangku_common_kucun`
        where dateFasheng<='{$arr['dateTo']}' {$strCon}";
        $zjKc = $this->_modelMain->findBySql($sql);
        //得到总计入库数
        $sql = "select
        sum(cntFasheng) as cnt
        from `cangku_common_kucun`
        where dateFasheng<='{$arr['dateTo']}' {$strCon} and rukuId>0";
        $zjRk = $this->_modelMain->findBySql($sql);
        //得到总计出库数
        $sql = "select
        sum(cntFasheng)*-1 as cnt
        from `cangku_common_kucun`
        where dateFasheng<='{$arr['dateTo']}' {$strCon} and chukuId>0";
        $zjCk = $this->_modelMain->findBySql($sql);
        // dump($sql);exit;
        if(isset($_GET['exportName'])){
            $smarty->assign('fn_export',$this->_url($_GET['action'],$arr+array('export'=>1)));
        }
        $smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action']), $arr)."<font color='red'>入库总计:{$zjRk[0]['cnt']};出库总计:{$zjCk[0]['cnt']};库存总计:{$zjKc[0]['cnt']}</font>");
        $smarty->assign('arr_js_css', $this->makeArrayJsCss(array('grid', 'calendar')));
        $smarty->display('TableList.tpl');
	}

	/**
	 * @desc ：调整库存
	 * Time：2016/12/19 13:01:25
	 * @author Wuyou
	*/
	function actionChangeKucun(){
        // dump($_GET);exit;
		$aRow = $_GET;
		// 取得库存
		$strCon = '';
		$strCon .= " and kuwei='{$aRow['kuwei']}'";
        $strCon .= " and productId = '{$aRow['productId']}'";
        $strCon .= " and pihao ='{$aRow['pihao']}'";
        $strCon .= " and ganghao = '{$aRow['ganghao']}'";
		$strCon .= " and dateFasheng<='{$aRow['dateTo']}'";
		$sql = "SELECT sum(cntFasheng) as cntKucun FROM cangku_common_kucun WHERE 1 {$strCon}";
	    $temp = $this->_modelExample->findBySql($sql);
	    $aRow['cntKucun'] = $temp[0]['cntKucun'];
        // dump($aRow);exit();
        $smarty = & $this->_getView();
        $smarty->assign('aRow',$aRow);
        $smarty->display('Cangku/Yuanliao/tzKucun.tpl');
	}

	/**
	 * @desc ：库存调整记录保存
	 * Time：2016/12/19 13:09:17
	 * @author Wuyou
	*/
	function actionSaveTzKucun(){
        $tzCnt = $_POST['cntKucun'] - $_POST['cntReal'];
        $Products[] = array(
  			'productId' => $_POST['productId'],
  			'pihao' => $_POST['pihao'],
  			'ganghao' => $_POST['ganghao'],
  			'cnt' => $tzCnt,
  			'memo' => "库存由{$_POST['cntKucun']}调为{$_POST['cntReal']}".($_POST['memo']!=''?(" ,".$_POST['memo']):''),
        );
        $arr = array(
  			'kind' => '调库出库',
  			'kuwei' => $_POST['kuwei'],
  			'chukuDate' => date('Y-m-d'),
  			'chukuCode' => $this->_getNewCode('TZCKA', $this->_modelMain->qtableName, 'chukuCode'),
  			'creater' => $_SESSION['REALNAME'].'',
  			'Products' => $Products
        );
        // dump($arr);exit;
        if($tzCnt!=0) $itemId = $this->_modelExample->save($arr);
        js_alert(null,'window.parent.showMsg("调整成功!");',$this->_url('Report'));
	}

	/**
	 * @desc ：调整明细列表
	 * Time：2016/12/19 13:33:03
	 * @author Wuyou
	*/
	function actionListChangeKucun(){
		$title = '库存调整列表';
        ///////////////////////////////模板文件
        $tpl = 'TableList.tpl';
        ///////////////////////////////模块定义
		$this->authCheck('3-1-14');
        FLEA::loadClass('TMIS_Pager');
        $arr = TMIS_Pager::getParamArray(array(
            "dateFrom" => date('Y-m-01'),
            "dateTo" => date('Y-m-d'),
            'proCode'=>''
        ));
		$sql = "SELECT
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
			where y.kind='调库出库' ";

        //找到所有属于原料的库位
		$str="select kuweiName from jichu_kuwei where type='原料'";
		$res=$this->_modelExample->findBySql($str);
		$strKuwei=join("','",array_col_values($res,'kuweiName'));

        $sql .= " and y.kuwei in ('{$strKuwei}')";

        if($arr['dateFrom']!=''){
            $sql.=" and y.chukuDate>='{$arr['dateFrom']}' and y.chukuDate<='{$arr['dateTo']}'";
        }
        if($arr['proCode']!=''){
            $sql.=" and b.proCode LIKE '%{$arr['proCode']}%'";
        }
        $sql.=" order by y.chukuDate desc, y.id desc";
        $pager=& new TMIS_Pager($sql);
        $rowset=$pager->findAll();
        foreach ($rowset as & $v) {
            $v['_edit'] .= $this->getRemoveHtml(array('id'=>$v['chukuId'],'fromAction'=>$_GET['action']),'RemoveTk');// 操作栏
        }

        $heji = $this->getHeji($rowset,array('chukuCnt'),'chukuDate');
        $rowset[] = $heji;
        //dump($rowset);exit;
        $smarty = & $this->_getView();
        $smarty->assign('title', $title);
        $arr_field_info = array(
			"_edit" => '操作',
			"chukuDate" => "调库日期",
			'chukuCode' => '出库单号',
			"kuwei" => "库位",
			'proCode' => '产品编码',
			'proName' => '品名',
			'guige' => '规格',
			'color' => '颜色',
			'ganghao'=>'缸号(批号)',
            'cntJian'=>'件数',
			'menfu' => '门幅',
			'kezhong' => '克重',
			'cnt' => '数量(kg)',
        );
        $smarty->assign('arr_field_info',$arr_field_info);
        $smarty->assign('arr_field_value',$rowset);
        $smarty->assign('arr_condition', $arr);
        $smarty->assign('add_display', 'none');
        $smarty->assign('arr_js_css',$this->makeArrayJsCss(array('calendar')));
        $smarty->assign('page_info',$pager->getNavBar($this->_url($_GET['action'],$arr)));
        $smarty->display($tpl);
	}

	/**
	 * @desc ：删除调库记录
	 * Time：2016/12/19 13:46:11
	 * @author Wuyou
	*/
	function actionRemoveTk(){
		$from = $_GET['fromAction'];
		if ($this->_modelExample->removeByPkv($_GET['id'])) {
			js_alert(null,"window.parent.showMsg('成功删除')",$this->_url($from));
		}
		else js_alert('出错，不允许删除!',$this->_url($from));
	}
}