<?php $this->layout('layouts/dashboard', ['title' => '版本计划']) ?>
<?php $this->start('main') ?>


<div class="panel panel-default">
    <div class="panel-heading">
        添加版本计划
    </div>
    <div class="panel-body">
<form class="form-inline" role="form" method="POST" action="/tag/store">
<div class="form-group">
<label>名称：</label>
<input type="text" class="form-control" name="row[name]" />
</div>
<div class="form-group">
<label>项目：</label>
<select name="row[pro]" class="form-control">
<?php $this->insert('selection-users', ['data' => $pros, 'slt' => $task->pro ]) ?>
</select>
</div>
<div class="form-group">
<label>开始：</label>
<input name="row[t_start]" class="form-control" type="text" onclick="showcalendar(event, this)">
</div>
<div class="form-group">
<label>结束：</label>
<input name="row[t_end]" class="form-control" type="text" onclick="showcalendar(event, this)">
</div>

<button type="submit" class="btn btn-primary">添加</button>

</form>

    </div>
    <!-- /.panel-body -->
</div>


<div class="row"> <div class="col-lg-12">

<div class="form-inline">

<div class="form-group"> <div class="input-group">
<span class="input-group-addon">筛选条件：</span>
<select onchange="topage( 1 );" id="pro" class="form-control">
<option value="0">项目</option>
<?php $this->insert('selection-users', ['data' => $pros, 'slt' => $pro ]) ?>
</select>
</div></div>

</div>

</div></div>
<div class="line"></div>


<div class="row">
	<div class="col-lg-12">
		<table class="table table-bordered table-striped table-hover vertical-middle">
			<thead>
				<tr>
<th width="50">#id</th>
<th width="200"> 名称 </th>
<th width="200"> 项目 </th>
<th width="200"> 开始时间 </th>
<th width="200"> 结束时间 </th>
<th> 操作 </th>
				</tr>
			</thead>
			<tbody id="tag-list">
<?php $this->insert('tag-list-content') ?>
			</tbody>
		</table>

	</div>
</div>

<?php $this->end( ) ?>

<?php $this->start('js') ?>

<script type="text/javascript">
function topage( page ) {
  var s = "page=" + page + "&pro=" + $( "#pro" ).val();
  $.ajax({
    data: s,
    type: "GET",
    url: '/tag/page',
    cache: false,
    success: function( res ) {
      $("#tag-list").html( res );
    }
  });
}
</script>
<?php $this->end( ) ?>
