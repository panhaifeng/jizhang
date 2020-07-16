<?php
FLEA::loadClass('TMIS_Controller');
class Controller_Sample_Yangpin extends Tmis_Controller{
	function Controller_Sample_Yangpin(){
	    $this->_modelExample=& FLEA::getSingleton('Model_Sample_Yangpin');
	    $this->_modelCaiyang=& FLEA::getSingleton('Model_Sample_Caiyang');
	}

	function actionRight(){
	    $this->authCheck('51-2');
	    $title='样品查询';
	    FLEA::loadClass('TMIS_Pager');
	    $arr = TMIS_Pager::getParamArray(array(
			'dateFrom'=>date('Y-m-d',mktime(0,0,0,date('m'),date('d')-30,date('Y'))),
			'dateTo'=>date('Y-m-d'),
			'proCode'=>'',
			'guige'=>'',
	    ));
	    $sql="select * from sample_db where isXiajia=1";
	    if($arr['dateFrom']!='')$sql.=" and rukuDate >='{$arr['dateFrom']}' ";
	    if($arr['dateTo']!='')  $sql.=" and rukuDate <='{$arr['dateTo']}' ";
	    if($arr['chengfen']!='')  $sql.=" and  chengfen like '%{$arr['chengfen']}%' ";
	    if($arr['guige']!='')  $sql.=" and  guige like '%{$arr['guige']}%' ";
	    if($arr['proCode']!='')  $sql.=" and  proCode like '%{$arr['proCode']}%' ";
	    $sql.=" order by id desc";
	    $pager=& new TMIS_Pager($sql);
	    $rowset=$pager->findAll();
	    foreach($rowset as & $v){
				// $str="select sum(chukuCnt) as cnt FROM sample_caiyang WHERE  sampleId ='{$v['id']}' ";
				// $rr=mysql_fetch_assoc(mysql_query($str));
				// $v['kucunCnt']=$v['kucunCnt']-$rr['cnt']+0;

				$v['jingwei']="<strong>".$v['jingwei']."</strong>";

				if($v['imageFile']!='') $v['imageFile'] = "<a href='".$this->_url('showImage',array(
						'barCode'=>$v['barCode'],
						'img'=>$v['imageFile']!=''?$v['imageFile']:'',
						 'TB_iframe'=>1
					    ))."'  class='thickbox' title='查看条码及图片'><img src='Resource/Image/img.gif' style='border:0px'></a>";
				$v['_edit']=$this->getEditHtml(array(
									'id'=>$v['id'],
									'fromAction'=>$_GET['action']
				     ))."&nbsp;&nbsp;".$this->getRemoveHtml(array(
									 'id'=>$v['id'],
									'fromAction'=>$_GET['action']
		    ));
			$v['cntJian'] = $v['cntJian']>0?$v['cntJian']:'';
			$v['cntM'] = $v['cntM']>0?$v['cntM']:'';
	    }
	    $rowset[] = $this->getHeji($rowset,array('kucunCnt','cntJian','cntM'),'_edit');
	    //dump($rowset);exit;
	    $arr_field_info=array(
				'_edit'  => '操作',
				'proCode'=>'产品编号',
				'guige'=>'规格',
				'color'=>'颜色',
				'chengfen'=>'成分',
				'menfu'=>'门幅',
				'kezhong'=>'克重',
				'cntJian'=>'件数',
				'cntM'=>'米数',
				'ganghao'=>'缸号',
				'imageFile'=>'图片文件',
				'kucunCnt'=>'入库数(KG)',
				'kucunWei'=>'库存位置',
				'memo'=>'备注',
				'creater'=>'创建人',
				'rukuDate'=>'上架时间'
	    );

	    //dump($rowset);exit;
	    $smarty = & $this->_getView();
	    $smarty->assign('arr_field_value',$rowset);
	    $smarty->assign('arr_field_info',$arr_field_info);
	    $smarty->assign('title',$title);
	    $smarty->assign('arr_condition', $arr);
	    $smarty->assign('arr_js_css',$this->makeArrayJsCss(array('calendar')));
	    $smarty->assign('page_info',$pager->getNavBar($this->_url($_GET['action'],$arr)));
	    $smarty->display('TableList.tpl');
	}

	function actionAdd(){
	   $this->_edit($_GET);
	}


