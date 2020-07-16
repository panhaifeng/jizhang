<?php
FLEA::loadClass('TMIS_Controller');
class Controller_Jichu_Client extends Tmis_Controller {
    var $_modelExample;
    var $_modelTaitou;
    var $title = "客户档案";
    var $funcId = 8;
    var $_tplEdit = "Jichu/ClientEdit.tpl";

    function Controller_Jichu_Client() {
        $this->_modelExample = & FLEA::getSingleton('Model_Jichu_Client');
        $this->_modelEmploy = & FLEA::getSingleton('Model_Jichu_Employ');
        $this->_modelTaitou = & FLEA::getSingleton('Model_Jichu_ClientTaitou');

        $this->fldMain = array(
            'id'=>array('title'=>'','type'=>'hidden','value'=>''),
            'compCode'=>array('title'=>'客户编码','type'=>'text','value'=>''),
            'compName'=>array('title'=>'客户名称','type'=>'text','value'=>''),
            'codeAtOrder'=>array('title'=>'客户简称','type'=>'text','value'=>''),
            'traderId'=>array('title'=>'业务员','type'=>'select','value'=>'','options'=>$this->_modelEmploy->getSelect()),
            'address'=>array('title'=>'地址','type'=>'text','value'=>''),
            'tel'=>array('title'=>'电话','type'=>'text','value'=>''),
            'zhujiCode'=>array('title'=>'助记码','type'=>'text','value'=>''),
            'people'=>array('title'=>'联系人','type'=>'text','value'=>'','addonEnd'=>'多个联系人用半角逗号","分隔'),
            'address'=>array('title'=>'地址','type'=>'text','value'=>''),
            'fax'=>array('title'=>'传真','type'=>'text','value'=>''),
            'mobile'=>array('title'=>'手机','type'=>'text','value'=>''),
            'email'=>array('title'=>'Email','type'=>'text','value'=>''),
            'pic'=>array('title'=>'营业执照','type'=>'file','value'=>'','addonEnd'=>'未上传营业执照'),
            'isStop'=>array('title'=>'停止往来','type'=>'select','value'=>'0','options'=>array(
                    array('text'=>'正常往来','value'=>0),
                    array('text'=>'停止往来','value'=>1)
                )),
            'kaipiao'=>array('title'=>'开票资料','type'=>'textarea','value'=>''),
            'memo'=>array('title'=>'备注','type'=>'textarea','value'=>''),
        );

        $this->rules = array(
            'compCode'=>'required',
            'compName'=>'required',
            // 'codeAtOrder'=>'required',
            'traderId'=>'required'
        );
    }

    function actionEdit(){
        if($_GET['flag']){
            //查看
            $this->authCheck('6-1-3');
            foreach($this->fldMain as & $v){
                $v['disabled']='disabled';
            }
        }else{
            //修改
            $this->authCheck('6-1-1');
        }
        $row = $this->_modelExample->find(array('id'=>$_GET['id']));
        $this->fldMain = $this->getValueFromRow($this->fldMain,$row);
        //处理提示信息，是否已经上传营业执照
        if($row['zhizaoPic']!=''){
            $this->fldMain['pic']['addonEnd']="已上传";
            $this->fldMain['pic']['addonEnd'] .= "<a href='".$row['zhizaoPic']."' target='_blank'>营业执照</a>";
        }
        // dump($this->fldMain);exit;
        $smarty = & $this->_getView();
        $smarty->assign('fldMain',$this->fldMain);
        $smarty->assign('rules',$this->rules);
        $smarty->assign('title','客户信息编辑');
        $smarty->assign('aRow',$row);
        $smarty->assign('flag', $_GET['flag']);
        $smarty->assign('form',array('up'=>true));
        $smarty->display('Main/A2.tpl');
    }

    function actionAdd($Arr){
        //需要设置默认业务员
        $mUser = & FLEA::getSingleton('Model_Acm_User');
        $canSeeAllOrder = $mUser->canSeeAllOrder($_SESSION['USERID']);
        if(!$canSeeAllOrder) {//如果不能看所有订单，得到当前用户的关联业务员
            $user = $mUser->find(array('id'=>$_SESSION['USERID']));
            $traderId = $user['traders'][0]['id'];
        }

        $row['traderId']=$traderId;

        $row['compCode'] = $this->_getCode();
        $this->fldMain = $this->getValueFromRow($this->fldMain,$row);

        $smarty = & $this->_getView();
        $smarty->assign('fldMain',$this->fldMain);
        $smarty->assign('title','客户信息编辑');
        $smarty->assign('rules',$this->rules);
        $smarty->assign('form',array('up'=>true));
        $smarty->display('Main/A2.tpl');
    }

