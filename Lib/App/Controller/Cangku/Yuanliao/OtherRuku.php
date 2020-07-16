<?php
/*********************************************************************\
*  Copyright (c) 1998-2013, TH. All Rights Reserved.
*  Author :Jeff
*  FName  :Ruku.php
*  Time   :2014/05/13 18:31:40
*  Remark :采购入库控制器
\*********************************************************************/
FLEA::loadClass('Controller_Cangku_Ruku');
class Controller_Cangku_Yuanliao_OtherRuku extends Controller_Cangku_Ruku {
    // var $fldMain;
    // var $headSon;
    // var $rules;//表单元素的验证规则
    // **************************************构造函数 begin********************************
    function __construct() {
        $this->_state = '原料';
        $this->_head = 'LLRKA';
        $this->_kind='来料入库';
        $this->_modelDefault = &FLEA::getSingleton('Model_Cangku_Yuanliao_Ruku');
        $this->_modelExample = &FLEA::getSingleton('Model_Cangku_Yuanliao_Ruku');
        $this->_modelMain = &FLEA::getSingleton('Model_Cangku_Yuanliao_Ruku');
        $this->_modelSon = &FLEA::getSingleton('Model_Cangku_Yuanliao_Ruku2Product');
        parent::__construct();

        $this->fldMain = array(
            // /*******2个一行******
            'rukuDate' => array('title' => '入库日期', "type" => "calendar", 'value' => date('Y-m-d')),
            // 入库单号，自动生成
            'rukuCode' => array('title' => '入库单号', 'type' => 'text', 'readonly' => true, 'value' => ''),
            // /*******2个一行******
            // 'supplierpopup需要显示供应商名称，所以需要定义supplierName属性,value属性作为supplierId用
            // 'supplierId' => array('title' => '供应商', 'type' => 'selectgys', 'value' => '', 'model' => 'Model_Jichu_Supplier'),
            'supplierId' => array(
                    'type' => 'popup',
                    "title" => '客户',
                    'name' => 'supplierId',
                    'url'=>url('Jichu_supplier','Popup'),
                    'textFld'=>'compName',
                    'hiddenFld'=>'id',
            ),
            'songhuoCode' => array('title' => '送货单号', 'type' => 'text', 'value' => ''),
            'kind' => array('title' => '入库类别', 'type' => 'text', 'value' => $this->_kind, 'readonly' => true),
            'kuwei' => array('title' => '库位选择', 'type' => 'kuwei', 'value' => '','kwType'=>$this->_state),
            // 'state' => array('title' => '状态', 'type' => 'text', 'value' =>$this->_state, 'readonly'=>true),
            // /*******2个一行******
            // 定义了name以后，就不会以memo作为input的id了
            'memo' => array('title' => '备注', 'type' => 'textarea', 'disabled' => true, 'name' => 'rukuMemo'),
            // 下面为隐藏字段
            'id' => array('type'=>'hidden', 'value'=>$_GET['id'],'name'=>'rukuId'),
            // 'isGuozhang' => array('type' => 'hidden', 'value' => ''),
        );
        // /从表表头信息
        // /type为控件类型,在自定义模板控件
        // /title为表头
        // /name为控件名
        // /bt开头的type都在webcontrolsExt中写的,如果有新的控件，也需要增加
        $this->headSon = array(
            '_edit' => array('type' => 'btBtnCopy', "title" => '+5行', 'name' => '_edit[]'),
            // 'productId' => array('type' => 'btProductpopup', "title" => '产品选择', 'name' => 'productId[]'),
            'productId' => array(
                'title' => '物料编码',
                'type' => 'BtPopup',
                'value' => '',
                'name'=>'productId[]',
                'text'=>'选择入库',
                'url'=>url('jichu_product','popup'),//弹出地址,回调函数在sonTpl中进行定义,主要是对缓存中的关联数组进行重写
                'textFld'=>'proCode',//显示在text中的字段
                'hiddenFld'=>'id',//显示在hidden控件中的字段
                'inTable'=>1,
            ),
            'proName' => array('type' => 'bttext', "title" => '品名', 'name' => 'proName[]', 'readonly' => true),
            'guige' => array('type' => 'bttext', "title" => '规格', 'name' => 'guige[]', 'readonly' => true),
            'color' => array('type' => 'bttext', "title" => '颜色', 'name' => 'color[]', 'readonly' => true),
            // 'dengji' => array('type' => 'bttext', "title" => '规格', 'name' => 'guige[]', 'readonly' => true),
            // 'unit'=>array('type'=>'bttext',"title"=>'单位','name'=>'unit[]','readonly'=>true),
            'shuhao' => array('type' => 'bttext', 'title' => '束号', 'name' => 'shuhao[]'),
            'ganghao'=>array('type'=>'bttext','title'=>'缸号(批号)','name'=>'ganghao[]'),
            'cntJian'=>array('type'=>'bttext',"title"=>'件数','name'=>'cntJian[]'),
            'cnt' => array('type' => 'bttext', "title" => '数量(kg)', 'name' => 'cnt[]'),
            // 'danjia'=>array('type'=>'bttext',"title"=>'单价','name'=>'danjia[]'),
            // 'money'=>array('type'=>'bttext',"title"=>'金额(元)','name'=>'money[]'),
            // 'kuweiId'=>array('type'=>'btkuweiselect','title'=>'库位选择','name'=>'kuweiId[]'),
            'memo' => array('type' => 'bttext', "title" => '备注', 'name' => 'memo[]'),
            // ***************如何处理hidden?
            'id' => array('type' => 'bthidden', 'name' => 'id[]'),
            );
        // 表单元素的验证规则定义
        $this->rules = array('rukuDate' => 'required',
            // 'orderDate'=>'required',
            'supplierId' => 'required',
            'kuwei' => 'required',
            // 'traderId'=>'required'
        );

    }

