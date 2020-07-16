<?php
/*********************************************************************\
*  Copyright (c) 1998-2013, TH. All Rights Reserved.
*  Author :Jeff
*  FName  :Ruku.php
*  Time   :2014/05/13 18:31:40
*  Remark :成品入库	控制器
\*********************************************************************/
FLEA::loadClass('Controller_Cangku_Chuku');
class Controller_Cangku_Chengpin_ChukuSell extends Controller_Cangku_Chuku {
    // var $fldMain;
    // var $headSon;
    // var $rules;//表单元素的验证规则
    // **************************************构造函数 begin********************************
    function __construct() {
        $this->_state = '成品';
        $this->_head = 'XSCKA';
        $this->_kind='销售出库';
        $this->_arrKuwei = array('成品仓库');

        $this->_modelDefault = &FLEA::getSingleton('Model_Cangku_Chengpin_Chuku');
        $this->_modelExample = &FLEA::getSingleton('Model_Cangku_Chengpin_Chuku');
        $this->_modelMain = &FLEA::getSingleton('Model_Cangku_Chengpin_Chuku');
        $this->_modelSon = &FLEA::getSingleton('Model_Cangku_Chengpin_Chuku2Product');
        //得到库位信息
        $sql = "select * from jichu_kuwei where 1";
        $rowset = $this->_modelMain->findBySql($sql);
        foreach($rowset as &$v) {
            // *根据要求：options为数组,必须有text和value属性
            $rowsKuwei[] = array('text' => $v['kuweiName'], 'value' => $v['kuweiName']);
        }

        $this->fldMain = array(
            'chukuCode' => array('title' => '出库单号', 'type' => 'text', 'readonly' => true, 'value' => $this->_getNewCode($this->_head, $this->_modelMain->qtableName, 'chukuCode')),
            'chukuDate' => array('title' => '出库日期', "type" => "calendar", 'value' => date("Y-m-d",strtotime("-1 day"))),
            'kind' => array('title' => '出库类别', 'type' => 'text', 'value' => $this->_kind, 'readonly' => true),
            'kuwei' => array('title' => '库位选择', 'type' => 'kuwei', 'value' => ''),
            'orderId' => array(
                'title' => '相关订单',
                'type' => 'popup',
                'value' => '',
                'name'=>'orderId',
                'text'=>'',
                'url'=>url('Trade_Order','Popup',array('fwFlag'=>1)),
                //'jsTpl'=>'Cangku/Chengpin/jsRuku.tpl',//需要载入的模板文件，这个文件中不需要使用{literal},因为是通过file_get_content获得的
                'onSelFunc'=>'onSel',//选中后需要执行的回调函数名,需要在jsTpl中书写
                'textFld'=>'orderCode',//显示在text中的字段
                'hiddenFld'=>'orderId',//显示在hidden控件中的字段
            ),
            'clientName' => array('title' => '客户名称', 'type' => 'text', 'value' => '', 'readonly' => true),
            'traderName' => array('title' => '业务员', 'type' => 'text', 'value' => '', 'readonly' => true),

            'memo' => array('title' => '出库备注', 'type' => 'textarea', 'disabled' => true, 'name' => 'chukuMemo'),
            'clientId' => array('type' => 'hidden', 'value' =>'','name'=>'clientId'),
            'id' => array('type' => 'hidden', 'value' => $_GET['id'],'name'=>'chukuId'),
        );

        // /从表表头信息
        $this->headSon = array(
            '_edit' => array('type' => 'btBtnCopy', "title" => '+5行', 'name' => '_edit[]'),
            'proCode' => array('type' => 'bttext', "title" => '产品编码', 'name' => 'proCode[]','readonly'=>true),
            // 'ord2proId' => array(
            // 	'title' => '', //表头文字
            // 	'type' => 'BtPopup',
            // 	'value' => '',
            // 	'name'=>'ord2proId[]',
            // 	'text'=>'',//现在在文本框中的文字
            // 	'url'=>url('Shengchan_Plan','popup'),//弹出地址,回调函数在sonTpl中进行定义,主要是对缓存中的关联数组进行重写
            // 	'textFld'=>'orderCode',//显示在text中的字段
            // 	'hiddenFld'=>'id',//显示在hidden控件中的字段
            // 	'inTable'=>1,
            // ),
            // 'productId' => array('type' => 'btProductpopup', "title" => '产品编码', 'name' => 'productId[]'),
            'proName' => array('type' => 'bttext', "title" => '品名', 'name' => 'proName[]', 'readonly' => true),
            'guige' => array('type' => 'bttext', "title" => '规格', 'name' => 'guige[]', 'readonly' => true),
            'color' => array('type' => 'bttext', "title" => '颜色', 'name' => 'color[]', 'readonly' => true),
            'menfu' => array('type' => 'bttext', "title" => '门幅', 'name' => 'menfu[]', 'readonly' => true),
            'kezhong' => array('type' => 'bttext', "title" => '克重', 'name' => 'kezhong[]', 'readonly' => true),
            'ganghao' => array('type' => 'bttext', 'title' => '缸号', 'name' => 'ganghao[]'),
            'type' => array('type' => 'btselect', "title" => '区分', 'name' => 'type[]', 'value'=>'','options' =>array(
                array('text'=>'A','value'=>'A'),
                array('text'=>'B','value'=>'B'),
                array('text'=>'C','value'=>'C'),
                array('text'=>'D','value'=>'D'),
            )),
            'dengji' => array('type' => 'btSelect', "title" => '等级', 'name' => 'dengji[]','options'=>array(
                array("text"=>'一等品',"value"=>'一等品',),
                array("text"=>'二等品',"value"=>'二等品',),
                array("text"=>'等外品',"value"=>'等外品',),
            )),
            'cntJian' => array('type' => 'bttext', "title" => '件数', 'name' => 'cntJian[]'),
            'cntOrg' => array('type' => 'bttext', "title" => '数量', 'name' => 'cntOrg[]'),
            //'cntM' => array('type' => 'bttext', "title" => '米数', 'name' => 'cntM[]'),
            'unit'=>array('type'=>'btSelect','title'=>'单位','name'=>'unit[]','options'=>array(
                array('text'=>'公斤','value'=>'公斤'),
                array('text'=>'米','value'=>'米'),
                array('text'=>'码','value'=>'码'),
                array('text'=>'磅','value'=>'磅'),
                array('text'=>'条','value'=>'条'),
                )),
            'cnt'=>array('type' => 'bttext', "title" => '折合公斤数', 'name' => 'cnt[]'),
            'jiagonghuId' => array('title' => '整理厂', 'type' => 'btselectNew', 'value' => '','name' => 'jiagonghuId[]', 'model' => 'Model_Jichu_Jiagonghu','orderBy'=>'paixu'),
            //'danjia' => array('type' => 'bttext', "title" => '单价', 'name' => 'danjia[]'),
            //'money' => array('type' => 'bttext', "title" => '金额(元)', 'name' => 'money[]'),
            'memo' => array('type' => 'bttext', "title" => '备注', 'name' => 'memo[]'),
            'productId' => array('type' => 'bthidden', 'name' => 'productId[]'),
            'ord2proId' => array('type' => 'bthidden', 'name' => 'ord2proId[]'),
            'id' => array('type' => 'bthidden', 'name' => 'id[]'),
        );
        // 表单元素的验证规则定义
        $this->rules = array('chukuDate' => 'required',
            'orderId' => 'required',
            // 'supplierId' => 'required',
            'kuwei' => 'required',
            'kind' => 'required',
            //'jiagonghuId[]'=>'required'
        );


        //查询时的字段信息,在查询界面和收发存弹出明细窗口需要用到
        $this->fldRight = array(
            "_edit" => '操作',
            "orderCode"=>'订单编号',
            'edit'=>array('text'=>'码单','width'=>70),
            "chukuDate" => "出库日期",
            'chukuCode' => '出库单号',
            "jiagonghuName" => "整理厂",
            'proKind' => '种类',
            'proCode' => '产品编码',
            'proName' => array('text'=>'品名', 'width'=>120),
            'guige' => array('text'=>'规格', 'width'=>320),
            'menfu' => '门幅',
            'kezhong' => '克重',
            'type'    => '区分',
            'ganghao' => '缸号',
            'color' => '颜色',
            'cntJian' => '件数',
            //'cntM' => '米数',
            'depName' => '领用部门',
             'kehuName' => '客户',
            // 'color' => '颜色',
            'cntOrg' => array('text'=>'数量','width'=>70,'type'=>'Number'),
            'unit'=>'单位',
            'cnt'=>'折合公斤数',
            'danjia' => '单价',
            'money' => '金额',
            'creater'=>'操作人',
            );

    }

