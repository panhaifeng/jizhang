<?php
/**
*޸by li zeng 2012-12-17
*{webcontrol type='HuaxingInf'}
*在模板中获取配置文件中的变量。
*/
function _ctlGetProductinf()	{
	$mm=& FLEA::getSingleton('TMIS_Controller');
	return $mm->getManuCodeName();
}
?>