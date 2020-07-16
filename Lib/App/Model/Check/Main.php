<?php
load_class('TMIS_TableDataGateway');
class Model_Check_Main extends TMIS_TableDataGateway {
    var $tableName = 'check_main';
    var $primaryKey = 'id';
    var $force_master =true;
    var $hasMany = array(
        array(
            'tableClass' => 'Model_Check_Main2flaw',
            'foreignKey' => 'mainId',
            'mappingName' => 'Flaw'
        )
    );
    var $hasOne = array(
        array(
            'tableClass' => 'Model_Code',
            'foreignKey' => 'relatId',
            'mappingName' => 'Code'
        ),
        array(
            'tableClass' => 'Model_Check_Main2chenpin',
            'foreignKey' => 'mainId',
            'mappingName' => 'Chenpin'
        )
    );

    /**
     * @desc ：保存完检验数据后，先生成对应的编号记录，产生最新编号并反写到检验表中
     * Time：2017/02/24 15:03:04
     * @author Wuyou
    */
    function _afterCreateDb(& $row){
        $this->force_master =true;
        if($row['checkId']<=0){//非手工指定卷号，需要执行以下操作
            __TRY();
            // 取得配置，判断卷号递增模式
            $mConfig = FLEA::getSingleton('Model_Check_Config');
            $orderByAllMachine = $mConfig->getOrderByAllMachine();
            $checkMachineId = $orderByAllMachine=='true'?0:$row['checkMachineId'];
            $mCode = & FLEA::getSingleton('Model_Code');
            // 得到自增表记录的id
            $code = $mCode->find(array('relatId'=>$row['id']));

            // 得到此分组的初始卷号
            $sql = "SELECT *
                    FROM code_auto_incrementing
                    WHERE rule='{$code['rule']}'
                    AND groups='{$code['groups']}'
                    AND checkMachineId='{$checkMachineId}'
                    AND kind = 'sg'";
            $firstCode = $mCode->findBySql($sql);
            // 得到此规则下至当前记录的个数
            $sql = "SELECT count(*) as cnt
                    FROM code_auto_incrementing
                    WHERE rule='{$code['rule']}'
                    AND groups='{$code['groups']}'
                    AND checkMachineId='{$checkMachineId}'
                    AND id<='{$code['id']}'
                    AND kind IS NULL";
            $count = $mCode->findBySql($sql);
            $newCode = $firstCode[0]['code'] + $count[0]['cnt'];
            $arr = array(
                'id' => $row['id'],
                'checkId' => $newCode,
                'Code' => array(
                    'id' => $code['id'],
                    'code' => $newCode
                )
            );
            //dump($arr);exit;
            $this->save($arr);
            // 抓取错误信息，如果发现有因修改卷号导致的唯一索引冲突，则删除此次保存的数据
            $ex = __CATCH();
            if (__IS_EXCEPTION($ex)) {
                $msg = $ex->getMessage();
                if(strpos($msg,"for key 'orderId_checkId'")||strpos($msg,"for key 'rule_code'")){
                    $this->removeByPkv($row['id']);
                }
            }

        }

    }
}
?>