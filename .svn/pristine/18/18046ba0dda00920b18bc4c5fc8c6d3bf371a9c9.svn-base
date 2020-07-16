<?php
/*********************************************************************\
*  Copyright (c) 1998-2013, TH. All Rights Reserved.
*  Author :jeff
*  FName  :Ruku.php
*  Time   :2014/05/13 18:27:29
*  Remark :仓库入库的通用控制器
\*********************************************************************/
FLEA::loadClass('Tmis_Controller');
// / 父类，被Controller_Shengchan_Yuliao_ruku继承
class Controller_Cangku_Ruku extends Tmis_Controller {
    var $fldMain;
    var $headSon;
    var $rules; //表单元素的验证规则
    var $_modelDefault;
    var $_modelExample;
    var $_modelMain;
    var $_modelSon;
    // var $_state;//状态 原料|已热轧|已精轧|已电解|已切断
    var $_head;//单据前缀
    var $_kind;//入库类型
    var $fldRight;//浏览时需要显示的字段

    /**
     * 构造函数
     */
    function __construct() {
        $this->_modelDefault = &FLEA::getSingleton('Model_Cangku_Ruku');
        $this->_modelExample = &FLEA::getSingleton('Model_Cangku_Ruku');
        $this->_modelMain = &FLEA::getSingleton('Model_Cangku_Ruku');
        $this->_modelSon = &FLEA::getSingleton('Model_Cangku_Ruku2Product');
        //浏览界面的字段
        $this->fldRight = array(
            //'id'=>'id',
            "rukuDate" => "入库日期",
            "kind" => "类别",
            'kuwei' => '库位',
            // 'state' => '状态',
            // 'proKind' => '种类',
            'proCode' => '产品编码',
            'proName' => '品名',
            'guige' => '规格',
            'color' => '颜色',
            'pihao'=>'批号',
            'ganghao'=>'缸号',
            'cntM' => '数量',
            'cnt' => array("text"=>'数量(折合公斤数)','width'=>115),
            'danjia' => '单价',
            'money' => '金额',
            "compName" => "供应商",
            'songhuoCode' => '送货单号',
            'rukuCode' => array("text"=>'入库单号','width'=>150),
            'memo' => '备注'
        );
        //得到库位信息
        // 生成库位 名称信息
        $m = & FLEA::getSingleton('Model_Jichu_Client');
        $sql = "select * from jichu_kuwei where 1";
        $rowset = $m->findBySql($sql);
        foreach($rowset as &$v) {
            // *根据要求：options为数组,必须有text和value属性
            $rowsKuwei[] = array('text' => $v['kuweiName'], 'value' => $v['kuweiName']);
        }

        // 定义模板中的主表字段
        $this->fldMain = array(
            // /*******2个一行******
            'rukuDate' => array('title' => '入库日期', "type" => "calendar", 'value' => date('Y-m-d')),
            // 入库单号，自动生成
            'rukuCode' => array('title' => '入库单号', 'type' => 'text', 'readonly' => true, 'value' => ''),
            // /*******2个一行******
            // 'supplierpopup需要显示供应商名称，所以需要定义supplierName属性,value属性作为supplierId用
            // 'supplierId' => array('title' => '供应商', 'type' => 'selectgys', 'value' => '', 'model' => 'Model_Jichu_Supplier'),
            'supplierId' => array(
                    'type' => 'popup',
                    "title" => '供应商',
                    'name' => 'supplierId',
                    'url'=>url('Jichu_supplier','Popup'),
                    'textFld'=>'compName',
                    'hiddenFld'=>'id',
            ),
            'songhuoCode' => array('title' => '送货单号', 'type' => 'text', 'value' => ''),
            'kind' => array('title' => '入库类别', 'type' => 'text', 'value' => $this->_kind, 'readonly' => true),
            'kuwei' => array('title' => '库位选择', 'type' => 'kuwei', 'value' => '','kwType'=>$this->_state),
            // 'state' => array('title' => '状态', 'type' => 'text', 'value' =>$this->_state, 'readonly'=>true),
            // /*******2个一行******
            // 定义了name以后，就不会以memo作为input的id了
            'memo' => array('title' => '备注', 'type' => 'textarea', 'disabled' => true, 'name' => 'rukuMemo'),
            // 下面为隐藏字段
            'id' => array('type'=>'hidden', 'value'=>$_GET['id'],'name'=>'rukuId'),
            // 'isGuozhang' => array('type' => 'hidden', 'value' => ''),
        );
        // /从表表头信息
        // /type为控件类型,在自定义模板控件
        // /title为表头
        // /name为控件名
        // /bt开头的type都在webcontrolsExt中写的,如果有新的控件，也需要增加
        $this->headSon = array(
            '_edit' => array('type' => 'btBtnCopy', "title" => '+5行', 'name' => '_edit[]'),
            // 'productId' => array('type' => 'btProductpopup', "title" => '产品选择', 'name' => 'productId[]'),
            'productId' => array(
                'title' => '物料编码',
                'type' => 'BtPopup',
                'value' => '',
                'name'=>'productId[]',
                'text'=>'选择入库',
                'url'=>url('jichu_product','popupNew'),//弹出地址,回调函数在sonTpl中进行定义,主要是对缓存中的关联数组进行重写
                'textFld'=>'proCode',//显示在text中的字段
                'hiddenFld'=>'id',//显示在hidden控件中的字段
                'inTable'=>1,
            ),
            'proName' => array('type' => 'bttext', "title" => '品名', 'name' => 'proName[]', 'readonly' => true),
            'guige' => array('type' => 'bttext', "title" => '规格', 'name' => 'guige[]', 'readonly' => true),
            'color' => array('type' => 'bttext', "title" => '颜色', 'name' => 'color[]', 'readonly' => true),
            // 'dengji' => array('type' => 'bttext', "title" => '规格', 'name' => 'guige[]', 'readonly' => true),
            // 'unit'=>array('type'=>'bttext',"title"=>'单位','name'=>'unit[]','readonly'=>true),
            'shuhao' => array('type' => 'bttext', 'title' => '束号', 'name' => 'shuhao[]'),
            'ganghao'=>array('type'=>'bttext','title'=>'缸号(批号)','name'=>'ganghao[]'),
            'cntJian'=>array('type'=>'bttext',"title"=>'件数','name'=>'cntJian[]'),
            'cnt' => array('type' => 'bttext', "title" => '数量(kg)', 'name' => 'cnt[]'),
            'danjia'=>array('type'=>'bttext',"title"=>'单价','name'=>'danjia[]'),
            'money'=>array('type'=>'bttext',"title"=>'金额(元)','name'=>'money[]'),
            // 'kuweiId'=>array('type'=>'btkuweiselect','title'=>'库位选择','name'=>'kuweiId[]'),
            'memo' => array('type' => 'bttext', "title" => '备注', 'name' => 'memo[]'),
            // ***************如何处理hidden?
            'id' => array('type' => 'bthidden', 'name' => 'id[]'),
            );
        // 表单元素的验证规则定义
        $this->rules = array('rukuDate' => 'required',
            // 'orderDate'=>'required',
            'supplierId' => 'required',
            'kuwei' => 'required',
            // 'traderId'=>'required'
        );
    }
    /**
     * 新增
     */
    function actionAdd() {
        // 默认库位
        $kuwei = $this->_state;
        $str = "select kuweiName from jichu_kuwei where type='{$kuwei}'";
        $res = $this->_modelExample->findBySql($str);
        // 主表信息字段
        $fldMain = $this->fldMain;
        // *入库号的默认值的加载*
        $fldMain['rukuCode']['value'] = $this->_getNewCode($this->_head, $this->_modelMain->qtableName, 'rukuCode');
        $fldMain['kuwei']['value'] = $res[0]['kuweiName'];
        // 判断是否需要过账 0要 1否
        // $fldMain['isGuozhang']['value'] = 1;
        // dump($fldMain);exit;
        //
        $headSon = $this->headSon;
        // 从表信息字段,默认5行
        for($i = 0;$i < 5;$i++) {
            $rowsSon[] = array();
        }
        // 主表区域信息描述
        $areaMain = array('title' => $this->_kind.'基本信息', 'fld' => $fldMain);
        // 从表区域信息描述
        $smarty = &$this->_getView();
        $smarty->assign('areaMain', $areaMain);
        $smarty->assign('headSon', $headSon);
        $smarty->assign('rowsSon', $rowsSon);
        $smarty->assign('rules', $this->rules);
        $smarty->assign('sonTpl', 'Cangku/Yuanliao/_callbackProduct.tpl');
        $this->_beforeDisplayAdd($smarty);
        // dump($smarty);exit;
        $smarty->display('Main2Son/T1.tpl');
    }

