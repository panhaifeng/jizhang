<?php
FLEA::loadClass('Controller_Jichu_Product');
class Controller_Jichu_Chanpin extends Controller_Jichu_Product{
    var $_modelExample;
    var $fldMain;

    ///构造函数
    function __construct() {
    // 类别对应的符号
    //        物料类别	旧编号	新编号	新物料类别分类显示
    //        罗纹	L	81	罗纹（81）
    //        汗布	K	51	汗布（51）
    //        卫衣	J	32	卫衣（32）
    //        双面机	I	61	双面素色提花（61）
    //                62	双面条子素色提花（62）
    //                63	空气层（63）
    //        罗马布	M	65	罗马布（65）
    //        复合毛圈	H	13	复合毛圈（13）
    //        斜纹	G	41	正斜纹（41）
    //                42	斜纹（42）
    //        彩色	空白	这个要删除掉，不用了
    //        直条毛圈	E	12
        //    格子与条子毛圈（12）
    //        斜毛圈	D	21	斜毛圈（21）
    //        小毛圈	C	31	小毛圈（31）
    //        大毛圈	B	11	大毛圈（11）
    //        大斜纹	A	43	大斜纹（43）
    //        印花系列	P	这个系列删除掉，统一归类到61系列里面
    //        提花布	Q	91	双色提花布（91）
    //                92	多色提花布（92）
    //                94	楼梯提花布（94）
    //        复合布	空白	这个系列删除.
    //                夹丝布	S	93
        //            夹丝提花布（93）
    //        不倒绒	R	64	不倒绒（64）
    //        客户来样	LY	暂时保留，不做处理.
    //        单面提花汗布（71）
    //        单面提花毛圈（72）
    //        单面提花珠地（73）
    //        单面提花网眼（74）
        $this->arrKind = array(
            '罗纹'        =>  '81',
            '汗布'        =>  '51',
            '卫衣'        =>  '32',
            '双面机'       =>  '61',
            '双面素色提花'  =>  '61',
            '双面条子素色提花' => '62',
            '空气层'         => '63',
            '罗马布'         => '65',
            '复合毛圈'       => '13',
            '正斜纹'         => '41',
            '斜纹'          => '42',
            '彩色'          => 'F',
            '直条毛圈'       => '12',
            '格子与条子毛圈'   => '12',
            '斜毛圈'        => '21',
            '小毛圈'        => '31',
            '大毛圈'        => '11',
            '大斜纹'        => '43',
            '印花系列'       => '61',
            '提花布'        => '91',
            '双色提花布'     => '91',
            '多色提花布'     => '92',
            '楼梯提花布'     => '94',
            '复合布'         =>   'X',
            '夹丝布'         => '93',
            '夹丝提花布'     => '93',
            '不倒绒'        => '64',
            '彩条及其他汗布'        => '52',
            '双面珠地布'        => '66',
            '客户来样'      => 'LY',
            '单面提花汗布'      => '71',
            '单面提花毛圈'      => '72',
            '单面提花珠地'      => '73',
            '单面提花网眼'      => '74',
            '成人装'            => '99',
            '本厂开发'          => 'KF',
    );
    $this->_modelExample = & FLEA::getSingleton('Model_Jichu_Product');
    $this->_modelPro = & FLEA::getSingleton('Model_Jichu_ProductSon');
    //系统中显示的是空气层、不倒绒、罗马布,变更为：双面空气层、不倒绒系列、双面罗马布
    $this->fldMain = array(
        'kind' => array('title' => '分类', "type" => "select", 'value' => '', 'options' => array(
                array('text' => '【罗纹（81）】', 'value' => '罗纹'),
                array('text' => '【汗布（51）】', 'value' => '汗布'),
                array('text' => '【卫衣(32)】', 'value' => '卫衣'),
                array('text' => '双面机', 'value' => '双面机', 'disabled'=>true),
                array('text'=>'【双面素色提花(61)】', 'value'=>'双面素色提花'),
                array('text'=>'【双面条子素色提花(62)】', 'value'=>'双面条子素色提花'),
                array('text' => '【双面空气层(63)】', 'value' => '空气层'),
                array('text' => '【复合毛圈(13)】', 'value' => '复合毛圈'),
                array('text' => '【正斜纹(41)】', 'value' => '正斜纹'),
                array('text' => '【斜纹(42)】', 'value' => '斜纹'),
                array('text' => '彩色', 'value' => '彩色', 'disabled'=>true),
                array('text' => '直条毛圈', 'value' => '直条毛圈', 'disabled'=>true),
                array('text' => '【格子与条子毛圈(12)】', 'value' => '格子与条子毛圈'),
                array('text' => '【斜毛圈(21)】', 'value' => '斜毛圈'),
                array('text' => '【小毛圈(31)】', 'value' => '小毛圈'),
                array('text' => '【大毛圈(11)】', 'value' => '大毛圈'),
                array('text' => '【大斜纹(43)】', 'value' => '大斜纹'),
                array('text' => '印花系列', 'value' => '印花系列', 'disabled'=>true),
                array('text' => '提花布', 'value' => '提花布', 'disabled'=>true),
                array('text' => '【双色提花布(91)】', 'value' => '双色提花布'),
                array('text' => '【多色提花布(92)】', 'value' => '多色提花布'),
                array('text' => '【楼梯提花布(94)】', 'value' => '楼梯提花布'),
                array('text' => '【双面罗马布(65)】', 'value' => '罗马布'),
                array('text' => '复合布', 'value' => '复合布', 'disabled'=>true),
                array('text' => '夹丝布', 'value' => '夹丝布', 'disabled'=>true),
                array('text' => '【夹丝提花布(93)】', 'value' => '夹丝提花布'),
                array('text' => '【不倒绒系列(64)】', 'value' => '不倒绒'),
                array('text' => '【彩条及其他汗布(52)】', 'value' => '彩条及其他汗布'),
                array('text' => '【双面珠地布(66)】', 'value' => '双面珠地布'),
                array('text' => '客户来样', 'value' => '客户来样'),
                array('text' => '【单面提花汗布(71)】', 'value' => '单面提花汗布'),
                array('text' => '【单面提花毛圈(72)】', 'value' => '单面提花毛圈'),
                array('text' => '【单面提花珠地(73)】', 'value' => '单面提花珠地'),
                array('text' => '【单面提花网眼(74)】', 'value' => '单面提花网眼'),
                array('text' => '【成人装（99）】', 'value' => '成人装'),
                array('text' => '本厂开发', 'value' => '本厂开发'),
            )),
        'proName' => array('title' => '品名', 'type' => 'text', 'value' => ''),
        'proCode' => array('title' => '物料编号', "type" => "text", 'value' => '','readonly'=>true),
        'guige' => array('title' => '规格', 'type' => 'text', 'value' => ''),
        'color' => array('title' => '颜色', 'type' => 'text', 'value' => ''),
        'chengFen' => array('title' => '成份', 'type' => 'text', 'value' => ''),
        'menfu' => array('title' => '门幅', 'type' => 'text', 'value' => '','addonEnd'=>'cm'),
        'kezhong' => array('title' => '克重', 'type' => 'text', 'value' => '','addonEnd'=>'gsm'),
        'clientId' => array('title' => '客户名称', 'type' => 'clientpopup', 'clientName' => ''),
        'warpShrink' => array('title' => '经向缩率', 'type' => 'text', 'value' => '', 'addonEnd' => '%'),
        'weftShrink' => array('title' => '纬向缩率', 'type' => 'text', 'value' => '', 'addonEnd' => '%'),
        'isStop' => array('title' => '使用状态', "type" => "select", 'value' => '0', 'options' => array(
          array('text' => '正常使用', 'value' => '0'),
          array('text' => '停止使用', 'value' => '1'),
        )),
        'imageFile' => array('type'=>'image','title' => '图片文件', 'name'=>'imageFile', 'id'=>'imageFile', 'onChange'=>'setBox()', 'style'=>'width:215px;'),
        'picture' => array('title' => '图片', 'type' => 'hidden', 'value' => ''),
        'imageFileGY' => array('type'=>'imageGY','title' => '工艺图片', 'name'=>'imageFileGY', 'id'=>'imageFileGY', 'onChange'=>'setBoxGY()', 'style'=>'width:215px;'),
        'pictureGY' => array('title' => '工艺图片', 'type' => 'hidden', 'value' => ''),
        // 'clothType' => array('title' => '机型', 'type' => 'text', 'value' => ''),
        
        'zhengshu' => array('title' => '针数', "type" => "select", 'value' => '', 'options' => array(
          array('text' => '16G', 'value' => '16G'),
          array('text' => '18G', 'value' => '18G'),
          array('text' => '20G', 'value' => '20G'),
          array('text' => '22G', 'value' => '22G'),
          array('text' => '24G', 'value' => '24G'),
          array('text' => '28G', 'value' => '28G'),
          array('text' => '32G', 'value' => '32G'),
        )),

        'cunshu' => array('title' => '寸数', "type" => "select", 'value' => '', 'options' => array(
          array('text' => '26"', 'value' => '26"'),
          array('text' => '30"', 'value' => '30"'),
          array('text' => '34"', 'value' => '34"'),
          array('text' => '38"', 'value' => '38"'),
        )),


        'memo' => array('title' => '备注说明', 'type' => 'textarea', 'value' => ''),
        'id' => array('type' => 'hidden', 'value' => '','name'=>'proId'),
        'state' => array('type' => 'hidden', 'value' => '1'),

    );

    $this->headSon = array(
      '_edit'=>array('type'=>'btBtnRemove',"title"=>'+5行','name'=>'_edit[]'),
      'productId' => array(
              'title' => '选择纱支',
              "type" => "btPopup",
              'name' => 'productId[]',
              'url'=>url('jichu_product','popup'),
              'textFld'=>'proCode',
              'hiddenFld'=>'id',
              'inTable'=>true,
          ),
      'proNameson'=>array('type'=>'bttext',"title"=>'纱支','name'=>'proNameson[]','readonly'=>true),
      'proGuige'=>array('type'=>'bthidden',"title"=>'规格','name'=>'proGuige[]'),
      //用于计算，原来称成分比例，后来客户提出为纱支比例，用于计算
      'viewPer'=>array('type'=>'bttext',"title"=>'纱支比列(%)','name'=>'viewPer[]'),
      // 'component'=>array('type'=>'bttext',"title"=>'成分','name'=>'component[]','readonly'=>true),
      'xianchang'=>array('type'=>'bttext',"title"=>'线长','name'=>'xianchang[]'),
      'memoView'=>array('type'=>'bttext',"title"=>'备注','name'=>'memoView[]'),
      //***************如何处理hidden?
      'proChengFenPer'=>array('type'=>'bthidden',"title"=>'','name'=>'proChengFenPer[]'),
      'proComponent'=>array('type'=>'bthidden',"title"=>'','name'=>'proComponent[]'),
      'id'=>array('type'=>'bthidden','name'=>'id[]'),
    );
    $this->headGongxu = array(
      '_edit'=>array('type'=>'btBtnRemove',"title"=>'+5行','name'=>'_edit[]'),
      'xuhao'=>array('type'=>'bttext',"title"=>'序号','name'=>'xuhao[]','readonly'=>'true','style'=>'width:50px;'),
      'gongxuId'=>array('type'=>'btselect',"title"=>'工序','name'=>'gongxuId[]','model'=>'Model_jichu_Gongxu','action'=>'getOptions'),
      'id'=>array('type'=>'bthidden','name'=>'gxId[]'),
    );

    $this->rules=array(
      'kind'=>'required',
      'proCode2'=>'required',
      'pinzhong'=>'required',
      'guige2'=>'required',
      'guige' => 'repeatGuige'
      // 'color2'=>'required',
    );
    }


