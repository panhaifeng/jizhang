<?php
/*********************************************************************\
*  Copyright (c) 1998-2013, TH. All Rights Reserved.
*  Author :zcc
*  FName  :CheckPibu.php
*  Time   :2014/08/30 09:18:48
*  Remark :坯布检验
\*********************************************************************/
FLEA::loadClass('Api_Response');
class Api_Lib_CheckPibu extends Api_Response {
    function Api_Lib_CheckPibu(){
        $this->_mConfig = FLEA::getSingleton('Model_Check_Config');
        $this->_mCusInfo = FLEA::getSingleton('Model_Check_CusInfo');
        $this->_mFlawInfo = FLEA::getSingleton('Model_Check_FlawInfo');
        $this->_mMain = FLEA::getSingleton('Model_Check_Main');
        $this->_mMain2flaw = FLEA::getSingleton('Model_Check_Main2flaw');
        $this->_mCode = FLEA::getSingleton('Model_Code');
        $this->_modelStratNoll = &FLEA::getSingleton('Model_Check_StratNoll');
    }


    //重写_checkParams,参数是否合法可能包含业务逻辑
    function _checkParams($params) {
        return true;
    }

     /**
     * @desc ：获得自定义属性
     * Time：2017/01/11 11:09:09
     * @author Wuyou
    */
    function getCustomInfo(){
        $rows = $this->_mCusInfo->findAll(array('type'=>'pj'),'sort asc');
        $data = array();
        $data['rsp'] = array(
            'success' => true,
            'msg'     => '请求成功'
        );
        foreach ($rows as & $v) {
            $v['notNull'] = $v['notNull']>0?true:false;
            $v['sort'] = (int)$v['sort'];
        }
        $data['params'] = array('customData'=>$rows);
        __TRY();
        $ex = __CATCH();
        if (__IS_EXCEPTION($ex)) {
            $data['rsp']['success'] = false;
            $data['rsp']['msg'] = $ex->getMessage();
            $data['rsp']['code'] = 'E4499';
            return $data;
        }
        return $data;
    }

    /**
     * @desc ：根据指定的订单号，返回此订单的下一卷卷号、客户名称等信息
     * Time：2017/01/11 14:22:59
     * @param string $OrderID 订单号   $CheckID 卷号
     * @author Wuyou
    */
    function getOrderInfo($params){
        // $params = array('OrderID'=>'H161930','CheckID'=>1);//fortest
        $data = array();
        $data['rsp'] = array(
            'success' => true,
            'msg'     => '请求成功'
        );
        __TRY();
        $ret = $this->getOrder($params['OrderID'],$params['CheckID'],$params['CheckMachineID']);
        if(!$ret['success']){
            $data['rsp']['success'] = false;
            $data['rsp']['msg'] = $ret['msg'];
            return $data;
        }
        $data['params'] = $ret['data'];
        $ex = __CATCH();
        if (__IS_EXCEPTION($ex)) {
            $data['rsp']['success'] = false;
            $data['rsp']['msg'] = $ex->getMessage();
            $data['rsp']['code'] = 'E4499';
            return $data;
        }
        return $data;
    }


