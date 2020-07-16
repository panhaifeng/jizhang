<?php
/*********************************************************************\
*  Copyright (c) 1998-2013, TH. All Rights Reserved.
*  Author :wuyou
*  FName  :Login.php
*  Time   :2019/07/17 14:16:27
*  Remark :二维码验证接口
\*********************************************************************/
FLEA::loadClass('Api_Response');
class Api_Lib_Login extends Api_Response {
    function Api_Lib_Login(){
        $this->_model = FLEA::getSingleton('Model_Acm_Qrcodeverify');
    }


    //重写_checkParams,参数是否合法可能包含业务逻辑
    function _checkParams($params) {
        return true;
    }

    /**
     * @desc ：二维码验证 回写验证状态
     * Time：2019/07/17 14:17:33
     * @author Wuyou
    */
    function callback($params = array()){
        $data = array();
        $data['rsp'] = array(
            'success' => true,
            'msg'     => '请求成功'
        );

        $row = $this->_model->find(array('token'=>$params['token']));
        if($row['id']>0){
            $row['status'] = $params['status'];
            $row['message'] = $params['message'];
            $this->_model->save($row);
        }else{
            $data['rsp']['success'] = false;
            $data['rsp']['msg'] = '未找到token相关的验证记录';
            return $data;
        }
        __TRY();
        $ex = __CATCH();
        if (__IS_EXCEPTION($ex)) {
            $data['rsp']['success'] = false;
            $data['rsp']['msg'] = $ex->getMessage();
            $data['rsp']['code'] = 'E4499';
            return $data;
        }
        return $data;
    }


}