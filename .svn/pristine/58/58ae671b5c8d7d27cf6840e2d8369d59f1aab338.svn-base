<?php
FLEA::loadClass('Tmis_Controller');
class Controller_Caiwu_Ys_Guozhang extends Tmis_Controller {
    var $_tplEdit = 'Caiwu/Ys/GuozhangEdit.tpl';
    function __construct() {
        $this->_modelExample = & FLEA::getSingleton('Model_Caiwu_Ys_Guozhang');
        $this->_modelChukuProduct = & FLEA::getSingleton('Model_Cangku_Chuku2Product');
        //搭建过账界面
        $this->fldMain = array(
            'guozhangDate' => array('title' => '过账日期', "type" => "calendar", 'value' => date('Y-m-d')),
            // 'btnRukuYuanliao' => array('title' => '选择入库', 'type' => 'BtnRukuYuanliao', 'value' => ''),
            'chuku2proId' => array(
                'title' => '选择出库',
                'type' => 'popup',
                'value' => '',
                'name'=>'chuku2proId',
                'text'=>'',
                'url'=>url('Cangku_Chuku','PopupOnGuozhang'),
                'jsTpl'=>'Caiwu/Ys/jsGuozhang.tpl',//需要载入的模板文件，这个文件中不需要使用{literal},因为是通过file_get_content获得的
                'onSelFunc'=>'onSelChuku',//选中后需要执行的回调函数名,需要在jsTpl中书写
                'textFld'=>'chukuCode',//显示在text中的字段
                'hiddenFld'=>'id',//显示在hidden控件中的字段
            ),
            'orderCode' => array('title' => '订单编号', 'type' => 'text', 'value' => '','readonly'=>true),
            'clientId' => array('title' => '客户名称', 'type' => 'clientpopup', 'clientName' => ''),
            // 'proName' => array('title' => '品名', 'type' => 'text', 'value' => '','readonly'=>true),
            // 'guige' => array('title' => '规格', 'type' => 'text', 'value' => '','readonly'=>true),
            'qitaMemo' => array('title' => '描述', 'type' => 'text', 'value' => '','readonly'=>true),
            'ganghao' => array('title' => '缸号', 'type' => 'text', 'value' => '','readonly'=>true),
            // 'kuweiName' => array('title' => '库位', 'type' => 'text', 'value' => '','readonly'=>true),
            'chukuDate' => array('title' => '出库日期', 'type' => 'text', 'value' => '','readonly'=>true),
            'cntOrg' => array('title' => '数量', 'type' => 'text', 'value' => '','readonly'=>true),
            'cntJian' => array('title' => '件数', 'type' => 'text', 'value' => '','readonly'=>true),
            'songhuoCode' => array('title' => '送货单号', 'type' => 'text', 'value' => ''),
            'unit'=>array('type'=>'select','title'=>'单位','name'=>'unit','options'=>array(
                array('text'=>'公斤','value'=>'公斤'),
                array('text'=>'米','value'=>'米'),
                array('text'=>'码','value'=>'码'),
                array('text'=>'只','value'=>'只'),
                )),
            'danjia' => array('title' => '单价', 'type' => 'text', 'value' => ''),
            'money' => array('title' => '应收金额', 'type' => 'text', 'value' => '','addonEnd'=>'元'),
            'bizhong' => array('title' => '币种', 'type' => 'select', 'value' => 'RMB', 'options' => array(
                    array('text' => 'RMB', 'value' => 'RMB'),
                    array('text' => 'USD', 'value' => 'USD'),
                    )),
            'huilv' => array('title' => '汇率', 'type' => 'text', 'value' => '1'),
            'memo' => array('title' => '备注', 'type' => 'textarea', 'value' => ''),
            'id' => array('type' => 'hidden', 'value' => ''),
            'creater' => array('type' => 'hidden', 'value' => $_SESSION['REALNAME']),
            'kind' => array('type' => 'hidden', 'value' => ''),
            'productId' => array('type' => 'hidden', 'value' => ''),
            'chukuId' => array('type' => 'hidden', 'value' => ''),
            'orderId' => array('type' => 'hidden', 'value' => ''),
            'ord2proId' => array('type' => 'hidden', 'value' => ''),
            'gzType' => array('type' => 'hidden', 'value' => ''),
        );

        
        // 表单元素的验证规则定义
        $this->rules = array(
            'guozhangDate' => 'required',
            'clientId' => 'required',
            'money' => 'required number'
        );
    }