    function actionRight() {
      set_time_limit(0);
        FLEA::loadClass('TMIS_Pager');
        $arr = TMIS_Pager::getParamArray(array(
            'proCode' => '',
            'proName' => '',
            'guige' => '',
            'chenfen' => '',
            'kezhong'=>'',
            'kind'=>'',
            'memo'=>''
        ));
        $str = "select * from jichu_product where 1 and state=1";
        if ($arr['kind']!='') $str .= " and kind like '%{$arr['kind']}%'";
        if ($arr['proCode']!='') $str .= " and proCode like '%{$arr['proCode']}%'";
        if ($arr['proName']!='') $str .= " and proName like '%{$arr['proName']}%'";
        if ($arr['guige']!='') {
          if(strpos($arr['guige'], '+')){
                $tempKey = explode('+', $arr['guige']);
                foreach ($tempKey as & $v) {
                  $strObj[] = " guige like '%{$v}%'";
                }
                $str .= ' and '.join(' and ', $strObj);
          }else{
            $str .= " and guige like '%{$arr['guige']}%'";
          }
        }
        if ($arr['chenfen']!='') $str .= " and chengFen like '%{$arr['chenfen']}%'";
        if($arr['kezhong']!='')$str .= " and kezhong like '%{$arr['kezhong']}%'";
        if($arr['memo']!='')$str .= " and memo like '%{$arr['memo']}%'";
        $str .=" order by proCode asc";
        if($_GET['export']==1){
          $rowset = $this->_modelExample->findBySql($str);
        }else{
          $pager =& new TMIS_Pager($str);
          $rowset =$pager->findAllBySql($str);
        }
        if(count($rowset)>0) foreach($rowset as & $v){
            $v['_edit'] = '';
            //2015-7-7 by jiang 添加删除和修改的权限
            if($this->authCheck('6-4-2',true)){
                $v['_edit'] .= $this->getEditHtml($v['id']) . ' ' . $this->getRemoveHtml($v['id']);
            }

            $v['_edit'] .="&nbsp;&nbsp;"."<a href='".$this->_url('Edit',array(
                            'id'=>$v['id'],
                            'flag'=>1,
                            'no_edit'=>1,
                            'TB_iframe'=>1
                            ))."' title='查看'>查看</a>";

            $v['_edit'] .= " <a href='".$this->_url('PrintBarCode',array(
                            'id'=>$v['id']
                            )) . "' target='_blank'>条码</a>";
            $v['_edit'] .= " <a href='".$this->_url('Copy',array(
                            'id'=>$v['id']
                            ))."'>复制</a>";

            $sql="select * from jichu_product_chengfen where proId='{$v['id']}'";

            $ret=$this->_modelPro->findBySql($sql);

            foreach ($ret as $k=>& $r){
                if($k==0) {
                    $v['viewPer']=$r['viewPer']!='0.0'?$r['viewPer']:'';;
                    $v['xianchang']=$r['xianchang'];
                }else{
                    $v['viewPer'].=$r['viewPer']!='0.0'?'/'.$r['viewPer']:'';
                    $v['xianchang'].=$r['xianchang']?'/'.$r['xianchang']:'';
                }
            }
            $v['menfu'] = $v['menfu']!=''?$v['menfu'].'cm':'';
            $v['kezhong'] = $v['kezhong']!=''?$v['kezhong'].'gsm':'';
            $v['price']  = $v['price']!=0?$v['price']:'';
            if($_GET['export']!=1){
                $v['baojia'] = "<input type='text' name='baojia[]' value='{$v['baojia']}' />
                                <input type='hidden' name='id[]' value='{$v['id']}' />";
                $v['price']  = "<input type='text' name='price[]' value='{$v['price']}'>
                                <input type='hidden' name='priceId[]' value='{$v['id']}' />";
            }

            //添加了查看图片列，点击小图片弹出窗口显示布匹图片，2015-09-11，by liuxin
            if($v['imageFile']!=''){
                $v['imageFile'] = "<a href='".$this->_url('showImage',array(
                      'barCode'=>$v['barCode'],
                      'img'=>$v['imageFile']!=''?$v['imageFile']:'',
                      'bimg'=>$v['bigimageFile']!=''?$v['bigimageFile']:'',
                      'width'=>'900',
                      'height'=>'550',
                      'baseWindow'=>'parent',
                      'TB_iframe'=>1
                ))."'  class='thickbox' title='查看图片'><img src='Resource/Image/img.gif' style='border:0px'></a>";
            }

            if($v['imageFileGY']!=''){
                $v['imageFileGY'] = "<a href='".$this->_url('showImage',array(
                      'barCode'=>$v['barCode'],
                      'img'=>$v['imageFileGY']!=''?$v['imageFileGY']:'',
                      //'height'=>'450',
                      'baseWindow'=>'parent',
                      'TB_iframe'=>1
                ))."'  class='thickbox' title='查看图片'><img src='Resource/Image/img.gif' style='border:0px'></a>";
            }
            //开发登记上录入的显示绿色
            if($v['localDelp']==1){
                $v['_bgColor'] = 'green';
            }
            // 按照状态显示颜色，，，如果为isStop，就直接为pink,不考虑是不是开发登记上录入的
            if($v['isStop']){
                $v['_bgColor'] = 'pink';
            }
        }
        $smarty = & $this->_getView();
        $smarty->assign('title', '选择产品');
        $pk = $this->_modelExample->primaryKey;
        $smarty->assign('pk', $pk);
        //2015-7-7 by jiang 添加报价一览的权限
        if($this->authCheck('6-4-3',true))
            $arr_field_info = array(
                    "_edit" => '操作',
                    // "kind" => array('text'=>'类别','width'=>70),
                    "proCode" => array('text'=>"产品编号",'width'=>70),
                    "proName" => array('text'=>"品名",'width'=>180),
                    "color" => array('text'=>"颜色" ,'width'=>150),
                    "guige" => array('text'=>"规格",'width'=>280),
                    "imageFile" => array('text'=>"图片文件",'width'=>70),
                    "imageFileGY" => array('text'=>"工艺图片",'width'=>70),
                    "baojia" => array('text'=>"报价区间",'width'=>70),
                    "chengFen" =>array('text'=>"成份",'width'=>150),
                    'menfu'=>array('text'=>'门幅','width'=>70),
                    'kezhong'=>array('text'=>'克重','width'=>70),
                    // "viewPer"=>array('text'=>'纱支比例','width'=>140),
                    // "xianchang"=>array('text'=>'线长','width'=>100),
                    'memo'=>'备注'
            );
        else
            $arr_field_info = array(
              "_edit" => '操作',
              // "kind" => array('text'=>'类别','width'=>70),
              "proCode" => array('text'=>"产品编号",'width'=>70),
              "proName" => array('text'=>"品名",'width'=>180),
              "color" => array('text'=>"颜色" ,'width'=>150),
              "guige" => array('text'=>"规格",'width'=>280),
              "imageFile" => array('text'=>"图片文件",'width'=>70),
              "imageFileGY" => array('text'=>"工艺图片",'width'=>70),
              "baojia" => array('text'=>"报价区间",'width'=>70),
              "chengFen" =>array('text'=>"成份",'width'=>150),
              'menfu'=>array('text'=>'门幅','width'=>70),
              'kezhong'=>array('text'=>'克重','width'=>70),
              // "viewPer"=>array('text'=>'纱支比例','width'=>140),
              // "xianchang"=>array('text'=>'线长','width'=>100),
              'memo'=>'备注'
            );
        //验证是否有查看纱支比例和线长字段的权限，没有则隐藏这两个字段，2015-09-10，by liuxin
        if (!($this->authCheck('6-4-4',true))){
          unset($arr_field_info['viewPer']);
        }
        if (!($this->authCheck('6-4-5',true))) {
          unset($arr_field_info['xianchang']);
        }
        // 是否有可以设置布档案单价的权限。
        if($this->authCheck('6-4-7',true)){
            $arr_field_info['price'] = array('text'=>'单价', 'width'=>100);
        }

        if ($_GET['export']==1) {
          unset($arr_field_info['_edit']);
          unset($arr_field_info['imageFile']);
        }
        $smarty->assign('title','布档案');
        $smarty->assign('arr_field_info',$arr_field_info);
        $smarty->assign('arr_field_value',$rowset);
        if($_GET['export']==1){
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: application/force-download");
            header("Content-Type: application/download");
            header("Content-Disposition: attachment;filename=test.xls");
            header("Content-Transfer-Encoding: binary");
            $smarty->display('Export2Excel2.tpl');
            exit;
        }
        $smarty->assign('arr_condition',$arr);
        $smarty->assign('sonTpl', 'Jichu/Chanpin.tpl');
        $smarty->assign('fn_export',$this->_url($_GET['action'],array('export'=>1)));
        $smarty->assign('page_info',$pager->getNavBar($this->_url($_GET['action'],$arr)).'<font color="red">注：红色为停止使用的产品</font>');
        $smarty-> display('TblList.tpl');
    }