    //新增时调整子模板
    function _beforeDisplayAdd(&$smarty) {
        // 从表信息字段,默认5行,等级默认一等品,整理厂默认佳程整理
        $sql = "SELECT * FROM jichu_jiagonghu WHERE compName='佳程整理'";
        $temp = $this->_modelExample->findBySql($sql);
        for($i = 0;$i < 5;$i++) {
            $temp['dengji']['value'] = '一等品';
            //$temp['jiagonghuId']['value'] = $temp[0]['id'];
            $rowsSon[] = $temp;
        }
        $smarty->assign('jiagonghuId', $temp[0]['id']);
        $smarty->assign('rowsSon', $rowsSon);
        $smarty->assign('sonTpl', 'Cangku/Chengpin/jsSell.tpl');
    }

    //修改时要显示订单号,客户和业务员
    function _beforeDisplayEdit(&$smarty) {
        // $rowsSon = $smarty->_tpl_vars['rowsSon'];
        $areaMain = & $smarty->_tpl_vars['areaMain'];
        // // dump($smarty->_tpl_vars);dump($areaMain);exit;
        $orderId= $areaMain['fld']['orderId']['value'];
        $m = & FLEA::getSingleton('Model_Trade_Order');
        $order = $m->find(array('id'=>$orderId));
        // 'clientName' => array('title' => '客户名称', 'type' => 'text', 'value' => '', 'readonly' => true),
        // 	'traderName' => array('title' => '业务员', 'type' => 'text', 'value' => '', 'readonly' => true),
        $areaMain['fld']['orderId']['text'] = $order['orderCode'];
        $areaMain['fld']['clientName']['value'] = $order['Client']['compName'];
        $areaMain['fld']['traderName']['value'] = $order['Trader']['employName'];
        // // dump($sql);
        // $_rows = $this->_modelExample->findBySql($sql);

        // $areaMain['fld']['orderId']['text'] = $_rows[0]['orderCode'];
    }

    //去掉领用部门
    function _beforeDisplayRight(&$smarty) {
        $f = & $smarty->_tpl_vars['arr_field_info'];
        unset($f['depName']);
        unset($f['danjia']);
        unset($f['money']);
        $f['memo'] = "备注";
        // $areaMain = & $smarty->_tpl_vars['areaMain'];
        // // dump($smarty->_tpl_vars);dump($areaMain);exit;
        // $orderId= $areaMain['fld']['orderId']['value'];
        // $sql = "select orderCode from trade_order where id='{$orderId}'";
        // // dump($sql);
        // $_rows = $this->_modelExample->findBySql($sql);

        // $areaMain['fld']['orderId']['text'] = $_rows[0]['orderCode'];
    }

