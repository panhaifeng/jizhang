<?php
/*********************************************************************\
*  Copyright (c) 1998-2013, TH. All Rights Reserved.
*  Author :Jeff
*  FName  :Plan.php
*  Time   :2014/05/13 18:31:40
*  Remark :生产计划控制器
*  change : removeByPkv可能会有删除限制
\*********************************************************************/
FLEA::loadClass('TMIS_Controller');
class Controller_Shengchan_Plan extends Tmis_Controller {
    // /构造函数
    function Controller_Shengchan_Plan() {
        $this->_modelExample = &FLEA::getSingleton('Model_Shengchan_Plan');
        $this->_modeltouliao = &FLEA::getSingleton('Model_Shengchan_Plan2Touliao');
        $this->_modelzhizao = &FLEA::getSingleton('Model_Shengchan_Plan2Zhizao');
        $this->_modelhouzheng = &FLEA::getSingleton('Model_Shengchan_Plan2houzheng');
        $this->_modelpro = &FLEA::getSingleton('Model_Jichu_Product');
        //类型
        $kind = array(
                array('value'=>'加工','text'=>'加工'),
                array('value'=>'双经销','text'=>'双经销'),
                array('value'=>'经销','text'=>'经销'),
        );
        // 定义模板中的主表字段
        $this->fldMain = array(
            'order2proId'=>array(
                    'title' => '订单编号',
                    'type' => 'popup',
                    'value' => '',
                    'name'=>'order2proId',
                    'text'=>'',
                    //只显示已审核的订单
                    'url'=>url('Trade_Order','popup'),//弹出地址,回调函数在sonTpl中进行定义,主要是对缓存中的关联数组进行重写
                    'textFld'=>'orderCode',//显示在text中的字段
                    'hiddenFld'=>'order2proId',//显示在hidden控件中的字段
            ),
            'planCode' => array('title' => '计划单号', "type" => "text", 'readonly' => true, 'value' => $this->_getNewCode('JH','shengchan_plan','planCode')),
            'planDate' => array('title' => '计划日期', 'type' => 'calendar', 'value' => date('Y-m-d')),
            'overflow' => array('title' => '溢短装', 'type' => 'text', 'value' => '', 'addonPre' => '±', 'addonEnd' => '%', 'readonly' => true),
            'cntYaohuo' => array('type' => 'text', "title" => '订单数量', 'name' => 'cntYaohuo', 'readonly' => true),
            'proId' => array(
                'title' => '产品选择',
                'type' => 'popup',
                'value' => '',
                'name'=>'proId',
                'text'=>'选择产品',
                'url'=>url('jichu_Chanpin','Popup'),//弹出地址,回调函数在sonTpl中进行定义,主要是对缓存中的关联数组进行重写
                'textFld'=>'proCode',//显示在text中的字段
                'hiddenFld'=>'proId',//显示在hidden控件中的字段
                ''
            ),
            'proName' => array('type' => 'text', "title" => '品名', 'name' => 'proName', 'readonly' => true),
            'guige' => array('type' => 'text', "title" => '规格', 'name' => 'guige', 'readonly' => true),
            'color' => array('type' => 'text', "title" => '颜色', 'name' => 'color', 'readonly' => true),
            'menfu' => array('type' => 'text', "title" => '门幅', 'name' => 'menfu', 'readonly' => true),
            'kezhong' => array('type' => 'text', "title" => '克重', 'name' => 'kezhong', 'readonly' => true),
            'xianchang' => array('type' => 'text', "title" => '线长', 'name' => 'xianchang',),
            'chengfen' => array('type' => 'text', "title" => '成分', 'name' => 'chengfen',),
            'zhcntKg' => array('type' => 'text', "title" => '坯布数', 'name' => 'zhcntKg',),
            'planMemo'=>array('title'=>'贸易部要求','type'=>'textarea','disabled'=>true,'name'=>'planMemo'),
            // 下面为隐藏字段
            'id' => array('type' => 'hidden', 'value' => '','name'=>'planId'),
            'orderId' => array('type' => 'hidden', 'value' => '','name'=>'orderId'),
            'creater' => array('type' => 'hidden', 'value' => $_SESSION['REALNAME']),
            );
        //纱
        $this->headSon = array(
            '_edit' => array('type' => 'btBtnRemove', "title" => '+5行', 'name' => '_edit[]'),
            'productId'=>array(
                'title' => '色纱品名',
                'type' => 'btpopup',
                'value' => '',
                'name'=>'productId[]',
                'text'=>'色纱品名',
                'url'=>url('Jichu_product','popup'),//弹出地址,回调函数在sonTpl中进行定义,主要是对缓存中的关联数组进行重写
                'textFld'=>'proName',//显示在text中的字段
                'hiddenFld'=>'id',//显示在hidden控件中的字段
                'disabled'=>'true'
            ),
            'bilv' => array('type' => 'bttext', "title" => '比例%', 'name' => 'bilv[]'),
            'cnt' => array('type' => 'bttext', "title" => '计划投料103%', 'name' => 'cnt[]'),
            'pihao' => array('type' => 'bttext', "title" => '批号', 'name' => 'pihao[]'),
            'ganghao' => array('type' => 'bttext', "title" => '缸号', 'name' => 'ganghao[]'),
            'kucun'=>array('type'=>'btBtnKucun',"title"=>'库存','name'=>'kucun[]'),
            'memo' => array('type' => 'bttext', "title" => '纱情况', 'name' => 'memo[]'),
            // ***************如何处理hidden?
            'id' => array('type' => 'bthidden', 'name' => 'id[]'),
        );
        //织造
        $this->zhizaoSon = array(
            '_edit'=>array('type'=>'btBtnRemove',"title"=>'+5行','name'=>'_edit[]'),
            'jiagonghuId' => array('type' => 'btselect', "title" => '加工户', 'name' => 'jiagonghu[]','model'=>'Model_Jichu_Jiagonghu','action'=>'getOptions'),
            'jhcnt'=>array('type'=>'bttext',"title"=>'计划坯布数','name'=>'jhcnt[]'),
// 			'songsha'=>array(
// 				'title' => '计划送纱',
// 				'type' => 'btpopup',
// 				'value' => '',
// 				'name'=>'songsha[]',
// 				'text'=>'',
// 				'url'=>url('Shengchan_Plan','Songsha'),//弹出地址,回调函数在sonTpl中进行定义,主要是对缓存中的关联数组进行重写
// 				'textFld'=>'color',//显示在text中的字段
// 				'hiddenFld'=>'songsha',//显示在hidden控件中的字段
// 				'disabled'=>'true',
// 				'inTable' => true,
// 			),
            'songsha'=>array('type'=>'btBtnSongsha',"title"=>'计划送纱','name'=>'songsha[]'),
// 			'sjcnt'=>array('type'=>'bttext',"title"=>'实际送整坯布','name'=>'jhcnt[]'),
// 			'dblv'=>array('type'=>'bttext',"title"=>'达标率','name'=>'dblv[]'),
            'id'=>array('type'=>'bthidden','name'=>'zhizaoId[]'),
        );
        //后整
        $this->houzhengSon = array(
                '_edit'=>array('type'=>'btBtnRemove',"title"=>'+5行','name'=>'_edit[]'),
                'xuhao' => array('type' => 'bttext', "title" => '序号', 'name' => 'xuhao[]','readonly' => true,'style'=>'width:50px;'),
                'gongxuId'=>array('type'=>'btselect',"title"=>'工序','name'=>'gongxuId[]','model'=>'Model_Jichu_Gongxu','action'=>'getOptions'),
                'jiagonghuId'=>array(
                    'title' => '加工户',
                    'type' => 'btpopup',
                    'value' => '',
                    'name'=>'jiagonghuId[]',
                    'text'=>'',
                    'url'=>url('Jichu_Jiagonghu','popup'),//弹出地址,回调函数在sonTpl中进行定义,主要是对缓存中的关联数组进行重写
                    'textFld'=>'compName',//显示在text中的字段
                    'hiddenFld'=>'jiagonghuId',//显示在hidden控件中的字段
                    'disabled'=>'true',
                    'inTable' => true,
                ),
                'id'=>array('type'=>'bthidden','name'=>'houzhengId[]'),
        );
        // 表单元素的验证规则定义
        $this->rules = array(
            'ord2proId' => 'required',
        );

        $this->fldRight = array(
            "planDate" => "日期",
            'planCode' => '计划单号',
            "planDate" => "开单日期",
            "overDate" => "计划完成时间",
            'overDateReal' => '实际完成时间',
            "planMemo" => '生产备注',
            "proCode" => '产品编号',
            "proName" => '品名',
            "guige" => array("text"=>'规格','width'=>150),
            "cntShengchan" => '计划生产数量',
        );
    }

