<?php
//header('location:Building.html');
define('APP_DIR','Lib/App');
/////
define('ERROR_SYS',true);//定义为true:系统显示错误信息，定义false:系统不显示错误信息
define('ERROR_SYS_DIR','Lib/App/TMIS/ErrorSys.php');//处理错误信息地址
//define('DEPLOY_MODE', true);//定义为发布模式，不写日志define('DEPLOY_MODE', true);//定义为发布模式，不写日志
require('Lib/FLEA/FLEA.php');
FLEA::loadAppInf("Config/config.inc.php");
FLEA::loadAppInf("Config/compInfo.php");
FLEA::import('Lib/App');
FLEA::runMVC();
?>