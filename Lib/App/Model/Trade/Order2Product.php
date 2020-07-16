<?php
load_class('TMIS_TableDataGateway');
class Model_Trade_Order2Product extends TMIS_TableDataGateway {
    var $tableName = 'trade_order2product';
    var $primaryKey = 'id';
    var $belongsTo = array (
        array(
            'tableClass' => 'Model_Jichu_Product',
            'foreignKey' => 'productId',
            'mappingName' => 'Products'
        ),
        array(
            'tableClass' => 'Model_Trade_Order',
            'foreignKey' => 'orderId',
            'mappingName' => 'Order'
        )
    );

    var $hasOne = array (
        array(
            'tableClass' => 'Model_Trade_Shenhe',
            'foreignKey' => 'ord2proId',
            'mappingName' => 'Shenhe',
        )
    );

    // 取得入库成品中某一车间, 某一原料的所有数量
    function getRukuYlCnt($ylId, $chejianId) {
        $initDate = '2009-4-1';		//设定期初日期
        $count = 0;					//本函数返回变量

        //找出含有$ylId的产品
        $modelProduct = FLEA::getSingleton('Model_Jichu_Product');
        $arrProId = $modelProduct->getArrProId($ylId);

        if (count($arrProId)>0) foreach($arrProId as & $v) {
            $condition[] = array('productId', $v['productId']);
            $condition[] = array('Ruku.chejianId', $chejianId);
            $condition[] = array('Ruku.rukuDate', $initDate, '>=');
            $rowset = $this->findAll($condition, null, null, "productId,cnt");
            if (count($rowset)>0) foreach($rowset as & $item) {
                $count += $item['cnt']*$v['ylCnt'];
            }
            $condition = array();
        }
        return $count;
    }

    // 取得入库成品中某一原料的所有数量
    function getRukuYlCntAll($ylId) {
        $initDate	= '2009-4-1';		//设定期初日期
        $count		= 0;				//本函数返回变量

        $modelProduct	= FLEA::getSingleton('Model_Jichu_Product');
        $arrProId		= $modelProduct->getArrProId($ylId);

        if (count($arrProId)>0) foreach($arrProId as & $v) {
            $condition[] = array('productId', $v['productId'], '=');
            $condition[] = array('Ruku.rukuDate', $initDate, '>=');
            $rowset = $this->findAll($condition, null, null, "productId, cnt");
            if (count($rowset)>0) foreach($rowset as & $item) {
                $count += $item['cnt']*$v['ylCnt'];
            }
            $condition = array();
        }
        return $count;
    }

    function getChukuChengpinCnt($orderId){
        $map = array();
        $sql = "SELECT SUM(ckp.cnt) as cnt, ckp.ord2proId
                 FROM cangku_common_chuku2product ckp
                 LEFT JOIN cangku_common_chuku ck ON ck.id = ckp.chukuId
                 WHERE ck.orderId = {$orderId}
                 GROUP BY ckp.ord2proId
            ";
        $res = $this->findBySql($sql);
        foreach ($res as $v){
            $map[$v['ord2proId']] = $v;
        }
        return $map;
    }

    function getChukuChengpinCntJian($orderId){
        $map = array();
        $sql = "SELECT SUM(ckp.cntJian) as cntJian, ckp.ord2proId,sum(ckp.cnt) as cnt
                 FROM cangku_common_chuku2product ckp
                 LEFT JOIN cangku_common_chuku ck ON ck.id = ckp.chukuId
                 WHERE ck.orderId = {$orderId}
                 GROUP BY ckp.ord2proId
            ";
        $res = $this->findBySql($sql);
        foreach ($res as $v){
            $map[$v['ord2proId']] = $v;
        }
        return $map;
    }

    function getRukuChengpinCntJian($orderId){
        $map = array();
        $sql = "SELECT SUM(ckp.cntJian) as cntJian, ckp.ord2proId,sum(ckp.cnt) as cnt
                 FROM cangku_common_ruku2product ckp
                 LEFT JOIN cangku_common_ruku ck ON ck.id = ckp.rukuId
                 WHERE ck.orderId = {$orderId}
                 GROUP BY ckp.ord2proId
            ";
        $res = $this->findBySql($sql);
        foreach ($res as $v){
            $map[$v['ord2proId']] = $v;
        }
        return $map;
    }

    function getFwChengpinCntJian($orderId){
        $map = array();
        $sql = "SELECT SUM(ckp.cntJian) as cntJian, ckp.ord2proId,sum(ckp.cnt) as cnt
                 FROM cangku_fawai2product ckp
                 WHERE ckp.orderId = {$orderId}
                 GROUP BY ckp.ord2proId
            ";
        $res = $this->findBySql($sql);
        foreach ($res as $v){
            $map[$v['ord2proId']] = $v;
        }
        return $map;
    }

    function getOdChengpinCntJian($orderId){
        $map = array();
        $sql = "SELECT SUM(ckp.cntJian) as cntJian, ckp.id as ord2proId,sum(ckp.cntYaohuo) as cnt
                 FROM trade_order2product ckp
                 LEFT JOIN trade_order ck ON ck.id = ckp.orderId
                 WHERE ck.id = {$orderId}
                 GROUP BY ckp.id
            ";
        $res = $this->findBySql($sql);
        foreach ($res as $v){
            $map[$v['ord2proId']] = $v;
        }
        return $map;
    }

    function getCkJAll($orderId){
        $sql = "select sum(r.cntJian) as cntJian,sum(r.cnt) as cnt
                from cangku_common_chuku2product r
                left join cangku_common_chuku k on r.chukuId=k.id
                WHERE k.orderId={$orderId}";
        $res = $this->findBySql($sql);
        return $res;
    }

    function getRkJAll($orderId){
        $sql = "select sum(r.cntJian) as cntJian,sum(r.cnt) as cnt
                from cangku_common_ruku2product r
                left join cangku_common_ruku k on r.rukuId=k.id
                WHERE k.orderId={$orderId}";
        $res = $this->findBySql($sql);
        return $res;
    }

    function getFwJAll($orderId){
        $sql = "select sum(cntJian) as cntJian,sum(cnt) as cnt
                from cangku_fawai2product
                WHERE orderId='{$orderId}'";
        $res = $this->findBySql($sql);
        return $res;
    }

    function getOrdJAll($orderId){
        $sql = "select sum(t.cntJian) as cntJian,sum(t.cntYaohuo) as cnt
                from trade_order2product t
                left join trade_order o on o.id=t.orderId
                WHERE o.id='{$orderId}'";
        $res = $this->findBySql($sql);
        return $res;
    }
}
?>