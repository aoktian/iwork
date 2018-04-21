<div class="row">

  <div class="col-lg-12">
<div class="form-inline" id="changemoreform">
    <div class="form-group">
    <div class="input-group">
        <span class="input-group-addon">状态</span>
<select itag="val" name="changeto[status]" class="form-control">
<option value="0">不修改</option>
<?php $this->insert('selection', ['data' => I\Setting::get('worktime', 'status'), 'slt' => 0 ]) ?>
</select>
    </div>
    </div>

    <div class="form-group">
    <div class="input-group">
        <span class="input-group-addon">优先级</span>
<select itag="val" name="changeto[priority]" class="form-control">
<option value="0">不修改</option>
<?php $this->insert('selection', ['data' => $prioritys, 'slt' => 0 ]) ?>
</select>
    </div>
    </div>

    <div class="form-group">
    <div class="input-group">
        <span class="input-group-addon">部门</span>
<select class="form-control" onchange="onChangeDepartment( this.value, '#changemore-leaders' )">
<option value="0">不修改</option>
<?php $this->insert('selection-users', ['data' => $departments, 'slt' => 0 ]) ?>
</select>
    </div>
    </div>

    <div class="form-group">
    <div class="input-group">
        <span class="input-group-addon">执行</span>
<select itag="val" name="changeto[leader]" class="form-control" id="changemore-leaders">
<option value="0">不修改</option>
<?php $this->insert('selection-users', ['data' => $users, 'slt' => 0 ]) ?>
</select>
    </div>
    </div>

    <div class="form-group">
    <div class="input-group">
        <span class="input-group-addon">验收</span>
<select itag="val" name="changeto[tester]" class="form-control">
<option value="0">不修改</option>
<?php $this->insert('selection-users', ['data' => $users, 'slt' => 0 ]) ?>
</select>
    </div>
    </div>

    <div class="form-group">
    <div class="input-group">
        <span class="input-group-addon">项目</span>
<select class="form-control" onchange="onChangePro(this.value, '#changemore-tags');">
<option value="0">不修改</option>
<?php $this->insert('selection-users', ['data' => $pros, 'slt' => 0 ]) ?>
</select>
    </div>
    </div>

    <div class="form-group">
    <div class="input-group">
        <span class="input-group-addon">版本</span>
<select itag="val" name="changeto[tag]" class="form-control" id="changemore-tags">
<option value="0">不修改</option>
</select>
    </div>
    </div>

    <button onclick="changeMore( );" class="btn btn-danger">批量修改</button>

</div>

  <p></p>
  </div>

</div>