    function actionRight() {
    ////////////////////////////////标题
        $title = '客户档案编辑';
        ///////////////////////////////模板文件
        $tpl = 'TableList.tpl';
        ///////////////////////////////表头
        $arr_field_info = array(
            '_edit'=>'操作',
            "compCode" =>array('text'=>"编码",'align'=>'center'),
            "compName" =>"名称",
            "zhujiCode" =>"助记码",
            "codeAtOrder" =>"客户简称",
            "Trader.employName" =>"本厂负责",
            "people"=>'联系人',
            "tel" =>"电话",
            "mobile" =>"手机",
            "fax" =>"传真",
            "email" =>"email",
            "address" =>"地址",
            'kaipiao'=>'开票资料',
            'zhizaoPic'=>'营业执照',
            "memo" =>"备注"
        );

        ///////////////////////////////模块定义
        $this->authCheck('6-1');

        FLEA::loadClass('TMIS_Pager');
        $arr = TMIS_Pager::getParamArray(array(
            'key'=>'',
            'traderId'=>'',
            //'lzId'=>''
        ));
        //dump($arr);
        $condition=array();
        if($arr['key']!='') {
            $condition[] = array('compCode',"%{$arr['key']}%",'like','or');
            $condition[] = array('compName',"%{$arr['key']}%",'like');
        }

        //业务员只能看自己的客户
        $mUser = & FLEA::getSingleton('Model_Acm_User');
        $canSeeAllOrder = $mUser->canSeeAllOrder($_SESSION['USERID']);
        if(!$canSeeAllOrder) {//如果不能看所有订单，得到当前用户的关联业务员
            $traderId = $mUser->getTraderIdByUser($_SESSION['USERID'],true);
//			$sql .= " and y.traderId='{$user['traderId']}'";
            if($traderId)$condition['in()'] = array('traderId'=>$traderId);
        }
        // dump($_POST['traderId']); dump($_GET['traderId']);exit;
        if($arr['traderId']!=0) {
            $condition[] = array('traderId',"{$arr['traderId']}",'=');
        }
        //dump($condition);
        $pager =& new TMIS_Pager($this->_modelExample,$condition,'id desc');
        $rowset =$pager->findAll();

        if(count($rowset)>0) foreach($rowset as & $v) {
            ///////////////////////////////
            //$this->makeEditable($v,'memoCode');
                if($v['zhizaoPic']!='') $v['zhizaoPic'] = "<a href='".$v['zhizaoPic']."' target='_blank'><img src='Resource/Image/img.gif' style='border:0px'></a>";
                if($v['isStop']==1) $v['_bgColor']='lightyellow';

                $v['_edit'] = $this->getEditHtml($v['id']) . '&nbsp;&nbsp;' . $this->getRemoveHtml($v['id']);
                $v['_edit'] .="&nbsp;&nbsp;"."<a href='".$this->_url('Edit',array(
                    'id'=>$v['id'],
                    'flag'=>1,
                    'no_edit'=>1,
                    'TB_iframe'=>1
                    ))."' title='查看'>查看</a>";
                $v['_edit'] .="&nbsp;&nbsp;"."<a href='".$this->_url('setTaitou',array(
                    'clientId'=>$v['id'],

                    'no_edit'=>1,
                    'TB_iframe'=>1
                    ))."' class='thickbox'  title='开票抬头设置'>开票抬头设置</a>";
            }
        $smarty = & $this->_getView();
        $smarty->assign('title', $title);
        $smarty->assign('arr_field_info',$arr_field_info);
        $smarty->assign('arr_field_value',$rowset);
        $smarty->assign('arr_condition', $arr);

        /*///////////////grid,thickbox,///////////////*/
        $smarty->assign('arr_js_css',$this->makeArrayJsCss(array('calendar')));
        $smarty->assign('page_info',$pager->getNavBar($this->_url($_GET['action'],$arr))."<font color='red'>黄色代表停止往来</font>");
        $smarty->display($tpl);
    }

