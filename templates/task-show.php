<?php $this->layout('layouts/plane', ['title' => $task->title]) ?>
<?php $this->start('body') ?>

<div class="line"></div>

<div class="panel panel-default">
    <div class="panel-heading">
  <h1 id="title">#<?=$task->id?> <?=$task->title?></h1>
    </div>
    <div class="panel-body">
<p>
作者：<?=$users[$task->author]->name?> (<?=$task->created_at?>)
&nbsp;&nbsp;&nbsp;&nbsp;
修改：<?=$users[$task->changer]->name?> (<?=$task->updated_at?>)
</p>

<hr />

<div id="taskinfo">


<div class="form-inline line">

<div class="input-group">
<span class="input-group-addon">标题</span>
<input style="width:500px;" itag="val" id="task-title" name="row[title]" type="text" class="form-control" value="<?=str_replace(array('\'', '"'), array('&apos;', '&quot;'), $task->title)?>">
</div>

<div class="input-group">
<span class="input-group-addon">类型</span>
<select id="caty" itag="val" name="row[caty]" class="form-control">
<?php $this->insert('selection-users', ['data' => $catys, 'slt' => $task->caty ]) ?>
</select>
</div>

<div class="input-group">
<span class="input-group-addon">优先级</span>
<select itag="val" name="row[priority]" class="form-control">
<?php $this->insert('selection', ['data' => I\Setting::get('worktime', 'priority'), 'slt' => $task->priority ]) ?>
</select>
</div>

<div class="input-group">
<span class="input-group-addon">状态</span>
<select itag="val" name="row[status]" class="form-control">
<?php $this->insert('selection', ['data' => I\Setting::get('worktime', 'status'), 'slt' => $task->status ]) ?>
</select>
</div>

<div class="input-group">
<span class="input-group-addon">线上</span>
<select itag="val" name="row[isonline]" class="form-control">
<?php $this->insert('selection', ['data' => [0 => '否', 1 => '是'], 'slt' => $task->isonline]) ?>
</select>
</div>

</div>

<div class="form-inline line">

<div class="input-group">
<span class="input-group-addon">项目</span>
<select class="form-control" onchange="onChangePro(this.value);">
<?php $this->insert('selection-users', ['data' => $pros, 'slt' => $task->pro ]) ?>
</select>
</div>

<div class="input-group">
<span class="input-group-addon">版本</span>
<select itag="val" name="row[tag]" class="form-control" id="tags">
<?php foreach ($tags as $tag) :?>
<?php if ($tag->pro == $task->pro) :?>
<option value="<?=$tag->id?>" <?=$tag->id == $task->tag ? 'selected' : ''?>><?=$tag->name?></option>
<?php endif ?>
<?php endforeach?>
</select>
</div>

<div class="input-group">
<span class="input-group-addon">部门</span>
<select class="form-control" onchange="onChangeDepartment( this.value )">
<?php $this->insert('selection-users', ['data' => $departments, 'slt' => $task->department ]) ?>
</select>
</div>

<div class="input-group">
<span class="input-group-addon">执行</span>
<select itag="val" name="row[leader]" class="form-control" id="leaders">
<?php foreach ($users as $user) :?>
<?php if ($user->department == $task->department) :?>
<option value="<?=$user->id?>" <?=$user->id == $task->leader ? 'selected' : ''?>><?=$user->name?></option>
<?php endif ?>
<?php endforeach?>
</select>
</div>

<div class="input-group">
<span class="input-group-addon">验收</span>
<select itag="val" name="row[tester]" class="form-control" id="leaders">
<option value="0" >无</option>
<?php $this->insert('selection-users', ['data' => $users, 'slt' => $task->tester ]) ?>
</select>
</div>


<div class="input-group">
<span class="input-group-addon">限期</span>
<input readonly onclick="showcalendar(event, this, true)" itag="val" name="row[deadline]" type="text" class="form-control" value="<?=date('Y-m-d H:i:s', $task->deadline) ?>">
</div>

<button onclick="updateTaskOnchange(<?=$task->id?>);" class="btn btn-danger"><span class="glyphicon glyphicon-wrench"></span> 修改属性</button>
</div>

</div>

