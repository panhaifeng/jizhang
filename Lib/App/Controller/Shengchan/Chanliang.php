<?php
FLEA::loadClass('TMIS_Controller');
class Controller_Shengchan_Chanliang extends Tmis_Controller {
    // **************************************构造函数 begin********************************
    function Controller_Shengchan_Chanliang() {
        $this->_modelDefault = &FLEA::getSingleton('Model_Shengchan_Chanliang');
        $this->_modelExample = &FLEA::getSingleton('Model_Shengchan_Chanliang');
        // 定义模板中的主表字段
        $this->fldMain = array(
            'chanliangDate' => array('title' => '产量日期', "type" => "calendar", 'value' => date('Y-m-d')),
            'workCode' => array('type' => 'text', "title" => '挡车工', 'value' => ''),
            'jtType' => array('title' => '机台类型', 'type' => 'select', 'value' => '','options'=>array(
                array('text'=>'单面','value'=>'单面'),
                array('text'=>'提花','value'=>'提花'),
                array('text'=>'双面','value'=>'双面'),
            )),
            'num'=> array('title' => '机台数', 'type' => 'text', 'value' => '',),
            'numAttend'=> array('title' => '考勤机台数', 'type' => 'text', 'value' => '',),
            'jisu' => array('title' => '机速', 'type' => 'text', 'value' => ''),
            'biaozhun' => array('title' => '标准', 'type' => 'text', 'value' => ''),
            'danjiaRe' => array('title' => '单价', 'type' => 'text', 'value' => ''),
            'revolution' => array('title' => '下布转速', 'type' => 'text', 'value' => ''),
            'moneyRe' => array('title' => '金额', 'type' => 'text', 'value' => '','readonly'=>true),
            'moneyAttend'=> array('title' => '考勤工资', 'type' => 'text', 'value' => '',),
            'memo' => array('title' => '备注', 'type' => 'textarea', 'value' => ''),
            // 用来存放shengchan_chanliang表的 主键
            'id' => array('type' => 'hidden', 'value' => ''),
        );
        // 表单元素的验证规则定义
        $this->rules = array(
            'workCode' => 'required',
            'jtType' => 'required',
            'jisu' => 'number',
            'biaozhun' => 'number',
            'danjiaRe' => 'number',
            'revolution' => 'number',
        );
    }
    // **************************************构造函数 end********************************
    // ************************产量登记 begin*****************
    function actionListForAdd() {
        $this->authCheck('8-1');
        $smarty = &$this->_getView();
        $smarty->assign('fldMain', $this->fldMain);
        $smarty->assign('rules', $this->rules);
        $smarty->assign('title', '产量基本信息');
        $smarty->assign('sonTpl', 'Shengchan/_chanliang.tpl');
        $smarty->assign('hiddenReset', true);
        $smarty->display('Main/A1.tpl');
    }
    // ************************产量登记 end*****************
    // *********************产量查询 begin********************
    function actionRight() {
        $this->authCheck('8-2');
        FLEA::loadClass('TMIS_Pager');
        // /构造搜索区域的搜索类型
        $serachArea = TMIS_Pager::getParamArray(
        array(
            'dateFrom' => date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-30, date("Y"))),
            'dateTo' => date("Y-m-d"),
            'jtType' => '',
            'workCode' => '',
        ));
        $sql = "SELECT * from shengchan_chanliang where 1";

        $str = "SELECT * from shengchan_chanliang where 1";

        $con = '';
        if($serachArea['dateFrom']!='') $con .= " and chanliangDate >= '{$serachArea['dateFrom']}' and chanliangDate<='{$serachArea['dateTo']}'";
        if ($serachArea['jtType'] != '') $con .= " and jtType = '{$serachArea['jtType']}'";
        if ($serachArea['workCode'] != '') $con .= " and workCode = '{$serachArea['workCode']}'";

        $sql .= $con." order by chanliangDate desc";
         //dump($str);exit;
        //得到总计

        $rowsetAll = $this->_modelExample->findBySql($str);
        $zongji = $this->getHeji($rowsetAll,array('moneyRe','revolution'));

        $pager = &new TMIS_Pager($sql);
        $rowset = $pager->findAll();
        //dump($rowset);exit;
        if (count($rowset) > 0) foreach($rowset as &$value) {

            if ($value["guozhangId"]) {
                $tip = "ext:qtip='已过账禁止修改'";
                $value['_edit'] .= "<a href='javascript:void(0)' style='color:black' $tip>修改</a> | ";
                $value['_edit'] .= "<a $tip  >删除</a>";
            }else {
                $value['_edit'] .= "<a href='" . $this->_url('Edit', array('id' => $value['id'])) . "'>修改</a> | ";
                $value['_edit'] .= "<a href='" . $this->_url('Remove', array('id' => $value['id'])) . "'  onclick=\"return confirm('确认删除吗?')\" >删除</a> |";
            }

        }

        // 合计行
        $heji = $this->getHeji($rowset, array('num', 'moneyRe'), '_edit');//匹数合计
        $rowset[] = $heji;

        // 标题栏信息
        $arrFieldInfo = array(
            "_edit"         => '操作',
            "chanliangDate" => "产量日期",
            'workCode'      =>'挡车工',
            'num'           => '机台数',
            'jtType'        => '机台类型',
            'jisu'          => '机速',
            'biaozhun'      => '标准',
            "danjiaRe"      => '单价',
            'revolution'    =>'下布转速',
            "moneyRe"       => '金额',
            "memo"          =>'备注',
        );

        $smarty = &$this->_getView();
        $smarty->assign('title', '产量查询');
        $smarty->assign('arr_field_info', $arrFieldInfo);
        $smarty->assign('add_display', 'none');
        $smarty->assign('arr_condition', $serachArea);
        $smarty->assign('arr_field_value', $rowset);
        $msg = "<font color='red'>下布转速总计:{$zongji['revolution']}|金额总计：{$zongji['moneyRe']}</font>";
        $smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action']), $serachArea).$msg);
        $smarty->assign('arr_js_css', $this->makeArrayJsCss(array('grid', 'calendar')));
        $smarty->display('TableList.tpl');
    }
    // *********************产量查询 end********************
    // ********************编辑 begin********************
    function actionEdit() {
        //dump($_GET);exit;
        $arr = $this->_modelDefault->find(array('id' => $_GET['id']));
        //dump($arr);exit;
        // /加载数据到fldMain中
        $this->fldMain['chanliangDate']['value'] = $arr['chanliangDate'];
        $this->fldMain['workCode']['value'] = $arr['workCode'];
        $this->fldMain['jtType']['value'] = $arr['jtType'];
        $this->fldMain['num']['value'] = $arr['num'];
        $this->fldMain['numAttend']['value'] = $arr['numAttend'];
        $this->fldMain['jisu']['value'] = $arr['jisu'];
        $this->fldMain['biaozhun']['value'] = $arr['biaozhun'];
        $this->fldMain['danjiaRe']['value'] = $arr['danjiaRe'];
        $this->fldMain['revolution']['value'] = $arr['revolution'];
        $this->fldMain['moneyRe']['value'] = $arr['moneyRe'];
        $this->fldMain['moneyAttend']['value'] = $arr['moneyAttend'];
        $this->fldMain['memo']['value'] = $arr['memo'];

        // 加载隐藏字段
        $this->fldMain['id']['value'] = $arr['id'];

        $smarty = &$this->_getView();
        $smarty->assign('fldMain', $this->fldMain);
        $smarty->assign('title', '产量基本信息');
        // $smarty->assign('rowsSon',$rowsSon);
        $smarty->assign('rules', $this->rules);
        $smarty->assign('sonTpl', 'Shengchan/_chanliang.tpl');
        $smarty->display('Main/A1.tpl');
    }
    // ********************编辑 end***********************
    // ********************保存 begin*****************
    function actionSave() {
        //dump($_POST);exit;
        $shengchan_chanliang = array(
            'id'            => $_POST['id'],
            'chanliangDate' => $_POST['chanliangDate'],
            'workCode'      => $_POST['workCode'],
            'jtType'        => $_POST['jtType'],
            'jisu'          => $_POST['jisu'],
            'num'          => $_POST['num'],
            'numAttend'          => $_POST['numAttend'],
            'biaozhun'      => $_POST['biaozhun'],
            'danjiaRe'      =>$_POST['danjiaRe'],
            'revolution'    =>$_POST['revolution'],
            'moneyRe'       =>$_POST['moneyRe'],
            'moneyAttend'       =>$_POST['moneyAttend'],
            'memo'          => $_POST['memo'],
        );
        //dump($shengchan_chanliang);exit;
        $itemId = $this->_modelExample->save($shengchan_chanliang);
        if ($itemId) {
            if ($_POST['id']>0) {
                js_alert('保存成功！', '', $this->_url('Right'));
            }else {
                js_alert('保存成功！', '', $this->_url('ListForAdd'));
            }
        }else die('保存失败!');
    }
    // ********************保存 end*********************
    // *****************控件弹出 begin**********************
    function actionPopup() {
        FLEA::loadClass('TMIS_Pager');
        $arr = TMIS_Pager::getParamArray(array('dateFrom' => date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-30, date("Y"))),
                'dateTo' => date("Y-m-d"),
                'key' => '',
                ));

        $sql = "select y.clientId,
                     y.orderDate,
                     y.orderCode,
                     y.isCheck,
                     x.productId,
                     x.id,
                     x.cntYaohuo,
                     x.orderId as orderId
                     from trade_order2product x
                     left join trade_order y on (x.orderId = y.id)";

        $str = "select
        x.cntYaohuo,
        x.orderId,
        x.id,
        x.orderCode,
        x.orderDate,
        x.productId,
        x.isCheck,
        y.compName,
        z.proCode,
        z.proName,
        z.zhonglei,
        z.guige,
        z.color,
        z.menfu,
        z.kezhong
        from (" . $sql . ") x
        left join jichu_client y on x.clientId = y.id
        left join jichu_product z on x.productId = z.id
                where 1 ";

        $str .= " and orderDate >= '{$arr[dateFrom]}' and orderDate<='{$arr[dateTo]}'";
        if ($arr['key'] != '') $str .= " and (x.orderCode like '%{$arr[key]}%'
                      or z.proName like '%{$arr[key]}%'
                      or z.proCode like '%{$arr[key]}%'
                      or z.guige like '%{$arr[key]}%')";
        $str .="order by orderDate desc";
        $pager = &new TMIS_Pager($str);
        $rowset = $pager->findAllBySql($str);
        // if(count($rowset)>0)foreach ($rowset as $i=> & $v ) {
        // }
        // dump($rowset);exit;
        // 标题栏信息
        $arrFieldInfo = array(
            "orderDate" => '订单日期',
            "orderCode" => '生产编号',
            // "compName" => "客户",
            'proCode' => '产品编码',
            'proName' => '品名',
            'zhonglei' => '种类',
            'guige' => '规格',
            'color' => '颜色',
            'cntYaohuo' => '要货数量',
            // ''=>'',
            );

        $smarty = &$this->_getView();
        $smarty->assign('title', '订单选择');
        $smarty->assign('add_display', 'none');
        $smarty->assign('arr_field_info', $arrFieldInfo);
        $smarty->assign('arr_field_value', $rowset);
        $smarty->assign('arr_js_css', $this->makeArrayJsCss(array('thickbox')));
        $smarty->assign('arr_condition', $arr);
        $smarty->assign('page_info', $pager->getNavBar($this->_url('Popup', $arr)));
        $smarty->display('Popup/CommonNew.tpl');
    }
    // ****************控件弹出 end************************

    //*产量统计报表
    function actionReport(){
        $this->authCheck('8-3');
        FLEA::loadClass('TMIS_Pager');
        // /构造搜索区域的搜索类型
        $serachArea = TMIS_Pager::getParamArray(
        array(
            'dateFrom' => date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-30, date("Y"))),
            'dateTo' => date("Y-m-d"),
            'workCode'=>'',
        ));


        //按挡车工，机台类型，汇总对应的机台数及下布转速及金额。
        $sql = "SELECT sum(revolution) as revolution,
                       sum(moneyRe) as moneyRe,
                       sum(num) as num,
                       sum(numAttend) as numAttend,
                       sum(moneyAttend) as moneyAttend,
                       workCode,
                       jtType
                FROM shengchan_chanliang
                where 1";

        $sql .= " and chanliangDate >= '{$serachArea['dateFrom']}' and chanliangDate<='{$serachArea['dateTo']}'";

        if ($serachArea['workCode'] != '') $sql .= " and  workCode like '%{$serachArea['workCode']}%'";

        $sql .= " group by workCode,jtType order by workCode asc, jtType asc";
        //dump($str);exit;
        $rowset = $this->_modelExample->findBySql($sql);

        // 合计行
        $heji = $this->getHeji($rowset, array('num','revolution','moneyRe'), 'workCode');

        foreach ($rowset as & $v) {
            $v['revolution'] = "<a href='".$this->_url('Right',array(
                                    'dateFrom'=>$v['dateFrom'],
                                    'dateTo'=>$v['dateTo'],
                                    'workCode'=>$v['workCode'],
                                    'width'=>'700',
                                    'no_edit'=>1,
                                    'TB_iframe'=>1,
                                ))."' class='thickbox' title='查看详情'>{$v['revolution']}</a>";

            $v['moneyRe'] = "<a href='".$this->_url('Right',array(
                                    'dateFrom'=>$v['dateFrom'],
                                    'dateTo'=>$v['dateTo'],
                                    'workCode'=>$v['workCode'],
                                    'width'=>'700',
                                    'no_edit'=>1,
                                    'TB_iframe'=>1,
                                ))."' class='thickbox' title='查看详情'>{$v['moneyRe']}</a>";
        }
        $rowset[] = $heji;

        // 标题栏信息
        $arrFieldInfo = array(
            "workCode"      => '挡车工',
            'jtType'        => '机台类型',
            'num'           => '机台数',
            'numAttend'        => '考勤机台',
            "revolution"    => '下布转速',
            "moneyRe"       => '金额',
            'moneyAttend'  => '考勤工资',
        );

        $smarty = &$this->_getView();
        $smarty->assign('title', '产量统计报表');
        $smarty->assign('arr_field_info', $arrFieldInfo);
        $smarty->assign('add_display', 'none');
        $smarty->assign('arr_condition', $serachArea);
        $smarty->assign('arr_field_value', $rowset);

        $smarty->assign('arr_js_css', $this->makeArrayJsCss(array('grid', 'calendar')));
        $smarty->display('TableList.tpl');
    }

}

?>