    function actionSave() {
        //dump($_POST);exit;
        //判断是否重复,
        //新增时判断公司名和代码是否重复
        $str1="SELECT count(*) as cnt FROM `jichu_client` where id<>'{$_POST['id']}' and (compCode='{$_POST['compCode']}' or compName='{$_POST['compName']}')";
        //dump($str1);exit;
        $ret=$this->_modelExample->findBySql($str1);
        if($ret[0]['cnt']>0) {
            js_alert('客户名称或客户代码重复!','window.history.go(-1);');
        }

        //处理图片
        $path = 'upload/yyzz/';//保存路径
        $colName='zhizaoPic';//字段名
        $inputName='pic';//文件控件名
        if($_POST['id']>0) {
                $re = $this->_modelExample->find(array('id'=>$_POST['id']));
                if ($_FILES[$inputName]['name']!="") {//图片文件不为空,需要删除原来的图片
                        unlink($re[$colName]);
                        $targetFile = '';
                } else {//为空的话保留
                        $targetFile = $re[$colName];
                }
        }
        if ($_FILES[$inputName]['name']!="") {
                $tempFile = $_FILES[$inputName]['tmp_name'];
                $bpic="b".date("YmdHis").".jpg";
                $targetFile=$path.$bpic;//目标路径
                move_uploaded_file($tempFile,$targetFile);
        }
        $_POST[$colName] = $targetFile.'';

        //首字母自动获取
        FLEA::loadClass('TMIS_Common');
        $letters=strtoupper(TMIS_Common::getPinyin($_POST['compName']));
        $_POST['letters']=$letters;

        $id = $this->_modelExample->save($_POST);
        //$dbo=& FLEA::getDBO(false);dump($dbo->log);exit;
        if($_POST['Submit']=='保 存')
            js_alert(null,"window.parent.showMsg('保存成功!')",$this->_url($_POST['fromAction']==''?'add':$_POST['fromAction']));
        else
            js_alert(null,"window.parent.showMsg('保存成功!')",$this->_url('add'));

    }

    function actionRemove() {
        $this->authCheck('6-1-2');
    //dump($_GET);exit;

        if($_GET['id']!="") {

            $sql="SELECT count(*) as cnt FROM `trade_order` where clientId=".$_GET['id'];
            //dump($sql);exit;
            $re=$this->_modelExample->findBySql($sql);
            //dump($re);exit;
            if($re[0]['cnt']>0) {
                js_alert('此客户有订单存在，不能够删除',null,$this->_url('right'));
            }
        }
        parent::actionRemove();
    }

    //在模式对话框中显示待选择的客户，返回某个客户的json对象。
    function actionPopup() {
        FLEA::loadClass('TMIS_Pager');
        $arr = TMIS_Pager::getParamArray(array(
            //'traderId' => '',
            'key' => '',
            'showModel'=>''
        ));

        $str = "select * from jichu_client where 1";
        //if ($arr[traderId]!='') $str .= " and traderId='$arr[traderId]'";
        if ($arr[key]!='') $str .= " and compCode like '%$arr[key]%'
                                        or compName like '%$arr[key]%' or zhujiCode like '%$arr[key]%'";

        //该用户关联的业务员的订单
        $mUser = & FLEA::getSingleton('Model_Acm_User');
        if(!$mUser->canSeeAllOrder($_SESSION['USERID'])) {
            //得到当前用户匹配的员工id
            $traderIds = $mUser->getTraderIdOfCurUser($_SESSION['USERID']);
            if(count($traderIds)==0) {//说明用户没有指定关联业务员，禁止访问
                $str .= " and 0";
            } else {
                $s = join(',',$traderIds);
                $str .= " and traderId in ({$s})";
            }
        }

        $str .=" order by compCode desc";
        $pager =& new TMIS_Pager($str);
        //echo $str;
        $rowset =$pager->findAllBySql($str);
        $pageInfo = $pager->getNavBar('Index.php?'.$pager->getParamStr($arr));
        //$mTrader = FLEA::getSingleton('Model_Jichu_Employ');
        if(count($rowset)>0) foreach($rowset as & $v){
            $str="select * from jichu_employ where id='{$v['traderId']}'";
            $re=mysql_fetch_assoc(mysql_query($str));
            $v['traderName']=$re['employName'];
            //$temp = $mTrader->find($v[traderId]);
            //$v[traderName] = $temp[employName];
            //$v[_edit] = "<a href='#' onclick=\"retOptionValue($v[id],'$v[compName]')\">选择</a>";
        }
        if($_GET['kind']==0){
            $arr_field_info = array(
                "compCode" =>"编码",
                "compName" =>"名称",
                'head'     => '抬头',
                'traderName'=>'本厂联系人',
                // "people" =>"联系人",
                // "address" =>"地址",
                // "tel" =>"电话",
                // "mobile" =>"手机",
                //"carCode" =>"车牌号",
                //"memo" =>"备注",

            );
        }else{
            $arr_field_info = array(
                "compCode" =>"编码",
                "compName" =>"名称",
            );
        }
        // $arr['kind']=$_GET['kind'];
        //dump($rowset); exit;
        $smarty = & $this->_getView();
        $smarty->assign('title', '选择客户');
        $pk = $this->_modelExample->primaryKey;
        $smarty->assign('pk', $pk);
        $smarty->assign('add_display','none');
        $smarty->assign('arr_field_info',$arr_field_info);
        $smarty->assign('arr_field_value',$rowset);
        $smarty->assign('s',$arr);
        $smarty->assign('page_info',$pager->getNavBar($this->_url($_GET['action'],$arr)));
        $smarty->assign('arr_condition',$arr);
        $smarty->assign('clean',true);
        // $smarty->assign('arr_js_css',$this->makeArrayJsCss(array('thickbox')));// nowork
        $smarty-> display('Popup/CommonNew.tpl');
    }