    /**
     * 编辑
     */
    function actionEdit() {
        $arr = $this->_modelMain->find(array('id' => $_GET['id']));
        // dump($arr);exit;
        foreach ($this->fldMain as $k => &$v) {
            $v['value'] = $arr[$k]?$arr[$k]:$v['value'];
        }

        //设置rukuId的值
        $this->fldMain['id']['value'] = $arr['id'];
        if(isset($this->fldMain['supplierId'])){
            $this->fldMain['supplierId']['text']=$arr['Supplier']['compName'];
        }

        // //加载库位信息的值
        $areaMain = array('title' => '入库基本信息', 'fld' => $this->fldMain);
        // 入库明细处理
        $rowsSon = array();
        foreach($arr['Products'] as &$v) {
            $sql = "select * from jichu_product where id='{$v['productId']}'";
            $_temp = $this->_modelDefault->findBySql($sql); //dump($_temp);exit;
            $v['proCode'] = $_temp[0]['proCode'];
            $v['proName'] = $_temp[0]['proName'];
            $v['guige'] = $_temp[0]['guige'];
            $v['menfu'] = $_temp[0]['menfu'];
            $v['kezhong'] = $_temp[0]['kezhong'];
            $v['color'] = $_temp[0]['color'];
        }
        foreach($arr['Products'] as &$v) {
            $temp = array();
            foreach($this->headSon as $kk => &$vv) {
                $temp[$kk] = array('value' => $v[$kk]);
            }
            // dump($v);exit;
            //定义弹出选择控件的text属性为proCode
            $temp['productId']['text'] = $v['proCode'];
            $rowsSon[] = $temp;
        }
        // dump($rowsSon);exit;
        //补齐5行
        $cnt = count($rowsSon);
        for($i=5;$i>$cnt;$i--) {
            $rowsSon[] = array();
        }

        // $this->fldMain = $this->getValueFromRow($this->fldMain, $row);
        // $this->fldMain['supplierId']['text']=$row['Supplier']['compName'];
        $smarty = &$this->_getView();
        // $smarty->assign('fldMain', $this->fldMain);
        $smarty->assign('areaMain', $areaMain);
        $smarty->assign('headSon', $this->headSon);
        $smarty->assign('rowsSon', $rowsSon);
        $smarty->assign('rules', $this->rules);
        $smarty->assign('sonTpl', 'Cangku/Yuanliao/_callbackProduct.tpl');
        $this->_beforeDisplayEdit($smarty);
        // dump($smarty);dump(get_class_vars($smarty));dump(get_class_methods($smarty));exit;
        $smarty->display('Main2Son/T1.tpl');
    }

