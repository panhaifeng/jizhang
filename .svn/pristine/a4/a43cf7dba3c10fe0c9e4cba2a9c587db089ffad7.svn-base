<?php
FLEA::loadClass('Tmis_Controller');
class Controller_Cangku_Chengpin_Madan extends Tmis_Controller {
	// /构造函数
	function __construct() {
		$this->_modelExample = & FLEA::getSingleton('Model_Cangku_Chengpin_Madan');
	}

	/**
	 * 码单导出
	*/
	function actionExportChuku(){
		// dump($_POST);exit;
		//$tpl = 'Cangku/Chengpin/ExportMadan.tpl';
		FLEA::loadClass('TMIS_Pager');
		$name=FLEA::getAppInf('compName');
		// dump($rowset);exit;
		//整理码单信息，每200个码单一页，40个一列，4列一行
		//导出时固定格式的参数
		$exportInfo=array();
		$eveCol=20;//一页40列，
		$eveRow=3;
		$evePage=$eveCol*$eveRow;
		$exportInfo=array(
			'page'=>$evePage,
			'col'=>$eveCol,
			'row'=>$eveRow,
		);

		// 取得需要导出的出库明细信息
		$str="select x.id,
					x.pihao,
					y.chukuDate,
					a.orderCode,
					b.menfu,
					b.kezhong,
					p.proName,
					p.guige,
					p.color,
					x.cntJian,
					x.cnt,
					x.cntM,
					x.cntOrg,
					e.compName 
			from cangku_common_chuku2product x
			left join cangku_common_chuku y on x.chukuId=y.id 
			left join trade_order a on y.orderId=a.id
			left join trade_order2product b on x.ord2proId=b.id
			left join jichu_client e on y.clientId=e.id
			left join jichu_product p on p.id=x.productId
			where 1";
		if($_POST['orderId']!='') $str.=" and y.orderId='{$_POST['orderId']}'";//订单id
		if($_POST['id']!='') $str.=" and y.id='{$_POST['id']}'";
		$str.=" and exists(select id from cangku_madan c where c.chuku2proId=x.id)";
		// dump($str);exit;
		$rowset = $this->_modelExample->findBySql($str);
		// dump($rowset);exit;
		//查找出库明细具体的码单信息
		foreach ($rowset as $k => & $row) {
			$cntKg=0;
			$cntM=0;
			$cntMadan=0;
			$bang=0;
			$arr=array();
			//查找码单信息
			$sql="select * from cangku_madan where chuku2proId='{$row['id']}'";
			$row['Son']=$this->_modelExample->findBySql($sql);
			// dump($row['Son']);exit;
			//处理码单信息
			$temp_number=array();
			foreach($row['Son'] as & $v) {
				//dump($v);die;
				$v['bang']=round(($v['cnt']*2.2046226),2);
				#取得最大值，确定所需的表格数及求得发运数
				$cntKg+=$v['cnt'];
				$cntM+=$v['cntM'];
				$cntMadan+=$v['cntMadan'];
                $bang+=$v['bang'];
				// $v['num']=$v['number'].'#';
				//取得值类型,判断是为String还是Number
				$v['type']='Number';
			/* 	if(is_numeric($v['cnt_format'])) {
					$v['type'] = 'Number';
				} */
				// $temp_number[]=$v['number'];
			}
			$row['cnt_Kg']=round($cntKg,1);
			$row['cnt_M']=round($cntM,1);
			$row['cntMadan']=round($cntMadan,1);
			$row['bang']=round($bang,1);
			$row['cnt_Jian']=count($row['Son']);
			/**
			* 处理码单之间的件数不间断
			*/
			/*$son_arr=array();
			foreach($row['Son'] as & $v){
				$son_arr[$v['number']]=$v;
			}
			// dump($son_arr);exit;
			$min=min($temp_number);$max=max($temp_number);
			$min=floor($min/($eveCol*$eveRow))*$eveCol*$eveRow+1;
			// echo $min;exit;
			for($i=$min;$i<=$max;$i++) {
				if(!isset($son_arr[$i]))$son_arr[$i]=array('number'=>$i);
			}
			// dump($son_arr);exit;
			$row['Son']=$son_arr;*/
		}
        // dump($rowset);exit;
		//计算小计
		$xiaoji=array();
		$totalpages=array();
		foreach($rowset as $key => & $v){
			$son=array_column_sort($v['Son'],'number',SORT_ASC);
			// ksort($v['Son']);
			// $son=$v['Son'];
			// dump($son);exit;
			//计算需要的页数，并重新组织数据
			$page=ceil(count($son)/$evePage);
			$totalpages[]=$page;
			$newSon=array();//每200条数据放在一个数组里
			for($i=0;$i<$page;$i++){
			    $newSon[]=array_slice($son,$evePage*$i,$evePage);
			}
			// dump($newSon);exit;
			//对每页处理,处理成每页中 每20条数据放在一个数组中
			foreach($newSon as $kp => & $vv){
				$temp=ceil(count($vv)/$eveCol);
				$newRow=array();
				for($i=0;$i<$temp;$i++){
				    $newRow[]=array_slice($vv,$eveCol*$i,$eveCol);
				}
				// dump($newRow);exit;
				//覆盖原来的每页中的数据
				$vv=$newRow;
				//计算小计，对该页中不同列计算小计
				foreach($newRow as $k=>& $val){
				    foreach($val as & $he){
						$xiaoji[$key][$kp][$k]['cnt']+=$he['cnt'];
						$xiaoji[$key][$kp][$k]['cntM']+=$he['cntM'];
						$xiaoji[$key][$kp][$k]['cntMadan']+=$he['cntMadan'];
						$xiaoji[$key][$kp][$k]['bang']+=$he['bang'];
						$xiaoji[$key][$kp][$k]['cntjian']=count($val);
				    }
				}
			}
			// dump($newSon);exit;
			//覆盖原来son值
			$v['Son']=$newSon;
		}
		//计算本页合计
		$hejiCurPage=array();//本页合计
		foreach ($xiaoji[0] as $key => & $value) {
			foreach ($value as $k => & $v) {
				$hejiCurPage[$key]['cnt']+=$v['cnt'];
				$hejiCurPage[$key]['cntM']+=$v['cntM'];
				$hejiCurPage[$key]['cntMadan']+=$v['cntMadan'];
				$hejiCurPage[$key]['bang']+=$v['bang'];
				$hejiCurPage[$key]['cntjian']+=$v['cntjian'];
			}
		}
		// dump($hejiCurPage);exit;
		//计算累计 根据合计计算
		$leijiCurPage=array();
		
		$this->GetLeiji($hejiCurPage,count($hejiCurPage),$leijiCurPage);
		// dump($leijiCurPage);exit;
// 		//计算每个花型的需要的分页行位置
// 		$arrPos = array();
// 		$head=3;
// 		$nums=0;
// 		$noP=0;
// 		// dump($xiaoji);exit;
// 		foreach($xiaoji as $k=>& $v) {
// 			for($i=1;$i<=count($v);$i++){
// 			    $no=count($v)>1?42:43;
// 				$no=$i==count($v)?43:42;
// 				//每页行数
// 				$page= $i==1?$head+$no:$no;
// 				//dump($i);dump($page);
// 				$nums+=$page;
// 				$arrPos[$k][] =$nums;
// 				$noP++;
// 			}
// 		}
		if(($_POST['mi'] == 'checkbox') && ($_POST['mashu'] == '')){
			$ls = 8;
			$ll=14;
		}elseif(($_POST['mi'] == '') && ($_POST['mashu'] == 'checkbox')){
			$ls = 8;
			$ll=14;
		}else{
			$ls = 11;
			$ll=17;
		}
// 		if(($_POST['mi'] == 'checkbox') && ($_POST['mashu'] == '')){
// 			$ls = 5;
// 			$ll=11;
// 		}elseif(($_POST['mi'] == '') && ($_POST['mashu'] == 'checkbox')){
// 			$ls = 5;
// 			$ll=11;
// 		}else{
// 			$ls = 8;
// 			$ll=14;
// 		}
		$_POST['ls'] = $ls;
		$_POST['ll'] = $ll;
		//dump(!isset($_POST['bang']));exit;
		// dump($exportInfo);exit;
		$smarty=& $this->_getView();
		$smarty->assign('madan',$rowset);
		$smarty->assign('name',$name);
		$smarty->assign('noP',$noP);//分得页数
		$smarty->assign('arrPos',$arrPos);
		$smarty->assign('xiaoji',$xiaoji);
		$smarty->assign('zongji',$zongji);
		$smarty->assign('exInfo',$exportInfo);
		$smarty->assign('field',$_POST);
		$smarty->assign('totalpages',$totalpages);//总共的打印版数
		$smarty->assign('hejiCurPage',$hejiCurPage);//显示当前版面的合计
		$smarty->assign('leijiCurPage',$leijiCurPage);//累计的数组
		
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/force-download");
		header("Content-Type: application/download");
		header("Content-Disposition: attachment;filename=".iconv('utf-8',"gb2312", "码单".$rowset[0]['compName'].'-'.$rowset[0]['chukuDate']).".xls");
		header("Content-Transfer-Encoding: binary");
		
		if(!isset($_POST['bang'])){
			$tpl = 'Cangku/Chengpin/ExportMadanNew.tpl';
		}else{
			$tpl = 'Cangku/Chengpin/ExportMadanNew2.tpl';
		}
		
		$smarty=$smarty->display($tpl);
		// js_alert(null, 'window.parent.showMsg("下载成功！");', url('Cangku_Chengpin_ChukuSell','right'));
	}