    function actionRight() {
        //权限判断
        $this->authCheck('2-5');
        FLEA::loadClass('TMIS_Pager');
        TMIS_Pager::clearCondition();
        $arr = TMIS_Pager::getParamArray(array(
            'dateFrom' => date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-30, date("Y"))),
            'dateTo' => date("Y-m-d"),
            'orderCode'=>'',
            'planCode' => '',
            'key'=>''
        ));
        //sql语句，查找信息
        $sql="select x.*,
                     o.orderCode,o.overflow,
                     y.cntYaohuo,y.menfu,y.kezhong,
                     p.menfu as productMenfu,p.kezhong as productKezhong,p.proCode,p.proName,p.guige,p.color
              from shengchan_plan x
                left join trade_order2product y on x.order2proId=y.id
                left join trade_order o on o.id=y.orderId
                left join jichu_product p on p.id=x.productId
              where 1";
        $sql .= " and planDate >= '$arr[dateFrom]' and planDate<='$arr[dateTo]'";
        if($arr['orderCode']!='') $sql.=" and o.orderCode like '%{$arr['orderCode']}%'";
        if($arr['planCode']!='') $sql.=" and x.planCode like '%{$arr['planCode']}%'";
        $sql.=" order by x.planCode desc";

        $pager = &new TMIS_Pager($sql);
        $rowset = $pager->findAll();
        if (count($rowset) > 0) foreach($rowset as &$v) {
            $v['_edit'] = $this->getEditHtml($v['id']) .' ' .$this->getRemoveHtml($v['id']);
            //显示明细数据
            $v['Touliaos']=$this->_modeltouliao->findAll(array('planId'=>$v['id']));
            foreach($v['Touliaos'] as & $vv){
                //查询产品信息
                $sql="select * from jichu_product where id='{$vv['productId']}'";
                $temp=$this->_modeltouliao->findBySql($sql);
                $vv['proCode']=$temp[0]['proCode'];
                $vv['proName']=$temp[0]['proName'];
            }
        }
        $smarty = &$this->_getView();
        // 左侧信息
        $arrFieldInfo = array(
            "_edit" => array('text'=>'操作','width'=>120),
            "planDate" => "日期",
            "orderCode"=>"订单号",
            'planCode' => '计划单号',
            'overflow' =>'溢短装',
            "proCode" => '产品编号',
            'cntYaohuo' =>'订单数量',
            'proName' =>'品名',
            'guige' =>'规格',
            'color' =>'颜色',
            'menfu' =>'门幅',
            'kezhong' =>'克重',
            'xianchang' =>'线长',
            'chengfen' =>'成分',
            'planMemo'=>'贸易部要求',
        );

        $arrField=array(
            "proCode" => '产品编号',
            "proName" => '纱支名称',
            'menfu' => '门幅',
            'kezhong' => '克重',
            "bilv" => '比率',
            "cnt" => '计划投料103%',
            "pihao" => '批号',
            "ganghao" => '缸号',
            "memo" => '备注',
        );