    /**
     * 保存
     */
    function actionSave() {
        //dump($_POST);exit;
        //根据headSon,动态组成明细表数据集
        $cangku_common_ruku2product = array();
        foreach ($_POST['productId'] as $key => $v) {
            // 当没有记录 或者 没有输入数量时 跳出本次循环 防止多保存
            if (empty($_POST['productId'][$key]) || empty($_POST['cnt'][$key])) continue;
            $temp = array();
            foreach($this->headSon as $k=>&$vv) {
                $temp[$k] = $_POST[$k][$key];
            }
            $cangku_common_ruku2product[] = $temp;
        }

        //如果没有选择物料，返回
        if(count($cangku_common_ruku2product)==0) {
            js_alert('请选择有效物料并输入有效数量!','window.history.go(-1)');
            exit;
        }
        // cangku_common_ruku 表 的数组
        foreach($this->fldMain as $k=>&$vv) {
            $name = $vv['name']?$vv['name']:$k;
            $cangku_common_ruku[$k] = $_POST[$name];
        }
        // 表之间的关联
        $cangku_common_ruku['Products'] = $cangku_common_ruku2product;
        // dump($_POST);dump($cangku_common_ruku);exit;

        $row = $this->notNull($cangku_common_ruku);

        $row['creater'] = $_SESSION['REALNAME'];

        // 保存 并返回cangku_common_ruku表的主键
        $itemId = $this->_modelExample->save($row);

        if (!$itemId) {
            echo "保存失败";
            exit;
        }
        js_alert(null, 'window.parent.showMsg("保存成功!")', url($_POST['fromController'],$_POST['fromAction']));
    }

