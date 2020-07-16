使用说明：
1，在进销存中调用ec的接口:
            $obj_api = FLEA::getSingleton('Api_Request');
            $r = $obj_api->callApi(array(
                'method'=>'apioubao.erp.response.get_members',
                'params'=>array(),
                'isDebug'=>true//开启调试模式,todo
            ));
            $ret = json_decode($r);
            dump($ret);exit;
    演示地址：
    http://localhost/jxc_oubao/index.php?controller=jichu_client&action=testapi

    注意ConfigRequest.json中的token必须ec的config/certi.php中的$certificate['token']保持一致,否则调用失败

2, 在ec中调用jxc的进口
    /**************************以下代码建议直接copy后改进
    $erp_caller_obj = kernel::service('apioubao.rpc_erp_request');
    $post = array(
        'method'=>'respon.test',
        'params'=>array(0,1,12,21),
        'isDebug'=>true//开启调试模式,
    );
    $result = $erp_caller_obj->call_api_oubao($post);
    echo $result;//如果开启调试模式,这里直接echo，不要再dump了
    // dumpJson($result);//将result中的unicode处理为中文并dump
    return $result;
    **************************************************/
    演示地址如下：
    http://localhost/ec_oubao/index.php/shopadmin/index.php?app=b2c&ctl=admin_member&act=testApi
    或者
    http://localhost/ec_oubao/index.php/shopadmin/#app=b2c&ctl=admin_order&act=createTest

    注意：
    a,必须在config/certi.php中定义如下变量
    $request_api_config = array(
        'url'=>'http://localhost/jxc_oubao/api.php',
        'token'=>'aa',
    );
3, 在进销存中测试ec和进销存自己的接口,访问如下地址即可列出所有待测试的api,相关api必须在配置文件中进行申明后，才会自动显示
    http://localhost/jxc_oubao/apitest.php

4,开发ec api注意
    4.1,所有的ec的api必须在custom/apioubao/api.xml中进行声明，声明后必须cmd update才能生效
    4.2,必须在config/certi.php中定义如下变量
        $request_api_config = array(
            'url'=>'http://localhost/jxc_oubao/api.php',
            'token'=>'aa',//密匙，比如和响应方的密匙一致
        );
    4.3,具体的方法必须写在custom/apioubao/lib/erp/response.php文件下
    4.4,写完后注意使用进销存中的测试工具进行测试,测试前，必须将此接口的方法名和需要的参数定义在进销存的ConfigRequest.json文件中,方便测试程序自动读取

5,开发进销存 api注意
    a,所有的api必须写在lib/app/api/lib目录下,订单相关写在Order.php文件下，仓库相关写在Cangku.php文件下
    b,具体的方法和参数必须在ConfigResponse.json文件中进行定义,
    c,ConfigResponse.json中的token必须和ec中 config/certi.php中的$request_api_config['token']保持一致,否则调用失败
    c,写完后注意使用进销存中的测试工具进行测试