<?php
/*********************************************************************\
*  Copyright (c) 1998-2013, TH. All Rights Reserved.
*  Author :Jeff
*  FName  :Ruku.php
*  Time   :2014/05/13 18:31:40
*  Remark :成品入库初始化
\*********************************************************************/
FLEA::loadClass('Controller_Cangku_Ruku');
class Controller_Cangku_Chengpin_RukuInit extends Controller_Cangku_Ruku {
    // var $fldMain;
    // var $headSon;
    // var $rules;//表单元素的验证规则
    // **************************************构造函数 begin********************************
    function __construct() {
        $this->_state = '成品';
        $this->_head = 'CCSHA';
        $this->_kind='成品初始化';
        $this->_modelDefault = &FLEA::getSingleton('Model_Cangku_Chengpin_Ruku');
        $this->_modelExample = &FLEA::getSingleton('Model_Cangku_Chengpin_Ruku');
        $this->_modelMain = &FLEA::getSingleton('Model_Cangku_Chengpin_Ruku');
        $this->_modelSon = &FLEA::getSingleton('Model_Cangku_Chengpin_Ruku2Product');

        //浏览界面的字段
        $this->fldRight = array(
            "rukuDate" => "入库日期",
            "kind" => "类别",
            'kuwei' => '库位',
            'proCode' => '产品编码',
            'proName' => '品名',
            'guige' => '规格',
            'color' => '颜色',
            'cntJian'=>'件数',
            'cnt' => '公斤数',
            'rukuCode' => array("text"=>'单号','width'=>150),
            'memo' => '备注'
        );

        // 定义模板中的主表字段
        $this->fldMain = array(
            'rukuDate' => array('title' => '入库日期', "type" => "calendar", 'value' => date('Y-m-d')),
            'rukuCode' => array('title' => '入库单号', 'type' => 'text', 'readonly' => true, 'value' => ''),
            // 'orderId' => array(
            // 	'title' => '相关订单',
            // 	'type' => 'popup',
            // 	'value' => '',
            // 	'name'=>'orderId',
            // 	'text'=>'',
            // 	'url'=>url('Trade_Order','Popup'),
            // 	'onSelFunc'=>'onSel',//选中后需要执行的回调函数名,需要在jsTpl中书写
            // 	'textFld'=>'orderCode',//显示在text中的字段
            // 	'hiddenFld'=>'orderId',//显示在hidden控件中的字段
            // ),

            'kind' => array('title' => '入库类别', 'type' => 'text', 'value' => $this->_kind, 'readonly' => true),
            'kuwei' => array('title' => '库位选择', 'type' => 'kuwei', 'value' => ''),
            'memo' => array('title' => '备注', 'type' => 'textarea', 'disabled' => true, 'name' => 'rukuMemo'),
            'id' => array('type'=>'hidden', 'value'=>$_GET['id'],'name'=>'rukuId'),
        );
        // /从表表头信息
        // /type为控件类型,在自定义模板控件
        // /title为表头
        // /name为控件名
        // /bt开头的type都在webcontrolsExt中写的,如果有新的控件，也需要增加
        $this->headSon = array(
            '_edit' => array('type' => 'btBtnRemove', "title" => '+5行', 'name' => '_edit[]'),
            // 'proCode' => array('type' => 'bttext', "title" => '产品编码', 'name' => 'proCode[]','readonly'=>true),
            'productId' => array(
                'title' => '物料编码',
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
            'color' => array('type' => 'bttext', "title" => '颜色', 'name' => 'color[]', 'readonly' => true),
            'menfu' => array('type' => 'bttext', "title" => '门幅', 'name' => 'menfu[]', 'readonly' => true),
            'kezhong' => array('type' => 'bttext', "title" => '克重', 'name' => 'kezhong[]', 'readonly' => true),
            // 'dengji' => array('type' => 'bttext', "title" => '规格', 'name' => 'guige[]', 'readonly' => true),
            // 'unit'=>array('type'=>'bttext',"title"=>'单位','name'=>'unit[]','readonly'=>true),
            'ganghao' => array('type' => 'bttext', 'title' => '缸号', 'name' => 'ganghao[]'),
            // 'pihao' => array('type' => 'bttext', 'title' => '批号', 'name' => 'pihao[]'),
            'dengji' => array('type' => 'btSelect', "title" => '等级', 'name' => 'dengji[]','options'=>array(
                array("text"=>'一等品',"value"=>'一等品',),
                array("text"=>'二等品',"value"=>'二等品',),
                array("text"=>'等外品',"value"=>'等外品',),
            )),
            'cntJian' => array('type' => 'bttext', "title" => '件数', 'name' => 'cntJian[]'),
            'cnt' => array('type' => 'bttext', "title" => '公斤数', 'name' => 'cnt[]'),
            'jiagonghuId' => array('title' => '整理厂', 'type' => 'btselect', 'value' => '','name' => 'jiagonghuId[]', 'model' => 'Model_Jichu_Jiagonghu'),
            // 'cntM' => array('type' => 'bttext', "title" => '米数', 'name' => 'cntM[]'),
            'memo' => array('type' => 'bttext', "title" => '备注', 'name' => 'memo[]'),
            //'productId' => array('type' => 'bthidden', 'name' => 'productId[]'),
            // 'ord2proId' => array('type' => 'bthidden', 'name' => 'ord2proId[]'),
            'id' => array('type' => 'bthidden', 'name' => 'id[]'),
            );
        // 表单元素的验证规则定义
        $this->rules = array('rukuDate' => 'required',
            // 'orderDate'=>'required',
            'kuwei' => 'required',
            'jiagonghuId[]'=>'required'
            // 'traderId'=>'required'
        );

    }

    //新增时调整子模板
    function _beforeDisplayAdd(&$smarty) {
        // 从表信息字段,默认5行,等级默认一等品
        for($i = 0;$i < 5;$i++) {
            $rowsSon[]['dengji']['value'] = '一等品';
        }
        $smarty->assign('rowsSon', $rowsSon);
        // $smarty->assign('sonTpl', 'Cangku/Chengpin/jsRuku.tpl');
    }

    //修改时要显示订单号
    function _beforeDisplayEdit(&$smarty) {
        // $rowsSon = $smarty->_tpl_vars['rowsSon'];
        // $areaMain = & $smarty->_tpl_vars['areaMain'];
        // // dump($smarty->_tpl_vars);dump($areaMain);exit;
        // $orderId= $areaMain['fld']['orderId']['value'];
        // $sql = "select orderCode from trade_order where id='{$orderId}'";
        // // dump($sql);
        // $_rows = $this->_modelExample->findBySql($sql);

        // $areaMain['fld']['orderId']['text'] = $_rows[0]['orderCode'];
    }

    //去掉打印按钮
    function _beforeDisplayRight(&$smarty) {

    }

    function actionRight(){
        $this->authCheck('3-2-1');
        // $this->authCheck('3-3');
        FLEA::loadClass('TMIS_Pager');
        // /构造搜索区域的搜索类型
        $serachArea = TMIS_Pager::getParamArray(array(
            'dateFrom' => date("Y-m-01"),
            'dateTo' => date("Y-m-d"),
            'supplierId' => '',
            'key' => '',
        ));
        $sql = "select  y.rukuCode,
                        y.kuwei,
                        y.rukuDate,
                        y.supplierId,
                        y.memo as rukuMemo,
                        y.kind,
                        y.songhuoCode,
                        x.id,
                        x.pihao,
                        x.rukuId,
                        x.productId,
                        x.cnt,
                        x.danjia,
                        x.money,
                        x.memo,
                        x.cntJian,
                        x.ganghao,
                        x.cntM,
                        b.proCode,
                        b.proName,
                        b.guige,
                        b.menfu,
                        b.kezhong,
                        b.color,
                        b.kind as proKind,
                        a.compName as compName,
                        c.compName as jghName
            from cangku_common_ruku y
            left join cangku_common_ruku2product x on y.id=x.rukuId
            left join jichu_supplier a on y.supplierId=a.id
            left join jichu_product b on x.productId=b.id
            left join jichu_jiagonghu c on x.jiagonghuId=c.id
            where y.kind='{$this->_kind}'
            ";
        $sql .= " and rukuDate >= '{$serachArea['dateFrom']}' and rukuDate<='{$serachArea['dateTo']}'";
        if ($serachArea['key'] != '') {
            if(strpos($serachArea['key'], '+')){
                $tempKey = explode('+', $serachArea['key']);
                foreach ($tempKey as & $v) {
                  $strObj[] = " b.guige like '%{$v}%'";
                }
                $guigeStr = join(' and ', $strObj);
                $sql .= " and (b.proName like '%{$serachArea['key']}%'
                        or b.proCode like '%{$serachArea['key']}%'
                        or ({$guigeStr})
                        or x.ganghao like '%{$serachArea['key']}%'
                        or y.rukuCode like '%{$serachArea['key']}%')";
            }else{
                $sql .= " and (b.proName like '%{$serachArea['key']}%'
                        or b.proCode like '%{$serachArea['key']}%'
                        or b.guige like '%{$serachArea['key']}%'
                        or x.ganghao like '%{$serachArea['key']}%'
                        or y.rukuCode like '%{$serachArea['key']}%')";
            }
        }
        if ($serachArea['rukuCode'] != '') $sql .= " and y.rukuCode like '%{$serachArea['rukuCode']}%'";
        if ($serachArea['supplierId'] != '') $sql .= " and y.supplierId = '{$serachArea['supplierId']}'";
        $sql .= " order by b.proCode,y.rukuCode desc";
        //得到总计
        //dump($sql);exit;
        $zongji = $this->getZongji($sql,array('cnt'=>'x.cnt','money'=>'x.money','cntJian'=>'x.cntJian'));
        //dump($sql);exit;
        $pager = &new TMIS_Pager($sql);
        $rowset = $pager->findAll();
        if (count($rowset) > 0) foreach($rowset as &$value) {
            $value['_edit'] = "<a href='" . $this->_url('View', array('id' => $value['rukuId'])) . "' target='_blank' title='$title'>打印</a>";
            //$tip = "ext:qtip='已过账禁止修改'";
            if($value['kind']=='采购退货') {
                $value['_edit'] .= " <a href='".url('Cangku_Yuanliao_Cgth','Edit',array(
                    'id'=>$value['rukuId'],
                    'fromAction' => $_GET['action']
                ))."'>修改</a>";
            } elseif($value['kind']=='生产入库') {
                $value['_edit'] .= " <a href='".url('Cangku_Chengpin_Ruku','Edit',array(
                    'id'=>$value['rukuId'],
                    'fromAction' => $_GET['action']
                ))."'>修改</a>";
                // 设置码单
                // 查找是否存在码单
                // $sql="select id from cangku_madan where ruku2proId='{$value['id']}' limit 0,1";
                // $temp=$this->_modelExample->findBySql($sql);
                // $color='';
                // $title='';
                // if($temp[0]['id']>0){
                // 	$color="green";
                // 	$title="码单已设置";
                // }
                // $value['_edit'] .=" <a style='color:{$color}' href='".url('Cangku_Chengpin_Ruku','SetMadan',array('ruku2proId'=>$value['id']))."' title='{$title}'>码单</a>";
            } elseif($value['kind']=='成品初始化') {
                $value['_edit'] .= " <a href='".url('Cangku_Chengpin_RukuInit','Edit',array(
                    'id'=>$value['rukuId'],
                    'fromAction' => $_GET['action']
                ))."'>修改</a>";
            } else {
                $value['_edit'] .= " <a href='".url('Cangku_Yuanliao_Ruku','Edit',array(
                    'id'=>$value['rukuId'],
                    'fromAction' => $_GET['action']
                ))."'>修改</a>";
            }
            $value['_edit'] .= " ".$this->getRemoveHtml($value['rukuId']);

            //码单导出
            if($value['kind']=='生产入库'){
                $sql="select id from cangku_madan where ruku2proId='{$value['id']}' limit 0,1";
                $temp=$this->_modelExample->findBySql($sql);
                $color='';
                $title='';
                if($temp[0]['id']>0){
                    //有码单入库信息
                    $value['_edit'] .=" <a style='color:{$color}' href='".url('Cangku_Chengpin_Madan','SelexportRuku',array('id'=>$value['id']))."' target='_blank' title='{$title}'>码单导出</a>";
                }

            }

            if($value['cnt']<0) $value['_bgColor'] = 'pink';
        }
        // 合计行
        $heji = $this->getHeji($rowset, array('cnt','cntJian'), '_edit');
        $rowset[] = $heji;
        // 左边信息
        $arrFieldInfo = array(
            '_edit'=>array("text"=>'操作','width'=>170),
            "rukuDate" => "入库日期",
            'jghName' => array("text"=>'整理厂','width'=>150),
            'ganghao' => '缸号',
            'proCode' => '产品编码',
            'proName' => '品名',
            'guige' => '规格',
            'menfu' => '门幅',
            'kezhong' => '克重',
            'color' => '颜色',
            'cntJian' => '件数',
            'cnt' => '数量',
            'danjia' => '单价',
            'money' => '金额',
            'rukuCode' => array("text"=>'入库单号','width'=>150),
            'memo' => '备注'
        );


        $smarty = &$this->_getView();
        $smarty->assign('title', '库存初始化');
        $smarty->assign('arr_field_info', $arrFieldInfo);
        $smarty->assign('arr_condition', $serachArea);
        $smarty->assign('arr_field_value', $rowset);
        $msg = "<font color='red'>数量总计:{$zongji['cnt']},金额总计:{$zongji['money']}</font>";
        $smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action']), $serachArea).$msg);
        $smarty->assign('arr_js_css', $this->makeArrayJsCss(array('grid', 'calendar')));
        $this->_beforeDisplayRight($smarty);
        $smarty->display('TableList.tpl');
        // $smarty->display('TblListMore.tpl');
    }
}