    function actionAdd(){
        $this->authCheck('3-1-15');
        parent::actionAdd();
    }


function actionRight(){
        $this->authCheck('3-1-3');
        FLEA::loadClass('TMIS_Pager');
        // /构造搜索区域的搜索类型
        $serachArea = TMIS_Pager::getParamArray(array(
            'dateFrom' => date("Y-m-01"),
            'dateTo' => date("Y-m-d"),
            'supplierId' => '',
            'key' => '',
            'ganghao'=>'',
        ));
        $sql = "select y.rukuCode,y.kuwei,y.rukuDate,y.supplierId,y.memo as rukuMemo,y.kind,y.songhuoCode,
                       x.id,x.shuhao,x.pihao,x.rukuId,x.productId,x.cnt,x.danjia,x.money,x.memo,x.cntJian,x.cntM,x.ganghao,x.cntJian,
                       b.proCode,b.proName,b.guige,b.color,b.kind as proKind,b.dengji,
                       a.compName as compName,
                       g.id as guozhangId,g.ruku2proId
                from cangku_common_ruku y
                left join cangku_common_ruku2product x on y.id=x.rukuId
                left join jichu_supplier a on y.supplierId=a.id
                left join jichu_product b on x.productId=b.id
                left join caiwu_yf_guozhang g on g.ruku2proId=x.id and g.kind in ('采购入库','采购退货','来料入库') and y.kuwei not in('成品仓库','其他仓库','疵品库位','OCS成品仓库','疵品库位')
                where y.kind in ('采购入库','采购退货','来料入库') and y.kuwei not in('成品仓库','成品仓库','其他仓库','疵品库位','OCS成品仓库','疵品库位')
        ";
        $sql .= " and y.rukuDate >= '{$serachArea['dateFrom']}' and y.rukuDate<='{$serachArea['dateTo']}'";
        if ($serachArea['key'] != '') $sql .= " and (b.proName like '%{$serachArea['key']}%'
                                            or b.proCode like '%{$serachArea['key']}%'
                                            or b.guige like '%{$serachArea['key']}%'
                                            or y.rukuCode like '%{$serachArea['key']}%')";
        if ($serachArea['rukuCode'] != '') $sql .= " and y.rukuCode like '%{$serachArea['rukuCode']}%'";
        if ($serachArea['supplierId'] != '') $sql .= " and y.supplierId = '{$serachArea['supplierId']}'";
        if($serachArea['ganghao'] != ''){
            $sql .=" and x.ganghao like '%{$serachArea['ganghao']}%'";
        }
        $sql .= " group by x.id";
        $sql .= " order by y.rukuDate desc";
        //得到总计
        //dump($sql);die;
        $zongji = $this->getZongji($sql,array('cnt'=>'x.cnt','money'=>'x.money'));
        $pager = &new TMIS_Pager($sql);
        $rowset = $pager->findAll();
        //dump($rowset);die;