	/**
	 * 码单导出
	*/
	function actionExportRuku(){
		// dump($_POST);exit;
		FLEA::loadClass('TMIS_Pager');
		$name=FLEA::getAppInf('compName');
		// dump($rowset);exit;
		//整理码单信息，每200个码单一页，40个一列，4列一行
		//导出时固定格式的参数
		$exportInfo=array();
		$eveCol=20;//一页20行，
		$eveRow=3;//3大列
		$evePage=$eveCol*$eveRow; //一页打印60条数据
		$exportInfo=array(
			'page'=>$evePage,
			'col'=>$eveCol,//一页20行，
			'row'=>$eveRow,//3大列
		);

		// 取得需要导出的入库明细信息
		$str="select x.id,
					x.pihao,
					y.rukuDate,
					a.orderCode,
					b.menfu,
					b.kezhong,
					p.proName,
					p.guige,
					p.color,
					x.cntJian,
					x.cnt,
					x.cntM,
					e.compName
		 from cangku_common_ruku2product x
			left join cangku_common_ruku y on x.rukuId=y.id 
			left join trade_order a on y.orderId=a.id
			left join trade_order2product b on x.ord2proId=b.id
			left join jichu_client e on a.clientId=e.id
			left join jichu_product p on p.id=x.productId
			where 1";
		if($_POST['orderId']!='') $str.=" and y.orderId='{$_POST['orderId']}'";//订单id
		if($_POST['id']!='') $str.=" and x.id='{$_POST['id']}'";
		$str.=" and exists(select id from cangku_madan c where c.ruku2proId=x.id)";
		// dump($str);exit;
		$rowset = $this->_modelExample->findBySql($str);
		// dump($rowset);exit;
		//查找出库明细具体的码单信息
		foreach ($rowset as $k => & $row) {
			$cntKg=0;
			$cntM=0;
			$cntMadan=0;
			$bang=0;
			$arr=array();
			//查找码单信息
			$sql="select * from cangku_madan where ruku2proId='{$row['id']}' order by seCode,lot";
			$row['Son']=$this->_modelExample->findBySql($sql);
			// dump($row['Son']);exit;
			//处理码单信息
			$temp_number=array();
			foreach($row['Son'] as & $v) {
				$v['bang']=round(($v['cnt']*2.2046226),2);
				#取得最大值，确定所需的表格数及求得发运数
				$cntKg+=$v['cnt'];
				$cntM+=$v['cntM'];
				$cntMadan+=$v['cntMadan'];
                $bang+=$v['bang'];
                $seCode+=$v['seCode'];
				// $v['num']=$v['number'].'#';
				//取得值类型,判断是为String还是Number
				$v['type']='Number';
				/* if(is_numeric($v['cnt_format'])) {
					$v['type'] = 'Number';
				} */
				// $temp_number[]=$v['number'];
			}
            //把码单的中的总值，放入入库从表中
			$row['cnt_Kg']=round($cntKg,2);
			$row['cnt_M']=round($cntM,2);
			$row['cntMadan']=round($cntMadan,2);
			$row['bang']=round($bang,2);
			$row['seCode']=round($seCode,2);
			$row['cnt_Jian']=count($row['Son']);

			/**
			* 处理码单之间的件数不间断
			*/
			/*$son_arr=array();
			foreach($row['Son'] as & $v){
				$son_arr[$v['number']]=$v;
			}
			// dump($son_arr);exit;
			$min=min($temp_number);$max=max($temp_number);
			$min=floor($min/($eveCol*$eveRow))*$eveCol*$eveRow+1;
			// echo $min;exit;
			for($i=$min;$i<=$max;$i++) {
				if(!isset($son_arr[$i]))$son_arr[$i]=array('number'=>$i);
			}
			// dump($son_arr);exit;
			$row['Son']=$son_arr;*/
		}
		// dump($rowset);exit;
        
		//计算小计
		$xiaoji=array();
		$totalpages=array();
		foreach($rowset as $key => & $v){
			//对$v['Son']进行排序，得到所有经过排序后的对应的码单
			$son=array_column_sort($v['Son'],'number',SORT_ASC);
			// ksort($v['Son']);
			// $son=$v['Son'];
			// dump($son);exit;
			//计算需要的页数，并重新组织数据
			$page=ceil(count($son)/$evePage);//向上取整
			$totalpages[]=$page;
			$newSon=array();//每60条数据放在一个数组里
			for($i=0;$i<$page;$i++){
				$_ttt = array_slice($son,$evePage*$i,$evePage);
			    $newSon[]=$_ttt;
			    // if($i==5) {
			    // 	dump($son);exit;
			    // 	dump($_ttt);exit;
			    // }
			}
			// dump($newSon);exit;
// 			foreach ($newSon as & $vv){
			
// 				$vv=array_column_sort($vv,'lot',SORT_ASC);
// 				//dump($vv);exit;
// 			}
			//对每页处理,处理成每页中 每20条数据放在一个数组中
			foreach($newSon as $kp => & $vv){
				$temp=ceil(count($vv)/$eveCol);
				$newRow=array();
				for($i=0;$i<$temp;$i++){
				    $newRow[]=array_slice($vv,$eveCol*$i,$eveCol);
				}
				// dump($newRow);exit;
				//覆盖原来的每页中的数据
				$vv=$newRow;
				//计算小计，对该页中不同列计算小计
				foreach($newRow as $k=>& $val){
				    foreach($val as & $he){
						$xiaoji[$key][$kp][$k]['cnt']+=$he['cnt'];
						$xiaoji[$key][$kp][$k]['cntM']+=$he['cntM'];
						$xiaoji[$key][$kp][$k]['cntMadan']+=$he['cntMadan'];
						$xiaoji[$key][$kp][$k]['bang']+=$he['bang'];
						$xiaoji[$key][$kp][$k]['cntjian']=count($val);
				    }
				}
			}
			// dump($newSon);exit;
			//覆盖原来son值
			$v['Son']=$newSon;
		}
		// dump($totalpage);exit;
         // dump($xiaoji[0]);exit;
         //计算本页合计
		 $hejiCurPage=array();//本页合计
         foreach ($xiaoji[0] as $key => & $value) {
         	 foreach ($value as $k => & $v) {
         	 	$hejiCurPage[$key]['cnt']+=$v['cnt'];
         	 	$hejiCurPage[$key]['cntM']+=$v['cntM'];
         	 	$hejiCurPage[$key]['cntMadan']+=$v['cntMadan'];
         	 	$hejiCurPage[$key]['bang']+=$v['bang'];
         	 	$hejiCurPage[$key]['cntjian']+=$v['cntjian'];
         	 }
         }
        
        //计算累计 根据合计计算
         $leijiCurPage=array();

         $this->GetLeiji($hejiCurPage,count($hejiCurPage),$leijiCurPage);
         // dump($leijiCurPage);exit;

		//计算每个花型的需要的分页行位置
		// $arrPos = array();
		// $head=3;
		// $nums=0;
		// $noP=0;
		// // dump($xiaoji);exit;
		// foreach($xiaoji as $k=>& $v) {
		// 	for($i=1;$i<=count($v);$i++){
		// 	    $no=count($v)>1?42:43;
		// 		$no=$i==count($v)?43:42;
		// 		//每页行数
		// 		$page= $i==1?$head+$no:$no;
		// 		//dump($i);dump($page);
		// 		$nums+=$page;
		// 		$arrPos[$k][] =$nums;
		// 		$noP++;
		// 	}
		// }
		// dump($arrPos);exit;
		// dump($noP);exit;
		// dump($rowset);exit;
		// dump($exportInfo);exit; 
		//组成的数组的形式是 以 分2页，每页60条数据 为例,假设有90条数据： 
		//array[0][0]=20；array[0][1]=20；array[0][2]=20；
		//array[1][0]=20；array[1][1]=10；
        // dump($rowset[0]['Son'][5]);exit;
//         dump($_POST);exit;
        //判断模板合并列数
        if(($_POST['mi'] == 'checkbox') && ($_POST['mashu'] == '')){
        	$ls = 8;
        	$ll=14;
        }elseif(($_POST['mi'] == '') && ($_POST['mashu'] == 'checkbox')){
        	$ls = 8;
        	$ll=14;
        }else{
        	$ls = 11;
        	$ll=17;
        }
        // dump($rowset);exit;
        $_POST['ls'] = $ls;
        $_POST['ll'] = $ll;
		$smarty=& $this->_getView();
		$smarty->assign('madan',$rowset);
		$smarty->assign('name',$name);
		$smarty->assign('noP',$noP);//分得页数
		$smarty->assign('arrPos',$arrPos);
		$smarty->assign('xiaoji',$xiaoji);
		$smarty->assign('zongji',$zongji);
		$smarty->assign('exInfo',$exportInfo);//3大列，20行，60个数据
		$smarty->assign('field',$_POST);
		$smarty->assign('totalpages',$totalpages);//总共的打印版数
		$smarty->assign('hejiCurPage',$hejiCurPage);//显示当前版面的合计
		$smarty->assign('leijiCurPage',$leijiCurPage);//累计的数组
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/force-download");
		header("Content-Type: application/download");
		header("Content-Disposition: attachment;filename=".iconv('utf-8',"gb2312", "码单".$rowset[0]['compName'].'-'.$rowset[0]['rukuDate']).".xls");
		header("Content-Transfer-Encoding: binary");
		
        if(!isset($_POST['bang'])){
        	$tpl = 'Cangku/Chengpin/ExportMadanNew.tpl';
        }else{
        	$tpl = 'Cangku/Chengpin/ExportMadanNew2.tpl';
        }
		
		$smarty=$smarty->display($tpl);
	} 
    
