<?php
/*********************************************************************\
*  Copyright (c) 1998-2013, TH. All Rights Reserved.
*  Author :Jeff
*  FName  :Response.php
*  Time   :2014/05/13 18:31:40
*  Remark :api接口的响应类
\*********************************************************************/
class Api_Response {
    // var $starTime;
    var $_json;//从json文件载入后的对象
    var $_arr_method=array();//所有的方法名列表
    var $_method;//当前的方法
    var $_tmpMethodExt; //method 方法的扩展名称，前缀
    var $_params;//当前参数
    var $_success;//是否成功
    var $_msg;//失败的错误信息，或者成功后的结果json
    var $_configFile = 'ConfigResponse.json';
    function __construct() {
        //判断超时代码，暂时不考虑
        // $this->starTime = $_SERVER['REQUEST_TIME']?$_SERVER['REQUEST_TIME']:time();
        //根据配置文件获得所有的方法列表,用作映射,可考虑引入缓存机制 todo
        $api_json = file_get_contents(APP_DIR."/Api/".$this->_configFile);

        //去掉api_json的注释,todo
        $arr = json_decode($api_json,true);
        if(!$arr) {
            echo "api配置文件decode失败，请检查config.json语法!";
            exit;
        }
        $this->_json = $arr;

        foreach($arr['data'] as $k=>&$v) {
            if($k=='//') continue;
            foreach($v as $kk=>&$vv) {
                if($kk=='//') continue;
                //如果是数组，说明是详细配置
                if(is_array($vv)) {
                    $name = isset($vv['name'])?$vv['name']:'';
                    $desc = isset($vv['desc'])?$vv['desc']:'';
                    $this->_arr_method[$kk] = array(
                        'name'=>$name,
                        'desc'=>$desc,
                        'params'=>$vv['params']
                    );

                    if(isset($vv['funcName'])) {
                        $temp = explode('@',$vv['funcName']);
                        $className = $temp[0];
                        $funcName = $temp[1];
                        $this->_arr_method[$kk]['className']=$className;
                        $this->_arr_method[$kk]['funcName']=$funcName;
                    }
                    continue;
                }
                //如果不是数组，说明是简单的接口说明
                echo "{$kk}接口的声明存在错误!";exit;
            }
        }
        // consoleLog($api_json);
        // consoleLog(json_decode($api_json,true));

        // $this->_arr_method = array(
        //     'order.create'=>array('className'=>'Api_Order','funcName'=>'create')
        // );
    }

    public function tmpExtMethodPre($pre = '')
    {
        $this->_tmpMethodExt = $pre;
    }