    /**
     * 浏览
     *$isShowAdd:是否显示新增按钮
     */
    function actionRight($isShowAdd) {
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
                        x.cntM,
                        b.proCode,
                        b.proName,
                        b.guige,
                        b.color,
                        b.kind as proKind,
                        a.compName as compName
                from cangku_common_ruku y
                left join cangku_common_ruku2product x on y.id=x.rukuId
                left join jichu_supplier a on y.supplierId=a.id
                left join jichu_product b on x.productId=b.id
                where y.kind='{$this->_kind}'
            ";
        $sql .= " and rukuDate >= '{$serachArea['dateFrom']}' and rukuDate<='{$serachArea['dateTo']}'";
        if ($serachArea['key'] != '') $sql .= " and (b.proName like '%{$serachArea['key']}%'
                                            or b.proCode like '%{$serachArea['key']}%'
                                            or b.guige like '%{$serachArea['key']}%'
                                            or y.rukuCode like '%{$serachArea['key']}%')";
        if ($serachArea['rukuCode'] != '') $sql .= " and y.rukuCode like '%{$serachArea['rukuCode']}%'";
        if ($serachArea['supplierId'] != '') $sql .= " and y.supplierId = '{$serachArea['supplierId']}'";
        $sql .= " order by y.rukuCode desc";
        //得到总计
        $zongji = $this->getZongji($sql,array('cnt'=>'x.cnt','money'=>'x.money'));
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
        $heji = $this->getHeji($rowset, array('cnt'), '_edit');
        $rowset[] = $heji;
        // 左边信息
        $arrFieldInfo = array(
            '_edit'=>array("text"=>'操作','width'=>170),
            "rukuDate" => "入库日期",
            "kind" => "类别",
            'kuwei' => '库位',
            // 'state' => '状态',
            // 'proKind' => '种类',
            'proCode' => '产品编码',
            'proName' => '品名',
            'guige' => '规格',
            'color' => '颜色',
            'pihao'=>'批号',
            // 'cntJian' => '件数',
            'cnt' => '数量',
            'danjia' => '单价',
            'money' => '金额',
            "compName" => "供应商",
            'songhuoCode' => '送货单号',
            'rukuCode' => array("text"=>'入库单号','width'=>150),
            'memo' => '备注'
        );
        // array_unshift($arrFieldInfo,);
        // dump($arrFieldInfo);exit;