    function actionReport() {
        $this->authCheck('3-2-7');
        FLEA::loadClass("TMIS_Pager");
        $BeginDate=date('Y-m-01', strtotime(date("Y-m-d")));
        $arr = &TMIS_Pager::getParamArray(array(
            "dateFrom" => date('Y-m-01'),
            "dateTo" => date(date('Y-m-d', strtotime("$BeginDate +1 month -1 day"))),
            "kuweiName"  =>'',
            "proCode" =>"",
            "guige" =>"",
            "ganghao" =>"",
            "proName" =>"",
            "kezhong" =>"",
            "isStockZero" =>0, // 默认选择有效库存
        ));

        //处理库位
        // $strKuwei = join("','",$this->_arrKuwei);
       //找到所有属于成品的库位
        $str="select kuweiName from jichu_kuwei where type='成品'";
        $res=$this->_modelExample->findBySql($str);
        $strKuwei=join("','",array_col_values($res,'kuweiName'));

        $strCon = " and kuwei in ('{$strKuwei}')";

        $map = '';
        $havingMap = '';
         if($arr['kuweiName']!='') $strCon.=" and kuwei='{$arr['kuweiName']}'";
         if($arr['guige']!='') $map .=" and guige like '%{$arr['guige']}%'";
         if($arr['proCode']!='') $map .="and proCode like '%{$arr['proCode']}%'";
         if($arr['ganghao']!='') $map .="and ganghao = '{$arr['ganghao']}'";
         if($arr['proName']!='') $map .=" and proName like '%{$arr['proName']}%'";
         if($arr['kezhong']!='') $map .=" and kezhong like '%{$arr['kezhong']}%'";

        // 是否为零库存
        if($arr['isStockZero']== '0'){
            $havingMap .= ' and round(sum(cntInit)+sum(cntRuku)-sum(cntChuku),1)<>0';
        }else if($arr['isStockZero'] == '1'){
            $havingMap .= ' and round(sum(cntInit)+sum(cntRuku)-sum(cntChuku),1)=0';
        }else{
            $havingMap .= '';
        }

        $strGroup="kuwei,productId,ganghao";
        $sqlUnion="select {$strGroup},
                sum(cntFasheng) as cntInit,
                sum(cntJian) as cntJianInit,
                sum(moneyFasheng) as moneyInit,
                0 as cntRuku,0 as cntJianRuku,0 as moneyRuku,
                0 as cntChuku,0 as cntJianChuku, 0 as moneyChuku
                from `cangku_common_kucun` where dateFasheng<'{$arr['dateFrom']}'
                {$strCon} group by {$strGroup}
                union
                select {$strGroup},
                0 as cntInit,0 as cntJianInit, 0 as moneyInit,
                sum(cntFasheng) as cntRuku,
                sum(cntJian) as cntJianRuku,
                sum(moneyFasheng) as moneyRuku,
                0 as cntChuku,0 as cntJianChuku, 0 as moneyChuku
                from `cangku_common_kucun` where
                dateFasheng>='{$arr['dateFrom']}' and dateFasheng<='{$arr['dateTo']}'
                and rukuId>0  {$strCon} group by {$strGroup}
                union
                select {$strGroup},
                0 as cntInit,0 as cntJianInit, 0 as moneyInit,
                0 as cntRuku,0 as cntJianRuku, 0 as moneyRuku,
                sum(cntFasheng*-1) as cntChuku,
                sum(cntJian) as cntJianChuku,
                sum(moneyFasheng*-1) as moneyChuku
                from `cangku_common_kucun` where
                dateFasheng>='{$arr['dateFrom']}' and dateFasheng<='{$arr['dateTo']}'
                and chukuId>0  {$strCon} group by {$strGroup}";
        $sql="select
            {$strGroup},
            sum(cntInit) as cntInit,
            sum(cntJianInit) as cntJianInit,
            sum(moneyInit) as moneyInit,
            sum(cntRuku) as cntRuku,
            sum(cntJianRuku) as cntJianRuku,
            sum(moneyRuku) as moneyRuku,
            sum(cntChuku) as cntChuku,
            sum(cntJianChuku) as cntJianChuku,
            sum(moneyChuku) as moneyChuku
            from ({$sqlUnion}) as x
            left join jichu_product y on x.productId = y.id
            where 1 {$map}
            group by {$strGroup}
            having (
                    sum(cntInit)<>0 or sum(moneyInit)<>0
                    or sum(cntRuku)<>0 or sum(moneyRuku)<>0
                    or sum(cntChuku)<>0 or sum(moneyChuku)<>0
                    )
                    {$havingMap}
            order by y.proCode";

        // dump($sql);die;
        if($_GET['export']==1){
            $rowset = $this->_modelExample->findBySql($sql);
        }else{
            $pager = &new TMIS_Pager($sql);
            $rowset = $pager->findAll();
        }
        $canChange= $this->authCheck('100-4',true);
        $canMove=$this->authCheck('100-5',true);

        // dump($rowset);die;
        //得到合计信息
        foreach($rowset as &$v) {
            // dump($v);exit;
            $sql = "select * from jichu_product where id='{$v['productId']}'";
            $temp = $this->_modelMain->findBySql($sql);
            $v['proCode'] = $temp[0]['proCode'];
            $v['proName'] = $temp[0]['proName'];
            $v['menfu'] = $temp[0]['menfu'];
            $v['kezhong'] = $temp[0]['kezhong'];
            $v['guige'] = $temp[0]['guige'];
            $v['color'] = $temp[0]['color'];
            $v['cntInit']=round($v['cntInit'], 2);
            $v['cntJianInit']=round($v['cntJianInit'], 2);
            $v['cntRuku']=round($v['cntRuku'], 2);
            $v['cntJianRuku']=round($v['cntJianRuku'], 2);
            $v['cntChuku']=round($v['cntChuku'], 2);
            $v['cntJianChuku']=round($v['cntJianChuku'], 2);
            // $sql = "select * from jichu_supplier where id='{$v['supplierId']}'";
            // $temp = $this->_modelMain->findBySql($sql);
            // $v['supplierName'] = $temp[0]['compName'];
            $v['cntKucun'] = round($v['cntInit'] + $v['cntRuku'] - $v['cntChuku'], 2);
            $v['cntJianKucun'] = round($v['cntJianInit'] + $v['cntJianRuku'] - $v['cntJianChuku'], 2);

            //本期入库和本期出库点击可看到明细

            $tkArr = array(
                    'kuwei'     => $v['kuwei'],
                    'productId' => $v['productId'],
                    'proCode'   => $v['proCode'],
                    'pihao'     => $v['pihao'],
                    'ganghao'   => $v['ganghao'],
                    'dateFrom'  => $arr['dateFrom'],
                    'dateTo'    => $arr['dateTo'],
                    'cntJianKucun'=> $v['cntJianKucun']
                );

            $v['_edit'] = '';
            if($canChange){
                $v['_edit'] .= "<a href='".$this->_url('ChangeKucun',$tkArr)."' class='thickbox' title='调整库存'>调整库存</a>&nbsp;&nbsp;";
            }
            if($canMove){
                $v['_edit'] .= "<a href='".url('Cangku_Chengpin_Kcdb','DiaoBo',$tkArr)."' class='thickbox' title='库存调拨'>库存调拨</a>";
            }
        }
        $heji = $this->getHeji($rowset,array('cntInit','cntJianInit','cntRuku','cntJianRuku','cntChuku', 'cntJianChuku','cntKucun', 'cntJianKucun'),'kuwei');
        if($_GET['export']) $heji['kuwei'] = "合计";

        //出入库数量形成可弹出明细的链接
        if(!$_GET['export']){
            foreach($rowset as & $v) {
                // $cName = str_replace('chuku', 'ruku', strtolower($_GET['controller']));
                $v['cntRuku'] = "<a href='".url('Cangku_Chengpin_Ruku','popup',array(
                    'dateFrom'=>$arr['dateFrom'],
                    'dateTo'=>$arr['dateTo'],
                    'kuwei'=>$v['kuwei'],
                    'productId'=>$v['productId'],
                    //'state'=>$this->_state,
                    // 'supplierId'=>$v['supplierId'],
                ))."' target='_blank'>{$v['cntRuku']}</a>";

                $v['cntChuku'] = "<a href='".url("Cangku_Chengpin_ChukuSell",'popup',array(
                    'dateFrom'=>$arr['dateFrom'],
                    'dateTo'=>$arr['dateTo'],
                    'kuwei'=>$v['kuwei'],
                    'productId'=>$v['productId'],
                    //'state'=>$this->_state,
                    // 'supplierId'=>$v['supplierId'],
                ))."' target='_blank'>{$v['cntChuku']}</a>";
            }
        }

        $rowset[] = $heji;
        // 显示信息
        $arrFieldInfo = array(
            'kuwei'           =>  array('text'=>'库位','width'=>'60'),
            //'state'         => '状态',
            "proCode"         => array('text'=>"产品编码",'width'=>'70'),
            'proName'         => array('text'=>'品名','width'=>'180'),
            "guige"           => array('text'=>"规格",'width'=>'180'),
            'menfu'           => array('text'=>"门幅",'width'=>'80'),
            'kezhong'           => array('text'=>"克重",'width'=>'80'),
            "color"           => array('text'=>"颜色",'width'=>'60'),
            "ganghao"         => array('text'=>"缸号",'width'=>'60'),
            //"dengji"        => "等级",
            // "dengji"       => "等级",
            // "supplierName" => '供应商',
            // "pihao"        =>'批号',
            // 'pihao'        =>'批号',
            'cntInit'         => array('type'=>'Number', 'text'=>'期初'),
            'cntJianInit'     => array('type'=>'Number', 'text'=>'期初-件数'),
            'cntRuku'         => array('type'=>'Number', 'text'=>'本期入库'),
            'cntJianRuku'     => array('type'=>'Number', 'text'=>'本期入库-件数'),
            'cntChuku'        => array('type'=>'Number', 'text'=>'本期出库'),
            'cntJianChuku'    => array('type'=>'Number', 'text'=>'本期出库-件数'),
            'cntKucun'        => array('type'=>'Number', 'text'=>'余存'),
            'cntJianKucun'    => array('type'=>'Number', 'text'=>'余存-件数'),
            '_edit'=>'操作',
            // 'cnt'          =>'数量',
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
            header("Content-Disposition: attachment;filename=report.xls");
            header("Content-Transfer-Encoding: binary");
            $smarty->display('Export2Excel2.tpl');
            exit;
        }
        //得到总计
        $sql = "select
        sum(cntFasheng) as cnt,
        sum(moneyFasheng) as money
        from `cangku_common_kucun`
        where dateFasheng<='{$arr['dateTo']}' {$strCon}";
        // dump($sql);exit;
        $zongji = $this->_modelMain->findBySql($sql);
        $zongji = $zongji[0];
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
        $font = "<font color='red'>余存总计:{$zongji['cnt']}公斤;&nbsp;&nbsp;入库总计:{$zjRk[0]['cnt']}公斤;&nbsp;&nbsp;出库总计:{$zjCk[0]['cnt']}公斤;</font>";
        $smarty->assign('fn_export',$this->_url($_GET['action'],$arr+array('export'=>1)));
        $smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action']), $arr).$font);
        $smarty->assign('arr_js_css', $this->makeArrayJsCss(array('grid', 'calendar')));
        $smarty->display('TableList.tpl');
    }
    /**
     * 收发存报表中点击出库数量弹出窗口
     */
    function actionPopup() {
        FLEA::loadClass('TMIS_Pager');
        // /构造搜索区域的搜索类型
        $arr = TMIS_Pager::getParamArray(array(
                'dateFrom' => $_GET['dateFrom'],
                'dateTo' => $_GET['dateTo'],
                'kuwei'=>$_GET['kuwei'],
                'pihao'=>$_GET['pihao'],
                'productId'=>$_GET['productId'],
                // 'supplierId' => $_GET['supplierId'],
        ));
        // dump($serachArea);exit;
        $sql = "select  y.chukuCode,
                        y.kuwei,
                        y.chukuDate,
                        y.memo as chukuMemo,
                        y.kind,
                        x.id,
                        x.chukuId,
                        x.pihao,
                        x.productId,
                        x.cnt,
                        x.danjia,
                        x.money,
                        x.memo,
                        b.proCode,
                        b.proName,
                        b.guige,
                        b.color,
                        b.menfu,b.kezhong,
                        b.kind as proKind,
                        c.depName,
                        d.compName as clientName,
                        o.orderCode,
                        j.compName as jiagonghuName
                from cangku_common_chuku y
                left join cangku_common_chuku2product x on y.id=x.chukuId
                left join jichu_product b on x.productId=b.id
                left join jichu_department c on y.depId=c.id
                left join jichu_client d on y.clientId=d.id
                left join trade_order o on  y.orderId=o.id
                left join jichu_jiagonghu j on x.jiagonghuId=j.id
                where x.pihao='{$arr['pihao']}'
        ";

        $sql .= " and y.chukuDate >= '{$arr['dateFrom']}' and y.chukuDate<='{$arr['dateTo']}'";
        $sql .= " and x.productId='{$arr['productId']}'";
        // $sql .= " and x.productId='{$arr['productId']}'";
        $sql .= " order by chukuCode desc";
        // dump($sql);exit;
        $pager = &new TMIS_Pager($sql);
        $rowset = $pager->findAll();

        if (count($rowset) > 0) foreach($rowset as &$value) {
            $value['cnt']=round($value['cnt'],2);
    }
    // 合计行
    $heji = $this->getHeji($rowset, array('cnt'), 'clientName');
    $rowset[] = $heji;
    // 显示信息
    $arrFieldInfo = $this->fldRight;
    unset($arrFieldInfo['_edit']);
    $arrFieldInfo = array('clientName'=>'客户')+$arrFieldInfo;
    $arrFieldInfo = array('orderCode'=>'订单编号')+$arrFieldInfo;

    $smarty = &$this->_getView();
    $smarty->assign('title', '出库清单');
    // $smarty->assign('pk', $this->_modelDefault->primaryKey);
    $smarty->assign('arr_field_info', $arrFieldInfo);
    $smarty->assign('add_display', 'none');
    // $smarty->assign('arr_condition', $arr);
    $smarty->assign('arr_field_value', $rowset);
    $smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action']), $arr));
    // $smarty->assign('arr_js_css', $this->makeArrayJsCss(array('grid', 'calendar')));
    $smarty->display('TableList.tpl');
    }
    //根据订单销售出库
    function actionAdd(){
        //$this->authCheck('3-2-5');
        parent::actionAdd();

    }