    /**
     * ps ：坯检的保存方法
     * Time：2017/06/30 12:42:47
     * @author zcc
    */
    function saveCheck($params){
        //dump($params);die();
        //判断该条码是否已经做了检验
        $sql="select * from check_main where ExpectCode='{$params['OrderID']}'";
        $isChecked = $this->_modelStratNoll->findBySql($sql);
        if($isChecked[0]){
            $data['rsp']['success'] = false;
            $data['rsp']['code'] = 'E00021';
            $data['rsp']['msg'] = "保存失败，条码{$params['CheckID']}已验，请重新选择！";
            return $data;
        }
        // 取得配置，判断卷号递增模式
        $orderByAllMachine = $this->_mConfig->getOrderByAllMachine();
        $data = array();
        $data['rsp'] = array(
            'success' => true,
            'msg'     => '保存成功'
        );
        //$checkId = $params['CheckID']>0?$params['CheckID']:$this->getNextCheckId($params['OrderID']);
        //dump($checkId);exit();


        if($params['OrderID']==''){
            $data['rsp']['success'] = false;
            $data['rsp']['msg'] = "保存失败，订单号不不能为空！";
            return $data;
        }
        //寻到这个条码的订单明细ID 即ord2proId 
        $sql = "SELECT ord2proId FROM shengchan_planpj_fenpi WHERE ExpectCode = '{$params['OrderID']}'";
        $ord2pro = $this->_mMain->findBySql($sql);
        $ord2proId = $ord2pro[0]['ord2proId'];
        // 自增卷号的验布机号，根据配置来取值；所有验布机编号，则验布机为0
        $checkMachineId = $orderByAllMachine=='true'?0:$params['CheckMachineID'];
        // dump($checkMachineId);exit();
        // 编码规则
        // $rule = 'pibu_'.$params['OrderID'];
        $rule = 'pibu_'.$ord2proId;
        //判断是否重号
        if($params['CheckID']>0){
            $sql = "SELECT count(*) as cnt
                    FROM code_auto_incrementing
                    WHERE rule='{$rule}' AND code='{$params['CheckID']}'";
            $temp = $this->_mMain->findBySql($sql);
            if($temp[0]['cnt']>0){
                $data['rsp']['success'] = false;
                $data['rsp']['code'] = 'E0002';
                $data['rsp']['msg'] = "保存失败，卷号{$params['CheckID']}已被使用，请重新输入卷号！";
                return $data;
            }

        }

       // 整理编号表内容
        $sql = "SELECT * FROM code_auto_incrementing WHERE rule='{$rule}' AND checkMachineId='{$checkMachineId}' ORDER BY groups DESC LIMIT 0,1";
        $lastCode = $this->_mMain->findBySql($sql);
        $codeArr = array(
            'rule' => $rule,
            'checkMachineId' => $checkMachineId,
            'groups' => $params['CheckID']>0?$lastCode[0]['groups']+1:$lastCode[0]['groups'],
        );
        if($params['CheckID']>0){
            $codeArr['code'] = $params['CheckID'];
            $codeArr['kind'] = 'sg';//区分类别，为手工指定卷号
        }

        //查询是否已经有过该条码的验布记录
        $isCreated = $this->_mMain->findCount(array('ExpectCode'=>$params['OrderID'],'kind'=>'pibu'));
        if($isCreated>0){
            $sql = "UPDATE check_main SET isRecovered=1 WHERE ExpectCode='{$params["OrderID"]}'";
            $this->_mMain->execute($sql);
        }

        // 保存验布信息
        $ckArr = array(
            'ExpectCode' => $params['OrderID'],
            'loomId' => $params['LoomID'].'',
            'checkMachineId' => $params['CheckMachineID'].'',
            'length' => $params['Length']+0,//检验长度
            'cutLength' => $params['CutLength']+0,//开剪长度
            'spliceLength' => $params['SpliceLength']+0,//拼接长度
            'inStockLength' => round($params['Length']-$params['CutLength']+$params['spliceLength'],1),//入库长度
            'lengthUnit' => $params['LengthUnit'].'',
            'lengthUnit' => $params['LengthUnit'].'',
            'weight' => $params['Weight']+0,
            'weightUnit' => $params['WeightUnit'].'',
            'width' => $params['Width'].'',
            'kezhong' => $params['KeZhong'].'',
            'grade' => $params['Grade'].'',
            'CutWeight' => $params['CutWeight'].'',
            'SpliceWeight' => $params['SpliceWeight'].'',
            'InStockWeight' => $params['InStockWeight'].'',
            'c1' => $params['CustomField'][0],
            'c2' => $params['CustomField'][1],
            'c3' => $params['CustomField'][2],
            'c4' => $params['CustomField'][3],
            'c5' => $params['CustomField'][4],
            'checkTime' => date('Y-m-d H:i:s'),
            'userName1' => $params['UserName1'].'',
            'userName2' => $params['UserName2'].'',
            'ext1' => $params['Ext1'].'',
            'ext2' => $params['Ext2'].'',
            'ext3' => $params['Ext3'].'',
            'ext4' => $params['Ext4'].'',
            'kind' => 'pibu',
            'Code' => $codeArr,
        );
        if($params['CheckID']>0) $ckArr['checkId'] = $params['CheckID'];
        // dump($ckArr);exit;
        $id = $this->_mMain->save($ckArr);
        // 整理疵点信息
        $xcArr = array();
        foreach ($params['Flaws'] as $k => & $v) {
            $xcArr[] = array(
                'mainId'             => $id,
                'flawId'             => $v['FlawID']+0,
                'name'               => $v['Name'].'',
                'yStartPos'          => $v['YStartPos']+0,
                'yStartPosCorrected' => $v['YStartPosCorrected']+0,
                'yEndPos'            => $v['YEndPos']+0,
                'yEndPosCorrected'   => $v['YEndPosCorrected']+0,
                'len'                => $v['Len']+0,
                'xPosName'           => $v['XPosName'].'',
                'xPos'               => $v['XPos']+0,
                'score'              => $v['Score']+0,
                'value'              => $v['Value'].'',
                'cut'                => $v['Cut'].'',
                'ext1'               => $v['Ext1'].'',
                'ext2'               => $v['Ext2'].'',
                'ext3'               => $v['Ext3'].'',
                'ext4'               => $v['Ext4'].'',
            );
        }
        $this->_mMain2flaw->saveRowset($xcArr);
        // 找到保存后记录中的卷号
        $newRow = $this->_mMain->find($id);
        if($newRow['id']==''){
            $checkId = $this->getNextCheckId($ord2proId,$params['CheckMachineID']);
            $data['rsp']['success'] = false;
            $data['rsp']['code'] = 'E0001';
            $data['rsp']['msg'] = "保存失败。卷号{$checkId}已被其它验布机使用，请再手工指定一个起始卷号后保存！";
            return $data;
        }
        $checkId = $newRow['checkId'];
        $ret = $this->getOrders($params['OrderID'],$checkId,$params['CheckMachineID']);
        $data['params'] = $ret['data'];
        __TRY();
        $ex = __CATCH();
        if (__IS_EXCEPTION($ex)) {
            $data['rsp']['success'] = false;
            $data['rsp']['msg'] = $ex->getMessage();
            $data['rsp']['code'] = 'E4499';
            return $data;
        }
        return $data;
    }   
    /**
     * @desc ：取得最新卷号 根据配置来取下一卷卷号(坯布检验)
     * Time：2017/01/11 14:40:54
     * @author Wuyou
     * @param string $orderId 厂编 $checkMachineId 验布机号
     * @return string
    */
    protected function getNextCheckId($orderId,$checkMachineId){
        $name = 'pibu';
        // 取得配置，判断卷号递增模式
        $orderByAllMachine = $this->_mConfig->getOrderByAllMachine();
        $checkMachineId = $orderByAllMachine=='true'?0:$checkMachineId;
        // 如果为区分验布机的排序方式，则先验证是否有手工指定的记录，没有则返回-1
        if($orderByAllMachine=='false'){
            $sql = "SELECT count(*) as cnt
                    FROM code_auto_incrementing
                    WHERE rule='{$name}_{$orderId}'
                    AND checkMachineId='{$checkMachineId}'
                    AND kind = 'sg'";
            $temp = $this->_mMain->findBySql($sql);
            if($temp[0]['cnt']==0) return -1;
        }
        $begin = 1;
        // 历史数据中的最大卷号
        $sql = "SELECT code as max
                FROM code_auto_incrementing
                WHERE rule='{$name}_{$orderId}'
                AND checkMachineId='{$checkMachineId}'
                ORDER BY groups DESC,id DESC LIMIT 0,1";
        $rows = $this->_mMain->findBySql($sql);
        $max = $rows[0]['max'];
        // dump($max);exit();
        if($max>0) {
            $next = $max + 1;
            return $next;
        }
        else {
            return $begin;

        }
    }
    /**
     * ps ：坯检中获取订单相关信息
     * Time：2017/07/11 10:50:37
     * @author zcc、
    */
    protected function getOrder($orderId,$checkId,$checkMachineId){
        set_time_limit(0);
        $sql = "SELECT
                    b.productId AS ProductID,
                    b.id as ord2proId,
                    p.ExpectCode AS OrderID,
                    c.compCode AS Customer,
                    b.unit AS LenUnit,
                    a.isOver AS CheckComplete,
                    'KG' AS weightUnit,
                    '' AS FabricID,
                    z.guige AS FabricName,
                    b.cntYaohuo AS SumLength,
                    z.menfu AS Width,
                    z.kezhong AS KeZhong,
                    'CM' AS WidthUnit,
                    concat_ws(' ',z.guige) AS Specification,
                    '' AS GuigeRuku,
                    '' AS Soledad,
                    '' AS ColorId,
                    z.color AS ColorName,
                    '' AS Dingchang,
                    '' AS ModelFile,
                    '' AS ModelFile2,
                    '' AS WaiCode,
                    a.orderCode  AS Ot1,
                    z.proCode  AS Ot2,
                    z.proName AS Ot3,
                    z.chengFen  AS Ot4,
                    p.ganghao  AS Ot5
                FROM trade_order2product b
                LEFT JOIN trade_order a ON a.id = b.orderId
                LEFT JOIN jichu_client c ON c.id = a.clientId
                LEFT JOIN jichu_product z ON z.id =b.productId
                left join shengchan_planpj_fenpi p on p.ord2proId =  b.id
                WHERE p.ExpectCode like '%{$orderId}%' and not exists(select ExpectCode from check_main where ExpectCode=p.ExpectCode)";
        $temp = $this->_mMain->findBySql($sql);
        // 如果为一条数据则返回对象 如果为多条数据则返回对象数组
        if (count($temp)<2) {
            $orderInfo = $temp[0];
            $ord2proId = $orderInfo['ord2proId'];
            // 订单信息
            if($orderInfo['OrderID']==''){
                return array('success'=>false,'msg'=>'订单号不存在');
            }
            // 已检验总数
            $sqlSum = "SELECT ifnull(SUM(length),0) as sum FROM check_main WHERE ExpectCode='{$orderId}' AND kind = 'pibu'";
            $sum = $this->_mMain->findBySql($sqlSum);
            // 下一卷卷号

            $nextCheckId = $this->getNextCheckId($ord2proId,$checkMachineId);
            $data = array(
                'ProductID'         => $orderInfo['ProductID'],
                'OrderID'           => $orderInfo['OrderID'],
                'NextCheckID'       => $nextCheckId,
                'Customer'          => $orderInfo['Customer'],
                'SumLength'         => $orderInfo['SumLength'],
                'LenUnit'           => $orderInfo['LenUnit'],
                'CheckedSumLength'  => round($sum[0]['sum'],2),
                'Width'             => $orderInfo['Width'],
                'KeZhong'           => $orderInfo['KeZhong'],
                'WidthUnit'         => $orderInfo['WidthUnit'],
                'CheckComplete'     => $checkId>0?true:false, //沃丰只要是每卷保存成功就需要返回完成字段
                'FabricID'          => $orderInfo['FabricID'],
                'FabricName'        => $orderInfo['FabricName'],
                'Specification'     => $orderInfo['Specification'],
                'JingWei'           => $orderInfo['JingWei'],
                'Soledad'           => $orderInfo['Soledad'],
                'ColorId'           => $orderInfo['ColorId'],
                'ColorName'         => $orderInfo['ColorName'],
                'Dingchang'         => 0,
                'ModelFile'         => $orderInfo['ModelFile'],
                'ModelFile2'        => $orderInfo['ModelFile2'],
                'Ot1'               => $orderInfo['Ot1'],
                'Ot2'               => $orderInfo['Ot2'],
                'Ot3'               => $orderInfo['Ot3'],
                'Ot4'               => $orderInfo['Ot4'],
                'Ot5'               => $orderInfo['Ot5']
            );
            // 如果卷号大于0 取得检验相关信息返回
            if($checkId>0){
                $sql = "SELECT *
                        FROM check_main
                        WHERE ExpectCode='{$orderId}' AND checkId='{$checkId}' AND kind = 'pibu'";
                $temp = $this->_mMain->findBySql($sql);
                $checkInfo = $temp[0];
                $data['LoomID'] = $checkInfo['loomId'];
                $data['CheckMachineID'] = $checkInfo['checkMachineId'];
                $data['CheckID'] = $checkInfo['checkId'];
                $data['Length'] = $checkInfo['length'];
                $data['CutLength'] = $checkInfo['cutLength'];
                $data['LengthUnit'] = $checkInfo['lengthUnit'];
                $data['Weight'] = $checkInfo['weight'];
                $data['WeightUnit'] = $checkInfo['weightUnit'];
                $data['Width'] = $checkInfo['width'];
                $data['KeZhong'] = $checkInfo['kezhong'];
                $data['CustomField'] = array($checkInfo['c1'],$checkInfo['c2'],$checkInfo['c3'],$checkInfo['c4'],$checkInfo['c5']);
                $data['CheckTime'] = $checkInfo['checkTime'];
                $data['UserName1'] = $checkInfo['userName1'];
                $data['UserName2'] = $checkInfo['userName2'];
                $data['Minification'] = $checkInfo['minification'];
                $data['Ext1'] = $checkInfo['ext1'];
                $data['Ext2'] = $checkInfo['ext2'];
                $data['Ext3'] = $checkInfo['ext3'];
                $data['Ext4'] = $checkInfo['ext4'];
            }
            return array('success'=>true,'data'=>$data);
        }else{//多条明细状态
            $Orders=array();
            foreach ($temp as $key =>&$v) {
                 // 已检验总数
                $sqlSum = "SELECT ifnull(SUM(length),0) as sum FROM check_main WHERE ExpectCode='{$v['OrderID']}' AND kind = 'pibu'";
                $sum = $this->_mMain->findBySql($sqlSum);
                // 下一卷卷号
                $nextCheckId = $this->getNextCheckId($v['ord2proId'],$checkMachineId);
                $Orders[$key] =array(
                    'ProductID'         => $v['ProductID'],
                    'OrderID'           => $v['OrderID'],
                    'NextCheckID'       => $nextCheckId,
                    'Customer'          => $v['Customer'],
                    'SumLength'         => $v['SumLength'],
                    'LenUnit'           => $v['LenUnit'],
                    'CheckedSumLength'  => round($sum[0]['sum'],2),
                    'Width'             => $v['Width'],
                    'KeZhong'           => $v['KeZhong'],
                    'WidthUnit'         => $v['WidthUnit'],
                    'CheckComplete'     => $v['CheckComplete']>0?true:false,
                    'FabricID'          => $v['FabricID'],
                    'FabricName'        => $v['FabricName'],
                    'Specification'     => $v['Specification'],
                    'JingWei'           => $v['JingWei'],
                    'Soledad'           => $v['Soledad'],
                    'ColorId'           => $v['ColorId'],
                    'ColorName'         => $v['ColorName'],
                    'Dingchang'         => 0,
                    'ModelFile'         => $v['ModelFile'],
                    'ModelFile2'        => $v['ModelFile2']
                );
                // 如果卷号大于0 取得检验相关信息返回
                if($checkId>0){
                    $sql = "SELECT *
                            FROM check_main
                            WHERE ExpectCode='{$v['OrderID']}' AND checkId='{$checkId}' AND kind = 'pibu'";
                    $temp1 = $this->_mMain->findBySql($sql);
                    $checkInfo = $temp1[0];
                    $Orders[$key]['LoomID'] = $checkInfo['loomId'];
                    $Orders[$key]['CheckMachineID'] = $checkInfo['checkMachineId'];
                    $Orders[$key]['CheckID'] = $checkInfo['checkId'];
                    $Orders[$key]['Length'] = $checkInfo['length'];
                    $Orders[$key]['CutLength'] = $checkInfo['cutLength'];
                    $Orders[$key]['LengthUnit'] = $checkInfo['lengthUnit'];
                    $Orders[$key]['Weight'] = $checkInfo['weight'];
                    $Orders[$key]['WeightUnit'] = $checkInfo['weightUnit'];
                    $Orders['Width'] = $checkInfo['width'];
                    $Orders['KeZhong'] = $checkInfo['kezhong'];
                    $Orders[$key]['CustomField'] = array($checkInfo['c1'],$checkInfo['c2'],$checkInfo['c3'],$checkInfo['c4'],$checkInfo['c5']);
                    $Orders[$key]['CheckTime'] = $checkInfo['checkTime'];
                    $Orders[$key]['UserName1'] = $checkInfo['userName1'];
                    $Orders[$key]['UserName2'] = $checkInfo['userName2'];
                    $Orders[$key]['Minification'] = $checkInfo['minification'];
                    $Orders[$key]['Ext1'] = $checkInfo['ext1'];
                    $Orders[$key]['Ext2'] = $checkInfo['ext2'];
                    $Orders[$key]['Ext3'] = $checkInfo['ext3'];
                    $Orders[$key]['Ext4'] = $checkInfo['ext4'];
                }
            }
            $data['Orders'] =$Orders;
            return array('success'=>true,'data'=>$data);
        }
    }
    /**
     * ps ：坯检中获取订单相关信息
     * Time：2017/07/11 10:50:37
     * @author zcc、
    */
    protected function getOrders($orderId,$checkId,$checkMachineId){
        $sql = "SELECT
                    b.productId AS ProductID,
                    b.id as ord2proId,
                    p.ExpectCode AS OrderID,
                    c.compCode AS Customer,
                    b.unit AS LenUnit,
                    a.isOver AS CheckComplete,
                    'KG' AS weightUnit,
                    '' AS FabricID,
                    z.guige AS FabricName,
                    b.cntYaohuo AS SumLength,
                    z.menfu AS Width,
                    z.kezhong AS KeZhong,
                    'CM' AS WidthUnit,
                    concat_ws(' ',z.guige) AS Specification,
                    '' AS GuigeRuku,
                    '' AS Soledad,
                    '' AS ColorId,
                    z.color AS ColorName,
                    '' AS Dingchang,
                    '' AS ModelFile,
                    '' AS ModelFile2,
                    '' AS WaiCode,
                    a.orderCode  AS Ot1,
                    z.proCode  AS Ot2,
                    z.proName AS Ot3,
                    z.chengFen  AS Ot4,
                    p.ganghao  AS Ot5
                FROM trade_order2product b
                LEFT JOIN trade_order a ON a.id = b.orderId
                LEFT JOIN jichu_client c ON c.id = a.clientId
                LEFT JOIN jichu_product z ON z.id =b.productId
                left join shengchan_planpj_fenpi p on p.ord2proId =  b.id
                WHERE p.ExpectCode like '%{$orderId}%'";
        $temp = $this->_mMain->findBySql($sql);
        // 如果为一条数据则返回对象 如果为多条数据则返回对象数组
        if (count($temp)<2) {
            $orderInfo = $temp[0];
            $ord2proId = $orderInfo['ord2proId'];
            // 订单信息
            if($orderInfo['OrderID']==''){
                return array('success'=>false,'msg'=>'订单号不存在');
            }
            // 已检验总数
            $sqlSum = "SELECT ifnull(SUM(length),0) as sum FROM check_main WHERE ExpectCode='{$orderId}' AND kind = 'pibu'";
            $sum = $this->_mMain->findBySql($sqlSum);
            // 下一卷卷号

            $nextCheckId = $this->getNextCheckId($ord2proId,$checkMachineId);
            $data = array(
                'ProductID'         => $orderInfo['ProductID'],
                'OrderID'           => $orderInfo['OrderID'],
                'NextCheckID'       => $nextCheckId,
                'Customer'          => $orderInfo['Customer'],
                'SumLength'         => $orderInfo['SumLength'],
                'LenUnit'           => $orderInfo['LenUnit'],
                'CheckedSumLength'  => round($sum[0]['sum'],2),
                'Width'             => $orderInfo['Width'],
                'KeZhong'           => $orderInfo['KeZhong'],
                'WidthUnit'         => $orderInfo['WidthUnit'],
                'CheckComplete'     => $checkId>0?true:false, //沃丰只要是每卷保存成功就需要返回完成字段
                'FabricID'          => $orderInfo['FabricID'],
                'FabricName'        => $orderInfo['FabricName'],
                'Specification'     => $orderInfo['Specification'],
                'JingWei'           => $orderInfo['JingWei'],
                'Soledad'           => $orderInfo['Soledad'],
                'ColorId'           => $orderInfo['ColorId'],
                'ColorName'         => $orderInfo['ColorName'],
                'Dingchang'         => 0,
                'ModelFile'         => $orderInfo['ModelFile'],
                'ModelFile2'        => $orderInfo['ModelFile2'],
                'Ot1'               => $orderInfo['Ot1'],
                'Ot2'               => $orderInfo['Ot2'],
                'Ot3'               => $orderInfo['Ot3'],
                'Ot4'               => $orderInfo['Ot4'],
                'Ot5'               => $orderInfo['Ot5']
            );
            // 如果卷号大于0 取得检验相关信息返回
            if($checkId>0){
                $sql = "SELECT *
                        FROM check_main
                        WHERE ExpectCode='{$orderId}' AND checkId='{$checkId}' AND kind = 'pibu'";
                $temp = $this->_mMain->findBySql($sql);
                $checkInfo = $temp[0];
                $data['LoomID'] = $checkInfo['loomId'];
                $data['CheckMachineID'] = $checkInfo['checkMachineId'];
                $data['CheckID'] = $checkInfo['checkId'];
                $data['Length'] = $checkInfo['length'];
                $data['CutLength'] = $checkInfo['cutLength'];
                $data['LengthUnit'] = $checkInfo['lengthUnit'];
                $data['Weight'] = $checkInfo['weight'];
                $data['WeightUnit'] = $checkInfo['weightUnit'];
                $data['Width'] = $checkInfo['width'];
                $data['KeZhong'] = $checkInfo['kezhong'];
                $data['CustomField'] = array($checkInfo['c1'],$checkInfo['c2'],$checkInfo['c3'],$checkInfo['c4'],$checkInfo['c5']);
                $data['CheckTime'] = $checkInfo['checkTime'];
                $data['UserName1'] = $checkInfo['userName1'];
                $data['UserName2'] = $checkInfo['userName2'];
                $data['Minification'] = $checkInfo['minification'];
                $data['Ext1'] = $checkInfo['ext1'];
                $data['Ext2'] = $checkInfo['ext2'];
                $data['Ext3'] = $checkInfo['ext3'];
                $data['Ext4'] = $checkInfo['ext4'];
            }
            return array('success'=>true,'data'=>$data);
        }else{//多条明细状态
            $Orders=array();
            foreach ($temp as $key =>&$v) {
                 // 已检验总数
                $sqlSum = "SELECT ifnull(SUM(length),0) as sum FROM check_main WHERE ExpectCode='{$v['OrderID']}' AND kind = 'pibu'";
                $sum = $this->_mMain->findBySql($sqlSum);
                // 下一卷卷号
                $nextCheckId = $this->getNextCheckId($v['OrderID'],$checkMachineId);
                $Orders[$key] =array(
                    'ProductID'         => $v['ProductID'],
                    'OrderID'           => $v['OrderID'],
                    'NextCheckID'       => $nextCheckId,
                    'Customer'          => $v['Customer'],
                    'SumLength'         => $v['SumLength'],
                    'LenUnit'           => $v['LenUnit'],
                    'CheckedSumLength'  => round($sum[0]['sum'],2),
                    'Width'             => $v['Width'],
                    'KeZhong'           => $v['KeZhong'],
                    'WidthUnit'         => $v['WidthUnit'],
                    'CheckComplete'     => $v['CheckComplete']>0?true:false,
                    'FabricID'          => $v['FabricID'],
                    'FabricName'        => $v['FabricName'],
                    'Specification'     => $v['Specification'],
                    'JingWei'           => $v['JingWei'],
                    'Soledad'           => $v['Soledad'],
                    'ColorId'           => $v['ColorId'],
                    'ColorName'         => $v['ColorName'],
                    'Dingchang'         => 0,
                    'ModelFile'         => $v['ModelFile'],
                    'ModelFile2'        => $v['ModelFile2']
                );
                // 如果卷号大于0 取得检验相关信息返回
                if($checkId>0){
                    $sql = "SELECT *
                            FROM check_main
                            WHERE ExpectCode='{$v['OrderID']}' AND checkId='{$checkId}' AND kind = 'pibu'";
                    $temp1 = $this->_mMain->findBySql($sql);
                    $checkInfo = $temp1[0];
                    $Orders[$key]['LoomID'] = $checkInfo['loomId'];
                    $Orders[$key]['CheckMachineID'] = $checkInfo['checkMachineId'];
                    $Orders[$key]['CheckID'] = $checkInfo['checkId'];
                    $Orders[$key]['Length'] = $checkInfo['length'];
                    $Orders[$key]['CutLength'] = $checkInfo['cutLength'];
                    $Orders[$key]['LengthUnit'] = $checkInfo['lengthUnit'];
                    $Orders[$key]['Weight'] = $checkInfo['weight'];
                    $Orders[$key]['WeightUnit'] = $checkInfo['weightUnit'];
                    $Orders['Width'] = $checkInfo['width'];
                    $Orders['KeZhong'] = $checkInfo['kezhong'];
                    $Orders[$key]['CustomField'] = array($checkInfo['c1'],$checkInfo['c2'],$checkInfo['c3'],$checkInfo['c4'],$checkInfo['c5']);
                    $Orders[$key]['CheckTime'] = $checkInfo['checkTime'];
                    $Orders[$key]['UserName1'] = $checkInfo['userName1'];
                    $Orders[$key]['UserName2'] = $checkInfo['userName2'];
                    $Orders[$key]['Minification'] = $checkInfo['minification'];
                    $Orders[$key]['Ext1'] = $checkInfo['ext1'];
                    $Orders[$key]['Ext2'] = $checkInfo['ext2'];
                    $Orders[$key]['Ext3'] = $checkInfo['ext3'];
                    $Orders[$key]['Ext4'] = $checkInfo['ext4'];
                }
            }
            $data['Orders'] =$Orders;
            return array('success'=>true,'data'=>$data);
        }
    }
}