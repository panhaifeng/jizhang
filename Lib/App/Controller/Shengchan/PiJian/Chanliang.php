<?php
/*********************************************************************\
*  Copyright (c) 1998-2013, TH. All Rights Reserved.
*  Author :zcc
*  FName  :Chanliang.php
*  Time   :2014/08/30 09:18:48
*  Remark :
\*********************************************************************/
FLEA::loadClass('TMIS_Controller');
class Controller_Shengchan_PiJian_Chanliang extends Tmis_Controller {
    // **************************************构造函数 begin********************************
    function Controller_Shengchan_PiJian_Chanliang() {
        $this->_modelDefault = &FLEA::getSingleton('Model_Shengchan_Chanliang');
        $this->_modelExample = &FLEA::getSingleton('Model_Shengchan_Chanliang'); 
        // 定义模板中的主表字段
        $this->fldMain = array(
            'chanliangDate' => array('title' => '产量日期', "type" => "calendar", 'value' => date('Y-m-d')),
            'chanliangCode' => array('title' => '产量编号', "type" => "text", 'value' =>'系统自动生成','readonly' =>'readonly'),
            // 'ord2proId' => array('title' => '相关订单', "type" => "ord2propopup", 'value' => ''),
            // 'jiagonghuId' => array('type' => 'select', "title" => '加工户', 'name' => 'jiagonghuId','model'=>'Model_Jichu_Jiagonghu','action'=>'getOptions','readonly'=>true),
            'jiagonghuName' => array('title' => '加工户', "type" => "text", 'value' =>'','readonly' =>'readonly',),
            'jiagonghuId' => array('title' => '加工户id', "type" => "hidden", 'value' =>''),
            );
        $this->headSon = array(
            '_edit' => array('type' => 'btBtnRemove', "title" => '+5行', 'name' => '_edit[]'),
            'ExpectCode' => array('title' => '条码', 'type' => 'bttext', 'value' => '', 'name' => 'ExpectCode[]','readonly'=>true),
            'orderCode' => array('title' => '订单号', 'type' => 'bttext', 'value' => '', 'name' => 'orderCode[]','readonly'=>true),
            'proName' => array('title' => '品名', 'type' => 'bttext', 'value' => '', 'name' => 'proName[]','readonly'=>true),
            'guige' => array('title' => '规格', 'type' => 'bttext', 'value' => '', 'name' => 'guige[]','readonly'=>true),
            'menfu'=>array('title' => '门幅', 'type' => 'bttext', 'value' => '', 'name' => 'menfu[]','readonly'=>true),
            'kezhong'=>array('title' => '克重', 'type' => 'bttext', 'value' => '', 'name' => 'kezhong[]','readonly'=>true),
            'color' => array('title' => '颜色', 'type' => 'bttext', 'value' => '', 'name' => 'color[]','readonly'=>true),
            'ganghao' => array('title' => '缸号', 'type' => 'bttext', 'value' => '', 'name' => 'ganghao[]'),
            'beforeCnt' => array('title' => '称前重量', 'type' => 'bttext', 'value' => '', 'name' => 'beforeCnt[]'),
            'zhiJiCode'=> array('title' => '机台号', 'type' => 'bttext', 'value' => '', 'name' => 'zhiJiCode[]'),
            'type'=> array('title' => '区分', 'type' => 'bttext', 'value' => '', 'name' => 'type[]'),
            'cnt' => array('title' => '数量', 'type' => 'bttext', 'value' => '', 'name' => 'cnt[]'),
            'memo' => array('title' => '匹数', 'type' => 'bttext', 'value' => '', 'name' => 'memo[]'),
            // ***************如何处理hidden?
            'id' => array('type' => 'bthidden', 'name' => 'id[]'),
            'ord2proId' => array('type' => 'bthidden', 'value' => '','name'=>'ord2proId[]'),
            'planId' => array('type' => 'bthidden', 'value' => '','name'=>'planId[]'),
            'fenpiId' => array('type' => 'bthidden', 'value' => '','name'=>'fenpiId[]'),
            'productId' => array('type' => 'bthidden', 'value' => '','name'=>'productId[]'),
            'checkMainId' => array('type' => 'bthidden', 'value' => '','name'=>'checkMainId[]'),
        );   
        // 表单元素的验证规则定义
        $this->rules = array(
            'ord2proId' => 'required',
            'workCode' => 'required',
            'cnt' => 'required',
            );
    }
    /**
     * ps ：坯检产量登记明细
     * Time：2017/06/30 16:20:29
     * @author zcc
    */
    function actionMadanVfly(){
       FLEA::loadClass('TMIS_Pager');
            $arr = TMIS_Pager::getParamArray(array(
                'ord2proId' => '',
            ));

            $sql = "SELECT *,
                        length as cnt,
                        checkId as number
                    from check_main where 1 ";


        if($arr['ord2proId']!=''){
            $sql.=" and ExpectCode like '{$arr['ord2proId']}-%'";
        }
        $sql.=" order by ExpectCode,checkId";
        //dump($sql);exit;
        $pager =& new TMIS_Pager($sql,null,null,100);
        $rowset =$pager->findAll();

        foreach ($rowset as $key => & $v) {

            $v['isRuku'] = $v['cprkId']>0 ? '是' : '否';
        }

        $rowset[]=$this->getHeji($rowset,array('weight','cnt','cutLength','inStockLength'),'orderId');

        $smarty = &$this->_getView();
        // 左侧信息
        $arrFieldInfo =array(
            "ExpectCode"    => '坯布条码',
            "number"        => '件号',
            'weight'        =>'重量数量',  
            'weightUnit'    =>'重量单位',
            "cnt"           => '长度',
            "lengthUnit"    => '单位',
            "cutLength"     => '开剪数量',
            "inStockLength" => array('text'=>'实际长度','width'=>160),
            "grade"         => '等级',
            "isRuku"        => '已入库',
            "checkTime"     => array('text'=>'检验时间','width'=>160),
        );

        $smarty->assign('title', '码单明细');
        $smarty->assign('arr_field_info', $arrFieldInfo);
        $smarty->assign('add_display', 'none');
        $smarty->assign('arr_condition', $arr);
        $smarty->assign('arr_field_value', $rowset);
        $smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action'], $arr)));
        $smarty->display('TblList.tpl');
    }
    /**
     * ps ：
     * Time：2017/06/30 17:09:59
     * @author zcc
     * @param 参数类型
     * @return 返回值类型
    */function actionChanliangPb(){
        $this->authCheck('14-1');
        $this->title = '坯布产量';
        FLEA::loadClass('TMIS_Pager');
        $arr = TMIS_Pager::getParamArray(array(
            'orderCode' => '',
            'ExpectCode' => '',
            // 'jizhi' => '',
        ));
        $sql = "SELECT x.*,y.id as ord2proId,z.orderCode,p.proName,p.proCode,p.guige,p.color,p.menfu,p.kezhong,m.jitai,m.jianhao,m.ganghao
            FROM check_main x
            left join shengchan_planpj_fenpi m on x.ExpectCode = m.ExpectCode
                left join trade_order2product y on y.id = m.ord2proId
                left join jichu_product p on p.id = y.productId
                left join trade_order z on y.orderId = z.id 
                left join shengchan_chanliang c on c.fenpiId = m.id
                where 1 and x.kind = 'pibu' and c.id is null and x.isRecovered<>1
            ";

        if($arr['orderCode'] != '') $sql.=" and z.orderCode like '%{$arr['orderCode']}%'";
        if($arr['ExpectCode'] != '') $sql.=" and x.ExpectCode like '%{$arr['ExpectCode']}%'";
        //$sql .=" group by y.orderId order by y.orderId";
        $sql .=" order by y.orderId,m.jianhao asc";
        $pager =& new TMIS_Pager($sql);
        $rowset =$pager->findAll();
        $rowseta = $rowset;
        $rowsetAll = $this->_modelExample->findBySql($sql);
        foreach($rowset as & $v){
            //添加操作
            $v['_edit'] = "<input type='checkbox' name='ck[]' value='{$v['ExpectCode']}' data='{$v['id']}'>";
            $v['weight'] = round($v['weight'],1);
            $v['cntKg'] = $v['weight'].$v['weightUnit'];
            $v['cnt'] = $v['length'].$v['lengthUnit'];
            //查看明细
            // $v['cntZ'] = "<a href='".$this->_url('MadanVfly',array(
            //         'factoryCode'=>$v['factoryCode'],
            //         'fromAction'=>$_GET['action'],
            //         'no_edit'=>1,
            //         'width'=>750,
            //         'TB_iframe'=>1
            //     ))."' class='thickbox' title='查看明细数据'>{$v['cntZ']} {$v['lengthUnit']}</a>";
            // $v['cntKgZ'] = "<a href='".$this->_url('MadanVfly',array(
            //         'ord2proId'=>$v['ord2proId'],
            //         'fromAction'=>$_GET['action'],
            //         'no_edit'=>1,
            //         'width'=>750,
            //         'TB_iframe'=>1
            //     ))."' class='thickbox' title='查看明细数据'>{$v['cntKgZ']} {$v['weightUnit']}</a>";

        }
        foreach($rowseta as & $vv){
            //添加操作
            $vv['_edit'] = "<input type='checkbox' name='ck[]' value='{$vv['ExpectCode']}' data='{$vv['id']}'>";
            $vv['weight'] = round($vv['weight'],1);
            $vv['cntKg'] = $vv['weight'];
            $vv['cnt'] = $vv['length'];
            //查看明细
            // $v['cntZ'] = "<a href='".$this->_url('MadanVfly',array(
            //         'factoryCode'=>$v['factoryCode'],
            //         'fromAction'=>$_GET['action'],
            //         'no_edit'=>1,
            //         'width'=>750,
            //         'TB_iframe'=>1
            //     ))."' class='thickbox' title='查看明细数据'>{$v['cntZ']} {$v['lengthUnit']}</a>";
            // $v['cntKgZ'] = "<a href='".$this->_url('MadanVfly',array(
            //         'ord2proId'=>$v['ord2proId'],
            //         'fromAction'=>$_GET['action'],
            //         'no_edit'=>1,
            //         'width'=>750,
            //         'TB_iframe'=>1
            //     ))."' class='thickbox' title='查看明细数据'>{$v['cntKgZ']} {$v['weightUnit']}</a>";

        }
        foreach($rowsetAll as & $va){
            //添加操作
            $va['_edit'] = "<input type='checkbox' name='ck[]' value='{$va['ExpectCode']}' data='{$va['id']}'>";
            $va['weight'] = round($va['weight'],1);
            $va['cntKg'] = $va['weight'];
            $va['cnt'] = $va['length'];
            //查看明细
            // $v['cntZ'] = "<a href='".$this->_url('MadanVfly',array(
            //         'factoryCode'=>$v['factoryCode'],
            //         'fromAction'=>$_GET['action'],
            //         'no_edit'=>1,
            //         'width'=>750,
            //         'TB_iframe'=>1
            //     ))."' class='thickbox' title='查看明细数据'>{$v['cntZ']} {$v['lengthUnit']}</a>";
            // $v['cntKgZ'] = "<a href='".$this->_url('MadanVfly',array(
            //         'ord2proId'=>$v['ord2proId'],
            //         'fromAction'=>$_GET['action'],
            //         'no_edit'=>1,
            //         'width'=>750,
            //         'TB_iframe'=>1
            //     ))."' class='thickbox' title='查看明细数据'>{$v['cntKgZ']} {$v['weightUnit']}</a>";

        }
        $heji = $this->getHeji($rowseta,array('cntKg'),'_edit');
        $zongji=$this->getHeji($rowsetAll, array('cntKg'), '_edit');
        $rowset[] = $heji;
        $zongji['_edit'] = '总计';
        $rowset[] = $zongji;
         
        $smarty = &$this->_getView();
        $arrFieldInfo =array(
            /*"_edit"       => array('text'=>'操作','width'=>40),*/
            '_edit'          =>array('text' => "<input type='checkbox' id='checkAll' ext:qtip='全选/反选'>选择", 'width' => 60),
            'orderCode'   => '订单编号',
            "proCode"   =>'产品编号',
            "proName"   =>'品名',
            "guige"   =>'规格',
            "color"   =>'颜色',
            "menfu"   =>'门幅',
            "kezhong" =>'克重',
            "ExpectCode" => '条码',
            // 'jitai' =>'机台号',
            'jianhao' =>'件号',
            // 'checkId'=>'卷号',
            //'dangchegong' =>'挡车工',
            "cntKg" => "重量",
            "c4"    => "区分"
            // "cnt"    => "长度",
            // "c1"    => "工号",
            // "c2"    => "色号",
            // "ganghao"    => "缸号",
        );
 
        $smarty->assign('title', '验布入库列表');
        $smarty->assign('arr_field_info', $arrFieldInfo);
        $smarty->assign('add_display', 'none');
        $smarty->assign('arr_condition', $arr);
        $smarty->assign('arr_field_value', $rowset);
        $smarty->assign('sonTpl', "shengchan/Pijian/_jsChanliangPb.tpl");
        $btn_rk = "<a href='".$this->_url('Add',array('fromAction'=>$_GET['action']))."' id='openMadanInfo',
        'cid'=>
        >确认并入库</a>";
        $smarty->assign('other_url', $btn_rk);
        $smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action'], $arr)));
        $smarty->display('TblList.tpl');        
    }
    /**
     * ps ：坯布检验 就是产量登记
     * Time：2017/08/04 16:31:46
     * @author zcc
    */
    function actionAdd(){
        $factoryCode = mysql_real_escape_string($_GET['factoryCode']);
        $factoryId = mysql_real_escape_string($_GET['factoryId']);
        if(!$factoryCode){
            echo "明细为空，不能入库";exit;
        }
        $factoryCode = explode(',',$factoryCode);
        foreach ($factoryCode as $key =>& $v) {
            $v="'{$v}'";
        }
        $factoryId = explode(',',$factoryId);
        foreach ($factoryId as $key =>& $v) {
            $v="'{$v}'";
        }
        $factoryCode = join(',',$factoryCode);
        $factoryId = join(',',$factoryId);
        // dump($_GET);exit();
       // 查找码单信息
        $sql = "SELECT round(sum(x.length),2)as cnt,round(sum(x.weight),2)as cntKg,count(x.checkId) as cntJuan,x.ExpectCode,round(sum(x.c1),2) as beforeCnt,x.c2 as ganghao,x.c3 as jizhiName,x.c4 as type,x.lengthUnit,x.weightUnit,x.id as checkMainId
                from check_main x
                left join check_main2chenpin m on m.mainId=x.id
                where 1 and x.ExpectCode in ($factoryCode) AND x.id in ($factoryId)
                and x.kind = 'pibu'
                group by x.ExpectCode,x.c2,x.c3";
        // dump($sql);exit();        
        $res = $this->_modelExample->findBySql($sql);
        // dump($res);exit();
          //  判断入库类型
        $order2proId = $_GET['factoryCode'];

        foreach ($res as $key => & $v) {
            //查找订单中的信息
            $sql_1 = "SELECT x.*,
                        y.proName,
                        y.color,
                        y.menfu,
                        y.kezhong,
                        y.guige,
                        y.chengfen,
                        y.proCode,
                        y.kezhong,
                        o.orderCode,
                        f.jitai,f.id as fenpiId,f.shengchanId as planId,f.ganghao as fpGanghao
                    from trade_order2product x
                    left join shengchan_planpj_fenpi f on f.ord2proId = x.id
                    left join trade_order o on o.id =x.orderId
                    left join jichu_product y on y.id=x.productId
                    WHERE f.ExpectCode='{$v['ExpectCode']}'";
            $tempOrder = $this->_modelExample->findBySql($sql_1);
            // dump($tempOrder);die;

            $v['productId'] = $tempOrder[0]['productId'];
            $v['proCode'] = $tempOrder[0]['proCode'];
            $v['proName'] = $tempOrder[0]['proName'];
            $v['color'] = $tempOrder[0]['color'];
            $v['colorNum'] = $tempOrder[0]['color'];
            $v['guige'] = $tempOrder[0]['guige'];
            $v['menfu'] = $tempOrder[0]['menfu'];
            $v['orderId'] = $tempOrder[0]['orderId'];
            $v['ord2proId'] = $tempOrder[0]['id'];
            $v['orderId'] = $v['orderId'];
           
            $arr['Products'][] = array(
                'fenpiId'=>$tempOrder[0]['fenpiId'],
                'planId'=>$tempOrder[0]['planId'],
                'ExpectCode'=>$v['ExpectCode'],
                'productId'=>$v['productId'],
                'workCode'=>$v['workCode'],
                'proName'=>$v['proName'],
                'proCode'=>$v['proCode'],
                'guige'=>$v['guige'],
                'color'=>$v['color'],
                'menfu' =>$tempOrder[0]['menfu'],
                'kezhong' =>$tempOrder[0]['kezhong'],
                'ord2proId'=>$v['ord2proId'],
                'ganghao'=>$v['ganghao'].'',
                'type'=>$v['type'].'',
                'beforeCnt'=>$v['beforeCnt'],
                'dengji'=>$v['dengji'],
                'jizhiId' =>0,
                'cnt' =>$v['cntKg'],
                'memo' =>count(tempOrder),
                'orderCode' =>$tempOrder[0]['orderCode'],
                'checkMainId' => $v['checkMainId'],
                'zhiJiCode' => $v['jizhiName'],
                // 'unit'=>'KG',
                
            );

        }
        //dump($arr['Products']);die;
        foreach($arr['Products'] as &$v) {
            $temp = array();
            foreach($this->headSon as $kk => &$vv) {
                $temp[$kk] = array('value' => $v[$kk]);
            }
           // $temp['productId']['text']=$v['productId'];
            $rowsSon[] = $temp;
        }
        //坯布检验(产量登记) 的加工户默认为沃丰
        $this->fldMain['jiagonghuId']['value'] = '63';
        $this->fldMain['jiagonghuName']['value'] = '金马布业';
        // dump($rowsSon);die;
        $smarty = &$this->_getView();
        $areaMain = array('title' => $this->_kind.'基本信息', 'fld' => $this->fldMain);
        $smarty->assign('areaMain', $areaMain);     
        $smarty->assign('headSon', $this->headSon);
        $smarty->assign('rowsSon', $rowsSon);

        $_from = $_GET['fromAction']==''?'add':$_GET['fromAction'];
        $smarty->assign('fromAction', $_from);
        // $smarty->assign('rules', $this->rules);
        $smarty->assign('sonTpl', 'Cangku/Chengpin/check.tpl');
        $smarty->display('Main2Son/T1.tpl');

    }
    function actionSave() {
        // dump($_POST);exit;
        if ($_POST['chanliangCode']=='系统自动生成'||$_POST['chanliangCode']=='') {
            $_POST['chanliangCode'] = $this->_getNewCode('CL','shengchan_chanliang','chanliangCode');
        }
        $chanliang = array();
        foreach ($_POST['ord2proId'] as $key => $v) {
            if (empty($_POST['ord2proId'][$key]) || empty($_POST['cnt'][$key])) continue;
            if ($_POST['id'][$key]) {//修改时 不让jghId变更
                $chanliang[] = array(
                    'chanliangDate' => $_POST['chanliangDate'],
                    'chanliangCode' => $_POST['chanliangCode'],
                    'planId' => $_POST['planId'][$key],
                    'ord2proId' => $_POST['ord2proId'][$key],
                    'checkMainId' => $_POST['checkMainId'][$key],//新增一个关联关系 2018年1月19日
                    // 'zhizao2planId' => $_POST['zhizao2planId'][$key],
                    'jiagonghuId' => $_POST['jiagonghuId'],
                    'workCode' => $_POST['workCode'][$key].'',
                    'cnt' => $_POST['cnt'][$key],
                    'memo' => $_POST['memo'][$key],
                    'productId' => $_POST['productId'][$key],
                    'id' => $_POST['id'][$key], 
                    'jizhiId'=>$_POST['jizhiId'][$key]+0,
                    'ganghao'=>$_POST['ganghao'][$key].'',
                    'type'=>$_POST['type'][$key].'',
                    'beforeCnt'=>$_POST['beforeCnt'][$key]+0,
                    'fenpiId'=>$_POST['fenpiId'][$key],
                    'zhiJiCode'=>$_POST['zhiJiCode'][$key],
                );
            }else{
               $chanliang[] = array(
                    'chanliangDate' => $_POST['chanliangDate'],
                    'chanliangCode' => $_POST['chanliangCode'],
                    'planId' => $_POST['planId'][$key],
                    'ord2proId' => $_POST['ord2proId'][$key],
                    'checkMainId' => $_POST['checkMainId'][$key],//新增一个关联关系 2018年1月19日
                    // 'zhizao2planId' => $_POST['zhizao2planId'][$key],
                    'jiagonghuId' => $_POST['jiagonghuId'],
                    'workCode' => $_POST['workCode'][$key].'',
                    'cnt' => $_POST['cnt'][$key],
                    'memo' => $_POST['memo'][$key],
                    'productId' => $_POST['productId'][$key],
                    'id' => $_POST['id'][$key], 
                    'jizhiId'=>$_POST['jizhiId'][$key]+0,
                    'ganghao'=>$_POST['ganghao'][$key].'',
                    'type'=>$_POST['type'][$key].'',
                    'beforeCnt'=>$_POST['beforeCnt'][$key]+0,
                    'fenpiId'=>$_POST['fenpiId'][$key],
                    'jghId'=>$_POST['jiagonghuId'],//给发外后整设计的一个字段 方便知道这个产量到了那个加工户那边
                    'zhiJiCode'=>$_POST['zhiJiCode'][$key],
                );  
            }
            
        }
        
        // dump($chanliang);exit;
        $itemId = $this->_modelExample->saveRowset($chanliang);
        js_alert(null,'window.parent.showMsg("保存成功!")',$this->_url('right'));
        exit;
    }
    /**
     * ps ：坯布产量登记查询
     * Time：2017/07/01 14:08:48
     * @author zcc
    */
    function actionRight(){
        FLEA::loadClass('TMIS_Pager'); 
        $this->authCheck('14-2');
        // /构造搜索区域的搜索类型
        $serachArea = TMIS_Pager::getParamArray(
            array(
                'dateFrom' => date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-30, date("Y"))),
                'dateTo' => date("Y-m-d"), 
                'orderCode' => '',
                'jizhi'=>'',
                // 'color'=>'',
                'ganghao'=>'',
                'key' => '',
                'jiagonghu'=>'',
                'ExpectCode'=>'',
                ));
        //ps:区分坯检的产量和不是的 看chanliangCode
        $str = "SELECT x.ord2proId,x.id,x.chanliangDate,x.chanliangCode,
                o.orderCode,p.proName,p.proCode,p.guige,p.color,x.workCode,
                round(x.cnt,1)as cnt,x.jizhiId,x.ganghao,x.type,j.compName as jiagonghu,x.memo as pishu,x.beforeCnt,
                x.zhiJiCode,z.jianhao,z.ExpectCode
            FROM shengchan_chanliang x 
            left join trade_order2product y on x.ord2proId = y.id
            left join jichu_product p on p.id = x.productId
            left join jichu_jiagonghu j on j.id = x.jiagonghuId
            left join trade_order o on y.orderId = o.id
            left join jichu_zhiji m on x.jizhiId=m.id
            left join shengchan_planpj_fenpi z on x.fenpiId = z.id
            where 1 and x.chanliangCode <>''";
        $str .= " and x.chanliangDate >= '$serachArea[dateFrom]' and x.chanliangDate<='{$serachArea[dateTo]}'";
        if ($serachArea['key'] != '') $str .= " and (x.workCode like '%{$serachArea[key]}%' or p.guige like '%{$serachArea[key]}%')";
        if ($serachArea['jizhi'] != '') $str .= " and x.jizhiId='{$serachArea[jizhi]}'";
        if ($serachArea['orderCode'] != '') $str .= " and o.orderCode like '%{$serachArea[orderCode]}%'";
        if ($serachArea['ganghao'] != '') $str .= " and x.ganghao like '%{$serachArea[ganghao]}%'";
        if ($serachArea['color'] != '') $str .= " and p.color like '%{$serachArea[color]}%'";
        if ($serachArea['ExpectCode'] != '') $str .= " and z.ExpectCode like '%{$serachArea[ExpectCode]}%'";
        if($serachArea['jiagonghu']!=''){

            $str .=" and j.compName like '%{$serachArea[jiagonghu]}%'";
        }
        if($serachArea['jizhi'] != '' && $serachArea['orderCode'] != ''){
            $str .= " order by z.jianhao asc"; 
        }else{
            $str .= " order by x.chanliangDate desc, o.orderCode desc"; 
        }
        //得到总计

        $zongji = $this->_modelExample->findBySql($str);
        $rowsetAll=$this->getHeji($zongji,array('pishu','cnt'));
        $zongji['pishu']=$rowsetAll['pishu'];
        $zongji['cnt']=$rowsetAll['cnt'];   
        $pager = &new TMIS_Pager($str);
        $rowset = $pager->findAll(); 
        //dump($rowset);exit;
        $trade_order2product = &FLEA::getSingleton('Model_Trade_Order2Product'); 
        if (count($rowset) > 0) foreach($rowset as &$value) {

                $value['_edit'] .= "<a href='" . $this->_url('Edit', array('chanliangCode' => $value['chanliangCode'])) . "'>修改</a> | ";
                $value['_edit'] .= "<a href='" . $this->_url('Remove', array('chanliangCode' => $value['chanliangCode'])) . "'  onclick=\"return confirm('确认删除吗?')\" >删除</a> |";
        }

        // 合计行
        $heji = $this->getHeji($rowset, array('cnt','pishu'), '_edit');//匹数合计
        $rowset[] = $heji;

        // 标题栏信息
        $arrFieldInfo = array("_edit" => '操作',
            "chanliangDate" => "产量日期",
            'orderCode' => '订单编号', 
            'jiagonghu'=>'加工户',
            // "compName" =>"客户名称",
            // 'workCode'=>'工号',
            'proName' => '品名',
            'guige' => '规格',
            "color" => '颜色',
            'ganghao'=>'缸号',
            "beforeCnt" => '称前重量',
            'zhiJiCode'=>'机台号',
            'type'=>'区分',
            // "pishu" =>'匹数',
            'jianhao' =>'件号',
            "cnt" => '数量', 
            "ExpectCode" => '条码', 
            //"bizhong" =>'币种',
            // "orderMemo" =>'订单备注',
            );

        $smarty = &$this->_getView();
        $smarty->assign('title', '产量查询');
        $smarty->assign('arr_field_info', $arrFieldInfo);
        $smarty->assign('add_display', 'none');
        $smarty->assign('arr_condition', $serachArea);
        $smarty->assign('arr_field_value', $rowset);
        $msg = "<font color='red'>匹数总计:{$zongji['pishu']}|数量总计：{$zongji['cnt']}</font>";
        $smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action']), $serachArea).$msg);
        $smarty->assign('arr_js_css', $this->makeArrayJsCss(array('grid', 'calendar')));
        $smarty->display('TableList.tpl');
    }
    /**
     * ps ：修改界面
     * Time：2017/07/01 15:47:59
     * @author zcc
    */
    function actionEdit() {
        $aRow=$this->_modelExample->findAll(array('chanliangCode'=>$_GET['chanliangCode']));
        $sql = "SELECT * from  jichu_jiagonghu WHERE id = '{$aRow[0]['jiagonghuId']}'";
        $jiagonghu = $this->_modelExample->findBySql($sql);
        $arr = array(
            'chanliangCode' =>$aRow[0]['chanliangCode'],
            'chanliangDate' =>$aRow[0]['chanliangDate'],
            'jiagonghuId' =>$aRow[0]['jiagonghuId'],
            'jiagonghuName' =>$jiagonghu[0]['compName'],
        );
        // dump($aRow);exit();
        foreach ($this->fldMain as $k => &$v) {
            $v['value'] = $arr[$k]?$arr[$k]:$v['value'];
        }
        $Products = array();
        foreach ($aRow as &$v) {
            $sql = "SELECT * FROM shengchan_planpj_fenpi where id = {$v['fenpiId']}";
            $fenpi = $this->_modelExample->findBySql($sql);
            $sql_1 = "SELECT y.orderCode 
                from  trade_order2product x 
                left join trade_order y on y.id= x.orderId
                where x.id = {$v['ord2proId']}";
            $order = $this->_modelExample->findBySql($sql_1);
            $Products[] = array(
                'id' =>$v['id'],
                'ord2proId' =>$v['ord2proId'],
                'productId' =>$v['productId'],
                'jizhiId' =>$v['jizhiId']+0,
                'cnt' =>$v['cnt'],
                'memo' =>$v['memo'],
                'proCode' =>$v['Products']['proCode'],
                'proName' =>$v['Products']['proName'],
                'guige' =>$v['Products']['guige'],
                'color' =>$v['Products']['color'],
                'menfu' =>$v['Products']['menfu'],
                'kezhong' =>$v['Products']['kezhong'],
                'ganghao' =>$v['ganghao'],
                'type'   =>$v['type'],
                'workCode' =>$v['workCode'],
                'ExpectCode' =>$fenpi[0]['ExpectCode'],
                'planId' =>$fenpi[0]['shengchanId'],
                'fenpiId' =>$v['fenpiId'],
                'orderCode' =>$order[0]['orderCode'],
                'checkMainId' => $v['checkMainId'],
                'beforeCnt'=>$v['beforeCnt'],
                'zhiJiCode'=>$v['zhiJiCode'],
            );
        }
        foreach($Products as &$v) {
            $temp = array();
            foreach($this->headSon as $kk => &$vv) {
                $temp[$kk] = array('value' => $v[$kk]);
            }
            $rowsSon[] = $temp;
        }
        $smarty = &$this->_getView();
        $areaMain = array('title' => $this->_kind.'基本信息', 'fld' => $this->fldMain);
        $smarty->assign('areaMain', $areaMain);     
        $smarty->assign('headSon', $this->headSon);
        $smarty->assign('rowsSon', $rowsSon);
        $_from = $_GET['fromAction']==''?'add':$_GET['fromAction'];
        $smarty->assign('fromAction', $_from);
        $smarty->assign('rules', $this->rules);
        $smarty->display('Main2Son/T1.tpl');
    }
    function actionRemove(){
        $from = $_GET['fromAction']?$_GET['fromAction']:$_GET['from'];
        if ($this->_modelExample->removeByConditions(array('chanliangCode'=>$_GET['chanliangCode']))) {
            if($from=='') redirect($this->_url("right"));
            else js_alert(null,"window.parent.showMsg('成功删除')",$this->_url($from));
        }
        else js_alert('出错，不允许删除!',$this->_url($from));
    }
    function actionRemoveByAjax() {
        //dump($_POST['id']);die;
        $m = &FLEA::getSingleton('Model_Sample_Shenqing');
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
     * ps ：按日期统计织机、挡车工、验布工的产量
     * Time：2017/07/03 15:05:12
     * @author zcc
    */
    function actionReport(){
        FLEA::loadClass('TMIS_Pager'); 
        $this->authCheck('14-5');
        $serachArea = TMIS_Pager::getParamArray(
            array(
                'dateFrom' => date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-30, date("Y"))),
                'dateTo' => date("Y-m-d"), 
                'jizhi' => '',
                'workCode'=>'',
                'key' => '',
                )
        );
        $sql = "SELECT x.workCode,z.zhijiCode,c.userName1,c.userName2,round(SUM(x.cnt),1) as cntKg,sum(x.memo) as pishu
            FROM shengchan_chanliang x 
            left join shengchan_planpj_fenpi y on x.fenpiId = y.id 
            left join check_main c on c.ExpectCode = y.ExpectCode
            left join jichu_zhiji z on x.jizhiId = z.id
            where 1 AND x.fenpiId>0
            ";
        $sql .= " and x.chanliangDate >= '$serachArea[dateFrom]' and x.chanliangDate<='{$serachArea[dateTo]}'";
        if ($serachArea['key'] != '') $sql .= " and (x.workCode like '%{$serachArea[key]}%' or c.userName1 like 
                                                '%{$serachArea[key]}%'or c.userName2 like 
                                                '%{$serachArea[key]}%')";
        if ($serachArea['jizhi'] != '') $sql .= " and x.jizhiId='{$serachArea[jizhi]}'";

        $sql .= " GROUP BY x.workCode,c.userName1,c.userName2 ORDER BY x.jizhiId";
        $pager = &new TMIS_Pager($sql);
        $rowset = $pager->findAll();
        $rowsetAll=$this->_modelExample->findBySql($sql);
        // 合计行
        $heji = $this->getHeji($rowset, array('cntKg','pishu'), 'workCode');
        $zongji=$this->getHeji($rowsetAll, array('cntKg','pishu'), 'workCode');
        $zongji['workCode']="<b>总计</b>";
        $rowset[] = $heji;
        $rowset[] = $zongji; 

        // 标题栏信息
        $arrFieldInfo = array(
            // 'zhijiCode'=>'机台号',
            "workCode" => '工号',
            'userName1'=>'验布工1',
            'userName2'=>'验布工2',
            "cntKg" => '产量(kg)', 
            'pishu'=>'匹数',
            );
         //得到总计
        $sql = "SELECT round(SUM(x.cnt),1) as cnt
            FROM shengchan_chanliang x 
            left join shengchan_planpj_fenpi y on x.fenpiId = y.id 
            left join check_main c on c.ExpectCode = y.ExpectCode
            left join jichu_zhiji z on x.jizhiId = z.id
            where 1 AND x.fenpiId>0
        and x.chanliangDate >= '$serachArea[dateFrom]' and x.chanliangDate<='{$serachArea[dateTo]}'";
        if ($serachArea['key'] != '') $sql .= " and (x.workCode like '%{$serachArea[key]}%' or c.userName1 like 
                                                '%{$serachArea[key]}%'or c.userName2 like 
                                                '%{$serachArea[key]}%')";
        if ($serachArea['jizhi'] != '') $sql .= " and x.jizhiId='{$serachArea[jizhi]}'";
        //dump($sql);exit;
        $zongji = $this->_modelExample->findBySql($sql);
        $zongji = $zongji[0];
        $smarty = &$this->_getView();
        $smarty->assign('title', '产量查询');
        $smarty->assign('arr_field_info', $arrFieldInfo);
        $smarty->assign('add_display', 'none');
        $smarty->assign('arr_condition', $serachArea);
        $smarty->assign('arr_field_value', $rowset);
        $smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action']), $serachArea)."<font color='red'>产量总计:{$zongji['cnt']}</font>");
        $smarty->assign('arr_js_css', $this->makeArrayJsCss(array('grid', 'calendar')));
        $smarty->display('TableList.tpl');  
    }
    /**
     * ps ：后整发外登记中的码单弹窗
     * Time：2017/08/03 16:26:03
     * @author zcc
    */
    function actionViewMadan(){
        //dump($_GET);die;
        $chanls = explode(',', $_GET['chanliangId']);
        //jghId 为本产量现在所在的加工户厂
        foreach ($chanls as $key => &$value) {
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
            WHERE 1  and x.id = {$value} ";
            $rowset = $this->_modelExample->findBySql($sql);
            $rowsets[] = $rowset[0];
        }
        
        // dump($rowset);exit();
        foreach ($rowsets as $key => &$v) {
            $v['number'] = $key+1;
            $sql = "SELECT c2 as seCode FROM check_main where ExpectCode = '{$v['lot']}' AND kind = 'pibu'";
            $seCode = $this->_modelExample->findBySql($sql);
            $v['seCode'] = $seCode[0]['seCode'];
            if ($_GET['chuku2proId']!='') {//修改时
                //第一步 先找到中间表中所对应之前勾选的数据 进行勾选显示
                $sql = "SELECT * FROM cangku_fawai2product_chanliang where fawaiId = '{$_GET['chuku2proId']}' and chanliangId = '{$v['id']}'";
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
        $row = array('Madan'=>$rowsets); 
        $smarty = & $this->_getView();
        $smarty->assign('title', "入库码单编辑");
        $smarty->assign('madanRows', json_encode($rowsets));
        $smarty->display("Waixie/FwDajuanEdit.tpl");
    }
    /**
     * ps ：根据工序id 和 发出厂来 获取 多个计划信息对应的产量信息
     * Time：2017/07/25 16:37:33
     * @author zcc
    */
    function actionViewPlanMadan(){
        $sql = "SELECT x.id as chanliangId,x.jiagonghuId,x.cnt,x.fenpiId,x.ord2proId,
            y.id as planId,z.id as plan2hzlId,z.gongxuId,y.planCode
            FROM shengchan_chanliang x
            LEFT JOIN shengchan_plan y on x.planId = y.id
            LEFT JOIN shengchan_plan2houzheng z ON y.id = z.planId
            WHERE 1 AND x.fenpiId > 0 AND z.gongxuId = '{$_GET['gongxuId']}' 
            AND  z.jiagonghuId = '{$_GET['jiagonghuId']}'";
        $sql .= "order by x.id asc";    
        $rowset = $this->_modelExample->findBySql($sql);
        // dump($sql);exit();
        $rowsetAll = array();
        foreach ($rowset as &$v) {
            $sql = "SELECT y.checkId as juanhao,y.c1 as sehao
                FROM 
                shengchan_planpj_fenpi x 
                left join check_main y on x.ExpectCode = y.ExpectCode
                where x.id = '{$v['fenpiId']}' and y.kind = 'pibu'";    
            $main = $this->_modelExample->findBySql($sql);
            $v['juanhao'] =  $main[0]['juanhao'];
            $v['sehao'] =  $main[0]['sehao'];
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
            $arr = $this->_modelExample->findBySql($sql);
            // dump($arr);exit();
            $v['proName'] = $arr[0]['proName'];
            $v['proCode'] = $arr[0]['proCode'];  
            $v['guige'] = $arr[0]['guige'];  
            $v['color'] = $arr[0]['color'];  
            $v['productId'] = $arr[0]['productId'];  
            $v['orderCode'] = $arr[0]['orderCode'];  
            $v['orderId'] = $arr[0]['orderId'];      
            if ($_GET['jghFromId']=='26') { //当发出户为沃丰的时候
                $sql = "SELECT * FROM cangku_fawai2product_chanliang where chanliangId = '{$v['chanliangId']}' and jghFromId = '{$_GET['jghFromId']}'"; 
                $a = $this->_modelExample->findBySql($sql);
                if ($a[0]['fawaiId']>0) {//说明已经从沃丰发出，所以不显示
                    $v['isDelete'] = true;
                }else{
                    $rowsetAll[] = $v;
                }
            }else{
                //本次的发出厂 就是上一次 工序的 加工户
                $sql = "SELECT * FROM cangku_fawai2product_chanliang where chanliangId = '{$v['chanliangId']}' and jiagonghuId = '{$_GET['jghFromId']}' ";
                $a = $this->_modelExample->findBySql($sql);

                $sql_1 = "SELECT * FROM cangku_fawai2product_chanliang where chanliangId = '{$v['chanliangId']}' and jghFromId = '{$_GET['jghFromId']}'";
                $b = $this->_modelExample->findBySql($sql_1);
                //发出次数和接送次数 相等情况下 这个加工户就没有库存
                $jishou = count($a);
                $fachu = count($b);
                if ($jishou > $fachu) {
                    $rowsetAll[] = $v;
                }else{
                    $v['isDelete'] = true;
                }
            }            
        }    
        // dump($rowsetAll);exit();
        if ($rowsetAll) {
            echo json_encode($rowsetAll);exit();
        }
    }

    
}   