<?php
/*********************************************************************\
*  Copyright (c) 1998-2013, TH. All Rights Reserved.
*  Author :wuyou
*  FName  :Config.php
*  Time   :2017/08/09 13:37:23
*  Remark :检验配置管理
\*********************************************************************/
FLEA::loadClass('TMIS_Controller');
class Controller_Check_Config extends Tmis_Controller {
    var $_modelExample;
    function Controller_Check_Config() {
        $this->_modelExample = FLEA::getSingleton('Model_Check_Config');
        $this->_tplEdit = 'Check/ConfigEdit.tpl';
    }

    /**
     * @desc ：查询列表
     * Time：2017/08/09 13:40:23
     * @author Wuyou
    */
    function actionRight() {
        $this->authCheck('7-5-1');
        FLEA::loadClass('TMIS_Pager');
        $arr=TMIS_Pager::getParamArray(array(
            'key'=>''
        ));
        $condition=array();
        if($arr['key']!='') {
            $condition[] = array('item',"%{$arr['key']}%",'like','or');
            $condition[] = array('itemName',"%{$arr['key']}%",'like');
        }
        $pager = new TMIS_Pager($this->_modelExample,$condition);
        $rowSet = $pager->findAll();
        //dump($rowSet[0]);
        if($rowSet) foreach ($rowSet as & $v) {
            $v['_edit'] = $this->getEditHtml(array('id'=>$v['id'])).'&nbsp;&nbsp;'.$this->getRemoveHtml(array('id'=>$v['id']));
        }

        $arrFieldInfo = array(
            '_edit'    =>'操作',
            "item"     =>array('text'=>"配置项英文名称",'width'=>120),
            "itemName" =>array('text'=>"配置中文项名称",'width'=>150),
            "value"    =>array('text'=>"配置项的值",'width'=>100),
        );

        $smarty = & $this->_getView();
        $smarty->assign('arr_condition',$arr);
        $smarty->assign('arr_edit_info',$arr_edit_info);
        $smarty->assign("arr_field_info",$arrFieldInfo);
        $smarty->assign("title","检验配置项管理");
        $smarty->assign("arr_field_value",$rowSet);
        $smarty->assign('search_display', 'none');
        $smarty->assign("page_info",$pager->getNavBar($this->_url('Right',$arr)));
        $smarty->display("TableList.tpl");
    }

    /**
     * @desc ：编辑
     * Time：2017/08/09 14:05:34
     * @author Wuyou
    */
    function _edit($arr) {
        $this->authCheck('7-5-1');
        $smarty = & $this->_getView();
        $smarty->assign('aRow',$arr);
        $smarty->display($this->_tplEdit);
    }

    /**
     * @desc ：保存
     * Time：2017/08/09 13:41:27
     * @author Wuyou
    */
    function actionSave() {
        if(empty($_POST['id'])) {
            $sql = "SELECT count(*) as cnt FROM `check_config` where item='{$_POST['item']}'";
            $rr = $this->_modelExample->findBySql($sql);
            if($rr[0]['cnt']>0) {
                js_alert('配置项英文名称重复,请确认以后输入!','window.history.go(-1)');
            }
        } else {
            //修改时判断是否重复
            $str1="SELECT count(*) as cnt FROM `check_config` where id<>{$_POST['id']} and item='{$_POST['item']}'";
            $ret=$this->_modelExample->findBySql($str1);
            if($ret[0]['cnt']>0) {
                js_alert('配置项英文名称重复,请确认以后输入!','window.history.go(-1)');
            }
        }
        $this->_modelExample->save($_POST);
        js_alert(null,"window.parent.showMsg('保存成功')",$this->_url('Right'));
    }

}
?>