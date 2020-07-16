<?php
/*********************************************************************\
*  Copyright (c) 1998-2013, TH. All Rights Reserved.
*  Author :shen
*  FName  :rsPlan.php
*  Time   :2018年1月11日 14:35:16
*  Remark :染色计划控制器
\*********************************************************************/
FLEA::loadClass('Controller_Cangku_Chuku');
class Controller_Cangku_Yuanliao_rsPlan extends Controller_Cangku_Chuku {

    // **************************************构造函数 begin********************************
    function __construct() {
        $this->_state = '原料';
        $this->_kind  = '染色出库';

        $this->_modelExample = &FLEA::getSingleton('Model_Cangku_Yuanliao_Chuku');
        $this->_modelSon = &FLEA::getSingleton('Model_Cangku_Yuanliao_Chuku2Product');
      
        $this->fldMain = array(
            'chukuCode' => array('title' => '染色计划单号', 'type' => 'text', 'readonly' => true, 'value' => $this->_getNewCode($this->_head, $this->_modelExample->qtableName, 'chukuCode')),
            'chukuDate' => array('title' => '计划日期', "type" => "calendar", 'value' => date('Y-m-d')),
            // 入库单号，自动生成

            'kind' => array('title' => '出库类别', 'type' => 'text', 'value' => $this->_kind, 'readonly' => true),

            // 'depId' => array('title' => '领料部门', 'type' => 'select', 'value' => '', 'model' => 'Model_Jichu_Department'),
            'lingliaoren' => array('title' => '领料人', 'type' => 'text', 'value' => $_SESSION['REALNAME']),

            'kuwei' => array('title' => '库位选择', 'type' => 'kuwei', 'value' => '','kwType'=>$this->_state),
            // 定义了name以后，就不会以memo作为input的id了
            'memo' => array('title' => '出库备注', 'type' => 'textarea', 'disabled' => true, 'name' => 'chukuMemo'),
            // 下面为隐藏字段
            'id' => array('type' => 'hidden', 'value' =>'','name'=>'chukuId'),
            // 是色坯纱  用来标示是在色坯纱管理中
            // 'isSePiSha' => array('type' => 'hidden', 'value' => '1', 'name' => 'isSePiSha')
        );

        // /从表表头信息
        $this->headSon = array(
            '_edit' => array('type' => 'btBtnRemove', "title" => '+5行', 'name' => '_edit[]'),
            'productId' => array('type' => 'btProductpopup', "title" => '产品编码', 'name' => 'productId[]'),
            'colorId' => array(
                'type' => 'btpopup', 
                "title" => '颜色', 
                'name' => 'colorId[]',
                'value' => '',
                'text'=>'',
                'url'=>url('Jichu_Color','Popup'),
                'onSelFunc'=>'onSelChuku',//选中后需要执行的回调函数名,需要在jsTpl中书写
                'textFld'=>'compCode',//显示在text中的字段
                'hiddenFld'=>'id',//显示在hidden控件中的字段
                'inTable'=>1,
            ),
            'proName' => array('type' => 'bttext', "title" => '品名', 'name' => 'proName[]', 'readonly' => true),
            'guige' => array('type' => 'bttext', "title" => '规格', 'name' => 'guige[]', 'readonly' => true),
            'chengfen' => array('type' => 'bttext', "title" => '成份', 'name' => 'chengfen[]', 'readonly' => true),
            // 'unit'=>array('type'=>'bttext',"title"=>'单位','name'=>'unit[]','readonly'=>true),
            'ganghao' => array('type' => 'bttext', 'title' => '缸号(批号)', 'name' => 'ganghao[]'),
            'cnt' => array('type' => 'bttext', "title" => '投料数量(kg)', 'name' => 'cnt[]'),
            // 'cntJian' => array('type' => 'bttext', "title" => '件数', 'name' => 'cntJian[]'),
            //'money' => array('type' => 'bttext', "title" => '金额(元)', 'name' => 'money[]'),
            'memo' => array('type' => 'bttext', "title" => '备注', 'name' => 'memo[]'),
            // ***************如何处理hidden?
            'id' => array('type' => 'bthidden', 'name' => 'id[]'),
            // 'plan2proId' => array('type' => 'bthidden', 'name' => 'plan2proId[]'),
        );
        // 表单元素的验证规则定义
        $this->rules = array(
            // 'orderDate'=>'required',
            'kuwei' => 'required',
            // 'traderId'=>'required'
        );

        $this->fldRight = array(
            "chukuDate" => "出库日期",
            // "kind" => "类别",
            'kuwei' => '库位',
            'proCode' => '产品编码',
            'proName' => '品名',
            'guige' => '规格',
            'dengji' => '等级',
            'colorName' => '颜色',
            'ganghao'=>'缸号(批号)',
            'cnt' => '领用数量(kg)',
            // 'cntJian' => '件数',
            'chukuCode' => array("text"=>'出库单号','width'=>150),
            'memo' => '备注'
        );
    }


