<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>老板驾驶舱</title>
    {webcontrol type='LoadJsCss' src="Resource/Script/Calendar/WdatePicker.js"}
    {webcontrol type='LoadJsCss' src="Resource/Script/jquery.1.9.1.js"}
    {webcontrol type='LoadJsCss' src="Resource/bootstrap/bootstrap3.0.3/css/bootstrap.css"}
    {webcontrol type='LoadJsCss' src="Resource/bootstrap/bootstrap3.0.3/js/bootstrap.min.js"}
    {webcontrol type='LoadJsCss' src="Resource/Script/BtGrid/btGrid.2.0.js"}
    {webcontrol type='LoadJsCss' src="Resource/Script/BtGrid/btGrid.css"}
    {webcontrol type='LoadJsCss' src="Resource/Script/layer/layer.js"}
    {webcontrol type='LoadJsCss' src="Resource/Script/echart/echarts.min.js"}
<style type="text/css">
{literal}
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
{/literal}
<script type="text/javascript">
var _column = {$_column|@json_encode};
var _column2 = {$_column2|@json_encode};
var chartArr = {$chartArr|@json_encode};
var MonthArr = {$MonthArr|@json_encode};
var type = '{$type}';
var _baseUrl = "?controller=Main&action=BossReport&type=";
{literal}
$(function(){
    // 按钮点击，切换显示表格内容，按钮样式改变
    $('.btnReport').click(function(){
        $('.btnReport').removeClass('active');
        $(this).addClass('active');
        var type = $(this).data('type');
        var year = $('#year').val();
        var url = _baseUrl+type+'&year='+year;
        window.location.href = url;

    });
    // 搜索按钮点击触发重新加载页面
    $('#btnSearch').click(function(){
        $('.active.btnReport').click();
    });

    //构建grid
    $('#tblMonth').btGrid({
        'url':'?controller=Main&action=GetReportData&type=Month'
        ,'columns':_column
        ,'pagination': false
        ,'height':500
        ,'onClickRow':function(rowIndex,rowData){
        }
        ,'onCheckAll':function() {
        }
        ,'onCheck':function(rowIndex,rowData){
        }
        //载入前，需要修改参数,参数中必须带搜索关键字
        //有了这些关键字后，只需要简单的$('#gird').btGrid('refresh')即可
        ,'beforeLoad':function(p) {
          var year = $('#year').val();
          p.year = year;
          return p;
        }
    });

    //构建grid
    $('#tblClient').btGrid({
        'url':'?controller=Main&action=GetReportData&type=Client'
        ,'columns':_column
        ,'pagination': false
        ,'height':500
        ,'onClickRow':function(rowIndex,rowData){
        }
        ,'onCheckAll':function() {
        }
        ,'onCheck':function(rowIndex,rowData){
        }
        //载入前，需要修改参数,参数中必须带搜索关键字
        //有了这些关键字后，只需要简单的$('#gird').btGrid('refresh')即可
        ,'beforeLoad':function(p) {
          var year = $('#year').val();
          p.year = year;
          return p;
        }
    });

    if(chartArr){
       $('#tblClient2').btGrid({
            'columns':_column2
            ,'data':chartArr
            ,'pagination': false
            ,'height':500
            ,'onClickRow':function(rowIndex,rowData){
            }
            ,'onCheckAll':function() {
            }
            ,'onCheck':function(rowIndex,rowData){
            }
            //载入前，需要修改参数,参数中必须带搜索关键字
            //有了这些关键字后，只需要简单的$('#gird').btGrid('refresh')即可
            ,'beforeLoad':function(p) {
              var year = $('#year').val();
              p.year = year;
              return p;
            }
        }); 
    }
    
    $('#tblSaler').btGrid({
        'url':'?controller=Main&action=GetReportData&type=Saler'
        ,'columns':_column
        ,'pagination': false
        ,'height':500
        ,'onClickRow':function(rowIndex,rowData){
        }
        ,'onCheckAll':function() {
        }
        ,'onCheck':function(rowIndex,rowData){
        }
        //载入前，需要修改参数,参数中必须带搜索关键字
        //有了这些关键字后，只需要简单的$('#gird').btGrid('refresh')即可
        ,'beforeLoad':function(p) {
          var year = $('#year').val();
          p.year = year;
          return p;
        }
    });
    
    if(chartArr){
       $('#tblSaler2').btGrid({
            'columns':_column2
            ,'data':chartArr
            ,'pagination': false
            ,'height':500
            ,'onClickRow':function(rowIndex,rowData){
            }
            ,'onCheckAll':function() {
            }
            ,'onCheck':function(rowIndex,rowData){
            }
            //载入前，需要修改参数,参数中必须带搜索关键字
            //有了这些关键字后，只需要简单的$('#gird').btGrid('refresh')即可
            ,'beforeLoad':function(p) {
              var year = $('#year').val();
              p.year = year;
              return p;
            }
        }); 
    }
    if(type=='Client'){
        showBar(MonthArr.xData,MonthArr.valueData,MonthArr.legendData,'chartMonth');
    }

    $('.bind').hover(function() {
        document.getElementById("wxImg").style.display="block";
    }, function() {
        document.getElementById("wxImg").style.display="none";
    });
    $('.focus').hover(function() {
        document.getElementById("focusImg").style.display="block";
    }, function() {
        document.getElementById("focusImg").style.display="none";
    });
});
// 显示柱状图
function showBar(xData,valueData,legendData,obj){
    var myChart = echarts.init(document.getElementById(obj));
    var option = {
        // color: ['#0068B1','#FF595F','#FDC559','#21AD5C','#000000','#A58BBA','#7A5327','#44B5E5'],
        tooltip: {
            trigger: 'axis',
            axisPointer: {
                type: 'shadow'
            }
        },
        legend: {
            data: legendData
        },
        xAxis: [{
            type: 'category',
            data: xData
        }],
        yAxis: [{
            name: '',
            type: 'value'
        }],
        series: valueData
    };
    myChart.setOption(option);
}
/*查看小程序绑定的二维码*/
function viewDetails(){
    var url='?controller=Main&action=ViewMiniCode';
    $.layer({
        type: 2,
        shade: [1],
        fix: false,
        title: '小程序二维码',
        maxmin: false,
        iframe: {src : url},
        // border:false,
        area: ['600px' , '500px']
    });
}
{/literal}
</script>
</head>
<body>
  <div class="row cal">
    <div class="col-md-12" >