    function actionRight() {
        // $this->authCheck('4-2-2');
        $title = '应收款查询';
        $tpl = 'TblList.tpl';
        FLEA::loadClass('TMIS_Pager');
        $arr = TMIS_Pager::getParamArray(array(
            'dateFrom'=>date('Y-m-d',mktime(0,0,0,date('m')-1,date('d'),date('Y'))),
            'dateTo'=>date('Y-m-d'),
            'clientId'=>'',
            'orderCode'=>'',
            'key'=>'',
            'proName'=>'',
            'ganghao'=>'',
            'gzType' =>'',
            'guigeDesc'=>'',
            'orderId'=>'',
            'no_edit'=>'',
            'no_time'=>'',
        ));
        $sql="SELECT x.*,z.compName,y.orderCode,b.guige as guigeDesc,b.proName,ck.chukuCode,ck2.ganghao
            from caiwu_ar_guozhang x
            left join trade_order y on x.orderId=y.id
            left join trade_order2product a on a.id=x.ord2proId
            left join jichu_client z on z.id=x.clientId
            left join jichu_product b on b.id=x.productId
            left join cangku_common_chuku ck on ck.id=x.chukuId
            left join cangku_common_chuku2product ck2 on ck2.id=x.chuku2proId
            where 1";
        if($arr['orderId']>0){
            $arr['dateFrom']='';
            $arr['dateTo']='';
            $sql.=" and x.orderId='{$arr['orderId']}'";
        }
        if($arr['no_time']!=''){
            $arr['dateFrom']='';
            $arr['dateTo']='';
        }

        $sql.=" and guozhangDate >='{$arr['dateFrom']}' and guozhangDate<='{$arr['dateTo']}'";
        if($arr['clientId']!='')$sql.=" and x.clientId='{$arr['clientId']}'";
        if($arr['orderCode']!='')$sql.=" and y.orderCode like '%{$arr['orderCode']}%' ";
        if($arr['key']!='')$sql.=" and ck.chukuCode like '%{$arr['key']}%' ";
        if($arr['product']!='')$sql.=" and a.product like '%{$arr['product']}%'";
        if($arr['guige']!='')$sql.=" and a.guige like '%{$arr['guige']}%'";
        if ($arr['guigeDesc'] != '')$sql.=" and b.guige like '%{$arr['guigeDesc']}%'";
        if ($arr['proName'] != '') $sql.=" and b.proName like '%{$arr['proName']}%'";
        if ($arr['ganghao'] != '') $sql.=" and ck2.ganghao like '%{$arr['ganghao']}%'";
        if ($arr['gzType'] != '' && $arr['gzType'] != '2') $sql.=" and x.gzType = '{$arr['gzType']}'";
        $sql.=" order by guozhangDate desc";
        // dump($sql);exit;
        if($_GET['export']!=1) {
            $pager = &new TMIS_Pager($sql);
            $rowset = $pager->findAll();
        }else{
            $this->authCheck('100-10');
            $rowset = $this->_modelExample->findBySql($sql);
        }
        foreach($rowset as & $v) {
            // 编辑入口
            $editUrl = $v['gzType']>0?'EditOther':'Edit';
            $v['_edit']= "<a href='".$this->_url($editUrl,array(
                'id'=>$v['id']
            ))."' title='过账编辑'>修改</a>". '&nbsp;&nbsp;' . $this->getRemoveHtml($v['id']);

            //核销的情况下不能修改删除
            if($v['moneyYishou']>0 || $v['moneyFapiao']>0)$v['_edit']="<span title='已核销，禁止操作'>修改&nbsp;&nbsp;删除</span>";

            $v['money']=round($v['money'],2);

            //折合人民币
            $v['moneyRmb']=round($v['money']*$v['huilv'],2);

            // 过账类型
            $v['gzType'] = $v['gzType']>0?'其他过账':'应收过账';
        }
        $rowset[] = $this->getHeji($rowset, array('cnt','money','moneyRmb'), $_GET['no_edit']==1?'compName':'_edit');
        // 总计信息
        $zongji = $this->getZongji($sql, array('cnt'=>'x.cnt', 'money'=>'x.money', 'moneyRmb'=>'x.money*x.huilv'));
        $zongji['moneyRmb'] = round($zongji['moneyRmb'], 2);
        $htmlPageMessage = "&nbsp;&nbsp;<font color='red'> 总计信息：</font>"
                         . " &nbsp;数量:{$zongji['cnt']}"
                         . " &nbsp;应收金额:{$zongji['money']}"
                         . " &nbsp;金额(RMB):{$zongji['moneyRmb']}";

        // dump($rowset);exit;
        $arrField = array(
            "_edit"        =>'操作',
            // 'id'        =>'过账id',
            // 'chukuId'   =>'出库Id',
            "gzType"       =>'过账类型',
            "compName"     =>'客户',
            "guozhangDate" =>'日期',
            'chukuCode'    =>'出库单号',
            "orderCode"    =>'订单编号',
            // "product"   =>$this->getManuCodeName(),
            // "guigeDesc" =>'规格',
            // "proName"   =>array('text'=>'品名','width'=>100),
            "qitaMemo"     =>array('text'=>'描述','width'=>160),
            // 'kuweiName' =>'库位',
            'ganghao'      =>'缸号',
            "cnt"          =>array('text'=>'数量','width'=>70),
            "cntJian"      =>array('text'=>'件数','width'=>70),
            "songhuoCode"  =>'送货单号',
            "danjia"       =>array('text'=>'单价','width'=>70),
            "unit"         =>array('text'=>'单位','width'=>70),
            "money"        =>array('text'=>'应收金额','width'=>70),
            'moneyRmb'     =>'金额(RMB)',
            "bizhong"      =>array('text'=>'币种','width'=>70),
            "huilv"        =>array('text'=>'汇率','width'=>70),
            "memo"         =>"备注",
            "creater"      =>"制单人"
        );

        $smarty = & $this->_getView();
        $smarty->assign('title', $title);
        $smarty->assign('arr_field_info',$arrField);
        $smarty->assign('arr_field_value',$rowset);
        $smarty->assign('arr_condition', $arr);
        $smarty->assign('add_display', 'none');
        $smarty->assign('fn_export',$this->_url($_GET['action'],array('export'=>1)+$arr));
        if($_GET['export']!=1) {
             $smarty->assign('page_info',$pager->getNavBar($this->_url($_GET['action']),$arr).$htmlPageMessage);
            $smarty->display($tpl);
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

    //已收款查询，对账单中编辑的信息
    function actionRightYis() {
        // $this->authCheck('4-2-2');
        $title = '应付款查询';
        $tpl = 'TblList.tpl';
        FLEA::loadClass('TMIS_Pager');
        $arr = TMIS_Pager::getParamArray(array(
            // 'dateFrom'=>date('Y-m-d',mktime(0,0,0,date('m')-1,date('d'),date('Y'))),
            // 'dateTo'=>date('Y-m-d'),
            'clientId'=>'',
            'orderCode'=>'',
            'product'=>'',
            'guige'=>'',
            'orderId'=>'',
            'no_edit'=>'',
        ));
        $sql="select x.*,z.compName,y.orderCode from caiwu_ar_guozhang x
            left join trade_order y on x.orderId=y.id
            left join trade_order2product a on a.id=x.ord2proId
            left join jichu_client z on z.id=x.clientId
            where 1";
        if($arr['orderId']>0){
            $arr['dateFrom']='';
            $arr['dateTo']='';
            $sql.=" and x.orderId='{$arr['orderId']}'";
        }
        if($arr['dateFrom']!='')$sql.=" and x.guozhangDate >='{$arr['dateFrom']}' and x.guozhangDate<='{$arr['dateTo']}'";
        if($arr['clientId']!='')$sql.=" and x.clientId='{$arr['clientId']}'";
        if($arr['orderCode']!='')$sql.=" and y.orderCode like '%{$arr['orderCode']}%'";
        if($arr['product']!='')$sql.=" and a.product like '%{$arr['product']}%'";
        if($arr['guige']!='')$sql.=" and a.guige like '%{$arr['guige']}%'";
        $sql.=" order by x.id asc";
        // dump($sql);exit;
        $pager =& new TMIS_Pager($sql);
        $rowset =$pager->findAll();
        foreach($rowset as & $v) {
            // $v['_edit']= $this->getEditHtml(array(
            // 	'id'=>$v['id'],
            // 	'fromAction'=>$_GET['action']
            // 	)) . '&nbsp;&nbsp;' . $this->getRemoveHtml($v['id']);

            // $v['money']=round($v['money'],2);

            //折合人民币
            $v['moneyRmb']=round($v['money']*$v['huilv'],2);
            $v['moneyRmb2']=round($v['moneyYishou']*$v['huilv'],2);
        }
        $rowset[] = $this->getHeji($rowset, array('moneyRmb2','money','moneyRmb'), $_GET['no_edit']==1?'compName':'_edit');
        // dump($rowset);exit;
        $arrField = array(
            // "_edit"=>'操作',
            "compName"=>'客户',
            "guozhangDate"=>'日期',
            "orderCode"=>'生产编号',
            "product"=>$this->getManuCodeName(),
            "guige"=>'规格',
            // "unit"=>array('text'=>'单位','width'=>70),
            // "cnt"=>array('text'=>'数量','width'=>70),
            // "danjia"=>array('text'=>'单价','width'=>70),
            // "money"=>array('text'=>'应收金额','width'=>70),
            'moneyRmb'=>'金额(RMB)',
            'moneyRmb2'=>'已收金额(RMB)',
            // "bizhong"=>array('text'=>'币种','width'=>70),
            // "huilv"=>array('text'=>'汇率','width'=>70),
            "memo"=>"备注",
            // "creater"=>"制单人",
        );

        $smarty = & $this->_getView();
        $smarty->assign('title', $title);
        $smarty->assign('arr_field_info',$arrField);
        $smarty->assign('arr_field_value',$rowset);
        $smarty->assign('arr_condition', $arr);
        $smarty->assign('add_display', 'none');
        $smarty->assign('page_info',$pager->getNavBar($this->_url($_GET['action'],$arr)));
        $smarty->display($tpl);
    }



    function actionSave(){
        //dump($_POST);exit;
        //有时候可能clientId保存不过来！所以在这边做个判断-by zhujunjie
        if($_POST['clientId']==0||$_POST['clientId']==''){
            js_alert("保存出错!请重新保存!",null,$this->_url('right'));
        }
        $arr=array(
            'id'=>$_POST['id'],
            'clientId'=>$_POST['clientId'],
            'guozhangDate'=>$_POST['guozhangDate'],
            'orderId'=>$_POST['orderId']+0,
            'ord2proId'=>$_POST['ord2proId']+0,
            'chukuId'=>$_POST['chukuId']+0,
            'chuku2proId'=>$_POST['chuku2proId']+0,
            'productId'=>$_POST['productId']+0,
            'qitaMemo'=>$_POST['qitaMemo'].'',
            'chukuDate'=>$_POST['chukuDate'].'',
            'unit'=>$_POST['unit'].'',
            'cnt'=>$_POST['cntOrg'].'',
            'cntJian'=>$_POST['cntJian'].'',
            'songhuoCode'=>$_POST['songhuoCode'].'',
            'kind'=>$_POST['kind'].'',
            'danjia'=>$_POST['danjia'].'',
            'money'=>$_POST['money'].'',
            'gzType'=>$_POST['gzType'].'',
            'memo'=>$_POST['memo'].'',
            'creater'=>$_POST['creater'].'',
            'memo'=>$_POST['memo'].'',
            'bizhong'=>$_POST['bizhong']?$_POST['bizhong']:'RMB',
            'huilv'=>empty($_POST['huilv'])?1:$_POST['huilv'],
        );
        // dump($arr);exit;
        $id=$this->_modelExample->save($arr);
        $guozhangId=$_POST['id']>0?$_POST['id']:$id;
        //改变入库表中的过账id
        // if($_POST['chuku2proId']){
        // 	$arr_rk=array(
        // 		'id'=>$_POST['chuku2proId'],
        // 		'guozhangId'=>$guozhangId,
        // 	);
        // 	$this->_modelChukuProduct->update($arr_rk);
        // 	// dump($arr_rk);exit;
        // }
        $toUrl = $_POST['id']>0?'Right':($_POST['gzType']>0?'AddOther':'Add');
        js_alert(null,'window.parent.showMsg("操作成功");',$this->_url($toUrl));
    }



    //应收款报表
    function actionReport(){
        //$this->authCheck('4-2-7');
        $tpl = $_GET['print']?'Print.tpl':'TblList.tpl';
        FLEA::loadclass('TMIS_Pager');
        $arr=TMIS_Pager::getParamArray(array(
            'dateFrom'=>date('Y-m-01'),
            'dateTo'=>date('Y-m-d'),
            'clientId'=>'',
            'traderId'=>'',
            'yxMoney'=>'1',
        ));
        //得到期初发生
        //应付款表中查找,日期为期初日期
        //按照加工商汇总
        $sql="SELECT sum(a.money*a.huilv) as fsMoney,a.clientId
              FROM caiwu_ar_guozhang a
              LEFT JOIN jichu_client t on t.id=a.clientId
              WHERE guozhangDate < '{$arr['dateFrom']}'";
        if($arr['clientId']!=''){
            $sql.=" and a.clientId='{$arr['clientId']}'";
        }
        if($arr['traderId']!=''){
            $sql.=" and t.traderId='{$arr['traderId']}'";
        }
        //用户对应的业务员
        $mUser = & FLEA::getSingleton('Model_Acm_User');
        if(!$mUser->canSeeAllOrder($_SESSION['USERID'])) {
            //得到当前用户匹配的员工id
            $traderIds = $mUser->getTraderIdOfCurUser($_SESSION['USERID']);
            if(count($traderIds)==0) {//说明用户没有指定关联业务员，禁止访问
                $sql .= " and 0";
            } else {
                $s = join(',',$traderIds);
                $sql .= " and t.traderId in ({$s})";
            }
        }
        $sql.=" group by clientId order by clientId";
        $rowset = $this->_modelExample->findBySql($sql);
        foreach($rowset as & $v){
            //期初金额
            $row[$v['clientId']]['initMoney']=$v['fsMoney']+0;//期初余额
            $row[$v['clientId']]['initIn']=$v['fsMoney']+0;
        }
        //得到起始日期前的收款金额
        //从付款表中查找
        //按照加工商汇总
        $sqlIncome = "SELECT sum(a.money*a.huilv) as shouKuanMoney,a.clientId
                      FROM `caiwu_ar_income` a
                      left join jichu_client t on t.id=a.clientId
                      WHERE  shouhuiDate < '{$arr['dateFrom']}'";
        if($arr['clientId']!=''){
            $sqlIncome.=" and a.clientId='{$arr['clientId']}'";
        }
        if($arr['traderId']!=''){
            $sqlIncome.=" and t.traderId='{$arr['traderId']}'";
        }
        //用户对应的业务员
        $mUser = & FLEA::getSingleton('Model_Acm_User');
        if(!$mUser->canSeeAllOrder($_SESSION['USERID'])) {
            //得到当前用户匹配的员工id
            $traderIds = $mUser->getTraderIdOfCurUser($_SESSION['USERID']);
            if(count($traderIds)==0) {//说明用户没有指定关联业务员，禁止访问
                $sqlIncome .= " and 0";
            } else {
                $s = join(',',$traderIds);
                $sqlIncome .= " and t.traderId in ({$s})";
            }
        }
        $sqlIncome.=" group by clientId order by clientId";
        $rowset = $this->_modelExample->findBySql($sqlIncome);
        foreach($rowset as & $v){
            //期初金额
            $row[$v['clientId']]['initMoney']=round($row[$v['clientId']]['initMoney']-$v['shouKuanMoney']+0,2);//期初余额=期初发生-期初已付款
            $row[$v['clientId']]['initOut']=round($v['shouKuanMoney'],2);
        }

        //得到本期的已收款
        //付款表中查找
        //按照客户汇总
        $str="SELECT sum(a.money*a.huilv) as moneySk,a.clientId
              FROM caiwu_ar_income a
              left join jichu_client t on t.id=a.clientId
              WHERE 1 ";
        if($arr['dateFrom']!=''){
            $str.=" and shouhuiDate>='{$arr['dateFrom']}' and shouhuiDate<='{$arr['dateTo']}'";
        }
        if($arr['clientId']!=''){
            $str.=" and a.clientId='{$arr['clientId']}'";
        }
        if($arr['traderId']!=''){
            $str.=" and t.traderId='{$arr['traderId']}'";
        }
        //用户对应的业务员
        $mUser = & FLEA::getSingleton('Model_Acm_User');
        if(!$mUser->canSeeAllOrder($_SESSION['USERID'])) {
            //得到当前用户匹配的员工id
            $traderIds = $mUser->getTraderIdOfCurUser($_SESSION['USERID']);
            if(count($traderIds)==0) {//说明用户没有指定关联业务员，禁止访问
                $str .= " and 0";
            } else {
                $s = join(',',$traderIds);
                $str .= " and t.traderId in ({$s})";
            }
        }
        $str.=" group by clientId order by clientId";
        //echo $str;exit;
        $fukuan=$this->_modelExample->findBySql($str);
        foreach($fukuan as & $v1){
            $row[$v1['clientId']]['moneySk']=round(($v1['moneySk']+0),2);
        }

        //得到本期发生
        //应付款表中查找
        //按照客户汇总
        $sql="SELECT sum(a.money*a.huilv) as fsMoney,a.clientId ,a.id
              FROM caiwu_ar_guozhang a
              left join jichu_client t on t.id=a.clientId
              WHERE 1";
        if($arr['dateFrom']!=''){
            $sql.=" and guozhangDate>='{$arr['dateFrom']}' and guozhangDate<='{$arr['dateTo']}'";
        }
        if($arr['clientId']!=''){
            $sql.=" and a.clientId='{$arr['clientId']}'";
        }
        if($arr['traderId']!=''){
            $sql.=" and t.traderId='{$arr['traderId']}'";
        }

        //用户对应的业务员
        $mUser = & FLEA::getSingleton('Model_Acm_User');
        if(!$mUser->canSeeAllOrder($_SESSION['USERID'])) {
            //得到当前用户匹配的员工id
            $traderIds = $mUser->getTraderIdOfCurUser($_SESSION['USERID']);
            if(count($traderIds)==0) {//说明用户没有指定关联业务员，禁止访问
                $sql .= " and 0";
            } else {
                $s = join(',',$traderIds);
                $sql .= " and t.traderId in ({$s})";
            }
        }
        $sql.=" group by clientId order by clientId";
        $rowset = $this->_modelExample->findBySql($sql);
        foreach($rowset as & $v2){
            $row[$v2['clientId']]['fsMoney']=round(($v2['fsMoney']+0),2);
        }

        //得到本期发票
        $str1="SELECT sum(a.money*a.huilv) as faPiaoMoney,a.clientId
               FROM `caiwu_ar_fapiao` a
               left join jichu_client t on t.id=a.clientId
               WHERE 1";
        if($arr['dateFrom']!=''){
            $str1.=" and fapiaoDate>='{$arr['dateFrom']}' and fapiaoDate<='{$arr['dateTo']}'";
        }
        if($arr['clientId']!=''){
            $str1.=" and a.clientId='{$arr['clientId']}'";
        }
        if($arr['traderId']!=''){
            $str1.=" and t.traderId='{$arr['traderId']}'";
        }
        //用户对应的业务员
        $mUser = & FLEA::getSingleton('Model_Acm_User');
        if(!$mUser->canSeeAllOrder($_SESSION['USERID'])) {
            //得到当前用户匹配的员工id
            $traderIds = $mUser->getTraderIdOfCurUser($_SESSION['USERID']);
            if(count($traderIds)==0) {//说明用户没有指定关联业务员，禁止访问
                $str1 .= " and 0";
            } else {
                $s = join(',',$traderIds);
                $str1 .= " and t.traderId in ({$s})";
            }
        }
        $str1.=" group by clientId order by clientId";
        $fukuan=$this->_modelExample->findBySql($str1);
        foreach ($fukuan as $v2){
            $row[$v2['clientId']]['faPiaoMoney']=$v2['faPiaoMoney']+0;
        }
        $mClient=& FLEA::getSingleton('Model_jichu_client');
        $mEmploy=& FLEA::getSingleton('Model_jichu_employ');

        if(count($row)>0){
            foreach($row as $key => & $v){
                $c=$mClient->find(array('id'=>$key));
                //根据客户表的traderId找到业务员的记录
                $m=$mEmploy->find(array('id'=>$c['traderId']));
                $v['clientId']=$key;
                $v['compName']=$c['compName'];
                $v['employName']=$m['employName'];
                // $v['weishouMoney']=round($v['initMoney'],2)+round($v['fsMoney'],2)-round($v['moneySk'],2);
                $v['weishouMoney']=($v['initMoney']+$v['fsMoney'])-$v['moneySk'];
                $v['weishouMoney']=round($v['weishouMoney'],2)+0;
                //判断 未收款 0 或者全部
                if($arr['yxMoney']==1 && $v['weishouMoney']==0){
                    unset($row[$key]);
                }
                if($arr['yxMoney']==2 && $v['weishouMoney']!=0){
                    unset($row[$key]);
                }
            }
        }

        unset($v);
        $row1=$row;
        // dump($row);exit;
        $heji=$this->getHeji($row,array('initMoney','fsMoney','moneySk','weishouMoney','faPiaoMoney'),'compName');
        $no = 1;
        foreach($row as $key=>& $v){
            $v['no']     = $no++;

            //查看对账单
            $v['duizhang']="<a href='".$this->_url('Duizhang',array(
                    'dateFrom'=>$arr['dateFrom'],
                    'dateTo'=>$arr['dateTo'],
                    'clientId'=>$v['clientId'],
                    'no_edit'=>1,
                ))."' target='_blank'>查看</a>";

            if($v['faPiaoMoney']){
                $v['duizhang'].="<span style='padding-left:1em'></span>"."<a href='".$this->_url('Duizhang',array(
                        'dateFrom'=>$arr['dateFrom'],
                        'dateTo'=>$arr['dateTo'],
                        'clientId'=>$v['clientId'],
                        'showFapiao'=>1,
                        'no_edit'=>1,
                    ))."' target='_blank'>查看(带发票)</a>";
            }

            $v['moneySk']="<a href='".url('caiwu_ys_Income','right',array(
                        'clientId'=>$v['clientId'],
                        'dateFrom'=>$arr['dateFrom'],
                        'dateTo'=>$arr['dateTo'],
                        'width'=>'700',
                        'no_edit'=>1,
                        'TB_iframe'=>1,))."' class='thickbox' title='收款明细'>".$v['moneySk']."</a>";
            $v['faPiaoMoney']="<a href='".url('caiwu_ys_fapiao','right',array(
                        'clientId'=>$v['clientId'],
                        'dateFrom'=>$arr['dateFrom'],
                        'dateTo'=>$arr['dateTo'],
                        'width'=>'700',
                        'no_edit'=>1,
                        'TB_iframe'=>1,))."' class='thickbox' title='开票明细'>".$v['faPiaoMoney']."</a>";
            $v['fsMoney']="<a href='".url('caiwu_ys_guozhang','right',array(
                        'clientId'=>$v['clientId'],
                        'dateFrom'=>$arr['dateFrom'],
                        'dateTo'=>$arr['dateTo'],
                        'width'=>'700',
                        'no_edit'=>1,
                        'TB_iframe'=>1,))."' class='thickbox' title='应收明细'>".$v['fsMoney']."</a>";
        }
        $row[]=$heji;
        //dump($row);exit;

        $arrFiled=array(
            'no'      => '序号',
            'compName'=>array('text'=>"客户",'width'=>'200'),
            "employName" =>"业务员",
            "initMoney" =>"期初余额",
            "fsMoney" =>"本期发生",
            "moneySk" =>"本期收款",
            "weishouMoney" =>"本期未收款",
            "faPiaoMoney" =>"本期开票",
            'duizhang'=>'对账单',
        );
        if($_GET['print']){
            unset($arrFiled['duizhang']);
        }
        $smarty = & $this->_getView();
        $smarty->assign('title', $title);
        $smarty->assign('arr_field_info',$arrFiled);
        $smarty->assign('arr_condition',$arr);
        $smarty->assign('add_display','none');
        $smarty->assign('arr_field_value',$row);
        $smarty->assign('heji',$heji);
        $smarty->assign('print_href',$this->_url($_GET['action'],array(
            'print'=>1
        )));
        $smarty->assign('title','应收款报表');
        if($_GET['print']) {
            //设置账期显示
            $smarty->assign('arr_main_value',array(
                '账期'=>$arr['dateFrom'] . ' 至 ' . $arr['dateTo'],
                '注'=>'金额已折合人民币',
            ));
        }
        $smarty->assign('fn_export',$this->_url($_GET['action'],array(
                'export'=>1
        )));
        if($_GET['export']!=1) {
            // $smarty->assign('page_info',$pager->getNavBar());
            $smarty->assign('page_info',"<font color='green'>金额已折合人民币</font>");
            $smarty->display($tpl);
            exit;
        }
        $heji=$this->getHeji($row1,array('initMoney','fsMoney','moneySk','weishouMoney','faPiaoMoney'),'compName');
        $heji['compName']="合计";
        //dump($heji);exit;
        foreach($row1 as $key=>& $v){

            $v['moneySk']=$v['moneySk'];
            $v['faPiaoMoney']=$v['faPiaoMoney'];
            $v['fsMoney']=$v['fsMoney'];

        }
        $row1[]=$heji;
        $arrFiled=array(
                'compName'=>"客户",
                "initMoney" =>array('text'=>"期初余额",'type'=>'Number'),
                "fsMoney" =>array('text'=>"本期发生",'type'=>'Number'),
                "moneySk" =>array('text'=>"本期收款",'type'=>'Number'),
                "weishouMoney" =>array('text'=>"本期未收款",'type'=>'Number'),
                "faPiaoMoney" =>array('text'=>"本期开票",'type'=>'Number'),

        );
        $smarty->assign('title',$arr['dateFrom'].'至'.$arr['dateTo'].'应收款报表');
        $smarty->assign('arr_field_info',$arrFiled);
        $smarty->assign('arr_field_value',$row1);
        // dump($arrFiled);exit;
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/download");
        header("Content-Disposition: attachment;filename=test.xls");
        header("Content-Transfer-Encoding: binary");
        $smarty->display('Export2Excel2.tpl');
    }

    /**
     * 对账单
     *
     * document:[README,财务-客户对账单]
     */
    function actionDuizhang(){
        // dump($_GET);exit;
        FLEA::loadClass('TMIS_Common');

        $arr=$_GET;
        if(empty($arr['clientId'])){
            echo "缺少客户信息";exit;
        }
        //查找对账单客户
        $mClient=& FLEA::getSingleton('Model_jichu_client');
        // 对账单设计格式
        $jgh=$mClient->find($arr['clientId']);

        $row = array();
        ## 查找期初欠款的情况
        //期初发生
        $sql="select sum(money) as money,sum(money*huilv) as moneyRmb
              from caiwu_ar_guozhang
              where guozhangDate < '{$arr['dateFrom']}'
                and clientId='{$arr['clientId']}'
              ";
        $initGuozhang = $this->_modelExample->findBySql($sql);
        //期初付款
        $sql="select sum(money) as money ,sum(money*huilv) as moneyRmb
              from caiwu_ar_income
              where shouhuiDate < '{$arr['dateFrom']}'
                and clientId='{$arr['clientId']}'
              ";

        $initIncome = $this->_modelExample->findBySql($sql);

        $row['moneyJieyu']=$initGuozhang[0]['moneyRmb']-$initIncome[0]['moneyRmb'];
        $row['guozhangDate']="<b>期初余额</b>";

        ##本期应付款对账信息
        //查找应付款信息
        $sql="select x.*,
                    y.orderCode,y.clientOrder,
                    z.cntYaohuo,z.unit as unitYaohuo,
                    p.menfu as pMenfu,p.kezhong as pKezhong,p.proName as pProName
              from caiwu_ar_guozhang x
              left join trade_order y on x.orderId=y.id
              left join trade_order2product z on z.id=x.ord2proId
              left join jichu_product p on p.id = x.productId
              where 1";
        if($arr['clientId']!=''){
            $sql.=" and x.clientId='{$arr['clientId']}'";
        }
        if($arr['dateFrom']!=''){
            $sql.=" and x.guozhangDate >= '{$arr['dateFrom']}'";
        }
        if($arr['dateTo']!=''){
            $sql.=" and x.guozhangDate <= '{$arr['dateTo']}'";
        }
        $sql.=" order by guozhangDate";
        $rowsGuozhang = $this->_modelExample->findBySql($sql);

        //查找已收款信息
        $sql="select x.money*x.huilv as shouhuimoney,x.shouhuiDate as guozhangDate,x.memo,x.type
              from caiwu_ar_income x
              where 1 ";
        if($arr['clientId']!=''){
            $sql.=" and x.clientId='{$arr['clientId']}'";
        }
        if($arr['dateFrom']!=''){
            $sql.=" and x.shouhuiDate >= '{$arr['dateFrom']}'";
        }
        if($arr['dateTo']!=''){
            $sql.=" and x.shouhuiDate <= '{$arr['dateTo']}'";
        }
        $sql.=" order by shouhuiDate";
        $rowsIncome = $this->_modelExample->findBySql($sql);

        ## 本期开票信息
        $rowsKp =  array();
        if(isset($arr['showFapiao'])){
            // 获得本期开票数据
            $sql = "SELECT money*huilv as moneyKp,fapiaoDate as guozhangDate,memo
                    from caiwu_ar_fapiao x
                    where 1 ";
            if($arr['clientId']!=''){
                $sql.=" and clientId='{$arr['clientId']}'";
            }
            if($arr['dateFrom']!=''){
                $sql.=" and fapiaoDate >= '{$arr['dateFrom']}'";
            }
            if($arr['dateTo']!=''){
                $sql.=" and fapiaoDate <= '{$arr['dateTo']}'";
            }
            $sql.=" order by fapiaoDate";
            $rowsKp = $this->_modelExample->findBySql($sql);
        }

        //合并应收款与已收款明细信息
        $rows=array_merge($rowsGuozhang, $rowsIncome, $rowsKp);

        //按照日期升序以及先显示发生再显示收款的顺序排列
        $rows = array_sortby_multifields($rows,array('guozhangDate'=>'SORT_ASC','shouhuimoney'=>'SORT_ASC'));

        //计算金额
        $money = 0;
        $moneyRmb = 0;
        $shouhuimoney = 0;
        $moneyJieyu1 = 0;
        $moneyKp = 0;

        foreach($rows as  & $v){
            // 收回金额
            $v['shouhuimoney']=$v['shouhuimoney']==0?'':round($v['shouhuimoney'],2);
            $v['money']=$v['money']==0?'':round($v['money'],2);
            $v['moneyRmb']=$v['money']==0?'':round($v['money']*$v['huilv'],2);
            $v['moneyKp'] = isset($v['moneyKp'])?round($v['moneyKp'], 2):'';


            // 若没有关联订单，则直接使用基础档案中的信息
            if(empty($v['orderCode']))
            {
                $v['menfu']  = $v['pMenfu'];
                $v['kezhong']  = $v['pKezhong'];
                $v['proName']  = $v['pProName'];
            }else{
                // 否则使用订单中处理过的信息
                $v['menfu']  = empty($v['menfu'])?$v['pMenfu']:$v['menfu'];
                $v['kezhong']  = empty($v['kezhong'])?$v['pKezhong']:$v['kezhong'];
                $v['proName']  = $v['pProName'];
            }

            $v['concatMemo'] = "{$v['proName']}[{$v['menfu']}][{$v['kezhong']}]";
            $v['concatMemo'] = $v['concatMemo'] == '[][]'?$v['qitaMemo']:$v['concatMemo'];


            if($v['shouhuimoney']>0){//若收款金额大于0，则描述使用收款记录的收款方式
                $v['concatMemo'] = $v['type'];
            }

            if(!empty($v['cntYaohuo']))$v['cntYaohuo']=round($v['cntYaohuo'],2).$v['unitYaohuo'];
            if(!empty($v['cnt']))$v['cnt']=round($v['cnt'],2).$v['unit'];
            $money += $v['money'];
            $moneyRmb += $v['moneyRmb'];
            $shouhuimoney += $v['shouhuimoney'];
            $moneyJieyu1 += $v['moneyJieyu'];
            $cnt += $v['cnt'];
            $moneyKp += $v['moneyKp']*1;

        }
        $moneyJieyu1=round($row['moneyJieyu']+$moneyRmb-$shouhuimoney,2);
        //合并数组
        $rowset=array_merge(array($row),$rows);

        $flag='';
        if($moneyJieyu1<0){
            $moneyJieyu2=-$moneyJieyu1;
            $flag='负 ';
            $moneyJieyuCap = TMIS_Common::trans2rmb ($moneyJieyu2); // 金额大写
        }else{
            $moneyJieyuCap = TMIS_Common::trans2rmb ($moneyJieyu1); // 金额大写
        }

        //金额合计
        $rowset[] = array(
                'guozhangDate1'=>'合计',
                'money'=>$money,
                'moneyRmb'=>$moneyRmb,
                'shouhuimoney'=>$shouhuimoney,
                'moneyJieyu'=>$moneyJieyu1,
                'cnt'=>$cnt,
                'moneyKp'   => $moneyKp==0?'':$moneyKp,
        );

        $arr_field_info=array(
            'guozhangDate'=>'日期',
//            'orderCode'=>'订单编号',
//            'clientOrder'=>'客户合同号',
            // 'proName'=>'品名',
            // 'guigeDesc'=>'规格',
            'concatMemo'=>'描述',
            //'qitaMemo'=>'描述',
            //'chukuDate'=>'出库日期',
//            'cntYaohuo'=>'要货数',
            'cntJian'=>array('text'=>"件数",'type'=>'right'),
            'cnt'=>'出库数',
            //'bizhong'=>'币种',
            'danjia'=>array('text'=>"单价",'type'=>'right'),
            'money'=>array('text'=>"应收金额",'type'=>'right'),
//            'moneyRmb'=>'应收(RMB)',
            'shouhuimoney'=>array('text'=>"已收(RMB)",'type'=>'right'),
            'moneyJieyu'=>array('text'=>"结余",'type'=>'right'),
            'moneyKp'     =>array('text'=>"发票金额",'type'=>'right'),
            'memo'=>'备注',
        );

        // 若不需要带发票信息，则不显示
        if(!isset($arr['showFapiao'])){
            unset($arr_field_info['moneyKp']);
        }
        //导出 去掉期初余额自己加粗
        if($_GET['export']){
            $rowset[0]['guozhangDate'] = '期初余额';
        }
        $date=$arr['dateTo'];
        $smarty=& $this->_getView();
        $smarty->assign('title',"{$jgh['compName']}对账单");
        $smarty->assign('title1',"{$jgh['compName']}");
        $smarty->assign('date',$date);
        $smarty->assign('arr_main_value',array('账期'=>$arr['dateFrom'] . ' 至 ' . $arr['dateTo']));
        $smarty->assign('arr_condition',$arr);
        $smarty->assign('flag',$flag);
        $smarty->assign('moneyJieyu',$moneyJieyu1);
        $smarty->assign('moneyJieyuCap',$moneyJieyuCap);
        if($_GET['no_edit']!=1){
            $smarty->assign('sonTpl',"caiwu/ys/sonTpl.tpl");
        }
        $smarty->assign('arr_field_info',$arr_field_info);
        $smarty->assign('arr_field_value',$rowset);
            // dump($arr_field_info);exit;
        if($_GET['export']){
            header("Content-type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=账单明细.xls");
            $smarty->display('Export3Excel.tpl');
            exit;
        }
        $smarty->display('printOld.tpl');
    }


    //删除
    function actionRemove(){
        //去掉入库信息中的guozhangid
        // $temp=$this->_modelExample->find($_GET['id']);
        // // dump($temp);exit;
        // $sql="update cangku_chuku2product set guozhangId='0' where id='{$temp['chuku2proId']}'";
        // $this->_modelExample->execute($sql);

        parent::actionRemove();
    }

    function actionAdd() {
        $this->fldMain['gzType']['value'] = 0;
        $url = $this->_url('AddBatch');
        $smarty = &$this->_getView();
        $smarty->assign('fldMain', $this->fldMain);
        $smarty->assign('rules', $this->rules);
        $smarty->assign('title', '过账信息编辑'."<div class='pull-right'><a href='{$url}' class='btn btn-default btn-xs'>批量增加</a></div>");
        $smarty->assign('sonTpl', 'caiwu/ys/sonTpl.tpl');
        $smarty->display('Main/A1.tpl');
    }

    protected function _initBatchAdd(){
        $fldMain = $this->fldMain;
            unset($fldMain['chuku2proId']);
            unset($fldMain['clientId']);
            unset($fldMain['qitaMemo']);
            unset($fldMain['chukuDate']);
            unset($fldMain['cntOrg']);
            unset($fldMain['cntJian']);
            unset($fldMain['songhuoCode']);
            unset($fldMain['unit']);
            unset($fldMain['danjia']);
            unset($fldMain['money']);
            unset($fldMain['bizhong']);
            unset($fldMain['huilv']);
            unset($fldMain['memo']);
            unset($fldMain['id']);
            unset($fldMain['creater']);
            unset($fldMain['kind']);
            unset($fldMain['productId']);
            unset($fldMain['chukuId']);
            unset($fldMain['orderId']);
            unset($fldMain['ord2proId']);
            unset($fldMain['gzType']);

        $this->fldMain = $fldMain;
        $this->headSon = array(
            '_edit' => array('type' => 'btBtnRemove', "title" => '+5行', 'name' => '_edit[]'),
            // 'guozhangDate' => array('title' => '过账日期', "type" => "btcalendar", 'value' => date('Y-m-d'),'name' => 'guozhangDate[]'),
            'chuku2proId' => array(
                'title' => '选择出库',
                'type' => 'BtPopup',
                'value' => '',
                'name'=>'chuku2proId[]',
                'text'=>'',
                'url'=>url('Cangku_Chuku','PopupOnGuozhang'),
                 'onSelFunc'=>'onSelChuku',//选中后需要执行的回调函数名,需要在jsTpl中书写
                'textFld'=>'chukuCode',//显示在text中的字段
                'hiddenFld'=>'id',//显示在hidden控件中的字段
                 'inTable'=>1,
            ),
            'orderCode' => array('title' => '订单编号', 'type' => 'bttext', 'value' => '','name' => 'orderCode[]','readonly'=>true),
            'ganghao' => array('title' => '缸号', 'type' => 'bttext', 'value' => '','name' => 'ganghao[]','readonly'=>true),
            'clientId' => array('title' => '客户名称', 'type' => 'btclientpopup','name' => 'clientId[]', 'clientName[]' => ''),
            'qitaMemo' => array('title' => '描述', 'type' => 'bttext', 'value' => '','name' => 'qitaMemo[]','readonly'=>true),
            'chukuDate' => array('title' => '出库日期', 'type' => 'bttext', 'value' => '','name' => 'chukuDate[]','readonly'=>true),
            'cntOrg' => array('title' => '数量', 'type' => 'bttext', 'value' => '','name' => 'cntOrg[]','readonly'=>true),
            'cntJian' => array('title' => '件数', 'type' => 'bttext', 'value' => '','name' => 'cntJian[]','readonly'=>true),
            'songhuoCode' => array('title' => '送货单号', 'type' => 'bttext', 'value' => '','name' => 'songhuoCode[]'),
            'unit'=>array('type'=>'btselect','title'=>'单位','name'=>'unit[]','options'=>array(
                    array('text'=>'公斤','value'=>'公斤'),
                    array('text'=>'米','value'=>'米'),
                    array('text'=>'码','value'=>'码'),
                    array('text'=>'只','value'=>'只'),
                    )),
            'danjia' => array('title' => '单价', 'type' => 'bttext', 'value' => '','name' => 'danjia[]'),
            'money' => array('title' => '应收金额', 'type' => 'bttext', 'value' => '','name' => 'money[]','addonEnd'=>'元'),
            'bizhong' => array('title' => '币种', 'type' => 'btselect','name' => 'bizhong[]', 'value' => 'RMB', 'options' => array(
                        array('text' => 'RMB', 'value' => 'RMB'),
                        array('text' => 'USD', 'value' => 'USD'),
                        )),
            'huilv' => array('title' => '汇率', 'type' => 'bttext', 'value' => '1','name' => 'huilv[]'),
            'memo' => array('title' => '备注', 'type' => 'bttext', 'value' => '','name' => 'memo[]'),
            'id' => array('type' => 'bthidden', 'value' => '','name' => 'id[]'),
            'creater' => array('type' => 'bthidden', 'value' => $_SESSION['REALNAME'],'name' => 'creater[]'),
            'kind' => array('type' => 'bthidden', 'value' => '','name' => 'kind[]'),
            'productId' => array('type' => 'bthidden', 'value' => '','name' => 'productId[]'),
            'chukuId' => array('type' => 'bthidden', 'value' => '','name' => 'chukuId[]'),
            'orderId' => array('type' => 'bthidden', 'value' => '','name' => 'orderId[]'),
            'ord2proId' => array('type' => 'bthidden', 'value' => '','name' => 'ord2proId[]'),
            'gzType' => array('type' => 'bthidden', 'value' => '','name' => 'gzType[]'),
        );
    }
    function actionAddBatch(){
        $url = $this->_url('Add');
        $this->_initBatchAdd();
        // 从表信息字段,默认5行
        for($i = 0;$i < 5;$i++) {
            $rowsSon[] = array(
                'bizhong'=>array('value'=>'RMB'),
                'huilv'=>array('value'=>'1'),
                'creater' => array('value' => $_SESSION['REALNAME']),
            );
        }
        unset($this->fldMain['orderCode']);
        unset($this->fldMain['ganghao']);
        $areaMain = array('title' => '过账信息编辑'."<div class='pull-right'><a href='{$url}' class='btn btn-default btn-xs'>单条增加</a></div>", 'fld' => $this->fldMain);
        $smarty = &$this->_getView();
        $smarty->assign('headSon', $this->headSon);
        $smarty->assign('rowsSon', $rowsSon);
        $smarty->assign('rules', $this->rules);
        $smarty->assign('areaMain', $areaMain);
        $smarty->assign('action_save', 'BatchSave');
        $smarty->assign('sonTpl', 'caiwu/ys/sonTpl.tpl');
        $smarty->display('Main2Son/T1.tpl');
    }

    function actionBatchSave(){
        // dump($_POST);exit;
        $arrs=array();
        if($_POST['clientId']==0||$_POST['clientId']==''){
            js_alert("保存出错!请重新保存!",null,$this->_url('right'));
        }
        foreach ($_POST['chuku2proId'] as $key=>$v){
            if(empty($v) || empty($_POST['clientId'])){
                    continue;
                }
        $arr=array(
            'id'=>$_POST['id[]'][$key],
            'clientId'=>$_POST['clientId'][$key],
            'guozhangDate'=>$_POST['guozhangDate'],
            'orderId'=>$_POST['orderId'][$key]+0,
            'ord2proId'=>$_POST['ord2proId'][$key]+0,
            'chukuId'=>$_POST['chukuId'][$key]+0,
            'chuku2proId'=>$_POST['chuku2proId'][$key]+0,
            'productId'=>$_POST['productId'][$key]+0,
            'qitaMemo'=>$_POST['qitaMemo'][$key].'',
            'chukuDate'=>$_POST['chukuDate'][$key].'',
            'unit'=>$_POST['unit'][$key].'',
            'cnt'=>$_POST['cntOrg'][$key].'',
            'cntJian'=>$_POST['cntJian'][$key].'',
            'songhuoCode'=>$_POST['songhuoCode'][$key].'',
            'kind'=>$_POST['kind'][$key].'',
            'danjia'=>$_POST['danjia'][$key].'',
            'money'=>$_POST['money'][$key].'',
            'gzType'=>$_POST['gzType'][$key].'',
            'memo'=>$_POST['memo'][$key].'',
            'creater'=>$_POST['creater'][$key].'',
            'memo'=>$_POST['memo'][$key].'',
            'bizhong'=>$_POST['bizhong'][$key]?$_POST['bizhong'][$key]:'RMB',
            'huilv'=>empty($_POST['huilv'][$key])?1:$_POST['huilv'][$key],
        );
            $arrs[]=$arr;
        }
        // dump($arrs);exit();
        // dump($arr);exit;
        $itemId = $this->_modelExample->saveRowset($arrs);
        $guozhangId=$_POST['id']>0?$_POST['id']:$id;

        js_alert(null,'window.parent.showMsg("操作成功");',$this->_url($_POST['fromAction']==''?'add':$_POST['fromAction']));


    }

    function actionEdit() {
        $row = $this->_modelExample->find(array('id' => $_GET['id']));//dump($row['cnt']);exit;
        //查找库位
        // $sql="select kuweiName from jichu_kuwei where id='{$row['kuweiId']}'";
        // $temp = $this->_modelExample->findBySql($sql);
        // $row['kuweiName']=$temp[0]['kuweiName'];

        // //查找产品
        // $row['proName']=$row['Product']['proName'];
        // $row['guige']=$row['Product']['guige'];
        // $row['clientName']=$row['Client']['compName'];

        $this->fldMain = $this->getValueFromRow($this->fldMain, $row);
        //得到数量
        $this->fldMain['cntOrg']['value'] = $row['cnt'];

        //订单编号
        $mOrder = & FLEA::getSingleton('Model_Trade_Order');
        $orders = $mOrder->find(array('id'=>$row['orderId']));
        $this->fldMain['orderCode']['value'] = $orders['orderCode'];

        //处理出库单号
        $mChuku = & FLEA::getSingleton('Model_Cangku_Chuku');
        $mChuku2pro = & FLEA::getSingleton('Model_Cangku_Chuku2Product');
        $chuku = $mChuku->find(array('id'=>$row['chukuId']));
        $chuku2pro = $mChuku2pro->find(array('id'=>$row['chuku2proId']));

        $this->fldMain['chuku2proId']['text'] = $chuku['chukuCode'];
        $this->fldMain['clientId']['clientName']=$row['Client']['compName'];
        $this->fldMain['ganghao']['value'] = $chuku2pro['ganghao'];
        $smarty = &$this->_getView();
        $smarty->assign('fldMain', $this->fldMain);
        $smarty->assign('rules', $this->rules);
        $smarty->assign('title', '过账信息编辑');
        $smarty->assign('aRow', $row);
        $smarty->assign('sonTpl', 'caiwu/ys/sonTpl.tpl');
        $smarty->display('Main/A1.tpl');
    }

    /**
     * @desc ：其他过账
     * Time：2017/03/01 22:04:37
     * @author Wuyou
    */
    function actionAddOther(){
        $this->_editOther(array());
    }

    /**
     * @desc ：其他过账修改
     * Time：2017/03/01 22:27:29
     * @author Wuyou
    */
    function actionEditOther(){
        $row = $this->_modelExample->find(array('id' => $_GET['id']));
        $row['hideTitle'] = 1;
        $this->_editOther($row);
    }

    /**
     * @desc ：其他过账编辑
     * Time：2017/03/01 22:19:20
     * @author Wuyou
    */
    function _editOther($arr){
        // dump($arr);exit;
        $url = $this->_url('AddOtherBatch');
        $this->fldMain['gzType']['value'] = 1;// 默认过账类型为其他过账
        $this->fldMain['chukuDate'] = array('title' => '出库日期', "type" => "calendar", 'value' => date('Y-m-d'));// 其他过账需要出库日期
        unset($this->fldMain['chuku2proId']);// 其他过账不需要选择出库单
        unset($this->fldMain['orderCode']);// 其他过账不需要订单
        unset($this->fldMain['ganghao']);// 其他过账不需要缸号
        unset($this->fldMain['qitaMemo']['readonly']);// 允许编辑描述
        unset($this->fldMain['cntOrg']['readonly']);// 允许编辑数量
        unset($this->fldMain['cntJian']['readonly']);// 允许编辑件数
        if($arr['id']>0){
            $this->fldMain = $this->getValueFromRow($this->fldMain, $arr);
            $this->fldMain['cntOrg']['value'] = $arr['cnt'];
            $this->fldMain['clientId']['clientName'] = $arr['Client']['compName'];
        }
        $smarty = &$this->_getView();
        $smarty->assign('fldMain', $this->fldMain);
        $smarty->assign('rules', $this->rules);
        if($arr['hideTitle']==1){
            $smarty->assign('title', '其他过账信息编辑');
        }else{
            $smarty->assign('title', '其他过账信息编辑'."<div class='pull-right'><a href='{$url}' class='btn btn-default btn-xs'>批量增加</a></div>");
        }
        $smarty->assign('sonTpl', 'caiwu/ys/qtSonTpl.tpl');
        $smarty->display('Main/A1.tpl');
    }


    function actionAddOtherBatch(){
        $url = $this->_url('AddOther');
        $this->_initBatchAddOther();
        // 从表信息字段,默认5行
        for($i = 0;$i < 5;$i++) {
            $rowsSon[] = array(
                'bizhong'=>array('value'=>'RMB'),
                'huilv'=>array('value'=>'1'),
                'gzType'=>array('value'=>'1'),
                'creater' => array('value' => $_SESSION['REALNAME']),
            );
        }
        $areaMain = array('title' => '其他过账信息编辑'."<div class='pull-right'><a href='{$url}' class='btn btn-default btn-xs'>单条增加</a></div>", 'fld' => $this->fldMain);
        $smarty = &$this->_getView();
        $smarty->assign('headSon', $this->headSon);
        $smarty->assign('rowsSon', $rowsSon);
        $smarty->assign('rules', $this->rules);
        $smarty->assign('areaMain', $areaMain);
        $smarty->assign('action_save', 'BatchSaveOther');
        $smarty->assign('sonTpl', 'caiwu/ys/qtSonTpl.tpl');
        $smarty->display('Main2Son/T1.tpl');
    }

    protected function _initBatchAddOther(){
        $fldMain = $this->fldMain;
            unset($fldMain['chuku2proId']);
            unset($fldMain['clientId']);
            unset($fldMain['qitaMemo']);
            unset($fldMain['chukuDate']);
            unset($fldMain['cntOrg']);
            unset($fldMain['cntJian']);
            unset($fldMain['songhuoCode']);
            unset($fldMain['unit']);
            unset($fldMain['danjia']);
            unset($fldMain['money']);
            unset($fldMain['bizhong']);
            unset($fldMain['huilv']);
            unset($fldMain['memo']);
            unset($fldMain['id']);
            unset($fldMain['creater']);
            unset($fldMain['kind']);
            unset($fldMain['productId']);
            unset($fldMain['chukuId']);
            unset($fldMain['orderId']);
            unset($fldMain['ord2proId']);
            unset($fldMain['gzType']);
            unset($fldMain['orderCode']);
            unset($fldMain['ganghao']);

        $this->fldMain = $fldMain;
        $this->headSon = array(
            '_edit' => array('type' => 'btBtnRemove', "title" => '+5行', 'name' => '_edit[]'),
            // 'guozhangDate' => array('title' => '过账日期', "type" => "btcalendar", 'value' => date('Y-m-d'),'name' => 'guozhangDate[]'),
             'clientId' => array(
                'title' => '客户名称',
                'type' => 'BtPopup',
                'value' => '',
                'name'=>'clientId[]',
                'text'=>'选择客户',
                'url'=>url('jichu_client','popup'),//弹出地址,回调函数在sonTpl中进行定义,主要是对缓存中的关联数组进行重写
                'textFld'=>'compName',//显示在text中的字段
                'hiddenFld'=>'id',//显示在hidden控件中的字段
                'inTable'=>1,
            ),

            'qitaMemo' => array('title' => '描述', 'type' => 'bttext', 'value' => '','name' => 'qitaMemo[]'),
            'chukuDate' => array('title' => '出库日期', 'type' => 'btcalendar', 'value' => '','name' => 'chukuDate[]'),
            'cntOrg' => array('title' => '数量', 'type' => 'bttext', 'value' => '','name' => 'cntOrg[]'),
            'cntJian' => array('title' => '件数', 'type' => 'bttext', 'value' => '','name' => 'cntJian[]'),
            'songhuoCode' => array('title' => '送货单号', 'type' => 'bttext', 'value' => '','name' => 'songhuoCode[]'),
            'unit'=>array('type'=>'btselect','title'=>'单位','name'=>'unit[]','options'=>array(
                    array('text'=>'公斤','value'=>'公斤'),
                    array('text'=>'米','value'=>'米'),
                    array('text'=>'码','value'=>'码'),
                    array('text'=>'只','value'=>'只'),
                    )),
            'danjia' => array('title' => '单价', 'type' => 'bttext', 'value' => '','name' => 'danjia[]'),
            'money' => array('title' => '应收金额', 'type' => 'bttext', 'value' => '','name' => 'money[]','addonEnd'=>'元'),
            'bizhong' => array('title' => '币种', 'type' => 'btselect','name' => 'bizhong[]', 'value' => 'RMB', 'options' => array(
                        array('text' => 'RMB', 'value' => 'RMB'),
                        array('text' => 'USD', 'value' => 'USD'),
                        )),
            'huilv' => array('title' => '汇率', 'type' => 'bttext', 'value' => '1','name' => 'huilv[]'),
            'memo' => array('title' => '备注', 'type' => 'bttext', 'value' => '','name' => 'memo[]'),
            'id' => array('type' => 'bthidden', 'value' => '','name' => 'id[]'),
            'creater' => array('type' => 'bthidden', 'value' => $_SESSION['REALNAME'],'name' => 'creater[]'),
            'kind' => array('type' => 'bthidden', 'value' => '','name' => 'kind[]'),
            'productId' => array('type' => 'bthidden', 'value' => '','name' => 'productId[]'),
            'chukuId' => array('type' => 'bthidden', 'value' => '','name' => 'chukuId[]'),
            'orderId' => array('type' => 'bthidden', 'value' => '','name' => 'orderId[]'),
            'ord2proId' => array('type' => 'bthidden', 'value' => '','name' => 'ord2proId[]'),
            'gzType' => array('type' => 'bthidden', 'value' => '1','name' => 'gzType[]'),
        );
    }


    function actionBatchSaveOther(){
        $arrs=array();
        if($_POST['clientId']==0||$_POST['clientId']==''){
            js_alert("保存出错!请重新保存!",null,$this->_url('right'));
        }
        foreach ($_POST['clientId'] as $key=>$v){
            if(empty($v) || empty($_POST['clientId'])){
                continue;
            }
            $arr=array(
                'id'=>$_POST['id[]'][$key],
                'clientId'=>$_POST['clientId'][$key],
                'guozhangDate'=>$_POST['guozhangDate'],
                'orderId'=>$_POST['orderId'][$key]+0,
                'ord2proId'=>$_POST['ord2proId'][$key]+0,
                'chukuId'=>$_POST['chukuId'][$key]+0,
                'chuku2proId'=>$_POST['chuku2proId'][$key]+0,
                'productId'=>$_POST['productId'][$key]+0,
                'qitaMemo'=>$_POST['qitaMemo'][$key].'',
                'chukuDate'=>$_POST['chukuDate'][$key].'',
                'unit'=>$_POST['unit'][$key].'',
                'cnt'=>$_POST['cntOrg'][$key].'',
                'cntJian'=>$_POST['cntJian'][$key].'',
                'songhuoCode'=>$_POST['songhuoCode'][$key].'',
                'kind'=>$_POST['kind'][$key].'',
                'danjia'=>$_POST['danjia'][$key].'',
                'money'=>$_POST['money'][$key].'',
                'gzType'=>$_POST['gzType'][$key].'',
                'memo'=>$_POST['memo'][$key].'',
                'creater'=>$_POST['creater'][$key].'',
                'memo'=>$_POST['memo'][$key].'',
                'bizhong'=>$_POST['bizhong'][$key]?$_POST['bizhong'][$key]:'RMB',
                'huilv'=>empty($_POST['huilv'][$key])?1:$_POST['huilv'][$key],
            );
            $arrs[]=$arr;
        }
        // dump($arrs);exit();
        $itemId = $this->_modelExample->saveRowset($arrs);
        $guozhangId=$_POST['id']>0?$_POST['id']:$id;
        js_alert(null,'window.parent.showMsg("操作成功");',$this->_url('AddOtherBatch'));

    }

}
?>