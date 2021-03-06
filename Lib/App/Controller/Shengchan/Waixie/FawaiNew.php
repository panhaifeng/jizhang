<?php
/*********************************************************************\
*  Copyright (c) 1998-2013, TH. All Rights Reserved.
*  Author :li
*  FName  :Fawai.php
*  Time   :2015/04/13 10:20:59
*  Remark :原料发到加工户后整登记入库的记录
*          该工序的入库记录，同时也相当于上一个工序的产量
*          沃丰后整理的产量不会拉回本厂，会直接送到下一个加工户，存在分批送
\*********************************************************************/
FLEA::loadClass('TMIS_Controller');
class Controller_Shengchan_Waixie_FawaiNew extends TMIS_Controller {

    /**
     * 主表model实例化
     * @var class
    */
    var $_modelExample;

    /**
     * 子表model实例化
     * @var class
    */
    var $_subModel;

    /**
     * 编辑界面的原型：主信息原型
     * @var Array
    */
    var $fldMain;

    /**
     * 编辑界面的原型：子信息
     * @var Array
    */
    var $headSon;

    /**
     * 编辑界面的原型：有效性验证
     * @var Array
    */
    var $rules;

    /**
     * 构造函数
    */
    function __construct() {
        //主表model
        $this->_modelExample = &FLEA::getSingleton('Model_Shengchan_Waixie_Fawai');
        //子表model
        $this->_subModel = &FLEA::getSingleton('Model_Shengchan_Waixie_FawaiSon');
    }

