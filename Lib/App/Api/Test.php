<?php
/*********************************************************************\
*  Copyright (c) 1998-2013, TH. All Rights Reserved.
*  Author :Jeff
*  FName  :Test.php
*  Time   :2014/05/13 18:31:40
*  Remark :api接口的测试类,注意，response类的api的访问，传递参数形式比如下：
*        Array (
*           [params] => Array(
*                [orderCode] => 1
*                [accesstoken] => 1
*            )
*            [method] => order.create
*            [isDebug] => bool //是否调试模式,调试模式开启时会自动dump传入和结果
*        )
\*********************************************************************/
// FLEA::loadClass('Api_Response');
class Api_Test{
    //这是api的访问地址,作为掉用方，必须申明这个地址
    // var $_url = "http://localhost/jxc_oubao/api.php";
    //访问密匙，必须和config.json中的token一致，确保安全性
    // var $_token = 'aa';

    function __construct() {
        //获得request的配置,用来测试其他系统的api
        $this->objReq = FLEA::getSingleton('Api_Request');
        //获得response的配置,用来测试自身提供的api
        $this->objRes = FLEA::getSingleton('Api_Response');
    }

    /**
     * @desc ：测试页面，列出所有api,点击一个可以发送模拟数据进行测试
     * @author jeff 2015/09/29 14:04:41
     * @param 参数类型
     * @return 返回值类型
    */
    function test() {
        //如果是post,http调用api
        if($_POST) {
            if($_POST['type']=='getsign') {
                //直接从配置文件中取token
                $token = md5($this->objRes->_json['token']);
                echo $token;exit;
            }
            if($_POST['type']=='response') {
                $this->callResponseApi();
            } else {
                $this->callRequestApi();
            }
            exit;
        }

        if(isset($_GET['type'])) {
            if($_GET['type']=='response') {
                $this->showResponsePage($_GET['m']);
            } else {
                $this->showRequestPage($_GET['m']);
            }
        }

        $arr =array();
        //列出所有的可测试的响应类api
        $arrRes = $this->objRes->_json['data'];
        $arr[] = "<dl>Response地址:{$this->objRes->_json['url']}";
        foreach($arrRes as $k=>&$v) {
            if($k=='//') continue;
            $arr[] = "<dt>{$k}</dt>";
            foreach($v as $kk=>&$vv) {
                if($kk=='//') continue;
                if(is_array($vv)) {
                    $arr[] = "<dd><b>{$kk}</b>({$vv['name']}):{$vv['funcName']} <a href='apitest.php?type=response&m={$kk}'>点击测试</a></dd>";
                }else {
                    $arr[] = "<dd><b>{$kk}</b>:{$vv} <a href='apitest.php?type=response'>点击测试</a></dd>";
                }

            }
        }
        $arr[] = "</dl>";

        //列出所有的可测试的请求类api
        $arrReq = $this->objReq->_json['data'];
        $arr[] = "<hr /><dl>Request地址:{$this->objReq->_json['url']}";
        if($arrReq) foreach($arrReq as $k=>&$v) {
            if($k=='//') continue;
            $arr[] = "<dt>{$k}</dt>";
            foreach($v as $kk=>&$vv) {
                if($kk=='//') continue;
                if(!isset($vv['funcName'])) $vv['funcName']='';
                if(is_array($vv)) {
                    $arr[] = "<dd><b>{$kk}</b>({$vv['name']}):{$vv['funcName']} <a href='apitest.php?type=request&m={$kk}'>点击测试</a></dd>";
                }else {
                    $arr[] = "<dd><b>{$kk}</b>:{$vv} <a href='apitest.php?type=request'>点击测试</a></dd>";
                }

            }
        }
        $arr[] = "</dl>";
        echo join('',$arr);
    }

    /**
     * @desc ：测试某个具体的response类api
     * @author jeff 2015/09/29 14:04:41
     * @param m:具体api名称
     * @return 返回值类型
    */
    function showResponsePage($m) {
        $obj = $this->objRes;
        //根据api声明，罗列出所有的参数
        $params = $obj->_arr_method[$m]['params'];
        $api = $m;
        foreach($params as $k=>&$v) {
            if($k=='//') unset($params[$k]);
        }
        $obj->_method = $m;
        $obj->_params = $params;
        require "Lib/App/Api/responsePage.php";
        exit;
    }

    /**
     * @desc ：测试某个具体的request类api
     * @author jeff 2015/09/29 14:04:41
     * @param m:具体api名称
     * @return 返回值类型
    */
    function showRequestPage($m) {
        $obj = $this->objReq;
        //根据api声明，罗列出所有的参数
        $params = $obj->_arr_method[$m]['params'];
        $api = $m;
        foreach($params as $k=>&$v) {
            if($k=='//') unset($params[$k]);
        }
        $obj->_method = $m;
        $obj->_params = $params;
        require "Lib/App/Api/requestPage.php";
        exit;
    }

    /**
     * @desc ：使用curl发起http请求，此方法一般用来测试本系统提供的api,如果要访问其他系统的api,需要使用callRequestApi方法
     * @author jeff 2015/09/29 14:04:41
     * @param 参数类型
     * @return 返回值类型
    */
    function callResponseApi() {
        //模拟post;
        $objRequest = FLEA::getSingleton('Api_Httprequest');
        $objApi = $this->objRes;
        $url = $objApi->_json['url'];
        $token = $objApi->_json['token'];
        $_POST['params']['token'] = md5($token) ;
        consoleLog($url);
        consoleLog($_POST);
        //dump2file可以把变量保存在/debug_log.txt中，这样方便调试。
        // dump2file('test中post:'.json_encode($_POST));
        // dump2file('test访问url:'.$url);
        $result = $objRequest->post($_POST,$url);

        //处理unicode
        echo $this->decodeUnicode($result);
        exit;
    }

    function callRequestApi() {
        // consoleLog('callRequestApi begin');
        //模拟post;
        $objRequest = FLEA::getSingleton('Api_Httprequest');
        $objApi = $this->objReq;
        $url = $objApi->_json['url'];
        $token = $objApi->_json['token'];
        $_POST['params']['token'] = md5($token);
        consoleLog($url);
        consoleLog($_POST);
        //dump2file可以把变量保存在/debug_log.txt中，这样方便调试。
        // dump2file('test中post:'.json_encode($_POST));
        // dump2file('test访问url:'.$url);
        $result = $objRequest->post($_POST,$url);
        echo $this->decodeUnicode($result);
        exit;
    }

    /**
     * ps ：将unicode进行解码
     * Time：2015/03/10 18:37:51
     * @author jeff
     * @param 参数类型
     * @return 返回值类型
    */
    function decodeUnicode($str) {
        return preg_replace_callback('/\\\\u([0-9a-f]{4})/i',create_function(
            '$matches',
            'return mb_convert_encoding(pack("H*", $matches[1]), "UTF-8", "UCS-2BE");'
        ),$str);
    }
}