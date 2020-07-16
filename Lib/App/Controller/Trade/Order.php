<?php
FLEA::loadClass('TMIS_Controller');
class Controller_Trade_Order extends Tmis_Controller {
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
        $this->_modelFwSon = &FLEA::getSingleton('Model_Shengchan_Waixie_FawaiSon');
        $this->_modelCkSon = &FLEA::getSingleton('Model_Cangku_Chengpin_Chuku2Product');
        // 订单类型对应中文以及前缀
        $this->orderTypeMap = array(
            '0' => array('cnName'=>'大货','head'=>'JM'),
            '1' => array('cnName'=>'本厂开发','head'=>'KF'),
            '2' => array('cnName'=>'客户打样','head'=>'DY'),
            '3' => array('cnName'=>'来料加工','head'=>'JG')
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
            'orderType'=>array('title' => '订单类型','type' => 'select','value' =>'0','options'=>array(
                    array('text'=>'大货','value'=>'0'),
                    array('text'=>'本厂开发','value'=>'1'),
                    array('text'=>'客户打样','value'=>'2'),
                    array('text'=>'来料加工','value'=>'3'),
                )),
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
    // ******************************构造函数 end******************************************
    // ***************************** 订单查询 begin*************************************
    function actionRight() {
        $this->authCheck('1-2');
        FLEA::loadClass('TMIS_Pager');
        // /构造搜索区域的搜索类型
        $serachArea = TMIS_Pager::getParamArray(array(
            'dateFrom' => date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-30, date("Y"))),
            'dateTo' => date("Y-m-d"),
            'orderType'=>'10',
            'clientId' => '',
            'traderId' => '',
            'isCheck' =>0,
            'isOver' =>0,
            'orderCode' => '',
            'key' => '',
            'guige' => '',
            'proCode'=> '',
            'tradeMemo'=>''
            ));
        // dump($serachArea);exit;
        $sql = "SELECT y.id,
                       y.orderCode,
                       y.orderDate,
                       y.clientId,
                       y.traderId,
                       y.clientOrder,
                       y.memo as orderMemo,
                       y.bizhong,
                       y.isCheck,
                       y.isOver,
                       y.orderType,
                       y.orderTypeXq,
                       y.xiaoshouName,
                       t.productId,
                       t.id as ord2proId
                from trade_order y
                join trade_order2product t on y.id =t.orderId
                where 1";
        $sql .= " and y.orderDate >= '$serachArea[dateFrom]' and y.orderDate<='$serachArea[dateTo]'";
        if ($serachArea['isCheck'] < 2) $sql .= " and y.isCheck = '$serachArea[isCheck]'";
        if ($serachArea['isOver'] < 2) $sql .= " and y.isOver = '$serachArea[isOver]'";
        if ($serachArea['orderCode'] != '') $sql .= " and y.orderCode like '%$serachArea[orderCode]%'";
        if($serachArea['tradeMemo']!='') $sql .= " and y.memoTrade like '%{$serachArea['tradeMemo']}%'";
        //订单类型
        if($serachArea['orderType'] != ''){
            if($serachArea['orderType']!=10){
                $sql .=" and y.orderType='$serachArea[orderType]'";
            }
        }
        if ($serachArea['clientId'] != '') $sql .= " and y.clientId = '$serachArea[clientId]'";
        if ($serachArea['traderId'] != '') $sql .= " and y.traderId = '$serachArea[traderId]'";

        $str = "select
                x.orderCode,
                x.orderDate,
                x.clientId,
                x.traderId,
                x.clientOrder,
                x.orderMemo,
                x.id,
                x.bizhong,
                x.ord2proId,
                x.isCheck,
                x.isOver,
                x.orderType,
                x.orderTypeXq,
                p.proName,
                p.guige,
                x.xiaoshouName,
                y.compName,
                m.employName,
                g.id as guozhangId
                from (" . $sql . ") x
                left join jichu_product p on p.id = x.productId
                left join jichu_client y on x.clientId = y.id
                left join jichu_employ m on m.id=x.traderId
                left join caiwu_ar_guozhang g on g.orderId=x.id
                where 1";

        if ($serachArea['key'] != '') {
            if(strpos($serachArea['key'], '+')){
                $tempKey = explode('+', $serachArea['key']);
                foreach ($tempKey as & $v) {
                  $strObj[] = " p.guige like '%$v%'";
                }
                $guigeStr = join(' and ', $strObj);
                $str .= " and (p.proName like '%$serachArea[key]%' or ({$guigeStr}))";
            }else{
                $str .= " and (p.proName like '%$serachArea[key]%' or p.guige like '%$serachArea[key]%')";
            }
        }

        if($serachArea['guige']!=''){
            $guige = explode("+",$serachArea['guige']);
            foreach ($guige as $k => &$v) {
                $str .=" and guige REGEXP '{$v}'";
            }
        }

        // if ($serachArea['guige'] != '') $str .= " and p.guige like '%{$serachArea['guige']}%'";
        if ($serachArea['proCode'] != '') $str .= " and p.proCode like '%{$serachArea['proCode']}%'";

        //该用户关联的业务员的订单
        $mUser = & FLEA::getSingleton('Model_Acm_User');
        if(!$mUser->canSeeAllOrder($_SESSION['USERID'])) {
            //得到当前用户匹配的员工id
            $traderIds = $mUser->getTraderIdOfCurUser($_SESSION['USERID']);
            if(count($traderIds)==0) {//说明用户没有指定关联业务员，禁止访问
                $str .= " and 0";
            } else {
                $s = join(',',$traderIds);
                $str .= " and x.traderId in ({$s})";
            }
        }
        $str .= " group by x.id order by orderDate desc, orderCode desc";
        //dump($str);exit;
        $pager = &new TMIS_Pager($str);
        $rowset = $pager->findAll();
        // dump($rowset);exit;
        $trade_order2product = &FLEA::getSingleton('Model_Trade_Order2Product');
        $trade_shenhe= &FLEA::getSingleton('Model_Trade_Shenhe');
        $canEdit= $this->authCheck('1-2-1',true);
        $canRemove=$this->authCheck('1-2-2',true);
        $isCk=$this->authCheck('1-2-5',true);
        $isOver=$this->authCheck('1-2-7',true);
        $canSeeClient = $this->authCheck('1-2-3', true);
        $canSeeMoney = $this->authCheck('1-2-4', true);

        if (count($rowset) > 0) foreach($rowset as &$value) {
            $res = $trade_order2product->findAll(array('orderId' => $value['id']));
            $chukuCntMap = $trade_order2product->getChukuChengpinCnt($value['id']);
            $chukuCntJianMap = $trade_order2product->getChukuChengpinCntJian($value['id']);
            $chukuCntRJianMap = $trade_order2product->getRukuChengpinCntJian($value['id']);
            $chukuCntFwJianMap = $trade_order2product->getFwChengpinCntJian($value['id']);
            $chukuCntOrderJianMap = $trade_order2product->getOdChengpinCntJian($value['id']);
//            dump($chukuCntMap);exit;
            //有订单修改权限
            //增加审核权限，所以先去掉之前的过账不许修改删除的限制 2015-09-01 by shen
            if($canEdit) {
                    //dump($res);exit;
                    $i=0;//判断审核的记录个数
                    foreach($res as &$v){
                        if($v['Order']['isCheck']){
                            $i++;//有审核就累加一次
                        }
                    }
                    if(count($res)==$i){
                        //如果个数相同，表示都已经审核过了，
                            $tip = "ext:qtip='订单明细已全部审核'";
                            $value['_edit'] .= " <a $tip  >修改</a>";
                    }else{
                            $value['_edit'] .= " <a href='" . $this->_url('Edit', array('id' => $value['id'])) . "'>修改</a>";
                    }
            }
            //有订单删除权限
            if($canRemove) {
                    $i=ture;//判断是否有审核的记录
                    foreach($res as &$v){
                          if($v['Order']['isCheck']){
                              $tip = "ext:qtip='订单明细有审核'";
                              $value['_edit'] .= " <a $tip  >删除</a>";
                              $i=false;
                              break;
                          }
                    }
                    if($i){
                        //$i为true，则表示没有审核明细记录
                        $value['_edit'] .= " <a href='" . $this->_url('Remove', array('id' => $value['id'])) . "'  onclick=\"return confirm('确认删除吗?')\" >删除</a>";
                    }
            }

            $value['_edit'] .= " <a href='".$this->_url('Export',array(
                    'id'=>$value['id'],
                    'fromAction'=>$_GET['action'],
                    'flag'=>1
            ))."' ext:qtip='导出'>导出</a>";

            //判断是否有审核权限
            if($isCk){
                    //根据主订单 得到明细id，判断是否审核，如果其中有一个审核就不能删除，
                    foreach($res as &$v){
                          if($v['Order']['isCheck']){
                              $tip = "ext:qtip='订单明细有审核'";
                          }
                    }
                        $msg=$value['isCheck']==0?"":"取消";
                        $value['_edit'].=" <a title='{$msg}审核' href='".$this->_url('Shenhe2',array(
                            'id'=>$value['id'],
                            'isCheck'=>$value['isCheck']
                        ))."'>{$msg}审核</a>";
            }
            if($isOver){
                $msg=$value['isOver']==0?"":"取消";
                $value['_edit'].=" <a title='{$msg}完成' href='".$this->_url('HaveOver',array(
                    'id'=>$value['id'],
                    'isOver'=>$value['isOver']
                ))."'>{$msg}完成</a>";
            }


            // 在左侧中 显示 总数量 和 总金额 初始化
            $value['cntTotal'] = 0;
            $value['moneyTotal'] = 0;

            //根据主订单 得到明细id，判断是否审核，如果其中有一个审核就不能删除，
            // $res = $value['Products'];
            foreach($res as &$item) {
                $item['_edit'] = "<a href='".$this->_url('PrintDingchan',array(
                    'ord2proId'=>$item['id']
                ))."'>导出</a>";

                $arrFp = $this->_modelPlanPj->find(array('ord2proId'=>$item['id']));
                if(!$arrFp){
                    $item['plan'] = "<a href='".url('Shengchan_PiJian_Plan','Add',array(
                        'ord2proId'=>$item['id'],
                    )). "' class='thickbox'>分批</a>";
                }else{
                    $item['plan'] = "<a href='".url('Shengchan_PiJian_Plan','Edit',array(
                        'id'=>$arrFp['id'],
                        'flags'=>'order'
                    )). "' class='thickbox'>分批修改</a>";
                }
                // //根据$trade_order2product 的id 查询 trade_shenhe表
                // $str=$trade_shenhe->findAll(array('ord2proId'=>$item['id']));

                // //如果已审核，可以打印定产单; 并且不能删除
                // if($str[0]['isCheck']) {
                // 	$item['_edit'] = "<a href='".$this->_url('PrintDingchan',array(
                // 		'ord2proId'=>$item['id']
                // 	))."'>导出</a>";
                // } else {
                // 	$item['_edit'] = "未审";
                // }

                // 成品出库数
                $item['proChuku'] = $chukuCntMap[$item['id']]['cnt'];
                $item['chukuJian'] = $chukuCntJianMap[$item['id']]['cntJian']?$chukuCntJianMap[$item['id']]['cntJian']:0;//出库件数
                //成品入库件数
                $item['rukuJian'] = $chukuCntRJianMap[$item['id']]['cntJian']?$chukuCntRJianMap[$item['id']]['cntJian']:0;
                //后整发外件数(坯布)
                $item['fawaiJian'] = round($chukuCntFwJianMap[$item['id']]['cntJian'],2)?round($chukuCntFwJianMap[$item['id']]['cntJian'],2):0;
                //订单查询上的件数
                $item['orderJian'] = $chukuCntOrderJianMap[$item['id']]['cntJian']?$chukuCntOrderJianMap[$item['id']]['cntJian']:0;

                //明细中的件数，表现形式为：成出/成入/坯布/订单
                $item['detailJian'] = $item['chukuJian'].'/'.$item['rukuJian'].'/'.$item['fawaiJian'].'/'.$item['orderJian'];

                //成品出库数
                $item['chukuCnts'] = round($chukuCntMap[$item['id']]['cnt'],2)?round($chukuCntMap[$item['id']]['cnt'],2):0;
                //成品入库数
                $item['rukuCnts'] = round($chukuCntRJianMap[$item['id']]['cnt'],2)?round($chukuCntRJianMap[$item['id']]['cnt'],2):0;
                //后整发外件数(坯布)
                $item['fawaiCnts'] = round($chukuCntFwJianMap[$item['id']]['cnt'],2)?round($chukuCntFwJianMap[$item['id']]['cnt'],2):0;
                //订单查询上的件数
                $item['orderCnts'] = round($chukuCntOrderJianMap[$item['id']]['cnt'],2)?round($chukuCntOrderJianMap[$item['id']]['cnt'],2):0;
                //明细中的重量，表现形式为：成出/成入/坯布/订单
                $item['detailCnt'] = $item['chukuCnts'].'/'.$item['rukuCnts'].'/'.$item['fawaiCnts'].'/'.$item['orderCnts'];
                // 添加信息
                $item['cntYaohuo'] = round($item['cntYaohuo'], 2);
                $item['danjia'] = round($item['danjia'], 2);
                $item['proCode'] = $item['Products']['proCode'];

                $item['proCode'] = "<a href='".url('Jichu_Chanpin','Edit',array(
                     'id'=>$item['Products']['id'],
                     'flag'=>'1',
                     'no_edit'=>'1',
                 ))."' target='_blank'>".$item['proCode']."</a>";

                $item['proName'] = $item['Products']['proName'];
                $item['guige'] = $item['Products']['guige'];
                // 客户需求颜色自己填写 不是从基础档案调过来
                // $item['color'] = $item['color'];
                // 客户需求颜色 从基础档案调过来
                $item['color'] = $item['Products']['color'];
                $item['kind'] = $item['Products']['kind'];
                $value['memoTrade'] = $item['Order']['memoTrade'];
                $item['money'] = round($item['cntYaohuo'] * $item['danjia'], 2);
                // 在左侧中 显示 总数量 和 总金额 累加
                $value['cntTotal'] += $item['cntYaohuo'];
                $value['moneyTotal'] += $item['money'];
                unset($item['Order']);

                // 点击查看明细
                $item['proChuku'] = "<a href='".url('Cangku_Chengpin_ChukuSell','ChukuDetail',array(
                        'orderId'=>$value['id'],
                        'ord2proId'=>$item['id'],
                        'width'=>800,
                        'no_edit'=>1,
                        'TB_iframe'=>1,
                    ))."' class='thickbox' title='出库明细'>{$item['proChuku']}</a>";
            }
            $value['DetailProducts'] = $res;

            //判断当前用户是否可以看客户和看金额
            if(!$canSeeClient) {//客户
                $value['compName'] = '';
            }
            //总金额
            if(!$canSeeMoney) {//金额
                $value['moneyTotal'] = '';
            }
            foreach($value['DetailProducts'] as & $vv) {
                if(!$canSeeMoney) {//金额
                    $vv['danjia'] = '';
                    $vv['money'] = '';
                }
            }
            $value['dateJiaohuo'] = $value['DetailProducts'][0]['dateJiaohuo'];

            // 翻单操作
            $value['_edit'] .= " <a href='".$this->_url('Edit',array(
                    'id'=>$value['id'],
                    'fromAction'=>$_GET['action'],
                    'flag'=>1
            ))."' ext:qtip='翻单'>翻单</a>";
            //总件数，表现形式为：（成出/成入/坯布/订单）。这个订单所有品种的件数
            //成品出库总件数
            $chukuJianAll = $trade_order2product->getCkJAll($value['id']);
            $value['chukuJianAll'] = round($chukuJianAll[0]['cntJian'],2)?round($chukuJianAll[0]['cntJian'],2):0;
            //成品出库总重量
            $value['chukuCntAll'] = round($chukuJianAll[0]['cnt'],2)?round($chukuJianAll[0]['cnt'],2):0;
            //成品入库总件数
            $rukuJianAll = $trade_order2product->getRkJAll($value['id']);
            $value['rukuJianAll'] = round($rukuJianAll[0]['cntJian'],2)?round($rukuJianAll[0]['cntJian'],2):0;
            //成品入库总重量
            $value['rukuCntAll'] = round($rukuJianAll[0]['cnt'],2)?round($rukuJianAll[0]['cnt'],2):0;
            //坯布总件数（发外数量）
            $fwJianAll = $trade_order2product->getFwJAll($value['id']);
            $value['fwJianAll'] = round($fwJianAll[0]['cntJian'],2)?round($fwJianAll[0]['cntJian'],2):0;
            //坯布总重量（发外数量）
            $value['fwCntAll'] = round($fwJianAll[0]['cnt'],2)?round($fwJianAll[0]['cnt'],2):0;
            //订单总件数
            $ordJianAll = $trade_order2product->getOrdJAll($value['id']);
            $value['ordJianAll'] = round($ordJianAll[0]['cntJian'],2)?round($ordJianAll[0]['cntJian'],2):0;
            //订单总重量
            $value['ordCntAll'] = round($ordJianAll[0]['cnt'],2)?round($ordJianAll[0]['cnt'],2):0;
            //总件数显示
            $value['jianTotal'] = $value['chukuJianAll'].'/'.$value['rukuJianAll'].'/'.$value['fwJianAll'].'/'.$value['ordJianAll'];
            //总重量显示
            $value['cntTotal'] = $value['chukuCntAll'].'/'.$value['rukuCntAll'].'/'.$value['fwCntAll'].'/'.$value['ordCntAll'];
        }
        //dump($rowset);die;

