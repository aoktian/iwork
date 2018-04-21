<?php $this->layout('layouts/dashboard', ['title' => '字段管理']) ?>
<?php $this->start('main') ?>

<?php
$titlecatys = I\Setting::get('worktime', 'title');
?>

<div class="row">
    <div class="col-lg-12">
<div class="panel panel-default">
    <div class="panel-heading">
        删除 <?=$title->name?>
    </div>
    <div class="panel-body">

<div class="alert alert-danger" role="alert">
<strong>如果删除本条目，那么任务下的属性也需要改变</strong>
</div>

<div class="line"></div>

<form class="form-inline" role="form" method="POST" action="/title/destroy">
<input type="hidden" name="id" value="<?=$title->id?>">
<div class="form-group">
    <div class="input-group">
        <span class="input-group-addon">选择转移目标</span>
<select itag="val" name="toid" class="form-control">
<?php $this->insert('selection-users', ['data' => $titles, 'slt' => 0 ]) ?>
</select>
    </div>
</div>

<button type="submit" class="btn btn-primary">提交</button>

</form>

    </div>
    <!-- /.panel-body -->
</div>
    </div>
</div>

<?php $this->end('main') ?>
