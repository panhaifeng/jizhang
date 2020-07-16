<?php
FLEA::loadClass('Controller_Cangku_Chuku');
class Controller_Cangku_Yuanliao_Kcdb extends Controller_Cangku_Chuku {
	function __construct() {
		$this->_state = '原料';
        $this->_kind = '调货出库';
        $this->_kindRu = '调货入库';
		$this->_arrKuwei = array('坯纱仓库','色纱仓库');
        $this->_modelRuk = &FLEA::getSingleton('Model_Cangku_Yuanliao_Ruku');
		parent::__construct();
	}

    function actionRight(){
        $title = '库存调整列表';
        ///////////////////////////////模板文件
        $tpl = 'TableList.tpl';
        ///////////////////////////////模块定义
        $this->authCheck('3-1-14');
        FLEA::loadClass('TMIS_Pager');
        $arr = TMIS_Pager::getParamArray(array(
            "dateFrom" => date('Y-m-01'),
            "dateTo" => date('Y-m-d'),
            'proCode'=>''
        ));
        $sql = "SELECT
            y.clientId,
            y.chukuCode,
            y.kuwei,
            y.chukuDate,
            y.memo as chukuMemo,
            y.orderId,
            y.kind,
            y.clientId,
            y.creater,
            x.id,
            x.chukuId,
            x.pihao,
            x.ganghao,
            x.productId,
            x.cnt,
            x.cntJian,
            x.cntM,
            x.unit,
            x.cntOrg,
            x.money,
            x.memo,
            b.proCode,
            b.proName,
            b.guige,
            b.color,
            b.menfu,
            b.kezhong,
            b.kind as proKind,
            c.depName,
            d.compName as jiagonghuName,
            f.compName as kehuName
            from cangku_common_chuku y
            left join cangku_common_chuku2product x on y.id=x.chukuId
            left join jichu_client f on y.clientId=f.id
            left join jichu_product b on x.productId=b.id
            left join jichu_department c on y.depId=c.id
            left join jichu_jiagonghu d on y.jiagonghuId=d.id
            where y.kind='{$this->_kind}' ";

        //找到所有属于原料的库位
        $str="select kuweiName from jichu_kuwei where type='原料'";
        $res=$this->_modelExample->findBySql($str);
        $strKuwei=join("','",array_col_values($res,'kuweiName'));

        $sql .= " and y.kuwei in ('{$strKuwei}')";

        if($arr['dateFrom']!=''){
            $sql.=" and y.chukuDate>='{$arr['dateFrom']}' and y.chukuDate<='{$arr['dateTo']}'";
        }
        if($arr['proCode']!=''){
            $sql.=" and b.proCode LIKE '%{$arr['proCode']}%'";
        }
        $sql.=" order by y.chukuDate desc, y.id desc";
        $pager=& new TMIS_Pager($sql);
        $rowset=$pager->findAll();
        foreach ($rowset as & $v) {
            $v['_edit'] .= $this->getRemoveHtml(array(
                'id'=>$v['chukuId'],
                'fromAction'=>$_GET['action']
            ));// 操作栏
        }

        $heji = $this->getHeji($rowset,array('chukuCnt'),'chukuDate');
        $rowset[] = $heji;
        //dump($rowset);exit;
        $smarty = & $this->_getView();
        $smarty->assign('title', $title);
        $arr_field_info = array(
            "_edit" => '操作',
            "chukuDate" => "调库日期",
            'chukuCode' => '出库单号',
            "kuwei" => "库位",
            'proCode' => '产品编码',
            'proName' => '品名',
            'guige' => '规格',
            'color' => '颜色',
            'ganghao'=>'缸号(批号)',
            'cntJian'=>'件数',
            'menfu' => '门幅',
            'kezhong' => '克重',
            'cnt' => '数量(kg)',
            'creater' => '操作人',
        );
        $smarty->assign('arr_field_info',$arr_field_info);
        $smarty->assign('arr_field_value',$rowset);
        $smarty->assign('arr_condition', $arr);
        $smarty->assign('add_display', 'none');
        $smarty->assign('arr_js_css',$this->makeArrayJsCss(array('calendar')));
        $smarty->assign('page_info',$pager->getNavBar($this->_url($_GET['action'],$arr)));
        $smarty->display($tpl);
    }

    function actionDiaoBo(){
        // dump($_GET);die;
        $aRow = $_GET;
        // 取得库存
        $strCon = '';
        $strCon .= " and kuwei='{$aRow['kuwei']}'";
        $strCon .= " and productId = '{$aRow['productId']}'";
        $strCon .= " and pihao ='{$aRow['pihao']}'";
        $strCon .= " and ganghao = '{$aRow['ganghao']}'";
        $strCon .= " and dateFasheng<='{$aRow['dateTo']}'";
        $sql = "SELECT sum(cntFasheng) as cntKucun FROM cangku_common_kucun WHERE 1 {$strCon}";
        $temp = $this->_modelExample->findBySql($sql);
        $aRow['cntKucun'] = $temp[0]['cntKucun'];

        //库位
        $str="select kuweiName,id from jichu_kuwei where type='原料'";
        $str.=" and kuweiName<>'{$aRow['kuwei']}'";
        $res=$this->_modelExample->findBySql($str);

        $this->_modelPro = &FLEA::getSingleton('Model_Jichu_Product');
        $proDetail = $this->_modelPro->find(array('id'=>$_GET['productId']));
        // dump($proDetail);die;
        $smarty = & $this->_getView();
        $smarty->assign('aRow',$aRow);
        $smarty->assign('kuwei',$res);
        $smarty->assign('proDetail',$proDetail);
        $smarty->assign('hideMk',true);
        $smarty->display('Cangku/Yuanliao/dbKc.tpl');

    }