        // 合计行
        $smarty = &$this->_getView();
        // 右侧信息
        $arrFieldInfo = array(
            "_edit" => array("text"=>'操作','width'=>220),
            "orderCode" => array("text"=>"生产编号",'width'=>110),
            "orderTypeXq"=>"订单类型",
            "orderDate" => "下单日期",
            "dateJiaohuo" => "交货日期",
            "compName" => array("text"=>"客户名称",'width'=>170),
            'employName' => '业务员',
            'xiaoshouName' => '销售助理',
            // 'pinming'=>'品名',
            // 'guige'=>'规格',
            'jianTotal'=> array('text'=>'总件数','width'=>130),
            "cntTotal" => array('text'=>'总数量','width'=>130),
            // "danjia" =>'单价',
            "moneyTotal" => '总金额',
            //"bizhong" => '币种',
            "memoTrade" => array("text"=>'贸易部要求','width'=>200),
            // "orderMemo" =>'订单备注',
            //"ord2proId" =>'明细id'
            );
        // 下面显示的信息
        $arrField = array(
            //'_edit'=>array("text"=>'定产单','width'=>50),
            //'proChuku' => '出库数',
            'plan' =>'操作',
            'proCode' => '编码',
            'kind'=>'类型',
            'proName' => array("text"=>'品名','width'=>200),
            'guige' => array("text"=>'规格','width'=>300),
            'color'=>'颜色',
            'menfu' => '门幅',
            'kezhong' => '克重',
            'detailJian'=>array('text'=>'件数','width'=>140),
            'detailCnt'=>array('text'=>'重量','width'=>150),
            // 'zhenshu'=>'针数',
            // 'cunshu'=>'寸数',
            // 'xiadanXianchang'=>'下单线长',
            "cntYaohuo" => array('text'=>'数量','width'=>70),
            "unit" => array('text'=>'单位','width'=>50),
            "danjia" => array('text'=>'单价','width'=>60),
            "money" => '金额',
            // "memo" =>'产品备注'
            );
        $smarty->assign('title', '订单查询');
        $smarty->assign('pk', $this->_modelDefault->primaryKey);
        $smarty->assign('arr_field_info', $arrFieldInfo);
        $smarty->assign('arr_field_info2', $arrField);
        $smarty->assign('sub_field', 'DetailProducts');
        $smarty->assign('add_display', 'none');
        $smarty->assign('arr_condition', $serachArea);
        $smarty->assign('arr_field_value', $rowset);
        
        $smarty->assign("page_info", $pager->getNavBar($this->_url($this->_url($_GET['action'])), $serachArea));
        $smarty->assign('arr_js_css', $this->makeArrayJsCss(array('grid', 'calendar')));
        $smarty->assign("sonTpl", 'Trade/jsOrderEdit.tpl');
        //if($this->authCheck('100-6')){
            $smarty->assign("fn_export",$this->_url('ExportMingxi',$serachArea+array('export'=>1)));
        //}
        // $smarty->display('TableList.tpl');
        $smarty->display('TblListMore.tpl');
    }

    function actionExportMingxi(){
        $this->authCheck('100-6');
        set_time_limit(0);
        ini_set('memory_limit', '2048M');
        $trade_order2product = &FLEA::getSingleton('Model_Trade_Order2Product');
         $sql = "SELECT y.id,
                       t.id as tid,
                       y.orderCode,
                       y.orderDate,
                       y.clientId,
                       y.traderId,
                       y.clientOrder,
                       y.memo as orderMemo,
                       y.bizhong,
                       y.isCheck,
                       y.isOver,
                       y.orderType,
                       y.orderTypeXq,
                       y.xiaoshouName,
                       t.id as ord2proId,
                       t.cntYaohuo,
                       t.danjia,
                       y.memoTrade,
                       t.productId,
                       t.unit
                from trade_order y
                join trade_order2product t on y.id =t.orderId
                where 1";
        $sql .= " and y.orderDate >= '$_GET[dateFrom]' and y.orderDate<='$_GET[dateTo]'";
        if ($_GET['isCheck'] < 2) $sql .= " and y.isCheck = '$_GET[isCheck]'";
        if ($_GET['isOver'] < 2) $sql .= " and y.isOver = '$_GET[isOver]'";
        if ($_GET['orderCode'] != '') $sql .= " and y.orderCode like '%$_GET[orderCode]%'";
        if($_GET['tradeMemo']!='') $sql .= " and y.memoTrade like '%{$_GET['tradeMemo']}%'";
        //订单类型
        if($_GET['orderType'] != ''){
            if($_GET['orderType']!=10){
                $sql .=" and y.orderType='$_GET[orderType]'";
            }
        }
        if ($_GET['clientId'] != '') $sql .= " and y.clientId = '$_GET[clientId]'";
        if ($_GET['traderId'] != '') $sql .= " and y.traderId = '$_GET[traderId]'";

        $str = "select
                x.orderCode,
                x.orderDate,
                x.clientId,
                x.traderId,
                x.clientOrder,
                x.orderMemo,
                x.id,
                x.bizhong,
                x.ord2proId,
                x.isCheck,
                x.isOver,
                x.orderType,
                x.orderTypeXq,
                x.cntYaohuo,
                x.danjia,
                p.proName,
                p.guige,
                x.xiaoshouName,
                y.compName,
                m.employName,
                g.id as guozhangId,
                x.tid,
                x.productId,
                x.memoTrade,
                x.unit
                from (" . $sql . ") x
                left join jichu_product p on p.id = x.productId
                left join jichu_client y on x.clientId = y.id
                left join jichu_employ m on m.id=x.traderId
                left join caiwu_ar_guozhang g on g.orderId=x.id
                where 1";

        if ($_GET['key'] != '') {
            if(strpos($_GET['key'], '+')){
                $tempKey = explode('+', $_GET['key']);
                foreach ($tempKey as & $v) {
                  $strObj[] = " p.guige like '%$v%'";
                }
                $guigeStr = join(' and ', $strObj);
                $str .= " and (p.proName like '%$_GET[key]%' or ({$guigeStr}))";
            }else{
                $str .= " and (p.proName like '%$_GET[key]%' or p.guige like '%$_GET[key]%')";
            }
        }

        if($_GET['guige']!=''){
            $guige = explode("+",$_GET['guige']);
            foreach ($guige as $k => &$v) {
                $str .=" and guige REGEXP '{$v}'";
            }
        }

        // if ($_GET['guige'] != '') $str .= " and p.guige like '%{$_GET['guige']}%'";
        if ($_GET['proCode'] != '') $str .= " and p.proCode like '%{$_GET['proCode']}%'";
        $str .= " group by x.tid order by orderDate desc, orderCode desc";
        $rowset = $this->_modelDefault->findBySql($str);
        $canSeeClient = $this->authCheck('1-2-3', true);
        $canSeeMoney = $this->authCheck('1-2-4', true);
        foreach ($rowset as $key => &$value) {
            $res = $trade_order2product->findAll(array('orderId' => $value['id']));
            $chukuCntMap = $trade_order2product->getChukuChengpinCnt($value['id']);
            $chukuCntJianMap = $trade_order2product->getChukuChengpinCntJian($value['id']);
            $chukuCntRJianMap = $trade_order2product->getRukuChengpinCntJian($value['id']);
            $chukuCntFwJianMap = $trade_order2product->getFwChengpinCntJian($value['id']);
            $chukuCntOrderJianMap = $trade_order2product->getOdChengpinCntJian($value['id']);

            $value['chukuJian'] = $chukuCntJianMap[$value['tid']]['cntJian']?$chukuCntJianMap[$value['tid']]['cntJian']:0;//出库件数
                //成品入库件数
                $value['rukuJian'] = $chukuCntRJianMap[$value['tid']]['cntJian']?$chukuCntRJianMap[$value['tid']]['cntJian']:0;
                //后整发外件数(坯布)
                $value['fawaiJian'] = round($chukuCntFwJianMap[$value['tid']]['cntJian'],2)?round($chukuCntFwJianMap[$value['tid']]['cntJian'],2):0;
                //订单查询上的件数
                $value['orderJian'] = $chukuCntOrderJianMap[$value['tid']]['cntJian']?$chukuCntOrderJianMap[$value['tid']]['cntJian']:0;

                //明细中的件数，表现形式为：成出/成入/坯布/订单
                $value['detailJian'] = $value['chukuJian'].'/'.$value['rukuJian'].'/'.$value['fawaiJian'].'/'.$value['orderJian'];
                //成品出库数
                $value['chukuCnts'] = round($chukuCntMap[$value['tid']]['cnt'],2)?round($chukuCntMap[$value['tid']]['cnt'],2):0;
                //成品入库数
                $value['rukuCnts'] = round($chukuCntRJianMap[$value['tid']]['cnt'],2)?round($chukuCntRJianMap[$value['tid']]['cnt'],2):0;
                //后整发外件数(坯布)
                $value['fawaiCnts'] = round($chukuCntFwJianMap[$value['tid']]['cnt'],2)?round($chukuCntFwJianMap[$value['tid']]['cnt'],2):0;
                //订单查询上的件数
                $value['orderCnts'] = round($chukuCntOrderJianMap[$value['tid']]['cnt'],2)?round($chukuCntOrderJianMap[$value['tid']]['cnt'],2):0;
                //明细中的重量，表现形式为：成出/成入/坯布/订单
                $value['detailCnt'] = $value['chukuCnts'].'/'.$value['rukuCnts'].'/'.$value['fawaiCnts'].'/'.$value['orderCnts'];
//            dump($chukuCntMap);exit;
            //查询产品信息
            $sql = "select * from jichu_product where id='{$value['productId']}'";
            $pros = $this->_modelDefault->findBySql($sql);
            $value['proCode'] = $pros[0]['proCode'];
            $value['kind'] = $pros[0]['kind'];
            $value['proName'] = $pros[0]['proName'];
            $value['guige'] = $pros[0]['guige'];
            $value['color'] = $pros[0]['color'];
            $value['kind'] = $pros[0]['kind'];
            $value['menfu'] = $pros[0]['menfu'];
            $value['kezhong'] = $pros[0]['kezhong'];
            $value['moneys'] = round($value['cntYaohuo'] * $value['danjia'], 2);
            // 在左侧中 显示 总数量 和 总金额 初始化
            $value['cntTotal'] = 0;
            $value['moneyTotal'] = 0;

   
            $value['DetailProducts'] = $res;

            //判断当前用户是否可以看客户和看金额
            if(!$canSeeClient) {//客户
                $value['compName'] = '';
            }
            //总金额
            if(!$canSeeMoney) {//金额
                $value['moneyTotal'] = '';
            }
            $value['dateJiaohuo'] = $value['DetailProducts'][0]['dateJiaohuo'];
         
     
        }
        $smarty = &$this->_getView();
        // 右侧信息
        $arrFieldInfo = array(
            "orderCode" => "生产编号",
            "orderTypeXq"=>"订单类型",
            "orderDate" => "下单日期",
            "dateJiaohuo" => "交货日期",
            "compName" => "客户名称",
            'employName' => '业务员',
            'xiaoshouName' => '销售助理',
            'proCode' => '编码',
            'kind'=>'类型',
            'proName' => '品名',
            'guige' => '规格',
            'color'=>'颜色',
            'menfu' => '门幅',
            'kezhong' => '克重',
            'cntYaohuo'=>'数量',
            // 'pinming'=>'品名',
            // 'guige'=>'规格',
            'detailJian'=> '总件数',
            "detailCnt" => '总数量',
            'unit'=>'单位',
            "danjia" =>'单价',
            "moneys" => '总金额',
            //"bizhong" => '币种',
            "memoTrade" => '贸易部要求',
            // "orderMemo" =>'订单备注',
            //"ord2proId" =>'明细id'
            );
        $smarty->assign('title', '订单查询');
        // $smarty->assign('pk', $this->_modelDefault->primaryKey);
        $smarty->assign('arr_field_info', $arrFieldInfo);
        $smarty->assign('add_display', 'none');
        $smarty->assign('arr_condition', $serachArea);
        $smarty->assign('arr_field_value', $rowset);
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/download");
        header("Content-Disposition: attachment;filename=order.xls");
        header("Content-Transfer-Encoding: binary");
        $smarty->display('Export2Excel2.tpl');
        exit;
    }
    // ***********************************订单查询 end***********************************
    function actionSetOver() {
        // dump($_GET);exit;
        $sql = "update trade_order set isCheck={$_GET['isCheck']} where id='{$_GET['id']}'";
        // echo $sql;exit;
        // mysql_query($sql) or die(mysql_error());
        $this->_modelDefault->execute($sql);
        // redirect($this->_url('ListForOver'));
        $msg = $_GET['isCheck'] == 1?"审核成功!":"取消审核成功!";
        js_alert(null, "window.parent.showMsg('{$msg}')", $this->_url($_GET['fromAction']));
    }
    // 查看详细
    function actionView() {
        FLEA::loadClass('TMIS_Common');
        $pk = $this->_modelDefault->primaryKey;
        $rowset = $this->_modelDefault->find($_GET[$pk]);
        $mPro = &FLEA::getSingleton('Model_Jichu_Product');
        if (count($rowset) > 0) if (count($rowset['Products']) > 0) {
            foreach($rowset['Products'] as &$value) {
                $row = $mPro->findByField('id', $value['productId']);
                $value['proCode'] = $row['proCode'];
                $value['proName'] = $row['proName'];
                $value['guige'] = $row['guige'];
                // $value['unit']			= $row['unit'];
                $value['money'] = number_format($value['danjia'] * $value['cnt'], 2, '.', '');
                $value['danjia'] = number_format($value['danjia'], 2, '.', '');
                $value['cnt'] = round($value['cnt'], 2);
                // if ($value['money'] == 0)	$value['money'] = $value['cnt'] * $value['danjia'];
                $totalCnt += $value['cnt'];
                $totalMoney += $value['money'];
            }
        }
        $smarty = &$this->_getView();
        $smarty->assign('title', '销售订单');
        $smarty->assign("arr_field_value", $rowset);
        $smarty->assign("total_cnt", $totalCnt);
        $smarty->assign("total_money", $totalMoney);
        $smarty->assign('money', TMIS_Common::trans2rmb($totalMoney));
        $smarty->display('Trade/OrderView.tpl');
    }
    // 打印生产单
    function actionManuPrint() {
        $pk = $this->_modelDefault->primaryKey;
        $rowset = $this->_modelDefault->find($_GET[$pk]);
        $mPro = &FLEA::getSingleton('Model_Jichu_Product');
        if (count($rowset) > 0) if (count($rowset['Products']) > 0) {
            foreach($rowset['Products'] as &$value) {
                $row = $mPro->findByField('id', $value['productId']);
                $value['proCode'] = $row['proCode'];
                $value['proName'] = $row['proName'];
                $value['guige'] = $row['guige'];
                $value['unit'] = $row['unit'];

                if ($value['money'] == 0) $value['money'] = $value['cnt'] * $value['danjia'];

                $totalCnt += $value['cnt'];
                $totalMoney += $value['money'];
            }
        }
        $smarty = &$this->_getView();
        $smarty->assign('title', '生产单');
        $smarty->assign("arr_field_value", $rowset);
        $smarty->assign("total_cnt", $totalCnt);
        $smarty->assign("total_money", $totalMoney);
        $smarty->display('Trade/ManuPrint.tpl');
    }
    // 打印领料单
    function actionLingliaoPrint() {
        $mYl = &FLEA::getSingleton('Model_Jichu_Yl');
        $mPro = &FLEA::getSingleton('Model_Jichu_Product');
        $kucun = &FLEA::getSingleton('Controller_Cangku_Yl_Kucun');
        $pk = $this->_modelDefault->primaryKey;
        $rowset = $this->_modelDefault->find($_GET[$pk]);
        if (count($rowset) > 0) if (count($rowset['Products']) > 0) {
            foreach($rowset['Products'] as &$value) {
                $sql = "select ylId, ylCnt from jichu_product2yl where productId = " . $value['productId'];
                $yls = $this->_modelDefault->findBySql($sql);
                // dump($yls); exit;
                if (count($yls) > 0) foreach($yls as &$v) {
                    $v['ylCnt'] = $v['ylCnt'] * $value['cnt'];
                    $v['chejianKucun'] = $kucun->getChejianKucun($rowset['chejianId'], $v['ylId']);
                    $newRowset[] = $v;
                    $arrYlId [] = $v['ylId'];
                }
            }
        }
        // dump($newRowset); //exit;
        if (count($arrYlId) > 0) {
            $arrYlId = array_count_values($arrYlId); //统计存在重量的id

            // dump($arrYlId);
            foreach($arrYlId as $k => $v) {
                $cnt = 0;
                if ($v > 1) { // 如果存在重复
                    foreach($newRowset as $vv) {
                        if ($vv['ylId'] == $k) {
                            // echo($vv['ylCnt']);
                            $cnt += $vv['ylCnt'];
                            $finalArr = $vv;
                        }
                    }
                    $finalArr['ylCnt'] = $cnt;
                    // dump($finalArr);exit;
                }else {
                    foreach($newRowset as $vvv) {
                        if ($vvv['ylId'] == $k) {
                            $finalArr = $vvv;
                        }
                    }
                }

                $arr[] = $finalArr; //去掉重复的数据, 把重复的cnt相加得出新的arr
            }

            if (count($arr) > 0) foreach($arr as &$value) {
                $rowPro = $mYl->find($value['ylId']);
                $value['ylCode'] = $rowPro['ylCode'];
                $value['ylName'] = $rowPro['ylName'];
                $value['guige'] = $rowPro['guige'];
                $value['unit'] = $rowPro['unit'];
                $value['wantLing'] = $value['ylCnt'] - $value['chejianKucun'];
            }
            // dump($arr); exit;
            $smarty = &$this->_getView();
            $smarty->assign('title', '领料单');
            $smarty->assign("arr_field_value", $rowset);
            $smarty->assign('arr_yl', $arr);
            $smarty->display('Trade/LingliaoPrint.tpl');
        }else {
            js_alert('产品组成不完整, 无法直接生成领料单!', "window.close()");
        }
    }
    // 装箱单打印
    function actionBoxPrint() {
        $pk = $this->_modelDefault->primaryKey;
        $rowset = $this->_modelDefault->find($_GET[$pk]);
        $mPro = &FLEA::getSingleton('Model_Jichu_Product');
        if (count($rowset) > 0) if (count($rowset['Products']) > 0) {
            foreach($rowset['Products'] as &$value) {
                $row = $mPro->findByField('id', $value['productId']);
                $value['proCode'] = $row['proCode'];
                $value['proName'] = $row['proName'];
                $value['guige'] = $row['guige'];
                $value['unit'] = $row['unit'];
                $value['boxCnt'] = ceil($value['cnt'] / $value['perBoxCnt']);
            }
        }

        $smarty = &$this->_getView();
        $smarty->assign('title', '装箱单');
        $smarty->assign("arr_field_value", $rowset);
        $smarty->assign("total_cnt", $totalCnt);
        $smarty->assign("total_money", $totalMoney);
        // $smarty->display('Trade/BoxPrint.tpl');
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=BoxPrint.xml");
        // $smarty->display('Trade/BoxPrint.tpl');
        $smarty->display('Trade/Export2Excel.tpl');
    }
    // 原料仓库领料介面
    function actionViewForYlChuku() {
        $mPro = &FLEA::getSingleton('Model_Jichu_Product');

        FLEA::loadClass('TMIS_Pager');
        $arrGet = TMIS_Pager::getParamArray(array('dateFrom' => date("Y-m-d", mktime(0, 0, 0, date("m")-1, date("d"), date("Y"))),
                'dateTo' => date("Y-m-d"),
                'clientId' => '',
                // 'chejianId'		=> '',
                'orderCode' => '',
                'key' => ''
                ));

        $condition[] = array('dateOrder', $arrGet['dateFrom'], '>=');
        $condition[] = array('dateOrder', $arrGet['dateTo'], '<=');
        if ($arrGet['clientId'] != '') $condition[] = array('clientId', $arrGet['clientId']);
        // if ($arrGet['chejianId'] != '') $condition[] = array('chejianId', $arrGet['chejianId']);
        if ($arrGet['orderCode'] != '') {
            $condition[] = array('orderCode', '%' . $arrGet['orderCode'] . '%', 'like');
        }
        $condition[] = array('isLingliao', 0);

        $pager = &new TMIS_Pager($this->_modelDefault, $condition, "dateOrder desc");
        $rowset = $pager->findAll();
        if (count($rowset) > 0) foreach($rowset as &$value) {
            $value['clientName'] = $value['Client']['compName'];
            $value['chejianName'] = $value['Chejian']['name'];
            $arrPro = array();
            if (count($value['Products']) > 0) foreach($value['Products'] as &$pv) {
                $arrPro[] = $mPro->getProStr($mPro->find(array('id' => $pv['productId']))) . ": $pv[cnt]";
            }
            $title = join("<br>", $arrPro);
            $value['_edit'] = "<a href='" . $this->_url('View', array('id' => $value['id'])) . "' target='_blank' title='$title' onmouseover='tipOver(this,event)' onmouseout='tipOut(this)'>查看详细</a> | <a href='" . $this->_url('LingliaoPrint', array('id' => $value['id'])) . "' target='_blank' title='$title' onmouseover='tipOver(this,event)' onmouseout='tipOut(this)'>打印领料单</a> | <a href='" . $this->_url('Convert2Lingliao', array('id' => $value['id'])) . "' title='$title' onmouseover='tipOver(this,event)' onmouseout='tipOut(this)'>领料</a>";
        }

        $arrFieldInfo = array('orderCode' => '单号',
            'dateOrder' => '日期',
            'clientName' => '客户名称',
            'chejianName' => '车间名称',
            'memo' => '备注',
            '_edit' => '操作'
            );
        $smarty = &$this->_getView();
        $smarty->assign('title', $this->title);
        $smarty->assign('pk', $this->_modelRuku->primaryKey);
        $smarty->assign('arr_field_info', $arrFieldInfo);
        $smarty->assign('arr_field_value', $rowset);
        $smarty->assign('arr_condition', $arrGet);
        $smarty->assign('arr_js_css', $this->makeArrayJsCss(array('tip', 'calendar')));
        $smarty->assign('page_info', $pager->getNavBar($this->_url('right', $arrGet)));
        $smarty->display('TableList2.tpl');
    }
