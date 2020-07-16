<?php
$_login_config = array(
  //登陆界面配置文件
  'Login'=>array(
    array(
      'bg'=>'Resource/Image/LoginNew/bg.gif',//显示在登陆窗口左边的图片地址
      'content'=>'ERP由美国 Gartner Group|公司于1990年提出|以系统化的管理思想|为企业决策层及员工|提供决策运行手段的管理平台',//显示内容 | 表示换行
      'pTop'=>'100',//显示在登陆窗口左边的图片距离上面的距离，不填写表示0
      'pictureCss'=>'',//显示的图片的其他样式，
      'lTitle'=>'易奇科技，一起看ERP',//显示标题
      'titleCss'=>'',//显示标题的其他样式
      'bigCss'=>'',//整个登陆特殊的样式，一般没有
      'contentCss'=>'',//显示文字的样式
    ),
  ),
  'Login_Ip'=>'http://sev1.eqinfo.com.cn/login_server/index.php?bg64=1',//为空不会访问远程服务器
  'timeOut'=>'3',//连接远程服务器超时时间，默认3秒
  'bg'=>'Resource/Image/LoginNew/localBg.png',
  'servTel'=>'4008285817',
);
?>