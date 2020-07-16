<?php
FLEA::loadClass('TMIS_Controller');
class Controller_Jichu_Product extends Tmis_Controller {
    var $_modelExample;
    var $_modelPro;
    var $fldMain;
    // /构造函数
    function Controller_Jichu_Product() {
        $this->_state = '原料';
        $this->_modelExample = &FLEA::getSingleton('Model_Jichu_Product');
        $this->_modelPro = & FLEA::getSingleton('Model_Jichu_ProductSon');
        // 得到所有的历史成分
        // $sql = "select zhonglei from jichu_product group by zhonglei";
        // $rowset = $this->_modelExample->findBySql($sql);
        // foreach($rowset as &$v) {
        // 	$opt[] = array('text' => $v['zhonglei'], 'value' => $v['zhonglei']);
        // }
        // 得到所有的历史颜色
        $sql = "select color from jichu_product group by color";
        $rowset = $this->_modelExample->findBySql($sql);
        foreach($rowset as &$v) {
            $color[] = array('text' => $v['color'], 'value' => $v['color']);
        }
        $this->fldMain = array('kind' => array('title' => '分类', "type" => "select", 'value' => '', 'options' => array(
                    array('text' => '色纱', 'value' => '色纱'),
                    array('text' => '坯纱', 'value' => '坯纱'),
                    array('text' => '丝类', 'value' => '丝类'),
                    array('text' => '氨纶', 'value' => '氨纶'),
                    array('text' => '其他', 'value' => '其他'),
                    )),
            'proCode' => array('title' => '物料编号', "type" => "text", 'value' =>$this->getproCode()),
            'proName' => array('title' => '品名', 'type' => 'text', 'value' => ''),
            'guige' => array('title' => '规格', 'type' => 'text', 'value' => ''),
            'dengji' => array('title' => '等级', 'type' => 'text', 'value' => ''),
            'color' => array('title' => '颜色', 'type' => 'text', 'value' => ''),
            'chengFen' => array('title' => '成份', 'type' => 'text', 'value' => '','readonly'=>true),
            // 'menfu' => array('title' => '门幅', 'type' => 'text', 'value' => ''),
            // 'kezhong' => array('title' => '克重', 'type' => 'text', 'value' => ''),
            'memo' => array('title' => '备注说明', 'type' => 'textarea', 'value' => ''),
            'id' => array('type' => 'hidden', 'value' => ''),
            'state' => array('type' => 'hidden', 'value' => '0'),
            'creater' => array('type' => 'hidden', 'value' => $_SESSION['REALNAME']),
            // 'kind'=>array('value'=>''),
            );

        $this->headSon = array(
            '_edit'=>array('type'=>'btBtnRemove',"title"=>'+5行','name'=>'_edit[]'),
            'chengfenPer'=>array('type'=>'bttext',"title"=>'成份占比%','name'=>'chengfenPer[]'),
            'component'=>array('type'=>'btselect',"title"=>'成分','value'=>'','name'=>'component[]','model'=>'Model_jichu_Chengfen'),
            'id'=>array('type'=>'bthidden','name'=>'chfId[]'),
            );
    }