// 生产单转换成领料单
    function actionConvert2Lingliao() {
        $mYl = &FLEA::getSingleton('Model_Jichu_Yl');
        $mPro = &FLEA::getSingleton('Model_Jichu_Product');
        $kucun = &FLEA::getSingleton('Controller_Cangku_Yl_Kucun');
        $pk = $this->_modelDefault->primaryKey;
        $rowset = $this->_modelDefault->find($_GET[$pk]);
    // dump($rowset
        if (count($rowset) > 0) if (count($rowset['Products']) > 0) {
            foreach($rowset['Products'] as &$value) {
                $sql = "select ylId, ylCnt from jichu_product2yl where productId = " . $value['productId'];
                $yls = $this->_modelDefault->findBySql($sql);
                // dump($yls); exit;
                if (count($yls) > 0) foreach($yls as &$v) {
                    $v['ylCnt'] = $v['ylCnt'] * $value['cnt'];
                    $v['chejianKucun'] = $kucun->getChejianKucun($rowset['chejianId'], $v['ylId']);
                    $newRowset[] = $v;
                    $arrYlId [] = $v['ylId'];
                }
            }
        }
        // dump($newRowset); //exit;
        if (count($arrYlId) > 0) {
            $arrYlId = array_count_values($arrYlId); //统计存在重量的id

            // dump($arrYlId);
            foreach($arrYlId as $k => $v) {
                $cnt = 0;
                if ($v > 1) { // 如果存在重复
                    foreach($newRowset as $vv) {
                        if ($vv['ylId'] == $k) {
                            // echo($vv['ylCnt']);
                            $cnt += $vv['ylCnt'];
                            $finalArr = $vv;
                        }
                    }
                    $finalArr['ylCnt'] = $cnt;
                    // dump($finalArr);exit;
                }else {
                    foreach($newRowset as $vvv) {
                        if ($vvv['ylId'] == $k) {
                            $finalArr = $vvv;
                        }
                    }
                }

                $arr[] = $finalArr; //去掉重复的数据, 把重复的cnt相加得出新的arr
            }

            if (count($arr) > 0) foreach($arr as &$value) {
                $rowPro = $mYl->find($value['ylId']);
                $value['ylCode'] = $rowPro['ylCode'];
                $value['ylName'] = $rowPro['ylName'];
                $value['guige'] = $rowPro['guige'];
                $value['unit'] = $rowPro['unit'];
                $value['cnt'] = $value['ylCnt'] - $value['chejianKucun'];
            }

            $mYlChuku = &FLEA::getSingleton('Model_Cangku_Yl_Chuku');
            $arrF['chukuNum'] = $mYlChuku->getNewChukuNum();
            $arrF['chejianId'] = $rowset['chejianId'];
            $arrF['Yl'] = $arr;

            $smarty = &$this->_getView();
            $smarty->assign('title', '原料出库');
            $smarty->assign('isConvert2Lingliao', 1);
            $smarty->assign('orderId', $rowset['id']);
            $smarty->assign('operator_id', $_SESSION['USERID']);
            $smarty->assign('real_name', $_SESSION['REALNAME']);
            $smarty->assign("arr_field_value", $arrF);
            $smarty->assign('default_date', date("Y-m-d"));
            $pk = $this->_mYlChuku->primaryKey;
            $primary_key = (isset($_GET[$pk])?$pk:"");
            $smarty->assign("pk", $primary_key);
            $smarty->display('Cangku/Yl/ChukuEdit.tpl');
        }else {
            js_alert('产品组成不完整, 无法直接生成领料单!', "window.history.go(-1)");
        }
    }
    // 编辑订单基本信息
    function _edit($Arr, $tag = 0) {
        // dump($Arr); exit;
        $smarty = &$this->_getView();
        $smarty->assign('title', '订单登记');
        $smarty->assign('user_id', $_SESSION['USERID']);
        $smarty->assign('real_name', $_SESSION['REALNAME']);
        $smarty->assign("arr_field_value", $Arr);
        $smarty->assign('default_date', date("Y-m-d"));
        $pk = $this->_modelDefault->primaryKey;
        $primary_key = (isset($_GET[$pk])?$pk:"");
        $smarty->assign("pk", $primary_key);
        $smarty->display('Trade/OrderEdit.tpl');
    }
    // ***************************订单登记 begin*********************************
    // /1.0 给控件填充信息
    // /2.0 主从表界面的展示
    // /3.0
    function actionAdd() {
        $this->authCheck('1-1');
        $role="select r.roleName
            from acm_roledb r 
            left join acm_user2role ur on ur.roleId=r.id 
            left join acm_userdb u on ur.userId=u.id
            where u.id='{$_SESSION['USERID']}'";
        // dump($role);exit();
        $rowset=$this->_modelExample->findBySql($role);
        // dump($rowset);exit();
        foreach($rowset as & $v) {
            if($v['roleName']=='跟单'){
                $this->headSon['danjia']=array('type' => 'bttext',"title" => '单价','name' => 'danjia[]','readonly'=>true);
                $this->headSon['money']=array('type' => 'hidden', "title" => '', 'name' => 'money[]', 'readonly' => true,);
            }
        }
        // 生成业务员信息
        // $m_jichu_employ = &FLEA::getSingleton('Model_Jichu_Employ');
        // $sql = "select * from jichu_employ where 1";
        // $rowset = $m_jichu_employ->findBySql($sql);
        // foreach($rowset as &$v) {
        // 	// *根据要求：options为数组,必须有text和value属性
        // 	$rowsTrader[] = array('text' => $v['employName'], 'value' => $v['id']);
        // }
        // 主表信息字段
        $fldMain = $this->fldMain;
        // *在主表字段中加载业务员选项*
        //$fldMain['traderId']['options'] = $rowsTrader;
        // *订单号的默认值的加载*
        $fldMain['orderCode']['value'] = '系统自动生成';
        // dump($fldMain);exit;
        $headSon = $this->headSon;
        // 从表信息字段,默认5行
        for($i = 0;$i < 5;$i++) {
            $rowsSon[] = array(
                'unit' => array('value'=>'公斤')
            );
        }
        // 主表区域信息描述
        $areaMain = array('title' => '订单基本信息', 'fld' => $fldMain);
        $jiaoqi = strtotime($areaMain['fld']['finalDate']['value'])+3600*72; //默认当前时间的后三天
        $areaMain['fld']['finalDate']['value'] = date("Y-m-d",$jiaoqi);
        // 从表区域信息描述
        $smarty = &$this->_getView();
        $smarty->assign('areaMain', $areaMain);
        $smarty->assign('headSon', $headSon);
        $smarty->assign('rowsSon', $rowsSon);
        $smarty->assign('rules', $this->rules);
        // 合同备注
        $arrMemo = array('memoTrade' => array('title' => '贸易部要求', 'type' => 'textarea','name' => 'memoTrade'),
            // 'memoYongjin' => array('title' => '佣金备注', 'type' => 'textarea','name' => 'memoYongjin'),
            // 'memoWaigou' => array('title' => '外购备注', 'type' => 'textarea','name' => 'memoWaigou'),

            );
        // 合同条款
        $arrItem = array('orderItem1' => array('title' => '第二条 质量标准', 'type' => 'textarea', 'readonly' => true, 'name' => 'orderItem1', 'value' => '按乙方确认样和FZ/T72008-2006标准生产。如果乙方对甲方的产品质量有异议，请在收货后15个工作日内提出，乙方开裁视为合格。'),
            'orderItem2' => array('title' => '第三条 包装标准', 'type' => 'textarea', 'readonly' => true, 'name' => 'orderItem2', 'value' => '塑料薄膜包装。特殊要求另行协商。'),
            'orderItem3' => array('title' => '第四条 交货数量', 'type' => 'textarea', 'readonly' => true, 'name' => 'orderItem3', 'value' => '大货数量允许 ±3%。'),
            'orderItem4' => array('title' => '第五条 交货方式', 'type' => 'textarea', 'readonly' => true, 'name' => 'orderItem4', 'value' => '由甲方送货到乙方指定国内地点， 费用由甲方负责,特殊情况另行协商。'),
            'orderItem5' => array('title' => '第六条 交货时间', 'type' => 'textarea', 'readonly' => true, 'name' => 'orderItem5', 'value' => '自乙方定金到甲方账户，并在乙方确认大货产前样品质后开始算交期。'),
            'orderItem6' => array('title' => '第七条 结算方式', 'type' => 'textarea', 'readonly' => true, 'name' => 'orderItem6', 'value' => '预付合同总金额的30%作为定金，余款提货前结清，如分批交货的,定金在最后一批货款中结算。'),
            'orderItem7' => array('title' => '第八条 争议解决', 'type' => 'textarea', 'readonly' => true, 'name' => 'orderItem7', 'value' => '本协议在履行过程中如发生争议，由双方协商解决；如协商不能解决，按下列两种方式解决(1) 提交签约地仲裁委员会仲裁； (2) 依法向人民法院起诉；'),
            );
        $smarty->assign("arr_memo", $arrMemo);
       // $smarty->assign("arr_item", $arrItem);
        $smarty->assign("tbl_son_width", '120%');
        $smarty->assign("otherInfoTpl", 'Trade/otherInfoTpl.tpl');
        $smarty->assign("sonTpl", 'Trade/jsOrderEdit.tpl');
        $smarty->display('Main2Son/T3.tpl');
    }
    // ***************************订单登记 end*********************************
    // ***************************订单编辑 begin*********************************
    function actionEdit() {
        // $pk=$this->_modelDefault->primaryKey;
        $arr = $this->_modelDefault->find(array('id' => $_GET['id']));
        $dqId=$_SESSION['USERID'];
        $role="select r.roleName
            from acm_roledb r 
            left join acm_user2role ur on ur.roleId=r.id 
            left join acm_userdb u on ur.userId=u.id
            where u.id=$dqId";
        // dump($role);exit();
        $rowset=$this->_modelExample->findBySql($role);
        // dump($rowset);exit();
        foreach($rowset as & $v) {
            if($v['roleName']=='跟单'){
                $this->headSon['danjia']=array('type' => 'bttext',"title" => '单价','name' => 'danjia[]','readonly'=>true);
                $this->headSon['money']=array('type' => 'hidden', "title" => '', 'name' => 'money[]', 'readonly' => true,);
            }
        }
        //如果为翻单，orderId，orderCode置空
        if($_GET['flag']==1) {
            $arr['fandanId']=$arr['id'];
            $arr['id']='';
            $arr['oldOrderCode']=$arr['orderCode'];
            $arr['orderCode'] = '系统自动生成';
            foreach($arr['Products'] as & $v) {
                $v['id'] = "";
            }
        }
        // dump($arr);exit;
        foreach ($this->fldMain as $k => &$v) {
            $v['value'] = $arr[$k];
            // $arr[$k] =
        }
        $this->fldMain['orderId']['value'] = $arr['id'];
        // $this->fldMain['orderId']['orderMemo'] = $arr['memo'];
        $this->fldMain['clientId']['clientName'] = $arr['Client']['compName'];


        if($_GET['flag']==1){//翻单订单日期默认当天，交期延迟三天
            $this->fldMain['orderDate']['value'] = date('Y-m-d');
            $this->fldMain['finalDate']['value'] = date('Y-m-d',mktime(0,0,0,date('m'),date('d')+3,date('Y')));
            $this->fldMain['traderId']['disabled'] = true;
        }else{
            $this->fldMain['finalDate']['value'] = $arr['Products']['0']['dateJiaohuo'];
        }
        // dump($this->fldMain);exit;
        // otherInfoTpl中数据处理
        $otherInfo = array('memo' => $arr['orderMemo'], // 备注
            'memoTrade' => $arr['memoTrade'], // 贸易部要求
            'memoYongjin' => $arr['memoYongjin'], // 佣金备注
            'memoWaigou' => $arr['memoWaigou'], // 外购备注
            'orderItem1' => $arr['orderItem1'],
            'orderItem2' => $arr['orderItem2'],
            'orderItem3' => $arr['orderItem3'],
            'orderItem4' => $arr['orderItem4'],
            'orderItem5' => $arr['orderItem5'],
            'orderItem6' => $arr['orderItem6'],
            'orderItem7' => $arr['orderItem7'],
            );


        $areaMain = array('title' => '订单基本信息', 'fld' => $this->fldMain);
        // 订单明细处理
        // 订单明细的产品信息处理
        // dump($arr['Products']);exit;
        foreach($arr['Products'] as &$v) {
            // dump($v);exit;
            $sql = "select * from jichu_product where id='{$v['productId']}'";
            $_temp = $this->_modelDefault->findBySql($sql);
            $v['proCode'] = $_temp[0]['proCode'];
            $v['proName'] = $_temp[0]['proName'];
            $v['guige'] = $_temp[0]['guige'];
            $v['color'] = $_temp[0]['color'];
            // dump($_temp);exit;
            $v['money'] = round($v['danjia'] * $v['cntYaohuo'], 2);
            // $v['']
        }
        // dump($arr['Products']);exit;
        foreach($arr['Products'] as &$v) {
            $temp = array();
            foreach($this->headSon as $kk => &$vv) {
                $temp[$kk] = array('value' => $v[$kk]);
            }
            $rowsSon[] = $temp;
        }

        //补齐5行
        $len = count($rowsSon);
        for($i=5;$i>$len;$i--) {
            $rowsSon[] = array();
        }

        //dump($rowsSon);exit;
        $smarty = &$this->_getView();
        $smarty->assign('areaMain', $areaMain);
        //$smarty->assign('otherInfo', $otherInfo);
        $smarty->assign('headSon', $this->headSon);
        $smarty->assign('rowsSon', $rowsSon);
        $smarty->assign('rules', $this->rules);
        // 合同备注
        $arrMemo = array('memoTrade' => array('title' => '贸易部要求', 'type' => 'textarea','name' => 'memoTrade', 'value' => $arr['memoTrade']),
            // 'memoYongjin' => array('title' => '佣金备注', 'type' => 'textarea','name' => 'memoYongjin', 'value' => $arr['memoYongjin']),
            // 'memoWaigou' => array('title' => '外购备注', 'type' => 'textarea','name' => 'memoWaigou', 'value' => $arr['memoWaigou']),

            );
        // 合同条款
        $arrItem = array('orderItem1' => array('title' => '第二条 质量标准', 'type' => 'textarea', 'readonly' => true, 'name' => 'orderItem1', 'value' => $arr['orderItem1']),
            'orderItem2' => array('title' => '第三条 包装标准', 'type' => 'textarea', 'readonly' => true, 'name' => 'orderItem2', 'value' => $arr['orderItem2']),
            'orderItem3' => array('title' => '第四条 交货数量', 'type' => 'textarea', 'readonly' => true, 'name' => 'orderItem3', 'value' => $arr['orderItem3']),
            'orderItem4' => array('title' => '第五条 交货方式', 'type' => 'textarea', 'readonly' => true, 'name' => 'orderItem4', 'value' => $arr['orderItem4']),
            'orderItem5' => array('title' => '第六条 交货时间', 'type' => 'textarea', 'readonly' => true, 'name' => 'orderItem5', 'value' => $arr['orderItem5']),
            'orderItem6' => array('title' => '第七条 结算方式', 'type' => 'textarea', 'readonly' => true, 'name' => 'orderItem6', 'value' => $arr['orderItem6']),
            'orderItem7' => array('title' => '第八条 争议解决', 'type' => 'textarea', 'readonly' => true, 'name' => 'orderItem7', 'value' => $arr['orderItem7']),
            );
        $smarty->assign("arr_memo", $arrMemo);
        //$smarty->assign("arr_item", $arrItem);
        $smarty->assign("tbl_son_width",'120%');
        $smarty->assign("otherInfoTpl", 'Trade/otherInfoTpl.tpl');
        $smarty->assign("sonTpl", 'Trade/jsOrderEdit.tpl');
        $smarty->display('Main2Son/T3.tpl');
    }
    // ***************************订单编辑 end*********************************
    function actionCopy() {
        $pk = $this->_modelDefault->primaryKey;
        $arr = $this->_modelDefault->find($_GET[$pk]);

        $arr['id'] = '';
        $arr['orderCode'] = $this->_modelDefault->getNewOrderCode();

        if (count($arr['Products']) > 0) foreach($arr['Products'] as &$v) {
            $mPro = &FLEA::getSingleton('Model_Jichu_Product');
            $row = $mPro->find($v['productId']);
            $v['proName'] = $row['proName'];
            $v['guige'] = $row['guige'];

            $v['id'] = '';
            $v['orderId'] = '';
        }
        // dump($arr);
        $this->_edit($arr);
    }

    function actionRemove() {
        $this->authCheck($this->funcId);
        $pk = $this->_modelDefault->primaryKey;
        $this->_modelDefault->removeByPkv($_GET[$pk]);
        redirect($this->_url('right'));
    }
    // 编辑界面利用ajax删除
    function actionRemoveByAjax() {
        $m = &FLEA::getSingleton('Model_Trade_Order2Product');
        $r = $m->removeByPkv($_POST['id']);
        if (!$r) {
            $arr = array('success' => false, 'msg' => '删除失败');
            echo json_encode($arr);
            exit;
        }
        $arr = array('success' => true);
        echo json_encode($arr);
        exit;
    }
    // ********************************数据保存 begin********************************
    function actionSave() {
        // dump($_POST);die;
        $map = $this->orderTypeMap;
        if($_POST['orderCode']=='系统自动生成'){
            $orderCode=$this->_modelDefault->getNewOrderCode($_POST['orderType'],$map[$_POST['orderType']]['head']);
            $_POST['orderCode']=$orderCode;
        }
        $trade_order2product = array();
        foreach ($_POST['productId'] as $key => $v) {
            // 当没有记录 或者 没有输入数量时 跳出本次循环 防止多保存
            if (empty($_POST['productId'][$key]) || empty($_POST['cntYaohuo'][$key])) continue;
            //2015-7-7 by jiang 判断修改时有没有修改数量
            if($_POST['id'][$key]){
                $tempPro=$this->_modelOrder2pro->find(array('id'=>$_POST['id'][$key]));
                if($tempPro['cntYaohuo']!=$_POST['cntYaohuo'][$key]){
                    $tempPro['isCntChange']=1;
                }
            }
            $trade_order2product[] = array(
                'id' => $_POST['id'][$key], // 主键id
                'productId' => $_POST['productId'][$key], // 产品id
                'supplierId' => $_POST['supplierId'][$key]+0, // 产品id
                // 'ratio' => $_POST['ratio'][$key], // 比例
                'cntYaohuo' => $_POST['cntYaohuo'][$key], // 要货数量
                'zhenshu' => $_POST['zhenshu'][$key], // 针数
                'cunshu' => $_POST['cunshu'][$key], // 寸数
                'xiadanXianchang' => $_POST['xiadanXianchang'][$key], // 下单线长
                'color' => $_POST['color'][$key], // 颜色
                'menfu' => $_POST['menfu'][$key], // 门幅
                'kezhong' => $_POST['kezhong'][$key], // 克重
                'unit' => $_POST['unit'][$key], // 单位
                'danjia' => $_POST['danjia'][$key], // 单价
                'memo' => $_POST['memo'][$key] . '', // 备注
                'dateJiaohuo' => $_POST['finalDate'], // 交货日期
                'isCntChange' => $tempPro['isCntChange']+0,
                'cntJian'=>$_POST['cntJian'][$key]+0
            );
        }

        //dump($trade_order2product);exit;
        if(count($trade_order2product)==0) {
            js_alert('未发现有效的产品明细','window.history.go(-1)');
            exit;
        }
        // trade_order 表 的数组
        //确定订单类型详情
        $orderTypeXq = $map[$_POST['orderType']]['cnName'];

        $trade_order = array(
            'id' => $_POST['orderId'], // 主键id
            'orderCode' => $_POST['orderCode'],// 订单号
            'orderType'=>$_POST['orderType'],//订单类型
            'orderTypeXq' => $orderTypeXq,//订单类型详情
            'orderDate' => $_POST['orderDate'], // 签订日期
            'finalDate'=>$_POST['finalDate'],                //最终交期
            'xiaoshouName' => $_POST['xiaoshouName'], // 销售助理
            'traderId' => $_POST['traderId']+0, // 业务员id
            'clientId' => $_POST['clientId'], // 客户id
            'clientOrder' => $_POST['clientOrder']+0, // 客户合同号
            'xsType' => $_POST['xsType'], // 内外销
            'overflow' => $_POST['overflow'], // 溢短装
            'warpShrink' => $_POST['warpShrink']+0, // 经向缩率
            'weftShrink' => $_POST['weftShrink']+0, // 纬向缩率
            'packing' => $_POST['packing']+0, // 包装要求
            'checking' => $_POST['checking']+0, // 检验要求
            'moneyDayang' => $_POST['moneyDayang']+0, // 打样收费
            'bizhong' => $_POST['bizhong'], // 币种
            'memo' => $_POST['orderMemo'] . '', // 备注
            'memoTrade' => $_POST['memoTrade'].'', // 贸易部要求
            'memoYongjin' => $_POST['memoYongjin'].'', // 佣金备注
            'memoWaigou' => $_POST['memoWaigou'].'', // 外购备注
            'orderItem1' => $_POST['orderItem1']||'',
            'orderItem2' => $_POST['orderItem2']||'',
            'orderItem3' => $_POST['orderItem3']||'',
            'orderItem4' => $_POST['orderItem4']||'',
            'orderItem5' => $_POST['orderItem5']||'',
            'orderItem6' => $_POST['orderItem6']||'',
            'orderItem7' => $_POST['orderItem7']||'',
            );
        // 表之间的关联
        $trade_order['Products'] = $trade_order2product;
        // 保存 并返回trade_order表的主键
        // dump($trade_order);exit;
        $itemId = $this->_modelExample->save($trade_order);
        if ($itemId) {
            js_alert(null, 'window.parent.showMsg("保存成功！");', $this->_url('right'));
        }else die('保存失败!');
    }
    // ********************************数据保存 end********************************
    function actionGetYlJson() {
        $sql = "select
m.*,sum(y.cnt*x.cnt*z.ylCnt) as ylCnt,z.ylId as ylId,ifnull(a.kucunCnt,0) as kucunCnt
from trade_order2product x
inner join jichu_pro2chengpin y on x.productId=y.chengpinId
inner join jichu_product2yl z on y.proId=z.productId
inner join jichu_yl m on z.ylId=m.id
left join cangku_yl_init a on m.id=a.ylId
where x.orderId='{$_GET['orderId']}'
group by z.ylId";
        // echo $sql;exit;
        $rowset = $this->_modelExample->findBySql($sql);
        // dump($rowset);
        echo json_encode($rowset);
        exit;
    }
    // ********************************订单弹出后台 begin********************
    // /从色坯纱管理中弹出订单，只能选择色纱和坯纱， 用isSePiSha=1来标示
    // /从成品管理中弹出订单，只能选择坯布和其他
    function actionPopup() {
        FLEA::loadClass('TMIS_Pager');
        // /构造搜索区域的搜索类型
        $serachArea = TMIS_Pager::getParamArray(array(
            'dateFrom' => date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-30, date("Y"))),
            'dateTo' => date("Y-m-d"),
// 		   	'clientId' => '',
            'traderId' => '',
            'orderCode' => '',
            'key' => '',
            'fwFlag' => '',
        ));
        // /查询sql语句
        $sql = "select y.orderCode,
           y.orderDate,
           y.clientId,
           y.traderId,
           y.clientOrder,
           y.isCheck,
           y.overflow,
           y.memoTrade,
           x.id,
           x.orderId as orderId,
           x.productId,
           x.danjia,
           x.cntYaohuo,
           x.xiadanXianchang,
           x.unit,
           x.ratio,
           x.kezhong,
           x.menfu
               from trade_order2product x
               left join trade_order y on (x.orderId = y.id)";

        $str = "select
        x.id as order2proId,
        x.orderId,
        x.orderCode,
        x.orderDate,
        x.clientId,
        x.traderId,
        x.productId,
        x.clientOrder,
        x.cntYaohuo,
        x.xiadanXianchang,
        x.danjia,
        x.danjia*x.cntYaohuo as money,
        x.isCheck,
        x.overflow,
        x.unit,
        x.memoTrade,
        x.ratio,
        x.menfu,
        x.kezhong,
        y.id,
        y.compName,
        z.id as productId,
        z.proCode,
        z.proName,
        z.guige,
        z.color,
        z.menfu as productMenfu,
        z.kezhong as productKezhong,
        z.kind,
        z.color,
        z.chengFen,
        m.employName
        from (" . $sql . ") x
        left join jichu_client y on x.clientId = y.id
        left join jichu_product z on x.productId = z.id
        left join jichu_employ m on m.id=x.traderId
        where 1 ";
        // /判断是从哪个页面进入的
        if (isset($_GET['isSePiSha'])) {
            if ($_GET['isSePiSha'] == 1) {
                $str .= " and z.kind in ('坯纱','色纱')";
            }
        }elseif (isset($_GET['isChengPing'])) {
            if ($_GET['isChengPing'] == 1) {
                $str .= " and z.kind in ('坯布','其他')";
            }
        }

        $str .= " and orderDate >= '$serachArea[dateFrom]' and orderDate<='$serachArea[dateTo]'";
        if ($serachArea['key'] != '') $str .= " and (x.orderCode like '%$serachArea[key]%'
                        or z.proName like '%$serachArea[key]%'
                        or z.proCode like '%$serachArea[key]%'
                        or z.guige like '%$serachArea[key]%')";
        if ($serachArea['orderCode'] != '') $str .= " and x.orderCode like '%$serachArea[orderCode]%'";
        if ($serachArea['clientId'] != '') $str .= " and x.clientId = '$serachArea[clientId]'";
        if ($serachArea['traderId'] != '') $str .= " and x.traderId = '$serachArea[traderId]'";
        $str .= " order by orderDate desc, orderCode desc";
        $pager = &new TMIS_Pager($str);
        $rowset = $pager->findAll();

        if (count($rowset) > 0) foreach($rowset as $i => &$v) {
            $v['cnt'] -= $cnt;
            $v['danjia'] = round($v['danjia'], 2);
            $v['money'] = round($v['money'], 2);
            //查找已计划次数
            $sql="select count(*) as cnt from shengchan_plan where order2proId='{$v['order2proId']}'";
            $temp = $this->_modelExample->findBySql($sql);
            $v['cishu'] = $temp[0]['cnt'];
            // 已计划的用绿色区分
            //if($v['cishu']>0) $v['_bgColor'] = 'lightGreen';
            if($_GET['fwFlag']){
                $bgState =$this->checkFw($v['order2proId'],$v['orderId']);
                $v['bgRes'] = $bgState['state'];
                if($v['bgRes'] == 'fw'){
                    $v['_bgColor'] = 'lightblue';
                }elseif($v['bgRes']== 'fwCk'){
                    $v['_bgColor'] = 'lightGreen';
                }
            }
        }
        $arrFieldInfo = array(
            //'orderId'=>'id',
            "orderCode" => "单号",
            "orderDate" => "日期",
            "compName" => "客户名称",
            'employName' => '业务员',
            'proName' => array('text'=>'产品名称', 'width'=>180),
            'guige' => array('text'=>'规格', 'width'=>330),
            'menfu' => '门幅',
            'kezhong' => '克重',
            'unit'=>'单位',
            "cntYaohuo" => '数量',
            "cishu" => '已计划次数',
            // "danjia" =>'单价',
            // "money" =>'金额'
        );
        if($_GET['fwFlag']){
            $msg = '&nbsp;&nbsp;<span style="color:red;">已后整发外的记录是蓝色标识，后整发外并出库的是绿色标识</span>';
        }
        $smarty = &$this->_getView();
        $smarty->assign('title', '选择客户');
        $pk = $this->_modelExample->primaryKey;
        $smarty->assign('pk', $pk);
        $smarty->assign('add_display', 'none');
        $smarty->assign('arr_field_info', $arrFieldInfo);
        $smarty->assign('arr_field_value', $rowset);
        $smarty->assign('arr_js_css', $this->makeArrayJsCss(array('thickbox')));
        $smarty->assign('arr_condition', $serachArea);
        $smarty->assign('page_info', $pager->getNavBar($this->_url('Popup', $serachArea)).$msg);
        $smarty->display('Popup/CommonNew.tpl');
    }
    /**
     * @desc ：订单汇总表
     * Time：2018/10/25 10:04:45
     * @author pan
    */
    function actionHuizongReport(){
        FLEA::loadClass('TMIS_Pager');
        // /构造搜索区域的搜索类型
        $serachArea = TMIS_Pager::getParamArray(array(
            'dateFrom' => date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-30, date("Y"))),
            'dateTo' => date("Y-m-d"),
            'kind'=>'',
           //'clientId'=>''
        ));
        if($serachArea['clientId']!=''){
            $str = " and r.clientId='{$serachArea['clientId']}'";
        }
        if($serachArea['kind']!=''){
            $str = " and t.kind = '{$serachArea['kind']}'";
        }
        $sql ="select sum(o.cntYaohuo) as cntYaohuo,t.proName,
               c.compName,r.clientId,o.productId,r.id as orderId,t.kind
               from 
               trade_order2product o 
               left join jichu_product t on o.productId=t.id
               left join trade_order r on r.id=o.orderId
               left join jichu_client c on c.id=r.clientId
               where 1 and r.orderDate>='{$serachArea['dateFrom']}' and r.orderDate<='{$serachArea['dateTo']}' " . $str . " and t.state=1 and t.kind not in ('印花系列','提花布','双面机','夹丝布','直条毛圈')group by t.kind
        ";
        $sql.=" order by cntYaohuo desc";
        //dump($sql);die;
        $pager = &new TMIS_Pager($sql);
        $rowset = $pager->findAll();
        //dump($rowset);die;
        foreach ($rowset as $key => &$value) {
            // $sql = "select sum(t.cntOrg) as chukuAll
            //         from cangku_common_chuku p
            //         left join cangku_common_chuku2product t on p.id=t.chukuId
            //         where 1 and p.orderId='{$value['orderId']}' and t.productId='{$value['productId']}' and p.clientId='{$value['clientId']}'";
            // $res = $this->_modelExample->findBySql($sql);
            // $value['chukuAll'] = $res[0]['chukuAll'];
            $value['cntYaohuo'] = "<a href='".$this->_url('Khmingx',array(
                 'dateFrom'=>$serachArea['dateFrom'],
                 'dateTo'=>$serachArea['dateTo'],
                 // 'productId'=>$value['productId']
                 'kind'=>$value['kind']
                ))."' target='_blank'>{$value['cntYaohuo']}</a>";
        }
        $arrFieldInfo = array(
            //'compName'=>array('text'=>'客户','width'=>'220'),
            'kind'=>'种类',
            //'proName'=>'产品名称',
            'cntYaohuo'=>'下单数量',
            //'chukuAll'=>'出库数量'
        );
        $smarty = &$this->_getView();
        $smarty->assign('add_display', 'none');
        $smarty->assign('arr_field_info', $arrFieldInfo);
        $smarty->assign('arr_field_value', $rowset);
        $smarty->assign('arr_condition', $serachArea);
        $smarty->assign('page_info', $pager->getNavBar($this->_url('HuizongReport', $serachArea)));
        $smarty-> display('TblList.tpl');
    }
    

    function actionKhmingx(){
        FLEA::loadClass("TMIS_Pager");
        $sql = "select j.proName,c.compName,sum(o.cntYaohuo) as cntYaohuo,r.clientId
                from trade_order2product o
                left join jichu_product j on j.id=o.productId
                left join trade_order r on r.id=o.orderId
                left join jichu_client c on c.id=r.clientId
                where r.orderDate>='{$_GET['dateFrom']}' and r.orderDate<='{$_GET['dateTo']}'
                and j.kind='{$_GET['kind']}'";
        $sql.=" group by r.clientId";
        $sql.=" order by cntYaohuo desc";
        $pager = &new TMIS_Pager($sql);
        $rowset = $pager->findAll();
        foreach ($rowset as $key => &$value) {
            $sql = "select sum(t.cntOrg) as chukuAll
                    from cangku_common_chuku p
                    left join cangku_common_chuku2product t on p.id=t.chukuId
                    left join jichu_product j on j.id=t.productId
                    where 1 and p.clientId='{$value['clientId']}' and j.kind='{$_GET['kind']}'";
            $res = $this->_modelExample->findBySql($sql);
            $value['chukuAll'] = $res[0]['chukuAll'];
        }
        $hj = $this->getHeji($rowset,array('cntYaohuo','chukuAll'),'compName');
        $rowset[] = $hj;
        $arrFieldInfo = array(
            "compName"=>array('text'=>'客户','width'=>120),
            "cntYaohuo"=>"下单数量",
            "chukuAll"=>"出库数量"
          
        );
        $smarty = &$this->_getView(); 
        $smarty->assign('arr_field_info', $arrFieldInfo);
        $smarty->assign('add_display', 'none');
        $smarty->assign('arr_field_value', $rowset);
        $smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action'], $_GET)));
        $smarty->display('TblList.tpl');
    }
    /**
     * @desc ：后整发外登记订单选择
     * Time：2016/11/29 09:04:45
     * @author Wuyou
    */
    function actionPopupFw(){
        FLEA::loadClass('TMIS_Pager');
        // /构造搜索区域的搜索类型
        $serachArea = TMIS_Pager::getParamArray(array('dateFrom' => date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-30, date("Y"))),
                'dateTo' => date("Y-m-d"),
                //'clientId' => '',
                'traderId' => '',
                'orderCode' => '',
                'key' => '',
            ));
        $sql = "SELECT
                    y.orderCode,
                    y.orderDate,
                    y.clientId,
                    y.traderId,
                    y.clientOrder,
                    y.isCheck,
                    y.overflow,
                    y.memoTrade,
                    x.id,
                    x.orderId as orderId,
                    x.productId,
                    x.danjia,
                    x.cntYaohuo,
                    x.xiadanXianchang,
                    x.unit,
                    x.ratio,
                    x.menfu,
                    x.kezhong,
                    z.proCode,
                    z.proName,
                    z.guige,
                    z.color,
                    z.menfu as productMenfu,
                    z.kezhong as productKezhong,
                    z.kind,
                    z.color,
                    z.chengFen,
                    m.employName
                from trade_order2product x
                left join trade_order y on x.orderId = y.id
                left join jichu_product z on x.productId = z.id
                left join jichu_employ m on m.id=y.traderId
                where 1 ";
        //$sql .= " and exists(select * from shengchan_plan where order2proId=x.id)";//只能选择做过计划的订单
        $sql .= " and y.orderDate >= '{$serachArea['dateFrom']}' and y.orderDate<='{$serachArea['dateTo']}'";
        if ($serachArea['key'] != '') $sql .= " and (
                        z.proName like '%{$serachArea['key']}%'
                        or z.proCode like '%{$serachArea['key']}%'
                        or z.guige like '%{$serachArea['key']}%')";
        if ($serachArea['orderCode'] != '') $sql .= " and y.orderCode like '%{$serachArea['orderCode']}%'";
        if ($serachArea['clientId'] != '') $sql .= " and y.clientId = '{$serachArea['clientId']}'";
        if ($serachArea['traderId'] != '') $sql .= " and y.traderId = '{$serachArea['traderId']}'";
        $sql .= " order by y.orderDate desc, y.orderCode desc";
         // dump($sql);exit;
        $pager = &new TMIS_Pager($sql);
        $rowset = $pager->findAll();
        foreach ($rowset as $k => &$v) {
            //$v['sel'] = '<input type="checkbox" name="_chk"  class="chk_' . $v['ord2proId'] . '"/>';
            $v['_edit']="<input type='checkbox' name='ck[]' id='ck[]' value='{$k}'>";
        }
        $arrFieldInfo = array(
            "_edit"=>array('text'=>"<input type='checkbox' id='ckAll'/>选择",'width'=>60),
            //'sel' => array("width" => 30, 'text' => '<input type="checkbox" id="_chkAll" />'),
            "orderCode"  => "单号",
            "orderDate"  => "日期",
            'employName' => '业务员',
            'proCode'    => '产品编号',
            'proName'    => array('text'=>'产品名称', 'width'=>180),
            'guige'      => array('text'=>'规格', 'width'=>330),
            'color'      => '颜色',
            'menfu'      => '门幅',
            'kezhong'    => '克重',
            'unit'       =>'单位',
            "cntYaohuo"  => '数量',
        );
        $smarty = &$this->_getView();
        $smarty->assign('title', '选择订单');
        $pk = $this->_modelExample->primaryKey;
        $smarty->assign('pk', $pk);
        $other_url="<input type='button' id='choose' name='choose' value='确定'/>";
        $smarty->assign('other_url', $other_url);
        $smarty->assign('add_display', 'none');
        $smarty->assign('arr_field_info', $arrFieldInfo);
        $smarty->assign('arr_field_value', $rowset);
        $smarty->assign('arr_js_css', $this->makeArrayJsCss(array('thickbox')));
        $smarty->assign('arr_condition', $serachArea);
        $smarty->assign('page_info', $pager->getNavBar($this->_url('PopupFw', $serachArea)));
        // $smarty->display('Popup/MultSelFw.tpl');
        // $smarty->display('Popup/CommonNew.tpl');
        $smarty->assign('sonTpl', "Popup/FrameInit.tpl");
        $smarty->display('Popup/CommonNew.tpl');
    }

     function actionPopupLy(){
        FLEA::loadClass('TMIS_Pager');
        // /构造搜索区域的搜索类型
        $serachArea = TMIS_Pager::getParamArray(array('dateFrom' => date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-30, date("Y"))),
                'dateTo' => date("Y-m-d"),
                //'clientId' => '',
                'traderId' => '',
                'orderCode' => '',
                'key' => '',
            ));
        $sql = "SELECT
                    y.orderCode,
                    y.orderDate,
                    y.clientId,
                    y.traderId,
                    y.clientOrder,
                    y.isCheck,
                    y.overflow,
                    y.memoTrade,
                    x.id as ord2proId,
                    x.orderId as orderId,
                    x.productId,
                    x.danjia,
                    x.cntYaohuo,
                    x.xiadanXianchang,
                    x.unit,
                    x.ratio,
                    x.menfu,
                    x.kezhong,
                    z.proCode,
                    z.proName,
                    z.guige,
                    z.color,
                    z.menfu as productMenfu,
                    z.kezhong as productKezhong,
                    z.kind,
                    z.color,
                    z.chengFen,
                    m.employName
                from trade_order2product x
                left join trade_order y on x.orderId = y.id
                left join jichu_product z on x.productId = z.id
                left join jichu_employ m on m.id=y.traderId
                where 1 ";
        $sql .= " and y.orderDate >= '{$serachArea['dateFrom']}' and y.orderDate<='{$serachArea['dateTo']}'";
        if ($serachArea['key'] != '') $sql .= " and (
                        z.proName like '%{$serachArea['key']}%'
                        or z.proCode like '%{$serachArea['key']}%'
                        or z.guige like '%{$serachArea['key']}%')";
        if ($serachArea['orderCode'] != '') $sql .= " and y.orderCode like '%{$serachArea['orderCode']}%'";
        if ($serachArea['clientId'] != '') $sql .= " and y.clientId = '{$serachArea['clientId']}'";
        if ($serachArea['traderId'] != '') $sql .= " and y.traderId = '{$serachArea['traderId']}'";
        $sql .= " order by y.orderDate desc, y.orderCode desc";
         // dump($sql);exit;
        $pager = &new TMIS_Pager($sql);
        $rowset = $pager->findAll();

        $arrFieldInfo = array(
            "orderCode"  => "单号",
            "orderDate"  => "日期",
            'employName' => '业务员',
            'proCode'    => '产品编号',
            'proName'    => array('text'=>'产品名称', 'width'=>180),
            'guige'      => array('text'=>'规格', 'width'=>330),
            'color'      => '颜色',
            'menfu'      => '门幅',
            'kezhong'    => '克重',
            'unit'       =>'单位',
            "cntYaohuo"  => '数量',
        );
        $smarty = &$this->_getView();
        $smarty->assign('title', '选择订单');
        $pk = $this->_modelExample->primaryKey;
        $smarty->assign('pk', $pk);
        $smarty->assign('add_display', 'none');
        $smarty->assign('arr_field_info', $arrFieldInfo);
        $smarty->assign('arr_field_value', $rowset);
        $smarty->assign('arr_js_css', $this->makeArrayJsCss(array('thickbox')));
        $smarty->assign('arr_condition', $serachArea);
        $smarty->assign('page_info', $pager->getNavBar($this->_url('PopupLy', $serachArea)));
        $smarty->display('Popup/CommonNew.tpl');
    }

    // ********************************订单弹出后台 end********************
    function actionGetJsonById() {
        $mPro = &FLEA::getSingleton("Model_Jichu_Product");
        $order = $this->_modelDefault->find(array('id' => $_GET['orderId']));
        // dump($order);exit;
        if ($order['Products']) foreach($order['Products'] as $i => &$v) {
            $sql = "select sum(cnt)as cnt from cangku_chuku2product where order2productId = '{$v['id']}' group by order2productId ";
            $cnt = $this->_modelDefault->findBySql($sql);
            $cnt = $cnt[0]['cnt'];
            if ($cnt >= $v['cnt']) {
                unset($order['Products'][$i]);
                continue;
            }

            $v['cnt'] -= $cnt;
            $v['money'] = $v['cnt'] * $v['danjia'];
            $v['Pro'] = $mPro->find(array('id' => $v['productId']));
        }
        $newOrder = $order;
        $newOrder['Products'] = '';
        foreach ($order['Products'] as $i => &$v) {
            $newOrder['Products'][] = $v;
        }
        // dump($newOrder);exit;
        echo json_encode($newOrder);
        exit;
    }
    // 客户对账单
    function actionDuizhang() {
        // $this->authCheck(113);
        $title = '客户对账单';
        FLEA::loadClass('TMIS_Pager');
        $arr = TMIS_Pager::getParamArray(array('dateFrom' => date('Y-m-01'),
                'dateTo' => date("Y-m-d"),
                // 'key'			=>'',
                'clientId' => '',
                'proId' => ''
                ));

        $sql = "select
a.compName,z.dateGuozhang,b.*,x.cnt,z.money,z.money/x.cnt as danjia
from cangku_chuku2product x
inner join cangku_chuku y on x.chukuId=y.id
inner join jichu_product b on x.productId=b.id
inner join caiwu_ar_guozhang z on x.guozhangId=z.id
left join jichu_client a on z.clientId=a.id
where x.guozhangId>0 and z.dateGuozhang>='{$arr['dateFrom']}' and z.dateGuozhang<='{$arr['dateTo']}'";
        $sql .= " and z.clientId='{$arr['clientId']}'";
        // if($arr['traderId']>0) $sql .= " and y.traderId='{$arr['traderId']}'";
        if ($arr['proId'] > 0) $sql .= " and x.productId='{$arr['proId']}'";
        // $sql .= " group by y.clientId,x.productId,y.traderId";
        // echo $sql;
        // $pager =& new TMIS_Pager($this->_modelExample,$condition);
        $rowset = $this->_modelExample->findBySql($sql);
        // dump($rowset[0]);
        if (count($rowset) > 0) foreach($rowset as &$v) {
            $v['danjia'] = round($v['danjia'], 2);
            $v['cnt'] = round($v['cnt'], 2);
            $v['money'] = round($v['money'], 2);
        }
        // 合计行
        $heji = $this->getHeji($rowset, array('cnt', 'money'), 'compName');
        $rowset[] = $heji;

        $smarty = &$this->_getView();
        // 客户，单据日期，单据类型，单据号，货品编号，货品名称，规格，单位，数量，单价，金额
        // 搜索项：客户，快捷查询，日期，货品。
        $arrFieldInfo = array("compName" => "客户名称",
            "dateGuozhang" => "过账日期",
            // "compName" =>"出库单号",
            "proCode" => "货品编号",
            'proName' => '名称',
            'guige' => '规格',
            'unit' => '单位',
            'cnt' => '数量',
            'danjia' => '单价',
            "money" => "总金额"
            );

        $smarty->assign('title', $title);
        // $smarty->assign('pk', $this->_modelDefault->primaryKey);
        $smarty->assign('arr_field_info', $arrFieldInfo);
        $smarty->assign('add_display', 'none');
        $smarty->assign('arr_condition', $arr);
        $smarty->assign('arr_field_value', $rowset);
        // $smarty->assign('page_info',$pager->getNavBar($this->_url($_GET['action'],$arrGet)));
        $smarty->assign('arr_js_css', $this->makeArrayJsCss(array('calendar')));
        $smarty->display('TableList2.tpl');
    }
    // 应收账款余额查询
    function actionYingshou() {
        // $this->authCheck(114);
        redirect(url('Caiwu_Ar_Report', 'month'));
    }
    // 超账期应收款查询
    function actionChaoqi() {
    }
    // 测试bootstrap的代码
    function actionAddTest() {
        // 生成业务员信息
        $sql = "select * from jichu_employ where 1";
        $rowset = $this->_modelDefault->findBySql($sql);
        foreach($rowset as &$v) {
            $rowsTrader[] = array('text' => $v['employName'], 'value' => $v['id']);
        }
        // 主表信息字段
        $fldMain = array('orderCode' => array('title' => '订单编号', "type" => "text", 'readonly' => true, 'value' => $this->_modelDefault->getNewOrderCode()),
            'dateOrder' => array('title' => '订单日期', 'type' => 'calendar', 'value' => date('Y-m-d')),
            // options为数组,必须有text和value属性
            'traderId' => array('title' => '业务负责', 'type' => 'select', 'options' => $rowsTrader, 'selected' => 'a'),
            'daoKuanDate' => array('title' => '到款日期', 'type' => 'calendar', 'value' => date('Y-m-d')),
            'clientOrderCode' => array('title' => '客户单号', 'type' => 'text', 'value' => ''),
            'proKind' => array('title' => '产品类型', 'type' => 'select', 'selected' => '', 'options' => array(
                    array('text' => '成品', 'value' => '0'),
                    array('text' => '半成品', 'value' => '1'),
                    )),
            // clientpopup需要显示客户名称,所以需要定义clientName属性,value属性作为clientId用
            'clientId' => array('title' => '客户名称', 'type' => 'clientpopup', 'clientName' => ''),
            'xsType' => array('title' => '内/外销', 'type' => 'select', 'selected' => '', 'options' => array(
                    array('text' => '内销', 'value' => '内销'),
                    array('text' => '外销', 'value' => '外销'),
                    )),
            'orderMemo' => array('title' => '订单备注', 'type' => 'textarea', 'disabled' => true),
            // 下面为隐藏字段
            'orderId' => array('type' => 'hidden', 'value' => ''),
            );
        // 从表表头信息
        // type为控件类型,在自定义模板控件
        // title为表头
        // name为控件名
        $headSon = array('_edit' => array('type' => 'btBtnRemove', "title" => '+5行', 'name' => '_edit[]'),
            'productId' => array('type' => 'btproductpopup', "title" => '产品编码', 'name' => 'productId[]'),
            'proName' => array('type' => 'bttext', "title" => '品名', 'name' => 'proName[]', 'readonly' => true),
            'guige' => array('type' => 'bttext', "title" => '规格', 'name' => 'guige[]', 'readonly' => true),
            'unit' => array('type' => 'bttext', "title" => '单位', 'name' => 'unit[]', 'readonly' => true),
            'cnt' => array('type' => 'bttext', "title" => '数量', 'name' => 'cnt[]'),
            'danjia' => array('type' => 'bttext', "title" => '单价', 'name' => 'danjia[]'),
            'money' => array('type' => 'bttext', "title" => '金额(元)', 'name' => 'money[]', 'readonly' => true),
            'memo' => array('type' => 'bttext', "title" => '备注', 'name' => 'memo[]'),
            // ***************如何处理hidden?
            'id' => array('type' => 'bthidden', 'name' => 'id[]'),
            );
        // 从表信息字段
        $rowsSon = array(
            array('productId' => array('value' => ''),
                'proName' => array('value' => ''),
                'guige' => array('value' => ''),
                'unit' => array('value' => ''),
                'cnt' => array('value' => ''),
                'danjia' => array('value' => ''),
                'money' => array('value' => ''),
                'memo' => array('value' => ''),
                // 从表中有时需要hidden控件的。
                'id' => array('value' => ''),
                ),
            );
        // 主表区域信息描述
        $areaMain = array('title' => '订单基本信息', 'fld' => $fldMain);
        // 从表区域信息描述
        $areaSon = array('title' => '产品信息', 'fld' => $fldSon);

        $smarty = &$this->_getView();
        $smarty->assign('areaMain', $areaMain);
        $smarty->assign('headSon', $headSon);
        $smarty->assign('rowsSon', $rowsSon);

        $smarty->display('Main2Son/T.tpl');
    }

    /**
     * 打印定产单
     */
    function actionPrintDingchan() {
        $m = & FLEA::getSingleton('Model_Trade_Order2Product');
        $ord2proId = $_GET['ord2proId'];
        $ord2pro = $m->find(array('id'=>$ord2proId));
        // dump($ord2pro);exit;
        $order = $ord2pro['Order'];
        $mC = & FLEA::getSingleton('Model_Jichu_Client');
        $client = $mC->find(array('id'=>$order['clientId']));
        // dump($order);exit;
        $product = $ord2pro['Products'];
        $mP = &FLEA::getSingleton('Model_Jichu_Product');

        // 获得产品的纱支组成，计算出个纱支使用的数量
        $proSha = $mP->find($ord2pro['productId']);
        foreach ($proSha['Products'] as & $v) {
            $sha = $mP->find($v['productId']);
            $v['proName'] = $sha['proName'];
            $v['chengFen'] = round($v['viewPer'],2).'%';
            // 用纱量计算公式=要货数量*对应纱织的成分比例*（1+损耗3%）
            $v['cntYongsha'] = round($ord2pro['cntYaohuo']*$v['viewPer']/100*1.03,1);
        }
        // 补足五行
        $cnt = 5-count($proSha['Products']);
        for ($i=0; $i < $cnt; $i++) {
            $proSha['Products'][] = array();
        }

        $product['yuanliao'] = join('+', array_col_values($proSha['Products'],'proName'));
        // dump($proSha);exit;

        $mShenhe = & FLEA::getSingleton('Model_Trade_Shenhe');
        $row = $mShenhe->find(array('ord2proId'=>$ord2proId));
        $sh = unserialize($row['serialStr']);
        //dump($sh['ceshiMenfu']);exit();
        $this->exportWithXml('Trade/dingdanNew.xml',array(
            'order'=>$order,
            'ord2pro'=>$ord2pro,
            'product'=>$product,
            'proSha'=>$proSha,
            'client'=>$client,
            'sh'=>$sh,
        ));
    }

    /**
     * 在成品生产入库时弹出选择订单后，需要利用ajax根据orderId得到所有订单明细
     */
    function actionGetMinxiByOrderId() {
        $orderId = $_POST['orderId'];
        if($orderId=='') {
            echo json_encode(array('success'=>false,'msg'=>'订单号为空'));
            exit;
        }

        $order = $this->_modelExample->find(array('id'=>$orderId));
        foreach($order['Products'] as & $v) {
            $sql = "select * from jichu_product where id='{$v['productId']}'";
            $_temp = $this->_modelExample->findBySql($sql);
            $v['ord2proId'] = $v['id'];
            $v['proCode'] = $_temp[0]['proCode'];
            $v['proName'] = $_temp[0]['proName'];
            $v['guige'] = $_temp[0]['guige'];
            $v['color'] = $_temp[0]['color'];
            $v['productMenfu'] = $_temp[0]['menfu'];
            $v['productKezhong'] = $_temp[0]['kezhong'];
        }
        $order['Trader']['employName'] = $order['Trader']['employName']?$order['Trader']['employName']:'';
        // $sql = "select * from trade_order where id='{$orderId}'";
        // $_rows = $this->_modelExample->findBySql($sql);
        // $order = $_rows[0];

        // $sql = "select
        // x.id as ord2proId,x.orderId as orderId,x.productId,
        // y.proCode,y.proName,y.guige
        // from trade_order2product x
        // left join jichu_product y on x.productId=y.id
        // where x.orderId='{$orderId}'";
        // $order['Products'] = $this->_modelExample->findBySql($sql);
        echo json_encode(array('success'=>true,'order'=>$order));
        exit;
    }

    //订单审核,按订单明细显示
    function actionShenheList() {
        // $this->authCheck('3-3');
        FLEA::loadClass('TMIS_Pager');
        // /构造搜索区域的搜索类型
        $arr = TMIS_Pager::getParamArray(array(
            'dateFrom' => date("Y-m-01"),
            'dateTo' => date("Y-m-d"),
            'isCheck' => '',
            'key' => '',
        ));
        $arrFieldInfo = array(
            "_edit" => '审核',
            'orderCode' => '生产编号',
            // "compName" => "客户名称",
            'employName' => '业务员',
            'proCode' => '产品编码',
            'proName' => '品名',
            'guige' => '规格',
            'color'=>'颜色',
            "cntYaohuo" => '要货数量',
            "unit" => '单位',
            "dateJiaohuo" => "交货日期",
            "orderDate" => "下单日期",
            // "danjia" => '单价',
            // "money" => '金额',
            // "memo" =>'产品备注'
            );
        $str="select x.* ,
                 (case when sh.isCheck is Null then 0 else sh.isCheck end) as isCheck
                 from trade_order2product x
                 left join trade_order_shenhe sh on sh.ord2proId=x.id";
        $sql = "select
                   y.orderDate,
                   y.orderCode,
                   x.id,
                   x.dateJiaohuo,
                   x.cntYaohuo,
                   x.unit,
                   x.isCheck,
                   b.employName,
                   z.proCode,
                   z.proName,
                   z.guige,
                   z.color
        from (" . $str . ") x
        left join trade_order y on x.orderId=y.id
        left join jichu_product z on x.productId=z.id
        left join jichu_employ b on y.traderId=b.id
        where 1
        ";
        $sql .= " and orderDate >= '{$arr['dateFrom']}' and orderDate<='{$arr['dateTo']}'";
        if ($arr['key'] != '') $sql .= " and (z.proCode like '%{$arr['key']}%' or z.proName like '%{$arr['key']}%' or z.guige like '%{$arr['key']}%')";
        if ($arr['isCheck'] < 2) $sql .= " and x.isCheck = '$arr[isCheck]'";

        //该用户关联的业务员的订单
        $mUser = & FLEA::getSingleton('Model_Acm_User');
        if(!$mUser->canSeeAllOrder($_SESSION['USERID'])) {
            //得到当前用户匹配的员工id
            $traderIds = $mUser->getTraderIdOfCurUser($_SESSION['USERID']);
            if(count($traderIds)==0) {//说明用户没有指定关联业务员，禁止访问
                $sql .= " and 0";
            } else {
                $s = join(',',$traderIds);
                $sql .= " and y.traderId in ({$s})";
            }
        }

        $sql .= " order by y.orderCode desc,x.id";
        //得到总计
        //$zongji = $this->getZongji($sql,array('cnt'=>'x.cnt','money'=>'x.money'));
       // dump($sql);exit;
        $pager = &new TMIS_Pager($sql);
        $rowset = $pager->findAll();
        // dump($rowset);exit;
        if (count($rowset) > 0) foreach($rowset as &$v) {
            $v['_edit'] = "<a href='".$this->_url('shenhe',array(
                'ord2proId'=>$v['id']
            ))."'>审核</a>";

            if($v['isCheck']==1) $v['_bgColor'] = 'pink';
        }
        // 合计行
        $heji = $this->getHeji($rowset, array('cntYaohuo'), '_edit');
        $rowset[] = $heji;


        $smarty = &$this->_getView();
        $smarty->assign('title', '订单查询');

        $smarty->assign('arr_field_info', $arrFieldInfo);
        $smarty->assign('add_display', $isShowAdd?'display':'none');
        $smarty->assign('arr_condition', $arr);
        $smarty->assign('arr_field_value', $rowset);
        // $msg = "<font color='red'>数量总计:{$zongji['cntYaohuo']},金额总计:{$zongji['money']}</font>";
        $smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action']), $arr).$msg);
        $smarty->assign('arr_js_css', $this->makeArrayJsCss(array('grid', 'calendar')));
        $this->_beforeDisplayRight($smarty);
        $smarty->display('TableList.tpl');
    }

    /**
     * 开始审核
     * 1,不同身份的人进入可输入的内容不同,
     * 2,可点击的审核按钮不同
     * 3,已审核的按钮显示审核时间并可取消审核
     */
    function actionShenhe() {
        $arrEnable = array(
            '1-3-1'=>array(
                'subTrader',
                'memoTrade',
                'returnBack',
            ),//业务员对id=subTrader的元素可编辑
            '1-3-2'=>array(//跟单审核
                'subGendan',
                'memoTrade',
                'returnBack',
            ),
            '1-3-3'=>array(//**织造审核
                'subZhizao',
                'pibuMenfu',//坯布门幅
                'gongyiShuju',//工艺数据
                'pibuKeZhong',//坯布克重
                'pibuXianChang',//坯布线长
                'pibuChengfen',//坯布成分
                'shazhi1',//纱支1
                'bilv1',//比率1
                'jihuaYongSha1',//计划用纱1.05%
                'shaqingkuang1',//纱情况
                'sunhao1',//用纱损耗
                'shazhi2',
                'bilv2',
                'jihuaYongSha2',
                'shaqingkuang2',
                'sunhao2',
                'shazhi3',
                'bilv3',
                'jihuaYongSha3',
                'shaqingkuang3',
                'sunhao3',
                'shazhi4',
                'bilv4',
                'jihuaYongSha4',
                'shaqingkuang4',
                'sunhao4',
                'shazhi5',
                'bilv5',
                'jihuaYongSha5',
                'shaqingkuang5',
                'sunhao5',
                'jitaihao',//机台号
                'cipinshuliang',//次品数量
                'wanchengDate',//完成日期
                'returnBack',
            ),
            '1-3-4'=>array(//定型审核
                'subDingxing',
                // 'pibuMenfu',

                'ceshiMenfu',//测缩门幅
                'ceshiKeZhong',//测缩克重
                'ceshiJingXiang',//经向缩率
                'ceshiWeiXiang',//纬向缩率
                'shifapibu',//实发坯布（匹）
                'shifaGongjin',//实发坯布公斤
                'chengbushuliang',//成布数量（匹）
                'chengbuGongjin',//成布公斤
                'dingxingSunhao',//定型损耗
                'returnBack',
            ),
            '1-3-5'=>array(//成品审核
                'subChengpin',
                'ChengbuMenfu',//成布门幅
                'ChengbuKeZhong',//成布克重
                'ChengbuShiJiMenfu',//成布实际门幅
                'ChengbuShiJiKeZhong',//成布实际克重
                // 'ChengbuMenfu',
                // 'ceshiMenfu',
                // 'shazhi1',
                // 'bilv1',
                // 'shazhi2',
                // 'bilv2',
                'returnBack',
            ),
            '1-3-6'=>array(//生产负责审核,可改所有数据
                'subShengchan',
                'memoTrade',

                'pibuMenfu',//坯布门幅
                'gongyiShuju',//工艺数据
                'pibuKeZhong',//坯布克重
                'pibuXianChang',//坯布线长
                'pibuChengfen',//坯布成分
                'shazhi1',//纱支1
                'bilv1',//比率1
                'jihuaYongSha1',//计划用纱1.05%
                'shaqingkuang1',//纱情况
                'sunhao1',//用纱损耗
                'shazhi2',
                'bilv2',
                'jihuaYongSha2',
                'shaqingkuang2',
                'sunhao2',
                'shazhi3',
                'bilv3',
                'jihuaYongSha3',
                'shaqingkuang3',
                'sunhao3',
                'shazhi4',
                'bilv4',
                'jihuaYongSha4',
                'shaqingkuang4',
                'sunhao4',
                'shazhi5',
                'bilv5',
                'jihuaYongSha5',
                'shaqingkuang5',
                'sunhao5',

                'ChengbuMenfu',//成布门幅
                'ChengbuKeZhong',//成布克重
                'ChengbuShiJiMenfu',//成布实际门幅
                'ChengbuShiJiKeZhong',//成布实际克重
                'ceshiMenfu',//测缩门幅
                'ceshiKeZhong',//测缩克重
                'ceshiJingXiang',//经向缩率
                'ceshiWeiXiang',//纬向缩率
                'shifapibu',//实发坯布（匹）
                'shifaGongjin',//实发坯布公斤
                'chengbushuliang',//成布数量（匹）
                'chengbuGongjin',//成布公斤
                'dingxingSunhao',//定型损耗
                'returnBack',
            ),
            '1-3-7'=>array(//最终审核
                'subZuizhong',
                'memoTrade',

                'pibuMenfu',//坯布门幅
                'gongyiShuju',//工艺数据
                'pibuKeZhong',//坯布克重
                'pibuXianChang',//坯布线长
                'pibuChengfen',//坯布成分
                'shazhi1',//纱支1
                'bilv1',//比率1
                'jihuaYongSha1',//计划用纱1.05%
                'shaqingkuang1',//纱情况
                'sunhao1',//用纱损耗
                'shazhi2',
                'bilv2',
                'jihuaYongSha2',
                'shaqingkuang2',
                'sunhao2',
                'shazhi3',
                'bilv3',
                'jihuaYongSha3',
                'shaqingkuang3',
                'sunhao3',
                'shazhi4',
                'bilv4',
                'jihuaYongSha4',
                'shaqingkuang4',
                'sunhao4',
                'shazhi5',
                'bilv5',
                'jihuaYongSha5',
                'shaqingkuang5',
                'sunhao5',

                'ChengbuMenfu',//成布门幅
                'ChengbuKeZhong',//成布克重
                'ChengbuShiJiMenfu',//成布实际门幅
                'ChengbuShiJiKeZhong',//成布实际克重
                'ceshiMenfu',//测缩门幅
                'ceshiKeZhong',//测缩克重
                'ceshiJingXiang',//经向缩率
                'ceshiWeiXiang',//纬向缩率
                'shifapibu',//实发坯布（匹）
                'shifaGongjin',//实发坯布公斤
                'chengbushuliang',//成布数量（匹）
                'chengbuGongjin',//成布公斤
                'dingxingSunhao',//定型损耗
                'ischeck',//是否审核
                'returnBack',
            ),
        );
        $rowsE = array();
        foreach($arrEnable as $k=>&$v) {
            if($this->authCheck($k,true)) {
                foreach($v as & $vv) {
                    $rowsE[] = $vv;
                }
            }
        }
        array_unique($rowsE);
        // dump($rowsE);exit;

        $mShenhe = & FLEA::getSingleton('Model_Trade_Shenhe');//dump($_POST);exit;
        if($_POST) {
            //取得之前保存的所有审核人审核时间，防止重新保存时丢失
            $row = $mShenhe->find(array('ord2proId'=>$_POST['ord2proId']));//dump($row);exit;
            $sh = unserialize($row['serialStr']);//dump($sh);exit;

            $s = array();
            //得到序列化的数据
            foreach($sh as $k=>&$v) {
                // if(substr($k,0,3)=='sub') {
                // 	$s[$k] = $v;
                // }
                $s[$k] = $v;
            }
            //dump($s);exit;

            //处理提交数据
            foreach($_POST as $k=>&$v) {
                if($k=='btnSub' || $k=='ord2proId') continue;
                $s[$k] = $v;
                if(substr($k,0,3)=='sub') {
                    if($v=='取消') {
                        unset($s[$k]);
                        unset($s[$k.'Time']);
                    }
                    else {
                        //如果是提交按钮，改为当前用户，并新增审核时间
                        $s[$k] = $_SESSION['REALNAME'];
                        $s[$k.'Time'] = date('Y-m-d H:i:s');
                    }
                }
            }
            // dump($s);exit;
            $row = $mShenhe->find(array('ord2proId'=>$_POST['ord2proId']));//dump($_POST['ischeck']);exit;
            $row['ord2proId'] = $_POST['ord2proId'];
            $row['isCheck'] = $_POST['ischeck'];
            //dump($s);exit;
            $row['serialStr'] = serialize($s);
            $mShenhe->save($row);
            js_alert(null,"window.parent.showMsg('审核成功!')",$this->_url('shenhe',array(
                'ord2proId'=>$_POST['ord2proId']
            )));
            exit;
        }
        //反序列化审核数据
        $row = $mShenhe->find(array('ord2proId'=>$_GET['ord2proId']));
        $sh = unserialize($row['serialStr']);
        // dump($sh);exit;
        $ord2proId = $_GET['ord2proId'];
        //得到订单信息
        $mSon = & FLEA::getSingleton('Model_Trade_Order2Product');
        $ord2pro = $mSon->find(array('id'=>$ord2proId));

        //得到已审核的数据
        $sql = "select * from trade_order_shenhe x
        where ord2proId='{$ord2proId}'";
        $rowset = $this->_modelExample->findBySql($sql);
        //反序列化
        // dump($ord2pro);exit;
        //显示模板
        $smarty = & $this->_getView();
        $smarty->assign('ord2pro',$ord2pro);
        $smarty->assign('sh',$sh);

        $smarty->assign('rowsE',$rowsE);
        $smarty->display('Trade/OrderShenhe.tpl');
    }
    /**
     * 订单查询审核
     * Time：2015-08-18 16:10:00
     * @author shen
    */
    function actionShenhe2(){
        // dump($_GET);exit;
        $shenhe=$_GET['isCheck']==0?1:0;
        $msg="操作失败";
        if(is_numeric($_GET['isCheck']) && is_numeric($_GET['isCheck']) && $_GET['id']>0){
            $arr=array(
                'id'=>$_GET['id'],
                'isCheck'=>$shenhe
            );
            $this->_modelExample->update($arr);
            $msg = "操作成功";
        }
        js_alert(null,'window.parent.showMsg("'.$msg.'")',$this->_url('right'));

    }
    /**
     * ps ：
     * Time：2016/07/20 09:28:04
     * @author Sjj
     * @param 参数类型
     * @return 返回值类型
    */

    function actionHaveOver(){
        // dump($_GET);exit;
        $isOver=$_GET['isOver']==0?1:0;
        $msg="操作失败";
        if(is_numeric($_GET['isOver']) && is_numeric($_GET['isOver']) && $_GET['id']>0){
            $arr=array(
                'id'=>$_GET['id'],
                'isOver'=>$isOver
            );
            $this->_modelExample->update($arr);
            $msg = "操作成功";
        }
        js_alert(null,'window.parent.showMsg("'.$msg.'")',$this->_url('right'));

    }

    /**
     * ps ：备注：新增功能页面
     * Time：2015/11/26 10:25:34
     * @author Zhujunjie
     * @param 参数类型
     * @return 返回值类型
    */
    function actionXinzeng(){
        $tpl=Trade/TblList.tpl;
        FLEA::loadClass('TMIS_Pager');
        $sql="SELECT * from shengchan_plan where id='{$_GET['id']}'";
        $pager = &new TMIS_Pager($sql);
        $rowset = $pager->findAll();
        //dump($rowset[0]);die();
        $smarty = &$this->_getView();
        $smarty->assign('aRow', $rowset[0]);
        $smarty->display('Trade/TblList1.tpl');

    }
    function actionXinzengSave(){
        $beizhu=$_POST['memo'];
        $id=$_POST['id'];
        $sql="update shengchan_plan set memo='{$beizhu}' where id='{$_POST['id']}'";
        //dump($sql);die();
        mysql_query($sql);
        js_alert(null, 'window.parent.showMsg("保存成功！");', $this->_url('Genzong'));

    }

    /**
     * ps ：备注：查看
     * Time：2015/11/26 10:26:40
     * @author Zhujunjie
     * @param 参数类型
     * @return 返回值类型
    */
    function actionChakan(){
        $tpl=Trade/TblList.tpl;
        FLEA::loadClass('TMIS_Pager');
        $sql="SELECT * from shengchan_plan where id='{$_GET['id']}'";
        $pager = &new TMIS_Pager($sql);
        $rowset = $pager->findAll();
        //dump($rowset[0]);die();
        $smarty = &$this->_getView();
        $smarty->assign('aRow', $rowset[0]);
        $smarty->display('Trade/TblList2.tpl');
    }

    /**
     * 订单跟踪报表
     * 按照生产计划跟踪
     * Time：2015/04/14 09:33:31
     * @author li
    */
    function actionGenzong(){
        $sonTpl = 'Trade/SetBeizhu.tpl';
        $this->authCheck('5-4');
        FLEA::loadClass('TMIS_Pager');
        $arr = TMIS_Pager::getParamArray(array(
// 			'dateFrom'=>date('Y-m-d',mktime(0,0,0,date('m')-1,date('d'),date('Y'))),
// 			'dateTo'=>date('Y-m-d'),
// 			'clientId' => '',
            'traderId' => '',
            'orderCode' => '',
            'isOver'=>0,
            'key' => '',
        ));

        $sql="select x.id as planId,x.orderId,x.order2proId,x.planCode,x.memo,y.isCntChange,y.cntYaohuo,y.unit,y.dateJiaohuo,z.orderCode,z.orderDate,y.isShiyong,y.isOver,
            p.id as productId,p.proName,p.guige,p.color,p.proCode,p.menfu,p.kezhong,cl.compName,e.employName
            from shengchan_plan x
            left join trade_order2product y on y.id=x.order2proId
            left join trade_order z on z.id=x.orderId
            left join jichu_product p on p.id=x.productId
            left join jichu_client cl on cl.id=z.clientId
            left join jichu_employ e on e.id=z.traderId
            where 1";

        //该用户关联的业务员的订单
        $mUser = & FLEA::getSingleton('Model_Acm_User');
        if(!$mUser->canSeeAllOrder($_SESSION['USERID'])) {
            //得到当前用户匹配的员工id
            $traderIds = $mUser->getTraderIdOfCurUser($_SESSION['USERID']);
            if(count($traderIds)==0) {//说明用户没有指定关联业务员，禁止访问
                $str .= " and 0";
            } else {
                $s = join(',',$traderIds);
                $str .= " and z.traderId in ({$s})";
            }
        }

        if($arr['dateFrom']!=''){
            $sql.=" and z.orderDate >= '{$arr['dateFrom']}'";
        }

        if($arr['dateTo']!=''){
            $sql.=" and z.orderDate <= '{$arr['dateTo']}'";
        }
        if($arr['clientId']>0){
            $sql.=" and z.clientId='{$arr['clientId']}'";
        }
        if($arr['traderId']>0){
            $sql.=" and z.traderId='{$arr['traderId']}'";
        }
        if($arr['orderCode']!=''){
            $sql.=" and z.orderCode like '%{$arr['orderCode']}%'";
        }
        if($arr['key']!=''){
            $sql.=" and (p.proCode like '%{$arr['key']}%'
                        or p.proName like '%{$arr['key']}%'
                        or p.guige like '%{$arr['key']}%'
                        or p.color like '%{$arr['key']}%'
                        or p.menfu like '%{$arr['key']}%'
                        or p.kezhong like '%{$arr['key']}%'
                )";
        }
        if($arr['isOver']!=''){
            $sql.=" and y.isOver='{$arr['isOver']}'";
        }