    function actionAdd() {
      // 从表区域信息描述
      $areaMain = array('title' => '成布基本信息', 'fld' => $this->fldMain);
      if($_GET['localDvlp']==1){ 
        $areaMain['fld']['kind']['value'] = '本厂开发';//默认为本厂开发
        //物料编号（当为本厂开发时）
        $areaMain['fld']['proCode']['value'] = $this->_getChanpinCodeKf($areaMain['fld']['kind']['value']);
        //如果是本厂开发，默认只能选择本厂开发，其他都不能选
        foreach ($areaMain['fld']['kind']['options'] as $key => &$value) {
            if($value['value']!='本厂开发'){
              $value['disabled'] = 1;
            }
        }
      }
  
      // 从表信息字段,默认5行
      for($i = 0;$i < 5;$i++) {
        $rowsSon[] = array();
      }
      for($i=0;$i<5;$i++){
        $temp['xuhao'] = array('value'=>$i+1);
        $gongxuSon[]=$temp;
      }
      $smarty = &$this->_getView();
      if(isset($_GET['type']) && $_GET['type']=='Popup'){
          $smarty->assign('fromAction','Add');
          $smarty->assign('localDelp','yes'); //开发登记页面快捷录入的需要做个标记
      }
      $smarty->assign('areaMain', $areaMain);
      $smarty->assign('headSon', $this->headSon);
      $smarty->assign('headGongxu', $this->headGongxu);
      $smarty->assign('rowsSon', $rowsSon);
      $smarty->assign('gongxuSon', $gongxuSon);
      $smarty->assign('rules', $this->rules);
      $smarty->assign("otherInfoTpl",'jichu/GongxuInfoTpl.tpl');
      $smarty->assign('sonTpl',"Jichu/proSonTpl.tpl");
      $smarty->display('Main2Son/T5.tpl');
    }