    function actionPopupWithHead(){
        FLEA::loadClass('TMIS_Pager');
        $arr = TMIS_Pager::getParamArray(array(
            //'traderId' => '',
            'key' => '',
            'clientHead' =>'',
            'showModel'=>''
        ));

        $str = "select c.*, m.taitou as head
                from jichu_client c
                left join jichu_client_taitou m on c.id = m.clientId
                where 1";
        //if ($arr[traderId]!='') $str .= " and traderId='$arr[traderId]'";
        if ($arr[key]!='') $str .= " and compCode like '%$arr[key]%'
                                        or compName like '%$arr[key]%' or zhujiCode like '%$arr[key]%'";

        //该用户关联的业务员的订单
        $mUser = & FLEA::getSingleton('Model_Acm_User');
        if(!$mUser->canSeeAllOrder($_SESSION['USERID'])) {
            //得到当前用户匹配的员工id
            $traderIds = $mUser->getTraderIdOfCurUser($_SESSION['USERID']);
            if(count($traderIds)==0) {//说明用户没有指定关联业务员，禁止访问
                $str .= " and 0";
            } else {
                $s = join(',',$traderIds);
                $str .= " and traderId in ({$s})";
            }
        }

        $str .=" order by compCode desc";

        $pager =& new TMIS_Pager($str);
        //echo $str;
        $rowset =$pager->findAllBySql($str);
        $pageInfo = $pager->getNavBar('Index.php?'.$pager->getParamStr($arr));
        //$mTrader = FLEA::getSingleton('Model_Jichu_Employ');
        $employModel = FLEA::getSingleton('Model_Jichu_Employ');
        if(count($rowset)>0) foreach($rowset as & $v){
            $employ = $employModel->find($v['traderId']);
            $v['traderName']=$employ['employName'];
            $v['head']   = empty($v['head'])?'':$v['head'];
        }
        if($_GET['kind']==0){
            $arr_field_info = array(
                "compCode" =>"编码",
                "compName" =>"名称",
                'head'     => '抬头',
                'traderName'=>'本厂联系人',
                // "people" =>"联系人",
                // "address" =>"地址",
                // "tel" =>"电话",
                // "mobile" =>"手机",
                //"carCode" =>"车牌号",
                //"memo" =>"备注",

            );
        }else{
            $arr_field_info = array(
                "compCode" =>"编码",
                "compName" =>"名称",
            );
        }
        // $arr['kind']=$_GET['kind'];
        //dump($rowset); exit;
        $smarty = & $this->_getView();
        $smarty->assign('title', '选择客户');
        $pk = $this->_modelExample->primaryKey;
        $smarty->assign('pk', $pk);
        $smarty->assign('add_display','none');
        $smarty->assign('arr_field_info',$arr_field_info);
        $smarty->assign('arr_field_value',$rowset);
        $smarty->assign('s',$arr);
        $smarty->assign('page_info',$pager->getNavBar($this->_url($_GET['action'],$arr)));
        $smarty->assign('arr_condition',$arr);
        $smarty->assign('clean',true);
        // $smarty->assign('arr_js_css',$this->makeArrayJsCss(array('thickbox')));// nowork
        $smarty-> display('Popup/CommonNew.tpl');
    }

