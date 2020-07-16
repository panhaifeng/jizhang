<?php
class Controller_Login extends FLEA_Controller_Action {
	function Controller_Login() {
	}
	function actionIndex() {
		require_once('Config/NewLogin_config.php');
		// dump($_login_config);die;
		$login = $_login_config;
		$_login = $_login_config['Login'];
		$_login_Ip = $_login_config['Login_Ip'];
		$_outTime = $_login_config['timeOut'];

		//如果设置了远程地址,获取远程数据，
		if($_login_Ip!=''){
			$_Url = str_replace(PHP_EOL, '',$_login_Ip);
			//设置超时时间
			$context['http'] = array(
				'timeout'=>$_outTime > 0 ? $_outTime : 3,
				'method' => 'POST'
	    	);
			$json = file_get_contents($_Url, false, stream_context_create($context));
			$_l = json_decode($json,true);
			if($_l['success']){
				$login = $_l['config'];
			}
		}

		$smarty = & $this->_getView();
		$login['btnColor']=substr($login['btnColor'],-7);
		FLEA::writeCache('Service.Tel',$login['servTel']);
		// dump($login);exit;
		$smarty->assign('login',$login);
		$smarty->display('LoginNew.tpl');
	}
	function actionLogout() {
		session_destroy();
		redirect(url("Index"));
	}