<?php if ($logs) : ?>
<div class="line"></div>
<hr/>
<table class="table table-striped">
<thead>
<tr>
<td width="50">
<i onclick="settaskcontent( <?=$task->id?>);" class="glyphicon glyphicon-repeat hand"></i>
</td>
<td width="100">#</td>
<td width="155">时间</td>
<td width="100">操作</td>
<td width="80">状态</td>
<td width="80">负责人</td>
<td width="100">项目</td>
<td width="100">版本</td>
<td width="50">类型</td>
<td>标题</td>
<td width="100">内容</td>
<td width="80">部门</td>
<td width="80">验收人</td>
<td width="50">等级</td>
<td width="155">期限</td>
</tr>
</thead>
<tbody id="tasklogs">
<?php foreach ($logs as $log) :?>
<tr>
<td>
    <input <?=(!$log->content ? 'disabled' : '')?> onclick="checklog( this, 'task', 'taskcontent');" type="checkbox" value="<?=$log->id?>">
</td>
<td> <?=$log->id?> </td>
<td> <?=$log->created_at?> </td>
<td> <?=$log->changer?> </td>
<td> <?=$log->status?> </td>
<td> <?=$log->leader?> </td>
<td> <?=$log->pro?> </td>
<td> <?=$log->tag?> </td>
<td> <?=$log->caty?> </td>
<td> <?=$log->title?> </td>
<td>
<?php if ($log->content):?>
<a href="javascript:diff( 'task', <?=$log->id?>, <?=$task->id?>, 'taskcontent' );" class="" target="_blank">和当前对比</a>
<?php endif ?></td>
<td> <?=$log->department?> </td>
<td> <?=$log->tester?> </td>
<td> <?=$log->priority?> </td>
<td> <?=$log->deadline?date('Y-m-d H:i:s', $log->deadline):''?> </td>
</tr>
<?php endforeach ?>
</tbody>
</table>
<?php endif ?>

    </div><!-- /.panel-body -->
</div>


<div class="panel panel-default">

<div class="panel-heading">
<div class="form-inline line">

<div class="form-group"> <div class="input-group">
<span class="input-group-addon">关联任务：</span>
<input id="relatedid" class="form-control" type="text" placeholder="输入编号">
</div></div>

<button class="btn btn-primary margin-right" onclick="addRelated();"><span class="glyphicon glyphicon-random"></span> 确定</button>
<a href="/task/create/0/<?=$task->id?>" target="_blank" class="btn btn-success margin-right"><span class="glyphicon glyphicon-tasks"></span> 新增相关任务</a>

<button class="btn btn-default margin-right" onclick="refreshRelated();"><span class="glyphicon glyphicon-repeat"></span> </button>

</div>
</div>

<?php if ($tasks) {?>
<div class="panel-body">
<table class="table table-striped text-center">
<?php $this->insert('task-list-table') ?>
</table>
</div>

<div class="panel-footer">
<div id="taskfilter">
<input type="hidden" itag="val" name="search[related]" value="<?=$task->id?>" >
</div>
<input type="hidden" id="stitle">
<?php $this->insert('task-changemore') ?>
</div>

<?php } ?>

</div>

<div class="panel panel-default">
<div class="panel-heading">
<a href="/task/create/<?=$task->id?>" class="btn btn-primary"><span class="glyphicon glyphicon-pencil"></span> 编辑内容</a>
</div>
<div class="panel-body">
<div id="taskcontent"> <?=$task->content?> </div>
</div><!-- /.panel-body -->
<div class="panel-footer">
<a href="/task/create/<?=$task->id?>" class="btn btn-primary"><span class="glyphicon glyphicon-pencil"></span> 编辑内容</a>
</div>
</div>

<?php $this->insert('task-feedback') ?>



<?php $this->end() ?>


<?php $this->start('js') ?>
<script type="text/javascript">
function addRelated() {
    var related = $("#relatedid").val();

    $.ajax({
        data: "id=<?=$task->id?>&related=" + related,
        url:'/task/related'
    }).done(function(data){
        $("#tasklist").html( data );
    });

    $("#relatedid").val("");
}
function refreshRelated() {
    $.ajax({
        data: "id=<?=$task->id?>",
        url:'/task/related'
    }).done(function(data){
        $("#tasklist").html( data );
    });
}
</script>
<?php $this->end() ?>
