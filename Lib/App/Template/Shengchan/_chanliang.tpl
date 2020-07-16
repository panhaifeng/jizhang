{literal}
<script language="javascript">
$(function(){
    // 机台类型、机速 改变触发
    $('#jtType,#jisu').change(function(){
        // 计算新的标准和单价
        calculateBiaozhun();
        // 计算新的金额
        calculateMoney();
        // 计算新的考勤工资
        calculateMoneyAttend();
    });

    // 转速、单价改变触发
    $('#revolution,#danjiaRe,#biaozhun').change(function(){
        // 计算新的金额
        calculateMoney();
        // 计算新的考勤工资
        calculateMoneyAttend();
    });

    //考勤机台数改变
    $('#numAttend').change(function(){
        // 计算新的考勤工资
        calculateMoneyAttend();
    });
});

// 依据机台类型、机速 来计算标准和单价信息
function calculateBiaozhun(){
    var jtType = $('#jtType').val(),
        jisu = $('#jisu').val(),
        biaozhun = '',
        danjia = '';

    // 初始化数据
    if(jtType=='单面'){
        biaozhun = '11000';
        danjia   = '68';
    }else if(jtType=='提花'){
        biaozhun = '8200';
        danjia   = '75';
    }else if(jtType=='双面'){
        biaozhun = '10200';
        danjia   = '70';
    }

    // 若设置了机速，则按照机速来计算
    if(jisu != ''){
        biaozhun = (parseFloat(jisu)||0)*10*60;
    }

    // 设置变动的信息
    $('#biaozhun').val(biaozhun);
    $('#danjiaRe').val(danjia);
}
// 计算金额  = 下布转速/标准*单价
function calculateMoney(){
    var revolution = parseFloat($('#revolution').val())||0;
    var biaozhun = parseFloat($('#biaozhun').val())||0;
    var danjiaRe = parseFloat($('#danjiaRe').val())||0;
    var money = (revolution/biaozhun*danjiaRe).toFixed(2);
    $('#moneyRe').val(money);
}

// 计算考勤工资 = 考勤机台数*单价
function calculateMoneyAttend(){
    var numAttend = parseFloat($('#numAttend').val()) || 0;
    var danjiaRe = parseFloat($('#danjiaRe').val())||0;
    var money = (numAttend*danjiaRe).toFixed(2);
    $('#moneyAttend').val(money);
}
</script>
{/literal}