    function actionSaveDiaoBo(){
        // dump($_POST);exit;

        //明细信息
        $Products[] = array(
            'productId' => $_POST['productId'],
            'pihao' => $_POST['pihao'].'',
            'ganghao' => $_POST['ganghao'].'',
            'cnt' => $_POST['cntDb']+0,
            'cntJian' => $_POST['cntJianDb']+0,
            'memo' => $_POST['memo']!=''?($_POST['memo']):'',
        );
        if(!$Products){
            js_alert('保存失败，没有有效明细数据','window.history.go(-1)');
            exit;
        }
       

        //主表信息
        $arr = array(
            'kind' => $this->_kind,
            'kuwei' => $_POST['currKuwei'],
            'chukuDate' => date('Y-m-d'),
            'chukuCode' => $this->_getNewCode('DBCKA', $this->_modelMain->qtableName, 'chukuCode'),
            'creater' => $_SESSION['REALNAME'].'',
            'Products' => $Products
        );

        //验证出库的产品编号是否有库存
        $_modelKucuen = &FLEA::getSingleton('Model_Cangku_Kucun');
       
        $sql="SELECT sum(cntFasheng)as cntChu,sum(cntJian)as cntJianChu  
            from cangku_common_kucun
             where kuwei='{$_POST['currKuwei']}'
             and ganghao='{$_POST['ganghao']}' 
             and pihao='{$_POST['pihao']}' 
             and productId ='{$_POST['productId']}'";
        $aRow=$_modelKucuen->findBySql($sql);
        $aRow[0]['cntChu']=$aRow[0]['cntChu']==''?0:$aRow[0]['cntChu'];
        $aRow[0]['cntJianChu']=$aRow[0]['cntJianChu']==''?0:$aRow[0]['cntJianChu'];

        if($aRow[0]['cntChu']<$_POST['cntDb'] || $aRow[0]['cntJianChu']<$_POST['cntJianDb']){
            js_alert('产品编号'.' '.$_POST['proCode'].' '.'在该库位'.$_POST['currKuwei'].'库存为:'.$aRow[0]['cntChu'].'件数为:'.$aRow[0]['cntJianChu'],'window.history.go(-1);');
        }

        $chuId=$this->_modelExample->save($arr);
        // dump($chuId);exit;
        // $chuId = '16183';
        $chuId=$_POST['cgckId']==''?$chuId:$_POST['cgckId'];
        $ret=$this->_modelExample->find(array('id'=>$chuId));


        //查找入库信息
        $sql="SELECT x.id
              from cangku_common_ruku x
              left join cangku_common_ruku2product z on x.id=z.rukuId
              where 1 and z.dbId='{$ret['Products'][0]['id']}'";
        $ru2pro=$this->_modelExample->findBySql($sql);


         //明细信息
        $arrRu[] = array(
            'productId' => $_POST['productId'],
            'pihao' => $_POST['pihao'].'',
            'ganghao' => $_POST['ganghao'].'',
            'cnt' => $_POST['cntDb']+0,
            'cntJian' => $_POST['cntJianDb']+0,
            'memo' => $_POST['memo']!=''?($_POST['memo']):'',
            'dbId' => $ret['Products'][0]['id']
        );
        if(!$arrRu){
            js_alert('保存失败，没有有效明细数据','window.history.go(-1)');
            exit;
        }

        //找到调入库位
        $str="select kuweiName from jichu_kuwei where id='{$_POST['kuwei']}'";
        $res=$this->_modelExample->findBySql($str);

        //主表信息
        $rowruku = array(
            'id'    =>$ru2pro[0]['id'],
            'kind' => $this->_kindRu,
            'kuwei' => $res[0]['kuweiName'],
            'rukuDate' => date('Y-m-d'),
            'rukuCode' => $this->_getNewCode('DBRKA', $this->_modelRuk->qtableName, 'rukuCode'),
            'creater' => $_SESSION['REALNAME'].'',
            'Products' => $arrRu
        );

        // dump($rowruku);//exit;
        $rukuId=$this->_modelRuk->save($rowruku);
        $rukuId=$ru2pro[0]['id']?$ru2pro[0]['id']:$rukuId;

        // //保存完入库后在将出库的id保存在入库的dbId中
        // $retRuku=$this->_modelRuk->find(array('id'=>$rukuId));
        // $retChuku=array();
        // foreach ($retRuku['Products'] as & $r) {
        //     $retChuku['Products'][]=array(
        //         'dbId'=>$r['id'],
        //     );
        // }
        // $retChuku['id']=$rukuId;
        // // dump($retChuku);die;
        // $this->_modelRuk->save($retChuku);


        //跳转
        js_alert(null,'window.parent.showMsg("保存成功!")',url('Cangku_Yuanliao_Chuku','Report'));
    }


     function actionRemove(){
        //删除入库信息
        $sql="select x.id from cangku_common_ruku x
                left join cangku_common_ruku2product y on x.id=y.rukuId
                left join cangku_common_chuku2product c on c.id=y.dbId
                where c.chukuId='{$_GET['id']}'";
        $ret=$this->_modelRuk->findBySql($sql);
        // dump($sql);die;
        $this->_modelRuk->removeByPkv($ret[0]['id']);
        parent::actionRemove();
    }

}