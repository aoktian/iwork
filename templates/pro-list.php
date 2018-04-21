<?php $this->layout('layouts/dashboard', ['title' => '项目管理']) ?>
<?php $this->start('main') ?>


<div class="row">
	<div class="col-lg-12">
<div class="panel panel-default">
    <div class="panel-heading">
        添加项目
    </div>
    <div class="panel-body">
<form class="form-inline" role="form" method="POST" action="/pro/store">
<div class="form-group">
<label>名称：</label>
<input type="text" class="form-control" name="row[name]" />
</div>

<button type="submit" class="btn btn-primary">添加</button>

</form>

    </div>
    <!-- /.panel-body -->
</div>
	</div>
</div>

<div class="row">
	<div class="col-lg-12">
		<table class="table table-bordered table-striped table-hover vertical-middle">
			<thead>
				<tr>
<th width="50">#id</th>
<th width="120"> 名称 </th>
<th width="200"> 创建时间 </th>
<th> 操作 </th>
				</tr>
			</thead>
			<tbody>
<?php foreach ($pros as $pro):?>
<tr>
<form method="POST" action="/pro/store">
<input type="hidden" name="id" value="<?=$pro->id?>">
	<td><?=$pro->id?></td>
<td>
  <input type="text" class="form-control" name="row[name]" value="<?=$pro->name?>">
</td>
  <td><?=$pro->created_at?></td>
	<td>
<button type="submit" class="btn btn-default">修改名字</button>
<a href="/pro/destroy/<?=$pro->id?>" class="btn btn-danger">删除</a>
	</td>
</form>
</tr>
<?php endforeach?>
			</tbody>
		</table>

	</div>
</div>

<?php $this->end( ) ?>
