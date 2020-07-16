<?php
load_class('TMIS_TableDataGateway');
class Model_Check_Config extends TMIS_TableDataGateway {
    var $tableName = 'check_config';
    var $primaryKey = 'id';
    
    /**
     * @desc ：获得配置orderByAllMachine的值
     * Time：2017/06/15 14:54:20
     * @author Wuyou
    */
    public function getOrderByAllMachine(){
        $config = $this->findAll();
        $orderByAllMachine = true;
        foreach ($config as & $v) {
            if($v['item']=='orderByAllMachine') $orderByAllMachine = $v['value'];
        }
        return $orderByAllMachine;
    }
}
?>