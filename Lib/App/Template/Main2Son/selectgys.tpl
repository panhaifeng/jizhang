
 <div class="col-xs-4">
  <div class="form-group">
    <label for="{$item.name|default:$key}" class="col-sm-3 control-label lableMain">{$item.title}:</label>
    <div class="col-sm-9">{webcontrol type='btselectgys' model=$item.model disabled=$item.disabled options=$item.options value=$item.value itemName=$item.name|default:$key}     
    </div>
  </div>
</div>