    //码单导出内容选择
	function actionSelExportChuku(){
        $id=$_GET['id'];

        $smarty=& $this->_getView();
        $smarty->assign('id',$id);
        $smarty=$smarty->display("Cangku/Chengpin/SelExportChuku.tpl");
	}

	function actionSelExportRuku(){
        $id=$_GET['id'];

        $smarty=& $this->_getView();
        $smarty->assign('id',$id);
        $smarty=$smarty->display("Cangku/Chengpin/SelExportRuku.tpl");
	}
    
    //得到累计的方法 第一个参数是数组，第二个参数是第一个数组的长度,第三个是返回的数组
    function GetLeiji($arr,$num,&$temp){
    	// $len = count($arr);
    	// $ret= array();
    	// foreach($arr as  & $v) {
    	// 	$cnt += $v['cnt']; 
    	// 	$cntM += $v['cntM']; 
    	// 	$cntMadan += $v['cntMadan']; 
    	// 	$bang += $v['bang']; 
    	// 	$cntjian += $v['cntjian']; 
    	// 	// $temp = 
    	// 	$ret[] = array(
    	// 		'cnt'=>$cnt,
    	// 		'cntM'=>$cntM,
    	// 		'cntMadan'=>$cntMadan,
    	// 		'bang'=>$bang,
    	// 		'cntjian'=>$cntjian,
    	// 	);
    	// }
    	// // dump($ret);exit;
    	// return $ret;
    	// dump($num);exit;
    	// dump($arr);exit;

    	if($num==1){
           $temp[0]=$arr[0];//dump($temp);exit;
           return true;
    	}
       	foreach ($arr as $key => &$v) {
       	  		$temp[$num-1]['cnt']+=$v['cnt'];
         	 	$temp[$num-1]['cntM']+=$v['cntM'];
         	 	$temp[$num-1]['cntMadan']+=$v['cntMadan'];
         	 	$temp[$num-1]['bang']+=$v['bang'];
         	 	$temp[$num-1]['cntjian']+=$v['cntjian'];
       	}
       	// dump($temp);exit;
       	array_pop($arr);//dump($arr);exit;
        $num=count($arr);//dump($num);exit;
        $this->GetLeiji($arr,$num,$temp);
    }

}
?>