    /**
     * 生成编辑界面的数据集
     * 在actionAdd,actionEdit时需要用到
     * Time：2015/04/13 10:46:49
     * @author li
     * @return Array
    */
    function prototype(){
        //加载加工户的model
        $jgh = &FLEA::getSingleton('Model_Jichu_jiagonghu');

        //主表信息原型
        $this->fldMain = array(
            'rukuCode' => array('title'=>'发外编号',"type" =>"text",'readonly' => true,'value' => $this->_getNewCode('FW','cangku_fawai','rukuCode')),
            'rukuDate' => array('title' => '发生日期', 'type' => 'calendar', 'value' => date('Y-m-d')),
            'creater' => array('title' => '创建人', 'type' => 'text', 'value' => $_SESSION['REALNAME'].'','readonly' => true),
            'jghFromId' => array('title' => '发出加工户', 'type' => 'select2', 'value' => '','options'=>$jgh->getOptions()),
            'jiagonghuId' => array('title' => '后整厂', 'type' => 'select2', 'value' => '','options'=>$jgh->getOptions()),
            'gongxuId'=>array('type'=>'select',"title"=>'后整工序','model'=>'Model_Jichu_Gongxu','action'=>'getOptions'),
            'memo' => array('title' => '备注', 'type' => 'textarea','name'=>'Memo'),
            'id' => array('type' => 'hidden', 'value' => '','name'=>'mainId'),
            'isNew' => array('type' => 'hidden', 'value' => '','value'=>'1'),
            );
        // /从表表头信息
        // /type为控件类型,在自定义模板控件
        // /title为表头
        // /name为控件名
        // /bt开头的type都在webcontrolsExt中写的,如果有新的控件，也需要增加
        $this->headSon = array(
            '_edit' => array('type' => 'btBtnRemove', "title" => '+5行', 'name' => '_edit[]'),
            // 'planCode' => array('type' => 'bttext', "title" => '计划单号', 'name' => 'planCode[]', 'readonly' => true),
            'proCode' => array('type' => 'bttext', "title" => '产品编号', 'name' => 'proCode[]', 'readonly' => true),
            'proName' => array('type' => 'bttext', "title" => '品名', 'name' => 'proName[]', 'readonly' => true),
            'guige' => array('type' => 'bttext', "title" => '规格', 'name' => 'guige[]', 'readonly' => true),
            'color' => array('type' => 'bttext', "title" => '颜色', 'name' => 'color[]','readonly' => true),
            'menfu' => array('type' => 'bttext', "title" => '门幅', 'name' => 'menfu[]','readonly' => true),
            'kezhong' => array('type' => 'bttext', "title" => '克重', 'name' => 'kezhong[]','readonly' => true),
            // 'type' => array('type' => 'btselect', "title" => '区分', 'name' => 'type[]', 'value'=>'','options' =>array(
            //     array('text'=>'A','value'=>'A'),
            //     array('text'=>'B','value'=>'B'),
            //     array('text'=>'C','value'=>'C'),
            //     array('text'=>'D','value'=>'D'),
            // )),
            //'type' => array('type' => 'bttext', "title" => '区分', 'name' => 'type[]','readonly' => true),
            'unit' => array('type' => 'btselect', "title" => '单位', 'name' => 'unit[]', 'value'=>'公斤','options' =>array(
                array('text'=>'公斤','value'=>'公斤'),
                array('text'=>'米','value'=>'米'),
                array('text'=>'码','value'=>'码'),
                array('text'=>'磅','value'=>'磅'),
                array('text'=>'条','value'=>'条'),
            )),
            'Madan' => array('type' => 'btBtnMadan', "title" => '码单', 'name' => 'Madan[]'),
            //'ganghao' => array('type' => 'bttext', "title" => '缸号', 'name' => 'ganghao[]','readonly' => true),
            'cntJian' => array('type' => 'bttext', "title" => '件数', 'name' => 'cntJian[]'),
            'cnt' => array('type' => 'bttext', "title" => '数量', 'name' => 'cnt[]'),
            'dengjidanjia' => array('type' => 'bttext', "title" => '单价', 'name' => 'dengjidanjia[]'),
            'memo' => array('type' => 'bttext', "title" => '备注', 'name' => 'memo[]',),
            /*'dengji' => array('type' => 'btselect', "title" => '等级', 'name' => 'dengji[]', 'value'=>'一等品','options' =>array(
                array('text'=>'一等品','value'=>'一等品'),
                array('text'=>'二等品','value'=>'二等品'),
                array('text'=>'等外品','value'=>'等外品'),
            )),*/
            //-------------------处理hidden--------------------
            'id' => array('type' => 'bthidden', 'name' => 'id[]'),
            'productId' => array('type' => 'bthidden', 'name' => 'productId[]'),
            'planId' => array('type' => 'bthidden', 'name' => 'planId[]'),
            'orderId' => array('type' => 'bthidden', 'name' => 'orderId[]'),
            'ord2proId' => array('type' => 'bthidden', 'name' => 'ord2proId[]'),
            'plan2hzlId' => array('type' => 'bthidden', 'name' => 'plan2hzlId[]'),
            'chanliangId' => array('type' => 'bthidden', 'name' => 'chanliangId[]'),
        );
        // 表单元素的验证规则定义
        $this->rules = array(
            'jiagonghuId' => 'required',
            'gongxuId' => 'required',
            'rukuDate' => 'required',
            'jghFromId'=>'required'
        );
    }
    /**
     * ps ：新写的ListForAdd 添加部分功能 详见需求id：3233
     * Time：2018-02-11 14:10:16
     * @author zcc
    */
    function actionListForAdd(){
        FLEA::loadClass('TMIS_Pager');
        $this->title = '坯布产量发外';
        FLEA::loadClass('TMIS_Pager');
        $arr = TMIS_Pager::getParamArray(array(
            'jiagonghuIdFc' =>'26',
            'jiagonghuId' =>'',
            'gongxuId' =>'',
        ));
        $sql = "SELECT x.id as chanliangId,x.jiagonghuId,x.cnt,x.fenpiId,x.ord2proId,x.checkMainId,
            y.id as planId,z.id as plan2hzlId,z.gongxuId,y.planCode,g.name as gongxuName,j.compName as jiagonghuName,x.jiagonghuId as fachuJGh,z.jiagonghuId as jieshouJGH,o.orderCode,f.checkId
            FROM shengchan_chanliang x
            LEFT JOIN shengchan_plan y on x.planId = y.id
            LEFT JOIN shengchan_plan2houzheng z ON y.id = z.planId
            left join jichu_gongxu g on g.id = z.gongxuId
            left join jichu_jiagonghu j on j.id = z.jiagonghuId
            LEFT JOIN trade_order o on o.id = y.orderId
            LEFT JOIN check_main f on f.id = x.checkMainId
            WHERE 1 
            AND x.fenpiId > 0 
            AND x.jghId = '{$arr['jiagonghuIdFc']}'
            ";
        if ($arr['jiagonghuId']) {//加工户
            $sql .= " AND z.jiagonghuId = '{$arr['jiagonghuId']}' AND z.jiagonghuId != '{$arr['jiagonghuIdFc']}'";
        }
        if ($arr['gongxuId']) {
            $sql .= " AND z.gongxuId = '{$arr['gongxuId']}'";
        } 
        $sql .= "order by o.orderCode,f.checkId asc";
        // dump($sql);die();
        $pager = &new TMIS_Pager($sql);
        $rowset = $pager->findAll();
        if (count($rowset)>0) foreach($rowset as & $v) {
            $check_kind = $v['jieshouJGH'].'_'.$v['gongxuId'];
            $v['isCheck'] = "<input type='checkbox' name='ck' id='ck' value='{$v['chanliangId']}' data='{$v['plan2hzlId']}' check_kind='{$check_kind}'/>";
            $sql = "SELECT y.checkId as juanhao,y.c2 as gHao
                FROM 
                shengchan_planpj_fenpi x 
                left join check_main y on x.ExpectCode = y.ExpectCode
                where x.id = '{$v['fenpiId']}' and y.kind = 'pibu'";
            if ($v['checkMainId']) {
                $sql .= " AND y.id = {$v['checkMainId']}";//by zcc 2018年1月19日 由于新增 字段checkMainId 关联 故添加
            }
            $main = $this->_modelExample->findBySql($sql);
            $v['juanCode'] =  $main[0]['juanhao'];
            $v['ganghao'] =  $main[0]['gHao'];
            $sql = "SELECT 
                    p.id as productId,
                    y.orderCode,
                    x.id as ord2proId,
                    p.proName,
                    p.proCode,
                    p.guige,
                    p.color,
                    y.id as orderId
                FROM  trade_order2product x 
                LEFT JOIN trade_order y ON y.id = x.orderId
                LEFT JOIN jichu_product p ON p.id = x.productId
                WHERE 1 AND x.id = {$v['ord2proId']}";
            $main = $this->_modelExample->findBySql($sql);
            $v['proName'] = $main[0]['proName'];
            $v['proCode'] = $main[0]['proCode'];  
            $v['guige'] = $main[0]['guige'];  
            $v['color'] = $main[0]['color'];  
            $v['productId'] = $main[0]['productId'];  
            $v['orderCode'] = $main[0]['orderCode'];  
            $v['orderId'] = $main[0]['orderId'];
            $sql = "SELECT compName FROM jichu_jiagonghu where id = '{$v['fachuJGh']}'";
            $fachuJGh = $this->_modelExample->findBySql($sql);
            $v['jiagonghuNameFc'] = $fachuJGh[0]['compName']; 
            //获得总匹数 注：总匹数来源于检验计划中的总匹数
            $pj = "SELECT pishuCnt FROM shengchan_planpj where shengchanId = '{$v['planId']}' AND ord2proId = '{$v['ord2proId']}'";
            $planpj = $this->_modelExample->findBySql($pj);
            $v['pishuCnt'] = $planpj[0]['pishuCnt'];     
        }
        $heji = $this->getHeji($rowset,array('cnt'),'isCheck');
        $rowset[] = $heji;
        $smarty = & $this->_getView();
        $smarty->assign('title', $this->title);
        $arr_field_info = array(
            'isCheck'         =>array("text"=>'<a href="javascript:void(0)" id="ckAll">全选</a>','width'=>'50'),
            'orderCode'       => '订单号',
            "planCode"        => "计划单号",
            'pishuCnt'           => '总匹数',  
            "proCode"         => "产品编号",
            'proName'         => '品名',
            "guige"           => "规格",
            "color"           => "颜色",
            "juanCode"        => "卷号",
            "cnt"             => "数量",
            "ganghao"          => "缸号",
            'gongxuName'      => '工序',
            'jiagonghuNameFc' => '发出加工户',
            'jiagonghuName'   => '加工户',
        );
        $smarty->assign('arr_field_info', $arr_field_info);
        $smarty->assign('add_display', 'none');
        $smarty->assign('arr_condition', $arr);
        $smarty->assign('arr_field_value', $rowset);
        $btn_rk = "<a href='".$this->_url('Add',array('fromAction'=>$_GET['action']))."' id='openMadanInfo',
        'cid'=>
        >确认并发外</a>";
        $msg = "<font color='red'><b>加工户和工序必选且匹配</b></font>";
        $smarty->assign('other_url', $btn_rk);
        $smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action']), $arr).$msg);
        $smarty->assign('arr_js_css', $this->makeArrayJsCss(array('grid', 'calendar')));
        $smarty->assign('sonTpl', 'Waixie/_jsFawai.tpl');
        $this->_beforeDisplayRight($smarty);
        $smarty->display('TableList.tpl');
    }
    function actionListForAddTwo(){
        FLEA::loadClass('TMIS_Pager');
        $this->title = '坯布产量发外';
        FLEA::loadClass('TMIS_Pager');
        $arr = TMIS_Pager::getParamArray(array(
            'orderCode' =>'',
            'key' =>'',
            'ganghao'=>'',
            'jizhi'=>'',
        ));

        $sql = "SELECT 
            x.id as chanliangId,x.cnt,x.fenpiId,x.ord2proId,x.checkMainId,x.ganghao,x.type,
            y.id as planId,
            o.orderCode,
            f.checkId,
            a.jitai,a.jianhao as juanCode,x.zhiJiCode
            FROM shengchan_chanliang x
            LEFT JOIN shengchan_plan y on x.planId = y.id
            LEFT JOIN shengchan_planpj_fenpi a on x.fenpiId=a.id
            LEFT JOIN trade_order2product d on d.id = x.ord2proId
            LEFT JOIN trade_order o on o.id = d.orderId
            LEFT JOIN check_main f on f.id = x.checkMainId
            LEFT JOIN jichu_product b on b.id=x.productId
            left join jichu_zhiji m on x.jizhiId=m.id
            left join cangku_fawai2product_chanliang c on c.chanliangId=x.id
            WHERE 1 
            AND x.fenpiId > 0 and c.id is null
            ";

        if ($arr['orderCode']!='') {
            $sql .= " AND o.orderCode like '%{$arr['orderCode']}%'";
        } 
        
        if ($arr['ganghao']!='') {
            $sql .= " AND x.ganghao like '%{$arr['ganghao']}%'";
        }

        if ($arr['jizhi']!='') {
            $sql .= " AND m.id ='{$arr['jizhi']}'";
        }
        if($arr['key']!=''){
            $sql.=" and (b.proCode like '%{$arr['key']}%'
                        or b.proName like '%{$arr['key']}%'
                        or b.guige like '%{$arr['key']}%'
                        or b.color like '%{$arr['key']}%'
                )";
        }
        $sql .= "  GROUP BY x.fenpiId,f.checkId order by o.orderCode,a.jianhao asc";
       // dump($sql);die;
        // $pager = &new TMIS_Pager($sql);
        // $rowset = $pager->findAll();
        $rowset = $this->_modelExample->findBySql($sql);
        // dump($sql);die;
        if (count($rowset)>0) foreach($rowset as & $v) {
            $check_kind = $v['jieshouJGH'].'_'.$v['gongxuId'];
            $v['isCheck'] = "<input type='checkbox' name='ck' id='ck' value='{$v['chanliangId']}' data='{$v['plan2hzlId']}' check_kind='{$check_kind}'/>";
            $sql = "SELECT x.jianhao as juanhao,y.c2 as gHao
                FROM 
                shengchan_planpj_fenpi x 
                left join check_main y on x.ExpectCode = y.ExpectCode
                where x.id = '{$v['fenpiId']}' and y.kind = 'pibu'";
            if ($v['checkMainId']) {
                $sql .= " AND y.id = {$v['checkMainId']}";//by zcc 2018年1月19日 由于新增 字段checkMainId 关联 故添加
            }
            $main = $this->_modelExample->findBySql($sql);
            // $v['juanCode'] =  $main[0]['juanhao'];
            $v['ganghao'] =  $main[0]['gHao'];
            $sql = "SELECT 
                    p.id as productId,
                    y.orderCode,
                    x.id as ord2proId,
                    p.proName,
                    p.proCode,
                    p.guige,
                    p.color,
                    y.id as orderId
                FROM  trade_order2product x 
                LEFT JOIN trade_order y ON y.id = x.orderId
                LEFT JOIN jichu_product p ON p.id = x.productId
                WHERE 1 AND x.id = {$v['ord2proId']}";
            $main = $this->_modelExample->findBySql($sql);
            $v['proName'] = $main[0]['proName'];
            $v['proCode'] = $main[0]['proCode'];  
            $v['guige'] = $main[0]['guige'];  
            $v['color'] = $main[0]['color'];  
            $v['productId'] = $main[0]['productId'];  
            $v['orderId'] = $main[0]['orderId'];
            $sql = "SELECT compName FROM jichu_jiagonghu where id = '{$v['fachuJGh']}'";
            $fachuJGh = $this->_modelExample->findBySql($sql);
            $v['jiagonghuNameFc'] = $fachuJGh[0]['compName']; 
            //获得总匹数 注：总匹数来源于检验计划中的总匹数
            $pj = "SELECT pishuCnt FROM shengchan_planpj where shengchanId = '{$v['planId']}' AND ord2proId = '{$v['ord2proId']}'";
            $planpj = $this->_modelExample->findBySql($pj);
            $v['pishuCnt'] = $planpj[0]['pishuCnt'];     
        }
        $heji = $this->getHeji($rowset,array('cnt'),'isCheck');
        //$zongji = $this->getHeji($rowsetAll,array('cnt'),'isCheck');
        //$zongji['isCheck'] = '<b>总计</b>';
        $rowset[] = $heji;
        //$rowset[] = $zongji;
        $smarty = & $this->_getView();
        $smarty->assign('title', $this->title);
        $arr_field_info = array(
            'isCheck'         =>array("text"=>'<a href="javascript:void(0)" id="ckAll">全选</a>','width'=>'50'),
            'orderCode'       => '订单号',
            'pishuCnt'        => '总匹数',  
            "proCode"         => "产品编号",
            'proName'         => '品名',
            "guige"           => "规格",
            "ganghao"         => "缸号",
            "color"           => "颜色",
            "zhiJiCode"       => "机台",
            "type"            => "区分",
            "juanCode"        => "件号",
            "cnt"             => "数量",
            // 'gongxuName'      => '工序',
            // 'jiagonghuNameFc' => '发出加工户',
            // 'jiagonghuName'   => '加工户',
        );
        $smarty->assign('arr_field_info', $arr_field_info);
        $smarty->assign('add_display', 'none');
        $smarty->assign('arr_condition', $arr);
        $smarty->assign('arr_field_value', $rowset);
        $btn_rk = "<a href='".$this->_url('Add',array('fromAction'=>$_GET['action']))."' id='openMadanInfo',
        'cid'=>
        >确认并发外</a>";
        $msg = "<font color='red'><b>加工户和工序必选且匹配</b></font>";
        $smarty->assign('other_url', $btn_rk);
        //$smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action']), $arr).$msg);
        $smarty->assign('arr_js_css', $this->makeArrayJsCss(array('grid', 'calendar')));
        $smarty->assign('sonTpl', 'Waixie/_jsFawai.tpl');
        $this->_beforeDisplayRight($smarty);
        $smarty->display('TableList.tpl');
    }
     /**
     * ps ：后整发外勾选列表
     * Time：2017/07/31 08:48:17
     * @author zcc
    */
    function actionListForAddOld(){
        FLEA::loadClass('TMIS_Pager');
        $this->title = '坯布产量发外';
        FLEA::loadClass('TMIS_Pager');
        // $arr = TMIS_Pager::getParamArray(array(
        //     'jiagonghuIdFc' =>'26',
        //     'jiagonghuId' =>'60',
        //     'gongxuId' =>'1',
        // ));
        $arr = TMIS_Pager::getParamArray(array(
            'jiagonghuIdFc' =>'26',
            'jiagonghuId' =>'',
            'gongxuId' =>'',
        ));
        $sql = "SELECT x.id as chanliangId,x.jiagonghuId,x.cnt,x.fenpiId,x.ord2proId,x.checkMainId,
            y.id as planId,z.id as plan2hzlId,z.gongxuId,y.planCode,g.name as gongxuName,j.compName as jiagonghuName,x.jiagonghuId as fachuJGh
            FROM shengchan_chanliang x
            LEFT JOIN shengchan_plan y on x.planId = y.id
            LEFT JOIN shengchan_plan2houzheng z ON y.id = z.planId
            left join jichu_gongxu g on g.id = z.gongxuId
            left join jichu_jiagonghu j on j.id = z.jiagonghuId
            WHERE 1 
            AND x.fenpiId > 0 
            AND z.jiagonghuId = '{$arr['jiagonghuId']}'
            AND z.jiagonghuId != '{$arr['jiagonghuIdFc']}'
            AND x.jghId = '{$arr['jiagonghuIdFc']}'
            AND z.gongxuId = '{$arr['gongxuId']}'
            ";
        $sql .= "order by gongxuId,z.jiagonghuId,x.id asc";
        // dump($sql);die();
        $pager = &new TMIS_Pager($sql);
        $rowset = $pager->findAll();
        if (count($rowset)>0) foreach($rowset as & $v) {
            $v['isCheck'] = "<input type='checkbox' name='ck' id='ck' value='{$v['chanliangId']}' data='{$v['plan2hzlId']}' />";
            $sql = "SELECT y.checkId as juanhao,y.c2 as gHao
                FROM 
                shengchan_planpj_fenpi x 
                left join check_main y on x.ExpectCode = y.ExpectCode
                where x.id = '{$v['fenpiId']}' and y.kind = 'pibu'";
            if ($v['checkMainId']) {
                $sql .= " AND y.id = {$v['checkMainId']}";//by zcc 2018年1月19日 由于新增 字段checkMainId 关联 故添加
            }
            $main = $this->_modelExample->findBySql($sql);
            $v['juanCode'] =  $main[0]['juanhao'];
            $v['ganghao'] =  $main[0]['gHao'];
            $sql = "SELECT 
                    p.id as productId,
                    y.orderCode,
                    x.id as ord2proId,
                    p.proName,
                    p.proCode,
                    p.guige,
                    p.color,
                    y.id as orderId
                FROM  trade_order2product x 
                LEFT JOIN trade_order y ON y.id = x.orderId
                LEFT JOIN jichu_product p ON p.id = x.productId
                WHERE 1 AND x.id = {$v['ord2proId']}";
            $main = $this->_modelExample->findBySql($sql);
            $v['proName'] = $main[0]['proName'];
            $v['proCode'] = $main[0]['proCode'];  
            $v['guige'] = $main[0]['guige'];  
            $v['color'] = $main[0]['color'];  
            $v['productId'] = $main[0]['productId'];  
            $v['orderCode'] = $main[0]['orderCode'];  
            $v['orderId'] = $main[0]['orderId'];
            $sql = "SELECT compName FROM jichu_jiagonghu where id = '{$v['fachuJGh']}'";
            $fachuJGh = $this->_modelExample->findBySql($sql);
            $v['jiagonghuNameFc'] = $fachuJGh[0]['compName'];                
        }
        $heji = $this->getHeji($rowset,array('cnt'),'isCheck');
        $rowset[] = $heji;
        $smarty = & $this->_getView();
        $smarty->assign('title', $this->title);
        $arr_field_info = array(
            'isCheck'=>array("text"=>'<a href="javascript:void(0)" id="ckAll">全选</a>','width'=>'50'),
            "planCode" =>"计划单号",
            "proCode" =>"产品编号",
            'proName' =>'品名',
            "guige" =>"规格",
            "color" =>"颜色",
            "juanCode" =>"卷号",
            "cnt" =>"数量",
            "ganghao" =>"缸号",
            'gongxuName' =>'工序',
            'jiagonghuNameFc' =>'发出加工户',
            'jiagonghuName' =>'加工户',
            
        );
        $smarty->assign('arr_field_info', $arr_field_info);
        $smarty->assign('add_display', 'none');
        $smarty->assign('arr_condition', $arr);
        $smarty->assign('arr_field_value', $rowset);
        $btn_rk = "<a href='".$this->_url('Add',array('fromAction'=>$_GET['action']))."' id='openMadanInfo',
        'cid'=>
        >确认并发外</a>";
        $msg = "<font color='red'><b>加工户和工序必选且匹配</b></font>";
        $smarty->assign('other_url', $btn_rk);
        $smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action']), $arr).$msg);
        $smarty->assign('arr_js_css', $this->makeArrayJsCss(array('grid', 'calendar')));
        $smarty->assign('sonTpl', 'Waixie/_jsFawai.tpl');
        $this->_beforeDisplayRight($smarty);
        $smarty->display('TableList.tpl'); 
    }
    /**
     * ps ：新的新增方法用于处理勾选数据
     * Time：2017/07/31 13:28:25
     * @author zcc
    */
    function actionAdd(){
        $this->authCheck('14-8');
        // dump($_GET);exit();
        $this->prototype();
        $row = array(
            'jghFromId' =>$_GET['jiagonghuIdFc'],
            'jiagonghuId' =>$_GET['jiagonghuId'],
            'gongxuId' =>$_GET['gongxuId'],
        );
        //主信息数据构造
        //主信息字段赋值
        foreach ($this->fldMain as $k => &$v) {
            $v['value'] = $row[$k]?$row[$k]:$v['value'];
        }
        $setSql = "SET @@GROUP_CONCAT_MAX_LEN=1000000";
        mysql_query($setSql);
        //sql
        $sql = "SELECT group_concat(x.id) as chanliangId,x.jiagonghuId,x.jiagonghuId as fachuJGh,
            sum(x.cnt) as cnt,x.ord2proId,COUNT(*) as cntJian,x.chanliangDate,
            y.id as planId,y.planCode
            -- ,z.id as plan2hzlId
            -- ,z.gongxuId
            -- ,g.name as gongxuName
            -- ,j.compName as jiagonghuName
            ,m.c2 as ganghao
            FROM shengchan_chanliang x
            LEFT JOIN shengchan_plan y on x.planId = y.id
            -- LEFT JOIN shengchan_plan2houzheng z ON y.id = z.planId
            -- LEFT JOIN jichu_gongxu g on g.id = z.gongxuId
            -- LEFT JOIN jichu_jiagonghu j on j.id = z.jiagonghuId
            LEFT JOIN shengchan_planpj_fenpi pf on pf.id = x.fenpiId
            LEFT JOIN check_main m  on x.checkMainId = m.id
            WHERE 1 
            AND x.fenpiId > 0 
            AND m.kind = 'pibu'
            ";
        if($_GET['chanliangId']){
            $sql.=" AND x.id in ({$_GET['chanliangId']})";
        }
        if($_GET['jiagonghuIdFc']){
            $sql.=" AND x.jghId = '{$_GET['jiagonghuIdFc']}'";
        }
        // if($_GET['jiagonghuId']){
        //     $sql.=" AND z.jiagonghuId = '{$_GET['jiagonghuId']}'";
        // }
        // if($_GET['gongxuId']){
        //     $sql.=" AND z.gongxuId = '{$_GET['gongxuId']}'";
        // }

        $sql .= " GROUP BY x.productId order by x.chanliangDate asc";    
        $rowset = $this->_modelExample->findBySql($sql);
        // dump($sql);die();
        foreach ($rowset as &$v) {
            $ganghao = str_replace("'","",$v['ganghao']);
            //获取所对应数据的产量信息 显示到码单中
            $sql = "SELECT x.cnt,z.c2 as ganghao,y.ExpectCode,x.id as chanliangId
                FROM shengchan_chanliang x 
                LEFT JOIN shengchan_planpj_fenpi y on x.fenpiId = y.id
                LEFT JOIN check_main z on z.ExpectCode = y.ExpectCode
                WHERE x.id in ({$v['chanliangId']}) AND x.planId = '{$v['planId']}' 
                AND z.kind = 'pibu' group by x.id
                ";
            $Madan = $this->_modelExample->findBySql($sql);
            $string1 = array();
            $string2 = array();
            foreach ($Madan as &$va) {
                $string1[] = $va['chanliangId'];
                $string2[] = $va['ExpectCode'];
            }
            $v['Madan'] = join(',',$string1).";".join(',',$string2);   
            //获取明细产品信息
            $sql = "SELECT 
                    p.id as productId,
                    y.orderCode,
                    x.id as ord2proId,
                    p.proName,
                    p.proCode,
                    p.guige,
                    p.color,
                    p.menfu,
                    p.kezhong,
                    y.id as orderId
                FROM  trade_order2product x 
                LEFT JOIN trade_order y ON y.id = x.orderId
                LEFT JOIN jichu_product p ON p.id = x.productId
                WHERE 1 AND x.id = {$v['ord2proId']}";
            $main = $this->_modelExample->findBySql($sql);
            $v['proName'] = $main[0]['proName'];
            $v['proCode'] = $main[0]['proCode'];  
            $v['guige'] = $main[0]['guige'];  
            $v['color'] = $main[0]['color'];  
            $v['menfu'] = $main[0]['menfu'];
            $v['kezhong'] = $main[0]['kezhong'];

            $v['productId'] = $main[0]['productId'];  
            $v['orderCode'] = $main[0]['orderCode'];  
            $v['orderId'] = $main[0]['orderId'];
            $v['chanliangId'] = $v['chanliangId'];
            //‘区分’显示
            $sql="select type from shengchan_chanliang where id='{$v['chanliangId']}' and ord2proId='{$v['ord2proId']}'";
            $types=$this->_modelExample->findBySql($sql);
            $v['type'] = $types[0]['type'];
        }   
        // dump($rowset);die();    
        foreach($rowset as &$v) {
            $temp = array();
            foreach($this->headSon as $kk => &$vv) {
                $temp[$kk] = array('value' => $v[$kk]);
            }
            $rowsSon[] = $temp;
        }
        $areaMain = array('title' => '发外基本信息', 'fld' => $this->fldMain);
        $smarty = &$this->_getView();
        $smarty->assign('areaMain', $areaMain);
        $smarty->assign('headSon', $this->headSon);
        $smarty->assign('rowsSon', $rowsSon);
        $smarty->assign('rules', $this->rules);
        $smarty->assign('recheck', 'recheck');//设置引入按钮为重新引入
        $smarty->assign('sonTpl', "Waixie/FawaiEdit.tpl");
        $smarty->display('Waixie/Fawai_A.tpl');
    }
    /**
     * ps ：删除方法
     * Time：2017/08/04 15:41:12
     * @author zcc
    */
    function actionRemove() {
        $from = $_GET['fromAction']?$_GET['fromAction']:$_GET['from'];
        $sql = "SELECT z.chanliangId,x.jghFromId,x.jiagonghuId
            FROM cangku_fawai x 
            left join cangku_fawai2product y on x.id = y.fawaiId
            left join cangku_fawai2product_chanliang z on y.id = z.fawaiId
            WHERE x.id = '{$_GET['id']}' ";
        $rowset = $this->_modelExample->findBySql($sql);    
        //得到这个单子的所有chanliangId
        $ids = array(); 
        foreach ($rowset as &$v) {
            //查看这个产量是否已经发到另一个加工户 如果发出则不许删除
            $sql = "SELECT * FROM shengchan_chanliang where id = '{$v['chanliangId']}'";
            $chanliang = $this->_modelExample->findBySql($sql);
            if ($chanliang[0]['jghId']!=$rowset[0]['jiagonghuId'] && $chanliang[0]['jghId']) {
                // echo "本条数据不允许删除，产量已经不再本加工户!";
                $failure = '1';//有一条数据符合则不可以
            }
            $ids[] = $v['chanliangId'];
        }
        // if ($failure == '1') {
        //     js_alert('本条数据不允许删除，有存在的产量已经发往其他加工户!','',$this->_url($from));
        //     exit();
        // }
        if ($this->_modelExample->removeByPkv($_GET['id'])) {
            $idsNew = join(',',$ids);
            if($idsNew && $rowset[0]['jghFromId']){
                $sql = "UPDATE shengchan_chanliang set jghId = '{$rowset[0]['jghFromId']}' WHERE id in (".join(',',$ids).")";
                $this->_modelExample->execute($sql);
            }
            
            if($from=='') redirect($this->_url("right"));
            else js_alert(null,"window.parent.showMsg('成功删除')",$this->_url($from));
        }
        else js_alert('出错，不允许删除!',$this->_url($from));

    }
    /**
     * ps ：xin保存
     * Time：2017/08/04 13:14:45
     * @author zcc
    */
    function actionSave(){
        $this->prototype();
        //生成主表信息
        $row = array();
        foreach($this->fldMain as $k=>&$vv) {
            $name = $vv['name']?$vv['name']:$k;
            $row[$k] = $_POST[$name];
        }
        $row['type'] = $row['isNew'];
        $Son = array();
        $chanliangIds = array();
        foreach ($_POST['productId'] as $key => $v) {
            if($v=='' || $_POST['cnt'][$key]=='') continue;
            $sql = "SELECT chanliangId from cangku_fawai2product_chanliang WHERE fawaiId = '{$_POST['id'][$key]}' ";
            $a = $this->_modelExample->findBySql($sql);
            if ($_POST['Madan'][$key]) { //当码单项中有值时 则为修改中增加取消勾选项 或 新增条列
                $b = array();
                foreach ($a as &$value) {
                    $b[] = $value['chanliangId'];
                }
                $_madanA=explode(';', $_POST['Madan'][$key]);//分割;前后数组
                $_madanIdStr=$_madanA[0];
                $_madanB=explode(',',$_madanIdStr);//获取chanliangId 成数组
                $chanliang = array();
                // dump($_madanB);exit();
                if ($_POST['id'][$key]=='') { //新增时
                    foreach ($_madanB as &$va) {
                        $chanliang[] = array(
                            'chanliangId' =>$va,
                            'jghFromId' =>$_POST['jghFromId'],
                            'jiagonghuId' =>$_POST['jiagonghuId'],
                            'gongxuId' =>$_POST['gongxuId'],
                            'ScDate' =>date('Y-m-d H:i:s'),
                        );
                        $chanliangIds[] = $va;//把产量ID存起来 用来变更产量表中的所在加工户id 
                    }
                }else{//修改时
                    foreach ($_madanB as &$va) {
                        if(!in_array($va, $b)){
                           $chanliang[] = array(
                                'chanliangId' =>$va,
                                'jghFromId' =>$_POST['jghFromId'],
                                'jiagonghuId' =>$_POST['jiagonghuId'],
                                'gongxuId' =>$_POST['gongxuId'],
                                'ScDate' =>date('Y-m-d H:i:s'),
                            );
                            $chanliangIds[] = $va;//把产量ID存起来 用来变更产量表中的所在加工户id 
                        }
                    }        
                }
                
            }
            // $str = "SELECT id as plan2hzlId 
            //         from shengchan_plan2houzheng 
            //         WHERE gongxuId = '{$_POST['gongxuId']}' and planId='{$_POST['planId'][$key]}'";
            // $houzheng = $this->_modelExample->findBySql($str);
            // 判断计划中是否有该工序
            // if(!$houzheng[0]['plan2hzlId']) {
            //     js_alert('该计划中没有当前选择的工序!','window.history.go(-1)');
            //     exit;
            // }
            $temp = array(
                'id' => $_POST['id'][$key],
                'productId' => $_POST['productId'][$key],
                'planId' => $_POST['planId'][$key],
                'plan2hzlId' => $houzheng[0]['plan2hzlId']+0,
                'orderId' => $_POST['orderId'][$key],
                'ord2proId' => $_POST['ord2proId'][$key],
                'type' => $_POST['type'][$key]+'',
                'cntJian' => $_POST['cntJian'][$key],
                'cnt' => $_POST['cnt'][$key],
                'dengjidanjia' => $_POST['dengjidanjia'][$key],
                'ganghao' => $_POST['ganghao'][$key]+'',
                'memo' => $_POST['memo'][$key],
                'unit' => $_POST['unit'][$key],
                'chanliangId' => $_POST['chanliangId'][$key],

            );
            $temp['Chanliang'] = $chanliang;
            //供应商信息
            $temp['gongxuId']=$_POST['gongxuId']+0;
            $Son[] = $temp;
        }
         if(count($Son)==0) {
            js_alert('请选择有效的产品明细信息!','window.history.go(-1)');
            exit;
        }
        $row['Son'] = $Son;
        //dump($row);die;
        // dump($row);exit();
        //删除 取消勾选的项的 cangku_fawai2product_chanliang 表数据和 shengchan_chanliang 表中的jghId（所在加工户）
        foreach ($_POST['Madan'] as $key => &$va) {
            if (!$va) continue;
            if ($_POST['id'][$key]>0) {
                $_madan=explode(';', $_POST['Madan'][$key]);
                $_madanIdStr=$_madan[0];
                if ($_madanIdStr=='') {//为空字符串跳出
                    continue;
                }
                $_madan=explode(',', $_madanIdStr);//由于explode 空数据时 也会存在一个[0]元素   
                $sql = "SELECT * from cangku_fawai2product_chanliang where fawaiId = {$_POST['id'][$key]} ";
                $oldMadanArr = $this->_modelExample->findBySql($sql);
                $deleteArr = array();
                $deleteArr2 = array();
                foreach ($oldMadanArr as $k => & $v) {//当原数据chanliangid 和码单中带出的id 不一致 则被取消
                    if(in_array($v['chanliangId'], $_madan)) continue;
                    $deleteArr[] = $v['id'];
                    $deleteArr2[] = $v['chanliangId'];
                }
                if(count($deleteArr)>0){
                    $deleteIds = join(',',$deleteArr);
                    $sql = "DELETE FROM cangku_fawai2product_chanliang WHERE fawaiId = {$_POST['id'][$key]} and id IN({$deleteIds})";
                    // dump($sql);
                    $this->_modelExample->execute($sql);
                    //删除之后还要修改 shanchang_chanliang 表中的所在加工户id 变为页面数据的发出加工户
                    $sql = "UPDATE shengchan_chanliang set jghId = '{$_POST['jghFromId']}' WHERE id in (".join(',',$deleteArr2).")";
                    $this->_modelExample->execute($sql);

                }
            }
        }
        if ($chanliangIds) {
            //新增界面 让chanliang表中的jghId 变成本次接受加工户
            $sql = "UPDATE shengchan_chanliang set jghId = '{$_POST['jiagonghuId']}' where id in (".join(',',$chanliangIds).")";
            $this->_modelExample->execute($sql);
        }
        //进行保存
        if(!$this->_modelExample->save($row)) {
            js_alert('保存失败','window.history.go(-1)');
            exit;
        }
        //跳转
        js_alert(null,'window.parent.showMsg("保存成功!")',url($_POST['fromController'],$_POST['fromAction'],array()));
        exit;


        
    }
    /**
     * 保存
     * Time：2015/04/13 18:55:10
     * @author li
    */
    function actionSaveOld(){
        dump($_POST);exit();
        $this->prototype();
        //有效性验证,没有明细信息禁止保存
        //开始保存
        $pros = array();
        $Cl = array();
        foreach($_POST['productId'] as $key => & $v) {
            if($v=='' || $_POST['cnt'][$key]=='') continue;
            $temp = array();
            foreach($this->headSon as $k=>&$vv) {
                $temp[$k] = $_POST[$k][$key];
                if($k=='Madan'){
                    $_madan=explode(';', $_POST[$k][$key]);
                    $_madanIdStr=$_madan[0];
                    $_madan=explode(',',$_madanIdStr);
                    $chanliang = array();
                    if ($_madanIdStr) { //由于explode 空数据时 也会存在一个[0]元素   
                        foreach ($_madan as &$va) {
                            if ($_POST['id'][$key]>0) {
                                $chanliang[] = array(
                                    'chanliangId' => $va,
                                    'jghFromId' =>$_POST['jghFromId'],
                                    'jiagonghuId' =>$_POST['jiagonghuId'],
                                    'gongxuId' =>$_POST['gongxuId'],
                                );
                            }else{
                                $chanliang[] = array(
                                    'chanliangId' => $va,
                                    'jghFromId' =>$_POST['jghFromId'],
                                    'jiagonghuId' =>$_POST['jiagonghuId'],
                                    'gongxuId' =>$_POST['gongxuId'],
                                    'ScDate' =>date('Y-m-d H:i:s'),
                                );
                            }
                            $Cl[] = $va;//把产量ID存起来 用来变更产量表中的所在加工户id
                        }
                        $temp['Chanliang'] = $chanliang;
                    }
                    
                    
                }
            }
            //供应商信息
            $temp['gongxuId']=$_POST['gongxuId']+0;
            $pros[]=$temp;
        }
        if(count($pros)==0) {
            js_alert('请选择有效的产品明细信息!','window.history.go(-1)');
            exit;
        }
        //删除为选择的 第三方（cangku_fawai2product_chanliang）表数据 by zcc
        // dump($_POST['Madan']);exit();
        foreach ($_POST['Madan'] as $key => &$va) {
            if (!$va) continue;
            if ($_POST['id'][$key]>0) {
                $_madan=explode(';', $_POST['Madan'][$key]);
                $_madanIdStr=$_madan[0];
                if ($_madanIdStr=='') {//为空字符串跳出
                    continue;
                }
                $_madan=explode(',', $_madanIdStr);//由于explode 空数据时 也会存在一个[0]元素   
                $sql = "SELECT * from cangku_fawai2product_chanliang where fawaiId = {$_POST['id'][$key]} ";
                $oldMadanArr = $this->_modelExample->findBySql($sql);
                $deleteArr = array();
                foreach ($oldMadanArr as $k => & $v) {
                    if(in_array($v['chanliangId'], $_madan)) continue;
                    $deleteArr[] = $v['id'];
                }
                if(count($deleteArr)>0){
                    $deleteIds = join(',',$deleteArr);
                    $sql = "DELETE FROM cangku_fawai2product_chanliang WHERE fawaiId = {$_POST['id'][$key]} and id IN({$deleteIds})";
                    dump($sql);
                    // $this->_modelExample->execute($sql);
                }
            }
        }
        $row = array();
        foreach($this->fldMain as $k=>&$vv) {
            $name = $vv['name']?$vv['name']:$k;
            $row[$k] = $_POST[$name];
        }
        $row['Son'] = $pros;
        //shengchan_chanliang 的相关字段进行变更
        // dump($row);exit();
        if ($Cl) {
            //修改界面 修改取消某个产量勾选时 让chanliang表中的jghId 重新变更为上一个所在加工户（即本次发出加工户） 
            // if ($_POST['id']) {
            //     $sql = "UPDATE shengchan_chanliang set jghId = '{$_POST['jiagonghuId']}' where id in (".join(',',$Cl).")";
            //     $this->_modelExample->execute($sql);
            // }
            $sql = "UPDATE shengchan_chanliang set jghId = '{$_POST['jiagonghuId']}' where id in (".join(',',$Cl).")";
            $this->_modelExample->execute($sql);
        }
        // dump($row);exit();
        if(!$this->_modelExample->save($row)) {
            js_alert('保存失败','window.history.go(-1)');
            exit;
        }
        //跳转
        js_alert(null,'window.parent.showMsg("保存成功!")',url($_POST['fromController'],$_POST['fromAction'],array()));
        exit;
    }

    /**
     * 查询界面
     * Time：2015/04/13 19:05:09
     * @author li
    */
    function actionRight(){
        $this->authCheck('14-9');
        FLEA::loadClass('TMIS_Pager');
        $arr = TMIS_Pager::getParamArray(array(
            'dateFrom'=>date('Y-m-d',mktime(0,0,0,date('m'),date('d')-30,date('Y'))),
            'dateTo'=>date('Y-m-d'),
            'jiagonghuId'=>'',
            'gongxuId'=>'',
            'key' => '',
            'orderCode'=>'',
        ));

        $sql="select x.*,y.id as sonId,y.unit,y.cnt,y.cntJian,y.chanliangId,y.ord2proId,z.proCode,z.proName,z.guige,z.color,g.name as gxName,j.compName,o.orderCode,y.memo as memo1,y.type as yType,y.ganghao,z.menfu as zMenfu,z.kezhong as zKezhong
            from cangku_fawai x
            left join cangku_fawai2product y on x.id=y.fawaiId
            left join jichu_product z on z.id=y.productId
            left join jichu_gongxu g on g.id=y.gongxuId
            left join jichu_jiagonghu j on j.id=x.jiagonghuId
            left join trade_order o on o.id=y.orderId
            where 1 and x.type = '1'";
        if($arr['jiagonghuId']>0){
            $sql.=" and x.jiagonghuId='{$arr['jiagonghuId']}'";
        }
        if($arr['gongxuId']>0){
            $sql.=" and y.gongxuId='{$arr['gongxuId']}'";
        }
        if($arr['key']!=''){
            $sql.=" and (z.proCode like '%{$arr['key']}%'
                        or z.proName like '%{$arr['key']}%'
                        or z.guige like '%{$arr['key']}%'
                        or z.color like '%{$arr['key']}%'
                )";
        }
        if($arr['dateFrom']!=''){
            $sql.=" and x.rukuDate >= '{$arr['dateFrom']}' and x.rukuDate <='{$arr['dateTo']}'";
        }
        if($arr['orderCode']!='') $sql.=" and o.orderCode like '%{$arr['orderCode']}%'";
        // dump($sql);exit;
        //按照时间排序然后是倒序
        $sql.=" order by x.rukuDate desc,y.ord2proId asc";
        $str = "select * from (".$sql.") as a where 1";
        //查找计划
        $pageSize = 50;
        $pager = &new TMIS_Pager($str,null,null,$pageSize);
        $rowset = $pager->findAll();
        $rowsetAll = $this->_modelExample->findBySql($str);
        foreach($rowset as &$v) {
            $sql="select zhijiCode from shengchan_chanliang where id='{$v['chanliangId']}'";
            $jizhiCode=$this->_modelExample->findBySql($sql);
            $v['zhijiCode'] = $jizhiCode[0]['zhijiCode'];
            $v['_edit'] = $this->getEditHtml($v['id']);
            //删除操作
            $v['_edit'] .= ' ' .$this->getRemoveHtml($v['id']);
            $v['_edit'].="&nbsp;<a href data_chanliang='{$v['chanliangId']}' data_chuku2proId='{$v['sonId']}' data_ord2proId='{$v['ord2proId']}' name='madanSearch' title='查看明细数据' >码单</a>";
            $v['_edit'] .="&nbsp;<a href='".url('shengchan_Waixie_FawaiNew','Export',array(
                    'fawaiId'=>$v['sonId'],
                    'ord2proId'=>$v['ord2proId']
                    ))."' >码单导出</a> ";
            $v['_edit'] .="&nbsp;<input type='checkbox' name='check[]' id='{$v['sonId']}' onclick='selClient(this,{$v['jiagonghuId']})' value='{$v['sonId']}' data_jiagonghuId='{$v['jiagonghuId']}'>";
            //门幅克重取订单页
            $sql="SELECT * from trade_order2product where id='{$v['ord2proId']}'";
            $proInfo = $this->_modelExample->findBySql($sql);
            $v['Tmenfu'] = $proInfo[0]['menfu'];
            $v['Tkezhong'] = $proInfo[0]['kezhong'];
            //查看该订单有没有出库，如果出库，那么背景色标记为绿色
            $sql="SELECT * from cangku_common_chuku2product where ord2proId='{$v['ord2proId']}'";
            $ischuku = $this->_modelExample->findBySql($sql);
            if($ischuku[0]){
                $v['_bgColor'] = '#8fbd8f';
            }
            //打印 by zcc 2018-02-11 需求3233
            // $v['_edit'] .= ' ' ."<a href='".$this->_url('PrintFahuo',array(
            //     'id'=>$v['id'],
            //     'rukuCode' => $v['rukuCode'],
            // ))."'>打印</a> ";

        }
        $rowset[]=$this->getHeji($rowset,array('cnt','cntJian'),'_edit');
        $zongji=$this->getHeji($rowsetAll,array('cnt','cntJian','_edit'));
        $zongji['_edit'] = '<b>总计</b>';
        $rowset[] = $zongji;
        $smarty = &$this->_getView();
        // 左侧信息
        $arrFieldInfo = array(
            //"_edit" => array('width'=>'190px','text'=>'操作'),
            '_edit' =>array('text'=>"全选/反选 <input type='checkbox' id='checkedAll' title='全选/反选'/>",'width'=>'190') ,
            'rukuCode'=>'发外单号',
            'rukuDate'=>'发生日期',
            'compName'=>'加工户',
            'gxName'=>'工序',
            'orderCode'=>'订单号',
            'proCode'=>'产品编号',
            "proName" => "品种",
            'guige' => '规格',
            'color' => '颜色',
            //'yType' =>'区分',
            'zhijiCode' =>'机台号',
            'Tmenfu' =>'门幅',
            'Tkezhong' =>'克重',
            'cntJian'=>'件数',
            'cnt'=>'数量',
            //'ganghao' =>'缸号',
            'unit'=>'单位',
            'creater'=>'操作人',
            'memo1'=>'备注',
        );
        $smarty->assign('title', '计划查询');
        $smarty->assign('arr_field_info', $arrFieldInfo);
        $smarty->assign('add_display', 'none');
        $smarty->assign('arr_condition', $arr);
        $smarty->assign('arr_field_value', $rowset);
        $smarty->assign('sonTpl', 'Shengchan/fawaiPrint.tpl');
        $smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action'], $arr)));
        $smarty->display('TblList.tpl');
    }

    function actionShezhiZd(){
        $smarty=& $this->_getView();
        $smarty->assign('receiveId', $_GET['ckPrintId']);
        $smarty->display('Waixie/SetFieldPrint.tpl');
    }
    function actionMadanView(){
         $sql = "SELECT
            x.id,
            x.cnt,
            x.cnt AS cntFormat,
            f.ExpectCode AS lot,
            x.jiagonghuId,
            x.jghId
        FROM
            shengchan_chanliang x
        LEFT JOIN shengchan_planpj_fenpi f ON x.fenpiId = f.id
        left join cangku_fawai2product_chanliang c on c.chanliangId=x.id
        WHERE 1  and x.ord2proId='{$_GET['ord2proId']}' and c.fawaiId='{$_GET['chuku2proId']}'";
        //dump($sql);die;
        $rowset = $this->_modelExample->findBySql($sql);
        //dump($_GET['chuku2proId']);die;
        // dump($rowset);exit();
        foreach ($rowset as $key => &$v) {
            $v['number'] = $key+1;
            $sql = "SELECT c2 as seCode FROM check_main where ExpectCode = '{$v['lot']}' AND kind = 'pibu'";
            $seCode = $this->_modelExample->findBySql($sql);
            $v['seCode'] = $seCode[0]['seCode'];
            if ($_GET['chuku2proId']!='') {//修改时
                //第一步 先找到中间表中所对应之前勾选的数据 进行勾选显示
                $sql = "SELECT * FROM cangku_fawai2product_chanliang where chanliangId = '{$v['id']}'";
                $chanliang = $this->_modelExample->findBySql($sql);
                if (count($chanliang)>=1) {//有数据则标记勾选
                    /*if ($v['seCode']==$_GET['seCode']) { //2019/04/02 页面secode已取消 暂注释掉
                        $v['isChecked'] = true;
                    }*/
                    $v['isChecked'] = true;
                }
                //第二步 如果本产量还在 发出厂或接受厂 中 则不用设置只读 否则 为只读  (方法待改进)
                // if ($v['jghId'] == $_GET['jiagonghuId'] || $v['jghId'] == $_GET['jghFromId']) {
                //     $v['disabled'] = false;
                // }else{
                //     $v['readonly'] = true;
                // }
            }else{//新增
                //第一步 判断产量是否在本发出厂 在则可发出 不在 则 勾选只读
                // if ($v['jghId'] == $_GET['jghFromId']) {
                //     $v['isChecked'] = false;
                // }else{
                //     $v['isChecked'] = true;
                //     $v['disabled'] = true;
                // }
                //第二步标记判断本产量
            }
            //和传递过来的色号不一样 就不许选中
            // if ($v['seCode']!=$_GET['seCode']) {
            //     $v['disabled'] = true;
            // }
        }
        // exit();
        //dump($rowset);die;
        $row = array('Madan'=>$rowset); 
        $smarty = & $this->_getView();
        $smarty->assign('title', "入库码单编辑");
        $smarty->assign('madanRows', json_encode($rowset));
        $smarty->display("Waixie/MadanSearch.tpl");
        
    }

    function actionExport(){
        $tpl = 'Shengchan/Pijian/Export.tpl';
        $sql = "select chanliangId from cangku_fawai2product where id='{$_GET['fawaiId']}'";
        $fwInfo = $this->_modelExample->findBySql($sql);
        $chanliangId = $fwInfo[0]['chanliangId'];
        $sql = "select
            x.id,
            x.cnt,
            x.cnt AS cntFormat,
            f.ExpectCode AS lot,
            x.jiagonghuId,
            x.jghId,
            a.rukuDate as date,
            j.compName,
            t.cntYaohuo,
            p.proCode,
            p.proName,
            p.kezhong,
            p.menfu,
            p.color,
            p.guige
        FROM
            shengchan_chanliang x
        LEFT JOIN shengchan_planpj_fenpi f ON x.fenpiId = f.id
        left join cangku_fawai2product w on w.chanliangId=x.id
        left join cangku_fawai a on a.id=w.fawaiId
        left join jichu_jiagonghu j on j.id=x.jiagonghuId
        left join trade_order2product t on t.id=x.ord2proId
        left join jichu_product p on p.id=x.productId
        left join cangku_fawai2product_chanliang cc on cc.chanliangId=x.id
        WHERE 1  and x.ord2proId in ({$_GET['ord2proId']}) and cc.id is not null and cc.fawaiId='{$_GET['fawaiId']}'";
        $madan = $this->_modelExample->findBySql($sql);
        // dump($madan);die;
        foreach ($madan as $key => &$value) {
           $madan_son1=array();
           $sql = "select c.*,f.ExpectCode 
                   from shengchan_chanliang c
                   left join shengchan_planpj_fenpi f on c.fenpiId=f.id
                   where c.id='{$value['id']}'";
           $re = $this->_modelExample->findBySql($sql);
           $madan_son1[] = $re[0];
           $value['Son'] = $madan_son1;
           //判断数组的数量
           $number=count($value['Son']);
           for ($i=0; $i <$number ; $i++){
                // 将checkinfo表中的lot_no赋值给码单表
                $value['Son'][$i]['lot']=$key+1;
                $value['Son'][$i]['number'] = $key+1;
           }
           // $value['Son'][$key]['lot'] = $key;
           //$value['Son'][$key]['number'] = $key+1;
        }
        //dump($madan);die;
        $madanNew = array();
        $madanNew[0] = $madan[0];
        $madanSonNew = array();
        foreach ($madan as $key => &$values) {
            foreach ($values['Son'] as $ke => &$vals) {
                $madanSonNew[] = $vals;
            }
        }
        $madanNew[0]['Son'] = $madanSonNew;
        //得到每页显示4列
        $cnt1=array();
        for($i=0;$i<4;$i++) {
            $cnt1[]=$i;
        }
        //得到每列显示25行
        $cnt2=array();
        for($i=0;$i<25;$i++) {
            $cnt2[]=$i;
        }
        foreach($madanNew as & $row) {
            $fayun=0;
            $arr=array();
            foreach($row['Son'] as & $v) {
            //dump($v);
            #取得最大值，确定所需的表格数及求得发运数
                $fayun+=$v['cnt'];
                //$m=substr($v['number'],0,-1);
                $v['num']=$v['number'].'#';
                $arr[]=$v['number'];
            }
            //dump($arr);exit;
            $max=max($arr);
            $num[]=ceil($max/100.00);
            $row['fayun']=$fayun;
            $row['cntJian']=count($row['Son']);
        }
        #取得小计
        //得到5列
        if(count($row['Son'])<100)for($i=count($row['Son']);$i<100;$i++) {
                $row['Son'][$i]='';
            }
        $kk=array();
        foreach($num as & $v) {
            $rr=array();
            for($i=0;$i<$v;$i++) {
                $rr[]=$i;
            }
            $kk[]=$rr;
        }

        $xiaoji = array();
        //转换$madanNew,使下标和卷号相同
        //dump($madanNew);
        $tempMandan=array();
        foreach($madanNew as $k=>& $v) {
            foreach($v['Son'] as & $vv) {
                if($vv['number']=='') continue;
                $tempMandan[$k][$vv['number']] = $vv;
            }
        }
        //dump($tempMandan);exit;
        //dump($madanNew);dump($num);exit;
        foreach($num as $k=>&$v) {
            $huaxing = $tempMandan[$k];
            for($i=0;$i<$v;$i++) {
                $t=0;
                $temp = array();
                for($j=1;$j<26;$j++) {
                    $t+=$huaxing[$i*100+$j]['cnt'];
                }
                $temp[] = $t;

                $t=0;
                for($j=26;$j<51;$j++) {
                    $t+=$huaxing[$i*100+$j]['cnt'];
                }
                $temp[] = $t;

                $t=0;
                for($j=51;$j<76;$j++) {
                    $t+=$huaxing[$i*100+$j]['cnt'];
                }
                $temp[] = $t;

                $t=0;
                for($j=76;$j<101;$j++) {
                    $t+=$huaxing[$i*100+$j]['cnt'];
                }
                $temp[] = $t;
                $xiaoji[$k][$i] =$temp;
            //$xiaoji[] = $temp;
            }

        }

        //判断码单的输出格式
        foreach($madanNew as & $v) {
            foreach($v['Son'] as & $vv) {
                $vv['type']='Number';
                if(is_numeric($vv['cntformat'])) {
                    $vv['type'] = 'Number';
                }
            }
        }

        //计算每个花型的需要的分页行位置
        $arrPos = array();
        $head=4;
        foreach($num as $k=>&$v) {
            for($i=1;$i<$v;$i++){
                $arrPos[$k][] = $head+$i*28;
            }
        }
        //dump($madanNew);exit;
        $smarty=& $this->_getView();
        $smarty->assign('madan',$madanNew);
        $smarty->assign('cnt1',$cnt1);
        $smarty->assign('cnt2',$cnt2);
        $smarty->assign('num',$kk);
        $smarty->assign('name',$name);
        $smarty->assign('arrPos',$arrPos);
        $smarty->assign('xiaoji',$xiaoji);
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/download");
        header("Content-Disposition: attachment;filename=码单导出.xls");
        header("Content-Transfer-Encoding: binary");
        $smarty=$smarty->display($tpl);
    }

    /**
     * 修改界面
     * Time：2015/04/13 19:49:08
     * @author li
    */
    function actionEdit(){
        $this->authCheck('14-9');
        $this->prototype();
        $arr = $this->_modelExample->find(array('id' => $_GET['id']));
        $arr['gongxuId'] = $arr['Son'][0]['gongxuId'];
        $arr['isNew']= $arr['type'];
        foreach ($this->fldMain as $k => &$v) {
            $v['value'] = $arr[$k]?$arr[$k]:$v['value'];
        }
        //加载库位信息的值
        $areaMain = array('title' => $this->_kind.'基本信息', 'fld' => $this->fldMain);

        // 入库明细处理
        $rowsSon = array();
        // dump($arr);die;
        foreach($arr['Son'] as &$v) {
            $sql="select c.id as chanliangId from shengchan_chanliang c left join cangku_fawai2product_chanliang l on c.id=l.chanliangId where c.ord2proId='{$v['ord2proId']}' and l.fawaiId='{$v['id']}' and l.chanliangId is not null";
            $chanl = $this->_modelExample->findBySql($sql);
            $chanliangIds=array();
            foreach ($chanl as $key => &$val) {
                $chanliangIds[]=$val['chanliangId'];
            }
            $chanliangIds1=implode(',', $chanliangIds);
            $v['chanliangId']=$chanliangIds1;
            //获取所对应数据的产量信息 显示到码单中
            $Madans = array();
            foreach ($chanliangIds as $keys => &$valss) {
                $sql1 = "SELECT x.cnt,z.c2 as ganghao,y.ExpectCode,x.id as chanliangId
                FROM shengchan_chanliang x 
                LEFT JOIN shengchan_planpj_fenpi y on x.fenpiId = y.id
                LEFT JOIN check_main z on z.ExpectCode = y.ExpectCode
                LEFT JOIN cangku_fawai2product_chanliang c on c.chanliangId=x.id
                WHERE x.id =$valss AND z.kind = 'pibu' and c.id is not null
                ";
                $Madan = $this->_modelExample->findBySql($sql1);
                $Madans[] = $Madan[0];
            }
            $string1 = array();
            $string2 = array();
            foreach ($Madans as &$va) {
                $string1[] = $va['chanliangId'];
                $string2[] = $va['ExpectCode'];
            }
            $v['Madan'] = join(',',$string1).";".join(',',$string2);
            $sql = "select * from jichu_product where id='{$v['productId']}'";
            $_temp = $this->_modelExample->findBySql($sql); //dump($_temp);exit;
            $v['proCode'] = $_temp[0]['proCode'];
            $v['proName'] = $_temp[0]['proName'];
            $v['guige'] = $_temp[0]['guige'];
            $v['color'] = $_temp[0]['color'];
            $v['menfu'] = $_temp[0]['menfu'];
            $v['kezhong'] = $_temp[0]['kezhong'];

        }
        $arr['Son']=array_column_sort($arr['Son'],'ord2proId',SORT_ASC);//根据订单排序
        foreach($arr['Son'] as &$v) {
            $temp = array();
            foreach($this->headSon as $kk => &$vv) {
                $temp[$kk] = array('value' => $v[$kk]);
            }
            $rowsSon[] = $temp;
        }

        //填充计划显示的信息
        foreach ($rowsSon as $key => & $v) {
            if(!$v['planId']['value'])continue;
            // dump($v);exit;
            $sql="select x.planCode from shengchan_plan x where x.id='{$v['planId']['value']}'";
            $_temp = $this->_modelExample->findBySql($sql);
            // dump($_temp);exit;
            $v['planCode']['value']=$_temp[0]['planCode'];
        }

        //补齐5行
        // $cnt = count($rowsSon);
        // for($i=3;$i>$cnt;$i--) {
        //     $rowsSon[] = array();
        // }
        // dump($rowsSon);exit;

        $smarty = &$this->_getView();
        $smarty->assign('areaMain', $areaMain);
        $smarty->assign('headSon', $this->headSon);
        $smarty->assign('rowsSon', $rowsSon);
        $smarty->assign('fromAction', $_GET['fromAction']);
        $smarty->assign('rules', $this->rules);
        $smarty->assign('sonTpl', "Waixie/FawaiEdit.tpl");
        $smarty->display('Waixie/Fawai_A.tpl');
    }
    /**
     * ps ：重新引入界面方法
     * Time：2017/08/03 13:33:24
     * @author zcc
    */
    function actionViewChoose(){
        // dump($_GET);exit();
        FLEA::loadClass('TMIS_Pager');
        $this->title = '坯布产量发外';
        FLEA::loadClass('TMIS_Pager');
        $arr = TMIS_Pager::getParamArray(array(
            'jiagonghuIdFc' =>$_GET['jghFromId'],
            'jiagonghuId' =>$_GET['jiagonghuId'],
            'gongxuId' =>$_GET['gongxuId'],
        )); 
        $sql = "SELECT x.id as chanliangId,x.jiagonghuId,x.cnt,x.fenpiId,x.ord2proId,
            y.id as planId,z.id as plan2hzlId,z.gongxuId,y.planCode,g.name as gongxuName,j.compName as jiagonghuName,x.jiagonghuId as fachuJGh
            FROM shengchan_chanliang x
            LEFT JOIN shengchan_plan y on x.planId = y.id
            LEFT JOIN shengchan_plan2houzheng z ON y.id = z.planId
            left join jichu_gongxu g on g.id = z.gongxuId
            left join jichu_jiagonghu j on j.id = z.jiagonghuId
            WHERE 1 
            AND x.fenpiId > 0 
            AND z.jiagonghuId = '{$_GET['jiagonghuId']}'
            AND z.jiagonghuId != '{$_GET['jghFromId']}'
            AND z.gongxuId = '{$_GET['gongxuId']}'
            ";
        if ($_GET['isRecheck']) {//为引入(修改界面中)时 则要显示已修改的数据 by zcc 2018年1月23日 10:33:28
           $sql .= " AND x.jghId  in ({$_GET['jghFromId']},{$_GET['jiagonghuId']})";
        }else{//为新增界面的重新引入时，则已经从这个发出加工户 中发出的 则不要显示
            $sql .= " AND x.jghId  in ({$_GET['jghFromId']})";
        }    
        $sql .= "order by gongxuId,z.jiagonghuId,x.id asc";
        // dump($sql);die();  
        $pager = &new TMIS_Pager($sql);
        $rowset = $pager->findAll();
        if (count($rowset)>0) foreach($rowset as & $v) {
            $v['isCheck'] = "<input type='checkbox' name='ck' id='ck' value='{$v['chanliangId']}' data='{$v['plan2hzlId']}' />";
            $sql = "SELECT y.checkId as juanhao,y.c2 as gHao
                FROM 
                shengchan_planpj_fenpi x 
                left join check_main y on x.ExpectCode = y.ExpectCode
                where x.id = '{$v['fenpiId']}' and y.kind = 'pibu'";    
            $main = $this->_modelExample->findBySql($sql);
            $v['juanCode'] =  $main[0]['juanhao'];
            $v['ganghao'] =  $main[0]['gHao'];
            $sql = "SELECT 
                    p.id as productId,
                    y.orderCode,
                    x.id as ord2proId,
                    p.proName,
                    p.proCode,
                    p.guige,
                    p.color,
                    y.id as orderId
                FROM  trade_order2product x 
                LEFT JOIN trade_order y ON y.id = x.orderId
                LEFT JOIN jichu_product p ON p.id = x.productId
                WHERE 1 AND x.id = {$v['ord2proId']}";
            $main = $this->_modelExample->findBySql($sql);
            $v['proName'] = $main[0]['proName'];
            $v['proCode'] = $main[0]['proCode'];  
            $v['guige'] = $main[0]['guige'];  
            $v['color'] = $main[0]['color'];  
            $v['productId'] = $main[0]['productId'];  
            $v['orderCode'] = $main[0]['orderCode'];  
            $v['orderId'] = $main[0]['orderId'];
            $sql = "SELECT compName FROM jichu_jiagonghu where id = '{$v['fachuJGh']}'";
            $fachuJGh = $this->_modelExample->findBySql($sql);
            $v['jiagonghuNameFc'] = $fachuJGh[0]['compName'];                
        }
        $heji = $this->getHeji($rowset,array('cnt'),'isCheck');
        $rowset[] = $heji;
        $smarty = & $this->_getView();
        $smarty->assign('title', $this->title);
        $arr_field_info = array(
            'isCheck'=>array("text"=>'<a href="javascript:void(0)" id="ckAll">全选</a>','width'=>'50'),
            "planCode" =>"计划单号",
            "proCode" =>"产品编号",
            'proName' =>'品名',
            "guige" =>"规格",
            "color" =>"颜色",
            "juanCode" =>"卷号",
            "cnt" =>"数量",
            "ganghao" =>"缸号",
            'gongxuName' =>'工序',
            'jiagonghuNameFc' =>'发出加工户',
            'jiagonghuName' =>'加工户',
            
        );
        $smarty->assign('arr_field_info', $arr_field_info);
        $smarty->assign('add_display', 'none');
        $smarty->assign('arr_condition', $arr);
        $smarty->assign('arr_field_value', $rowset);
        $btn_rk = "<a href='javascript:;' id='openMadanInfo2',
        'cid'=>
        >确认并发外</a>";
        $smarty->assign('other_url', $btn_rk);
        $smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action']), $arr).$msg);
        $smarty->assign('arr_js_css', $this->makeArrayJsCss(array('grid', 'calendar')));
        $smarty->assign('sonTpl', 'Waixie/_jsFawaiView.tpl');
        $this->_beforeDisplayRight($smarty);
        $smarty->display('TableList.tpl'); 
    }
    /**
     * ps ：选择重新引入后JS 重组数据的ajax
     * Time：2017/08/03 13:33:49
     * @author zcc
    */
    function actionAjaxFawai(){
        $sql = "SELECT x.id as chanliangId,x.jiagonghuId,sum(x.cnt) as cnt,x.ord2proId,COUNT(*) as cntJian,
            y.id as planId,z.id as plan2hzlId,z.gongxuId,y.planCode,g.name as gongxuName,j.compName as jiagonghuName,x.jiagonghuId as fachuJGh,m.c2 as ganghao
            FROM shengchan_chanliang x
            LEFT JOIN shengchan_plan y on x.planId = y.id
            LEFT JOIN shengchan_plan2houzheng z ON y.id = z.planId
            LEFT JOIN jichu_gongxu g on g.id = z.gongxuId
            LEFT JOIN jichu_jiagonghu j on j.id = z.jiagonghuId
            LEFT JOIN shengchan_planpj_fenpi pf on pf.id = x.fenpiId
            LEFT JOIN check_main m  on x.checkMainId = m.id
            WHERE 1 
            AND x.fenpiId > 0 
            AND m.kind = 'pibu'
            AND x.id in ({$_GET['chanliangId']}) AND z.jiagonghuId = '{$_GET['jiagonghuId']}'
            AND x.jghId in ('{$_GET['jiagonghuIdFc']}',{$_GET['jiagonghuId']})
            AND z.gongxuId = '{$_GET['gongxuId']}'
            ";
        $sql .= " GROUP BY plan2hzlId,ganghao order by ganghao";    
        $rowset = $this->_modelExample->findBySql($sql);
        // dump($rowset);exit();
        foreach ($rowset as &$v) {
            $sql = "SELECT x.cnt,z.c2 as ganghao,y.ExpectCode,x.id as chanliangId
                FROM shengchan_chanliang x 
                LEFT JOIN shengchan_planpj_fenpi y on x.fenpiId = y.id
                LEFT JOIN check_main z on z.ExpectCode = y.ExpectCode
                WHERE x.id in ({$_GET['chanliangId']}) AND x.planId = '{$v['planId']}' 
                AND z.c2 = '{$v['ganghao']}' AND z.kind = 'pibu'
                ";
            $Madan = $this->_modelExample->findBySql($sql);
            $string1 = array();
            $string2 = array();
            foreach ($Madan as &$va) {
                $string1[] = $va['chanliangId'];
                $string2[] = $va['ExpectCode'];
            }
            $v['Madan'] = join(',',$string1).";".join(',',$string2);
            //获取明细产品信息
            $sql = "SELECT 
                    p.id as productId,
                    y.orderCode,
                    x.id as ord2proId,
                    p.proName,
                    p.proCode,
                    p.guige,
                    p.color,
                    y.id as orderId
                FROM  trade_order2product x 
                LEFT JOIN trade_order y ON y.id = x.orderId
                LEFT JOIN jichu_product p ON p.id = x.productId
                WHERE 1 AND x.id = {$v['ord2proId']}";
            $main = $this->_modelExample->findBySql($sql);
            $v['proName'] = $main[0]['proName'];
            $v['proCode'] = $main[0]['proCode'];  
            $v['guige'] = $main[0]['guige'];  
            $v['color'] = $main[0]['color'];  
            $v['productId'] = $main[0]['productId'];  
            $v['orderCode'] = $main[0]['orderCode'];  
            $v['orderId'] = $main[0]['orderId'];
        }
        echo(json_encode($rowset));exit();
    }
    /**
     * ps ：修改界面ajax删除
     * Time：2017/08/07 11:02:46
     * @author zcc
    */
    function actionRemoveByAjax() {
        //dump($_POST['id']);die;
        $m = &FLEA::getSingleton('Model_Shengchan_Waixie_FawaiSon');
        $row = $m->find($_POST['id']);
        $ids = array();
        foreach ($row['Chanliang'] as &$v) {
            //查看这个产量是否已经发到另一个加工户 如果发出则不许删除
            $sql = "SELECT * FROM shengchan_chanliang where id = '{$v['chanliangId']}'";
            $chanliang = $this->_modelExample->findBySql($sql);
            if ($chanliang[0]['jghId']!=$row['Fawai']['jiagonghuId']) {
                // echo "本条数据不允许删除，产量已经不再本加工户!";
                $failure = '1';//有一条数据符合则不可以
            }
            $ids[] = $v['chanliangId'];
        }
        if ($failure != '1') { 
            // $r = $m->removeByPkv($_POST['id']);
        }else{
            $arr = array('success' => false, 'msg' => '本条数据不允许删除，产量已经发往其他加工户!');
            echo json_encode($arr);
            exit;
        }
        exit();
        if (!$r) {
            $arr = array('success' => false, 'msg' => '删除失败');
            echo json_encode($arr);
            exit;
        }
        $sql = "UPDATE shengchan_chanliang set jghId = '{$row['Fawai']['jghFromId']}' WHERE id in (".join(',',$ids).")";
        $this->_modelExample->execute($sql);
        $arr = array('success' => true);
        echo json_encode($arr);
        exit;
    }
    /**
     * ps ：后整发外打印
     * Time：2018-02-11 14:19:25
     * @author zcc
    */
   function actionPrintFahuo(){
        //前台是否显示缸号，机台，工序
        $ganghao = $_POST['ganghao'];
        $zhiJiCode= $_POST['zhiJiCode'];
        $gongxuName = $_POST['gongxuName'];
        //end
        $ids = explode(',', $_POST['receiveId']);
        $modelOrder = &FLEA::getSingleton('Model_Trade_Order');
        //查询出主表id
        $sql ="select f.id,f.rukuDate,f.rukuCode,j.compName 
               from cangku_fawai2product c
               left join cangku_fawai f on c.fawaiId=f.id
               left join jichu_jiagonghu j on j.id=f.jiagonghuId
               where c.id='{$ids[0]}'";
        $fw = $this->_modelExample->findBySql($sql);
        $arr['fawaiId'] = $fw[0]['id'];
        $arr['rukuDate'] = $fw[0]['rukuDate'];
        $arr['rukuCode'] = $fw[0]['rukuCode'];
        $arr['compName'] = $fw[0]['compName'];
        if(($ganghao==1&&$zhiJiCode==1&&$gongxuName==1)||($ganghao==1&&$zhiJiCode==1&$gongxuName==0)){
             $sql11="select sum(s.cnt) as cntAll,count(c.fawaiId) as cntJianAll,f.gongxuId,f.productId,f.orderId,s.ganghao,s.type,s.zhiJiCode,f.id as fawaiIds,f.ord2proId 
                 from
                 cangku_fawai2product_chanliang c 
                 left join cangku_fawai2product f on c.fawaiId=f.id
                 left join shengchan_chanliang s on c.chanliangId=s.id where f.id in ({$_POST['receiveId']}) group by f.orderId,f.productId,f.gongxuId,s.ganghao,s.type,s.zhiJiCode";
        }else{
            $sql11="select sum(s.cnt) as cntAll,count(c.fawaiId) as cntJianAll,f.gongxuId,f.productId,f.orderId,s.ganghao,s.type,s.zhiJiCode,f.id as fawaiIds,f.ord2proId
               from
               cangku_fawai2product_chanliang c 
               left join cangku_fawai2product f on c.fawaiId=f.id
               left join shengchan_chanliang s on c.chanliangId=s.id where f.id in ({$_POST['receiveId']}) group by f.orderId,f.productId";
            if ($ganghao==1&&$zhiJiCode==0&&$gongxuName==0) {
                $sql11 .=" ,s.ganghao,f.gongxuId,s.type";
            }elseif ($ganghao==1&&$zhiJiCode==0&&$gongxuName==1) {
                $sql11 .=" ,s.ganghao,f.gongxuId,s.type";
            }elseif ($ganghao==0&&$zhiJiCode==1&&$gongxuName==0) {
                $sql11 .=" ,s.zhiJiCode,f.gongxuId";
            }elseif ($ganghao==0&&$zhiJiCode==1&&$gongxuName==1) {
                $sql11 .=" ,s.zhiJiCode,f.gongxuId";
            }elseif ($ganghao==0&&$zhiJiCode==0&&$gongxuName==1) {
                $sql11 .=" ,f.gongxuId";
            }
        }
        $sonInfo=$this->_modelExample->findBySql($sql11);
        $arr['Son']=$sonInfo;
        $i=0;
        // dump($arr['Son']);die;
        foreach ($arr['Son'] as $key => &$v) {
            $i+=1;
            $sql = "SELECT * FROM jichu_gongxu WHERE id = '{$v['gongxuId']}'";
            $gongxu = $this->_modelExample->findBySql($sql);
            $v['gongxuName'] = $gongxu[0]['name'];
            $sql = "SELECT * FROM jichu_product WHERE id = '{$v['productId']}'";
            $product = $this->_modelExample->findBySql($sql);
            $orderDetail = $modelOrder->find(array('id'=>$v['orderId']));
            $v['orderCode'] = $orderDetail['orderCode'];
            $v['orderCode'] = substr($v['orderCode'] , 5 );
            $v['codeAtOrder'] = $orderDetail['Client']['codeAtOrder'];
            $v['proCode'] = $product[0]['proCode'];
            $v['proName'] = $product[0]['proName'];
            $v['guige'] = $product[0]['guige'];
            $v['color'] =$product[0]['color'];
            $v['menfu'] = $product[0]['menfu'];
            $v['kezhong'] = $product[0]['kezhong'];
            $v['xuhao'] = $i;
            $v['cntJianAll'] = round($v['cntJianAll']);
            if($v['type']){
                $v['ganghao'] = $v['ganghao'].'-'.$v['type'];
            }
            //克重和门幅取订单页
            $sql="SELECT * from trade_order2product where id='{$v['ord2proId']}'";
            $proInfo = $this->_modelExample->findBySql($sql);
            $v['menfu'] = $proInfo[0]['menfu'];
            $v['kezhong'] = $proInfo[0]['kezhong'];
        }
        //合计
        $heji = $this->getHeji($arr['Son'],array('cntJianAll','cntAll'),'xuhao');
        $heji['orderCode'] = "合计";
        $arr['Son'][] = $heji;
        //合计
        $smarty = &$this->_getView();
        $smarty->assign('obj', $arr);
        $default = FLEA::getAppInf('compName');//获取配置文件中公司名称
        $smarty->assign('default', $default);
        $smarty->assign('ganghao', $ganghao);
        $smarty->assign('gongxuName', $gongxuName);
        $smarty->assign('zhiJiCode', $zhiJiCode);
        $smarty->display('Waixie/FawaiPrintNew.tpl');
   }
}
?>