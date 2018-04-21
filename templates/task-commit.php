<?php $this->layout('layouts/plane', ['title' => '提交新任务']) ?>
<?php $this->start('body') ?>

<?php
if (is_null($task)) {
    $task = (object)array();
    $task->id = 0;
    $task->title = '没有标题的标题';
    $task->caty = 0;
    $task->priority = 0;
    $task->department = 0;
    $task->leader = 0;
    $task->pro = 0;
    $task->tag = 0;
    $task->tester = I\Request::$auth->id;
    $task->deadline = 0;
    $task->content = '';
}
?>
<h1><?=($related ? sprintf('上级任务：#%s %s', $related->id, $related->title) : '提交新任务')?></h1>
<hr />

<form method="POST" action="/task/store" onsubmit="return oncommit( );">
<input type="hidden" name="id" value="<?=$task->id?>" />
<input type="hidden" id="taskContent" name="row[content]">
<input type="hidden" name="row[related]" value="<?=($related ? $related->id : 0)?>">

<div class="row line">
  <div class="col-lg-12">
<div class="form-inline">
    <div class="form-group">
    <div class="input-group">
        <span class="input-group-addon">优先级</span>
        <select name="row[priority]" class="form-control">
<?php $this->insert('selection', ['data' => I\Setting::get('worktime', 'priority'), 'slt' => $task->priority ]) ?>
        </select>
    </div>
    </div>

    <div class="form-group">
    <div class="input-group">
        <span class="input-group-addon">类型</span>
        <select id="caty" name="row[caty]" class="form-control">
<?php $this->insert('selection-users', ['data' => $catys, 'slt' => $task->caty ]) ?>
        </select>
    </div>
    </div>

    <div class="form-group">
    <div class="input-group">
      <span class="input-group-addon">标题</span>
      <input id="task-title" name="row[title]" type="text" style="width:380px" class="form-control" value="<?=str_replace(array('\'', '"'), array('&apos;', '&quot;'), $task->title)?>">
    </div>
    </div>

    <div class="form-group">
    <div class="input-group">
        <span class="input-group-addon">线上</span>
        <select name="row[isonline]" class="form-control">
<?php $this->insert('selection', ['data' => [0 => '否', 1 => '是'], 'slt' => 0 ]) ?>
        </select>
    </div>
    </div>
  </div></div>
</div>

<div class="row line">
  <div class="col-lg-12">
<div class="form-inline">

    <div class="form-group">
    <div class="input-group">
        <span class="input-group-addon">部门</span>
        <select class="form-control" onchange="onChangeDepartment( this.value )">
<option value="0">选择部门</option>
<?php $this->insert('selection-users', ['data' => $departments, 'slt' => $task->department ]) ?>
        </select>
    </div>
    </div>

    <div class="form-group">
    <div class="input-group">
        <span class="input-group-addon">负责人</span>
        <select name="row[leader]" class="form-control" id="leaders">
<option value="0">未选部门</option>
<?php $this->insert('selection-users', ['data' => $users, 'slt' => $task->leader ]) ?>
        </select>
    </div>
    </div>

    <div class="form-group">
    <div class="input-group">
        <span class="input-group-addon">项目</span>
<select class="form-control" onchange="onChangePro(this.value);">
<option value="0">选择项目</option>
<?php $this->insert('selection-users', ['data' => $pros, 'slt' => $task->pro ]) ?>
</select>
    </div>
    </div>

    <div class="form-group">
    <div class="input-group">
        <span class="input-group-addon">版本</span>
        <select name="row[tag]" class="form-control" id="tags">
<option value="0">未选项目</option>
<?php $this->insert('selection-users', ['data' => $tags, 'slt' => $task->tag ]) ?>
        </select>
    </div>
    </div>

<div class="form-group">
<div class="input-group">
    <span class="input-group-addon">验收</span>
    <select name="row[tester]" class="form-control" id="leaders">
<option value="0">未选</option>
<?php $this->insert('selection-users', ['data' => $users, 'slt' => $task->tester ]) ?>
    </select>
</div>
</div>

    <div class="form-group">
    <div class="input-group">
        <span class="input-group-addon">限期</span>
<input onclick="showcalendar(event, this, true)" name="row[deadline]" type="text" class="form-control" value="<?=date('Y-m-d H:i:s', $task->deadline ? $task->deadline : time() + 86400 * 7 ) ?>">
    </div>
    </div>

  </div></div>
</div>

<div class="row">
  <div class="col-lg-12">
    <div class="form-group">
      <textarea id="summernote" height="500"><?=$task->content?></textarea>
    </div>
  </div>
</div>

<div class="row">
    <div class="col-sm-4">
        <button type="submit" class="btn btn-danger btn-lg btn-block"> 提 交 </button>
    </div>
</div>

</form>

<p></p>

<?php $this->end() ?>


<?php $this->start('js') ?>
<script type="text/javascript">
var users = <?=json_encode($users)?>;
var tags = <?=json_encode($tags)?>;

$(document).ready(function( ) {
  initEditor( "summernote" );
});

function oncommit( ) {

  if ($("#leaders").val() <= 0) {
    alert('没有选择部门或者负责人');
    return false;
  }

  if ($("#tags").val() <= 0) {
    alert('没有选择项目或者版本');
    return false;
  }

  $('#taskContent').val( $('#summernote').summernote( 'code' ) );
  return true;
}

</script>
<?php $this->end() ?>
