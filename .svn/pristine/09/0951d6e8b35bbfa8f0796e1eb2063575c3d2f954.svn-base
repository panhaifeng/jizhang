<?php
/*********************************************************************\
*  Copyright (c) 1998-2013, TH. All Rights Reserved.
*  Author :liangwenquan
*  FName  :Cpjy.php
*  Time   :2015/12/29 09:52:00
*  Remark :成品检验，验布信息
\*********************************************************************/
FLEA::loadClass('TMIS_Controller');
class Controller_Shengchan_PiJian_Pbjy extends Tmis_Controller{
    var $_modelCheck;
    var $_modelCheck2flaw;
    function __construct() {
        $this->_modelCheck = & FLEA::getSingleton('Model_Check_Main');
        $this->_modelExample = & FLEA::getSingleton('Model_Check_Main');
        $this->_modelCheck2Main = & FLEA::getSingleton('Model_Check_Main2chenpin');
        $this->_modelCheck2flaw = & FLEA::getSingleton('Model_Check_Main2flaw');
    }

        /**
     * ps ：验布检验查询模块
     * Time：2015/12/29 12:56:32
     * @author liangwenquan
    */
    function actionJianyan(){
        set_time_limit(0);
        $this->authCheck('14-3-3');
        FLEA::loadClass('TMIS_Pager');
        $arr = TMIS_Pager::getParamArray(array(
            'dateFrom'=>date('Y-m-d'),
            'dateTo' =>date('Y-m-d'),
            'orderCode'=>'',
            'proCode'=>'',
            'checkId'=>'',
            'checkMachineId'=>'',
            'jizhi'=>'',
            'ganghao'=>'',
            'ExpectCode'=>'',
        ));
        $sql = "SELECT x.*,round(if(lengthUnit='m',length,0.9144*length),1) as lengthM,
                o.orderCode,y.jitai,y.jianhao,x.c2 as ganghao,x.c3 as jitaiCode,
                o.id as ordId,j.proCode,j.proName,j.guige,j.color,t.id as ord2proId,round(x.weight,1)as wt
                FROM check_main x
                left join shengchan_planpj_fenpi y on x.ExpectCode = y.ExpectCode
                left join trade_order2product t on t.id=y.ord2proId 
                left join trade_order o on t.orderId=o.id
                left join jichu_product j on t.productId=j.id
                left join jichu_zhiji zz on zz.zhijiCode = y.jitai
                where 1 and x.kind = 'pibu'
                ";
        if($arr['dateFrom']!=''){
            $sql.=" and x.checkTime >='{$arr['dateFrom']} 00:00:00' and x.checkTime <='{$arr['dateTo']} 23:59:59'";
        }
        if($arr['orderCode']!='') {
            $sql .= " and o.orderCode like  '%{$arr['orderCode']}%'";
        }
        if($arr['jizhi']!='') {
            $sql.=" and zz.id='{$arr['jizhi']}'";
        }
        if($arr['ganghao']!='') {
            $sql.=" and y.ganghao='{$arr['ganghao']}'";
        }
        if($arr['proCode']!=''){
            /*$str = "SELECT distinct t.orderId FROM trade_order2product t left join jichu_product j on j.id=t.productId WHERE j.proCode like '%{$arr['proCode']}%'";
            $temp = $this->_modelCheck->findBySql($str);
            $orderIds = join(',', array_col_values($temp,'orderId'));
            $sql .= " and y.id IN({$orderIds})";*/
            $sql.=" and j.proCode like '%{$arr['proCode']}%'";
        }
        if($arr['checkId']!=''){
            $sql.=" and x.checkId = '{$arr['checkId']}'";
        }
        if($arr['checkMachineId']!=''){
            $sql.=" and x.checkMachineId = '{$arr['checkMachineId']}'";
        }

        if($arr['ExpectCode']!='') {
            $sql .= " and x.ExpectCode like  '%{$arr['ExpectCode']}%'";
        }
        
        if($arr['jizhi'] != '' && $arr['orderCode'] != ''){
            $sql.="order by y.jianhao asc";
        }else{
            $sql.="order by x.ExpectCode,x.checkId desc";
        }

        
        // dump($sql);die;
        $str = "SELECT * FROM ({$sql}) as t";
        $pager =& new TMIS_Pager($str);
        $rowset =$pager->findAll();
        $rowsetAll=$this->_modelExample->findBySql($str);
        //总计
        $sql = "SELECT sum(length) as length,sum(round(if(lengthUnit='m',length,0.9144*length),1)) as lengthM
                FROM ({$sql}) as t";

        $sumAll = $this->_modelCheck->findBySql($sql);
        $canEdit= $this->authCheck('14-3-1',true);
        $canDel= $this->authCheck('14-3-2',true);
        // dump($rowset);die;
        foreach($rowset as &$v) {
            $v['length'] = round($v['length'],1);

            if($canEdit){
                $v['_edit'] = $this->getEditHtml(array(
                    'orderId'=>$v['ordId'],
                    'checkId'=>$v['checkId'],
                    'proCode'=>$v['proCode'],
                    'ExpectCode'=>$v['ExpectCode'],
                    'cId'=>$v['id'],
                    'ord2proId'=>$v['ord2proId']
                ),'JianyanEdit');
            }else{
                $v['_edit'] .= " <a ext:qtip='暂无权限'>修改</a>";
            }
            if($canDel){
                $v['_edit'] .= ' '.$this->getRemoveHtml(array(
                        'id'=>$v['id'],
                        'msg'=>"是否确认删除",
                    ));
            }else{
                $v['_edit'] .= " <a ext:qtip='暂无权限'>删除</a>";
            }
            // $sql="select s.* from shengchan_chanliang s left join shengchan_planpj_fenpi f on s.fenpiId=f.id where f.ExpectCode='{$v['ExpectCode']}'";
            // $info = $this->_modelExample->findBySql($sql);
            // if($info[0]){
            //     $v['_edit'] ="<span class='glyphicon glyphicon-pencil' ext:qtip='已入库不能修改'>修改</span>";
            //     $v['_edit'].="&nbsp;<span class='glyphicon glyphicon-trash ' ext:qtip='已入库不能删除'>删除</span>";
            // }else{
            //      $v['_edit'] = $this->getEditHtml(array('orderId'=>$v['orderId'],'checkId'=>$v['checkId'],'proCode'=>$v['proCode'],'ExpectCode'=>$v['ExpectCode'],'cId'=>$v['id']),'JianyanEdit');
            //     $v['_edit'] .= ' '.$this->getRemoveHtml(array(
            //             'id'=>$v['id'],
            //             'msg'=>"是否确认删除",
            //         ));
            // }
          
        }
        $rowset[] = $this->getHeji($rowset,array('length','lengthM','wt','c1'),'_edit');
        $zongjiA = $this->getHeji($rowsetAll,array('length','lengthM','wt','c1'),'_edit');
        // $zongji = array(
        //     '_edit' => '<b>总计</b>',
        //     'length' => $sumAll[0]['length'],
        //     'lengthM' => $sumAll[0]['lengthM'],
        // );
        // dump($zongji);exit;
        $zongjiA['_edit'] = '总计';
        // $rowset[] = $zongji;
        $rowset[] = $zongjiA;
        // dump($rowset);die;
        $arrFieldInfo =array(
            "_edit"          => '操作',
            "orderCode"        => '订单编号',
            // 'ord2proId'      => '生产序号',
            'ExpectCode'     => '条码',
            "proCode"        => '产品编号',
            "proName"        => '品名',
            "guige"          => '规格',
            "color"          => '颜色',
            "checkId"        => array('text'=>'卷号','width'=>50),
            // "length"         => '检验长度',
            // "lengthUnit"     => '检验单位',
            // "lengthM"        => '检验米数',
            "wt"         => '检验重量',
            "jianhao"        => '件号',
            "ganghao"        => '缸号',
            "c4"             =>'区分',
            "c1"             => '称前重量',
            "jitaiCode"          => '机台号',
            "checkMachineId" => '验布机号',
            "userName1"      => '验布工1',
            "userName2"      => '验布工2',
            "checkTime"      => array('text'=>'检验时间','width'=>150),
        );
        $smarty = &$this->_getView();
        $smarty->assign('title', '成品检验');
        $smarty->assign('arr_field_info', $arrFieldInfo);
        $smarty->assign('arr_condition', $arr);
        $smarty->assign('add_display', 'none');
        $smarty->assign('arr_field_value', $rowset);
        $smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action'], $arr)));
        $smarty->display('TblList.tpl');

    }