// 		dump($rowset);exit;
        $smarty->assign('title', '计划查询');
        $smarty->assign('arr_field_info', $arrFieldInfo);
        $smarty->assign('arr_field_info2', $arrField);
        $smarty->assign('sub_field', 'Touliaos');
        $smarty->assign('add_display', 'none');
        $smarty->assign('arr_condition', $arr);
        $smarty->assign('arr_field_value', $rowset);
        $smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action']), $arr));
        $smarty->display('TblListMore.tpl');
    }


    function actionAdd() {
        $this->authCheck('2-4');
        // 从表区域信息描述
        $smarty = &$this->_getView();
        $areaMain = array('title' => '生产计划基本信息', 'fld' => $this->fldMain);
        $smarty->assign('areaMain', $areaMain);
        // 从表信息字段,默认5行
        for($i=0;$i<5;$i++){
            $rowsSon[]=array();
        }

        /*
        * 投料计划设置、工序信息编辑，默认5行
        */
        for($i=0;$i<5;$i++){
            $zhizaoSon[]=array();
        }
        for($i=1;$i<6;$i++) {
            $temp['xuhao']=array('value'=>$i);
            $houzhengSon[] =$temp;
        }
        // dump($houzhengSon);exit;
        $smarty->assign('headSon', $this->headSon);
        $smarty->assign('rowsSon', $rowsSon);
        $smarty->assign('sonTitle', "投料计划编辑");
        $smarty->assign('zhizaoSon',$this->zhizaoSon);
        $smarty->assign('rowzSon',$zhizaoSon);
        $smarty->assign('rules', $this->rules);
        $smarty->assign('rowghSon',$houzhengSon);
        $smarty->assign('houzhengSon',$this->houzhengSon);
        $smarty->assign('sonTpl', 'Shengchan/Plan/jsPlanEdit.tpl');
        $smarty->assign("otherInfoTpl",array(
                'Shengchan/Plan/zhizaoInfo.tpl',
                'Shengchan/Plan/houzhengInfoTpl.tpl',
            ));
        $smarty->display('Main2Son/T2.tpl');
    }

    function actionEdit() {
        //计划信息
        $arr = $this->_modelExample->find(array('id' => $_GET['id']));
// 		dump($arr);exit;
        /*
        * 主信息
        */
        foreach ($this->fldMain as $k => &$v) {
            $v['value'] = $arr[$k]?$arr[$k]:$v['value'];
        }
        $sql="select x.*,
                     y.id as order2proId,y.cntYaohuo,
                     y.menfu,y.kezhong,
                     o.id as orderId,o.orderCode,o.overflow,
                     p.id as proId,p.menfu as productMenfu,p.kezhong as productKezhong,p.proCode,p.proName,p.guige,p.color 
                from shengchan_plan x
                left join trade_order2product y on x.order2proId=y.id
                left join trade_order o on o.id=y.orderId
                left join jichu_product p on p.id=x.productId
                where 1 and x.id='{$arr['id']}'";
        $ret=$this->_modelExample->findBySql($sql);
        //设置rukuId的值
        $this->fldMain['id']['value'] = $arr['id'];
        //设置订单编号显示
        $this->fldMain['orderId']['value'] = $ret[0]['orderId'];
        $this->fldMain['order2proId']['value'] = $ret[0]['order2proId'];
        $this->fldMain['order2proId']['text'] = $ret[0]['orderCode'];
        $this->fldMain['overflow']['value'] = $ret[0]['overflow'];
        $this->fldMain['cntYaohuo']['value'] = $ret[0]['cntYaohuo'];
        $this->fldMain['proId']['text'] = $ret[0]['proCode'];
        $this->fldMain['proName']['value'] = $ret[0]['proName'];
        $this->fldMain['guige']['value'] = $ret[0]['guige'];
        $this->fldMain['color']['value'] = $ret[0]['color'];
        $this->fldMain['menfu']['value'] = $ret[0]['menfu'];
        $this->fldMain['kezhong']['value'] = $ret[0]['kezhong'];
        $this->fldMain['proId']['value'] = $arr['productId'];

        // //加载库位信息的值
        $areaMain = array('title' => '入库基本信息', 'fld' => $this->fldMain);

        /*
        * 投料明细处理
        */
        $ret='';
        $rowsSon = array();
        foreach($arr['Touliaos'] as &$v) {
            $temp = array();
            foreach($this->headSon as $kk => &$vv) {
                $temp[$kk] = array('value' => $v[$kk]);
            }
            $ret=$this->_modelpro->find(array('id'=>$v['productId']));
            $temp['productId']=array('value'=>$v['productId'],'text'=>$ret['proName']);
            $rowsSon[] = $temp;
        }

        //补齐5行
        $cnt = count($rowsSon);
        for($i=5;$i>$cnt;$i--) {
            $rowsSon[] = array();
        }

        /*
        * 织造明细处理
        */
        $zhizaoSon=array();
        foreach($arr['Zhizaos'] as & $v) {
            $temp = array();
            foreach($this->zhizaoSon as $kk => &$vv) {
                $temp[$kk] = array('value' => $v[$kk]);
            }
            $sql="select x.*,y.id as productId,y.proCode from shengchan_plan2zhizao_sha x
                    left join jichu_product y on x.productId=y.id where x.zhizaoId='{$v['id']}'";
            $ret=$this->_modelExample->findBySql($sql);
// 			dump($ret);exit;
            $temp['songsha']['value']=json_encode($ret);
            $zhizaoSon[] = $temp;
        }

        //补齐5行
        $cnt = count($zhizaoSon);
        for($i=5;$i>$cnt;$i--) {
            $zhizaoSon[] = array();
        }

        /*
        * 工序明细处理
        */
        $sqlhx="select xuhao from shengchan_plan2houzheng where planId='{$arr['id']}' group by xuhao";
        $arr['houhengs']=$this->_modelExample->findBySql($sqlhx);
        $houzhengSon=array();
        foreach($arr['houhengs'] as &$v) {
            $jia=array();
            $temp=array();
            $sqlxh="select x.*,y.compName from shengchan_plan2houzheng x
                    left join jichu_jiagonghu y on x.jiagonghuId=y.id
                    where x.planId='{$arr['id']}' and x.xuhao='{$v['xuhao']}'";
            $retxh=$this->_modelExample->findBySql($sqlxh);
            foreach ($retxh as $i=>&$r){
                if($i==0) {$jia['gxid']=$r['id'];$jia['id']=$r['jiagonghuId'];$jia['name']=$r['compName'];}
                else {$jia['gxid'].=','.$r['id'];$jia['id'].=','.$r['jiagonghuId'];$jia['name'].=','.$r['compName'];}
            }
            $temp['id']=array('value'=>$jia['gxid']);
            $temp['xuhao']=array('value'=>$retxh[0]['xuhao']);
            $temp['gongxuId']=array('value'=>$retxh[0]['gongxuId']);
            $temp['jiagonghuId']=array('value'=>$jia['id'],'text'=>$jia['name']);
            $houzhengSon[] = $temp;
        }

        //补齐5行
        $cnt = count($houzhengSon);
        for($i=$cnt+1;$i<6;$i++) {
            $temp=array();
            $temp['xuhao']=array('value'=>$i);
            $houzhengSon[] = $temp;
        }
        $smarty = &$this->_getView();
        $smarty->assign('areaMain', $areaMain);
        $smarty->assign('headSon', $this->headSon);
        $smarty->assign('rowsSon', $rowsSon);
        $smarty->assign('sonTitle', "投料计划编辑");
        $smarty->assign('zhizaoSon',$this->zhizaoSon);
        $smarty->assign('rowzSon',$zhizaoSon);
        $smarty->assign('rules', $this->rules);
        $smarty->assign('rowghSon',$houzhengSon);
        $smarty->assign('houzhengSon',$this->houzhengSon);
        $smarty->assign('sonTpl', 'Shengchan/Plan/jsPlanEdit.tpl');
        $smarty->assign("otherInfoTpl",array(
                'Shengchan/Plan/zhizaoInfo.tpl',
                'Shengchan/Plan/houzhengInfoTpl.tpl',
            ));
        $smarty->display('Main2Son/T2.tpl');
    }

    function actionSave(){
        // dump($_POST);exit;
        /*
        * 投料信息，计划产品明细
        */
        $proTl = array();
        foreach($_POST['productId'] as $key=>&$v) {
            if($v=='' || empty($_POST['productId'][$key])) continue;
            $temp = array();
            foreach($this->headSon as $k=>&$vv) {
                $name = $vv['name']?substr($vv['name'],0, -2):$k;
                $temp[$k] = $_POST[$name][$key];
            }
            $proTl[]=$temp;
        }
        //没有明细数据提示需要明细信息，不给予保存
        if(count($proTl)==0) {
            js_alert('请选择投料信息并填写有效投料数量!','window.history.go(-1)');
            exit;
        }

        /*
        * 工序
        */
        $proGx = array();
        foreach($_POST['gongxuId'] as $key=>&$v) {
            if($v=='') continue;
            $jiagonghu=explode(',', $_POST['jiagonghuId'][$key]);
            $gxId=explode(',', $_POST['houzhengId'][$key]);
            foreach ($jiagonghu as $i=>& $r){
                $temp=array(
                    'id'=>$gxId[$i],
                    'xuhao'=>$_POST['xuhao'][$key],
                    'gongxuId'=>$_POST['gongxuId'][$key],
                    'jiagonghuId'=>$jiagonghu[$i],
                );
                $proGx[]=$temp;
            }
        }
        /*
        * 主表信息
        */
        $row = array();
        foreach($this->fldMain as $k=>&$vv) {
            $name = $vv['name']?$vv['name']:$k;
            $row[$k] = $_POST[$name];
            $row['productId'] = $_POST['proId'];
        }
        /*
        * 添加关联关系
        */
        $row['Touliaos'] = $proTl;
        $row['houhengs'] = $proGx;

        // 保存
        $id = $this->_modelExample->save($row);
        /*
         * 保织造信息
        */
        $proZz = array();
        $cnt1 = count($_POST['jhcnt']);
        foreach($_POST['jhcnt'] as $key=>&$v){
            if($v=='') continue;
            $temp = array();
            foreach($this->zhizaoSon as $k=>&$vv) {
                $name = $vv['name']?substr($vv['name'],0, -2):$k;
                $temp[$k] = $_POST[$name][$key];
            }
            $sha=json_decode($_POST['songsha'][$key],true);
// 			dump($sha);exit;
            $temp['Shas']=$sha;
            $temp['planId'] = $_POST['planId']>0?$_POST['planId']:$id;
            $proZz[]=$temp;
        }

        $temp = $this->_modelzhizao->saveRowset($proZz);

// 		dump($row);exit;
        if(!$id) {
            js_alert('保存失败','window.history.go(-1)');
            exit;
        }

        //跳转
        js_alert(null,'window.parent.showMsg("保存成功!")',url('Shengchan_plan','right'));
        exit;
    }
    /**
     * 删除织造
     * Time：2015/11/24 15:19:15
     * @author jiang
     */
    function actionRemove2ZhizaoAjax(){
        $id=$_POST['id'];
        $r = $this->_modelzhizao->removeByPkv($id);
        if(!$r) {
            // js_alert('删除失败');
            echo json_encode(array('success'=>false,'msg'=>'删除失败'));
            exit;
        }
        echo json_encode(array('success'=>true));
    }
    function actionRemove2HouzhengAjax(){
        $id=$_POST['id'];
        $r = $this->_modelhouzheng->removeByPkv($id);
        if(!$r) {
            // js_alert('删除失败');
            echo json_encode(array('success'=>false,'msg'=>'删除失败'));
            exit;
        }
        echo json_encode(array('success'=>true));
    }
    function actionRemoveByAjax(){
        $id=$_POST['id'];
        $r = $this->_modeltouliao->removeByPkv($id);
        if(!$r) {
            // js_alert('删除失败');
            echo json_encode(array('success'=>false,'msg'=>'删除失败'));
            exit;
        }
        echo json_encode(array('success'=>true));
    }
    function actionSongsha() {
        $smarty = &$this->_getView();
        $smarty->display('Shengchan/Plan/SongshaEdit.tpl');
    }
    function actionPopupGx() {
        // dump($_GET);exit;
        FLEA::loadClass('TMIS_Pager');
        $arr = TMIS_Pager::getParamArray(
                array(
                    // 'dateFrom' => date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-30, date("Y"))),
                    // 'dateTo' => date("Y-m-d"),
                    'jiagonghuId' => '',
                    'orderCode' => '',
                    'planCode' => '',
                    'gongxuId' => '',
                    'key'=>''
                ));
        /*$str = "select x.*,y.planCode,o.orderDate,o.orderCode,g.name,j.compName,p.proCode,p.proName,p.guige,p.color ,y.productId,y.orderId,y.order2proId
                from shengchan_plan2houzheng x
                left join shengchan_plan y on x.planId=y.id
                left join trade_order2product op on op.id=y.order2proId
                left join trade_order o on o.id=op.orderId
                left join jichu_gongxu g on g.id=x.gongxuId
                left join jichu_jiagonghu j on j.id=x.jiagonghuId
                left join jichu_product p on p.id=op.productId
                 where 1";*/

        //新的SQL ，by zhujunjie
        $str = "select x.*,y.planCode,y.productId,y.orderId,y.order2proId,
                    o.orderDate,o.orderCode,g.name,j.compName,
                    p.proCode,p.proName,p.guige,p.color
                    from shengchan_plan2houzheng x
                    left join shengchan_plan y on x.planId=y.id
                    left join trade_order o on o.id=y.orderId
                    left join jichu_gongxu g on g.id=x.gongxuId
                    left join jichu_jiagonghu j on j.id=x.jiagonghuId
                    left join jichu_product p on p.id=y.productId
                    where 1 and o.orderCode <> ''";

        // $str .= " and orderDate >= '$arr[dateFrom]' and orderDate<='$arr[dateTo]'";
        if($arr['orderCode']!='') $str.=" and o.orderCode like '%{$arr['orderCode']}%'";
        if($arr['planCode']!='') $str.=" and y.planCode like '%{$arr['planCode']}%'";
        if($arr['jiagonghuId']!='') $str.=" and x.jiagonghuId = '{$arr['jiagonghuId']}'";
        if($arr['gongxuId']!='') $str.=" and x.gongxuId = '{$arr['gongxuId']}'";
        if ($arr['key'] != '') $str .= " and (p.proCode like '%$arr[key]%'
                        or g.name like '%$arr[key]%'
                        or p.proName like '%$arr[key]%'
                        or p.guige like '%$arr[key]%'
                        or p.color like '%$arr[key]%'
                        or j.compName like '%$arr[key]%')";
        $str .= " order by orderCode desc,planCode desc,xuhao desc";
        $pager = &new TMIS_Pager($str);
        $rowset = $pager->findAllBySql($str);
        //dump($rowset);exit;
        foreach($rowset as &$v) {
            //查找发外数量
            $sql="select sum(cnt) as cnt from cangku_fawai2product where plan2hzlId='{$v['id']}'";
            $temp = $this->_modelhouzheng->findBySql($sql);
            $v['cntYf'] = $temp[0]['cnt'];
            //查找该订单要货总量
            $trade_order2product = &FLEA::getSingleton('Model_Trade_Order2Product');
            $sql_yaohuo="select cntYaohuo from trade_order2product where id='{$v['order2proId']}'";
            $yaohuo = $trade_order2product->findBySql($sql_yaohuo);
            $v['cntYaohuo'] = $yaohuo[0]['cntYaohuo'];
        }
        // dump($rowset);exit;
        $smarty = &$this->_getView();
        $smarty->assign('title', '选择工序');
        $pk = $this->_modelExample->primaryKey;
        $smarty->assign('pk', $pk);
        $arr_field_info = array(
            "orderCode"=>"订单号",
            'orderDate' => '订单日期',
            "proCode"=>"产品编号",
            'proName' => '产品名称',
            'guige' => '规格',
            'color' => '颜色',
            'name' => '工序',
            'compName' => '加工户',
            'cntYaohuo' => '订单数量',
            'cntYf' => '已发数量',
            'planCode' => '计划单号',
            'planDate' => '计划日期',
        );
        $smarty->assign('arr_field_info', $arr_field_info);
        $smarty->assign('arr_field_value', $rowset);
        $smarty->assign('add_display', 'none');
        $smarty->assign('arr_condition', $arr);
        $smarty->assign('page_info', $pager->getNavBar($this->_url('PopupGx', $arr)));
        $smarty->display('Popup/CommonNew.tpl');
    }
    function actionPopupTl() {
        // dump($_GET);exit;
        FLEA::loadClass('TMIS_Pager');
        $arr = TMIS_Pager::getParamArray(
                array(
                    'dateFrom' => date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-30, date("Y"))),
                    'dateTo' => date("Y-m-d"),
                    'orderCode' => '',
                    'planCode' => '',
                    'key'=>'',
                    'isLingliao'=>0
                ));
        $str = "select x.*,y.id as planId,y.planCode,y.planDate,o.orderDate,o.orderCode,p.proCode,p.proName,p.guige,p.color from shengchan_plan2touliao x
                left join shengchan_plan y on x.planId=y.id
                left join trade_order2product op on op.id=y.order2proId
                left join trade_order o on o.id=op.orderId
                left join jichu_product p on p.id=x.productId
                 where 1";
        $str .= " and orderDate >= '$arr[dateFrom]' and orderDate<='$arr[dateTo]'";
        if($arr['orderCode']!='') $str.=" and o.orderCode like '%{$arr['orderCode']}%'";
        if($arr['planCode']!='') $str.=" and x.planCode like '%{$arr['planCode']}%'";
        if ($arr['key'] != '') $str .= " and (p.proCode like '%$arr[key]%'
        or p.proName like '%$arr[key]%'
        or g.guige like '%$arr[key]%'
        or g.color like '%$arr[key]%')";
        if($arr['isLingliao']>0){
            $str .= " and exists(select * from cangku_common_chuku2product where plan2touliaoId=x.id)";
        }else{
            $str .= " and not exists(select * from cangku_common_chuku2product where plan2touliaoId=x.id)";
        }
        $str .= " order by orderCode asc,planCode asc,proName asc";
        $pager = &new TMIS_Pager($str);
        // dump($str);exit;
        $rowset = $pager->findAllBySql($str);
        // dump($str);exit;
        if (count($rowset) > 0) foreach($rowset as $k=> &$v) {
            $v['_edit']="<input type='checkbox' name='ck[]' id='ck[]' value='{$k}'>";
            // 获得已领料数量
            if($arr['isLingliao']>0){
                $sql = "SELECT SUM(cnt) AS cntLingliao FROM cangku_common_chuku2product WHERE plan2touliaoId={$v['id']}";
                $temp = $this->_modelExample->findBySql($sql);
                $v['cntLingliao'] = $temp[0]['cntLingliao'];
            }
        }
        $smarty = &$this->_getView();
        $smarty->assign('title', '选择工序');
        $pk = $this->_modelExample->primaryKey;
        $smarty->assign('pk', $pk);
        $arr_field_info = array(
                "_edit"=>array('text'=>"<input type='checkbox' id='ckAll'/>选择",'width'=>60),
                "orderCode"=> "订单号",
                'orderDate' => array('text'=>'订单日期','width'=>80),
                'planCode' => array('text'=>'计划单号','width'=>90),
                'planDate' => array('text'=>'计划日期','width'=>80),
                "proCode"=> array('text'=>"产品编号",'width'=>70),
                'proName' => '产品名称',
                'guige' => '规格',
                'color' => array('text'=>'颜色','width'=>60),
                'bilv' => array('text'=>'比率','width'=>60),
                'pihao' => '批号',
                'ganghao' => '缸号',
                'cnt' => '计划投料103%',
        );
        if($arr['isLingliao']>0){
            $arr_field_info['cntLingliao'] = '已领料';
        }
        $other_url="<input type='button' id='choose' name='choose' value='确定'/>";
        $smarty->assign('other_url', $other_url);
        $smarty->assign('arr_field_info', $arr_field_info);
        $smarty->assign('arr_field_value', $rowset);
        $smarty->assign('add_display', 'none');
        $smarty->assign('arr_js_css', $this->makeArrayJsCss(array('thickbox')));
        $smarty->assign('arr_condition', $arr);
        $smarty->assign('page_info', $pager->getNavBar($this->_url('PopupTl', $arr)));
        $smarty->assign('sonTpl', "Popup/FrameInit.tpl");
        $smarty->display('Popup/CommonNew.tpl');
    }
    function actionPopupZz() {
        // dump($_GET);exit;
        FLEA::loadClass('TMIS_Pager');
        $arr = TMIS_Pager::getParamArray(
            array(
                'dateFrom' => date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-30, date("Y"))),
                'dateTo' => date("Y-m-d"),
                'orderCode' => '',
                'planCode' => '',
                'key'=>''
        ));
        $str = "select x.*,y.planCode,y.planDate,op.id as ord2proId,o.id as orderId,o.orderDate,o.orderCode,p.id as productId,p.proCode,p.proName,p.guige,p.color,p.menfu,p.kezhong,j.compName
                from trade_order2product op
                left join shengchan_plan y on op.id=y.order2proId
                left join shengchan_plan2zhizao x on x.planId=y.id
                left join trade_order o on o.id=op.orderId
                left join jichu_product p on p.id=op.productId
                left join jichu_jiagonghu j on j.id=x.jiagonghuId
                 where 1";
        $str .= " and orderDate >= '$arr[dateFrom]' and orderDate<='$arr[dateTo]'";
        if($arr['orderCode']!='') $str.=" and o.orderCode like '%{$arr['orderCode']}%'";
        if($arr['planCode']!='') $str.=" and y.planCode like '%{$arr['planCode']}%'";
        if ($arr['key'] != '') $str .= " and (p.proCode like '%$arr[key]%'
        or p.proName like '%$arr[key]%'
        or p.guige like '%$arr[key]%'
        or p.color like '%$arr[key]%')";
        $str .= " order by orderCode asc,planCode asc,proName asc";