    function actionEdit() {
        if($_GET['flag']){
          //查看
          $this->authCheck('6-4-1');
          foreach($this->fldMain as & $v){
            $v['disabled']='disabled';
          }
          foreach($this->headSon as & $vv){
            $vv['readonly']=true;
          }
        }else{
          //修改
          $this->authCheck('6-4-2');
        }
        $arr = $this->_modelExample->find(array('id' => $_GET['id']));
        $arr['picture']=$arr['imageFile'];
        // dump($arr);exit;
        //设置主表id的值
        foreach ($this->fldMain as $k => &$v) {
            $v['value'] = $arr[$k]?$arr[$k]:$v['value'];
        }
        foreach($arr['Products'] as &$v) {
            $temp = array();
            foreach($this->headSon as $kk => &$vv) {
                $temp[$kk] = array('value' => $v[$kk]);
            }
            $pro = $this->_modelExample->find(array('id' => $v['productId']));
            $temp['productId']=array('text' => $pro['proCode'],'value' => $pro['id']);
            $temp['proNameson']=array('value' => $pro['proName']);
            $temp['proGuige']=array('value' => $pro['guige']);
            $temp['sonId']=array('value' => $v['id']);
            $strChF = "select c.chengfenPer,cc.name from jichu_product p left join  jichu_product_chengfen c on p.id=c.proId left join jichu_chengfen cc on c.component=cc.id where p.id ='{$pro['id']}'";
            $rowsetChF=$this->_modelPro->findBySql($strChF);
            foreach($rowsetChF as $value) 
            {
                $pro['proChengFenPer'] .=$value['chengfenPer'] . ',';
                $pro['proComponent'] .=$value['name']. ','; 
            }
            $strChF=$pro['proChengFenPer'];
            $strCom=$pro['proComponent'];
            $temp['proChengFenPer']=array('value' => substr($strChF,0,strlen($strChF)-1));
            $temp['proComponent']=array('value' => substr($strCom,0,strlen($strCom)-1));
            $rowsSon[] = $temp;
        }
        //补齐5行
        $cnt = count($rowsSon);
        for($i=5;$i>$cnt;$i--) {
            $rowsSon[] = array();
        }
       /*
        * 工序明细处理
        */
        // $sqlgx="select * from jichu_product_gongxu where productId='{$arr['id']}' group by xuhao";
        // $arr['gxs']=$this->_modelExample->findBySql($sqlgx);
        // $gongxuSon=array();
        // foreach($arr['gxs'] as &$v) {
        //     $temp=array();
        //     foreach($this->headGongxu as $key => &$vv) {
        //         $temp[$key] = array('value' => $v[$key]);
        //     }
        //     $gongxuSon[] = $temp;
        // }
       foreach($arr['Gongxu'] as &$vx) {
          $temp = array();
          foreach($this->headGongxu as $key => &$vv) {
            $temp[$key] = array('value' => $vx[$key]);
          }
          $gongxuSon[] = $temp;
        }
        // dump($gongxuSon);exit();
        //补齐5行
        $cnt = count($gongxuSon);
        // dump($cnt);exit();
        for($i=$cnt+1;$i<6;$i++) {
            $temp=array();
            $temp['xuhao']=array('value'=>$i);
            $gongxuSon[] = $temp;
        }
        $smarty = &$this->_getView();
        $smarty->assign('areaMain',array('title' => '布基本信息', 'fld' => $this->fldMain));
        $smarty->assign('headSon', $this->headSon);
        $smarty->assign('headGongxu', $this->headGongxu);
        $smarty->assign('rowsSon', $rowsSon);
        $smarty->assign('gongxuSon', $gongxuSon);
        $smarty->assign('rules', $this->rules);
        $smarty->assign('sonTpl',"Jichu/proSonTpl.tpl");
        $smarty->assign("otherInfoTpl",'jichu/GongxuInfoTpl.tpl');    
        $smarty->assign('print', 'yes');
        $smarty->assign('flag', $_GET['flag']);
        //T5.tpl在flag==1时没有保存按钮只有返回按钮
        $smarty->display('Main2Son/T5.tpl');
    }