    //修改检验报告信息
    function actionJianyanEdit(){
        // dump($_GET);die;
        //订单信息
        $sql = "SELECT t.id as orderId,j.compCode as customer,j.codeAtOrder,c.checkTime,p.proCode as fabricID,p.proName,p.guige,p.color,tt.menfu,tt.kezhong,t.orderCode,c.ExpectCode 
            FROM trade_order t
            left join jichu_client j on t.clientId=j.id
            left join jichu_product p on p.proCode='{$_GET['proCode']}'
            left join check_main c on c.checkId='{$_GET['checkId']}' and c.id = '{$_GET['cId']}'
            left join trade_order2product tt on tt.id='{$_GET['ord2proId']}'
            WHERE t.id='{$_GET['orderId']}' ";
        $order = $this->_modelCheck->findBySql($sql);
        $orderInfo = $order[0];
        // dump($orderInfo);die;

        //验布信息
        $sql_1 = "SELECT c.*,m.jitai,m.jianhao,m.ganghao,m.ord2proId
            FROM check_main c 
            left join shengchan_planpj_fenpi m on m.ExpectCode = c.ExpectCode
            WHERE c.checkId='{$_GET['checkId']}' 
            and c.ExpectCode='{$_GET['ExpectCode']}'";
        $Yan = $this->_modelCheck->findBySql($sql_1);
        $YanInfo = $Yan[0];
        // dump($YanInfo);die;

        //疵点信息

        $sql_2 = "SELECT a.*
                from check_main2flaw a
                left join check_main b on a.mainId=b.id
                where b.ExpectCode='{$_GET['ExpectCode']}'
                and b.checkId='{$_GET['checkId']}'";

        $Flaw = $this->_modelCheck->findBySql($sql_2);
        // dump($Flaw);die;
        if(count($Flaw)==0){
            $Flaw = array(array());
        }
        $smarty=& $this->_getView();
        $smarty->assign('orderinfo',$orderInfo);
        $smarty->assign('checkinfo',$YanInfo);
        $smarty->assign('cidianInfo',$Flaw);
        $smarty->display('Cangku/Chengpin/JianyanEdit.tpl');

    }