         foreach($rowset as & $value){
            if(!$value['guozhangId']){
                $value['_edit'] = "<a href='" . $this->_url('View', array('id' => $value['rukuId'])) . "' target='_blank' title='$title'>打印</a>";
                if($value['kind']=='来料入库') {
                        $value['_edit'] .= " <a href='".url('Cangku_Yuanliao_OtherRuku','Edit',array(
                            'id'=>$value['rukuId'],
                            'fromAction' => $_GET['action']
                        ))."'>修改</a>";
                        $value['client']=$value['compName'];
                        $value['compName']='';
                }else{
                    $value['_edit'] .= " <a href='".url('Cangku_Yuanliao_Ruku','Edit',array(
                                'id'=>$value['rukuId'],
                                'fromAction' => $_GET['action']
                            ))."'>修改</a>";
                }
                $value['_edit'] .= " ".$this->getRemoveHtml($value['rukuId']);
            }else{
                $value['_edit'] = "<a href='" . $this->_url('View', array('id' => $value['rukuId'])) . "' target='_blank' title='$title'>打印</a>";
                $tip = "ext:qtip='已过账'";
                $value['_edit'] .= " <a href='javascript:void(0)' style='color:black' $tip>修改</a>";
                $value['_edit'] .= " <a $tip  >删除</a>";
            }
        }


        // 合计行
        $heji = $this->getHeji($rowset, array('cnt'), '_edit');
        $rowset[] = $heji;
        // 左边信息
        $arrFieldInfo = array(
            '_edit'=>array("text"=>'操作','width'=>110),
            "rukuDate" => "入库日期",
            // 'state' => '状态',
            // 'proKind' => '种类',
            'proName' => '品名',
            'guige' => '规格',
            "dengji" => "等级",
            'color' => '颜色',
            'ganghao'=>'缸号(批号)',
            "compName" => "供应商",
            "client" => "客户",
            'memo' => '备注',
            'shuhao'=>'束号',
            // 'cntJian' => '件数',
            'cnt' => '数量(kg)',
            'danjia' => '单价',
            'money' => '金额',
            'cntJian' => '件数',
            'songhuoCode' => '送货单号',
            'rukuCode' => array("text"=>'入库单号','width'=>150),
            "kind" => "类别",
            'kuwei' => '库位',
            'proCode' => '产品编码'
        );
        // array_unshift($arrFieldInfo,);
        // dump($arrFieldInfo);exit;


        $smarty = &$this->_getView();
        $smarty->assign('title', '订单查询');
        $smarty->assign('arr_field_info', $arrFieldInfo);
        $smarty->assign('add_display', $isShowAdd?'display':'none');
        $smarty->assign('arr_condition', $serachArea);
        $smarty->assign('arr_field_value', $rowset);
        $msg = "<font color='red'>数量总计:{$zongji['cnt']},金额总计:{$zongji['money']}</font>";
        $smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action']), $serachArea).$msg);
        $smarty->assign('arr_js_css', $this->makeArrayJsCss(array('grid', 'calendar')));
        $this->_beforeDisplayRight($smarty);
        $smarty->display('TableList.tpl');
        // $smarty->display('TblListMore.tpl');
    }
}