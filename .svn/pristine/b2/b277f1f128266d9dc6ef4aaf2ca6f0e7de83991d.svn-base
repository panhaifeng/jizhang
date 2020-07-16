<?php
/*********************************************************************\
*  Copyright (c) 2007-2015, TH. All Rights Reserved.
*  Author :li
*  FName  :Peihuo.php
*  Time   :2015/09/10 19:54:52
*  Remark :测试相关
\*********************************************************************/
FLEA::loadClass('Api_Response');
class Api_Lib_Test extends Api_Response {

    //重写_checkParams,参数是否合法可能包含业务逻辑
    function _checkParams($params) {
        return true;
    }

    /**
     * ps ：response测试
     * Time：2015/03/10 18:37:51
     * @author jeff
     * @param 参数类型
     * @return 返回值类型
    */
    function responseTest($params) {
        //返回语法错误,方法不存在
        // asdfasd();

        //flea框架错误,类不存在
        // $_model = FLEA::getSingleton('Model_Cangku_Madan1');

        //返回sql错误
        // $_model = FLEA::getSingleton('Model_Cangku_Madan');
        // $sql = "seleccc 2";
        // $rows = $_model->findBySql($sql);

        //直接dump
        // dump(array(0,1,1,1));
        $data['rsp'] = array(
            'success' => 'true',
            'msg'     => '执行成功'
        );
        $data['params'] = $params;
        // dump($data);exit;
        return $data;
    }

    /**
     * ps ：request测试
     * Time：2015/03/10 18:37:51
     * @author jeff
     * @param 参数类型
     * @return 返回值类型
    */
    function requestTest() {
        //返回语法错误,方法不存在
        // asdfasd();

        //flea框架错误,类不存在
        // $_model = FLEA::getSingleton('Model_Cangku_Madan1');

        //返回sql错误
        // $_model = FLEA::getSingleton('Model_Cangku_Madan');
        // $sql = "seleccc 2";
        // $rows = $_model->findBySql($sql);

        //直接dump
        // dump(array(0,1,1,1));

        //返回自定义的错误
        return array('success'=>false,'msg'=>'自定义错误');
    }
}