    /**
     * @desc ：调用api对应的方法，
     * @author jeff 2015/09/21 15:55:03
     * @param 参数类型
     * @return json
    */
    function response() {
        $time1 = microtime_float();
        $method = $this->_getMethod();
        if(!$method) {
            $this->error('$_POST["method"] not found','E40001',false);
            return false;
        }
        //开始正式访问api方法，

        // $params 兼容 json格式、表单数组格式
        if(empty($_POST['params'])){
            $params = array();
        }else if(is_array($_POST['params'])){
            $params = $_POST['params'];
        }else{
            $params = json_decode($_POST['params'], true);
        }

        $isDebug = isset($_POST['isDebug']) ? $_POST['isDebug'] : false;
        if(!$params) {
            $this->error('$_POST["params"] can not be empty','E40002',false);
            return false;
        }
        if(!isset($_POST['version']) && !isset($_POST['token'])) {
           $this->error('$_POST["token"] not found','E40003',false);
           return false;
        }

        if(isset($_POST['version']) && $_POST['version']=='verify_1.0'){
            //获取处理入口和对应参数
            list($method,$params2) = $this->parse_rpc_request($_POST, $this->_json['token2qr']);
        }elseif($_POST['token']!=md5($this->_json['token'])) {
           $this->error('token not match','E40004',false);
           return false;
        }

        if(!$this->_checkParams($params)) {
            $this->error('params is not avlidate','E40005',false);
            return false;
        }
        if(!isset($this->_arr_method[$method])) {
            $this->error('api method is not announced','E40006',false);
            return false;
        }

        //调试模式下输出传入参数
        if($isDebug) {
            echo "<b>post params:</b><br />";
            dump($_POST);
        }
        $class = FLEA::getSingleton($this->_arr_method[$method]['className']);
        if(!$class) {
            $this->error("{$this->_arr_method[$method]['className']} not found",'E40006',false);
            return false;
        }

        $this->_method = $method;
        $this->_params = $params;

        if(!isset($this->_arr_method[$method])) {
            $this->error("{$method} is not defined!",'E40007',false);
            return false;
        }

        $funcName = $this->_arr_method[$method]['funcName'];
        if(!method_exists($class, $funcName)) {
            $this->error("{$this->_arr_method[$method]['className']}@{$funcName} not exists!",'E40008',false);
            return false;
        }
        // $this->error($this->_arr_method[$method]['className']);

        $ret = $class->$funcName($params);
        $time2 = microtime_float();
        $ret['takeTime'] = $time2 - $time1;// 耗时

        //调试模式下输出返回结果
        if($isDebug) {
            echo "<b>api return:</b><br />";
            dump($ret);
            exit;
        }
        if(!$ret) {
            $this->error('the value returned can not be null','E40009',false);
            return false;
        }
        if(!$ret['rsp']['success']) {
            $this->_success = false;
            $this->_msg = $ret['rsp']['msg'];
            $this->error($ret['rsp']['msg'],$ret['rsp']['code'],true);//抛出错误信息，并写日志
            return false;
        }
        $this->_success = true;
        $this->_msg = json_encode($ret);
        $this->success($ret);//输出response，并写日志
        return true;
    }

    //抛出错误信息
    /**
     * @desc ：判出错误信息
     * @author jeff 2015/09/21 15:55:03
     * @param msg:错误提示
     * @param bWritelog:是否记录日志,系统级别的错误都不需要写日志
     * @return 返回值类型
    */
    function error($msg,$code,$bWritelog=true) {
        if($bWritelog) {
            $this->writeLog();
        }
        //先写日志，然后返回
        $ret = array(
            'success'   =>  false,
            'msg'   =>  $msg,
            'code'  => $code,
            'takeTime'  =>  $data['takeTime']
        );
        echo json_encode($ret);
        exit;
    }



    //成功返回
    function success($data,$bWritelog=true) {
        if($bWritelog) {
            $this->writeLog();
        }
        //先写日志，然后返回
        $ret = array(
            'success'   =>  true,
            'msg'   =>  $data['rsp']['msg'],
            'code'  =>  0,
            'params'  =>  $data['params']
        );
        echo json_encode($ret);
        exit;
    }

    // 获得 POST中的method参数
    public function _getMethod()
    {
        $method = isset($_POST['method'])?$_POST['method']:'';

        if(!$method) {
            return false;
        }

        if(!empty($this->_tmpMethodExt))
        {
            $method = $this->_tmpMethodExt . '.' . $method;
        }

        return $method;
    }

    /**
     * @desc ：检查参数是否正确,这个方法一般需要在子类中进行重写，因为参数的合法性可能包含业务逻辑
     * @author jeff 2015/09/21 15:55:03
     * @param 参数类型
     * @return 返回值类型
    */
    function _checkParams($params) {
        return true;
    }

