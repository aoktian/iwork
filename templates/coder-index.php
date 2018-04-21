<?php $this->layout('layouts/dashboard', ['title' => '生产力']) ?>
<?php $this->start('main') ?>

<div class="row line"> <div class="col-lg-6">
<form class="form-inline" role="form" method="GET" action="/coder/intime">
<div class="form-group"> <div class="input-group">
<span class="input-group-addon">开始</span>
<input value="<?=$t_start_show?>" name="t_start" class="form-control" type="text" onclick="showcalendar(event, this)">
</div></div>

<div class="form-group"> <div class="input-group">
<span class="input-group-addon">结束</span>
<input value="<?=$t_end_show?>" name="t_end" class="form-control" type="text" onclick="showcalendar(event, this)">
</div></div>

<button class="btn btn-success"><span class="glyphicon glyphicon-stats" aria-hidden="true"></span> 查询</button>
</form>
</div>

</div>

<?php if (isset($coders)) {?>
<div class="row"> <div class="col-lg-6">
    <table class="table table-bordered table-striped table-hover text-center">
<thead>
<tr>
<th class="text-center">姓名</th>
<th class="text-center">需求</th>
<th class="text-center">改进</th>
<th class="text-center">BUG</th>
        </tr>
      </thead>
      <tbody>
<?php
$departments_coder = array();
foreach ($users as $user) {
    if (!isset($coders[$user->id])) {
        continue;
    }
    $one = $coders[$user->id];

    if (!isset($departments_coder[$user->department])) {
        $departments_coder[$user->department] = array();
    }

    $departments_coder[$user->department][$user->id] = $one;
}

foreach ($departments_coder as $department_id => $department_coder) {
    $arrsort = array();
    foreach ($department_coder as $uid => $one) {
        $arrsort[$uid] = $one[10] + $one[20];
    }
    arsort($arrsort);
    foreach ($arrsort as $uid => $score) {
        $one = $department_coder[$uid];
?>
<tr>
<td><?=$users[$uid]->name?></td>
<td><?=$one[10]?></td>
<td><?=$one[20]?></td>
<td><?=$one[30]?></td>
</tr>
<?php } ?>
<tr>
<td colspan="4"></td>
</tr>
<?php } ?>
</tbody>
</table>
</div>
</div>

<?php }?>

<?php $this->end() ?>
