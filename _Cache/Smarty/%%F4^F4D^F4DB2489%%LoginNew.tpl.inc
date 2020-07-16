<?php /* Smarty version 2.6.10, created on 2020-06-24 17:08:35
         compiled from LoginNew.tpl */

$this->_cache_serials['Lib/App/../../_Cache/Smarty/%%F4^F4D^F4DB2489%%LoginNew.tpl.inc'] = 'f94fb260373f7c965fb2403585293eb6';

?><?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'webcontrol', 'LoginNew.tpl', 5, false),array('function', 'url', 'LoginNew.tpl', 36, false),array('modifier', 'default', 'LoginNew.tpl', 69, false),array('modifier', 'date_format', 'LoginNew.tpl', 69, false),)), $this); ?>
<?php
function _smarty_tplfunc_f94fb260373f7c965fb2403585293eb6_0(&$_smarty)
{
echo $_smarty->_plugins['function']['webcontrol'][0][0]->_pi_func_webcontrol(array('type' => 'GetAppInf','varName' => 'systemV'), $_smarty);
}


function _smarty_tplfunc_f94fb260373f7c965fb2403585293eb6_1(&$_smarty)
{
echo $_smarty->_plugins['function']['webcontrol'][0][0]->_pi_func_webcontrol(array('type' => 'GetAppInf','varName' => 'systemV'), $_smarty);
}


function _smarty_tplfunc_f94fb260373f7c965fb2403585293eb6_2(&$_smarty)
{
echo $_smarty->_plugins['function']['url'][0][0]->_pi_func_url(array('controller' => $_GET['controller'],'action' => 'login'), $_smarty);
}


function _smarty_tplfunc_f94fb260373f7c965fb2403585293eb6_3(&$_smarty)
{
echo $_smarty->_plugins['function']['url'][0][0]->_pi_func_url(array('controller' => $_GET['controller'],'action' => 'createshortcuts'), $_smarty);
}



?>
