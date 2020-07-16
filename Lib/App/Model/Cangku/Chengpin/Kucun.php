<?php
load_class('Model_Cangku_Kucun');
class Model_Cangku_Chengpin_Kucun extends Model_Cangku_Kucun {
    var $tableName = "cangku_common_kucun";
    var $primaryKey = "id";
    var $hasMany = array (
        array(
            'tableClass' => 'Model_Cangku_Chengpin_Chuku2Product',
            'foreignKey' => 'chukuId',
            'mappingName' => 'Chuku',
        ),
        array(
            'tableClass' => 'Model_Cangku_Chengpin_Ruku2Product',
            'foreignKey' => 'rukuId',
            'mappingName' => 'Ruku',
        ),
    );
}
?>