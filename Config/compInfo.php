<?php
return array(
	'compName'=> '易奇科技',
	'ykbName'=>'常州市金马劲飞布业有限公司',
	'systemName'=>'易奇信息管理系统（金马定制版）',
	'ENcompName'=> 'Technologies.Inc',
    'teamCode'=>'c19e30b3503b3790bfc42aa7676dc5b2',
	'menu'=>'Config/menu.php',//使用的菜单目录
	'khqcxs'=>true,//列表和搜索项中是否显示客户全称
	'htbmgz'=>'getNewCode',//合同编码规则,getNewCode可形成类似'D11050003华'的编码，留空可形成DH11-00002的编码
	//'htbmxg'=>false,//合同编码是否可以修改，默认不可修改流转单号，置为true则可修改流转单号
	'dbDSN' => array(
        'driver'    => 'mysql',
        'host'      => 'localhost',
        'login'     => 'root',
        'password'  => 'root',
        'database'  => 'jm'
    )
);
?>
