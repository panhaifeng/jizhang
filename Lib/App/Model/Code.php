<?php
load_class('TMIS_TableDataGateway');
class Model_Code extends TMIS_TableDataGateway {
    var $tableName = 'code_auto_incrementing';
    var $primaryKey = 'id';
    var $force_master =true;
}
?>