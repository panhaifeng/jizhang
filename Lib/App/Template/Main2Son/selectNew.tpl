<div class="col-xs-4">
  <div class="form-group">
    <label for="{$item.name|default:$key}" class="col-sm-3 control-label lableMain">{$item.title}:</label>
    <div class="col-sm-9">{webcontrol type='btselectNew' model=$item.model options=$item.options value=$item.value disabled=$item.disabled orderBy=$item.orderBy itemName=$item.name|default:$key}      
    </div>
  </div>
</div>