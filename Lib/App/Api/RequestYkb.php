<?php
/*********************************************************************\
*  Copyright (c) 1998-2013, TH. All Rights Reserved.
*  Author :Jeff
*  FName  :Response.php
*  Time   :2014/05/13 18:31:40
*  Remark :api接口的请求类
\*********************************************************************/
FLEA::loadClass('Api_Response');
class Api_RequestYkb extends Api_Response{
    // var $starTime;
    var $_json;//从json文件载入后的对象
    var $_arr_method=array();//所有的方法名列表
    var $_method;//当前的方法
    var $_params;//当前参数
    var $_success;//是否成功
    var $_msg;//失败的错误信息，或者成功后的结果json
    var $_configFile = 'ConfigRequestYkb.json';

    /**
     * @desc ：调用外部api的统一接口
     * @author jeff 2015/09/22 11:15:11
     * @param $post:格式必须如下：
     *array(
     *   'method'=>'xxxx.xxx',
     *   'params'=>array(
     *       'param1'=>'a',
     *       'param2'=>'b',
     *   )
     *)
     * @return 返回值类型
    */
    function callApi($post) {
        // if(!isset($post['method']) || $post['method']=='') {
        //     $this->error("post['method']必须定义",false);
        //     // echo "post['method']必须定义";
        // }
        $objRequest = FLEA::getSingleton('Api_Httprequest');
        $objApi = $this;
        $url = $objApi->_json['url'];
        $token = $objApi->_json['token'];
        // consoleLog($url);
        // consoleLog($post);
        $post['token'] = $token;
        //dump2file可以把变量保存在/debug_log.txt中，这样方便调试。
        // dump2file('test中post:'.json_encode($_POST));
        // dump2file('test访问url:'.$url);

        $result = $objRequest->post($post,$url);
        return $result;
        // if(!isset($post['params'])) {
        //     $this->error("post['method']必须定义",false);
        //     // echo "post['method']必须定义";
        // }
    }
}