<!--         {if $canShowBoss.isTrialVer}
        <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
            <span class="sr-only">Error:</span>
            剩余使用天数【{$canShowBoss.days}天】，试用到期后将关闭，正式启用请联系您的专属客服！
        </div>
        {/if}
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
                      <button type="button" class="btn btn-default btnReport {if $type=='Client'}active{/if}" data-type='Client'>按客户汇总</button>
                      <button type="button" class="btn btn-default btnReport {if $type=='Saler'}active{/if}" data-type='Saler'>按业务员汇总</button>
                    </div>
                    <div class="pull-right">
                        <button type="button" class="btn btn-default" id='btnSearch'><span class="glyphicon glyphicon-search"></span>&nbsp;搜索</button>
                    </div>
                    <div class="pull-right">
                        <select class="form-control" id='year' name='year'>
                            {foreach from=$yearArr item=item}
                            <option value='{$item}' {if $arr_condition.year == $item} selected {/if}>{$item}</option>
                            {/foreach}
                        </select>
                    </div>
                </div>
            </div>
            <div class="panel-body" style="padding:10px;border-top:1px solid #ccc;">
                <div id="tabListClient" class="divTable" style="height:100%;{if $type!='Client'}display:none;{/if}">
                    <div id="chartMonth" class="divChart" style="height:300px;">
                    </div>
                    <table class="table table-striped table-hover table-condensed tableInTab" id='tblClient2'></table>
                    <!-- <table class="table table-striped table-hover table-condensed tableInTab" id='tblClient'></table> -->
                </div>
                <div id="tabListSaler" class="divTable" style="height:100%;{if $type!='Saler'}display:none;{/if}">
                    <table class="table table-striped table-hover table-condensed tableInTab" id='tblSaler2'></table>
                    <!-- <table class="table table-striped table-hover table-condensed tableInTab" id='tblSaler'></table> -->
                </div>
            </div>
        </div>
    </div>
  </div>
</body>
</html>