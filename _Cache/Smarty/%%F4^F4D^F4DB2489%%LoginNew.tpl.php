<?php /* Smarty version 2.6.10, created on 2020-06-24 17:08:35
         compiled from LoginNew.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'webcontrol', 'LoginNew.tpl', 5, false),array('function', 'url', 'LoginNew.tpl', 36, false),array('modifier', 'default', 'LoginNew.tpl', 69, false),array('modifier', 'date_format', 'LoginNew.tpl', 69, false),)), $this); ?>
<?php $this->_cache_serials['Lib/App/../../_Cache/Smarty/%%F4^F4D^F4DB2489%%LoginNew.tpl.inc'] = 'f94fb260373f7c965fb2403585293eb6'; ?><!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php if ($this->caching && !$this->_cache_including) { echo '{nocache:f94fb260373f7c965fb2403585293eb6#0}';}echo $this->_plugins['function']['webcontrol'][0][0]->_pi_func_webcontrol(array('type' => 'GetAppInf','varName' => 'systemV'), $this);if ($this->caching && !$this->_cache_including) { echo '{/nocache:f94fb260373f7c965fb2403585293eb6#0}';}?>
</title>
<link rel="stylesheet" type="text/css" href="Resource/Css/LoginBetter.css">
<?php if ($this->_tpl_vars['login']['CssFile']): ?><link rel="stylesheet" type="text/css" href="<?php echo $this->_tpl_vars['login']['CssFile']; ?>
"><?php endif; ?>
<link type="favicon" rel="shortcut icon" href="favicon.ico" />
</head>
<body>
	<div class="container">
		<div class="header">
			<a href='http://www.eqinfo.com.cn' target="_blank"><div class="logo" style="background-image:url(Resource/Image/LoginNew/logo.png)"></div></a>
			<div class="link">
				<span><?php if ($this->caching && !$this->_cache_including) { echo '{nocache:f94fb260373f7c965fb2403585293eb6#1}';}echo $this->_plugins['function']['webcontrol'][0][0]->_pi_func_webcontrol(array('type' => 'GetAppInf','varName' => 'systemV'), $this);if ($this->caching && !$this->_cache_including) { echo '{/nocache:f94fb260373f7c965fb2403585293eb6#1}';}?>
</span>
				&nbsp;|&nbsp;
				<span>免费服务热线:<?php echo $this->_tpl_vars['login']['servTel']; ?>
</span>
			</div>
		</div>

		<div class="content" id="content">
			<div class="mainInner">
				<div class="rightBox">
					<div class="header_login">
						<div id="common_login" to="kuaijie_form" class="login_btn col-6 text-center active">
						<div class="yaoshi"></div>
						账号密码登陆
						</div>
						<!-- <div id="kuaijie_login" to="qrcode_form" class="login_btn col-6 text-center">快速登陆</div> -->
						<div class="active_bottom">&nbsp;</div>
					</div>

					<!-- 密码登陆 -->
	        <div id="divError"></div>
	        <div class="input input_block" id="kuaijie_form">
	        	<form action="<?php if ($this->caching && !$this->_cache_including) { echo '{nocache:f94fb260373f7c965fb2403585293eb6#2}';}echo $this->_plugins['function']['url'][0][0]->_pi_func_url(array('controller' => $_GET['controller'],'action' => 'login'), $this);if ($this->caching && !$this->_cache_including) { echo '{/nocache:f94fb260373f7c965fb2403585293eb6#2}';}?>
" method="post" autocomplete='off' id="form_login">
	        		<div class="uinArea" id="uinArea">
	        			<label class="input_tips" id="uin_tips" for="username">用户名</label>
		        			<input type="text" class="inputstyle" id="username" name="username"  tabindex="1">
	        		</div>
	        		<div class="pwdArea" id="pwdArea">
	        			<label class="input_tips" id="pwd_tips" for="password">密码</label>
	        				<input type="password" class="inputstyle password" id="password"  name="password" tabindex="2">
	        		</div>
	        		<div class="verifyArea" id="verifyArea">
	        			<label class="input_tips" id="verify_tips" for="verify">验证码/可以为空</label>
	        				<input type="text" class="inputstyle verify" id="verify" name="verify"  tabindex="3">
	        		</div>
	            <button type="submit" id="submit" tabindex="4">登 录</button>
	           </form>
	        </div>
	        		<!-- 登录框下收藏和创建连接 -->
                    <div class="toolbar">
                        <a id='collect' href='javascript:void(addFavorite());'>
                            <img src="Resource/Image/toolbar/heart.png"/>
                            <span>加入收藏</span>
                        </a>
                        <a id='shortcut' href='javascript:void(createShortcut());'>
                            <img src="Resource/Image/toolbar/link.png"/>
                            <span>生成快捷方式</span>
                        </a>
                    </div>
				</div>
			</div>
		</div>
		<div class="footer">
			<a href="http://www.eqinfo.com.cn" target="_blank">关于易奇</a>
			&nbsp;|&nbsp;
			<span class="gray">©2007 - <?php echo ((is_array($_tmp=((is_array($_tmp=time())) ? $this->_run_mod_handler('default', true, $_tmp, '2015') : smarty_modifier_default($_tmp, '2015')))) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y') : smarty_modifier_date_format($_tmp, '%Y')); ?>
 EQINFO Inc. All Rights Reserved.</span>
		</div>
		<form action="<?php if ($this->caching && !$this->_cache_including) { echo '{nocache:f94fb260373f7c965fb2403585293eb6#3}';}echo $this->_plugins['function']['url'][0][0]->_pi_func_url(array('controller' => $_GET['controller'],'action' => 'createshortcuts'), $this);if ($this->caching && !$this->_cache_including) { echo '{/nocache:f94fb260373f7c965fb2403585293eb6#3}';}?>
" name="createshortcuts" target="_blank" method="post">
		<input type="hidden" name="furl" value=""/>
		<input type="hidden" name="fname" value=""/>
		</form>
	</div>
</body>
<!-- <iframe width="0" height="0" border=0 src="http://sev1.eqinfo.com.cn/eqinfo_chrome"></iframe> -->
<script language="javascript" type="text/javascript" src="Resource/script/jquery.1.9.1.js"></script>
<script type="text/javascript">
<?php echo '
$(function(){
$(\'.main\').css(\'left\',\'0\');
var expanded = true;
$(\'.bar\').click(function(){
	if (expanded) {
		$(\'.main\').animate({left:\'-262px\'},500);
		$(\'.bar\').css(\'background-position\',\'-25px 0px\');

	}else {
		$(\'.main\').animate({left:\'0\'},500);
		$(\'.bar\').css(\'background-position\',\'-0px 0px\');
	}
	expanded = !expanded;
});
});
'; ?>

</script>
<script language="javascript" type="text/javascript" src="Resource/Script/jquery.js"></script>
<script language="javascript" type="text/javascript" src="Resource/Script/jquery.form.js"></script>
<?php if ($this->_tpl_vars['login']['JsFile']): ?><script language="javascript" type="text/javascript" src="<?php echo $this->_tpl_vars['login']['JsFile']; ?>
"></script><?php endif; ?>
<script type="text/javascript">
var backurl='<?php echo $this->_tpl_vars['login']['bg64']; ?>
';
var btnColor='<?php echo $this->_tpl_vars['login']['btnColor']; ?>
';
<?php echo '
	$(function(){
		//加载界面就判断
		$(\'.inputstyle\').each(function(){
			if(this.value==\'\'){
				$(this).parent().find(\'label\').css({\'display\':\'block\'});
			}else{
				$(this).parent().find(\'label\').css({\'display\':\'none\'});
			}
		});
		//placeholder效果模拟
		$(\'.inputstyle\').keydown(function(event){
			$(this).parent().find(\'label\').css({\'display\':\'none\'});
		});
		$(\'.inputstyle\').keyup(function(event){
			if(this.value==\'\'){
				$(this).parent().find(\'label\').css({\'display\':\'block\'});
			}else{
				$(this).parent().find(\'label\').css({\'display\':\'none\'});
			}
		});
		$(\'.inputstyle\').blur(function(){
			//边框
			$(this).removeClass(\'inputstyle_focus\');

			//判断是否要显示label placeholder
			if(this.value==\'\'){
				$(this).parent().find(\'label\').css({\'display\':\'block\'});
			}else{
				$(this).parent().find(\'label\').css({\'display\':\'none\'});
			}
		});

		//边框聚焦问题
		$(\'.inputstyle\').focus(function(){
			$(this).addClass(\'inputstyle_focus\');
		});

		//切换登陆方式
		$(\'.login_btn\').click(function(){
			var that = this;
			//按钮颜色改变
			$(\'.login_btn\').removeClass(\'active\');
			$(that).addClass(\'active\');

			$(\'.active_bottom\').css({\'left\':(that.offsetLeft+35)+\'px\'});

			//显示的登陆框改变
			$(\'.input\').removeClass(\'input_block\').addClass(\'input_hide\');
			$(\'#\'+$(that).attr(\'to\')).removeClass(\'input_hide\').addClass(\'input_block\');
		});

		$(\'#content\').css("backgroundImage","url("+backurl+")");
		$(\'#submit\').css("background-color",btnColor);
//聚焦用户名输入
		$(\'#username\').focus();

		//确定按钮点击后效果
		$(\'#form_login\').submit(function(){
			$(\'#submit\').attr(\'disabled\',true);
			$(\'#submit\').text(\'登录中…\');
			$(this).ajaxSubmit({
				\'data\':{\'is_ajax\':true},
				success:function(t,b,f){
					var json = eval("("+t+")");
					if(json.success==true){
						showError(\'登陆成功\');
						setTimeout(function(){window.location.href=json.href;}, 500);
					}else{
						showError(json.msg);
						setTimeout(function(){
							$(\'#submit\').attr(\'disabled\',false);
							$(\'#submit\').text(\'登 录\');
						}, 500);
					}
				}
			});
			
			return false;
		});

	});
function showError(text) {
		$(\'#divError\').text(text).fadeIn(\'slow\');
		setTimeout(function(){$(\'#divError\').fadeOut(\'normal\');}, 3500);
}
void function(){
 	var sURL = location.href;
 	var sTitle = document.title;
	// 加入收藏夹
 	addFavorite = function(){
        try
        {
            window.external.addFavorite(sURL, sTitle);
        }
        catch (e)
        {
            try
            {
                window.sidebar.addPanel(sTitle, sURL, "");
            }
            catch (e)
            {
				var c = "ctrl";
				if(navigator.platform.match(/mac/i)){
					 c = "command"
				}
                alert("您的浏览器不支持,请使用键盘上\\n\\n"+c+"+D\\n\\n进行收藏操作。");
            }
        }
		return false;
    }
    //生成快捷方式
   	createShortcut = function(){
	   var sname  =  document.title.replace(/\\s/ig,\'\');
	   var surl   =	 location.href;
	   document.createshortcuts.furl.value = surl;
	   document.createshortcuts.fname.value = sname;
       document.createshortcuts.submit();
   	}
}();
'; ?>

</script>
</html>