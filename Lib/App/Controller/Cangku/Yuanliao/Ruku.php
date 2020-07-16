<?php
/*********************************************************************\
*  Copyright (c) 1998-2013, TH. All Rights Reserved.
*  Author :Jeff
*  FName  :Ruku.php
*  Time   :2014/05/13 18:31:40
*  Remark :采购入库控制器
\*********************************************************************/
FLEA::loadClass('Controller_Cangku_Ruku');
class Controller_Cangku_Yuanliao_Ruku extends Controller_Cangku_Ruku {
    // var $fldMain;
    // var $headSon;
    // var $rules;//表单元素的验证规则
    // **************************************构造函数 begin********************************
    function __construct() {
        $this->_state = '原料';
        $this->_head = 'CGRKA';
        $this->_kind='采购入库';
        $this->_modelDefault = &FLEA::getSingleton('Model_Cangku_Yuanliao_Ruku');
        $this->_modelExample = &FLEA::getSingleton('Model_Cangku_Yuanliao_Ruku');
        // $this->_modelExample = &FLEA::getSingleton('Model_Cangku_Yuanliao_Ruku');
        // $this->_modelExample = &FLEA::getSingleton('Model_Cangku_Yuanliao_Ruku');
        $this->_modelMain = &FLEA::getSingleton('Model_Cangku_Yuanliao_Ruku');
        $this->_modelSon = &FLEA::getSingleton('Model_Cangku_Yuanliao_Ruku2Product');
        parent::__construct();

    }

    function actionAdd(){
        $this->authCheck('3-1-2');
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
        //找到所有属于成品的库位
        $str="select kuweiName from jichu_kuwei where type='{$this->_state}'";
        $res=$this->_modelExample->findBySql($str);
        $strKuwei=join("','",array_col_values($res,'kuweiName'));

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
                where y.kind in ('采购入库','采购退货','来料入库','调货入库') and y.kuwei in('{$strKuwei}')
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
        //得到总计 notice 总计的sql里面不能有group by
        $zongji = $this->getZongji($sql,array('cnt'=>'x.cnt','money'=>'x.money'));
        $sql .= " group by x.id";
        $sql .= " order by y.rukuDate desc";
        // dump($sql);die;
        // dump($zongji);die;
        if($_GET['export']!=1) {
            $pager = &new TMIS_Pager($sql);
            $rowset = $pager->findAll();
        }else{
            $this->authCheck('100-7');
            $rowset = $this->_modelExample->findBySql($sql);
        }

        foreach($rowset as & $value){
            if($value['kind']!='调货入库'){
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
            }else{
                $value['_edit'] = " <a href='javascript:void(0)' style='color:black' ext:qtip='调货入库数据不能修改'>不可操作</a>";
            }
        }


/*		if (count($rowset) > 0) foreach($rowset as &$value) {
            if (!$value['guozhangId']) {
                    $value['_edit'] = "<a href='" . $this->_url('View', array('id' => $value['rukuId'])) . "' target='_blank' title='$title'>打印</a>";
                    //$tip = "ext:qtip='已过账禁止修改'";
                    if($value['kind']=='采购退货') {
                        $value['_edit'] .= " <a href='".url('Cangku_Yuanliao_Cgth','Edit',array(
                            'id'=>$value['rukuId'],
                            'fromAction' => $_GET['action']
                        ))."'>修改</a>";
                    } elseif($value['kind']=='生产入库') {
                        $value['_edit'] .= " <a href='".url('Cangku_Chengpin_Ruku','Edit',array(
                            'id'=>$value['rukuId'],
                            'fromYuanliao'=>1,
                            'fromAction' => $_GET['action']
                        ))."'>修改</a>";
                    } elseif($value['kind']=='成品初始化') {
                        $value['_edit'] .= " <a href='".url('Cangku_Chengpin_RukuInit','Edit',array(
                            'id'=>$value['rukuId'],
                            'fromAction' => $_GET['action']
                        ))."'>修改</a>";
                    } else {
                        $value['_edit'] .= " <a href='".url('Cangku_Yuanliao_Ruku','Edit',array(
                            'id'=>$value['rukuId'],
                            'fromAction' => $_GET['action']
                        ))."'>修改</a>";
                    }
                    $value['_edit'] .= " ".$this->getRemoveHtml($value['rukuId']);
                    //码单导出
                    if($value['kind']=='生产入库'){
                        $sql="select id from cangku_madan where ruku2proId='{$value['id']}' limit 0,1";
                        $temp=$this->_modelExample->findBySql($sql);
                        $color='';
                        $title='';
                        if($temp[0]['id']>0){
                            //有码单入库信息
                            $value['_edit'] .=" <a style='color:{$color}' href='".url('Cangku_Chengpin_Madan','SelexportRuku',array('id'=>$value['id']))."' target='_blank' title='{$title}'>码单导出</a>";
                        }
                    }

            }else{
                //码单导出
                if($value['kind']=='生产入库'){
                    $sql="select id from cangku_madan where ruku2proId='{$value['id']}' limit 0,1";
                    $temp=$this->_modelExample->findBySql($sql);
                    $color='';
                    $title='';
                    if($temp[0]['id']>0){
                        //有码单入库信息
                        $value['_edit'] .=" <a style='color:{$color}' href='".url('Cangku_Chengpin_Madan','SelexportRuku',array('id'=>$value['id']))."' target='_blank' title='{$title}'>码单导出</a>";
                    }
                }
                $value['_edit'] .= " ".$this->getRemoveHtml($value['rukuId']);
                $value['_edit'] = "<a href='" . $this->_url('View', array('id' => $value['rukuId'])) . "' target='_blank' title='$title'>打印</a>";
                $tip = "ext:qtip='已过账'";
                $value['_edit'] .= " <a href='javascript:void(0)' style='color:black' $tip>修改</a>";
                $value['_edit'] .= " <a $tip  >删除</a>";
            }
          if($value['cnt']<0) $value['_bgColor'] = 'pink';
        }*/

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


        $title = '色坯纱仓库入库查询';
        $smarty = &$this->_getView();
        $smarty->assign('title', $title);
        $smarty->assign('arr_field_info', $arrFieldInfo);
        $smarty->assign('add_display', $isShowAdd?'display':'none');
        $smarty->assign('arr_condition', $serachArea);
        $smarty->assign('arr_field_value', $rowset);
        $msg = "<font color='red'>数量总计:{$zongji['cnt']},金额总计:{$zongji['money']}</font>";
        $smarty->assign('arr_js_css', $this->makeArrayJsCss(array('grid', 'calendar')));
        $this->_beforeDisplayRight($smarty);

        $smarty->assign('fn_export',$this->_url($_GET['action'],array('export'=>1)+$serachArea));
        if($_GET['export']!=1) {
            $smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action']), $serachArea).$msg);
            $smarty->display('TableList.tpl');
            exit;
        }
        $filename = $title.'-'.time();
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/download");
        header("Content-Disposition: attachment;filename=".$filename.".xls");
        header("Content-Transfer-Encoding: binary");
        $smarty->display('Export2Excel2.tpl');
    }
}