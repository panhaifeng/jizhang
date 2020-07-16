<?php
/*********************************************************************\
*  Copyright (c) 1998-2013, TH. All Rights Reserved.
*  Author :Jeff
*  FName  :Chuku.php
*  Time   :2014/05/13 18:28:05
*  Remark :仓库出库的通用控制器
\*********************************************************************/
FLEA::loadClass('TMIS_Controller');
class Controller_Cangku_Chuku extends Tmis_Controller {
    var $fldMain;
    var $headSon;
    var $rules; //表单元素的验证规则
    var $_modelDefault;
    var $_modelExample;
    var $_modelMain;
    var $_modelSon;
    // var $_state;//库位
    var $_head;//单据前缀
    var $_kind;//入库类型
    var $_fldList;//查询界面和收发存弹出明细中需要用到
    var $_arrKuwei;//收发存报表中，只显示该变量指示的库位.


    function __construct() {
        $this->_modelDefault = &FLEA::getSingleton('Model_Cangku_Yuanliao_Chuku');
        $this->_modelExample = &FLEA::getSingleton('Model_Cangku_Yuanliao_Chuku');
        $this->_modelMain = &FLEA::getSingleton('Model_Cangku_Yuanliao_Chuku');
        $this->_modelSon = &FLEA::getSingleton('Model_Cangku_Yuanliao_Chuku2Product');
        //得到库位信息
        $sql = "select * from jichu_kuwei where 1";
        $rowset = $this->_modelMain->findBySql($sql);
        foreach($rowset as &$v) {
            // *根据要求：options为数组,必须有text和value属性
            $rowsKuwei[] = array('text' => $v['kuweiName'], 'value' => $v['kuweiName']);
        }

        $this->fldMain = array(
            'chukuCode' => array('title' => '出库单号', 'type' => 'text', 'readonly' => true, 'value' => $this->_getNewCode($this->_head, $this->_modelMain->qtableName, 'chukuCode')),
            'chukuDate' => array('title' => '出库日期', "type" => "calendar", 'value' => date('Y-m-d')),
            // 入库单号，自动生成

            'kind' => array('title' => '出库类别', 'type' => 'text', 'value' => $this->_kind, 'readonly' => true),
            // 'supplierpopup需要显示供应商名称，所以需要定义supplierName属性,value属性作为supplierId用
            // 'supplierId' => array('title' => '供应商', 'type' => 'select', 'value' => '', 'model' => 'Model_Jichu_Supplier'),

            // 'orderId' => array('title' => '相关订单', "type" => "orderpopup", 'value' => ''),
            // 'planId' => array('title' => '相关计划', "type" => "planpopup", 'value' => ''),

            'depId' => array('title' => '领料部门', 'type' => 'select', 'value' => '', 'model' => 'Model_Jichu_Department'),
            'lingliaoren' => array('title' => '领料人', 'type' => 'text', 'value' => ''),

            'kuwei' => array('title' => '库位选择', 'type' => 'kuwei', 'value' => '','kwType'=>$this->_state),
            //'state' => array('title' => '状态', 'type' => 'text', 'value' =>$this->_state, 'readonly'=>true),
            // 'gongxuId' => array('title' => '待做工序', 'type' => 'select', 'value' => '','options'=>$rowsKuwei),
            // 定义了name以后，就不会以memo作为input的id了
            'memo' => array('title' => '出库备注', 'type' => 'textarea', 'disabled' => true, 'name' => 'chukuMemo'),
            // 下面为隐藏字段
            'id' => array('type' => 'hidden', 'value' => $_GET['id'],'name'=>'chukuId'),
            // 是色坯纱  用来标示是在色坯纱管理中
            // 'isSePiSha' => array('type' => 'hidden', 'value' => '1', 'name' => 'isSePiSha')
        );

        // /从表表头信息
        $this->headSon = array(
            '_edit' => array('type' => 'btBtnRemove', "title" => '+5行', 'name' => '_edit[]'),
            // 'ord2proId' => array(
            //     'title' => '生产计划', //表头文字
            //     'type' => 'BtPopup',
            //     'value' => '',
            //     'name'=>'ord2proId[]',
            //     'text'=>'',//现在在文本框中的文字
            //     'url'=>url('Shengchan_Plan','popupTl'),//弹出地址,回调函数在sonTpl中进行定义,主要是对缓存中的关联数组进行重写
            //     'textFld'=>'orderCode',//显示在text中的字段
            //     'hiddenFld'=>'id',//显示在hidden控件中的字段
            //     'inTable'=>1,
            // ),
            'ord2proId' => array(
                'type' => 'btPopup',
                "title" => '选择订单',
                'name' => 'ord2proId[]',
                'textFld' => 'orderCode',
                'hiddenFld' => 'ord2proId',
                'url'   =>url('Trade_Order','PopupLy'),
            ),
            'productId' => array('type' => 'btProductpopup', "title" => '产品编码', 'name' => 'productId[]'),
            'proName' => array('type' => 'bttext', "title" => '品名', 'name' => 'proName[]', 'readonly' => true),
            'guige' => array('type' => 'bttext', "title" => '规格', 'name' => 'guige[]', 'readonly' => true),
            'color' => array('type' => 'bttext', "title" => '颜色', 'name' => 'color[]', 'readonly' => true),
            // 'unit'=>array('type'=>'bttext',"title"=>'单位','name'=>'unit[]','readonly'=>true),
            'ganghao' => array('type' => 'bttext', 'title' => '缸号(批号)', 'name' => 'ganghao[]'),
            'cnt' => array('type' => 'bttext', "title" => '数量(kg)', 'name' => 'cnt[]'),
            'cntJian' => array('type' => 'bttext', "title" => '件数', 'name' => 'cntJian[]'),
            //'money' => array('type' => 'bttext', "title" => '金额(元)', 'name' => 'money[]'),
            'memo' => array('type' => 'bttext', "title" => '备注', 'name' => 'memo[]'),
            // ***************如何处理hidden?
            'id' => array('type' => 'bthidden', 'name' => 'id[]'),
            // 'plan2proId' => array('type' => 'bthidden', 'name' => 'plan2proId[]'),
        );
        // 表单元素的验证规则定义
        $this->rules = array('chukuDate' => 'required',
            'depId' => 'required',
            // 'supplierId' => 'required',
            'kuwei' => 'required',
            'kind' => 'required',
        );


        //查询时的字段信息,在查询界面和收发存弹出明细窗口需要用到
        $this->fldRight = array(
            //'id'=>'id',
            "_edit" => '操作',
            "chukuDate" => "出库日期",
            'chukuCode' => '出库单号',
            "kuwei" => "库位",
            'proCode' => '产品编码',
            'proName' => '品名',
            'guige' => '规格',
            'dengji' => '等级',
            'color' => '颜色',
            'ganghao'=>'缸号(批号)',
            'menfu' => '门幅',
            'kezhong' => '克重',
            'depName' => '领用部门',
            // 'zhonglei' => '种类',
            // 'color' => '颜色',
            'cntJian' => '件数',
            'cnt' => '数量(kg)',
            'danjia' => '单价',
            'money' => '金额',
            // ''=>'',
            );

    }