// 		dump($arr);exit;
        $pager = &new TMIS_Pager($str);
        $rowset = $pager->findAllBySql($str);
        if (count($rowset) > 0) foreach($rowset as &$v) {
            //查找产量数量
            $sql="select sum(cnt) as cnt from shengchan_chanliang where zhizao2planId='{$v['id']}'";
            $temp = $this->_modelzhizao->findBySql($sql);
            $v['cntYf'] = $temp[0]['cnt'];
        }
        $smarty = &$this->_getView();
        $smarty->assign('title', '选择工序');
        $pk = $this->_modelExample->primaryKey;
        $smarty->assign('pk', $pk);
        $arr_field_info = array(
                "orderCode"=> "订单号",
                'orderDate' => '订单日期',
                'planCode' => '计划单号',
                'planDate' => '计划日期',
                "proCode"=> "产品编号",
                'proName' => '产品名称',
                'guige' => '规格',
                'color' => '颜色',
                "compName"=> "加工户",
                'jhcnt' => '计划匹数',
                'cntYf' => '已生产数量',
        );
        $smarty->assign('arr_field_info', $arr_field_info);
        $smarty->assign('arr_field_value', $rowset);
        $smarty->assign('add_display', 'none');
        $smarty->assign('arr_js_css', $this->makeArrayJsCss(array('thickbox')));
        $smarty->assign('arr_condition', $arr);
        $smarty->assign('page_info', $pager->getNavBar($this->_url('PopupZz', $arr)));
        $smarty->display('Popup/CommonNew.tpl');
    }

    /**
     * 生产计划投料明细
     * 实际投料数量
     * Time：2015/04/14 10:30:23
     * @author li
    */
    function actionPopTousha(){
// 		$this->authCheck('');
        FLEA::loadClass('TMIS_Pager');
        $arr = TMIS_Pager::getParamArray(array(
            'planId' => '',
            // 'orderId' => '',
            // 'ord2proId' => '',
        ));

        //查询界面需要显示的信息，
        $rowset=array();

        //搜索条件
        $where = '';
        if($arr['planId']>0){
            $where.=" and planId='{$arr['planId']}'";
        }
        /*if($arr['orderId']>0){
            $where.=" and orderId='{$arr['orderId']}'";
        }*/
        // if($arr['ord2proId']>0){
        // 	$where.=" and ord2proId='{$arr['ord2proId']}'";
        // }

        //生产计划
        $sql="select * from shengchan_plan2touliao where 1 {$where}";
        $rows = $this->_modelExample->findBysql($sql);
        foreach ($rows as $key => & $v) {
            $k = $v['id'].','.$v['planId'].','.$v['productId'].','.$v['ganghao'];

            //查找品名规格
            $sql="select * from jichu_product where id='{$v['productId']}'";
            $temp = $this->_modelExample->findBysql($sql);
            // $v['proCode'] = $temp[0]['proCode'];
            $v['proName'] = $temp[0]['proName'];
            $v['guige'] = $temp[0]['guige'];

            $rowset[$k]['productId']=$v['productId'];
            $rowset[$k]['ganghao']=$v['ganghao'];
            $rowset[$k]['proName']=$v['proName'];
            $rowset[$k]['guige']=$v['guige'];
            $rowset[$k]['planId']=$v['planId'];
            $rowset[$k]['plan2touliaoId']=$v['id'];
            $v['bilv']>0 && $rowset[$k]['bilv']=round($v['bilv'],2).'%';
            $rowset[$k]['cntPlan']+=$v['cnt'];
        }

        //实际领料
        $sql="select * from cangku_common_chuku2product where 1 {$where}";
        $rows = $this->_modelExample->findBysql($sql);

        foreach($rows as & $v) {
            $k = $v['plan2touliaoId'].','.$v['planId'].','.$v['productId'].','.$v['pihao'];

            //查找品名规格
            $sql="select * from jichu_product where id='{$v['productId']}'";
            $temp = $this->_modelExample->findBysql($sql);
            // $v['proCode'] = $temp[0]['proCode'];
            $v['proName'] = $temp[0]['proName'];
            $v['guige'] = $temp[0]['guige'];

            $rowset[$k]['productId']=$v['productId'];
            $rowset[$k]['proName']=$v['proName'];
            $rowset[$k]['guige']=$v['guige'];
            $rowset[$k]['ganghao']=$v['pihao'];
            $rowset[$k]['planId']=$v['planId'];
            $rowset[$k]['plan2touliaoId']=$v['plan2touliaoId'];
            $v['bilv']>0 && $rowset[$k]['bilv']=round($v['bilv'],2).'%';
            $rowset[$k]['cnt']+=$v['cnt'];
        }
        // dump($rowset);exit;
        $rowset[]=$this->getHeji($rowset,array('cnt','cntPlan'),'proName');

        $smarty = &$this->_getView();
        // 左侧信息
        $arrFieldInfo = array(
            'proName'=>array('text'=>'品名','width'=>'100'),
            'guige'=>array('text'=>'规格','width'=>'120'),
            'bilv'=>array('text'=>'比率','width'=>'100'),
            'ganghao'=>array('text'=>'批号','width'=>'100'),
            'cntPlan'=>array('text'=>'计划用纱103%','width'=>'100'),
            'cnt'=>array('text'=>'实际领用','width'=>'100'),
        );

        $smarty->assign('title', '订单跟踪报表');
        $smarty->assign('arr_field_info', $arrFieldInfo);
        $smarty->assign('add_display', 'none');
        $smarty->assign('arr_condition', $arr);
        $smarty->assign('arr_field_value', $rowset);
        $smarty->display('TblList.tpl');
    }

    /**
     * 生产计划织造数量明细
     * 实际织造数量
     * Time：2015/04/14 10:30:23
     * @author li
    */
    function actionPopPibu(){
        FLEA::loadClass('TMIS_Pager');
        $arr = TMIS_Pager::getParamArray(array(
            'planId' => '',
            // 'orderId' => '',
            'ord2proId' => '',
        ));

        //查询界面需要显示的信息，
        $rowset=array();

        //搜索条件
        $where = '';
        if($arr['ord2proId']>0){
            $ord2pro.=" and ord2proId='{$arr['ord2proId']}'";
        }
        if($arr['planId']>0){
            $where.=" and planId='{$arr['planId']}'";
        }

        //生产计划
        $sql="select * from shengchan_plan2zhizao where 1 {$where}";
        $rows = $this->_modelExample->findBysql($sql);
        foreach ($rows as $key => & $v) {
            $k = $v['id'].','.$v['jiagonghuId'];

            //查找品名规格
            $sql="select * from jichu_jiagonghu where id='{$v['jiagonghuId']}'";
            $temp = $this->_modelExample->findBysql($sql);
            $v['compName'] = $temp[0]['compName'];

            $rowset[$k]['compName']=$v['compName'];
            $rowset[$k]['cntPlan']+=$v['jhcnt'];
        }

        //实际领料
        $sql="select * from shengchan_chanliang where 1 {$ord2pro}";
        $rows = $this->_modelExample->findBysql($sql);
// 		dump($sql);exit;
        foreach($rows as & $v) {
            $k = $v['zhizao2planId'].','.$v['jiagonghuId'];

            //查找品名规格
            $sql="select * from jichu_jiagonghu where id='{$v['jiagonghuId']}'";
            $temp = $this->_modelExample->findBysql($sql);
            $v['compName'] = $temp[0]['compName'];
            $v['cnt'] = round($v['cnt'],2);

            $rowset[$k]['Son'][]=array(
                'chanliangDate'=>$v['chanliangDate'],
                'cnt'=>$v['cnt'],
            );
            $rowset[$k]['compName']=$v['compName'];
            $rowset[$k]['cnt']+=$v['cnt'];
        }
        // dump($rowset);exit;

        //开始输出
        foreach ($rowset as $key => & $v) {
            echo "<br><h2>{$v['compName']}计划坯布{$v['cntPlan']}，实际产量{$v['cnt']}</h2>";
            foreach ($v['Son'] as $key => & $s) {
                if($key>0)echo "<br>";
                echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",$s['chanliangDate'],'，产量',$s['cnt'];
            }
        }
    }

    /**
     * 工序跟踪明细
     * Time：2015/04/14 10:30:23
     * @author li
    */
    function actionPopHzl(){

        //如果工序是最后一个，直接输出
        if($_GET['last']==1){
            exit("详细信息就是成品入库信息，请查看成品入库信息");
        }
        FLEA::loadClass('TMIS_Pager');
        $arr = TMIS_Pager::getParamArray(array(
            'planId' => '',
            'gongxuId' => '',
            'xuhao' => '',
            'plan2hzlId' => '',
        ));

        //查询界面需要显示的信息，
        $rowset=array();

        //搜索条件
        $where = '';
        if($arr['plan2hzlId']!=''){
            $where.=" and x.plan2hzlId in({$arr['plan2hzlId']})";
        }
        // dump($arr);exit;
        //生产计划
        $sql="select x.*,y.rukuDate,z.compName,j.compName as jghName,g.name
            from cangku_fawai2product x
            left join cangku_fawai y on y.id=x.fawaiId
            left join jichu_jiagonghu z on z.id=y.jghFromId
            left join jichu_jiagonghu j on j.id=y.jiagonghuId
            left join jichu_gongxu g on g.id=x.gongxuId
            where 1 {$where}";
        $rows = $this->_modelExample->findBysql($sql);

// 		dump($sql);exit;

        //开始输出
        foreach ($rows as $key => & $v) {
            echo "<br>",$v['rukuDate'].'，'.$v['compName'].'加工'.round($v['cnt'],2).$v['unit'].'，'.'发到'.$v['jghName'],"<br>";
        }
    }