    function actionAdd(){
        $this->authCheck('3-1-16');
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
        $smarty->assign('other_button',"<input class='btn btn-info' type='submit' id='Submit1' name='Submit1' value=' 保存并打印 ' accesskey='S' onClick=$('#submitValue').val('保存并打印条码')>");
        $smarty->assign('rules', $this->rules);
        $smarty->assign('sonTpl', 'Cangku/Yuanliao/rsPlan.tpl');
        $this->_beforeDisplayAdd($smarty);
        $smarty->display('Main2Son/T1.tpl');
    }

    function actionEdit() {
        $arr = $this->_modelExample->find(array('id' => $_GET['id'])); //dump($arr);exit;
        //如果为翻单，orderId，orderCode置空
        if($_GET['flag']==1) {
            $arr['fandanId']=$arr['id'];
            $arr['id']='';
            $arr['oldchukuCode']=$arr['chukuCode'];
            $arr['chukuCode'] = $this->_getNewCode($this->_head, $this->_modelExample->qtableName, 'chukuCode');
            foreach($arr['Products'] as &$v) {
                $v['id'] = "";
                $v['chukuId']='';
            }
        }
        //设置主表id的值
        foreach ($this->fldMain as $k => &$v) {
            $v['value'] = $arr[$k]?$arr[$k]:$v['value'];
        }
        // 入库明细处理
        foreach($arr['Products'] as &$v) {
            $sql = "select * from jichu_product where id='{$v['productId']}'";
            $_temp = $this->_modelExample->findBySql($sql); //dump($_temp);exit;
            $v['proCode'] = $_temp[0]['proCode'];
            $v['proName'] = $_temp[0]['proName'];
            $v['guige'] = $_temp[0]['guige'];
            $v['menfu'] = $_temp[0]['menfu'];
            $v['kezhong'] = $_temp[0]['kezhong'];
        }
        foreach($arr['Products'] as &$v) {
            $temp = array();
            foreach($this->headSon as $kk => &$vv) {
                $temp[$kk] = array('value' => $v[$kk]);
            }
            $sqlC = "select * from jichu_color where id='{$v['colorId']}'";
            $color_temp = $this->_modelExample->findBySql($sqlC); 
            $temp['colorId']['text'] = $color_temp[0]['compName'];
            $rowsSon[] = $temp;
        }
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
        $smarty->assign('other_button',"<input class='btn btn-info' type='submit' id='Submit1' name='Submit1' value=' 保存并打印 ' accesskey='S' onClick=$('#submitValue').val('保存并打印条码')>");
        $smarty->assign('sonTpl', 'Cangku/Yuanliao/rsPlan.tpl');
        $this->_beforeDisplayEdit($smarty);
        $smarty->display('Main2Son/T1.tpl');
    }