    // function actionRight(){
    // 	//$this->authCheck('3-2-6');
    // 	parent::actionRight();
    // }

    //出库查询
    function actionRight() {
        set_time_limit(0);
        ini_set('memory_limit', '2048M');
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
            'zlbType'  => '',
            // 'traderId' => '',
            // 'isCheck' => 0,
            'key' => '',
            'proKind'=>'',
            'ganghao'=>'',
            'orderCode'=>''
        ));

        $sql = "select  y.clientId,
                        y.chukuCode,
                        y.kuwei,
                        y.chukuDate,
                        y.memo as chukuMemo,
                        y.orderId,
                        y.kind,
                        y.creater,
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
                        x.type,
                        b.proCode,
                        b.proName,
                        b.guige,
                        b.color,
                        b.menfu as productMenfu,
                        b.kezhong as productKezhong,
                        b.kind as proKind,
                        c.depName,
                        d.compName as jiagonghuName,
                        g.id as guozhangId,
                        f.compName as kehuName,
                        o2p.menfu,
                        o2p.kezhong,
                        o2.orderCode
                from cangku_common_chuku y
                left join cangku_common_chuku2product x on y.id=x.chukuId
                left join trade_order2product o2p on o2p.id = x.ord2proId
                left join trade_order o2 on o2.id=y.orderId
                left join jichu_client f on y.clientId=f.id
                left join jichu_product b on x.productId=b.id
                left join jichu_department c on y.depId=c.id
                left join jichu_jiagonghu d on x.jiagonghuId=d.id
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
        if ($serachArea['key'] != '') {
            if(strpos($serachArea['key'], '+')){
                $tempKey = explode('+', $serachArea['key']);
                foreach ($tempKey as & $v) {
                  $strObj[] = " b.guige like '%{$v}%'";
                }
                $guigeStr = join(' and ', $strObj);
                $sql .= " and (y.chukuCode like '%{$serachArea['key']}%'
                    or b.proName like '%{$serachArea['key']}%'
                    or b.proCode like '%{$serachArea['key']}%'
                    or x.ganghao like '%{$serachArea['key']}%'
                    or ({$guigeStr}))";
            }else{
                $sql .= " and (y.chukuCode like '%{$serachArea['key']}%'
                    or b.proName like '%{$serachArea['key']}%'
                    or b.proCode like '%{$serachArea['key']}%'
                    or x.ganghao like '%{$serachArea['key']}%'
                    or b.guige like '%{$serachArea['key']}%')";
            }
        }
         if ($serachArea['clientId'] != '') $sql .= " and y.clientId = '{$serachArea[clientId]}'";
         if ($serachArea['ord2proId'] >0) $sql .= " and x.ord2proId = '{$serachArea['ord2proId']}'";
         if ($serachArea['proKind'] != '') $sql .= " and b.kind = '{$serachArea['proKind']}'";
         if ($serachArea['zlbType'] != '') $sql .= " and x.type = '{$serachArea['zlbType']}'";
         if ($serachArea['ganghao'] != '') $sql .= " and x.ganghao = '{$serachArea['ganghao']}'";
         if ($serachArea['orderCode'] != '') $sql .= " and o2.orderCode like '%{$serachArea['orderCode']}%'";
        $sql .= " order by y.chukuCode,y.chukuDate desc";
        // dump($sql);exit;
        // dump($curState);exit;
        $str = "SELECT * FROM ({$sql}) as t";
        // dump($str);exit;
        //得到总计
        $zongji = $this->getZongji($str,array('cntOrg'=>'cntOrg','cnt'=>'cnt','money'=>'money','cntJian'=>'cntJian'));
        if($_GET['export']==1){
             $rowset = $this->_modelExample->findBySql($str);
        }else{
             $pager = &new TMIS_Pager($str);
             $rowset = $pager->findAll();
        }
       
        $rowsetAll = $this->_modelExample->findBySql($str);

        // dump($rowset);exit;
       //找到所有属于成品的库位
        $str = "select kuweiName from jichu_kuwei where type='成品'";
        $arrKuwei = $this->_modelExample->findBySql($str);

        if (count($rowset) > 0) foreach($rowset as &$value) {
            //如果过账了，则禁止删除与修改
            if(!$value['guozhangId']){
                  //如果库位是成品仓库，则判断orderId是否为0
                if(in_array($value['kuwei'], $arrKuwei)){
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
                            $value['edit'].=" "."<a href='".url('Cangku_Chengpin_Madan','SelexportChuku',array(
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

            $value['kezhong'] = (empty($value['kezhong']))?$value['productKezhong']:$value['kezhong'];
            $value['menfu'] = (empty($value['menfu']))?$value['productMenfu']:$value['menfu'];
        }


            $value['cnt']=round($value['cnt'],1);
            $value['cntOrg']=round($value['cntOrg'],1);

            //得到客户
            if($value['clientId']) {
                $m = & FLEA::getSingleton('Model_Jichu_Client');
                $c = $m->find(array('id'=>$value['clientId']));
                $value['clientName'] = $c['compName'];
            }
        }
        // 合计行
        $heji = $this->getHeji($rowset, array('cnt','cntJian','cntOrg'), '_edit');
        $rowset[] = $heji;
        $zj = $this->getHeji($rowsetAll,array('cnt','cntJian','cntOrg'),'_edit');
        if($_GET['export']!=1){
            $zj['_edit'] = '总计';
            $rowset[] = $zj;
        }
        
        // 显示信息
        $arrFieldInfo = array("_edit" => '操作')+$this->fldRight;
        if($_GET['export']==1){
            unset($arrFieldInfo['_edit']);
            unset($arrFieldInfo['depName']);
            unset($arrFieldInfo['danjia']);
            unset($arrFieldInfo['money']);
        }
        $smarty = &$this->_getView();
        $smarty->assign('title', '出库查询');
        // $smarty->assign('pk', $this->_modelDefault->primaryKey);
        $smarty->assign('arr_field_info', $arrFieldInfo);
        $smarty->assign('add_display', 'none');
        $smarty->assign('arr_condition', $serachArea);
        $smarty->assign('arr_field_value', $rowset);
        $msg = "<font color='red'>数量总计:{$zongji['cnt']},金额总计:{$zongji['money']},件数总计:{$zongji['cntJian']}</font>";
        if($_GET['export']==1){
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: application/force-download");
            header("Content-Type: application/download");
            header("Content-Disposition: attachment;filename=chuku.xls");
            header("Content-Transfer-Encoding: binary");
            $smarty->display('Export2Excel2.tpl');
            exit;
        }
        $smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action']), $serachArea).$msg);
        $smarty->assign('fn_export',$this->_url($_GET['action'],$serachArea+array('export'=>1)));
        $smarty->assign('arr_js_css', $this->makeArrayJsCss(array('grid', 'calendar')));
        $this->_beforeDisplayRight($smarty);
        $smarty->display('TableList.tpl');
    }

    function actionSave(){
        // dump($_POST);exit;
        $yuanliao_llck2product = array();
        $order2pro=&FLEA::getSingleton('Model_Trade_Order2Product');
        foreach ($_POST['productId'] as $key => $v) {
            // 当没有记录 或者 没有输入数量时 跳出本次循环 防止多保存
            if (empty($_POST['productId'][$key]) || empty($_POST['cnt'][$key])) continue;
            if($_POST['productId'][$key]!=''){
                if($_POST['jiagonghuId'][$key]==''){
                    js_alert('请选择整理厂!','window.history.go(-1)');
                    exit();
                }
            }
            $temp = array();
            foreach($this->headSon as $k=>&$vv) {
                $temp[$k] = $_POST[$k][$key];
                //根据ord2proId 取得订单明细表中的 单价
                if($k=='ord2proId'){
                    $res=$order2pro->find(array('id'=>$_POST['ord2proId'][$key]));
                    $temp['danjia'] = $res['danjia'];
                }
            }
            $yuanliao_llck2product[] = $temp;
        }
        //dump($yuanliao_llck2product);exit;
        //如果没有选择物料，返回
        if(count($yuanliao_llck2product)==0) {
            js_alert('请选择有效物料并输入有效数量!','window.history.go(-1)');
            exit;
        }
        // yuanliao_llck 表 的数组
        foreach($this->fldMain as $k=>&$vv) {
            $name = $vv['name']?$vv['name']:$k;
            $yuanliao_llck[$k] = $_POST[$name];
        }

        // 表之间的关联
        $yuanliao_llck['Products'] = $yuanliao_llck2product;
        // 保存 并返回yuanliao_cgrk表的主键
        $row = $this->notNull($yuanliao_llck);
        //dump($yuanliao_llck);exit;
        $row['creater'] = $_SESSION['REALNAME'];
        $itemId = $this->_modelExample->save($row);
        if (!$itemId) {
            echo "保存失败";
            exit;
        }
        js_alert(null, 'window.parent.showMsg("保存成功!")', url($_POST['fromController'],$_POST['fromAction']));
    }

    /**
     * @desc ：整理厂明细报表
     * Time：2016/12/21 14:49:06
     * @author Wuyou
    */
    function actionZlcReport(){
        $this->authCheck('3-2-10');
        FLEA::loadClass("TMIS_Pager");
        $arr = &TMIS_Pager::getParamArray(array(
            "jiagonghuId" => '',
            "clientId"    => '',
            "traderId"    => '',
            "dateType"    => 'fwDate',
            "dateFrom"    => date('Y-m-01'),
            "dateTo"      => date('Y-m-d'),
            'orderCode'   => '',
            'proCode'     => '',
            "guige"       => "",
            "ganghao"     => "",
        ));

        //找到所有属于成品的库位
        $str = "select kuweiName from jichu_kuwei where type='成品'";
        $res = $this->_modelExample->findBySql($str);
        $strKuwei = join("','",array_col_values($res,'kuweiName'));
        $con = '';
        $subUion = '';

        // Tips 【特殊需求】::屏蔽发外日期在 2017年04月25日(包含) 之前的发外数据。
        $limitTime = '2017-04-25 23:59:59';
        if(strtotime($arr['dateFrom']) <=  strtotime($limitTime)){
            $arr['dateFrom'] = $limitTime;
        }
        // 时间搜索
        if($arr['dateType']!=''){
            if($arr['dateType']=='fwDate'){
                $con .= " and fwDate>='{$arr['dateFrom']}' and fwDate<='{$arr['dateTo']}'";

            }else{
                $con .= " and chukuDate>='{$arr['dateFrom']}' and chukuDate<='{$arr['dateTo']}'";
            }
        }
        $subFwUion  = " and b.rukuDate>='{$arr['dateFrom']}' and b.rukuDate<='{$arr['dateTo']}'";
        $subRukuUion  = " and b.rukuDate>='{$arr['dateFrom']}' and b.rukuDate<='{$arr['dateTo']}'";
        $subChukuUion  = " and b.chukuDate>='{$arr['dateFrom']}' and b.chukuDate<='{$arr['dateTo']}'";


        // 客户搜索
        if($arr['clientId']!=''){
            $con .= " and clientId='{$arr['clientId']}'";
        }
        // 整理厂搜素
        if($arr['jiagonghuId']!=''){
            $con .= " and jiagonghuId='{$arr['jiagonghuId']}'";
        }
        // 业务员
        if($arr['traderId'] != ''){
            $con .= " and traderId= '{$arr['traderId']}'";
        }
        // 订单编号
        if($arr['orderCode']!=''){
            $con .= " and orderCode like '%{$arr['orderCode']}%'";
        }
        // 产品编码
        if($arr['proCode']!=''){
            $con .= " and proCode like '%{$arr['proCode']}%'";
        }
        // 缸号
        if($arr['ganghao']!=''){
            $con .= " and ganghao like '%{$arr['ganghao']}%'";
        }
        // 规格
        if($arr['guige']!=''){
            $con .= " and guige like '%{$arr['guige']}%'";
        }
        // 发外数据
        $sqlFw = "SELECT b.jiagonghuId,
                       max(b.rukuDate) as fwDate,
                       sum(a.cntJian) as jianFw,
                       sum(a.cnt) as cntFw,
                       '' as rukuDate,
                       0 as jianRuku,
                       0 as cntRuku,
                       a.orderId,
                       a.ord2proId,
                       a.productId,
                       '' as ganghao,
                       '' as chukuDate,
                       '' as cntChuku,
                       '' as jianChuku,
                       '' as clientId,
                       a.memo
                FROM cangku_fawai2product a
                LEFT JOIN cangku_fawai b ON b.id=a.fawaiId
                WHERE 1 {$subFwUion}
                GROUP BY b.jiagonghuId,a.ord2proId";
        // 出库数据
        $sqlCk = "SELECT a.jiagonghuId,
                       '' as fwDate,
                       0 as jianFw,
                       0 as cntFw,
                       '' as rukuDate,
                       0 as jianRuku,
                       0 as cntRuku,
                       b.orderId,
                       a.ord2proId,
                       a.productId,
                       group_concat(a.ganghao) as ganghao,
                       max(b.chukuDate) as chukuDate,
                       sum(a.cnt) as cntChuku,
                       sum(a.cntJian) as jianChuku,
                       b.clientId as clientId,
                       '' as memo
                    FROM cangku_common_chuku2product a
                    LEFT JOIN cangku_common_chuku b ON a.chukuId=b.id
                    WHERE 1 {$subChukuUion}
                      and b.kuwei in ('{$strKuwei}')
                      and b.kind='{$this->_kind}'
                    GROUP BY a.jiagonghuId,a.ord2proId";
        // 生产入库数据
        $sqlRk = "SELECT a.jiagonghuId,
                       '' as fwDate,
                       0 as jianFw,
                       0 as cntFw,
                       max(b.rukuDate) as rukuDate,
                       sum(a.cntJian) as jianRuku,
                       sum(a.cnt) as cntRuku,
                       b.orderId,
                       a.ord2proId,
                       a.productId,
                       group_concat(a.ganghao) as ganghao,
                       '' as chukuDate,
                       0 as cntChuku,
                       0 as jianChuku,
                       0 as clientId,
                       '' as memo
                    FROM cangku_common_ruku2product a
                    LEFT JOIN cangku_common_ruku b ON a.rukuId=b.id
                    WHERE 1 {$subRukuUion}
                      and b.kuwei in ('{$strKuwei}')
                    GROUP BY a.jiagonghuId,a.ord2proId";
        $sql = "SELECT
                       jiagonghuId,
                       max(rukuDate) as fwDate,
                       sum(jianFw) as jianFw,
                       sum(cntFw) as cntFw,
                       orderId,
                       orderCode,
                       ord2proId,
                       productId,
                       proCode,
                       max(rukuDate) as rukuDate,
                       sum(jianRuku) as jianRuku,
                       sum(cntRuku) as cntRuku,
                       max(chukuDate) as chukuDate,
                       sum(cntChuku) as cntChuku,
                       sum(jianChuku) as jianChuku,
                       max(ganghao) as ganghao,
                       max(t.clientId) as clientId,
                       max(t.memo) as memo,
                       p.proName,
                       p.guige,
                       p.color,
                       trader.employName as traderName,
                       ord.traderId
                FROM ({$sqlFw} UNION {$sqlCk} UNION {$sqlRk}) AS t
                LEFT JOIN jichu_product p ON t.productId=p.id
                LEFT JOIN trade_order ord ON ord.id = orderId
                LEFT JOIN jichu_employ trader ON trader.id = traderId
                GROUP BY jiagonghuId,ord2proId";
        $str = "SELECT * FROM ({$sql}) as tp
                WHERE 1 {$con}";
        // dump($str);die;


        if($_GET['export'] == 1){
            $rowset = $this->_modelExample->findBySql($str);
        }else{
            $pager = &new TMIS_Pager($str);
            $rowset = $pager->findAll();
        }

        // dump($rowset);die;
        //得到合计信息
        foreach($rowset as &$v) {
            //门幅克重取值订单
            $sql="SELECT * from trade_order2product where id='{$v['ord2proId']}'";
            $proInfo = $this->_modelExample->findBySql($sql);
            $v['menfu'] = $proInfo[0]['menfu'].'/'.$proInfo[0]['kezhong'];
            // 整理厂
            $sql = "SELECT * FROM jichu_jiagonghu WHERE id='{$v['jiagonghuId']}'";
            $temp = $this->_modelMain->findBySql($sql);
            $v['zlcName'] = $temp[0]['compName'];
            // 客户
            $sql = "SELECT * FROM jichu_client WHERE id='{$v['clientId']}'";
            $temp = $this->_modelMain->findBySql($sql);
            $v['clientName'] = $temp[0]['compName'];
            // 库存
            $sql = "SELECT SUM(a.cnt) as cntChuku
                    FROM cangku_common_chuku2product a
                    LEFT JOIN cangku_common_chuku b ON b.id = a.chukuId
                    WHERE a.ord2proId={$v['ord2proId']}
                    AND a.jiagonghuId='{$v['jiagonghuId']}'
                    AND b.kuwei in ('{$strKuwei}')
                    AND b.kind='{$this->_kind}'
                    {$subChukuUion}
                    ";
            $temp = $this->_modelExample->findBySql($sql);
            $cntChuku = $temp[0]['cntChuku'];
            $sql = "SELECT SUM(a.cnt) as cntRuku
                    FROM cangku_common_ruku2product a
                    LEFT JOIN cangku_common_ruku b ON b.id = a.rukuId
                    WHERE a.ord2proId='{$v['ord2proId']}'
                    AND a.jiagonghuId='{$v['jiagonghuId']}'
                    AND b.kuwei in ('{$strKuwei}')
                    {$subRukuUion}
                    ";
            $temp = $this->_modelExample->findBySql($sql);
            $cntRuku = $temp[0]['cntRuku'];

//            $v['cntRukuF'] = $cntRuku;
//            $v['cntChukuF'] = $cntChuku;

            $v['cntKucun'] = round($cntRuku - $cntChuku,2);
        }

        $heji = $this->getHeji($rowset,array('jianFw','cntFw','cntChuku','jianChuku','cntRuku','cntKucun'),'zlcName');

        if($_GET['export'] != 1){
            foreach($rowset as &$v) {
                // 显示明细信息
                $v['cntRuku'] = "<a href='" . url('Cangku_Chengpin_Ruku', 'RukuDetail', array(
                        'jiagonghuId' => $v['jiagonghuId'],
                        'ord2proId' => $v['ord2proId'],
                        'dateFrom' => $arr['dateFrom'],
                        'dateTo' => $arr['dateTo'],
                        'kuwei' => $strKuwei,
                        'width' => 800,
                        'no_edit' => 1,
                        'TB_iframe' => 1,
                    )) . "' class='thickbox' title='入库明细'>{$v['cntRuku']}</a>";
                $v['cntChuku'] = "<a href='" . $this->_url('ChukuDetail', array(
                        'jiagonghuId' => $v['jiagonghuId'],
                        'ord2proId' => $v['ord2proId'],
                        'dateFrom' => $arr['dateFrom'],
                        'dateTo' => $arr['dateTo'],
                        'kuwei' => $strKuwei,
                        'width' => 800,
                        'no_edit' => 1,
                        'TB_iframe' => 1,
                    )) . "' class='thickbox' title='出库明细'>{$v['cntChuku']}</a>";
            }
        }else{
            $heji['zlcName'] ='合计';
        }

        $rowset[] = $heji;
        // 显示信息
        $arrFieldInfo = array(
            'zlcName'    =>  array('text'=>'整理厂','width'=>'120'),
            'fwDate'   => '日期',
//            'fwDate'   => '最后发外日期',
//            'rukuDate'   => '最后入库日期',
            'orderCode'  => '订单号',
            'proCode'    => array('text'=>'产品编码','width'=>'100'),
            'proName'    => array('text'=>'品名','width'=>'180'),
            "guige"      => array('text'=>"规格",'width'=>'180'),
            "color"      => array('text'=>"颜色",'width'=>'100'),
            "ganghao"    => array('text'=>"缸号",'width'=>'60'),
            "menfu"      => array('text'=>"门幅/克重",'width'=>'70'),
            "jianFw"    => array('text'=>"件数",'width'=>'60'),
            'cntFw'        => '发坯数量',
            'cntRuku'    => '入库数量',
            'cntChuku'   => '出库数量',
//            'chukuDate'  => '最后出库日期',
//            'cntRukuF'    => 'F入库数量',
//            'cntChukuF'   => 'F出库数量',
            'jianChuku'  => '件数',
            'clientName' => '客户',
            'traderName' => '业务员',
            'cntKucun'   => '库存量',
            'memo'       => '备注',
        );

        $smarty = &$this->_getView();
        $smarty->assign('title', '整理厂明细报表');
        $smarty->assign('arr_field_info', $arrFieldInfo);
        $smarty->assign('add_display', 'none');
        $smarty->assign('arr_condition', $arr);
        $smarty->assign('arr_field_value', $rowset);
        if($_GET['export']==1){
            $date =
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: application/force-download");
            header("Content-Type: application/download");
            header("Content-Disposition: attachment;filename=整理厂明细报表.xls");
            header("Content-Transfer-Encoding: binary");
            $smarty->display('Export2Excel2.tpl');
            exit;
        }
        $smarty->assign('fn_export',$this->_url($_GET['action'],$arr+array('export'=>1)));
        $smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action']), $arr));
        $smarty->assign('arr_js_css', $this->makeArrayJsCss(array('grid', 'calendar')));
        $smarty->display('TableList.tpl');
    }

    /**
     * 出库明细（订单）
     */
    public function actionChukuDetail(){
        $form = $_GET;
        $orderId = $_GET['orderId'];
        $ord2proId = $_GET['ord2proId'];
        $jiagonghuId = $_GET['jiagonghuId'];
        $dateFrom = $_GET['dateFrom'];
        $dateTo = $_GET['dateTo'];
        $kuwei  = $_GET['kuwei'];

        $sql = "select  y.clientId,
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
                        x.type,
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
                left join jichu_jiagonghu d on x.jiagonghuId=d.id
                left join caiwu_ar_guozhang g on g.chuku2proId=x.id
                where y.kind='{$this->_kind}' ";

        if($orderId){
            $sql.= " and y.orderId ={$orderId}";
        }

        if(isset($form['ord2proId'])){
            $sql.= " and x.ord2proId ={$ord2proId}";
        }

        if(isset($form['jiagonghuId'])){
            $sql.= " and x.jiagonghuId ={$jiagonghuId}";
        }

        if(!empty($form['dateFrom'])){
            $sql.= " and y.chukuDate>= '{$dateFrom}'";
        }

        if(!empty($form['dateTo'])){
            $sql.= " and y.chukuDate<='{$dateTo}'";
        }

        if($kuwei){
            $sql .=" and y.kuwei in ('{$kuwei}')";
        }

        /* 入库分为：初始化，成品初始化，采购入库，生产入库；
            出库分为：销售出库，领料出库，发外领料，其他出库
        ** 子类大多都是调用父类的right方法，通过子类中的_kind字段，来区分是查询的哪一种出入库类型；对于原料的销售出库与成品的销售出库，通过再$curState即_state来区分
        */



        $curState = $this->_state;
        //用来区分是原料与成品的销售出库
        if($curState=='成品'&&$this->_kind=='销售出库'){
            $sql .=" and y.depId =0 ";
        }elseif($this->_kind=='发外领料'){
            //发外领料时，领料单位是加工户  ,如果depId <>0 将查询不到，因此区分开来
            $sql.=" and y.depId =0 ";
        }else{
            $sql .=" and y.depId <>0 ";
        }
        $sql .= " order by  chukuCode desc,y.chukuDate desc";

        //得到总计
        $zongji = $this->getZongji($sql,array('cnt'=>'x.cnt','money'=>'x.money'));

        $rowset = $this->_modelExample->findBySql($sql);

        //找到所有属于成品的库位
        $str = "select kuweiName from jichu_kuwei where type='成品'";
        $arrKuwei = $this->_modelExample->findBySql($str);

        if (count($rowset) > 0) foreach($rowset as &$value) {


            $value['cnt']=round($value['cnt'],1);
            $value['cntOrg']=round($value['cntOrg'],1);

            //得到客户
            if($value['clientId']) {
                $m = & FLEA::getSingleton('Model_Jichu_Client');
                $c = $m->find(array('id'=>$value['clientId']));
                $value['clientName'] = $c['compName'];
            }
        }
        // 合计行
        $heji = $this->getHeji($rowset, array('cntOrg', 'cnt'), '_edit');
        $rowset[] = $heji;
        // 显示信息
        $arrFieldInfo = array("_edit" => '操作')+$this->fldRight;

        $smarty = &$this->_getView();
        $smarty->assign('title', '订单查询');
        // $smarty->assign('pk', $this->_modelDefault->primaryKey);
        $smarty->assign('arr_field_info', $arrFieldInfo);
        $smarty->assign('add_display', 'none');
        $smarty->assign('arr_field_value', $rowset);
        $msg = "<font color='red'>数量总计:{$zongji['cnt']},金额总计:{$zongji['money']}</font>";
        $smarty->assign('arr_js_css', $this->makeArrayJsCss(array('grid', 'calendar')));
        $this->_beforeDisplayRight($smarty);
        $smarty->display('TableList.tpl');
    }

    /**
     * 编辑界面中ajax删除
     */
    function actionRemoveByAjax() {
        //dump($_POST);die;
        $m = &FLEA::getSingleton('Model_Cangku_Chengpin_Chuku2Product');
        $r = $m->removeByPkv($_POST['id']);
        if (!$r) {
            $arr = array('success' => false, 'msg' => '删除失败');
            echo json_encode($arr);
            exit;
        }
        $arr = array('success' => true);
        echo json_encode($arr);
        exit;
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
        $smarty->display('Cangku/Chengpin/tzKucun.tpl');
    }

    /**
     * @desc ：库存调整记录保存
     * Time：2016/12/19 13:09:17
     * @author Wuyou
    */
    function actionSaveTzKucun(){
        //dump($_POST);die;
        if($_POST['cntReal']==''){
           $tzCnt = 0;
        }else{
           $tzCnt = $_POST['cntKucun'] - $_POST['cntReal'];
        }
        if($_POST['jianReal']==''){
            $tzJian = 0;
        }else{
            $tzJian = $_POST['cntJianKucun'] - $_POST['jianReal'];
        }
        
        $Products[] = array(
            'productId' => $_POST['productId'],
            'pihao' => $_POST['pihao'].'',
            'ganghao' => $_POST['ganghao'],
            'cnt' => $tzCnt,
            'cntJian' => $tzJian,
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
        // if($tzCnt!=0) 
        $itemId = $this->_modelExample->save($arr);
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
        $this->authCheck('3-2-14');
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
        $str="select kuweiName from jichu_kuwei where type='成品'";
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