	function actionRemove(){
		//$this->authCheck('51-1');
		if($_GET['id']!="") {
		    $sql="select count(*) as cnt from sample_caiyang where sampleId={$_GET['id']}";
		    $row = $this->_modelExample->findBySql($sql);
		    if($row[0]['cnt']>0) {
				js_alert('此样品已被采样，禁止删除！',null,$this->_url('right'));
			}
		}
		$from = $_GET['fromAction']?$_GET['fromAction']:$_GET['from'];
		$re = $this->_modelExample->find(array('id'=>$_GET['id']));
		if ($this->_modelExample->removeByPkv($_GET['id'])) {
		    //同时删除该记录上传的图片
		    unlink($re['imageFile']);
		    if($from=='') redirect($this->_url("right"));
		    else js_alert(null,"window.parent.showMsg('成功删除')",$this->_url($from));
		}
		else js_alert('出错，不允许删除!','',$this->_url($from));
	}

	function _edit($arr){
		//dump($arr);exit;
		$this->authCheck('51-1');
	/*	if(count($this->getHzlArr($arr))>0)
		    $arr['hzl']=$this->getHzlArr($arr);	*/
		$smarty = & $this->_getView();
		$smarty->assign('aRow',$arr);
		$smarty->display('Sample/SampleEdit.tpl');
	}

	function actionSave(){
	  //判断厂编有效性
	  if($_POST['proCode']!=''){
			$str2="select * from jichu_product where proCode = '{$_POST['proCode']}'";
			$rowCode=mysql_fetch_assoc(mysql_query($str2));
			//dump($rowCode);exit;
			if($rowCode['proCode']==''){
			    js_alert('该产品编码不存在!',null,$this->_url('add',$_POST));
			}
    }

	    if($_POST['rukuDate']=='0000-00-00')
			$_POST['rukuDate']=date('Y-m-d');
	    //处理图片
	    //dump($_FILES);exit;
	     //如果编辑界面选择了删除图片的复选框，就移除原有图片
	    if($_POST['isDelImage']=='yes'){
		    $re = $this->_modelExample->find(array('id'=>$_POST['id']));
		    $str="update sample_db set imageFile='' where id = ".$_POST['id'];
		    $res=mysql_query($str);
		    if($res > 0){
					unlink($re['imageFile']);
		    }
	    }
	    if($_FILES['imageFile']['name']!=''){
		    $imgPath = $this->upLoad($_FILES);
		    $_POST['imageFile'] = $imgPath;
	    }
			//图片处理结束

	   $_POST['creater']=$_SESSION['REALNAME'];
	   $_POST['proCode']=trim($_POST['proCode']);
	    //dump($_POST);exit;
	    $id = $this->_modelExample->save($_POST);
	    if($_POST['id']!='')$newId=$_POST['id'];
	    else $newId=mysql_insert_id();
	    //dump($id);exit;
	    if($id>0){
			 if($_POST['submit']=='保存并打印条码'){
			     js_alert(null,"window.parent.showMsg('保存成功!')",$this->_url('print',array('id'=>$newId)));
			 }else{
			    // dump($_POST);exit;
			     $_POST['id']='';
			     js_alert(null,"window.parent.showMsg('保存成功!')",$this->_url('add'));
			 }
     	}else{
     		js_alert(null,"window.parent.showMsg('保存失败!')",$this->_url('add',$_POST));
     	}


	}

	function actionPrint(){
	    $arr=$this->_modelExample->find($_GET['id']);
	    $smarty = & $this->_getView();
	   // $smarty->assign('codetitle','样品条码信息');
	    $smarty->assign('codewidth',$arr['barCode']);
	    $smarty->assign('row',$arr);
	    // dump($arr);exit;
	    //exit;
	    $smarty->display('sample/Print.tpl');

	}