    function actionRight(){
        $this->authCheck('3-1-17');
        // DUMP($this->_arrKuwei);exit;
        //处理$this->_arrKuwei，用来是区分是成品销售出库，还是色坯纱销售出库
        $curState=$this->_state;
        // $this->authCheck('3-5');
        FLEA::loadClass('TMIS_Pager');
        // /构造搜索区域的搜索类型
        $serachArea = TMIS_Pager::getParamArray(array(
            'dateFrom' => date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-30, date("Y"))),
            'dateTo' => date("Y-m-d"),
            'kuweiName' => '',
            'proName' => '',
            'guige' => '',
            'ganghao'=>'',
            'colorSelect'=>'',
            // 'key' => '',
        ));

        $sql = "select
            y.clientId,
            y.chukuCode,
            y.kuwei,
            y.chukuDate,
            y.memo as chukuMemo,
            y.orderId,
            y.kind,
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
            d.compName as jiagonghuName,
            f.compName as kehuName,
            z.compName as colorName
            from cangku_common_chuku y
            left join cangku_common_chuku2product x on y.id=x.chukuId
            left join jichu_client f on y.clientId=f.id
            left join jichu_product b on x.productId=b.id
            left join jichu_jiagonghu d on y.jiagonghuId=d.id
            left join jichu_color z on z.id=x.colorId
            where y.kind='{$this->_kind}' ";

        if ($serachArea['dateFrom'] != '') {
            $sql .= " and y.chukuDate >= '{$serachArea['dateFrom']}' and y.chukuDate<='{$serachArea['dateTo']}'";
        }
        if ($serachArea['key'] != '') {
            $sql .= " and (y.chukuCode like '%{$serachArea['key']}%'
                    or b.proName like '%{$serachArea['key']}%'
                    or b.proCode like '%{$serachArea['key']}%'
                    or b.guige like '%{$serachArea['key']}%')";
        }
        if ($serachArea['kuweiName'] != '') {
            $sql .= " and y.kuwei = '{$serachArea['kuweiName']}'";
        }
        if($serachArea['proName'] != ''){
            $sql .=" and b.proName like '%{$serachArea['proName']}%'";
        }
        if($serachArea['guige'] != ''){
            $sql .=" and b.guige like '%{$serachArea['guige']}%'";
        }
        if($serachArea['colorSelect'] != ''){
            $sql .=" and b.color like '%{$serachArea['colorSelect']}%'";
        }
        if($serachArea['ganghao'] != ''){
            $sql .=" and x.ganghao like '%{$serachArea['ganghao']}%'";
        }
        $sql .= " order by  chukuCode desc,y.chukuDate desc";
        // dump($sql);exit;

        //得到总计
        $zongji = $this->getZongji($sql,array('cnt'=>'x.cnt','money'=>'x.money'));

        $pager = &new TMIS_Pager($sql);
        $rowset = $pager->findAll();
        // dump($rowset);exit;
        $canEdit= $this->authCheck('3-1-17-1',true);
        $canRemove=$this->authCheck('3-1-17-2',true);
        $canView=$this->authCheck('3-1-17-3',true);
        $canFan=$this->authCheck('3-1-17-4',true);
        $canPrint = $this->authCheck('3-1-17-5', true);

        if (count($rowset) > 0) foreach($rowset as &$value) {
            if($canEdit){
                $value['_edit'] = $this->getEditHtml($value['chukuId']);
            }else{
                $value['_edit'] .= " <a ext:qtip='暂无权限'>修改</a>";
            }

            if($canRemove){
                $value['_edit'] .= " ".$this->getRemoveHtml($value['chukuId']);
            }else{
                $value['_edit'] .= " <a ext:qtip='暂无权限'>删除</a>";
            }

            if($canView){
                $value['_edit'] .= " "."<a href='" . $this->_url('View', array('id' => $value['chukuId'])) . "' target='_blank' title='查看'>查看</a>";
            }else{
                $value['_edit'] .= " <a ext:qtip='暂无权限'>查看</a>";
            }

            if($canFan){
                 $value['_edit'] .= " <a href='".$this->_url('Edit',array(
                    'id'=>$value['chukuId'],
                    'fromAction'=>$_GET['action'],
                    'flag'=>1
                ))."' ext:qtip='翻单'>翻单</a>";
            }else{
                $value['_edit'] .= " <a ext:qtip='暂无权限'>翻单</a>";
            }

            if($canPrint){
                $value['_edit'] .= " "."<a href='" . $this->_url('Print', array('id' => $value['chukuId'])) . "' target='_blank' title='打印'>打印</a>";
            }else{
                $value['_edit'] .= " <a ext:qtip='暂无权限'>打印</a>";
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
        $arrFieldInfo = array("_edit" => array('text'=>'操作','width'=>180))+$this->fldRight;

        $smarty = &$this->_getView();
        $smarty->assign('title', '订单查询');
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

    function actionSave(){
        // dump($_POST);die;
        $yuanliao_llck2product = array();
        foreach ($_POST['productId'] as $key => $v) {
            // 当没有记录 或者 没有输入数量时 跳出本次循环 防止多保存
            if (empty($_POST['productId'][$key]) || empty($_POST['cnt'][$key])) continue;
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

        // 表之间的关联
        $yuanliao_llck['Products'] = $yuanliao_llck2product;
        $row = $this->notNull($yuanliao_llck);
        $row['creater'] = $_SESSION['REALNAME'];

        // 保存 并返回yuanliao_cgrk表的主键
         // dump($yuanliao_llck);exit;
        $itemId = $this->_modelExample->save($this->notNull($yuanliao_llck));

        $newId = $yuanliao_llck['id']?$yuanliao_llck['id']:$itemId;
        if($itemId>0){
             if($_POST['submitValue']=='保存并打印条码'){
                 js_alert(null,"window.parent.showMsg('保存成功!')",$this->_url('Print',array('id'=>$newId)));
             }else{
                 $_POST['id']='';
                 js_alert(null,"window.parent.showMsg('保存成功!')",url($_POST['fromController'],$_POST['fromAction']));
             }
        }else{
            // ;
            js_alert(null,"window.parent.showMsg('保存失败!')",$this->_url('add',$_POST));
        }
    }
    function actionPrint(){
        // dump($_GET);die;
        $rowset = $this->_modelExample->find(array('id' => $_GET['id'])); 

        $heji = array();
        foreach ($rowset['Products'] as $k => &$v) {
            $sql = "select * from jichu_product where id='{$v['productId']}'";
            $_temp = $this->_modelExample->findBySql($sql); //dump($_temp);exit;
            $v['proCode'] = $_temp[0]['proCode'];
            $v['proName'] = $_temp[0]['proName'];
            $v['guige'] = $_temp[0]['guige'];
            $v['menfu'] = $_temp[0]['menfu'];
            $v['chengFen'] = $_temp[0]['chengFen'];
            $sqlCl = "select compName from jichu_color where id='{$v['colorId']}'";
            $colo = $this->_modelExample->findBySql($sqlCl);
            $v['color'] = $colo[0]['compName'];
            $heji['cnt']+=$v['cnt'];
        }
        //不满5行的话填充数据
        $ii = count($rowset['Products']);
        if($ii<5){
            for($i = 0;$i < 5-$ii;$i++) {
                $rowset['Products'][] = array();
            }
        }

        $smarty = &$this->_getView();
        $smarty->assign('rowset', $rowset);
        $smarty->assign('heji',$heji);
        $smarty->display("Cangku/Yuanliao/rsPrint.tpl");
    }

    function actionView(){
        $arr = $this->_modelExample->find(array('id' => $_GET['id'])); //dump($arr);exit;
        //如果为翻单，orderId，orderCode置空
        if($_GET['flag']==1) {
            $arr['fandanId']=$arr['id'];
            $arr['id']='';
            $arr['oldchukuCode']=$arr['chukuCode'];
            $arr['chukuCode'] = $this->_getNewCode($this->_head, $this->_modelExample->qtableName, 'chukuCode');
            foreach($arr['Products'] as &$v) {
                $v['id'] = "";
                $v['chukuId']='';
            }
        }
        //设置主表id的值
        foreach ($this->fldMain as $k => &$v) {
            $v['value'] = $arr[$k]?$arr[$k]:$v['value'];
        }
        // 入库明细处理
        foreach($arr['Products'] as &$v) {
            $sql = "select * from jichu_product where id='{$v['productId']}'";
            $_temp = $this->_modelExample->findBySql($sql); //dump($_temp);exit;
            $v['proCode'] = $_temp[0]['proCode'];
            $v['proName'] = $_temp[0]['proName'];
            $v['guige'] = $_temp[0]['guige'];
            $v['menfu'] = $_temp[0]['menfu'];
            $v['kezhong'] = $_temp[0]['kezhong'];
        }
        foreach($arr['Products'] as &$v) {
            $temp = array();
            foreach($this->headSon as $kk => &$vv) {
                $temp[$kk] = array('value' => $v[$kk]);
            }
            $sqlC = "select * from jichu_color where id='{$v['colorId']}'";
            $color_temp = $this->_modelExample->findBySql($sqlC); 
            $temp['colorId']['text'] = $color_temp[0]['compName'];
            $rowsSon[] = $temp;
        }
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
        $smarty->assign('sonTpl', 'Cangku/Yuanliao/rsPlan.tpl');
        $smarty->assign('flag',$flag);
        $smarty->display('Main2Son/T4.tpl');
    }
}