	function actionLogoutToIndex() {
		session_destroy();
		$ui =& FLEA::initWebControls();
		header("Location:Index.php");
	}
	function actionLogin() {
		do {
			$p=$_POST?$_POST:$_GET;
			$_ajax = $p['is_ajax'];
			if(!isset($p['username'])) break;
            /*验证用户名和密码是否正确*/
			$eqLogin = FLEA::getSingleton('Model_Login');
			$user = $eqLogin->findByUsername($p['username']);
			if (!$user) {
				$msg = "无效用户名";
				if($_ajax){
					echo json_encode(array('success'=>false,'msg'=>$msg));
				}
				else{
					js_alert($msg,null,$this->_url('index'));
				}
				exit;
				//break;
			}
			$userId = $user['id'];
			$realName = $user['realName'];
			$_SESSION['SN'] = false;
			$_SESSION['isTool']=0;
			if($p['password']!=$user['passwd']) {
				$msg = "无效密码";
				if($_ajax){
					echo json_encode(array('success'=>false,'msg'=>$msg));
				}
				else{
					js_alert($msg,null,$this->_url('index'));
				}
				exit;
			}
			if(isset($p['sn']) && $p['sn']) {
				if($p['username']!='admin') {
					$result = $eqLogin->checkSn($userId, $p['sn']);
					if(!$result){
						$msg = "动态密码错误!";
						if($_ajax){
							echo json_encode(array('success'=>false,'msg'=>$msg));
						}
						else{
							js_alert($msg,null,$this->_url('index'));
						}
						exit;
					}
				} else {////如果登录的用户名我admin并且sn不为空，则验证sn是否正确
					$str="SELECT * FROM acm_sninfo where 1";
					$row=$eqLogin->findBySql($str);
					$bSn = false;
					foreach($row as & $v){
						if($v['sn'] == $user['sn']) {
							$result = $eqLogin->checkSn($userId, $p['sn']);
							if($result) {
								$bSn=true;
								$_SESSION['SN'] = true;
								$_SESSION['isTool']=1;
								break;
							}
						}
					}
					if(!$bSn) {
						$msg = "动态密码错误,或者没有在工具箱中定义序列号!";
						if($_ajax){
							echo json_encode(array('success'=>false,'msg'=>$msg));
						}
						else{
							js_alert($msg,null,$this->_url('index'));
						}
						exit;
					}
				}
			}

			//判断是否需要验证扫码认证身份
			//如果是开发者，则不需要验证：：
			$isVerify = $user['qrCodeVerify'];//是否需要验证的判断依据
			$ipWhilte = array('localhost' ,'127.0.0.1');
			if(in_array($_SERVER['SERVER_NAME'], $ipWhilte)){
				$isVerify = 0;
			}

			//如果需要验证，则跳转到身份验证区域，session放临时区域
			if($isVerify == 1){
				$_SESSION['LOGIN_VERIFY_TEMP']['USERID']   = $userId;
				$_SESSION['LOGIN_VERIFY_TEMP']['REALNAME'] = $realName;
				$_SESSION['LOGIN_VERIFY_TEMP']['USERNAME'] = $p['username'];
				$_SESSION['LOGIN_VERIFY_TEMP']['PHP_SELF'] = $_SERVER['PHP_SELF'];
				if($_ajax){
					echo json_encode(array('success'=>true,'href'=>$this->_url('QrCodeVerify')));exit;
				}
				else{
					redirect($this->_url('QrCodeVerify'));
				}
			}

			//验证结束

            /*登录成功，通过 RBAC 保存用户信息和角色*/
			// $_SESSION['LANG'] = $p['language'];
			$_SESSION['USERID'] = $userId;
			$_SESSION['REALNAME'] = $realName;
			$_SESSION['USERNAME'] = $p['username'];
			$_SESSION['PHP_SELF'] = $_SERVER['PHP_SELF'];

			//改变登陆时间
			$re=$eqLogin->changeLoginTime($_SESSION['USERID']);
			
			//更新用户的最后登录时间和登陆日期的登录次数
			if($_ajax){
				echo json_encode(array('success'=>true,'href'=>url('Main')));exit;
			}
			else{
				redirect(url('Main'));
			}
			
		}  while (false);
		// 登录发生错误，再次显示登录界面
		//$ui = FLEA::initWebControls();
		$smarty = & $this->_getView();
		$smarty->display('Login.tpl');
	}
		/**
	 * @desc ：创建桌面快捷方式
	 * Time：2016/09/01 15:59:33
	 * @author Wuyou
	*/
	function actionCreateshortcuts(){
		// dump($_POST);exit;
		$Shortcut = "[InternetShortcut]
		URL={$_POST['furl']}
		IDList=
		[{000214A0-0000-0000-C000-000000000046}]
		Prop3=19,2";
		$filename = urlencode($_POST['fname']);
		header("Content-Type: application/octet-stream");
		header("Content-Disposition: attachment; filename={$filename}.url");
		echo $Shortcut;
	}
	//动态密码卡同步
	function actionTongbu() {
		if($_POST) {
			//dump($_POST);
			$m=FLEA::getSingleton('Model_Acm_User');
			//根据用户名获得sn号
//			$sql = "select * from acm_sninfo where sn='{$_POST['sn']}'";
//			$_r = $m->findBySql($sql);
			$sn = $_POST['sn'];
			//dump($sn);exit;
			//if($sn=='') die('未发现用户纪录');

			//动态令牌SN号对应的字符串
			$sql = "select * from  acm_userdb where sn='{$sn}'";
			$_r = $m->findBySql($sql);
			$str = $_r[0]['sninfo'];
			if(count($_r)==0) die('未发现动态密码卡登记信息');

			$b=new COM("SeaMoonDLL.ClassKeys");//调用Com组件
			//dump($b);
			//dump($_POST);dump($str);exit;
			//调用同步接口，第一个参数为动态令牌SN号对应的字符串，第二个参数为动态密码
			//dump($_POST);exit;
			$c=$b->ITSecurity_SN_syn($_POST['initCode'],$_POST['dPwd'],0,"0");
			//dump($str);dump($_POST);exit;
			if (strlen($c)>3){
				echo "同步成功";
				//此时你需要把$c的值更新到你的数据库，下次调用时取出此字符串作为参数
				$sql = "update acm_userdb set snInfo='".trim($c)."' where sn='{$sn}'";
				$m->execute($sql);
				echo "同步成功!";
			}elseif($c=="-2"){
				echo "系统内部错误";
			}else{
				echo "同步失败";
			}
			echo $c;
			exit;
			//exit;
		}

		echo '<br>动态密码卡时间同步接口<br>
0)<font color="red">同步前：您需要从技术部获得初始注册码,然后开始同步</font>
	<br>函数原型：String  ITSecurity_SN_syn(SNInfo, password,usestime,cardclock)<br>
1）	SNInfo：动态密码卡的SN信息字符串，需要根据用户名从用户表中查出该用户的SN号码，然后从SN信息表中，根据SN号码，查出该SN号码对应的SN信息字符串；<br>
2）	Password：动态密码卡上显示的密码;<br>
3）	Usestime: 卡的使用次数。只有用SecureCard（卡片式动态密码卡），才有这个参数，用其他小卡时，填入0即可；<br>
4）	cardclock：卡的时钟。只有用SecureCard（卡片式动态密码卡），才有这个参数，用其他小卡时，填入字符串“0”即可；<br>
5）	返回值：<br>
A）	时间同步成功时，返回值的是新的SN信息字符串，这时返回的字符串长度大于3。特别说明：密码验证通过时，需要用返回的这个字符串替换《SN信息表》的原字符串。
返回值等于“-2”时，表示“动态加密字符串有错”；
返回值等于“0”时，表示“动态密码错误”
';
		echo '<form name="login" id="login" method="post" action="?controller=Login&action=Tongbu">
						<table>
							<tr>
								<td>初始sn<span style="font-weight:bold">:</span></td>
								<td align="left"><input type="text" name="sn" id="sn" value="" style="width:100px;" tabindex="2"/></td>
							</tr>
							<tr>
								<td>初始字符串<span style="font-weight:bold">:</span></td>
								<td align="left"><input type="text" name="initCode" id="initCode" value="" style="width:100px;" tabindex="2"/></td>
							</tr>
							<tr>
								<td>动态密码<span style="font-weight:bold">:</span></td>
								<td align="left"><input type="text" name="dPwd" id="dPwd" value="" style="width:100px;" tabindex="2"/></td>
							</tr>

