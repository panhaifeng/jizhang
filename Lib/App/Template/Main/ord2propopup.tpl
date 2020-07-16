
<div class="form-group">
    <label for="orderName"  class="col-sm-3 control-label lableMain">{$item.title|default:"名称"}:</label>
    <div class="col-sm-7">
      {webcontrol type='btor2propopup' value=$item.value itemName=$item.name|default:$key}
    </div>
</div>

