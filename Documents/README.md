## 权限

## 财务   [caiwu]

### 应收款报表 []
说明::
#### 原版本
#### v0.1 [更新时间2017年03月15日]
    1. 支持查看带发票信息的对账单


### 客户对账单  [client_duizhang]
说明:: 按照客户对过账单进行的汇总的应收款单据（并将出库、收款、发票等信息进行关联）
#### 原版本
    1. 检索条件：时间区间（过账单以过账时间为线索， 收款信息以收款时间为线索，出库信息与过账单对应）
    2. 需要统计期初信息（时间区间前发生的账目）
    3. 报表记录，将罗列时间区间内的过账和收款。
    4. 过账的信息（应收款项）显示 过账关联的出库日期
    5. 收款的信息（已收款项）显示 收款信息的收款日期
    
#### 现有版本的变动
   ##### v0.1 [更新时间2017年03月14日]
    原由:过账中支持了其他过账的录入。（其他过账没有出库日期）
    约定:过账日期会与纸质的送货日期一致（出库日期与送货日期之间，存在偏差），对账单中需要关注 过账日期。
    1. 过账的信息（应收款项）不再显示过账关联的出库日期，显示过账日期
    2. 其他过账需要支持输入 出库日期。
  ##### v0.2  [更新时间2017年03月15日]
   原由: TaskId:1847,调整对账单格式
   约定: 描述中，仅需要显示 「门幅」「克重」即可
   1. 「要货数」「币种」「单价」「应收(RMB)」不显示
   2. 增加，「开票日期」「开票金额」