    /**
     * @desc ：写日志
     * @author jeff 2015/09/21 15:55:03
     * @param 参数类型
     * @return 返回值类型
    */
    function writeLog() {
        $row = array(
            'type'=>0,//0表示响应
            'success'=>$this->_success?true:false,
            'apiName'=>$this->_method,
            'url'=>$_SERVER['REQUEST_URI'],
            'params'=>json_encode($this->_params),
            'msg'=>$this->_msg,
            'calltime'=>date('Y-m-d H:i:s'),
            'retrytime'=>'',
        );
        //在控制台中输出变量，需要和chromephp配合 ，具体安装和使用请参考170/phpwind中的帖子
        // consoleLog("保存日志");
        $model = & FLEA::getSingleton('Model_Api_Log');
        // consoleLog($row);
        $model->save($row);
        return true;
    }

    /**
     * @desc ：得到签名，暂时不考虑
     * @author jeff 2015/09/21 15:55:03
     * @param 参数类型
     * @return 返回值类型
    */
    private function genSign($params, $token) {
        if (!$token) {
            return false;
        }
        return strtoupper(md5(strtoupper(md5($this->assemble($params))) . $token));
    }

    //设置accesstoken,暂时先不考虑
    protected function set_accesstoken($member_id) {
        if (empty($member_id)) {
            return falase;
        }
        kernel::single("base_session")->start();
        $member_ident = kernel::single("base_session")->sess_id();
        $sess_id = md5($member_ident . 'api' . $member_id);
        kernel::single("base_session")->set_sess_id($sess_id);
        return $sess_id;
    }

    /**
     * @time 2015-07-14
     * @author Mark
     * @param type $accesstoken
     * @param type $member_id 现在不要member_id 2015/7/28 20:40
     * @return boolean
     */
    protected function check_accesstoken($accesstoken) {
        if (empty($accesstoken)) {
            return false;
        }
        $_GET['sess_id'] = $accesstoken;
        kernel::single("base_session")->start();
        $userObject = kernel::single('b2c_user_object');
        $member_id = $userObject->get_member_id();
        return $member_id;
    }


    /**
     * 判断条码类型
     * @param $code
     *
     * @return string
     */
    protected function _checkCodeType($code){
        if( ! strripos($code,'-') >0 ){
            return 'shelf';
        }
        return 'product';
    }


    /**
     * 依据产品条码获取产品信息
     * @param $code
     */
    protected function _getInfoByProCode($code){
        $pos = strripos($code,'-');

        // 订单明细ID
        $order2proId  =  substr($code, 0, $pos);
        // 码单明细卷号
        $number       =  substr($code, $pos+1);

        $product = array(
            'pos' => $pos,
            'ord2proId' => $order2proId,
            'number' => $number
        );
        return $product;
    }

     /**
     * 处理参数信息
     * Time：2017/04/26 09:02:38
     * @author li
    */
    private function parse_rpc_request($request, $token){
        if(!isset($request['method'])){
            $this->error('ERROR_METHOD_NULL','E40011',false);
            return false;
        }

        $sign = $request['sign'];
        unset($request['sign']);
        $self_sign = $this->gen_sign($request, $token);
        if($self_sign != $sign && defined('DEPLOY_MODE') && DEPLOY_MODE == true){
            $this->error('SIGN_ERROR','E40012',false);
            return false;
        }

        //获取method
        $method = $request['method'];
        $array_unset = array('method','version');
        foreach ($variable as $pk) {
            unset($request[$pk]);
        }

        return array($method ,$request);
    }

    /**
     * 签名处理
     * Time：2017/04/26 09:02:38
     * @author li
    */
    private function gen_sign($params, $token){
        $str_key_value = $this->assemble($params);
        return strtoupper(md5(strtoupper(md5($str_key_value)).$token));
    }

    /**
     * 字符串处理
     * Time：2017/04/26 09:02:38
     * @author li
    */
    private function assemble($params)
    {
        if(!is_array($params))  return null;
        ksort($params, SORT_STRING);
        $sign = '';
        foreach($params AS $key=>$val){
            if(is_null($val))   continue;
            if(is_bool($val))   $val = ($val) ? 'true' : 'false';
            $sign .= $key . (is_array($val) ? $this->assemble($val) : $val);
        }
        return $sign;
    }
}