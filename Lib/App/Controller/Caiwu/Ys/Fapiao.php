<?php
FLEA::loadClass('TMIS_Controller');
////////////////////////////////////////控制器名称
class Controller_Caiwu_Ys_Fapiao extends Tmis_Controller {
    var $_modelExample;
    var $_tplEdit='Caiwu/Ys/fapiaEdit.tpl';
    function __construct() {
        $this->_modelExample = & FLEA::getSingleton('Model_Caiwu_Ys_Fapiao');

        //搭建过账界面
        $this->fldMain = array(
            'creater' => array('type' => 'text', 'value' => $_SESSION['REALNAME'],'title'=>'制单人','readonly'=>true),
            'fapiaoCode' => array('type' => 'text', 'value' => '','title'=>'发票号'),
            'fapiaoDate' => array('title' => '发票日期', "type" => "calendar", 'value' => date('Y-m-d')),
//            'clientId' => array('title' => '客户名称', 'type' => 'clientpopup', 'clientName' => '', ''),
            'clientId' => array(
                'title' => '客户名称',
                'type' => 'popup',
                'url'=>url('Jichu_Client','PopupWithHead'),
                'textFld'=>'clientName',
                'hiddenFld'=>'id',
                'inTable'=>true,
            ),
            'head' => array('title' => '发票抬头', 'type' => 'text', 'value'=>''),
            'money' => array('title' => '发票金额', 'type' => 'text', 'value' => ''),
            'bizhong' => array('title' => '币种', 'type' => 'select', 'value' => 'RMB', 'options' => array(
                    array('text' => 'RMB', 'value' => 'RMB'),
                    array('text' => 'USD', 'value' => 'USD'),
                    )),
            'huilv' => array('title' => '汇率', 'type' => 'text', 'value' => '1'),
            'memo' => array('title' => '备注', 'type' => 'textarea', 'value' => ''),
            'id' => array('type' => 'hidden', 'value' => ''),
        );

        // 表单元素的验证规则定义
        $this->rules = array(
            'fapiaoCode' => 'required repeat',
            'fapiaoDate' => 'required',
            'clientId' => 'required',
            'head' => 'required',
            'money' => 'required number'
        );
    }

    function actionRight() {
        // $this->authCheck('4-2-4');
        FLEA::loadclass('TMIS_Pager');
        $arr=TMIS_Pager::getParamArray(array(
            'dateFrom'=>date('Y-m-d',mktime(0,0,0,date('m'),date('d')-30,date('Y'))),
            'dateTo'=>date('Y-m-d'),
            'clientId'=>'',
            'key'=>'',
            'no_edit'=>'',
        ));
        $sql="select x.*,a.compName from caiwu_ar_fapiao x
            left join jichu_client a on a.id=x.clientId
            where 1";
        if($arr['clientId']!='')$sql.=" and x.clientId='{$arr['clientId']}'";
        if($arr['dateFrom']!=''){
            $sql.=" and x.fapiaoDate >= '{$arr['dateFrom']}'";
        }
        if($arr['dateTo']!=''){
            $sql.=" and x.fapiaoDate <='{$arr['dateTo']}'";
        }

        if($arr['key']!=''){
            $sql.=" and x.fapiaoCode like '%{$arr['key']}%'";
        }
        $sql.=" order by x.id desc";
        $page=& new TMIS_Pager($sql);
        $rowset=$page->findAll();
        //dump($rowset);exit;
        if(count($rowset)>0)foreach($rowset as & $v) {
                $v['_edit'].=$this->getEditHtml($v['id']).'&nbsp;&nbsp;'.$this->getRemoveHtml($v['id']);

                //折合人民币
                $v['moneyRmb']=round($v['money']*$v['huilv'],2);
        }

        $rowset[] = $this->getHeji($rowset, array('money','moneyRmb'), $_GET['no_edit']==1?'fapiaoCode':'_edit');

        $arr_field_info=array(
            '_edit'=>'操作',
            'fapiaoCode'=>array('text'=>'发票编码','width'=>100),
            'fapiaoDate'=>'收票日期',
            'compName'=>'客户',
            'head'   => '发票抬头',
            'money'=>'金额',
            'moneyRmb'=>'金额(RMB)',
            'bizhong'=>'币种',
            'huilv'=>'汇率',
            'memo'=>'备注',
        );
        $smarty=& $this->_getView();
        $smarty->assign('arr_condition',$arr);
        $smarty->assign('add_display','none');
        $smarty->assign('arr_field_info',$arr_field_info);
        $smarty->assign('arr_field_value',$rowset);
        $smarty->assign('page_info',$page->getNavBar($this->_url($_GET['action'],$arr)).$note);
        $smarty->display('TblList.tpl');
    }


    function actionSave() {
        $arr=array();
        foreach($this->fldMain as $key=>&$v) {
            $arr[$key] = $_POST[$key];
        }

        $arr['huilv']=empty($arr['huilv'])?1:$arr['huilv'];
// 		dump($arr);exit;
        $id=$this->_modelExample->save($arr);
        js_alert(null,'window.parent.showMsg("操作成功");',$this->_url($_POST['fromAction']==''?'add':$_POST['fromAction']));

    }

    function actionAdd() {
        $smarty = &$this->_getView();
        $smarty->assign('fldMain', $this->fldMain);
        $smarty->assign('rules', $this->rules);
        $smarty->assign('title', '发票信息编辑');
        $smarty->assign('sonTpl', 'Caiwu/clientHead.tpl');
        $smarty->display('Main/A1.tpl');
    }

    function actionEdit() {
        $row = $this->_modelExample->find(array('id' => $_GET['id']));

        $this->fldMain = $this->getValueFromRow($this->fldMain, $row);
        $this->fldMain['clientId']['clientName']=$row['Client']['compName'];
        $this->fldMain['clientId']['text'] = $row['Client']['compName'];
        // dump($row);exit;
        $smarty = &$this->_getView();
        $smarty->assign('fldMain', $this->fldMain);
        $smarty->assign('rules', $this->rules);
        $smarty->assign('title', '发票信息编辑');
        $smarty->assign('aRow', $row);
        $smarty->assign('sonTpl', 'Caiwu/clientHead.tpl');
        $smarty->display('Main/A1.tpl');
    }

}
?>