    function actionRight() {
        FLEA::loadClass('TMIS_Pager');
        $arr = TMIS_Pager::getParamArray(array(
                "kindSha" => "",
                'key'     => '',
        ));
        $str = "select * from jichu_product where 1 and state=0";
        if ($arr['key'] != '') $str .= " and (proCode like '%{$arr['key']}%'
                        or proName like '%{$arr['key']}%'
                        or guige like '%{$arr['key']}%')";
        $arr['kindSha'] && $str .= " and kind='{$arr['kindSha']}'";
        $str .= " order by convert(proName USING gbk) asc";
        // dump($str);exit;
        if($_GET['export']==1){
          $rowset = $this->_modelExample->findBySql($str);
        }else{
          $pager =& new TMIS_Pager($str);
          $rowset =$pager->findAllBySql($str);
        }
        // $pageInfo = $pager->getNavBar('Index.php?'.$pager->getParamStr($arr));
        if (count($rowset) > 0) foreach($rowset as &$v) {
            $v['_edit'] = $this->getEditHtml($v['id']) . ' ' . $this->getRemoveHtml($v['id']);
            $v['_edit'] .="&nbsp;&nbsp;"."<a href='".$this->_url('Edit',array(
                    'id'=>$v['id'],
                    'flag'=>1,
                    'no_edit'=>1,
                    'TB_iframe'=>1
                    ))."' title='查看'>查看</a>";
            $v['_edit'] .= " <a href='".$this->_url('PrintBarCode',array(
                'id'=>$v['id']
            ))."' target='_blank'>条码</a>";
        }
        $smarty = &$this->_getView();
        // $smarty->assign('title', '选择产品');
        $smarty->assign('title', '纱档案');
        $pk = $this->_modelExample->primaryKey;
        $smarty->assign('pk', $pk);
        $arr_field_info = array("_edit" => '操作',
            "kind" => array('text'=>'类别','width'=>'50px'),
            "proCode" => array('text'=>'产品编号','width'=>'70px'),
            "proName" => array('text'=>'品名','width'=>'150px'),
            "color" => array('text'=>'颜色','width'=>'70px') ,
            "guige" => array('text'=>'规格','width'=>'150px'),
            "dengji" => "等级",
            "chengFen" =>"成份",
            'memo'=>'备注',
            'creater'=>array('text'=>'录入人','width'=>'70px')
            );
        $smarty->assign('arr_field_info', $arr_field_info);
        $smarty->assign('arr_field_value', $rowset);
        // $smarty->assign('add_display','none');
        //导出页面上所有信息
        if($_GET['export']==1){
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: application/force-download");
            header("Content-Type: application/download");
            header("Content-Disposition: attachment;filename=Product.xls");
            header("Content-Transfer-Encoding: binary");
            $smarty->display('Export2Excel2.tpl');
            exit;
        }
        $smarty->assign('fn_export',$this->_url($_GET['action'],$arr+array('export'=>1)));
        $smarty->assign('arr_js_css', $this->makeArrayJsCss(array('thickbox')));
        $smarty->assign('arr_condition', $arr);
        $smarty->assign('page_info', $pager->getNavBar($this->_url($_GET['action'], $arr)));
        $smarty->display('TblList.tpl');
    }
    // **************************弹出产品信息 begin***************************
    function actionPopup() {
        // dump($_GET);exit;
        FLEA::loadClass('TMIS_Pager');
        $arr = TMIS_Pager::getParamArray(array(
            'key' => '',
            'kindSha' => '',
        ));
        // var_dump(empty($_GET['proKind']));exit;
        if (isset($_GET['proKind'])) {
            // 如果$_GET['proKind']不为空 并且url过来的kind=0 表示要选择的是色坯纱;否则选择的是成品
            if ($_GET['proKind'] == 0) {
                $sql = " and kind in ('坯纱','色纱','针织','丝类','氨纶','其他')";
            }else {
                $sql = " and kind not in ('坯纱','色纱','针织','丝类','氨纶','其他')";
            }
        }else {
            $sql = "";
        }
        // dump($sql);exit;
        $str = "select * from jichu_product where 1 and state=0" . $sql;

        if ($arr['key'] != '')
        {
            $str .= " and (proCode like '%$arr[key]%'
                        or proName like '%$arr[key]%'
                        or guige like '%$arr[key]%')";
        }

        if ($arr['kindSha'] != '')
        {
            $str .= " and kind='$arr[kindSha]'";
        }

        $str .= " order by proCode asc,proName asc,guige asc"; //dump($str);exit;

        $pager = &new TMIS_Pager($str);

        $rowset = $pager->findAllBySql($str);
        // $pageInfo = $pager->getNavBar('Index.php?'.$pager->getParamStr($arr));



        if (count($rowset) > 0)
        {
            foreach($rowset as &$v)
            {
                //显示 
                $v["_proName"] = $v["proName"];
                $v["proName"] = $v["proName"];
                $strChF = "select c.chengfenPer,cc.name from jichu_product p left join  jichu_product_chengfen c on p.id=c.proId left join jichu_chengfen cc on c.component=cc.id where p.id ='{$v['id']}'";
                $pagerChF = &new TMIS_Pager($strChF);
                $rowsetChF = $pagerChF->findAllBySql($strChF);
                foreach($rowsetChF as $key=>$value) 
                {
                    $v['chengfenPer'][]=$value['chengfenPer'];
                    $v['component'][]=$value['name']; 
                }
            }
        }   

        // dump($rowset);exit();

        $pk = $this->_modelExample->primaryKey;
        $arr_field_info = array(
            "kind" => "分类",
            "proCode" => "编码",
            "_proName" => array('text'=>"产品名称", 'width'=>220),
            "guige" => array('text'=>"规格", 'width'=>220),
            "chengFen" => "成分",
            "color" => "颜色",
            "dengji" => "等级",
        );

        $smarty = &$this->_getView();
        $smarty->assign('title', '选择产品');
        $smarty->assign('pk', $pk);
        $smarty->assign('arr_field_info', $arr_field_info);
        $smarty->assign('arr_field_value', $rowset);
        $smarty->assign('add_display', 'none');
        $smarty->assign('arr_js_css', $this->makeArrayJsCss(array('thickbox')));
        $smarty->assign('arr_condition', $arr);
        $smarty->assign('page_info', $pager->getNavBar($this->_url('Popup', $arr)));
        $smarty->display('Popup/CommonNew.tpl');
    }

        // **************************弹出产品信息 begin***************************
    function actionPopupNew() {
        //dump($_GET);exit;
        FLEA::loadClass('TMIS_Pager');
        $arr = TMIS_Pager::getParamArray(array(
            'key' => '',
            'kindSha' => '',
            'supplierId'=>$_GET['supplierId']
            //'supplierId'=>$_GET['supplierId']
        ));
        //找到所有属于成品的库位
        $str="select kuweiName from jichu_kuwei where type='{$this->_state}'";
        $res=$this->_modelExample->findBySql($str);
        $strKuwei=join("','",array_col_values($res,'kuweiName'));
        // var_dump(empty($_GET['proKind']));exit;
        if (isset($_GET['proKind'])) {
            // 如果$_GET['proKind']不为空 并且url过来的kind=0 表示要选择的是色坯纱;否则选择的是成品
            if ($_GET['proKind'] == 0) {
                $sql = " and kind in ('坯纱','色纱','针织','丝类','氨纶','其他')";
            }else {
                $sql = " and kind not in ('坯纱','色纱','针织','丝类','氨纶','其他')";
            }
        }else {
            $sql = "";
        }
        if($arr['supplierId']!=''){
            $strSl = " and s.id={$arr['supplierId']}";
        }
        // dump($sql);exit;
       // $str = "select * from jichu_product where 1 and state=0" . $sql;
        $str = "select t.*
                from jichu_product t
                left join cangku_common_ruku2product r on r.productId=t.id
                left join cangku_common_ruku u on u.id=r.rukuId
                left join jichu_supplier s on s.id=u.supplierId
                where 1 ".$strSl." and t.state=0" . $sql;
        if ($arr['key'] != '')
        {
            $str .= " and (proCode like '%$arr[key]%'
                        or proName like '%$arr[key]%'
                        or guige like '%$arr[key]%')";
        }

        if ($arr['kindSha'] != '')
        {
            $str .= " and t.kind='$arr[kindSha]'";
        }
        $str .= " group by t.id";
        $str .= " order by proCode asc,proName asc,guige asc"; //dump($str);exit;

        $pager = &new TMIS_Pager($str);

        $rowset = $pager->findAllBySql($str);
        // $pageInfo = $pager->getNavBar('Index.php?'.$pager->getParamStr($arr));



        if (count($rowset) > 0)
        {
            foreach($rowset as &$v)
            {
                //显示 
                $v["_proName"] = $v["proName"];
                $v["proName"] = $v["proName"];
                $strChF = "select c.chengfenPer,cc.name from jichu_product p left join  jichu_product_chengfen c on p.id=c.proId left join jichu_chengfen cc on c.component=cc.id where p.id ='{$v['id']}'";
                $pagerChF = &new TMIS_Pager($strChF);
                $rowsetChF = $pagerChF->findAllBySql($strChF);
                foreach($rowsetChF as $key=>$value) 
                {
                    $v['chengfenPer'][]=$value['chengfenPer'];
                    $v['component'][]=$value['name']; 
                }
            }
        }   

        // dump($rowset);exit();

        $pk = $this->_modelExample->primaryKey;
        $arr_field_info = array(
            "kind" => "分类",
            "proCode" => "编码",
            "_proName" => array('text'=>"产品名称", 'width'=>220),
            "guige" => array('text'=>"规格", 'width'=>220),
            "chengFen" => "成分",
            "color" => "颜色",
            "dengji" => "等级",
        );

        $smarty = &$this->_getView();
        $smarty->assign('title', '选择产品');
        $smarty->assign('pk', $pk);
        $smarty->assign('arr_field_info', $arr_field_info);
        $smarty->assign('arr_field_value', $rowset);
        $smarty->assign('add_display', 'none');
        $smarty->assign('arr_js_css', $this->makeArrayJsCss(array('thickbox')));
        $smarty->assign('arr_condition', $arr);
        $smarty->assign('page_info', $pager->getNavBar($this->_url('Popup', $arr)));
        $smarty->display('Popup/CommonNew.tpl');
    }
    function actionPopup2() {
        // dump($_GET);exit;
        FLEA::loadClass('TMIS_Pager');
        $arr = TMIS_Pager::getParamArray(array('key' => '',
                'kindPro' => '',
        ));
        // var_dump(empty($_GET['proKind']));exit;
        if (isset($_GET['proKind'])) {
            // 如果$_GET['proKind']不为空 并且url过来的kind=0 表示要选择的是色坯纱;否则选择的是成品
            if ($_GET['proKind'] == 0) {
                $sql = " and kind in ('坯纱','色纱','针织','丝类','氨纶','其他')";
            }else {
                $sql = " and kind not in ('坯纱','色纱','针织','丝类','氨纶','其他')";
            }
        }else {
            $sql = "";
        }
        // dump($sql);exit;
        $str = "select * from jichu_product where 1 and isStop=0" . $sql;

        if ($arr['key'] != '') {

            if(strpos($arr['key'], '+')){
                $tempKey = explode('+', $arr['key']);
                foreach ($tempKey as & $v) {
                  $strObj[] = " guige like '%$v%'";
                }
                $guigeStr = join(' and ', $strObj);
                $str .= " and (proCode like '%$arr[key]%'
                                    or proName like '%$arr[key]%'
                                    or ({$guigeStr}))";
            }else{
                $str .= " and (proCode like '%$arr[key]%'
                                    or proName like '%$arr[key]%'
                                    or guige like '%$arr[key]%')";
            }
        }
        if ($arr['kindPro'] != '') {
            $str .= " and kind='$arr[kindPro]'";
        }
        $str .= " order by proCode asc,proName asc,guige asc";
        // dump($str);exit;
        $pager = &new TMIS_Pager($str);
        $rowset = $pager->findAllBySql($str);
        // dump($rowset);die;
        // $pageInfo = $pager->getNavBar('Index.php?'.$pager->getParamStr($arr));
        if (count($rowset) > 0) foreach($rowset as &$v) {
            //显示
            $v["_proName"] = $v["proName"];
            $v["proName"] = $v["proName"];
            $v['guige'] = $v['guige'];

            if($v['imageFile']!=''){
                $v['imageFile'] = "<a href='".url('Jichu_Chanpin','showImage',array(
                      'barCode'=>$v['barCode'],
                      'img'=>$v['imageFile']!=''?$v['imageFile']:'',
                      'bimg'=>$v['bigimageFile']!=''?$v['bigimageFile']:'',
                      //'height'=>'450',
                      'baseWindow'=>'parent',
                      'TB_iframe'=>1
                ))."'  class='thickbox' title='查看图片'><img src='Resource/Image/img.gif' style='border:0px'></a>";
            }
            $temp = array();
            $rowsSon= $this->_modelPro->findAll(array('proId'=>$v['id']));
            foreach ($rowsSon as $k => &$a) {
                if(!$a['xianchang']) continue;
                $temp[] = $a['xianchang'];
            }
            if($temp){
                $v['xianchang'] = join('/',$temp);
            }
        }
        // dump($rowset);die;
        $smarty = &$this->_getView();
        $smarty->assign('title', '选择产品');
        $pk = $this->_modelExample->primaryKey;
        $smarty->assign('pk', $pk);
        $arr_field_info = array(
                "kind" => "分类",
                "proCode" => "编码",
                "imageFile" => array('text'=>"图片文件",'width'=>70),
                "_proName" => array('text'=>'产品名称','width'=>'220px'),
                "guige" => array('text'=>'规格','width'=>'300px'),
                "color" => "颜色",
                "chengFen" => array('text'=>'成份','width'=>'220px'),
                "dengji" => "等级",
                "xianchang" => "线长",
                "zhengshu" => "针数",
                "cunshu" => "寸数",
        );
        if($_GET['proKind']==1){
             $arr_field_info = array(
                "kind" => "分类",
                "proCode" => "编码",
                "imageFile" => array('text'=>"图片文件",'width'=>70),
                "_proName" => array('text'=>'产品名称','width'=>'220px'),
                "guige" => array('text'=>'规格','width'=>'300px'),
                "color" => "颜色",
                "menfu" => "门幅",
                "kezhong" => "克重",
                "chengFen" => array('text'=>'成份','width'=>'220px'),
                "dengji" => "等级",
                "xianchang" => "线长",
                "zhengshu" => "针数",
                "cunshu" => "寸数",
            );
        }
        //dump($arr);die;
        $smarty->assign('arr_field_info', $arr_field_info);
        $smarty->assign('arr_field_value', $rowset);
        $smarty->assign('add_display', 'none');
        $smarty->assign('arr_js_css', $this->makeArrayJsCss(array('thickbox')));
        $smarty->assign('arr_condition', $arr);
        $smarty->assign('page_info', $pager->getNavBar($this->_url('Popup2', $arr+$_GET)));
        $smarty->display('Popup/CommonNew.tpl');
    }
    // **************************弹出产品信息 end***************************
    function actionAdd() {
        // $smarty = &$this->_getView();
        // $smarty->assign('fldMain', $this->fldMain);
        // $smarty->assign('title', '原料信息编辑');
        // $smarty->display('Main/A2.tpl');

        $areaMain = array('title' => '原料信息编辑', 'fld' => $this->fldMain);
        // 从表信息字段,默认5行
        for($i = 0;$i < 5;$i++) {
          $rowsSon[] = array();
        }
        $smarty = &$this->_getView();
        $smarty->assign('areaMain', $areaMain);
        $smarty->assign('headSon', $this->headSon);
        $smarty->assign('rowsSon', $rowsSon);
        $smarty->assign('rules', $this->rules);
        $smarty->assign('sonTpl',"Jichu/proSonTpl1.tpl");
        $smarty->display('Main2Son/T4.tpl');
    }

    function actionEdit() {
        if($_GET['flag']){
          //查看
          $this->authCheck('6-3-3');
          foreach($this->fldMain as & $v){
            $v['disabled']='disabled';
          }
          foreach($this->headSon as & $vv){
            $vv['readonly']=true;
          }
        }else{
          //修改
          $this->authCheck('6-3-1');
        }
        $arr = $this->_modelExample->find(array('id' => $_GET['id']));

        foreach ($this->fldMain as $k => &$v) {
            $v['value'] = $arr[$k]?$arr[$k]:$v['value'];
        }
        foreach($arr['Products'] as &$v) {
            // dump($v);exit;
            $temp = array();
            foreach($this->headSon as $kk => &$vv) {
                $temp[$kk] = array('value' => $v[$kk]);
            }
            $rowsSon[] = $temp;
        }
        // dump($rowsSon);exit;
        //补齐5行
        $cnt = count($rowsSon);
        for($i=5;$i>$cnt;$i--) {
            $rowsSon[] = array();
        }
        $smarty = &$this->_getView();
        $smarty->assign('areaMain',array('title' => '原料信息编辑', 'fld' => $this->fldMain));
        $smarty->assign('headSon', $this->headSon);
        $smarty->assign('rowsSon', $rowsSon);
        $smarty->assign('rules', $this->rules);
        $smarty->assign('sonTpl',"Jichu/proSonTpl1.tpl");   
        $smarty->assign('print', 'yes');
        $smarty->assign('flag', $_GET['flag']);
        //T4.tpl在flag==1时没有保存按钮只有返回按钮
        $smarty->display('Main2Son/T4.tpl');
    }

    function actionSave() {
        // dump($_POST);exit;
        // 确保产品编码,品名,规格,颜色都存在
        if (!$_POST['kind']) {
            js_alert('请选择类别!', null, $this->_url($_POST['fromAction']));
            exit;
        }
        if (!$_POST['proCode']) {
            js_alert('产品编码缺失!', null, $this->_url($_POST['fromAction']));
            exit;
        }else {
            // 产品编码不重复
            $sql = "select count(*) cnt from jichu_product where proCode='{$_POST['proCode']}' and id<>'{$_POST['id']}'";
            $_rows = $this->_modelExample->findBySql($sql);
            if ($_rows[0]['cnt'] > 0) {
                js_alert('产品编码重复!', "window.history.go(-1)");
                exit;
            }
        }
        if (!$_POST['proName']) {
            js_alert('品名缺失!', null, $this->_url($_POST['fromAction']));
            exit;
        }
        if (!$_POST['guige']) {
            js_alert('规格缺失!', null, $this->_url($_POST['fromAction']));
            exit;
        }
        // if (!$_POST['color']) {
        // 	js_alert('颜色缺失!', null, $this->_url($_POST['fromAction']));
        // 	exit;
        // }
        // dump($_POST);exit;

        foreach ($_POST['component'] as $k =>& $v){
            if(empty($_POST['component'][$k])) continue;
            $chengfen[]=array(
              'id'=>$_POST['chfId'][$k],
              'chengfenPer'=>$_POST['chengfenPer'][$k],
              'component'=>$_POST['component'][$k]
            );
        }

        $row=array(
            'id'=>$_POST['id'],
            'kind' =>$_POST['kind'],
            'proCode' =>$_POST['proCode'],
            'proName' =>$_POST['proName'],
            'guige' =>$_POST['guige'],
            'color' => $_POST['color'],
            'chengFen' =>$_POST['chengFen'],
            'dengji' =>$_POST['dengji'],
            'memo' =>$_POST['memo'],
            'state' =>$_POST['state'],
            'creater' =>$_POST['creater'],
            'Products' =>$chengfen
        );
        //dump($row);exit;
        $id = $this->_modelExample->save($row);
        js_alert(null, 'window.parent.showMsg("保存成功")',$this->_url('right'));
        exit;
    }

    /**
     * 打印条码标签
     * by jeff
     */
    function actionPrintBarCode() {
        $row=$this->_modelExample->find(array('id'=>$_GET['id']));
        // $row['guige'] = str_replace('（','(',$row['guige']);
        // $row['guige'] = str_replace('）',')',$row['guige']);
        // $row['guige'] = preg_replace('/([\x80-\xff]*)/i','',$row['guige']);
        // $row['guige'] = str_replace('()','',$row['guige']);
        // $row['guige'] = "";
        // $row['proKind']=($row['proColor']!=''?$row['proColor'].' ':'').$row['proKind'];
        // dump($row);exit;

            // 	for($i=0;$i<$length;$i++)
            // 	if(ord($str_cut[$i])>10) $i++;
            // 	$str_cut=substr($str_cut,0,$i)."..";
            // }
            // 	return $str_cut;
        $str_cut=$row[proName];
            if (strlen($str_cut) > 30)
            {
                for($i=0; $i < 30; $i++)
                if (ord($str_cut[$i]) > 128)    $i++;
                $row[proName] = substr($str_cut,0,$i)."";
            }
                $str_cut=$row[guige];
            if (strlen($str_cut) > 25)
            {
                for($i=0; $i < 25; $i++)
                if (ord($str_cut[$i]) > 128)    $i++;
                $row[guige] = substr($str_cut,0,$i)."";
            }
        // dump($row);exit;
        $smarty=& $this->_getView();
        $smarty->assign('aRow',$row);
        $smarty->display('JiChu/PrintBarcode.tpl');
    }

    #展会系统中，导入产品
    function actionGetProduct(){
        $str="select * from jichu_product where 1 and state=1"; //金马只同步布档案 2018年9月19日 by shen
        $row=$this->_modelExample->findBySql($str);

        $data=array(
            'proName'=>array_col_values($row,'proName'),
            'proCode'=>array_col_values($row,'proCode'),
            'color'=>array_col_values($row,'color'),
            'guige'=>array_col_values($row,'guige'),
            'chengFen'=>array_col_values($row,'chengFen'),
            'menfu'=>array_col_values($row,'menfu'),
            'kezhong'=>array_col_values($row,'kezhong'),
            'kind'=>array_col_values($row,'kind'),
            'huaxing'=>array_col_values($row,'huaxing'),
            'clientHuaxing'=>array_col_values($row,'clientHuaxing'),
            'shazhi'=>array_col_values($row,'shazhi'),
            'jingwei'=>array_col_values($row,'jingwei'),
            'memo'=>array_col_values($row,'memo'),
        );
        echo json_encode($data);
        exit;
    }
    /**
     * @desc ：自动生成物料编号
     * Time：2016/06/15 15:19:10
     * @author czb
     * @param null
     * @return string
    */
    function getproCode(){
        $begin="0001";
        $str="SELECT * FROM `jichu_product` where state=0 order by proCode desc limit 0,1";
        //echo $str;exit;
        $re=mysql_fetch_assoc(mysql_query($str));
        // dump($re);exit;
        if($re['proCode']!='')
        {
            $max=$re['proCode'];
            $next=$max+10001;
            return substr($next,1);
        }else{
            return $begin;
        }
    }
    function actionRemove() {
        $this->authCheck('6-3-2');
        parent::actionRemove();
    }
}