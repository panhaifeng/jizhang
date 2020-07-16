<?php /* Smarty version 2.6.10, created on 2020-07-15 08:59:26
         compiled from BossReport.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'webcontrol', 'BossReport.tpl', 6, false),array('modifier', 'json_encode', 'BossReport.tpl', 40, false),)), $this); ?>
<?php $this->_cache_serials['Lib/App/../../_Cache/Smarty\%%24^24D^24D6BBC5%%BossReport.tpl.inc'] = '7c70a3faf434d719a324e5e557a33821'; ?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>老板驾驶舱</title>
    <?php if ($this->caching && !$this->_cache_including) { echo '{nocache:7c70a3faf434d719a324e5e557a33821#0}';}echo $this->_plugins['function']['webcontrol'][0][0]->_pi_func_webcontrol(array('type' => 'LoadJsCss','src' => "Resource/Script/Calendar/WdatePicker.js"), $this);if ($this->caching && !$this->_cache_including) { echo '{/nocache:7c70a3faf434d719a324e5e557a33821#0}';}?>

    <?php if ($this->caching && !$this->_cache_including) { echo '{nocache:7c70a3faf434d719a324e5e557a33821#1}';}echo $this->_plugins['function']['webcontrol'][0][0]->_pi_func_webcontrol(array('type' => 'LoadJsCss','src' => "Resource/Script/jquery.1.9.1.js"), $this);if ($this->caching && !$this->_cache_including) { echo '{/nocache:7c70a3faf434d719a324e5e557a33821#1}';}?>

    <?php if ($this->caching && !$this->_cache_including) { echo '{nocache:7c70a3faf434d719a324e5e557a33821#2}';}echo $this->_plugins['function']['webcontrol'][0][0]->_pi_func_webcontrol(array('type' => 'LoadJsCss','src' => "Resource/bootstrap/bootstrap3.0.3/css/bootstrap.css"), $this);if ($this->caching && !$this->_cache_including) { echo '{/nocache:7c70a3faf434d719a324e5e557a33821#2}';}?>

    <?php if ($this->caching && !$this->_cache_including) { echo '{nocache:7c70a3faf434d719a324e5e557a33821#3}';}echo $this->_plugins['function']['webcontrol'][0][0]->_pi_func_webcontrol(array('type' => 'LoadJsCss','src' => "Resource/bootstrap/bootstrap3.0.3/js/bootstrap.min.js"), $this);if ($this->caching && !$this->_cache_including) { echo '{/nocache:7c70a3faf434d719a324e5e557a33821#3}';}?>

    <?php if ($this->caching && !$this->_cache_including) { echo '{nocache:7c70a3faf434d719a324e5e557a33821#4}';}echo $this->_plugins['function']['webcontrol'][0][0]->_pi_func_webcontrol(array('type' => 'LoadJsCss','src' => "Resource/Script/BtGrid/btGrid.2.0.js"), $this);if ($this->caching && !$this->_cache_including) { echo '{/nocache:7c70a3faf434d719a324e5e557a33821#4}';}?>

    <?php if ($this->caching && !$this->_cache_including) { echo '{nocache:7c70a3faf434d719a324e5e557a33821#5}';}echo $this->_plugins['function']['webcontrol'][0][0]->_pi_func_webcontrol(array('type' => 'LoadJsCss','src' => "Resource/Script/BtGrid/btGrid.css"), $this);if ($this->caching && !$this->_cache_including) { echo '{/nocache:7c70a3faf434d719a324e5e557a33821#5}';}?>

    <?php if ($this->caching && !$this->_cache_including) { echo '{nocache:7c70a3faf434d719a324e5e557a33821#6}';}echo $this->_plugins['function']['webcontrol'][0][0]->_pi_func_webcontrol(array('type' => 'LoadJsCss','src' => "Resource/Script/layer/layer.js"), $this);if ($this->caching && !$this->_cache_including) { echo '{/nocache:7c70a3faf434d719a324e5e557a33821#6}';}?>

    <?php if ($this->caching && !$this->_cache_including) { echo '{nocache:7c70a3faf434d719a324e5e557a33821#7}';}echo $this->_plugins['function']['webcontrol'][0][0]->_pi_func_webcontrol(array('type' => 'LoadJsCss','src' => "Resource/Script/echart/echarts.min.js"), $this);if ($this->caching && !$this->_cache_including) { echo '{/nocache:7c70a3faf434d719a324e5e557a33821#7}';}?>

<style type="text/css">
<?php echo '
 body{
    background: #EBF1F2;
    height:100%;
  }
.cal{
  padding: 10px;
  height: 100%;
}
.btn-default:hover, .btn-default:focus, .btn-default:active, .btn-default.active {
    background: #007aff;
    color: white;
    border-color: #007aff;
}
.btn-default{
    background: transparent;
    color: #007aff;
    border-color: #007aff;
}
.codeImg{
    display:none;height: 20px;position: absolute;padding-left: 25%;
}
</style>
'; ?>

<script type="text/javascript">
var _column = <?php echo json_encode($this->_tpl_vars['_column']); ?>
;
var _column2 = <?php echo json_encode($this->_tpl_vars['_column2']); ?>
;
var chartArr = <?php echo json_encode($this->_tpl_vars['chartArr']); ?>
;
var MonthArr = <?php echo json_encode($this->_tpl_vars['MonthArr']); ?>
;
var type = '<?php echo $this->_tpl_vars['type']; ?>
';
var _baseUrl = "?controller=Main&action=BossReport&type=";
<?php echo '
$(function(){
    // 按钮点击，切换显示表格内容，按钮样式改变
    $(\'.btnReport\').click(function(){
        $(\'.btnReport\').removeClass(\'active\');
        $(this).addClass(\'active\');
        var type = $(this).data(\'type\');
        var year = $(\'#year\').val();
        var url = _baseUrl+type+\'&year=\'+year;
        window.location.href = url;

    });
    // 搜索按钮点击触发重新加载页面
    $(\'#btnSearch\').click(function(){
        $(\'.active.btnReport\').click();
    });

    //构建grid
    $(\'#tblMonth\').btGrid({
        \'url\':\'?controller=Main&action=GetReportData&type=Month\'
        ,\'columns\':_column
        ,\'pagination\': false
        ,\'height\':500
        ,\'onClickRow\':function(rowIndex,rowData){
        }
        ,\'onCheckAll\':function() {
        }
        ,\'onCheck\':function(rowIndex,rowData){
        }
        //载入前，需要修改参数,参数中必须带搜索关键字
        //有了这些关键字后，只需要简单的$(\'#gird\').btGrid(\'refresh\')即可
        ,\'beforeLoad\':function(p) {
          var year = $(\'#year\').val();
          p.year = year;
          return p;
        }
    });

    //构建grid
    $(\'#tblClient\').btGrid({
        \'url\':\'?controller=Main&action=GetReportData&type=Client\'
        ,\'columns\':_column
        ,\'pagination\': false
        ,\'height\':500
        ,\'onClickRow\':function(rowIndex,rowData){
        }
        ,\'onCheckAll\':function() {
        }
        ,\'onCheck\':function(rowIndex,rowData){
        }
        //载入前，需要修改参数,参数中必须带搜索关键字
        //有了这些关键字后，只需要简单的$(\'#gird\').btGrid(\'refresh\')即可
        ,\'beforeLoad\':function(p) {
          var year = $(\'#year\').val();
          p.year = year;
          return p;
        }
    });

    if(chartArr){
       $(\'#tblClient2\').btGrid({
            \'columns\':_column2
            ,\'data\':chartArr
            ,\'pagination\': false
            ,\'height\':500
            ,\'onClickRow\':function(rowIndex,rowData){
            }
            ,\'onCheckAll\':function() {
            }
            ,\'onCheck\':function(rowIndex,rowData){
            }
            //载入前，需要修改参数,参数中必须带搜索关键字
            //有了这些关键字后，只需要简单的$(\'#gird\').btGrid(\'refresh\')即可
            ,\'beforeLoad\':function(p) {
              var year = $(\'#year\').val();
              p.year = year;
              return p;
            }
        }); 
    }
    
    $(\'#tblSaler\').btGrid({
        \'url\':\'?controller=Main&action=GetReportData&type=Saler\'
        ,\'columns\':_column
        ,\'pagination\': false
        ,\'height\':500
        ,\'onClickRow\':function(rowIndex,rowData){
        }
        ,\'onCheckAll\':function() {
        }
        ,\'onCheck\':function(rowIndex,rowData){
        }
        //载入前，需要修改参数,参数中必须带搜索关键字
        //有了这些关键字后，只需要简单的$(\'#gird\').btGrid(\'refresh\')即可
        ,\'beforeLoad\':function(p) {
          var year = $(\'#year\').val();
          p.year = year;
          return p;
        }
    });
    
    if(chartArr){
       $(\'#tblSaler2\').btGrid({
            \'columns\':_column2
            ,\'data\':chartArr
            ,\'pagination\': false
            ,\'height\':500
            ,\'onClickRow\':function(rowIndex,rowData){
            }
            ,\'onCheckAll\':function() {
            }
            ,\'onCheck\':function(rowIndex,rowData){
            }
            //载入前，需要修改参数,参数中必须带搜索关键字
            //有了这些关键字后，只需要简单的$(\'#gird\').btGrid(\'refresh\')即可
            ,\'beforeLoad\':function(p) {
              var year = $(\'#year\').val();
              p.year = year;
              return p;
            }
        }); 
    }
    if(type==\'Client\'){
        showBar(MonthArr.xData,MonthArr.valueData,MonthArr.legendData,\'chartMonth\');
    }

    $(\'.bind\').hover(function() {
        document.getElementById("wxImg").style.display="block";
    }, function() {
        document.getElementById("wxImg").style.display="none";
    });
    $(\'.focus\').hover(function() {
        document.getElementById("focusImg").style.display="block";
    }, function() {
        document.getElementById("focusImg").style.display="none";
    });
});
// 显示柱状图
function showBar(xData,valueData,legendData,obj){
    var myChart = echarts.init(document.getElementById(obj));
    var option = {
        // color: [\'#0068B1\',\'#FF595F\',\'#FDC559\',\'#21AD5C\',\'#000000\',\'#A58BBA\',\'#7A5327\',\'#44B5E5\'],
        tooltip: {
            trigger: \'axis\',
            axisPointer: {
                type: \'shadow\'
            }
        },
        legend: {
            data: legendData
        },
        xAxis: [{
            type: \'category\',
            data: xData
        }],
        yAxis: [{
            name: \'\',
            type: \'value\'
        }],
        series: valueData
    };
    myChart.setOption(option);
}
/*查看小程序绑定的二维码*/
function viewDetails(){
    var url=\'?controller=Main&action=ViewMiniCode\';
    $.layer({
        type: 2,
        shade: [1],
        fix: false,
        title: \'小程序二维码\',
        maxmin: false,
        iframe: {src : url},
        // border:false,
        area: [\'600px\' , \'500px\']
    });
}
'; ?>

</script>
</head>
<body>
  <div class="row cal">
    <div class="col-md-12" >
<!--         <?php if ($this->_tpl_vars['canShowBoss']['isTrialVer']): ?>
        <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
            <span class="sr-only">Error:</span>
            剩余使用天数【<?php echo $this->_tpl_vars['canShowBoss']['days']; ?>
天】，试用到期后将关闭，正式启用请联系您的专属客服！
        </div>
        <?php endif; ?>
        <div class="alert alert-success alert-dismissible" role="alert" >
            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
            <span class="sr-only"></span>
            老板驾驶舱有小程序版本了，快来体验吧~&nbsp;
            <button type="button" class="btn" onclick="viewDetails()">点击查看</button>
        </div> -->

        <div class="panel panel-default" id="panelChart">
            <!-- Default panel contents -->
            <div class="panel-heading" style="height:5em">
                <div class="form-inline">
                    <div class="pull-left">
                      <button type="button" class="btn btn-default btnReport <?php if ($this->_tpl_vars['type'] == 'Client'): ?>active<?php endif; ?>" data-type='Client'>按客户汇总</button>
                      <button type="button" class="btn btn-default btnReport <?php if ($this->_tpl_vars['type'] == 'Saler'): ?>active<?php endif; ?>" data-type='Saler'>按业务员汇总</button>
                    </div>
                    <div class="pull-right">
                        <button type="button" class="btn btn-default" id='btnSearch'><span class="glyphicon glyphicon-search"></span>&nbsp;搜索</button>
                    </div>
                    <div class="pull-right">
                        <select class="form-control" id='year' name='year'>
                            <?php $_from = $this->_tpl_vars['yearArr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
                            <option value='<?php echo $this->_tpl_vars['item']; ?>
' <?php if ($this->_tpl_vars['arr_condition']['year'] == $this->_tpl_vars['item']): ?> selected <?php endif; ?>><?php echo $this->_tpl_vars['item']; ?>
</option>
                            <?php endforeach; endif; unset($_from); ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="panel-body" style="padding:10px;border-top:1px solid #ccc;">
                <div id="tabListClient" class="divTable" style="height:100%;<?php if ($this->_tpl_vars['type'] != 'Client'): ?>display:none;<?php endif; ?>">
                    <div id="chartMonth" class="divChart" style="height:300px;">
                    </div>
                    <table class="table table-striped table-hover table-condensed tableInTab" id='tblClient2'></table>
                    <!-- <table class="table table-striped table-hover table-condensed tableInTab" id='tblClient'></table> -->
                </div>
                <div id="tabListSaler" class="divTable" style="height:100%;<?php if ($this->_tpl_vars['type'] != 'Saler'): ?>display:none;<?php endif; ?>">
                    <table class="table table-striped table-hover table-condensed tableInTab" id='tblSaler2'></table>
                    <!-- <table class="table table-striped table-hover table-condensed tableInTab" id='tblSaler'></table> -->
                </div>
            </div>
        </div>
    </div>
  </div>
</body>
</html>