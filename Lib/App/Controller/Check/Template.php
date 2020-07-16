<?php
/*********************************************************************\
*  Copyright (c) 1998-2013, TH. All Rights Reserved.
*  Author :wuyou
*  FName  :Template.php
*  Time   :2017/08/09 14:48:33
*  Remark :打印模板管理
\*********************************************************************/
FLEA::loadClass('TMIS_Controller');
class Controller_Check_Template extends Tmis_Controller {
    var $_modelExample;
    function Controller_Check_Template() {
        $this->_modelExample = FLEA::getSingleton('Model_Check_Template');
        $this->_tplEdit = 'Check/TemplateEdit.tpl';
    }

    /**
     * @desc ：查询列表
     * Time：2017/08/09 13:40:23
     * @author Wuyou
    */
    function actionRight() {
        $this->authCheck('7-5-4');
        FLEA::loadClass('TMIS_Pager');
        $arr=TMIS_Pager::getParamArray(array(
            'key'=>'',
            'tplType'=>''
        ));
        $condition=array();
        if($arr['key']!='') {
            $condition[] = array('fileName',"%{$arr['key']}%",'like');
        }
        if($arr['tplType']!='') {
            $condition[] = array('type',"{$arr['tplType']}",'=');
        }
        $pager = new TMIS_Pager($this->_modelExample,$condition,'type ASC,uploadTime ASC');
        $rowSet = $pager->findAll();
        //dump($rowSet[0]);
        if($rowSet) foreach ($rowSet as & $v) {
            if($v['type']=='client'){
                $sql = "SELECT * FROM jichu_client WHERE id='{$v['relateId']}'";
                $temp = $this->_modelExample->findBySql($sql);
                $v['relateInfo'] = $temp[0]['compName'];
            }
            if($v['type']=='order'){
                $sql = "SELECT * FROM trade_order WHERE id='{$v['relateId']}'";
                $temp = $this->_modelExample->findBySql($sql);
                $v['relateInfo'] = $temp[0]['orderCode'];
            }
            $v['type'] = $v['type'] == 'sys'?'系统级':($v['type'] == 'client'?'客户级':'订单级');
            $v['_edit'] = $this->getRemoveHtml(array('id'=>$v['id']));
        }

        $arrFieldInfo = array(
            '_edit'      =>'操作',
            "fileName"   =>array('text'=>"文件名称",'width'=>160),
            "filePath"   =>array('text'=>"存储路径",'width'=>200),
            "type"       =>array('text'=>"模板类型",'width'=>100),
            "relateInfo" =>array('text'=>"关联客户/订单",'width'=>180),
            "uploadTime" =>array('text'=>"上传时间",'width'=>150),
        );

        $smarty = & $this->_getView();
        $smarty->assign('arr_condition',$arr);
        $smarty->assign('arr_edit_info',$arr_edit_info);
        $smarty->assign("arr_field_info",$arrFieldInfo);
        $smarty->assign("title","打印模板管理");
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
        $this->authCheck('7-5-4');
        $sql = "SELECT id,orderCode FROM trade_order ORDER BY id DESC";
        $order = $this->_modelExample->findBySql($sql);
        $smarty = & $this->_getView();
        $smarty->assign('aRow',$arr);
        $smarty->assign('orderArr',$order);
        $smarty->display($this->_tplEdit);
    }

    /**
     * @desc ：保存
     * Time：2017/08/09 13:41:27
     * @author Wuyou
    */
    function actionSave() {
        // 文件上传
        $path = 'upload/check/';//保存路径
        if($_FILES['file']['name']!="") {
            // 上传文件类型仅为 .xlsx
            $file = substr($_FILES['file']['name'], -5);
            if($file!='.xlsx'){
                js_alert('只允许上传.xlsx的文件,请重新上传!','window.history.go(-1)');
            }
            // $allowType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
            // if($_FILES['file']['type']!=$allowType){
            //     js_alert('只允许上传.xlsx的文件,请重新上传!','window.history.go(-1)');
            // }
            // 验证文件名是否超过8个汉字
            $name = substr($_FILES['file']['name'], 0, strlen(substr($_FILES['file']['name'], -strpos($_FILES['file']['name'], '.'))));
            $nameLen = strlen($name);
            if($nameLen>24){
                js_alert('模板名不能超过8个汉字或者24个英文,请修改文件名称后重新上传!','window.history.go(-1)');
            }
            $tempFile = $_FILES['file']['tmp_name'];
            $targetFile = $path.$_FILES['file']['name'];//目标路径
            move_uploaded_file($tempFile,iconv('UTF-8','gb2312',$targetFile));
            $_POST['fileName'] = $_FILES['file']['name'];
            $_POST['filePath'] = $targetFile;
        }
        // 验证文件名是否重复
        $sql = "SELECT count(*) as cnt FROM `check_template` where fileName='{$_POST['fileName']}'";
        $rr = $this->_modelExample->findBySql($sql);
        if($rr[0]['cnt']>0) {
            js_alert('已存在同名文件,请重新上传!','window.history.go(-1)');
        }
        if($_POST['type']=='client') $_POST['relateId'] = $_POST['relateClientId'];
        if($_POST['type']=='order') $_POST['relateId'] = $_POST['relateOrderId'];
        $_POST['uploadTime'] = date('Y-m-d H:i:s');
        $this->_modelExample->save($_POST);
        js_alert(null,"window.parent.showMsg('保存成功')",$this->_url('Right'));
    }

    /**
     * @desc ：删除 并移除相应打印模板
     * Time：2017/08/10 10:51:36
     * @author Wuyou
    */
    function actionRemove(){
        // 先删除附件
        $row = $this->_modelExample->find($_GET['id']);
        if($row['filePath']!=''){
            // 中文文件名需转码后才能删除
            $filePath = iconv("utf-8","gbk",$row['filePath']);
            // dump($filePath);exit;
            unlink($filePath);
        }
        $temp = $this->_modelExample->removeByPkv($_GET['id']);
        if ($temp) {
            js_alert(null,"window.parent.showMsg('成功删除')",$this->_url('Right'));
        }
        else js_alert('出错，不允许删除!',$this->_url('Right'));
    }

    /**
     * @desc ：获取订单号
     * Time：2017/08/10 09:19:12
     * @author Wuyou
    */
    function actionGetOrderByKey() {
        if($_GET['code'])$key = $_GET['code'];
        if(isset($_REQUEST['q']))$key =$_REQUEST['q']?$_REQUEST['q']:'';
        $sql = "SELECT * from trade_order where orderCode like '%{$key}%' order by orderCode DESC";
        $arr = $this->_modelExample->findBySql($sql);
        $data=array();
        foreach ($arr as & $v) {
            $data[]=array($v['orderCode'],array('name'=>$v['orderCode'],'id'=>$v['id']));
        }
        echo json_encode($data);exit;
    }
}
?>