        $smarty = &$this->_getView();
        $smarty->assign('title', '订单查询');
        $smarty->assign('arr_field_info', $arrFieldInfo);
        $smarty->assign('add_display', $isShowAdd?'display':'none');
        $smarty->assign('arr_condition', $serachArea);
        $smarty->assign('arr_field_value', $rowset);
        $msg = "<font color='red'>数量总计:{$zongji['cnt']},金额总计:{$zongji['money']}</font>";
        $smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action']), $serachArea).$msg);
        $smarty->assign('arr_js_css', $this->makeArrayJsCss(array('grid', 'calendar')));
        $this->_beforeDisplayRight($smarty);
        $smarty->display('TableList.tpl');
        // $smarty->display('TblListMore.tpl');
    }

    /**
     * 打印
     */
    function actionView() {
        $m = & $this->_modelSon;
        $rowset = $m->find($_GET['id']);
        $row = $this->_modelMain->find(array('id'=>$_GET['id']));
        // dump($row);
        foreach($row['Products'] as &$v) {
            $sql = "select * from jichu_product where id='{$v['productId']}'";
            $_temp = $this->_modelMain->findBySql($sql); //dump($_temp);exit;
            // dump($v);dump($_temp);exit;
            $v['proCode'] = $_temp[0]['proCode'];
            $v['proName'] = $_temp[0]['proName'];
            $v['guige'] = $_temp[0]['guige'];
        }
        //补齐5行
        $cnt = count($row['Products']);
        for($i=5;$i>$cnt;$i--) {
            $row['Products'][] = array();
        }
        // dump($row);exit;
        $main = array(
            '单号'=>$row['rukuCode'],
            '入库日期'=>$row['rukuDate'],
            '供应商'=>$row['Supplier']['compName'],
            '库位'=>$row['kuwei'],
            '送货单号'=>$row['songhuoCode'],
            // '单号'=>,
            // '单号'=>,
            // '单号'=>,
        );
        $smarty = &$this->_getView();
        $smarty->assign("arr_main_value", $main);
        $smarty->assign("arr_field_info", array(
            // "_edit" => '操作',
            // "rukuDate" => "入库日期",
            // 'rukuCode' => array("text"=>'入库单号','width'=>150),
            // "kind" => "类别",
            // 'kuwei' => '库位',
            // 'proKind' => '种类',
            'proCode' => '产品编码',
            'proName' => '品名',
            'guige' => '规格',
            'color' => '颜色',
            'pihao'=>'批号',
            // 'color' => '颜色',
            'cnt' => '数量',
            'danjia' => '单价',
            'money' => '金额',
            // "compName" => "供应商",
            // 'songhuoCode' => '送货单号',
            'memo' => '备注'
        ));
        $smarty->assign("arr_field_value", $row['Products']);
        $this->_beforeDisplayRight($smarty);
        $smarty->display('Print.tpl');
    }

    /**
     * 编辑界面利用ajax删除
     */
    function actionRemoveByAjax() {
        $m = & $this->_modelSon;
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
     * 收发存报表中的入库数点击后弹出明细窗口
     */
    function actionPopup() {
        FLEA::loadClass('TMIS_Pager');
        // /构造搜索区域的搜索类型
        $arr = TMIS_Pager::getParamArray(array(
            'dateFrom' => $_GET['dateFrom'],
            'dateTo' => $_GET['dateTo'],
            'pihao'=>$_GET['pihao'],
            'ganghao'=>$_GET['ganghao'],
            'productId' => $_GET['productId'],
            'kuwei' => $_GET['kuwei'],
        ));
        $sql = "select
            y.rukuCode,
            y.kuwei,
            y.rukuDate,
            y.supplierId,
            y.memo as rukuMemo,
            y.kind,
            y.songhuoCode,
            x.id,
            x.pihao,
            x.ganghao,
            x.rukuId,
            x.productId,
            x.cnt,
            x.danjia,
            x.money,
            x.memo,
            b.proCode,
            b.proName,
            b.guige,
            b.color,
            b.kind as proKind,
            a.compName as compName
            from cangku_common_ruku y
            left join cangku_common_ruku2product x on y.id=x.rukuId
            left join jichu_supplier a on y.supplierId=a.id
            left join jichu_product b on x.productId=b.id
            where x.pihao='{$arr['pihao']}'";
        $sql .= " and rukuDate >= '{$arr['dateFrom']}' and rukuDate<='{$arr['dateTo']}'";
        if ($arr['productId'] != '') $sql .= " and x.productId = '{$arr['productId']}'";
        if ($arr['kuwei'] != '') $sql .= " and y.kuwei = '{$arr['kuwei']}'";
        $sql .= " and x.ganghao = '{$arr['ganghao']}'";
        $sql .= " order by y.rukuCode desc";
        // dump($sql);exit;

        $pager = &new TMIS_Pager($sql);
        $rowset = $pager->findAll();
        if (count($rowset) > 0) foreach($rowset as &$value) {
        }
        // 合计行
        $heji = $this->getHeji($rowset, array('cnt'), 'rukuDate');
        $rowset[] = $heji;
        // 左边信息
        $arrFieldInfo = $this->fldRight;
        // dump($arrFieldInfo);exit;

        $smarty = &$this->_getView();
        $smarty->assign('title', '订单查询');
        $smarty->assign('arr_field_info', $arrFieldInfo);
        $smarty->assign('add_display', $isShowAdd?'display':'none');
        $smarty->assign('arr_condition', $serachArea);
        $smarty->assign('arr_field_value', $rowset);
        $smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action']), $serachArea));
        $smarty->assign('arr_js_css', $this->makeArrayJsCss(array('grid', 'calendar')));
        $smarty->display('TableList.tpl');
    }

    /**
     * 在过账时需要弹出选择销售出库记录这里的条件不一样
     */
    function actionPopupOnGuozhang() {
        FLEA::loadClass('TMIS_Pager');
        // /构造搜索区域的搜索类型
        $arr = TMIS_Pager::getParamArray(array(
            'dateFrom' => $_GET['dateFrom'],
            'dateTo' => $_GET['dateTo'],
            'kuwei'=>$_GET['kuwei'],
            'productId'=>$_GET['productId'],
            'supplierId' => $_GET['supplierId'],
        ));

        $sql = "select  y.id,y.rukuCode,y.kuwei,y.rukuDate,y.memo as rukuMemo,y.kind,y.supplierId,
                        x.id,x.plan2proId,x.rukuId,x.pihao,x.productId,x.cnt,x.danjia,x.money,x.memo,
                        round(x.money/x.danjia,2) as cntM,
                        b.proCode,b.proName,b.guige,b.color,b.kind as proKind,d.compName
                        from cangku_common_ruku y
                        left join cangku_common_ruku2product x on y.id=x.rukuId
                        left join jichu_product b on x.productId=b.id
                        left join jichu_supplier d on y.supplierId=d.id
                        left join caiwu_yf_guozhang g on g.ruku2proId=x.id
                        where y.kind='采购入库'
                        and x.id not in
                        (select ruku2proId from caiwu_yf_guozhang where 1 and kind='采购入库')
                        ";
        if($arr['dateFrom']!=""){
            $sql .= " and y.rukuDate >= '{$arr['dateFrom']}' and y.rukuDate<='{$arr['dateTo']}'";
        }
        if ($arr['supplierId'] != '') {
            $sql .= " and y.supplierId = '{$arr['supplierId']}'";
        }

        $sql .= " order by y.rukuDate desc ";

        $pager = &new TMIS_Pager($sql);
        $rowset = $pager->findAll();

        if (count($rowset) > 0) foreach($rowset as &$v) {
            $temp=array();
            if($v['proCode']!='')$temp[]=$v['proCode'];
            if($v['proName']!='')$temp[]=$v['proName'];
            if($v['guige']!='')$temp[]=$v['guige'];
            // if($v['chengFen']!='')$temp[]='成分：'.$v['chengFen'];
            if($v['color']!='')$temp[]=$v['color'];
            // if($v['menfu']!='')$temp[]='门幅：'.$v['menfu'];
            // if($v['dengji']!='')$temp[]='等级:'.$v['dengji'];
            // if($v['state']!='')$temp[]='状态:'.$v['state'];
            $v['qitaMemo']=join(' ',$temp);
            // dump($v);exit;
        }
        // 合计行
        $heji = $this->getHeji($rowset, array('cnt','money'), 'rukuDate');
        $rowset[] = $heji;
        // 显示信息
        $arrFieldInfo = $this->fldRight;
        // array_unshift(array, var)
        // array_unshift($arrFieldInfo,array('compName'=>'客户'));
        $arrFieldInfo['compName']='供应商';
        // dump($arrFieldInfo);exit;
        unset($arrFieldInfo['_edit']);
        unset($arrFieldInfo['depName']);

        $smarty = &$this->_getView();
        $smarty->assign('title', '采购入库清单');
        // $smarty->assign('pk', $this->_modelDefault->primaryKey);
        $smarty->assign('arr_field_info', $arrFieldInfo);
        $smarty->assign('add_display', 'none');
        $smarty->assign('arr_condition', $arr);
        $smarty->assign('arr_field_value', $rowset);
        $smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action']), $serachArea));
        // $smarty->assign('arr_js_css', $this->makeArrayJsCss(array('grid', 'calendar')));
        // $smarty->display('TableList.tpl');
        $smarty->display('Popup/CommonNew.tpl');
    }

}

?>