// 		dump($sql);exit;
        //查找计划
        $pager = &new TMIS_Pager($sql);
        $rowset = $pager->findAll();
        //dump($rowset);die();
        foreach($rowset as & $v) {
            //门幅/克重
            $v['menfuKezhong'] = $v['menfu'].'/'.$v['kezhong'];

            //备注说明
            $v['memo']="<a href='".$this->_url('xinzeng',array(
                    'id'=>$v['planId'],
                ))."'class='thickbox' title='添加备注信息'>新增</a>"."&nbsp;".
                        "<a href='".$this->_url('chakan',array('id'=>$v['planId'],
                ))."'class='thickbox' title='查看备注信息'>查看</a>";


            //dump($v['memo']);die();
            //要货数
            $v['cntYaohuo'] = $v['isCntChange']==1?"<font color='red'>".round($v['cntYaohuo'],2).' '.$v['unit']."</font>":round($v['cntYaohuo'],2).' '.$v['unit'];
            //订单号
            $v['orderCode'] = "<b>{$v['orderCode']}</b>";

            //交期
            $v['dateJiaohuo'] = "<font color='green'>{$v['dateJiaohuo']}</font>";

            //计划投料
            $sql="select sum(cnt) as cnt from shengchan_plan2touliao where planId='{$v['planId']}'";
            $t = $this->_modelDefault->findBySql($sql);
            $v['planTlCnt'] = round($t[0]['cnt'],2);
            //添加明细查看
            $v['planTlCnt'] ="<a href='".url('shengchan_plan','PopTousha',array(
                    'planId'=>$v['planId'],
                    'no_edit'=>1,
                    'TB_iframe'=>1,
                ))."' class='thickbox' title='计划用纱与实际用纱'>{$v['planTlCnt']}</a>";

            //实际投纱
            $sql="select sum(cnt) as cnt from cangku_common_chuku2product where planId='{$v['planId']}'";
            $t = $this->_modelDefault->findBySql($sql);
            $v['sjTlCnt'] = round($t[0]['cnt'],2);

            //计划坯布数量
            $sql="select sum(cnt) as cnt from shengchan_chanliang where ord2proId='{$v['order2proId']}'";
// 			dump($sql);
            $t = $this->_modelDefault->findBySql($sql);
            $v['pibuCnt'] = round($t[0]['cnt'],2);
            //添加明细查看
            $v['pibuCnt'] ="<a href='".url('shengchan_plan','PopPibu',array(
                    'planId'=>$v['planId'],
                    'ord2proId'=>$v['order2proId'],
                    'no_edit'=>1,
                    'TB_iframe'=>1,
                ))."' class='thickbox' title='计划用纱与实际用纱'>{$v['pibuCnt']}</a>";

            //查找生产计划的后整理工序信息
            $sql="select gongxuId,xuhao,group_concat(id) as plan2hzlId,group_concat(jiagonghuId) as jiagonghuId
                from shengchan_plan2houzheng where planId='{$v['planId']}' group by xuhao,gongxuId order by xuhao";
            $gx = $this->_modelDefault->findBySql($sql);
// 			dump($gx);exit;
            foreach ($gx as $key => & $h) {
                //查找工序名称
                $sql="select name,gongxuCode from jichu_gongxu where id='{$h['gongxuId']}'";
                $temp = $this->_modelDefault->findBySql($sql);
                $h['gongxuName'] = $temp[0]['name'];
                $h['gongxuCode'] = $temp[0]['gongxuCode'];

                //查找后整计划对应的产量
                //查找方法：工序1的产量要到工序2的发外数据中查询
// 				if($key>=count($gx)-1){
// 					//查找成品信息
// 					// dump($v);exit;
// 					$sql="select sum(cnt) as cnt from cangku_common_ruku2product where ord2proId='{$v['order2proId']}'";
// 					$temp = $this->_modelDefault->findBySql($sql);
// 					$last = 1;
// 				}else{
// 					$plan2hzlId = $gx[$key+1]['plan2hzlId'];
// 					$plan2hzlId == '' && $plan2hzlId="''";
// 					//查找工序产量
// 					$sql="select sum(cnt) as cnt from cangku_fawai2product where plan2hzlId in({$plan2hzlId})";
// 					$temp = $this->_modelDefault->findBySql($sql);
// 					$last = 0;
// 				}
                //205-5-12 by jiang 工序产量按每个工序进去的数量显示
                $plan2hzlId = $gx[$key]['plan2hzlId'];
                $sql="select sum(cnt) as cnt from cangku_fawai2product where plan2hzlId in({$plan2hzlId})";
                $temp = $this->_modelDefault->findBySql($sql);
                $_cnt = round($temp[0]['cnt'],2);
                if($_cnt!=0){
                    $h['gongxu']="<a href='".url('shengchan_plan','PopHzl',array(
                        'planId'=>$v['planId'],
                        'gongxuId'=>$h['gongxuId'],
                        'xuhao'=>$h['xuhao'],
                        'plan2hzlId'=>$plan2hzlId,
                        'last'=>0,
                        'no_edit'=>1,
                        'TB_iframe'=>1,
                    ))."' class='thickbox' title='后整进度' style='color:#51AE64'>{$h['gongxuCode']}({$_cnt})</a>";
                }else{
                    $h['gongxu']="<font color='#999'>{$h['gongxuCode']}(0)</font>";
                }
            }
            // dump($gx);exit;
            $v['gongxu_jd'] = join('→',array_col_values($gx,'gongxu'));
            // dump($v);exit;

            //成品入库信息
            $sql="select sum(cnt) as cnt from cangku_common_ruku2product where ord2proId='{$v['order2proId']}'";
            $temp = $this->_modelDefault->findBySql($sql);
            $v['rkCnt'] = round($temp[0]['cnt'],2);

            //2015-5-12 by jiang 损耗=第一后整工序数量/入库数
            //2015/9/10 HeYimiao 修改为：损耗=入库数/第一后整工序数量
            $sql="select sum(cnt) as cnt from cangku_fawai2product where plan2hzlId ='{$gx[0]['plan2hzlId']}'";
            $temp = $this->_modelDefault->findBySql($sql);
            $v['sunhao'] = round($v['rkCnt']*100/$temp[0]['cnt'],2).' %';

            //成品出库信息
            $sql="select sum(cnt) as cnt from cangku_common_chuku2product where ord2proId='{$v['order2proId']}'";
            $temp = $this->_modelDefault->findBySql($sql);
            $v['ckCnt'] = round($temp[0]['cnt'],2);

            //点产品编号看到布档案中的信息
            $v['proCode']="<a href='".url('Jichu_Chanpin','buMingxi',array(
                'productId'=>$v['productId'],
                'width'=>800,
                'no_edit'=>1,
                'TB_iframe'=>1,
            ))."' class='thickbox' title='贸易要求'>{$v['proCode']}</a>";
            //点订单号可以看到贸易要求
            $v['orderCode']="<a href='".$this->_url('Maoyi',array(
                    'orderId'=>$v['orderId'],
                    'ord2proId'=>$v['order2proId'],
                    'width'=>800,
                    'no_edit'=>1,
                    'TB_iframe'=>1,
            ))."' class='thickbox' title='贸易要求'>{$v['orderCode']}</a>";
            //余存信息，并设置是否可用
            $v['yucun'] = $v['rkCnt']-$v['ckCnt'];
            if($v['isShiyong']==1){
                $color='#000';
                $msg="已用";
            }else{
                $color='green';
                $msg="可用";
            }

            $v['yucun'].="[<a href='".$this->_url('isShiyong',array(
                    'ord2proId'=>$v['order2proId'],
                    'isShiyong'=>$v['isShiyong'],
                ))."' style='color:{$color}'>{$msg}</a>]";

            //成品入库明细弹出
            $v['rkCnt']="<a href='".url('Cangku_Chengpin_Ruku','right',array(
                'orderId'=>$v['orderId'],
                'ord2proId'=>$v['order2proId'],
                'width'=>800,
                'no_edit'=>1,
                'TB_iframe'=>1,
            ))."' class='thickbox' title='成品入库'>{$v['rkCnt']}</a>";

            //出库码单
            $v['ckCnt']="<a href='".url('Cangku_Chengpin_ChukuSell','right',array(
                'orderId'=>$v['orderId'],
                'ord2proId'=>$v['order2proId'],
                'width'=>800,
                'no_edit'=>1,
                'TB_iframe'=>1,
            ))."' class='thickbox' title='成品入库'>{$v['ckCnt']}</a>";

            //回填数据
            $sqlht="select count(*) as cnt from shengchan_huitian y
                left join shengchan_huitian2bu x on y.id=x.huitianId
                left join shengchan_plan p on p.id=y.planId
                where p.order2proId='{$v['order2proId']}'";
            $retht=$this->_modelExample->findBySql($sqlht);
            $v['huitian']="<a href='".url('Shengchan_Huitian','Mingxi',array(
                'ord2proId'=>$v['order2proId'],
                'width'=>800,
// 				'no_edit'=>1,
                'TB_iframe'=>1,
            ))."' class='thickbox' title='生产回填'>".$retht[0]['cnt']."</a>";

            //是否完成
            if($v['isOver']==1){
                $v['_edit']="<a href='".$this->_url('isOver',array(
                    'ord2proId'=>$v['order2proId'],
                    'isOver'=>0,
                ))."'>未完成</a>";
            }else{
                $v['_edit']="<a href='".$this->_url('isOver',array(
                    'ord2proId'=>$v['order2proId'],
                    'isOver'=>1,
                ))."'>完成</a>";
            }
        }

        $smarty = &$this->_getView();
        // 左侧信息
        $arrFieldInfo = array(
// 			'compName'=>array('text'=>'客户','width'=>'100'),
            '_edit'=>array('text'=>'操作'),
            'employName'=>array('text'=>'业务员','width'=>'60'),
            'orderCode'=>array('text'=>'订单号','width'=>'100'),
            'planCode'=>array('text'=>'计划单号','width'=>'90'),
            'proCode'=>array('text'=>'产品编号','width'=>'80'),
            "proName" => array('text'=>'品名','width'=>'80'),
            'guige' => array('text'=>'规格','width'=>'100'),
            'color' => array('text'=>'颜色','width'=>'60'),
            'menfuKezhong'=>array('text'=>'门幅/克重','width'=>'100'),
            'cntYaohuo'=>array('text'=>'要货数','width'=>'70'),
            'dateJiaohuo'=>array('text'=>'交期','width'=>'80'),
            'planTlCnt'=>array('text'=>'计划投纱','width'=>'80'),
            'sjTlCnt'=>array('text'=>'实际投纱','width'=>'80'),
            'pibuCnt'=>array('text'=>'坯布产量','width'=>'80'),
            'gongxu_jd'=>array('text'=>'后整进度','width'=>'250'),
            'rkCnt'=>array('text'=>'入库(Kg)','width'=>'80'),
            'sunhao'=>array('text'=>'损耗','width'=>'80'),
            'ckCnt'=>array('text'=>'出库(Kg)','width'=>'80'),
            'yucun'=>array('text'=>'余存(Kg)','width'=>'80'),
            'memo'=>array('text'=>'备注','width'=>'80'),
            'huitian'=>array('text'=>'生产回填','width'=>'80'),
        );

        $smarty->assign('title', '订单跟踪报表');
        $smarty->assign('arr_field_info', $arrFieldInfo);
        $smarty->assign('add_display', 'none');
        $smarty->assign('sonTpl', $sonTpl);
        $smarty->assign('arr_condition', $arr);
        $smarty->assign('arr_field_value', $rowset);
        $smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action'], $arr)));
        $smarty->display('TblList.tpl');
    }


    /**
     * ps ：添加备注
     * Time：2015/11/24 08:52:26
     * @author Zhujunjie
     * @param 参数类型
     * @return 返回值类型
    */
    function actionSaveBeizhuByAjax(){
        if($_GET['id']>0){
            $str="update shengchan_plan set memo='{$_GET['memo']}' where id='{$_GET['id']}' ";
            mysql_query($str);
            echo json_encode(array('success'=>true));exit;
        }
        echo json_encode(array("success"=>false,"msg"=>"11保存失败，请刷新重试！"));exit;
    }



    /**
     * 余存是否可用
     * Time：2015/04/14 16:57:09
     * @author li
    */
    function actionIsShiyong(){
        $isShiyong = $_GET['isShiyong']==0 ? 1 : 0;
        $arr = array(
            'id'=>$_GET['ord2proId'],
            'isShiyong'=>$isShiyong,
        );

        $m = &FLEA::getSingleton('Model_Trade_Order2Product');
        $m->update($arr);

        js_alert('','window.parent.showMsg("操作成功")',$this->_url('Genzong'));
    }
    /**
     * 是否完成
     * Time：2015/05/18 16:57:09
     * @author jiang
     */
    function actionIsOver(){
        $arr = array(
                'id'=>$_GET['ord2proId'],
                'isOver'=>$_GET['isOver'],
        );
        $m = &FLEA::getSingleton('Model_Trade_Order2Product');
        $m->update($arr);

        js_alert('','window.parent.showMsg("操作成功")',$this->_url('Genzong'));
    }
    /**
     * 贸易要求显示
     * Time：2015/04/28 16:57:09
     * @author jiang
     */
    function actionMaoyi(){
        $order=$this->_modelExample->find(array('id'=>$_GET['orderId']));
        echo "贸易要求：".$order['memoTrade'];
    }

    /**
     * ps ：普通订单的查询方法
     * Time：2016/02/24 13:43:33
     * @author Zhujunjie
    */
    function actionOrderCode(){
        $orderType=$_GET['orderType'];
        $orderCode=$this->_modelDefault->getNewOrderCode($orderType);
        echo json_encode(array('success'=>$orderCode));exit;
    }

     /**
     * 订单导出
     * Time：2018年11月30日13:15:25
     * @author shen
     */
    function actionExport(){
        $sql = "SELECT 
                       y.orderCode,
                       y.orderDate,
                       y.memoTrade,
                       y.memo as orderMemo,
                       y.xsType,
                       t.productId,
                       t.id as ord2proId,
                       t.cntYaohuo,
                       t.menfu,
                       t.kezhong,
                       t.zhenshu,
                       t.cunshu,
                       t.xiadanXianchang,
                       t.dateJiaohuo,
                       t.cntJian,
                       z.proCode,
                       z.proName,
                       z.color,
                       z.guige,
                       b.employName,
                       a.codeAtOrder
                from trade_order y
                left join trade_order2product t on y.id =t.orderId
                left join jichu_product z on z.id=t.productId 
                left join jichu_employ b on b.id=y.traderId 
                left join jichu_client a on a.id=y.clientId
                where 1 and y.id='{$_GET['id']}'";
        $rowset = $this->_modelExample->findBySql($sql);
        // dump($rowset);die;
    
        $mP = &FLEA::getSingleton('Model_Jichu_Product');
        $arr = array();
        $cntZ = 0;
        $cntZJian = 0;
        $percent=0;
        foreach ($rowset as $k => &$v) {
            $temp = array();
            $v['zhenshu'] = $v['zhenshu']?$v['zhenshu'].'寸':'';            
            $v['cunshu'] = $v['cunshu']?$v['cunshu'].'"':'';     
            $v['menfu'] = $v['menfu']?$v['menfu'].'cm':'';     
            $v['kezhong'] = $v['kezhong']?$v['kezhong'].'gsm':'';     

            $cntZ+=$v['cntYaohuo'];
            $cntZJian+=$v['cntJian'];
             // 获得产品的纱支组成，计算出个纱支使用的数量
            $proSha = $mP->find($v['productId']);
            $i=0;
            foreach ($proSha['Products'] as & $b) {
                $temp = array();
                $sha = $mP->find($b['productId']);
                $temp['proName'] = $sha['proName'];
                // $temp['proName'] = preg_replace('/([\x80-\xff]*)/i','',$sha['proName']);
                $temp['chengFen'] = round($b['viewPer'],2).'%';
                $temp['per'] = round($b['viewPer'],2);
                $temp['cntYaohuo']  = $v['cntYaohuo'];

                $percent = $arr[$i]['per']?$arr[$i]['per']:$b['viewPer']; //默认第一个的比例
                $temp['cntYongsha'] = round($v['cntYaohuo']*$percent/100*1.03,1);// 用纱量计算公式=要货数量*对应纱织的成分比例*（1+损耗3%）
                if(!$arr[$i]['proName']){
                    $temp['com'][$temp['proName']]['cnt'] = $temp['cntYongsha'];
                    $arr[] = $temp;
                }elseif($arr[$i]['proName']){
                    if($temp['proName']!=$arr[$i]['proName']){
                        if(!$arr[$i]['cntYongsha2']){
                            $arr[$i]['cntYongsha2'] = $arr[$i]['cntYongsha']+$temp['cntYongsha'];
                        }else{
                            $arr[$i]['cntYongsha2'] += $temp['cntYongsha'];
                        }
                        $arr[$i]['diff'] = true;
                        $arr[$i]['com'][$temp['proName']]['cnt']+=$temp['cntYongsha'];
                    }else{
                        $arr[$i]['cntYongsha'] = $arr[$i]['cntYongsha']+$temp['cntYongsha'];
                        $arr[$i]['cntYongsha2'] = $arr[$i]['cntYongsha'];
                        $arr[$i]['com'][$temp['proName']]['cnt']+=$temp['cntYongsha'];
                    }
                }
                $proName[] = $temp['proName'];
                $i++;
            }
        }
        if(count($rowset)<9){
            $cnt = 9-count($rowset);
            for ($i=0; $i <$cnt; $i++) { 
                $rowset[] = array();
            }
        } 
        foreach ($arr as $k => &$v) {
            if(!$v['diff']) continue;
            $tmpName = array();
            $tmpCnt = array();
            if($v['com']) foreach ($v['com'] as $key => &$b) {
                $tmpName[] = $key;
                $tmpCnt[] = $b['cnt'];
            }
            $v['proName'] = implode('/',$tmpName);
            $v['cntYongsha'] = implode('/',$tmpCnt);
        }
        $heji = $this->getHeji($arr,array('chengFen','cntYongsha2'),'proName');
        // dump($result);die;
        $smarty=& $this->_getView();
        $smarty->assign('rowset',$rowset);
        $smarty->assign('result',$arr);
        $smarty->assign('heji',$heji);
        $smarty->assign('cntZ',$cntZ);
        $smarty->assign('cntZJian',$cntZJian);

        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/download");
        header("Content-Disposition: attachment;filename=".iconv('utf-8',"gb2312", "订单".$rowset[0]['compName'].'-'.$rowset[0]['rukuDate']).".xls");
        header("Content-Transfer-Encoding: binary");
        
        // $tpl = 'Trade/TradeExport.tpl';
        $tpl = 'Trade/OrderExport.tpl';
        
        $smarty=$smarty->display($tpl);
    }

    /*对应订单是否有发外，并且有出库记录*/
    function checkFw($order2proId,$orderId){
        $res = array();
        if($order2proId){
            $resFw = $this->_modelFwSon->find(array('ord2proId'=>$order2proId));
            if($resFw){
                $resCK = $this->_modelCkSon->find(array('ord2proId'=>$order2proId));
                if($resCK){
                    $res['state'] = 'fwCk';
                }else{
                    $res['state'] = 'fw';
                }
            }else{
                $res['state'] = 'nofw';
            }
        }
        return $res;  
    }
}
?>