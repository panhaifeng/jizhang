<?php
/**
*޸by jeff zeng 2012-9-18
*{webcontrol type='Sysparam' item='manuCodeName'}
*在模板中获取系统配置中的项目值
*/
function _ctlSysparam($name,$params)	{
	FLEA::loadClass('TMIS_Common');
	$arr = TMIS_Common::getSysSet();
	if(!$arr[$params['item']]) return $params['item']=='manuCodeName'?'厂编':"系统设置缺失";
	return $arr[$params['item']];
}
?>