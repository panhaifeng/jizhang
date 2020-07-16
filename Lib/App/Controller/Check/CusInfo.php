<?php
/*********************************************************************\
*  Copyright (c) 1998-2013, TH. All Rights Reserved.
*  Author :wuyou
*  FName  :CusInfo.php
*  Time   :2017/08/09 13:37:23
*  Remark :检验配置管理
\*********************************************************************/
FLEA::loadClass('TMIS_Controller');
class Controller_Check_CusInfo extends Tmis_Controller {
    var $_modelExample;
    function Controller_Check_CusInfo() {
        $this->_modelExample = FLEA::getSingleton('Model_Check_CusInfo');
        $this->_tplEdit = 'Check/CusInfoEdit.tpl';
    }

    /**
     * @desc ：查询列表
     * Time：2017/08/09 13:40:23
     * @author Wuyou
    */
    function actionRight() {
        $this->authCheck('7-5-2');
        FLEA::loadClass('TMIS_Pager');
        $arr=TMIS_Pager::getParamArray(array(
            'key'=>''
        ));
        $condition=array();
        if($arr['key']!='') {
            $condition[] = array('fieldName',"%{$arr['key']}%",'like');
        }
        $pager = new TMIS_Pager($this->_modelExample,$condition);
        $rowSet = $pager->findAll();
        //dump($rowSet[0]);
        if($rowSet) foreach ($rowSet as & $v) {
            $v['notNull'] = $v['notNull']>0?'是':'否';
            $v['clear'] = $v['clear']>0?'是':'否';
            $v['fieldType'] = $v['fieldType']=='select'?'下拉选项':($v['fieldType']=='text'?'文本框':'单选框');
            $v['_edit'] = $this->getEditHtml(array('id'=>$v['id'])).'&nbsp;&nbsp;'.$this->getRemoveHtml(array('id'=>$v['id']));
        }

        $arrFieldInfo = array(
            '_edit'       =>'操作',
            "fieldName"   =>"属性名称",
            "fieldType"   =>"属性类别",
            "fieldValue"  =>"属性值",
            "defaultText" =>array('text'=>"默认文本或选中项",'width'=>150),
            "notNull"     =>"是否必填",
            "clear"       =>array('text'=>"新的检验是否清除原先设置的值",'width'=>200),
            "sort"        =>"排序",
        );

        $smarty = & $this->_getView();
        $smarty->assign('arr_condition',$arr);
        $smarty->assign('arr_edit_info',$arr_edit_info);
        $smarty->assign("arr_field_info",$arrFieldInfo);
        $smarty->assign("title","检验自定义字段管理");
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
        $this->authCheck('7-5-2');
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
            $sql = "SELECT count(*) as cnt FROM `check_cusinfo` where fieldName='{$_POST['fieldName']}'";
            $rr = $this->_modelExample->findBySql($sql);
            if($rr[0]['cnt']>0) {
                js_alert('自定义字段名称重复,请确认以后输入!','window.history.go(-1)');
            }
        } else {
            //修改时判断是否重复
            $str1="SELECT count(*) as cnt FROM `check_cusinfo` where id<>{$_POST['id']} and fieldName='{$_POST['fieldName']}'";
            $ret=$this->_modelExample->findBySql($str1);
            if($ret[0]['cnt']>0) {
                js_alert('自定义字段名称重复,请确认以后输入!','window.history.go(-1)');
            }
        }
        $this->_modelExample->save($_POST);
        js_alert(null,"window.parent.showMsg('保存成功')",$this->_url('Right'));
    }

}
?>