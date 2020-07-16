    <div class="form-group">
        <label for="clientName"  class="col-sm-3 control-label lableMain">{$item.title|default:"选择部门"}:</label>
        <div class="col-sm-7">
          {webcontrol type='btdepselect' value=$item.value clientName=$item.clientName itemName=$item.name|default:$key}
        </div>
    </div>