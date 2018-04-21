<?php $this->layout('layouts/dashboard') ?>

<?php $this->start('main') ?>

<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">权限列表</h3>
  </div>
  <div class="panel-body">

<div class="list-group">
<?php foreach($permissions as $value): ?>
    <li class="list-group-item"><?=$this->e($value) ?></li>
<?php endforeach ?>
</div>

  </div>
</div>

<?php $this->end() ?>