    function actionAdd($Arr) {
        // $this->authCheck('3-4');
        // 主表信息字段
        $fldMain = $this->fldMain;
        // 默认库位
        $kuwei = $this->_state;
        $str = "select kuweiName from jichu_kuwei where type='{$kuwei}'";
        $res = $this->_modelExample->findBySql($str);
        $fldMain['kuwei']['value'] = $res[0]['kuweiName'];

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
        $smarty->assign('sonTpl', 'Cangku/Yuanliao/jsLlck.tpl');
        $this->_beforeDisplayAdd($smarty);
        $smarty->display('Main2Son/T1.tpl');
    }

    // /保存
    function actionSave() {
        // dump($_POST);exit;
        $yuanliao_llck2product = array();
        foreach ($_POST['productId'] as $key => $v) {
            // 当没有记录 或者 没有输入数量时 跳出本次循环 防止多保存
            if (empty($_POST['productId'][$key]) || empty($_POST['cnt'][$key])) continue;
            if($_POST['productId'][$key]!='' && $this->_state=='成品'){
                if($_POST['jiagonghuId'][$key]==''){
                    js_alert('请选择整理厂!','window.history.go(-1)');
                    exit();
                }
            }
            $temp = array();
            foreach($this->headSon as $k=>&$vv) {
                $temp[$k] = $_POST[$k][$key];
            }
            $yuanliao_llck2product[] = $temp;
        }
        // dump($yuanliao_llck2product);exit;
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
        // dump($yuanliao_llck);exit;
        // 表之间的关联
        $yuanliao_llck['Products'] = $yuanliao_llck2product;
        // 保存 并返回yuanliao_cgrk表的主键
        $row = $this->notNull($yuanliao_llck);
         //dump($yuanliao_llck);exit;
        $row['creater'] = $_SESSION['REALNAME'];
        //dump($row);die;
        $itemId = $this->_modelExample->save($this->notNull($yuanliao_llck));

        if (!$itemId) {
            echo "保存失败";
            exit;
        }
        js_alert(null, 'window.parent.showMsg("保存成功!")', url($_POST['fromController'],$_POST['fromAction']));
    }
    // **********************************入库查询 begin*************************
    function actionRight() {
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
            'ord2proId'=>'',
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
         if ($serachArea['ord2proId'] >0) $sql .= " and x.ord2proId = '{$serachArea['ord2proId']}'";
        if($serachArea['ganghao'] != ''){
            $sql .=" and x.ganghao like '%{$serachArea['ganghao']}%'";
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
            //如果过账了，则禁止删除与修改
            if(!$value['guozhangId']){
                  //如果库位是成品仓库，则判断orderId是否为0
                if($value['kuwei']=='成品仓库'||$value['kuwei']=='其他仓库'||$value['kuwei']=='样品间'||$value['kuwei']=='疵品库位'||$value['kuwei']=='OCS成品仓库'){
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

    function actionEdit() {
//dump($_GET['id']);die;
        $arr = $this->_modelMain->find(array('id' => $_GET['id'])); //dump($arr);exit;
        //设置主表id的值
        foreach ($this->fldMain as $k => &$v) {
            $v['value'] = $arr[$k]?$arr[$k]:$v['value'];
        }
        //dump($this->fldMain);//exit;
        //处理客户信息
        //$this->areaMain['']
        // 入库明细处理
        foreach($arr['Products'] as &$v) {
            $sql = "select * from jichu_product where id='{$v['productId']}'";
            $_temp = $this->_modelDefault->findBySql($sql); //dump($_temp);exit;
            $v['proCode'] = $_temp[0]['proCode'];
            $v['proName'] = $_temp[0]['proName'];
            $v['guige'] = $_temp[0]['guige'];
            $v['color'] = $_temp[0]['color'];
            $v['menfu'] = $_temp[0]['menfu'];
            $v['kezhong'] = $_temp[0]['kezhong'];
        }
        foreach($arr['Products'] as &$v) {
            $temp = array();
            foreach($this->headSon as $kk => &$vv) {
                $temp[$kk] = array('value' => $v[$kk]);
            }
            if($v['planId']){
                $m = & FLEA::getSingleton('Model_Shengchan_Plan');
                $row = $m->find(array('id'=>$v['planId']));
                $temp['plan2touliaoId']['text'] = $row['planCode'];
                //之前是按照生产计划做的生产领用，现在需求改为：目前因为人员问题，没有人在做生产计划这块，所以改为按订单来做生产领用
                //得到订单编号(这里是老数据的处理，之前保存在表里的都是planId,也要得到订单号)
                $mo = & FLEA::getSingleton('Model_Trade_Order');
                $ar = $mo->find(array('id'=>$row['orderId']));
                $temp['ord2proId']['text'] = $ar['orderCode'];
            }else{
                //得到订单编号
                $m = & FLEA::getSingleton('Model_Trade_Order2product');
                $row = $m->find(array('id'=>$v['ord2proId']));
                $temp['ord2proId']['text'] = $row['Order']['orderCode'];
            }
            $rowsSon[] = $temp;
        }
        // dump($rowsSon);
        //补齐5行
        // $cnt = count($rowsSon);
        // for($i=5;$i>$cnt;$i--) {
        //     $rowsSon[] = array();
        // }

        $smarty = &$this->_getView();
        $smarty->assign('areaMain',array('title' => $this->_kind.'基本信息', 'fld' => $this->fldMain));
        $smarty->assign('headSon', $this->headSon);
        $smarty->assign('rowsSon', $rowsSon);
        $smarty->assign('rules', $this->rules);
        $smarty->assign('sonTpl', 'Cangku/Yuanliao/jsLlck.tpl');
        $this->_beforeDisplayEdit($smarty);
        $smarty->display('Main2Son/T1.tpl');
    }

    /**
     * 原料收发存报表
     */
    function actionReport() {
    // $this->authCheck('3-1-8');
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

    $strGroup="kuwei,productId,pihao,ganghao";
    $sqlUnion="select {$strGroup},
        sum(cntFasheng) as cntInit,
        sum(moneyFasheng) as moneyInit,
        0 as cntRuku,0 as moneyRuku,0 as cntChuku,0 as moneyChuku
        from `cangku_common_kucun` where dateFasheng<'{$arr['dateFrom']}'
         {$strCon} group by {$strGroup}
        union
        select {$strGroup},
        0 as cntInit,0 as moneyInit,
        sum(cntFasheng) as cntRuku,
        sum(moneyFasheng) as moneyRuku,
        0 as cntChuku,0 as moneyChuku
        from `cangku_common_kucun` where
        dateFasheng>='{$arr['dateFrom']}' and dateFasheng<='{$arr['dateTo']}'
        and rukuId>0  {$strCon} group by {$strGroup}
        union
        select {$strGroup},
        0 as cntInit,0 as moneyInit,
        0 as cntRuku,
        0 as moneyRuku,
        sum(cntFasheng*-1) as cntChuku,
        sum(moneyFasheng*-1) as moneyChuku
        from `cangku_common_kucun` where
        dateFasheng>='{$arr['dateFrom']}' and dateFasheng<='{$arr['dateTo']}'
        and chukuId>0  {$strCon} group by {$strGroup}";
    $sql="select
        {$strGroup},
        z.guige,
        sum(cntInit) as cntInit,
        sum(moneyInit) as moneyInit,
        sum(cntRuku) as cntRuku,
        sum(moneyRuku) as moneyRuku,
        sum(cntChuku) as cntChuku,
        sum(moneyChuku) as moneyChuku
        from ({$sqlUnion}) as x
         left join jichu_product z on x.productId=z.id
        where 1 {$strC}
        group by {$strGroup}
        having sum(cntInit)<>0 or sum(moneyInit)<>0
        or sum(cntRuku)<>0 or sum(moneyRuku)<>0
        or sum(cntChuku)<>0 or sum(moneyChuku)<>0";
// 		dump($sql);exit;
    // todo.....

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

        //本期入库和本期出库点击可看到明细
    }
    $heji = $this->getHeji($rowset,array('cntInit','cntRuku','cntChuku','cntKucun'),'kuwei');
    if($_GET['export']) $heji['kuwei'] = "合计";

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
            $v['_edit'] = "<a href='".$this->_url('ChangeKucun',$tkArr)."' class='thickbox' title='调整库存'>调整库存</a>";
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
        'pihao'=>'批号',
        'ganghao'=>'缸号',
        'cntInit' => '期初',
        'cntRuku' => '本期入库',
        'cntChuku' => '本期出库',
        'cntKucun' => '余存',
        '_edit'=>'操作',
        // 'cnt'=>'数量',
    );

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
     * 打印
     */
    function actionView() {
        $m = & $this->_modelSon;
        $rowset = $m->find($_GET['id']);
        $row = $this->_modelMain->find(array('id'=>$_GET['id']));
        foreach($row['Products'] as &$v) {
            $sql = "select * from jichu_product where id='{$v['productId']}'";
            $_temp = $this->_modelMain->findBySql($sql); //dump($_temp);exit;
            // dump($v);dump($_temp);exit;
            $v['proCode'] = $_temp[0]['proCode'];
            $v['proName'] = $_temp[0]['proName'];
            $v['guige'] = $_temp[0]['guige'];
            $v['color'] = $_temp[0]['color'];

            //得到计划编号
            // $sql = "select  y.orderCode from trade_order2product x
            // left join trade_order y on x.orderId=y.id
            // where x.id='{$v['ord2proId']}'";
            // $temp = $this->_modelMain->findBySql($sql);
            // $v['planCode'] = $temp[0]['orderCode'];
            //得到订单编号
            if($v['planId']){
                $sql = "select orderId from shengchan_plan where id='{$v['planId']}'";
                $re = $this->_modelMain->findBySql($sql);
                $sqlOrderCode = "select orderCode from trade_order where id='{$re[0]['orderId']}'";
                $result = $this->_modelMain->findBySql($sqlOrderCode);
                $v['orderCode'] = $result[0]['orderCode'];
            }else{
                $sql = "select o.orderCode
                        from trade_order o 
                        left join trade_order2product t on o.id=t.orderId
                        where t.id='{$v['ord2proId']}'";
                $result = $this->_modelMain->findBySql($sql);
                $v['orderCode'] = $result[0]['orderCode'];
            }
            // dump($temp);
        }
        //补齐5行
        $cnt = count($row['Products']);
        for($i=5;$i>$cnt;$i--) {
            $row['Products'][] = array();
        }
        // dump($row);exit;
        $main = array(
            '出库单号'=>$row['chukuCode'],
            '出库日期'=>$row['chukuDate'],
            // '相关计划'=>$row['Plan']['planCode'],
            '领料部门'=>$row['Department']['depName'],
            '领料人'=>$row['lingliaoren'],
            '库存位置'=>$row['kuwei'],
            // '送货单号'=>$row['songhuoCode'],
            // '单号'=>,
            // '单号'=>,
            // '单号'=>,
        );
        $smarty = &$this->_getView();
        $smarty->assign("title", $this->_kind.'单');
        $smarty->assign("arr_main_value", $main);
        $smarty->assign("arr_field_info", array(
            // "_edit" => '操作',
            // "rukuDate" => "入库日期",
            // 'rukuCode' => array("text"=>'入库单号','width'=>150),
            // "kind" => "类别",
            // 'kuwei' => '库位',
            // 'proKind' => '种类',
            'orderCode' => '订单编号',
            'proCode' => '产品编码',
            'proName' => '品名',
            'guige' => '规格',
            'color' => '颜色',
            'pihao'=>'批号',
            // 'color' => '颜色',
            'cnt' => '发料数量',
            // 'danjia' => '单价',
            // 'money' => '金额',
            // "compName" => "供应商",
            // 'songhuoCode' => '送货单号',
            'memo' => '备注'
        ));
        $smarty->assign("arr_field_value", $row['Products']);
        $smarty->display('Print.tpl');
    }

    /**
     * 编辑界面中ajax删除
     */
    function actionRemoveByAjax() {
        //dump($_POST);die;
        $m = &FLEA::getSingleton('Model_Yuanliao_Llck2product');
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
            'ganghao'=>$_GET['ganghao'],
            'productId'=>$_GET['productId'],
            // 'supplierId' => $_GET['supplierId'],
        ));
        // dump($arr);exit;
        $sql = "select
            y.chukuCode,
            y.kuwei,
            y.chukuDate,
            y.memo as chukuMemo,
            y.kind,
            x.id,
            x.chukuId,
            x.pihao,
            x.ganghao,
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
            d.compName as clientName
            from cangku_common_chuku y
            left join cangku_common_chuku2product x on y.id=x.chukuId
            left join jichu_product b on x.productId=b.id
            left join jichu_department c on y.depId=c.id
            left join jichu_client d on y.clientId=d.id
            where x.pihao='{$arr['pihao']}'";

        $sql .= " and y.chukuDate >= '{$arr['dateFrom']}' and y.chukuDate<='{$arr['dateTo']}'";
        $sql .= " and y.kuwei='{$arr['kuwei']}'";
        $sql .= " and x.ganghao='{$arr['ganghao']}'";
        $sql .= " and x.productId='{$arr['productId']}'";
        // $sql .= " and x.productId='{$arr['productId']}'";
        $sql .= " order by chukuCode desc";
        // dump($sql);exit;
        $pager = &new TMIS_Pager($sql);
        $rowset = $pager->findAll();

        if (count($rowset) > 0) foreach($rowset as &$value) {
        }
        // 合计行
        $heji = $this->getHeji($rowset, array('cnt'), 'clientName');
        $rowset[] = $heji;
        // 显示信息
        $arrFieldInfo = $this->fldRight;
        unset($arrFieldInfo['_edit']);
        $arrFieldInfo = array('clientName'=>'客户')+$arrFieldInfo;

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

    /**
     * 在过账时需要弹出选择销售出库记录这里的条件不一样
     */
    function actionPopupOnGuozhang() {
        FLEA::loadClass('TMIS_Pager');

        // /构造搜索区域的搜索类型
        $arr = TMIS_Pager::getParamArray(array(
            'dateFrom' => date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-30, date("Y"))),
            'dateTo' => date("Y-m-d"),
            'kuwei'=>$_GET['kuwei'],

            'key'=>'',
            'clientName' => '',
            'orderCode' => '',
            'ganghao' => '',
            // 'supplierId' => $_GET['supplierId'],
        ));

        $sql = "select
                x.orderId,
                x.chukuCode,
                x.kuwei,
                x.chukuDate,
                x.kind,
                x.clientId,
                x.depId,
                y.id,
                y.ord2proId,
                y.chukuId,
                y.productId,
                y.cnt,
                y.unit,
                y.cntOrg,
                y.cntJian,
                y.danjia,
                y.memo,
                y.money,
                y.ganghao,
                b.proCode,
                b.proName,
                b.kind as proKind,
                b.guige,
                b.color,
                b.menfu,
                b.kezhong,
                c.depName,
                d.compName,
                e.orderCode
            from cangku_common_chuku2product y
            left join cangku_common_chuku x on x.id=y.chukuId
            left join jichu_product b on y.productId=b.id
            left join jichu_department c on x.depId=c.id
            left join jichu_client d on x.clientId=d.id
            left join trade_order e on e.id=x.orderId
            left join caiwu_ar_guozhang a on y.id=a.chuku2proId
            where x.kind='销售出库' and a.id is null
           ";

        $sql .= " and x.chukuDate >= '{$arr['dateFrom']}' and x.chukuDate<='{$arr['dateTo']}'";

        if ($arr['key'] != '')
        {
            $sql .= " and (b.proCode='{$arr['key']}' or b.proName='{$arr['key']}' or b.guige='{$arr['key']}')";
        }
        // $arr['key'] && $sql .= " and (b.proCode='{$arr['key']}' or b.proName='{$arr['key']}' or b.guige='{$arr['key']}')";
        // $sql .= " and x.productId='{$arr['productId']}'";

        $arr['clientName'] = trim($arr['clientName']);
        if($arr['clientName'] != '')
        {
            $sql .= " and d.compName like '%{$arr['clientName']}%' ";
        }

        if($arr['orderCode'] != '')
        {
            $sql .= " and e.orderCode like '%{$arr['orderCode']}%' ";
        }

        if($arr['ganghao'] != '')
        {
            $sql .= " and y.ganghao like '%{$arr['ganghao']}%' ";
        }


        $sql .= " order by chukuDate desc, chukuCode desc";
        
        $str = "SELECT * FROM ({$sql}) as t";

        $pager = &new TMIS_Pager($str);

        $rowset = $pager->findAll();
        //dump($rowset);die;
        if (count($rowset) > 0) foreach($rowset as $k => & $v) {
            $sql="SELECT * from trade_order2product where id='{$v['ord2proId']}'";
            $proInfo = $this->_modelExample->findBySql($sql);
            $v['menfu'] = $proInfo[0]['menfu'];
            $v['kezhong'] = $proInfo[0]['kezhong'];
            $temp=array();
            if($v['proCode']!='')$temp[]='编码:'.$v['proCode'];
            if($v['proName']!='')$temp[]='品名:'.$v['proName'];
            if($v['guige']!='')$temp[]='规格:'.$v['guige'];
            // if($v['chengFen']!='')$temp[]='成分：'.$v['chengFen'];
            if($v['color']!='')$temp[]='颜色：'.$v['color'];
            if($v['menfu']!='')$temp[]='门幅：'.$v['menfu'];
            if($v['kezhong']!='')$temp[]='克重：'.$v['kezhong'];
            // if($v['dengji']!='')$temp[]='等级:'.$v['dengji'];
            // if($v['state']!='')$temp[]='状态:'.$v['state'];
            $v['qitaMemo']=join(' ',$temp);
            // dump($v);exit;

            // 若是纱支, 计算前的数据就是用现有数量  cntOrg == cnt
            if($v['proKind'] != '成品')
            {
                $v['cntOrg']  = $v['cntOrg']*1==0?$v['cnt']:$v['cntOrg'];
            }
            $v['_edit']="<input type='checkbox' name='ck[]' id='ck[]' value='{$k}'>";
        }

        // 合计行
        $heji = $this->getHeji($rowset, array('cnt','money'), '_edit');
        $rowset[] = $heji;
        // 显示信息
        $arrFieldInfo =  array(
            //'id'=>'id',
            "_edit"=>array('text'=>"<input type='checkbox' id='ckAll'/>选择",'width'=>60),
            "chukuDate" => "出库日期",
            'chukuCode' => '出库单号',
            "orderCode"=>array('text'=>"订单编号",'width'=>110),
            "ganghao"  => '缸号',
            "compName"  => '客户',

            'proCode' => '产品编码',
            'proName' => '品名',
            'guige' => '规格',
            'color' => '颜色',
            'menfu' => '门幅',
            'kezhong' => '克重',
            'depName' => '领用部门',
            // 'zhonglei' => '种类',
            // 'color' => '颜色',
            'cntOrg' => '数量',
            'cntJian' => '件数',
            'cnt'=>'折合公斤数',
            'danjia' => '单价',
            'money' => '金额',
            "kuwei" => "库位",
            );;

        // unset($arrFieldInfo['_edit']);
        unset($arrFieldInfo['depName']);
        $smarty = &$this->_getView();
        $other_url="<input type='button' id='choose' name='choose' value='确定'/>";
        $smarty->assign('other_url', $other_url);
        $smarty->assign('title', '出库清单');
        $smarty->assign('arr_field_info', $arrFieldInfo);
        $smarty->assign('add_display', 'none');
        $smarty->assign('arr_condition', $arr);
        $smarty->assign('arr_field_value', $rowset);
        $smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action']), $arr));
        $smarty->assign('arr_js_css', $this->makeArrayJsCss(array('thickbox')));
        // $smarty->assign('arr_js_css', $this->makeArrayJsCss(array('grid', 'calendar')));
        // $smarty->display('TableList.tpl');
        $smarty->assign('sonTpl', "Popup/FrameInit.tpl");
        $smarty->display('Popup/CommonNew.tpl');
    }
}

?>