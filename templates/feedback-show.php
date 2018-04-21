<?php $this->layout('layouts/plane', ['title' => $task->title]) ?>
<?php $this->start('body') ?>

<div class="line"></div>
<?php if ($logs) : ?>
<div class="panel panel-default">
    <div class="panel-heading">
<h1 id="title">#<?=$task->id?> <?=$task->title?></h1>
    </div>
    <div class="panel-body">

<table class="table table-striped">
<thead>
<tr>
<td width="50">
<i onclick="setfeedbackcontent( <?=$feedback->id?>);" class="glyphicon glyphicon-repeat hand"></i>
</td>
<td width="50">#</td>
<td width="155">时间</td>
<td width="100">操作</td>
<td>内容</td>
</tr>
</thead>
<tbody id="feedbacklogs">
<?php foreach ($logs as $log) :?>
<tr>
<td>
    <input onclick="checklog(this, 'feedback', 'feedback-<?=$feedback->id?>');" type="checkbox" value="<?=$log->id?>">
</td>
<td> <?=$log->id?> </td>
<td> <?=$log->created_at?> </td>
<td> <?=$log->changer?> </td>
<td> <a href="javascript:diff( 'feedback', <?=$log->id?>, <?=$feedback->id?>, 'feedback-<?=$feedback->id?>' );" class="" target="_blank">和当前对比</a> </td>
</tr>
<?php endforeach ?>
</tbody>
</table>

</div>
</div>
<div class="line"></div>
<?php endif ?>

<?php $this->insert('feedback-one', [ 'feedback' => $feedback, 'users' => $users ]) ?>

<?php $this->end() ?>