    function actionGetJsonByKey() {
        $sql = "select * from jichu_client where (
            compName like '%{$_GET['code']}%' or zhujiCode like '%{$_GET['code']}%' or compCode like '%{$_GET['code']}%'
        )";
        dump($sql);exit;
        $arr = $this->_modelExample->findBySql($sql);
        echo json_encode($arr);exit;
    }
    //根据传入的id获得具体信息,订单录入时根据客户定位业务员时用到
    function actionGetJsonById() {
        $sql = "select * from jichu_client where id='{$_GET['id']}'";
        $arr = $this->_modelExample->findBySql($sql);
        echo json_encode($arr[0]);exit;
    }
    //根据业务员查找客户
    function actionGetJsonByTraderId() {
        $sql = "select * from jichu_client where 1";
        if($_GET['traderId']!='')$sql.=" and traderId='{$_GET['traderId']}'";
        $mUser = & FLEA::getSingleton('Model_Acm_User');
        $canSeeAllOrder = $mUser->canSeeAllOrder($_SESSION['USERID']);
        if(!$canSeeAllOrder) {
            //如果不能看所有订单，得到当前用户的关联业务员
            $traderId = $mUser->getTraderIdByUser($_SESSION['USERID']);
            if($traderId)$sql .= " and traderId in ({$traderId})";
        }
        // $sql.=" order by convert(trim(compName) USING gbk)";
        // $arr = $this->_modelExample->findBySql($sql);
        $sql.=" order by ";
        $kg = & FLEA::getAppInf('khqcxs');
        if($kg)$sql.=" letters";
        else $sql.=" compCode";

        $arr = $this->_modelExample->findBySql($sql);

        //生成下拉框
        $ret=$this->_modelExample->options($arr);
        echo json_encode($ret);exit;
    }

    //开票抬头设置
    function actionSetTaitou(){
        $rows=$this->_modelTaitou->findAll(array('clientId'=>$_GET['clientId']));
        //dump($rows);exit;
        $smarty = & $this->_getView();
        $smarty->assign('title', '开票抬头设置');
        $smarty->assign("aRow", $rows);
        $smarty->display('Jichu/ClientTaitou.tpl');
    }
    // 得到客户自动完成控件的提示信息 2015-05-18 by jiang
    function actionGetJsonByKey2() {
        if($_GET['code'])$key = $_GET['code'];
        if(isset($_REQUEST['q']))$key =$_REQUEST['q']?$_REQUEST['q']:'';
        $sql = "select * from jichu_client where (
        compName like '%{$key}%' or zhujiCode like '%{$key}%' or compCode like '%{$key}%'
        ) order by compName";
        $arr = $this->_modelExample->findBySql($sql);
        $data=array();
        foreach ($arr as & $v) {
        $data[]=array($v['compName'],array('name'=>$v['compName'],'id'=>$v['id']));
        }
        echo json_encode($data);exit;
    }
    //保存抬头设置
    function actionSaveTaitou(){
        //dump($_POST);exit;
        $rows=array(
            'taitou'=>$_POST['taitou'],
            'clientId'=>$_POST['clientId'],
            'memo'=>$_POST['memo']
        );
        if($rows) $this->_modelTaitou->save($rows);
        // js_alert(null,'window.parent.parent.showMsg("设置成功");window.parent.location.href=window.parent.location.href');
        js_alert('保存成功！','',$this->_url('SetTaitou',array('clientId'=>$_POST['clientId'])));
    }

    //删除抬头设置
    function actionDelTaitouAjax(){
        if($_GET['id']!='') {
            if($this->_modelTaitou->removeByPkv($_GET['id'])) {
                echo json_encode(array('success'=>true));
                exit;
            }
        }
    }

    /**
     * 客户的自动编码,沃丰的个性化要求,
     * WF开头,四位序号
     */
    function _getCode() {
        $head = 'JM-';
        $len = strlen($head);
        $sql = "select max(compCode) c
        from jichu_client
        where compCode like '{$head}_____' order by compName desc limit 0,1";
        $rows = $this->_modelExample->findBySql($sql);
        $begin = $head.'00001';
        if($begin>$rows) return $begin;
        $max = $rows[0]['c'];

        $num = 100001+substr($max,$len);
        return $head . substr($num,1);
    }
}
?>