    function actionXiajia(){
       $this->authCheck('51-3');
        $title='样品查询';
        FLEA::loadClass('TMIS_Pager');
        $arr = TMIS_Pager::getParamArray(array(
            'isXiajia'=>'',
            'proCode'=>'',
            'guige'=>'',
        ));
        $sql="select * from sample_db where 1";
        if($arr['isXiajia']!='')$sql.=" and  isXiajia = '{$arr['isXiajia']}' ";
        if($arr['proCode']!='')  $sql.=" and  proCode like '%{$arr['proCode']}%' ";
        if($arr['guige']!='')  $sql.=" and  guige like '%{$arr['guige']}%' ";
        $sql.=" order by id desc";
        $pager=& new TMIS_Pager($sql);
        $rowset=$pager->findAll();
        //计算库存数
        foreach($rowset as & $v){
            $str="select sum(chukuCnt) as cnt FROM sample_caiyang WHERE  sampleId ='{$v['id']}' ";
            $rr=mysql_fetch_assoc(mysql_query($str));
            $v['kucunCnt']=$v['kucunCnt']-$rr['cnt']+0;

            $v['jingwei']="<strong>".$v['jingwei']."</strong>";

            if($v['imageFile']!='') $v['imageFile'] = "<a href='".$this->_url('showImage',array(
                'barCode'=>$v['barCode'],
                'img'=>$v['imageFile']!=''?$v['imageFile']:'',
                 'TB_iframe'=>1
                ))."'  class='thickbox' title='查看条码及图片'><img src='Resource/Image/img.gif' style='border:0px'></a>";

            if($v['isXiajia']==1){
                $isXiajia="<font color='blue'>下架</font>";
            }
            else{
                //$v['_bgColor']='lightblue';
                $isXiajia="<font color='green'>上架</font>";
            }

            $v['_edit'] = "<a href='javascript:void(0);' class='setXiajia' title='点击进行操作' data-id='{$v['id']}'>{$isXiajia}</a>";

            $v['cntJian'] = $v['cntJian']>0?$v['cntJian']:'';
            $v['cntM'] = $v['cntM']>0?$v['cntM']:'';
        }
        $rowset[] = $this->getHeji($rowset,array('kucunCnt','cntJian','cntM'),'_edit');
        $arr_field_info=array(
                '_edit'  => '操作',
                'proCode'=>'产品编号',
                'guige'=>'规格',
                'color'=>'颜色',
                'jingwei'=>'经纬密',
                'chengfen'=>'成分',
                'menfu'=>'门幅',
                'kezhong'=>'克重',
                'cntJian'=>'件数',
                'cntM'=>'米数',
                'ganghao'=>'缸号',
                'imageFile'=>'图片文件',
                'kucunCnt'=>'库存数(KG)',
                'kucunWei'=>'库存位置',
                'memo'=>'备注',
                'creater'=>'创建人',
                'rukuDate'=>'上架时间'
        );
        //dump($rowset);exit;
        $smarty = & $this->_getView();
        $smarty->assign('arr_field_value',$rowset);
        $smarty->assign('arr_field_info',$arr_field_info);
        $smarty->assign('title',$title);
        $smarty->assign('arr_condition', $arr);
            $smarty->assign('sonTpl', 'Sample/XiajiaEdit.tpl');
        $smarty->assign('add_display','none');
        $smarty->assign('arr_js_css',$this->makeArrayJsCss(array('calendar')));
        $smarty->assign('page_info',$pager->getNavBar($this->_url($_GET['action'],$arr)));
        $smarty->display('TblList.tpl');
    }

    function actionSetXiajia(){
        $id = $_POST['id'];
        $isXiajia = $_POST['isXiajia'];

        if($id !=''){

            $sql = "update sample_db set isXiajia={$isXiajia} where id={$id}";
            $id = mysql_query($sql);

            if($id>0){
                 echo json_encode(array(
                'success'=>true,
                ));
                exit;
            }
        }

        echo json_encode(array(
            'success'=>false,
        ));
        exit;
    }

	//处理sample_db数据中的hzl字段，转为数组形式传递给模板
	function &getHzlArr($arr){
	    if(count($arr)==0)return null;
	    $arr2=explode(',',$arr['hzl']);
	    if(count($arr2)==0) return null;
	    return $arr2;
	}

