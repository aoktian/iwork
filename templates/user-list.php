<?php $this->layout('layouts/dashboard', ['title' => '成员管理']) ?>
<?php $this->start('main') ?>

<div class="row line">
	<div class="col-lg-6">
		<table class="table table-bordered table-striped table-hover  vertical-middle text-center">
			<thead>
				<tr>
<th width="50">#id</th>
<th width="80"> Email </th>
<th width="80"> 姓名 </th>
<th width="80"> 部门 </th>
<th>
操作
</th>
				</tr>
			</thead>
			<tbody>
<?php
foreach ($users as $user) { ?>
<tr>
	<td><?=$user->id?></td>
    <td><?=$user->email?></td>
    <td><?=$user->name?></td>
	<td><?=$departments[$user->department]->name?></td>
	<td>
<!-- Button trigger modal -->
<button onclick="delid = <?=$user->id?>;" type="button" class="btn btn-link" data-toggle="modal" data-target="#deluserconfirm">
  删除
</button>
	</td>
</tr>
<?php } ?>
			</tbody>
		</table>

	</div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="deluserconfirm">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">删除角色</h4>
      </div>
      <div class="modal-body">
        <p>把他的任务转移给其他人，包括负责的任务和发布的任务</p>
<div class="input-group input-group-lg line">
  <span class="input-group-addon">部门：</span>
<select class="form-control" onchange="onChangeDepartment( this.value )">
<option value="0">选择部门</option>
<?php $this->insert('selection-users', ['data' => $departments, 'slt' => 0 ]) ?>
</select>
</div>

<div class="input-group input-group-lg">
  <span class="input-group-addon">转到：</span>
<select class="form-control"  id="leaders">
<?php $this->insert('selection-users', ['data' => $users, 'slt' => 0 ]) ?>
</select>
</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="deluser();">Save changes</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php $this->end()?>

<?php $this->start('js')?>
<script type="text/javascript">
var delid = 0;
var users = <?=json_encode($users)?>;

function deluser( ) {
  var toid = $('#leaders').val();
  if (parseInt(toid) <= 0) {
    alert("没有选择把他的任务转移给谁？");
    return;
  }
  window.location.href= '/user/del/' + delid + '/' + toid;
}
</script>


<?php $this->end()?>