// 	function actionKucun(){
// // 		dump($_GET);exit;
// 		//sql语句，查找信息
// 		$sql="select proName,pihao,cnt,cnttl from (
// 			select p.proName,x.pihao,sum(cnt) as cnt,0 as cnttl from cangku_common_ruku2product x
// 			left join cangku_common_chuku y on x.rukuId=y.id
// 			left join jichu_product p on p.id=x.productId
// 			where x.productId='{$_GET['productId']}' and x.pihao='{$_GET['ganghao']}'
// 			GROUP BY p.proName,x.pihao
// 			union
// 			select p.proName,x.ganghao as pihao,cnt,sum(cnt) as cnttl from shengchan_plan2touliao x
// 			left join jichu_product p on p.id=x.productId
// 			where x.productId='{$_GET['productId']}' and x.ganghao='{$_GET['ganghao']}'
// 			GROUP BY p.proName,x.ganghao
// 			)as a where 1 and proName!='' GROUP BY proName,pihao";
// 		$rowset=$this->_modelExample->findBySql($sql);
// // 		dump($sql);exit;
// 		if (count($rowset) > 0) foreach($rowset as &$v) {
// 		}
// 		$smarty = &$this->_getView();
// 		// 左侧信息
// 		$arrFieldInfo = array(
// 				"pihao" => "批号/缸号",
// 				'proName' =>'品名',
// 				'cnt' =>'总入库',
// 				'cnttl' =>'已计划数',
// 		);
// 		$smarty->assign('title', '计划查询');
// 		$smarty->assign('arr_field_info', $arrFieldInfo);
// 		$smarty->assign('add_display', 'none');
// 		$smarty->assign('arr_field_value', $rowset);
// 		$smarty->display('TblList.tpl');
// 	}
    function actionKucun(){
        $preArr = array();
        $needEmptyGanghao = false;
        // 弹窗-前置条件
        if($_GET['productId']!=''){
            $preArr['productId'] = $_GET['productId'];
        }

        // 弹窗-首次跳转时，GET/POST 中均不会有 ganghao信息
        if(empty($_POST['ganghao']) && empty($_GET['ganghao'])){
            $needEmptyGanghao = true;
        }

        FLEA::loadClass("TMIS_Pager");
        $arr = TMIS_Pager::getParamArray(array(
            'ganghao' => '',
        ));

        if($needEmptyGanghao){
            $arr['ganghao'] = '';
        }

        $strCon = " and kuwei not in ('成品仓库','疵品库位')";

        if(!empty($preArr['productId'])){
            $strCon .=" and productId='{$preArr['productId']}'";
            $arr['productId'] = $preArr['productId'];
        }

        if(!empty($arr['ganghao'])){
            $strCon .=" and ganghao like '%{$arr['ganghao']}%'";
        }


        $strGroup="productId,pihao,ganghao";
        $sqlUnion="
        
        select {$strGroup},
        sum(cntFasheng) as cntRuku,
        sum(moneyFasheng) as moneyRuku,
        0 as cntChuku,0 as moneyChuku
        from `cangku_common_kucun` where
        rukuId>0  {$strCon} group by {$strGroup}
        union
        select {$strGroup},
        0 as cntRuku,
        0 as moneyRuku,
        sum(cntFasheng*-1) as cntChuku,
        sum(moneyFasheng*-1) as moneyChuku
        from `cangku_common_kucun` where
        chukuId>0  {$strCon} group by {$strGroup}";
        $sql="select
        {$strGroup},
        z.guige,
        sum(cntRuku) as cntRuku,
        sum(moneyRuku) as moneyRuku,
        sum(cntChuku) as cntChuku,
        sum(moneyChuku) as moneyChuku
        from ({$sqlUnion}) as x
         left join jichu_product z on x.productId=z.id
        where 1
        group by {$strGroup}
        having sum(cntRuku)<>0 or sum(moneyRuku)<>0
        or sum(cntChuku)<>0 or sum(moneyChuku)<>0";
        //dump($sql);exit;
        // dumP($sqlUnion);exit;
        // todo.....
        $pager = &new TMIS_Pager($sql);
        $rowset = $pager->findAll();
        //得到合计信息

        foreach($rowset as &$v) {
            // dump($v);exit;
            //产品名称
            $sql = "select * from jichu_product where id='{$v['productId']}'";
            $temp = $this->_modelExample->findBySql($sql);
            $v['proCode'] = $temp[0]['proCode'];
            $v['proName'] = $temp[0]['proName'];
            $v['cntKucun'] = round($v['cntRuku'] - $v['cntChuku'], 2);
            //计划数
            $sqljh="select sum(cnt) as cntjh from shengchan_plan2touliao x
            where 1 and productId='{$v['productId']}' and ganghao='{$v['pihao']}'
            GROUP BY productId,ganghao";
            $retjh=$this->_modelExample->findBySql($sqljh);
            $v['cntjh'] = $retjh[0]['cntjh'];
            //本期入库和本期出库点击可看到明细
        }
        $heji = $this->getHeji($rowset,array('cntRuku','cntChuku','cntKucun'),'kuwei');

        $rowset[] = $heji;
        // 显示信息
        $arrFieldInfo = array(
            "pihao" => "批号",
            "ganghao" => "缸号",
            'proCode' => '品名',
            'cntKucun' => '余存',
            'cntjh' =>'已计划数',
            // 'cnt'=>'数量',
            );

        $smarty = &$this->_getView();
        $smarty->assign('title', '收发存报表');
        // $smarty->assign('pk', $this->_modelDefault->primaryKey);
        $smarty->assign('arr_field_info', $arrFieldInfo);
        $smarty->assign('add_display', 'none');
        $smarty->assign('arr_condition', $arr);
        $smarty->assign('arr_field_value', $rowset);
        $smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action'], $arr)));
        $smarty->assign('arr_js_css', $this->makeArrayJsCss(array('grid', 'calendar')));
        $smarty->display('Popup/CommonNew.tpl');
    }
}
?>