	//获取编码
	function actionGetBarCode(){
	    //通过itemName查找对应的itemCode
	    $m=& FLEA::getSingleton('Model_jichu_Sample');
	    $str1="select itemCode from jichu_sample where kind=2 and itemName='{$_GET['gexing']}'";
	    $str2="select itemCode from jichu_sample where kind=0 and itemName='{$_GET['chengfen']}'";
	    $str3="select itemCode from jichu_sample where kind=3 and itemName='{$_GET['zuzhi']}'";
	    $hzl='';
	    if(count($_GET['hzl'])>0)
	    foreach($_GET['hzl'] as $k=>$v){
		$str4="select itemCode from jichu_sample where kind=1 and itemName='{$v}'";
		$hzls=$m->findBySql($str4);
		if($hzls[0]['itemCode']!='')
		    $hzl.=$hzls[0]['itemCode'];
	    }
	    if($hzl!='')$hzl.=".";
	    $gexing=$m->findBySql($str1);
	    $chengfen=$m->findBySql($str2);
	    $zuzhi=$m->findBySql($str3);
	    //拼接条码
	    $code=$gexing[0]['itemCode'].".".$chengfen[0]['itemCode'].".".$_GET['jingwei'].".".$zuzhi[0]['itemCode'].".". $_GET['isTanli'].".".$hzl;
	    //如果条码值前面部分不变，则barCode不变，否则重新生成。
	    if($_GET['id']!=''){
		$str="select barCode from sample_db where id={$_GET['id']} ";
		$arr=$this->_modelExample->findBySql($str);
		 if(substr($arr[0]['barCode'],0,-5)==$code){
		      echo json_encode(array(
			'success'=>true,
			'code'=>$arr[0]['barCode']
			));
			exit;
		}
	    }
	    //判断是否有除序号以外其他字符相同的条码，如果有，在后面的数字上+1
	    $lengthcode=strlen($code)+5;
	    $sql="select * from sample_db where barCode like '{$code}%' and length(barCode)={$lengthcode} order by barCode desc";
	    $row=$this->_modelExample->findBySql($sql);
	    $no=1;
	    if(count($row)>0){
		 if(substr($row[0]['barCode'],0,-5)==$code)
		    $no=substr($row[0]['barCode'],strlen($row[0]['barCode'])-5,5)+1;
//		foreach($row as $value){
//		    if(substr($value['barCode'],0,-5)==$code){
//			$no=substr($value['barCode'],strlen($value['barCode'])-5,5)+1;
//			break;
//		    }
//		    else{
//			break;
//		    }
//		}
	    }
	    //格式化成5位并拼接成条码
	    $code.=sprintf('%05d',$no);
	    echo json_encode(array(
			'success'=>true,
			'code'=>$code
			));
			exit;
	}

	 //根据厂编得到相关信息
  function actionGetProInfoByAjax(){
      $str="SELECT * FROM jichu_product WHERE proCode='{$_GET['proCode']}'";
      $row=$this->_modelExample->findBySql($str);
      // dump($row);exit;
      if(!$row[0]) {
          echo json_encode(array(
          'success'=>false,
          'msg'=>'未发现相关记录'
          ));
          exit;
      }
      echo json_encode($row[0]);
      exit;
  }

	//显示图片大图
	function actionShowImage(){
	    $arr=$_GET;
	    $smarty = & $this->_getView();
	    $smarty->assign('title','条码大图');
	    $smarty->assign('row',$arr);
	    $smarty->display('sample/showImage.tpl');
	}