    //保存疵点信息
    function actionSaveCheck(){
        // dump($_POST);die;
        if($_POST['orderId']!='' && $_POST['checkId']!=''){
            $arr = array(
                'id' => $_POST['id'],
                'length' => $_POST['length']+0,
                'weight' => $_POST['weight']+0,
                'c1' => $_POST['c1'],
                'c2' => $_POST['c2'],
                'c3' => $_POST['c3'],
                'c4' => $_POST['c4'],
                'userName1' => $_POST['userName1'],
                'userName2' => $_POST['userName2'],
            );
            // dump($arr);exit;
            $ip = $this->_modelCheck->save($arr);
            foreach ($_POST['yStartPosCorrected'] as $k => & $v){
                if($_POST['name'][$k]=='') continue;
                $cidian[]=array(
                    'id' =>$_POST['sonId'][$k],
                    'mainId'=>$_POST['id'],
                    'yStartPosCorrected' =>$_POST['yStartPosCorrected'][$k],
                    'flawId' =>$_POST['flawId'][$k],
                    'name' =>$_POST['name'][$k],
                    'score' =>$_POST['score'][$k],
                    'value' =>$_POST['value'][$k],
                );
            }
            if(count($cidian)>0){
                $id = $this->_modelCheck2flaw->saveRowset($cidian);
            }else{
                $id = true;
            }
        }
        $ex = __CATCH();
        if($id && $ip) {
            js_alert(null,"window.parent.showMsg('保存成功!')",$this->_url('Jianyan'));
        } else {
            js_alert('出错,！'.$ex->getMessage(),'',$this->_url('Jianyan'));
        }
    }

    function actionRemove(){
        // dump($_GET);die;
        $sql = "DELETE FROM check_main2flaw  WHERE mainId='{$_GET['id']}'";
        $ii = $this->_modelCheck2flaw->execute($sql);
        $sql_1 = "DELETE FROM check_main WHERE id='{$_GET['id']}'";
        $id = $this->_modelCheck->execute($sql_1);
        $sqlSon = "DELETE FROM check_main2chenpin WHERE mainId='{$_GET['id']}' ";
        $ip = $this->_modelCheck2Main->execute($sqlSon);

        if($ii && $id && $ip) {
            js_alert(null,"window.parent.showMsg('删除成功!')",$this->_url('Jianyan'));
        } else {
            js_alert('删除失败！','',$this->_url('Jianyan'));
        }

    }
    /**
     * @desc ：ajax删除疵点明细
     * Time：2017/01/19 12:34:46
     * @author Wuyou
    */
    function actionRemoveByAjax(){
        if($this->_modelCheck2flaw->removeByPkv($_GET['id'])) {
            echo json_encode(array('success'=>true));
            exit;
        }
    }

