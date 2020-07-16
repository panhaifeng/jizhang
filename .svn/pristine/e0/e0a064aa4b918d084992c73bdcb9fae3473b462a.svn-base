<?php
FLEA::loadClass('Controller_Trade_Order');
class Controller_Trade_OrderLocalDevelop extends Controller_Trade_Order {
    var $title = "订单管理-登记";
    var $fldMain;
    //表单元素的验证规则
    // **************************************构造函数 begin********************************
    function __construct() {
        $this->_modelDefault = &FLEA::getSingleton('Model_Trade_Order');
        $this->_modelExample = &FLEA::getSingleton('Model_Trade_Order');
        $this->_modelOrder2pro = &FLEA::getSingleton('Model_Trade_Order2Product');
        $this->jichu_employ= &FLEA::getSingleton('Model_Jichu_Employ');
        $this->_modelPlanPj = &FLEA::getSingleton('Model_Shengchan_PiJian_Plan');
        // 订单类型对应中文以及前缀
        $this->orderTypeMap = array(
            '1' => array('cnName'=>'本厂开发','head'=>'KF'),
        );
        // 定义模板中的主表字段
        $this->fldMain = array('orderCode' => array('title' => '订单编号', "type" => "text", 'readonly' => true, 'value' => '系统自动生成'),
            'orderDate' => array('title' => '订单日期', 'type' => 'calendar', 'value' => date('Y-m-d')),

            'finalDate' => array('title' => '最终交期', 'type' => 'calendar', 'value' => date('Y-m-d')),
            'xiaoshouName' => array('title' => '销售助理', 'type' => 'text', 'value' => $_SESSION['REALNAME']),
            'traderId' => array('title' => '业务负责', 'type' => 'select', 'value' => '','options'=>$this->jichu_employ->getSelect(),'disabled'=>true), 

            'clientId' => array('title' => '客户名称', 'type' => 'clientpopup', 'clientName' => ''),
            //'clientOrder' => array('title' => '客户单号', 'type' => 'text', 'value' => ''),
            'xsType' => array('title' => '内/外销', 'type' => 'select', 'value' => '内销', 'options' => array(
                    array('text' => '内销', 'value' => '内销'),
                    array('text' => '外销', 'value' => '外销'),
                    )),

            'overflow' => array('title' => '溢短装', 'type' => 'text', 'value' => '', 'addonEnd' => '%'),
            //'warpShrink' => array('title' => '经向缩率', 'type' => 'text', 'value' => '', 'addonEnd' => '%'),
            //'weftShrink' => array('title' => '纬向缩率', 'type' => 'text', 'value' => '', 'addonEnd' => '%'),
            //'packing' => array('title' => '包装要求', 'type' => 'text', 'value' => ''),
            //'checking' => array('title' => '检验要求', 'type' => 'text', 'value' => ''),
            //'moneyDayang' => array('title' => '打样收费', 'type' => 'text', 'value' => ''),
            'bizhong' => array('title' => '交易币种', 'type' => 'select', 'value' => 'RMB', 'options' => array(
                    array('text' => 'RMB', 'value' => 'RMB'),
                    array('text' => 'USD', 'value' => 'USD'),
                    )),
            'orderType'=>array('title' => '订单类型','type' => 'select','value' =>'1','options'=>array(
                    array('text'=>'本厂开发','value'=>'1'),
                )),
            'addJichu' => array(
                'title' => '布档案添加',
                'type' => 'popupForAdd',
                'name' => 'addJichu',
                // 'url'=>url('Jichu_Client','Popup'),
                'urlAdd'=>url('Jichu_Chanpin','Add',array('type'=>'Popup','localDvlp'=>'1')),
                'textFld'=>'proCode',
                'hiddenFld'=>'id',
                'dialogWidth'=>'1050',
                // 'inTable'=>true,
            ),
            // 定义了name以后，就不会以memo作为input的id了
            // 'memo'=>array('title'=>'订单备注','type'=>'textarea','disabled'=>true,'name'=>'orderMemo'),
            // 下面为隐藏字段
            'orderId' => array('type' => 'hidden', 'value' => ''),
            );
        // /从表表头信息
        // /type为控件类型,在自定义模板控件
        // /title为表头
        // /name为控件名
        // /bt开头的type都在webcontrolsExt中写的,如果有新的控件，也需要增加
        $this->headSon = array(
            '_edit' => array('type' => 'btBtnRemove', "title" => '+5行', 'name' => '_edit[]'),
            'productId' => array('type' => 'btproductpopup', "title" => '产品选择', 'name' => 'productId[]'),
            'proName' => array('type' => 'bttext', "title" => '品名', 'name' => 'proName[]', 'readonly' => true,'style' => 'width:150px;','showTitle'=>true),
            'guige' => array('type' => 'bttext', "title" => '规格', 'name' => 'guige[]', 'style' => 'width:270px','readonly' => true),
            'color' => array('type' => 'bttext', "title" => '颜色', 'name' => 'color[]','readonly' => true),
            'menfu' => array('type' => 'bttext', "title" => '门幅', 'name' => 'menfu[]'),
            'kezhong' => array('type' => 'bttext', "title" => '克重', 'name' => 'kezhong[]'),
            'zhenshu' => array('type' => 'bttext', "title" => '针数', 'name' => 'zhenshu[]'),
            'cunshu' => array('type' => 'bttext', "title" => '寸数', 'name' => 'cunshu[]'),
            'xiadanXianchang' => array('type' => 'bttext', "title" => '下单线长', 'name' => 'xiadanXianchang[]'),
            'cntJian' => array('type' => 'bttext', "title" => '件数', 'name' => 'cntJian[]'),
            // 'ratio' => array('type' => 'bttext', "title" => '比例%', 'name' => 'ratio[]'),
            'cntYaohuo' => array('type' => 'bttext', "title" => '数量', 'name' => 'cntYaohuo[]'),
            'danjia' => array('type' => 'bttext', "title" => '单价', 'name' => 'danjia[]'),
            'unit' => array('type' => 'btselect', "title" => '单位', 'name' => 'unit[]', 'value'=>'公斤','options' =>array(
                    array('text'=>'公斤','value'=>'公斤'),
                    array('text'=>'米','value'=>'米'),
                    array('text'=>'码','value'=>'码'),
                    array('text'=>'磅','value'=>'磅'),
                    array('text'=>'条','value'=>'条'),
            )),
            'money' => array('type' => 'bttext', "title" => '金额', 'name' => 'money[]', 'readonly' => true),
            // 'supplierId' => array('type' => 'BtSupplierSelect', "title" => '原料供应商', 'name' => 'supplierId[]'),
            'memo' => array('type' => 'bttext', "title" => '备注', 'name' => 'memo[]'),
            // ***************如何处理hidden?
            'id' => array('type' => 'bthidden', 'name' => 'id[]'),
            //'shenheid' => array('type' => 'bthidden', 'name' => 'shenheid[]'),
            );
        // 表单元素的验证规则定义
        $this->rules = array(
            'orderCode' => 'required',
            'orderDate' => 'required',
            'orderType' => 'required',
            'clientId' => 'required',
            'traderId' => 'required'
            );
    }

        //销售出库要选择相关订单，所有需要重新定义onorderSel函数
    function actionAdd($Arr) {
        $this->authCheck('1-10');        
        parent::actionAdd();
    }
    
   

   
}
?>