	//上传图片处理函数
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
		if($size[1]>250){
		    $width=250/$size[1]*$size[0];
		    $height=250;
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

	//样品间收发存报表
	function actionReport(){
	    $this->authCheck('51-12');
	    $title='收发存报表';
	    FLEA::loadClass('TMIS_Pager');
	    $arr = TMIS_Pager::getParamArray(array(
			'dateFrom'=>date('Y-m-d',mktime(0,0,0,date('m'),date('d')-30,date('Y'))),
			'dateTo'=>date('Y-m-d'),
			'proCode'=>''
	    ));
	    //期初
	    $sql="SELECT x.*,(sum(x.kucunCnt)-ifnull(y.cntLlck,0)) as cnt,
	    			(sum(x.cntM)-ifnull(y.cntMLlck,0)) as initCntM
	    			from sample_db x
			left join (select proCode,ganghao,kucunWei,sum(chukuCnt) as cntLlck,sum(cntM) as cntMLlck from sample_caiyang
			where chukuDate <'{$arr['dateFrom']}' group by proCode,ganghao,kucunWei) as y
			on x.proCode=y.proCode and x.ganghao=y.ganghao and x.kucunWei=y.kucunWei
			where 1 ";
		// where 1后面原来有and x.kucunCnt<>ifnull(y.cntLlck,0)。不知道干嘛的，去掉
	    if($arr['dateFrom']!='')$sql.=" and x.rukuDate < '{$arr['dateFrom']}'";
	    if($arr['proCode']!='')$sql.=" and x.proCode like '%{$arr['proCode']}%'";
	    $sql.="and x.proCode!='' GROUP BY x.proCode,x.ganghao,x.kucunWei order by x.id desc";
	    $rowset=$this->_modelExample->findBysql($sql);
	    // dump($sql);exit;
	    $ret=array();
	    if($rowset){
			foreach($rowset as & $v){
				$key=$v['proCode'].','.$v['kucunWei'].','.$v['ganghao'];
			    $ret[$key]['proCode']=$v['proCode'];
			    $ret[$key]['kucunWei']=$v['kucunWei'];
			    $ret[$key]['ganghao']=$v['ganghao'];
			    $ret[$key]['cnt']=$v['cnt'];
			    $ret[$key]['initCntM']=$v['initCntM'];
			}
	    }
	    // dump($ret);exit;
	    //本期入库
	    $strBen="select *,sum(kucunCnt) as rkCnt,sum(cntM) as rkCntM from sample_db where 1";
	    if($arr['dateFrom']!='')$strBen.=" and rukuDate >= '{$arr['dateFrom']}' and rukuDate <= '{$arr['dateTo']}' ";
	    if($arr['proCode']!='')$strBen.=" and proCode like '%{$arr['proCode']}%'";

	    $strBen.=" group by proCode,kucunWei,ganghao order by id desc";
	    $rowBen=$this->_modelExample->findBysql($strBen);
	    if($rowBen){
				foreach($rowBen as & $v1){
						$key=$v1['proCode'].','.$v1['kucunWei'].','.$v1['ganghao'];
				    $ret[$key]['proCode']=$v1['proCode'];
				    $ret[$key]['kucunWei']=$v1['kucunWei'];
				    $ret[$key]['ganghao']=$v1['ganghao'];
				    $ret[$key]['kucunCnt']=$v1['rkCnt'];
				    $ret[$key]['rkCntM']=$v1['rkCntM'];
				}
	    }
	    //dump($ret);exit;
	    //本期出库
	    $strBenChu="select sum(chukuCnt) as chuCnt,sum(cntM) as ckCntM,x.* from sample_caiyang x
		    where 1";
	    if($arr['dateFrom']!='')$strBenChu.=" and chukuDate >= '{$arr['dateFrom']}' and chukuDate <= '{$arr['dateTo']}' ";
	    if($arr['proCode']!='')$strBenChu.=" and x.proCode like '%{$arr['proCode']}%'";
	    $strBenChu.=" group by x.proCode,x.kucunWei,x.ganghao order by x.id desc";
	    //dump($strBenChu);exit;
	    $strBenChu=$this->_modelExample->findBysql($strBenChu);
	    //dump($strBenChu);exit;
	    if($strBenChu){
				foreach($strBenChu as & $v){
						$key=$v['proCode'].','.$v['kucunWei'].','.$v['ganghao'];
				    $ret[$key]['proCode']=$v['proCode'];
				    $ret[$key]['kucunWei']=$v['kucunWei'];
				    $ret[$key]['ganghao']=$v['ganghao'];
				    $ret[$key]['chuCnt']=$v['chuCnt'];
				    $ret[$key]['ckCntM']=$v['ckCntM'];
				}
	    }

	    $ret=array_column_sort($ret,'proCode',SORT_ASC);
	    //dump($ret);exit;
	    $row=$ret;
	    foreach($row as $key =>& $v){
	    	//查找产品信息
	    	$sql="select * from jichu_product where proCode = '{$v['proCode']}'";
	    	$res = $this->_modelExample->findBySql($sql);
	    	$v['guige'] = $res[0]['guige'];

				$v['kucun']=$v['cnt']+$v['kucunCnt']-$v['chuCnt']+0;
				$v['kcCntM']=$v['initCntM']+$v['rkCntM']-$v['ckCntM']+0;
				//数据整理
				$v['kucunCnt']="<a href='".$this->_url('SelYangpin',array(
								    'proCode'=>$v['proCode'],
								    'kucunWei'=>$v['kucunWei'],
								    'ganghao'=>$v['ganghao'],
								    'dateFrom'=>$arr['dateFrom'],
								    'dateTo'=>$arr['dateTo'],
								    'width'=>'750',
								    'no_edit'=>1,
								    'TB_iframe'=>1
								))."'
					title='样品登记信息' class='thickbox'>
					<span style='color:blue'>".$v['kucunCnt']."</span></href>";
				if($v['chuCnt']>0){
				    $v['chuCnt']="<a href='".url('Sample_Caiyang','Selcaiyang',array(
					    'proCode'=>$v['proCode'],
					    'kucunWei'=>$v['kucunWei'],
					    'ganghao'=>$v['ganghao'],
					    'dateFrom'=>$arr['dateFrom'],
					    'dateTo'=>$arr['dateTo'],
					    'width'=>'750',
					    'no_edit'=>1,
					    'TB_iframe'=>1
					))."'
					    title='采样信息' class='thickbox'>
					    <span style='color:blue'>".$v['chuCnt']."</span></href>";
				}
				$v['_edit'] = "<a href = '".$this->_url('tiaoku',array(
					    'proCode'=>$v['proCode'],
					    'kucunWei'=>$v['kucunWei'],
					    'kucun'=>$v['kucun'],
					    'ganghao'=>$v['ganghao'],
					    'no_edit'=>1,
					    'TB_iframe'=>1
					))."'>调库</a>";
				$v['_edit'] .= " | <a href = '".$this->_url('yiKu',array(
					    'proCode'=>$v['proCode'],
					    'kucunWei'=>$v['kucunWei'],
					    'kucun'=>$v['kucun'],
					    'ganghao'=>$v['ganghao'],
					    'no_edit'=>1,
					    'TB_iframe'=>1
					))."'>移库</a>";
				if($v['kucun'] == 0){
					unset($row[$key]);
				}
	    }
	    // dump($rowset);exit;
	    $arr_field_info=array(
	    		'_edit'=>'操作',
				'proCode'=>'产品编号',
				'guige'=>'规格',
				'ganghao'=>'缸号',
				'kucunWei'=>'库存位置',
				'cnt'=>'期初数量(KG)',
				'initCntM'=>'期初米数',
				'kucunCnt'=>'入库数量(KG)',
				'rkCntM'=>'入库米数',
				'chuCnt'=>'出库数量(KG)',
				'ckCntM'=>'出库米数',
				'kucun'=>'库存数量(KG)',
				'kcCntM'=>'库存米数',
				//'kucunWei'=>'库存位置',
	    );
	    // dump($row);exit;
	    //处理分页信息    导出不需要分页
	    if($_GET['export']!=1)
	    	$rowset = $this->arrayPage($this->_url($_GET['action'],$arr),$row,$_GET['page']+0);
	    $smarty = & $this->_getView();
	    $smarty->assign('arr_field_value',$rowset['row']);
	    $smarty->assign('arr_field_info',$arr_field_info);
	    $smarty->assign('add_display','none');
	    $smarty->assign('title',$title);
	    $smarty->assign('arr_condition', $arr);
	    $smarty->assign('fn_export',$this->_url($_GET['action'],$arr+array('export'=>1)));
	     if($_GET['export']==1) {
            FLEA::loadClass('TMIS_Common');
            TMIS_Common::exportByHtml($row,$arr_field_info,array(
                'big'=>'样品间收发存报表',
            ));
            exit;
        }
	   	//处理页数
	    $smarty->assign('page_info',$rowset['select']);
	    $smarty->display('TableList.tpl');
	}

	/**
	 * @desc ：调库
	 * Time：2016/05/31 13:46:54
	 * @author xubiao
	*/
	function actionTiaoku(){
		//放的是样品登记权限
		$this->authCheck('51-1');
		// dump($_GET);
		$str="SELECT * FROM jichu_product WHERE proCode='{$_GET['proCode']}'";
		$row=$this->_modelExample->findBySql($str);
		$arr = $row[0];
		$arr['kucunWei'] = $_GET['kucunWei'];
		$arr['kucun'] = $_GET['kucun'];
		$arr['ganghao'] = $_GET['ganghao'];
		// dump($arr);exit;
		$smarty = & $this->_getView();
	    $smarty->assign('aRow',$arr);
	    $smarty->display('Sample/tiaokuEdit.tpl');

	}

	/**
	 * @desc ：移库
	 * Time：2016/05/31 14:05:39
	 * @author xubiao
	*/
	function actionYiku(){
		//放的是样品登记权限
		$this->authCheck('51-1');
		// dump($_GET);exit;
		$str="SELECT * FROM jichu_product WHERE proCode='{$_GET['proCode']}'";
		$row=$this->_modelExample->findBySql($str);
		$arr = $row[0];
		$arr['kucunWei'] = $_GET['kucunWei'];
		$arr['kucun'] = $_GET['kucun'];
		$arr['ganghao'] = $_GET['ganghao'];
		// dump($arr);exit;
		$smarty = & $this->_getView();
	    $smarty->assign('aRow',$arr);
	    $smarty->display('Sample/YikuEdit.tpl');
	}

	/**
	 * @desc ：调库保存方法
	 * Time：2016/06/01 15:37:27
	 * @author xubiao
	*/
	function actionSaveTiaoku(){
		// dump($_POST);exit;
		if($_POST['kucun']>$_POST['kucunCnt']){
			//如果调整的数量少于库存量，出库操作
			$cnt = $_POST['kucun']-$_POST['kucunCnt'];
			$_POST['chukuCnt'] = $cnt;
			$_POST['creater']=$_SESSION['REALNAME'];
			$_POST['proCode']=trim($_POST['proCode']);
			$_POST['chukuDate']=$_POST['Date'];
			$_POST['clientName']='调库';
			$_POST['memo']='调库出库';
			$_POST['danjia'] = 0;
	    	$_POST['money'] = 0;
	    	$_POST['yunfei'] = 0;
	    	//采样人编号设置000
	    	$_POST['caiyangren'] = '000';
	    	$str="select *,sum(kucunCnt) as kucun from sample_db where proCode='{$_POST['proCode']}'";
	  		$row=mysql_fetch_assoc(mysql_query($str));
	  		$_POST['sampleId'] = $row['id'];
	  		// dump($row);exit;
			$this->_modelCaiyang->save($_POST);
			js_alert(null,"window.parent.showMsg('保存成功!')",$this->_url('report'));exit;
		}elseif($_POST['kucun']<$_POST['kucunCnt']){
			//如果调整的数量多于库存量，入库操作
			$cnt = $_POST['kucunCnt']-$_POST['kucun'];
			$_POST['kucunCnt'] = $cnt;
			$_POST['creater']=$_SESSION['REALNAME'];
	   		$_POST['proCode']=trim($_POST['proCode']);
	   		$_POST['rukuDate']=$_POST['Date'];
	   		$_POST['memo']='调库入库';
			// dump($_POST);
			$this->_modelExample->save($_POST);
			js_alert(null,"window.parent.showMsg('保存成功!')",$this->_url('report'));exit;
		}else{
			//给个提醒没有改变，也不保存
			js_alert(null,"window.parent.showMsg('库存未调整!')",$this->_url('report'));exit;
		}

	}

	function actionSaveYiku(){
		// dump($_POST);exit;
		//移库的入库到新库部分
		$ruku = $_POST;
		$ruku['creater']=$_SESSION['REALNAME'];
   		$ruku['rukuDate']=$ruku['Date'];
   		$ruku['memo']='移库入库';
		// dump($ruku);
		$this->_modelExample->save($ruku);
		//移库旧库的出库部分
		$chuku = $_POST;
		$chuku['chukuCnt'] = $chuku['kucunCnt'];
		$chuku['creater']=$_SESSION['REALNAME'];
		$chuku['chukuDate']=$chuku['Date'];
		$chuku['clientName']='移库';
		$chuku['memo']='移库出库';
		$chuku['danjia'] = 0;
    	$chuku['money'] = 0;
    	$chuku['yunfei'] = 0;
    	$chuku['kucunWei'] = $chuku['yuankucunWei'];
    	//采样人编号设置000
    	$chuku['caiyangren'] = '000';
    	$str="select *,sum(kucunCnt) as kucun from sample_db where proCode='{$chuku['proCode']}'";
  		$row=mysql_fetch_assoc(mysql_query($str));
  		$chuku['sampleId'] = $row['id'];
  		// dump($chuku);exit;
		$this->_modelCaiyang->save($chuku);
		js_alert(null,"window.parent.showMsg('移库成功!')",$this->_url('report'));exit;
	}

	/**
	 * 处理数组，给数组分页
	 * Time：2015/06/08 14:15:06
	 * $count 总共的数量
	 * $_page 第几页
	 * $evePage 每页数量
	 * @author li
	*/
	function arrayPage($mpurl,$row,$_page=1,$evePage=50){
		//需要的页数
		$count = count($row);
		// dump($row);exit;
		$_page==0 && $_page=1;
		$_flPage = ceil($count/$evePage);
		$_flPage == 0 && $_flPage=1;

		for ($i=1; $i <= $_flPage; $i++) {
			$pg[] = "<option value='{$i}' ".($i==$_page?'selected':'').">{$i}</option>";
		}
		$_pg = join('',$pg);
		// dump($mpurl);exit;
		$select = "共{$count}，{$_flPage} / <select class='p_pager_list'onchange=\"window.location='".$mpurl."&page='+(this.value); return false;\">{$_pg}</select> ";

		//处理数组信息
		// dump(($_page-1)*$evePage+1);
		// dump($evePage);
		// exit;
		$rowset = array_slice($row, ($_page-1)*$evePage,$evePage);
		// echo count($rowset);
		return array('select'=>$select,'row'=>$rowset);
	}

	//查看报表样品登记信息
	function actionSelYangpin(){
	    //dump($_GET);exit;
	    $title='样品登记信息';
	    $sql="select * from sample_db  where 1 and proCode='{$_GET['proCode']}' and kucunWei='{$_GET['kucunWei']}' and ganghao='{$_GET['ganghao']}'   and rukuDate >= '{$_GET['dateFrom']}' and rukuDate <= '{$_GET['dateTo']}'
		 order by id desc";
	    $rowset=$this->_modelExample->findBysql($sql);
	    $arr_field_info=array(
				'proCode'=>'产品编号',
				'kucunWei'=>'库存位置',
				'ganghao'=>'缸号',
				'kucunCnt'=>'入库数量',
				'kucunWei'=>'库存位置',
				'memo'=>'备注',
				'creater'=>'创建人',
				'rukuDate'=>'上架时间'
	    );
	    foreach($rowset as & $v){
				$v['jingwei']="<strong>".$v['jingwei']."</strong>";
				$v['shazhi']="<strong>".$v['shazhi']."</strong>";
				if($v['isTanli']==1)$v['isTanli']="是";else $v['isTanli']="否";
	    }
	    $heji=$this->getHeji($rowset,array('kucunCnt'),'proCode');
	    $rowset[]=$heji;
	    $smarty = & $this->_getView();
	    $smarty->assign('arr_field_value',$rowset);
	    $smarty->assign('arr_field_info',$arr_field_info);
	    $smarty->assign('no_edit','1');
	    $smarty->assign('title',$title);
	    $smarty->assign('arr_js_css',$this->makeArrayJsCss(array('calendar')));
	    $smarty->display('TableList.tpl');
	}


	/**
	 * 自动控件，缸号
	 * Time：2015/06/03 16:29:44
	 * @author li
	*/
	function actionautocompleteGh(){
		$sql="select ganghao from sample_db where proCode = '{$_GET['proCode']}' group by ganghao order by length(ganghao),ganghao";
		$res = $this->_modelExample->findBySql($sql);


		$ganghao = array_col_values($res,'ganghao');

		foreach ($ganghao as $key =>&$v) {
			if($v)$_ganghao[] = $v;
		}

		if(!$_ganghao)$_ganghao=array('无');
		echo json_encode($_ganghao);
	}

	/**
	 * 自动控件，库存位置
	 * Time：2015/06/03 16:29:44
	 * @author li
	*/
	function actionautocompleteKw(){
		$sql="select kucunWei from sample_db where proCode = '{$_GET['proCode']}' group by kucunWei order by length(kucunWei),kucunWei";
		$res = $this->_modelExample->findBySql($sql);

		$kucunWei = array_col_values($res,'kucunWei');
		foreach ($kucunWei as $key =>&$v) {
			if($v)$_kucunWei[] = $v;
		}
		// dump($kucunWei);
		if(!$_kucunWei)$_kucunWei=array('无');
		echo json_encode($_kucunWei);
	}

}
?>