    function actionSave(){
        //dump($_FILES);exit;
        // dump($_POST);exit;
        if (!$_POST['kind']) {
            // js_alert('请选择类别!', null, $this->_url($_POST['fromAction']));
            js_alert('请选择类别!', "window.history.go(-1)");
            exit;
        }
        if (!$_POST['proCode']) {
            // js_alert('产品编码缺失!', null, $this->_url($_POST['fromAction']));
            js_alert('产品编码缺失!', "window.history.go(-1)");
            exit;
        }else {
            // 产品编码不重复
            $sql = "select count(*) cnt from jichu_product where kind='{$_POST['kind']}' and proCode='{$_POST['proCode']}' and id<>'{$_POST['proId']}'";
            $_rows = $this->_modelExample->findBySql($sql);
        // dump($_rows);die;
            if ($_rows[0]['cnt'] > 0) {
                js_alert('产品编码重复!', "window.history.go(-1)");
                exit;
            }
        }
        if (!$_POST['proName']) {
            // js_alert('品名缺失!', null, $this->_url($_POST['fromAction']));
            js_alert('品名缺失!','window.history.go(-1)');
            exit;
        }
        if (!$_POST['guige']) {
            // js_alert('规格缺失!', null, $this->_url($_POST['fromAction']));
            js_alert('规格缺失!','window.history.go(-1)');
            exit;
        }
        // dump($_POST);die;
        //处理图片，2015-09-11,by liuxin
        //如果编辑界面选择了删除图片的复选框，就移除原有图片
        if($_POST['isDelImage']=='yes'){
        $re = $this->_modelExample->find(array('id'=>$_POST['proId']));
        $str="update jichu_product set imageFile='' where id = ".$_POST['proId'];
        $res=mysql_query($str);
        if($res > 0){
          unlink($re['imageFile']);
        }
        }else{
          if($_FILES['imageFile']['name']!=''){
            //$imgPath = $this->upLoad($_FILES);
            list($imgPathb, $imgPath) = $this->AddPics(30, 'Upload/Sample/', 250,'imageFile');
//            $imgPath = $this->upLoad($_FILES);
            $_POST['imageFile'] = $imgPath;
            $_POST['bigimageFile'] = $imgPathb;
          }else{
            $imgPath=$_POST['picture'];
          }
        }

        if($_POST['isDelImage']=='yes'){
        $reGY = $this->_modelExample->find(array('id'=>$_POST['proId']));
        $strGY="update jichu_product set imageFileGY='' where id = ".$_POST['proId'];
        $resGY=mysql_query($strGY);
        if($resGY > 0){
          unlink($reGY['imageFileGY']);
        }
        }else{
          if($_FILES['imageFileGY']['name']!=''){
            $imgPathGY = $this->upLoadGY($_FILES);
            $_POST['imageFileGY'] = $imgPathGY;
          }else{
            $imgPathGY=$_POST['picture'];
          }
        }

        //图片处理结束
        foreach ($_POST['productId'] as $key =>& $value){
            if(empty($_POST['productId'][$key])) continue;
            $arr[]=array(
                'id'=>$_POST['id'][$key],
                'productId'=>$_POST['productId'][$key],
                'viewPer'=>$_POST['viewPer'][$key],
                'component'=>$_POST['proComponent'][$key].'',
                'xianchang'=>$_POST['xianchang'][$key].'',
                'memoView'=>$_POST['memoView'][$key],
            );
        }

        foreach ($_POST['gongxuId'] as $k =>& $v){
          if(empty($_POST['gongxuId'][$k])) continue;
          $gongxu[]=array(
            'id'=>$_POST['gxId'][$k],
            'gongxuId'=>$_POST['gongxuId'][$k],
            'xuhao'=>$_POST['xuhao'][$k]
          );
        }

        //删除未保存的成份
        foreach ($_POST['id'] as $k =>&$v){
            if(!$v) continue;
            $ids[]=$v;
        }
        $sql="select * from jichu_product_chengfen where proId='{$_POST['proId']}'";
        $ret=$this->_modelPro->findBySql($sql);
        foreach ($ret as $key => &$value) {
            if( !in_array($value['id'],$ids)){
                $removeId[] = $value['id'];
            }
        }
        foreach ($removeId as $key => &$g) {
            if($g){
              $r = $this->_modelPro->removeByPkv($g);
            }
        }
        //如果是开发登记页面快捷新增的类型 ，需要标记一下
        if($_POST['localDelp']=='yes'){
            $localDelp = 1;
        }else{
            $localDelp = 0;
        }
        //删除未保存的成份end

        $row=array(
            'id'=>$_POST['proId'],
            'kind' =>$_POST['kind'],
            'proCode' =>$_POST['proCode'],
            'proName' =>$_POST['proName'],
            'guige' =>$_POST['guige'],
            'color' => $_POST['color'],
            'chengFen' =>$_POST['chengFen'],
            'clientId' =>$_POST['clientId'],
            'warpShrink' => $_POST['warpShrink'], // 经向缩率
            'weftShrink' => $_POST['weftShrink'], // 纬向缩率
            'isStop' =>$_POST['isStop'],
            'menfu' =>$_POST['menfu'],
            'kezhong' =>$_POST['kezhong'],
            'imageFile' =>$imgPath,
            'imageFileGY' =>$imgPathGY,
            'bigimageFile' =>$imgPathb,
            'memo' =>$_POST['memo'],
            'state' =>$_POST['state'],
            'clothType' =>$_POST['clothType'].' ',
            'zhengshu' =>$_POST['zhengshu'],
            'cunshu' =>$_POST['cunshu'],
            'localDelp'=>$localDelp,
            'Products' =>$arr,
            'Gongxu' =>$gongxu
        );
        // dump($row);exit;
        //dump($_POST['fromAction']);die;
        $id = $this->_modelExample->save($row);
        js_alert('',null,$this->_url($_POST['fromAction']==''?'right':$_POST['fromAction'],array('fromAction'=>$_POST['fromAction'])));
        // js_alert(null, 'window.parent.showMsg("保存成功")',$this->_url('right'));
        // exit;
    }

    function actionIsGuigeRepeat(){
        if($_GET['field']=='' || $_GET['fieldValue']==''){
            exit;
        }
        //查找是否存在
        $con=array();
        $con[]=array($_GET['field'],$_GET['fieldValue'],'=');
        if($_GET['id']>0)$con[]=array('id',$_GET['id'],'<>');
        $temp=$this->_modelExample->findAll($con);
        $success = count($temp)>0?false:true;

        echo json_encode(array('success'=>$success));
    }