    function actionCiDian(){
        $this->authCheck('14-4');
        FLEA::loadClass('TMis_Pager');
        $arr_condition = array(
            'proGuige'=>'',
            'traderId'=>'',
            'orderCode'=>'',
            'proCode'=>'',
            'guige' =>'',
            'ExpectCode' =>'',
        );
        $arr = TMis_Pager::getParamArray($arr_condition);
        $sql = "SELECT t.id as ordId,o.orderDate,o.orderCode,o.clientOrder,o.clientId,o.traderId,c.ExpectCode
            FROM check_main c
            left join shengchan_planpj_fenpi f on c.ExpectCode = f.ExpectCode
            left join trade_order2product t on t.id=f.ord2proId
            left join trade_order o on t.orderId=o.id
            left join jichu_product p on p.id = t.productId
            WHERE 1";

        if($arr['traderId']!='') {
            $sql .= " and o.traderId='{$arr['traderId']}'";
        }
        if($arr['orderCode']!='') {
            $sql .= " and o.orderCode like '%{$arr['orderCode']}%'";
        }
        if($arr['proCode']!='') {
            $sql .= " and p.proCode like '%{$arr['proCode']}%'";
        }
        if($arr['guige']!='') {
            $sql .= " and p.guige like '%{$arr['guige']}%'";
        }
        if($arr['ExpectCode']!='') {
            $sql .= " and c.ExpectCode like  '%{$arr['ExpectCode']}%'";
        }
        // if($arr['proCode']!='') {
        //     $str = "SELECT distinct orderId FROM trade_order2product t 
        //         left join jichu_product p on p.id=t.productId
        //         WHERE p.proCode like '%{$arr['proCode']}%'";
        //     $temp = $this->_modelCheck->findBySql($str);
        //     $orderIds = join(',', array_col_values($temp,'orderId'));
        //     $sql .= " and o.id IN({$orderIds})";
        // }
        // if($arr['guige']!='') {
        //     $str = "SELECT distinct orderId FROM trade_order2product t
        //         left join jichu_product p on p.id=t.productId
        //         WHERE p.guige like '%{$arr['guige']}%'";
        //     $temp = $this->_modelCheck->findBySql($str);
        //     $orderIds = join(',', array_col_values($temp,'orderId'));
        //     $sql .= " and o.id IN({$orderIds})";
        // }

        $sql .= " group by ordId order by ordId asc";
        $pager = & new TMIS_Pager($sql);
        // dump($sql);exit();
        $rowset = $pager->findAll();
        // dump($rowset);die;
        $rowsetAll =$this->_modelExample->findBySql($sql);
        if($rowset) {
            foreach($rowset as & $v) {
                // 获得订单明细的信息
                $str = "SELECT p.menfu,p.kezhong,p.color,p.proName,p.guige,p.proCode,t.*
                    FROM trade_order2product t
                    left join jichu_product p on p.id=t.productId
                    WHERE t.id = '{$v['ordId']}'";
                $temp = $this->_modelCheck->findBySql($str);
                // dump($temp);die;
                $v['proCode']     = $temp[0]['proCode'];
                $v['guige']       = $temp[0]['guige'];
                $v['color']       = $temp[0]['color'];
                // $v['jingwei']     = $temp[0]['jingwei'];
                // $v['menfu']       = $temp[0]['menfu'];
                $v['cntYaohuo']   = $temp[0]['cntYaohuo'];
                $v['dateJiaohuo'] = $temp[0]['dateJiaohuo'];
                // 获得业务员
                $sql = "SELECT employName FROM jichu_employ WHERE id='{$v['traderId']}'";
                $temp = $this->_modelCheck->findBySql($sql);
                $v['traderName'] = $temp[0]['employName'];
                // 获得客户名称
                $sql = "SELECT compName FROM jichu_client WHERE id='{$v['clientId']}'";
                $temp = $this->_modelCheck->findBySql($sql);

                $v['compName'] = $temp[0]['compName'];
                $v['_edit'] = "<a href='".$this->_url('FlawReport',array('ord2proId'=>$v['ordId'],'orderCode'=>$v['orderCode']))."'>疵点报告</a>";
                // $v['leixing'] = $v['orderKind']==0?'大货':'大样';
                if($v['unitDanjia']!=$v['unit']) {
                    if($v['unit']=='Y')
                        $v['money'] = round($v['danjia']*$v['cntYaohuo']*0.9144,2);
                    else $v['money'] = round($v['danjia']*$v['cntYaohuo']/0.9144,2);
                }
                else $v['money'] = round($v['danjia']*$v['cntYaohuo'],2);
                $v['cntYaohuo'].=$v['unit'];
                $v['danjia']=round($v['danjia'],3);
             }
        }
        if($rowsetAll) {
            foreach($rowsetAll as & $vv) {
                // 获得订单明细的信息
                $str = "SELECT p.menfu,p.kezhong,p.color,p.proName,p.guige,p.proCode,t.*
                    FROM trade_order2product t
                    left join jichu_product p on p.id=t.productId
                    WHERE t.id = '{$vv['ordId']}'";
                $temp = $this->_modelCheck->findBySql($str);
                // dump($temp);die;
                $vv['proCode']     = $temp[0]['proCode'];
                $vv['guige']       = $temp[0]['guige'];
                $vv['color']       = $temp[0]['color'];
                // $vv['jingwei']     = $temp[0]['jingwei'];
                // $vv['menfu']       = $temp[0]['menfu'];
                $vv['cntYaohuo']   = $temp[0]['cntYaohuo'];
                $vv['dateJiaohuo'] = $temp[0]['dateJiaohuo'];
                // 获得业务员
                $sql = "SELECT employName FROM jichu_employ WHERE id='{$vv['traderId']}'";
                $temp = $this->_modelCheck->findBySql($sql);
                $vv['traderName'] = $temp[0]['employName'];
                // 获得客户名称
                $sql = "SELECT compName FROM jichu_client WHERE id='{$vv['clientId']}'";
                $temp = $this->_modelCheck->findBySql($sql);

                $vv['compName'] = $temp[0]['compName'];
                $vv['_edit'] = "<a href='".$this->_url('FlawReport',array('ord2proId'=>$vv['ordId'],'orderCode'=>$vv['orderCode']))."'>疵点报告</a>";
                // $vv['leixing'] = $vv['orderKind']==0?'大货':'大样';
                if($vv['unitDanjia']!=$vv['unit']) {
                    if($vv['unit']=='Y')
                        $vv['money'] = round($vv['danjia']*$vv['cntYaohuo']*0.9144,2);
                    else $vv['money'] = round($vv['danjia']*$vv['cntYaohuo']/0.9144,2);
                }
                else $vv['money'] = round($vv['danjia']*$vv['cntYaohuo'],2);
                $vv['cntYaohuo'].=$vv['unit'];
                $vv['danjia']=round($vv['danjia'],3);
             }
        }
        $rowset[]=$this->getHeji($rowset, array('cntYaohuo'), '_edit');
        $zongji=$this->getHeji($rowsetAll, array('cntYaohuo'), '_edit');
        $zongji['_edit'] = '<b>总计</b>';
        $rowset[] = $zongji;
        $arrField = array(
            "_edit"         =>"操作",
            /*"ordId"       =>"生产序号",*/
            "orderCode"       =>"订单编号",
            "ExpectCode"    =>"条码",
            /*'compName'      =>array('text'=>'客户','width'=>200),*/
            'orderDate'     =>'下单日期',
            'proCode'       =>'产品编号',
            // "menfu"         =>"门幅",
            "guige"         =>"品名规格",
            "color"         =>"颜色",
            "cntYaohuo"     =>"下单数量",
            'dateJiaohuo'   =>'交期',
            // "leixing"       =>"合同类型",
            'traderName'    =>'业务员',
            // 'clientOrder'   =>'客户合同号',
        );
        $smarty = & $this->_getView();
        $smarty->assign("arr_field_info",$arrField);
        $smarty->assign("arr_field_value",$rowset);
        $smarty->assign("arr_condition",$arr);
        $smarty->assign('nowrap',true);
        $smarty->assign('add_display','none');
        $smarty->assign('arr_js_css',$this->makeArrayJsCss(array('calendar')));
        $smarty->assign('page_info',$pager->getNavBar($this->_url($_GET['action'],$arr)));
        $smarty->display("TableList.tpl");

    }
    /**
     * ps 疵点报告
     * Time：2017/07/11 12:48:03
     * @author zcc
    */
    function actionFlawReport(){
        $title="疵点报告";
        if ($_POST['button1']=="导出") {
            $arr=array(
            'date' => $_REQUEST['curDate'] !='' ? $_REQUEST['curDate'] : date('Y-m-d'),
            // 'orderId' => $_REQUEST['fCode'],
            'orderCode' => $_REQUEST['fCode'],
            );
        }else{
            $arr=array(
            'date' => $_REQUEST['date'] !='' ? $_REQUEST['date'] : date('Y-m-d'),
            // 'orderId' => $_REQUEST['orderId'],
            'orderCode' => $_REQUEST['ord2proId'],
        );
        }
        $orderId = $arr['orderCode'];
        // dump($orderId);die;
        $arr['orderCodeNew'] = $_REQUEST['orderCode'];
        if($orderId!=''){
            $sql="select j.proCode,z.codeAtOrder,j.color,j.guige,j.proName,x.cntYaohuo,y.orderCode,z.compName,z.compCode
                from trade_order2product x
                left join trade_order y on y.id=x.orderId
                left join jichu_client z on z.id=y.clientId
                left join jichu_product j on j.id=x.productId
                where x.id='{$orderId}'";
            $res=$this->_modelExample->findBySql($sql);
            $info=$res[0];
            // dump($info);die;
        }
        $sql = "SELECT m.*,f.jitai,f.jianhao,m.c2 as ganghao,x.productId,round(m.weight,1)as weight
            from check_main m
            left join shengchan_planpj_fenpi f on f.ExpectCode = m.ExpectCode
            left join trade_order2product x on x.id=f.ord2proId
            left join trade_order y on y.id=x.orderId
            left join jichu_product z on z.id=x.productId
            WHERE x.id='{$orderId}' and m.kind = 'pibu' order by jitai,jianhao asc ";    
        $rowset=$this->_modelExample->findBySql($sql);
        // dump($rowset);exit;
        foreach($rowset as & $v){
            $sql="select * from check_main2flaw where mainId='{$v['id']}'";

            $re=$this->_modelExample->findBySql($sql);
            $xcPos=array();
            $xcName=array();
            $xcScore=array();
            //放置疵点情况的数组
            $xcinfo= array($xcPos,$xcName,$xcScore);
            for ($i=0; $i<sizeof($re); $i++) {
                $xcinfo[$i]['xcPos']=$re[$i]['yStartPosCorrected'];
                $xcinfo[$i]['xcName']=$re[$i]['name'];
                $xcinfo[$i]['score']=intval($re[$i]['score']);
                $xcinfo[$i]['score']=$xcinfo[$i]['score']?$xcinfo[$i]['score']:'0';
                $xcinfo[$i]['xcvalue']=$re[$i]['value']?$re[$i]['value']:" ";
            }
            $v['xcinfo']=$xcinfo;
        }
            // dump($rowset);die;

        foreach($rowset as & $v2){
            $sql = "SELECT a.*,sum(a.score) as totalScore
                    from check_main2flaw a
                    left join check_main b on a.mainId=b.id
                    where a.mainId='{$v2['id']}'
                    group by b.checkId order by b.checkId";
            $re=$this->_modelExample->findBySql($sql);
            // dump($sql);exit;
            $v2['totalScore']=intval($re[0]['totalScore']);
            //获取每100平方码扣分=(总扣分*100*36)/(验布码数*幅宽)
            //幅宽大单位是英寸，1码=36英寸
            if($info['unit']=='Y'){
                $v2['perScore']=round(($v2['totalScore']*100*36)/($v2['checkLen']*$v2['checkWidth']),2);
            }else{
                $v2['perScore']=round(($v2['totalScore']*100*36)/($v2['checkLen']/0.9144*$v2['checkWidth']),2);
            }
        }
        // dump($info);
        // dump($rowset);die;
        $smarty=& $this->_getView();
        $smarty->assign('arr_condition',$arr);
        $smarty->assign('info',$info);
        $smarty->assign('rowset',$rowset);
        $filename = urlencode($orderId.'疵点报告');
        if ($_POST['button1']=="导出") {
            //头部文件
            header("Content-type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename={$filename}.xls");
            $smarty->display('Shengchan/Pijian/CdDaochu.tpl');
        }else{
            $smarty->display('Shengchan/Pijian/CdReport.tpl');
        }

   }
}