							<tr style="height:50px;">
								<td colspan="3"><input name="login" type="submit" class="button" value=" 开始同步 " </td>
							</tr>
						</table>
					</form>';
		exit;
	}

    /**显示验证码*/
	function actionImgCode() {
		$imgcode =& FLEA::getSingleton('FLEA_Helper_ImgCode');
		$imgcode->image();
	}


	/**
	 * @desc ：二维码验证页面
	 * Time：2019/07/15 16:42:40
	 * @author Wuyou
	*/
	function actionQrCodeVerify(){
		// 生成token信息记录
		$m = FLEA::getSingleton('Model_Acm_Qrcodeverify');
		$token = $m->createRecord($_SESSION['LOGIN_VERIFY_TEMP']['USERNAME']);
		// 删除今天之前的token记录：这些数据属于过期未删除数据，清理后方便系统维护和提高性能
		$timeToday = strtotime(date('Y-m-d'));
		$m->removeByConditions("timestamp < '{$timeToday}'");

		// 二维码图片地址获取
		$qrCodePath = $this->_createQrcode($token);
		// dump($qrCodePath);

		// $login['bg64'] = 'Resource/Image/LoginNew/qrCode_bg.jpg';
		$smarty = & $this->_getView();
		$smarty->assign('login',$login);
		$smarty->assign('token',$token);
		$smarty->assign('mainUrl',url('Main'));
		$smarty->assign('qrCodePath',$qrCodePath);
		$smarty->display('QrcodeVerify.tpl');
	}

	/**
	 * @desc ：获取扫码状态
	 * Time：2019/07/17 10:20:42
	 * @author Wuyou
	*/
	function actionGetStatusByAjax(){
		$m = FLEA::getSingleton('Model_Acm_Qrcodeverify');
		$break = false;
		$i = 0;
		while (($break == false && $i <= 6)) {
			$i++;
			$arr = $m->find(array('token'=>$_GET['token']));
			// 如果为创建和已扫码状态的二维码，且时长超过60s，则状态为超时
			if($arr['status']=='CREATED' || $arr['status']=='SCANED'){
				$time = time() - $arr['timestamp'];
				if($time > 60){
					$arr['status'] = 'OVERTIME';
					$break = true;
				}else{
					//暂停500毫秒
					usleep(500000);
				}
			}elseif($arr['status']=='SUCCESS'){
				$_SESSION['USERID']   = $_SESSION['LOGIN_VERIFY_TEMP']['USERID'];
				$_SESSION['REALNAME'] = $_SESSION['LOGIN_VERIFY_TEMP']['REALNAME'];
				$_SESSION['USERNAME'] = $_SESSION['LOGIN_VERIFY_TEMP']['USERNAME'];
				$_SESSION['PHP_SELF'] = $_SESSION['LOGIN_VERIFY_TEMP']['PHP_SELF'];
				unset($_SESSION['LOGIN_VERIFY_TEMP']);

				//更改登录时间
				$eqLogin = FLEA::getSingleton('Model_Login');
				$re = $eqLogin->changeLoginTime($_SESSION['USERID']);
				$break = true;
			}
		}

		echo json_encode(array('success'=>true,'verifyInfo'=>$arr));exit;
	}

	/**
	 * @desc ：重新获取二维码
	 * Time：2019/07/18 14:47:14
	 * @author Wuyou
	*/
	function actionRefreshQrcode(){
		if(!$_GET['token']){
			echo json_encode(array('success'=>false));exit;
		}
		$ret = array();
		// 生成token信息记录
		$m = FLEA::getSingleton('Model_Acm_Qrcodeverify');
		$ret['token'] = $m->createRecord($_SESSION['LOGIN_VERIFY_TEMP']['USERNAME']);
		// 删除老的token记录
		$m->removeByConditions(array('token'=>$_GET['token']));
		// 获取二维码图片地址
		$ret['qrCodePath'] = $this->_createQrcode($ret['token']);

		echo json_encode(array('success'=>true,'data'=>$ret));exit;
	}

	/**
	 * @desc ：服务端生成二维码
	 * Time：2019/07/22 15:50:37
	 * @author Wuyou
	 * @param   1.compCode 项目编号，参考demo代码或者积分使用的代码
				2.compName compInfo中配置的公司名称
				3.userName 需要绑定的帐号
				4.token 当前处理的token，对应到某次请求的唯一码
				5.fromUrl 当前项目的url地址 如 http://sev7.eqinfo.com.cn/demo_project  后面的/及index.php都不需要
	 * @return 二维码图片url
	*/
	function _createQrcode($token){
		$url = 'http://sev11.eqinfo.com.cn/login-verify-wechat/index.php?controller=Apply_Index&action=Qrcode';

		$m = FLEA::getSingleton('Model_Acm_Qrcodeverify');
		$arr = $m->find(array('token'=>$token));

		$params = array(
			'fromUrl'  =>$arr['projectAdd'],
			'userName' =>$arr['userName'],
			'compCode' =>$arr['compCode'],
			'compName' =>FLEA::getAppInf('compName'),
			'token'    =>$arr['token'],
		);
		$query = http_build_query($params);
		$url .= "&".$query;
        return $url;
	}
}


?>