    function actionPopup() {
        FLEA::loadClass('TMIS_Pager');
        $arr = TMIS_Pager::getParamArray(
                array(
                    'key' => '',
                    'kind'=>'',
                    'kezhong'=>'',
                ));
        $str = "select * from jichu_product where 1 and state=1 and isStop=0";
        if ($arr['key'] != '') {
            if(strpos($arr['key'], '+')){
            $tempKey = explode('+', $arr['key']);
            foreach ($tempKey as & $v) {
              $strObj[] = " guige like '%$v%'";
            }
            $guigeStr = join(' and ', $strObj);
            $str .= " and (proCode like '%{$arr['key']}%'
                                or proName like '%{$arr['key']}%'
                                or ({$guigeStr}))";
            }else{
            $str .= " and (proCode like '%{$arr['key']}%'
                                or proName like '%{$arr['key']}%'
                                or guige like '%{$arr['key']}%')";
            }
        }
        if ($arr['kind']!=''){
            $str .= " and kind like '%{$arr['kind']}%'";
        }
        if ($arr['kezhong'] != ''){
            $str .= " and kezhong like '%{$arr['kezhong']}%'";
        }
        $str .= " order by proCode asc,proName asc,guige asc";
        $pager = &new TMIS_Pager($str);
        $rowset = $pager->findAllBySql($str);
        if (count($rowset) > 0) foreach($rowset as &$v) {
            //显示
            $v["_proName"] = $v["proName"];
            $v["proName"] = $v["proName"]."   ".$v["guige"];
        }
        $smarty = &$this->_getView();
        $smarty->assign('title', '选择产品');
        $pk = $this->_modelExample->primaryKey;
        $smarty->assign('pk', $pk);
        $arr_field_info = array(
            "kind" => array('text'=>"分类",'width'=>60),
            "proCode" => array('text'=>"编码",'width'=>60),
            "_proName" => array('text'=>"产品名称",'width'=>200),
            "guige" => array('text'=>"规格",'width'=>200),
            "color" => "颜色",
            "menfu" => "门幅",
            "kezhong" => "克重",
        );
        $smarty->assign('arr_field_info', $arr_field_info);
        $smarty->assign('arr_field_value', $rowset);
        $smarty->assign('add_display', 'none');
        $smarty->assign('arr_js_css', $this->makeArrayJsCss(array('thickbox')));
        $smarty->assign('arr_condition', $arr);
        $smarty->assign('page_info', $pager->getNavBar($this->_url('Popup', $arr)));
        $smarty->display('Popup/CommonNew.tpl');
    }
    function actionRemove(){
      $this->authCheck('6-4-6');
      //查找订单信息
      $sql="select count(*) as cnt from trade_order2product where productId='{$_GET['id']}'";
      $rest = $this->_modelExample->findBySql($sql);
      if($rest[0]['cnt']>0){
        js_alert('订单中已使用该产品信息，禁止删除','',$this->_url('right'));
        exit;
      }
      // 若产品存在出入库记录(库存表中存在记录)，则不可删除
      $sql = "SELECT count(*) as cnt 
              FROM cangku_common_kucun 
              WHERE productId = '{$_GET['id']}'
             ";
      $rest = $this->_modelExample->findBySql($sql);
      if($rest[0]['cnt']>0){
          js_alert('该产品已存在出入库信息，禁止删除','',$this->_url('right'));
          exit;
      }

      //删除功能同时删除该记录上传的图片，2015-09-11，by liuxin
      $from = $_GET['fromAction']?$_GET['fromAction']:$_GET['from'];
      $re = $this->_modelExample->find(array('id'=>$_GET['id']));
      if ($this->_modelExample->removeByPkv($_GET['id'])) {
        unlink($re['imageFile']);
        if($from=='') redirect($this->_url("right"));
        else js_alert(null,"window.parent.showMsg('成功删除')",$this->_url($from));
    }
    else js_alert('出错，不允许删除!','',$this->_url($from));
      // parent::actionRemove();
    }
    function actionPrintBarCode() {
    $row=$this->_modelExample->find(array('id'=>$_GET['id']));
    $row['menfu'] = str_replace(' ','',$row['menfu']).'cm';
    $row['kezhong'] = str_replace(' ','',$row['kezhong']).'gsm';
    $guigeLength = mb_strlen($row['guige'],'utf-8');//规格长度，大于20换行
    // dump($guigeLength);exit;
    // $row['guige'] = str_replace('）',')',$row['guige']);
    // $row['guige'] = preg_replace('/([\x80-\xff]*)/i','',$row['guige']);
    // $row['guige'] = str_replace('()','',$row['guige']);
    // $row['guige'] = "";
    // $row['proKind']=($row['proColor']!=''?$row['proColor'].' ':'').$row['proKind'];
    // dump($row);exit;
    $smarty=& $this->_getView();
    $smarty->assign('aRow',$row);
    $smarty->assign('guigeLength',$guigeLength);
    $smarty->display('JiChu/PrintBarcode.tpl');
    }
    function actionGetShaInfo(){
    // 		dump($arr);
        $str="select x.*,y.proName from jichu_product_chengfen x
            left join jichu_product y on x.productId=y.id where proId='{$_GET['proId']}'";
        $ret = $this->_modelExample->findBySql($str);
    echo json_encode(array('success'=>count($ret)>0,'Sha'=>$ret));exit;
    }
    //修改界面中ajax删除
    function actionRemoveByAjax() {
      //纱档案不保存时候不真正删除纱支选项 2017年10月24日 by shen
      // $id=$_POST['id'];
      // $r = $this->_modelPro->removeByPkv($id);
      // if(!$r) {
      //     // js_alert('删除失败');
      //     echo json_encode(array('success'=>false,'msg'=>'删除失败'));
      //     exit;
      // }
      echo json_encode(array('success'=>true));
    }
    function actionbuMingxi(){
        $str = "select x.chengFen,y.xianchang,y.viewPer from jichu_product x
                left join jichu_product_chengfen y on x.id=y.proId
                left join jichu_product p on y.productId=y.id
                 where 1 and x.state=1 and x.id='{$_GET['productId']}'";
        $rowset=$this->_modelExample->findBySql($str);
        foreach ($rowset as $k=>&$v){
            $temp[0]['chengFen']=$v['chengFen'];
            if($k==0){
                $temp[0]['xianchang']=$v['xianchang'];
                $temp[0]['viewPer']=$v['viewPer'].'%';
            }
            else{
                $temp[0]['xianchang'].='/'.$v['xianchang'].'%';
                $temp[0]['viewPer'].='/'.$v['viewPer'].'%';
            }
        }
    // 		dump($rowset);exit;
        $smarty = & $this->_getView();
        $smarty->assign('title', '选择产品');
        $pk = $this->_modelExample->primaryKey;
        $smarty->assign('pk', $pk);
        $arr_field_info = array(
                "xianchang" => "线长",
                "viewPer" => "纱支比列(%)",
                "chengFen" =>"成份",
        );
        $smarty->assign('arr_field_info',$arr_field_info);
        $smarty->assign('arr_field_value',$temp);
        $smarty-> display('TblList.tpl');
    }
    function actionSetBaojia(){
        $arr=array(
                'id'=>$_GET['id'],
                'baojia'=>$_GET['baojia']
        );
        $id = $this->_modelExample->save($arr);

        js_alert(null, 'window.parent.showMsg("保存成功")',$this->_url('right'));
        exit;
    }

    /**
     * 布档案列表界面- 设置单价信息
     */
    function actionSetPrice(){
        $this->authCheck('6-4-7');
        $arr = array(
            'id' => $_POST['id'],
            'price' => $_POST['price']
        );

        $res = $this->_modelExample->save($arr);
        $data = array(
            'success' => $res>0 ? true : false,
            'msg'     => ''
        );
        echo json_encode($data);exit;
    }

    //显示图片大图，2015-09-11，by liuxin
    function actionShowImage(){
      $arr=$_GET;
      //dump($arr);
      $smarty = & $this->_getView();
      $smarty->assign('title','布匹大图');
      $smarty->assign('row',$arr);
      $smarty->display('Jichu/showImage.tpl');
    }
    //上传图片处理函数，2015-09-11，by liuxin
    function upLoad($arrFile) {
    $file = $arrFile["imageFile"];
    $uptypes=array('image/jpg',  //上传文件类型列表
    'image/jpeg',
    'image/png',
    'audio/x-pn-realaudio-plugin',
    'image/gif',
    'image/bmp',
    'application/x-shockwave-flash',
    'image/x-png');
    $max_file_size=20000000;
    $destination_folder="Upload/Sample/"; //上传文件路径,必须属性为７７７否则出现移动文件出错的错误
    $watermark=2;   //是否附加水印(1为加水印,其他为不加水印);
    $watertype=1;   //水印类型(1为文字,2为图片)
    $waterposition=1;   //水印位置(1为左下角,2为右下角,3为左上角,4为右上角,5为居中);
    $waterstring="wtenglish.com"; //水印字符串
    $waterimg="xplore.gif";  //水印图片
    $imgpreview=2;   //是否生成预览图(1为生成,其他为不生成);
    $imgpreviewsize=1/1;  //缩略图比例

    if (!is_uploaded_file($file['tmp_name']))//是否存在文件
    {
    echo "<font color='red'>文件不存在！</font>";
    exit;
    }

    if($max_file_size < $file["size"])//检查文件大小
    {
    echo "<font color='red'>文件太大！</font>";
    exit;
    }
    if(!file_exists($destination_folder)) mkdir($destination_folder);

    $filename=$file["tmp_name"];
    //$image_size = getimagesize($file["tmp_name"]);
    $pinfo=pathinfo($file["name"]);
    $ftype=$pinfo['extension'];
    $destination = $destination_folder.date("YmdHis").".".$ftype;
    if (file_exists($destination) && $overwrite != true)
    {
    echo "<font color='red'>同名文件已经存在了！</a>";
    exit;
    }
    //dump($file);exit;
    //dump($destination);exit;
    //将其他形式的图片压缩并改为jpeg的类型
    $size=GetImageSize($file["tmp_name"]);
    //定义图片最大高度
    $heightImage=250;//调整图片大小只要增加高度就好了
    if($size[1]>$heightImage){
        $width=$heightImage/$size[1]*$size[0];
        $height=$heightImage;
    }else{
        $width=$size[0];
        $height=$size[1];
    }
    //echo($width);dump($size);exit;
    if($size[2]==1)
        $im_in=imagecreatefromgif($filename);
    if($size[2]==2)
        $im_in=imagecreatefromjpeg($filename);
    if($size[2]==3)
        $im_in=imagecreatefrompng($filename);
    if($size[2]==6)
        $im_in=imagecreatefrombmp($filename);
    $im_out=imagecreatetruecolor($width,$height);
    imagecopyresampled($im_out,$im_in,0,0,0,0,$width,$height,$size[0],$size[1]);

    if(!move_uploaded_file ($filename, $destination))
    {
        echo "<font color='red'>移动文件出错！</a>";
        exit;
    }

    $pinfo=pathinfo($destination);
    $fname=$pinfo['basename'];
    $upfile=$destination_folder.$fname;
    //移动时改变图片大小
    imagejpeg($im_out,$upfile);
    chmod($upfile,0777);
    return $upfile;
    }

    function upLoadGY($arrFile) {
    $file = $arrFile["imageFileGY"];
    $uptypes=array('image/jpg',  //上传文件类型列表
    'image/jpeg',
    'image/png',
    'audio/x-pn-realaudio-plugin',
    'image/gif',
    'image/bmp',
    'application/x-shockwave-flash',
    'image/x-png');
    $max_file_size=20000000;
    $destination_folder="Upload/SampleGY/"; //上传文件路径,必须属性为７７７否则出现移动文件出错的错误
    $watermark=2;   //是否附加水印(1为加水印,其他为不加水印);
    $watertype=1;   //水印类型(1为文字,2为图片)
    $waterposition=1;   //水印位置(1为左下角,2为右下角,3为左上角,4为右上角,5为居中);
    $waterstring="wtenglish.com"; //水印字符串
    $waterimg="xplore.gif";  //水印图片
    $imgpreview=2;   //是否生成预览图(1为生成,其他为不生成);
    $imgpreviewsize=1/1;  //缩略图比例

    if (!is_uploaded_file($file['tmp_name']))//是否存在文件
    {
    echo "<font color='red'>文件不存在！</font>";
    exit;
    }

    if($max_file_size < $file["size"])//检查文件大小
    {
    echo "<font color='red'>文件太大！</font>";
    exit;
    }
    if(!file_exists($destination_folder)) mkdir($destination_folder);

    $filename=$file["tmp_name"];
    //$image_size = getimagesize($file["tmp_name"]);
    $pinfo=pathinfo($file["name"]);
    $ftype=$pinfo['extension'];
    $destination = $destination_folder.date("YmdHis").".".$ftype;
    if (file_exists($destination) && $overwrite != true)
    {
    echo "<font color='red'>同名文件已经存在了！</a>";
    exit;
    }
    //dump($file);exit;
    //dump($destination);exit;
    //将其他形式的图片压缩并改为jpeg的类型
    $size=GetImageSize($file["tmp_name"]);
    //定义图片最大高度
    $heightImage=250;//调整图片大小只要增加高度就好了
    if($size[1]>$heightImage){
        $width=$heightImage/$size[1]*$size[0];
        $height=$heightImage;
    }else{
        $width=$size[0];
        $height=$size[1];
    }
    //echo($width);dump($size);exit;
    if($size[2]==1)
        $im_in=imagecreatefromgif($filename);
    if($size[2]==2)
        $im_in=imagecreatefromjpeg($filename);
    if($size[2]==3)
        $im_in=imagecreatefrompng($filename);
    if($size[2]==6)
        $im_in=imagecreatefrombmp($filename);
    $im_out=imagecreatetruecolor($width,$height);
    imagecopyresampled($im_out,$im_in,0,0,0,0,$width,$height,$size[0],$size[1]);

    if(!move_uploaded_file ($filename, $destination))
    {
        echo "<font color='red'>移动文件出错！</a>";
        exit;
    }

    $pinfo=pathinfo($destination);
    $fname=$pinfo['basename'];
    $upfile=$destination_folder.$fname;
    //移动时改变图片大小
    imagejpeg($im_out,$upfile);
    chmod($upfile,0777);
    return $upfile;
    }

    /**
    * @desc ：根据类别获得产品的最新编号
    * Time：2016/06/07 13:56:17
    * @author Wuyou
    */
    function actiongetNewCodeByAjax()
    {
      $data = array(
          'newCode' => $this->_getChanpinCode($_GET['kind']),
      );
      echo json_encode($data);exit;
    }

    /**
    * @desc ：复制档案功能，编号取最新编号，其他内容一致
    * Time：2016/06/27 16:55:21
    * @author Wuyou
    */
    function actionCopy()
    {
      $arr = $this->_modelExample->find($_GET['id']);
      $arr['proCode'] = $this->_getChanpinCode($arr['kind']);

      unset($arr['id']);
      foreach ($this->fldMain as $k => &$v)
      {
        $v['value'] = $arr[$k]?$arr[$k]:$v['value'];
      }

      foreach($arr['Products'] as &$v) {
        unset($v['id']);
        $temp = array();
        foreach($this->headSon as $kk => &$vv) {
          $temp[$kk] = array('value' => $v[$kk]);
        }
        $pro = $this->_modelExample->find(array('id' => $v['productId']));
        $temp['productId']=array('text' => $pro['proCode'],'value' => $pro['id']);
        $temp['proNameson']=array('value' => $pro['proName']);
        $temp['proGuige']=array('value' => $pro['guige']);
        $temp['sonId']=array('value' => $v['id']);
        $strChF = "select c.chengfenPer,cc.name from jichu_product p left join  jichu_product_chengfen c on p.id=c.proId left join jichu_chengfen cc on c.component=cc.id where p.id ='{$pro['id']}'";
        $rowsetChF=$this->_modelPro->findBySql($strChF);
        foreach($rowsetChF as $value) 
        {
            $pro['proChengFenPer'] .=$value['chengfenPer'] . ',';
            $pro['proComponent'] .=$value['name']. ','; 
        }
        $strChF=$pro['proChengFenPer'];
        $strCom=$pro['proComponent'];
        $temp['proChengFenPer']=array('value' => substr($strChF,0,strlen($strChF)-1));
        $temp['proComponent']=array('value' => substr($strCom,0,strlen($strCom)-1));
        $rowsSon[] = $temp;
        
      }
      //补齐5行
      $cnt = count($rowsSon);
      for($i=5;$i>$cnt;$i--) {
        $rowsSon[] = array();
      }
        /*
        * 工序明细处理
        */
        // $sqlgx="select * from jichu_product_gongxu where productId='{$arr['id']}' group by xuhao";
        // $arr['gxs']=$this->_modelExample->findBySql($sqlgx);
        // $gongxuSon=array();
        // foreach($arr['gxs'] as &$v) {
        //     $temp=array();
        //     foreach($this->headGongxu as $key => &$vv) {
        //         $temp[$key] = array('value' => $v[$key]);
        //     }
        //     $gongxuSon[] = $temp;
        // }
        foreach($arr['Gongxu'] as &$vx) {
        unset($vx['id']);
        $temp = array();
        foreach($this->headGongxu as $key => &$vv) {
          $temp[$key] = array('value' => $vx[$key]);
          }
          $gongxuSon[] = $temp;
        }
        //补齐5行
        $cnt = count($gongxuSon);
        // dump($cnt);exit();
        for($i=$cnt+1;$i<6;$i++) {
            $temp=array();
            $temp['xuhao']=array('value'=>$i);
            $gongxuSon[] = $temp;
        }
      // dump($this->fldMain);exit;
      $smarty = &$this->_getView();
      $smarty->assign('areaMain',array('title' => '布基本信息', 'fld' => $this->fldMain));
      $smarty->assign('headSon', $this->headSon);
      $smarty->assign('headGongxu', $this->headGongxu);
      $smarty->assign('rowsSon', $rowsSon);
      $smarty->assign('gongxuSon', $gongxuSon);
      $smarty->assign('rules', $this->rules);
      $smarty->assign("otherInfoTpl",'jichu/GongxuInfoTpl.tpl');
      $smarty->assign('sonTpl',"Jichu/proSonTpl.tpl");
      $smarty->assign('print', 'yes');
      $smarty->display('Main2Son/T1.tpl');
    }

    /**
    * @desc ：展会系统导入产品档案
    * Time：2016/03/18 14:16:10
    * @author Wuyou
    */
    function actionGetProDetail(){
    set_time_limit(0);
    $str = "SELECT proCode,proName,menfu,chengFen,kezhong,guige
            from jichu_product
            where state=1";
    $rows=$this->_modelExample->findBySql($str);
    echo json_encode($rows);
    exit;
    }
    

    /**
     * 获取布档案资料的编码
     *
     * @param $kind
     * @return string
     */
    protected function _getChanpinCodeKf($kind)
    {
        $ymd = date('ymd');
        $ym = date('ym');
        $date = $ym;

        //$date = ($_GET['kind']=='客户来样') ? $ymd : '';

        $head = 'JM-' . $this->arrKind[$kind] . $date;

        $sql = "SELECT proCode
              FROM jichu_product
              WHERE proCode like '{$head}%'
              ORDER BY proCode DESC";

        $temp = $this->_modelExample->findBySql($sql);

        $max = substr($temp[0]['proCode'],-4);

        $next = $head.substr(10001+(int)$max , -4);

        $code = $frist = $head.'0001';

        if($next>$frist)
        {
            $code = $next;
        }

        return $code;
    }


    /**
     * 获取布档案资料的编码
     *
     * @param $kind
     * @return string
     */
    protected function _getChanpinCode($kind)
    {
        $ymd = date('ymd');
        $ym = date('ym');
        if($_GET['kind']=='客户来样') {
           $date = $ymd;
        }elseif ($_GET['kind']=='本厂开发') {
           $date = $ym;
        }else{
          $date = '';
        }

        //$date = ($_GET['kind']=='客户来样') ? $ymd : '';

        $head = 'JM-' . $this->arrKind[$kind] . $date;

        $sql = "SELECT proCode
              FROM jichu_product
              WHERE proCode like '{$head}%'
              ORDER BY proCode DESC";

        $temp = $this->_modelExample->findBySql($sql);

        $max = substr($temp[0]['proCode'],-4);

        $next = $head.substr(10001+(int)$max , -4);

        $code = $frist = $head.'0001';

        if($next>$frist)
        {
            $code = $next;
        }

        return $code;
    }

    function actionRemove2GongxuAjax(){
        $gxModal  = & FLEA::getSingleton('Model_Jichu_ProductGx');
        $id=$_POST['id'];
        $r = $gxModal->removeByPkv($id);
        if(!$r) {
            echo json_encode(array('success'=>false,'msg'=>'删除失败'));
            exit;
        }
        echo json_encode(array('success'=>true));
      }
}
?>