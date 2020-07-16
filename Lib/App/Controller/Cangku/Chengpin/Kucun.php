<?php
/*********************************************************************\
*成品库存
\*********************************************************************/
FLEA::loadClass('TMIS_Controller');
class Controller_Cangku_Chengpin_Kucun extends Tmis_Controller {

    function __construct() {
        $this->_modelExample = & FLEA::getSingleton('Model_Cangku_Chengpin_Kucun');
    }

    function actionPriceReport(){
        $this->authCheck('3-2-12');
        FLEA::loadClass("TMIS_Pager");
        $arr = &TMIS_Pager::getParamArray(array(
//            "dateFrom" => date('Y-m-01'),
//            "dateTo" => date('Y-m-d'),
            "proName"  =>'',
            "proCode" =>"",
            "guige" =>"",
            "color" =>"",
        ));

        //处理库位
        // $strKuwei = join("','",$this->_arrKuwei);
        //找到所有属于成品的库位
        $str="select kuweiName from jichu_kuwei where type='成品'";
        $res=$this->_modelExample->findBySql($str);
        $strKuwei=join("','",array_col_values($res,'kuweiName'));

        $strCon = " and x.kuwei in ('{$strKuwei}') ";

        if($arr['proName']!='') $strCon.=" and proName='{$arr['proName']}'";
        if($arr['guige']!='') $strCon .=" and guige like '%{$arr['guige']}%'";
        if($arr['proCode']!='') $strCon .="and proCode like '%{$arr['proCode']}%'";
        if($arr['color']!='') $strCon .=" and color like '%{$arr['color']}%'";
        if($arr['dateFrom']!='') $strCon.=" and dateFasheng>='{$arr['dateFrom']}'";
        if($arr['dateTo']!='') $strCon.=" and dateFasheng<='{$arr['dateTo']}'";



        $sql="SELECT sum(x.cntFasheng) as cnt, sum(y.price*x.cntFasheng) as money, 
                     y.proCode, y.proName,y.guige,y.color,y.price
              FROM cangku_common_kucun x
              LEFT JOIN jichu_product y ON y.id = x.productId 
              WHERE 1 {$strCon} 
              GROUP BY x.productId
              ORDER BY y.proCode
              ";

        if($_GET['export']==1){
            $rowset = $this->_modelExample->findBySql($sql);
        }else{
            $pager = &new TMIS_Pager($sql);
            $rowset = $pager->findAll();
        }

        $zongji =  $this->getZongji($sql, array('cnt'=>'cntFasheng', 'money'=>'cntFasheng*y.price'));
        $zongji['proCode']= '总计';

        $heji = $this->getHeji($rowset,array('cnt', 'money'),'proCode');

        if($_GET['export']) {
            $heji['proCode'] = "合计";
        }

        $rowset[] = $heji;
        if(!$_GET['export']){
            $rowset[] = $zongji;
        }

        foreach ($rowset as &$row){
            $row['money'] = round($row['money'], 2);
        }


        // 显示信息
        $arrFieldInfo = array(
            "proCode"         => array('text'=>"产品编码",'width'=>'70'),
            'proName'         => array('text'=>'品名','width'=>'180'),
            "guige"           => array('text'=>"规格",'width'=>'180'),
            "color"           => array('text'=>"颜色",'width'=>'60'),
            "price"           => array('text'=>"单价", 'type'=>'Number', 'width'=>'100'),
            'cnt'             => array('text'=>'库存数', 'type'=>'Number'),
            'money'           => array('text'=>'金额', 'type'=>'Number'),
        );

        $smarty = &$this->_getView();
        $smarty->assign('title', '库存价值表');
        $smarty->assign('arr_field_info', $arrFieldInfo);
        $smarty->assign('add_display', 'none');
        $smarty->assign('arr_condition', $arr);
        $smarty->assign('arr_field_value', $rowset);
        if($_GET['export']==1){
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: application/force-download");
            header("Content-Type: application/download");
            header("Content-Disposition: attachment;filename=report.xls");
            header("Content-Transfer-Encoding: binary");
            $smarty->display('Export2Excel2.tpl');
            exit;
        }
        $smarty->assign('fn_export',$this->_url($_GET['action'],$arr+array('export'=>1)));
        $smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action']), $arr));
        $smarty->assign('arr_js_css', $this->makeArrayJsCss(array('grid', 'calendar')));
        $smarty->display('TableList.tpl');
    }
}
