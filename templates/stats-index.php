<?php $this->layout('layouts/dashboard', ['title' => '数量统计']) ?>
<?php $this->start('main') ?>

<div class="alert alert-success">
<div class="row">

<div class="col-lg-6">
<form class="form-inline" role="form" method="GET" action="/stats/tag">

<div class="form-group"> <div class="input-group">
<span class="input-group-addon">筛选条件：</span>
<select onchange="onFilterChangePro( this.value );" name="pro" class="form-control">
<option value="0">项目</option>
<?php $this->insert('selection-users', ['data' => $pros, 'slt' => isset($pro_slt) ? $pro_slt : 0 ]) ?>
</select>
</div></div>

<div class="form-group">
<select name="tag" class="form-control" id="filterTags">
<option value="0">版本</option>
<?php $this->insert('selection-users', ['data' => $tags, 'slt' => isset($tag_slt) ? $tag_slt : 0 ]) ?>
</select>
</div>


<button class="btn btn-success"><span class="glyphicon glyphicon-stats" aria-hidden="true"></span> 根据版本查询</button>
</form>
</div>


<div class="col-lg-6">
<form class="form-inline" role="form" method="GET" action="/stats/intime">

<div class="form-group"> <div class="input-group">
      <span class="input-group-addon">开始</span>
<input value="<?=(isset($t_start_show) ? $t_start_show : '')?>" name="t_start" class="form-control" type="text" onclick="showcalendar(event, this)">
</div></div>

<div class="form-group"> <div class="input-group">
      <span class="input-group-addon">结束</span>
<input value="<?=(isset($t_end_show) ? $t_end_show : '')?>" name="t_end" class="form-control" type="text" onclick="showcalendar(event, this)">
</div></div>


<button class="btn btn-success"><span class="glyphicon glyphicon-stats" aria-hidden="true"></span> 根据时间查询</button>
</form>
</div>



</div>
</div>

<?php if (isset($s_all)) {?>
<div class="row">
  <div class="col-lg-12">
    <table class="table table-bordered table-striped table-hover">
      <thead>
        <tr>
<th width="100">#id</th>
<th width="80">总数</th>
<th width="80">新增</th>
<th width="80">处理</th>
<th width="80">已解决</th>
<th width="80">可测试</th>
<th width="80">已通过</th>
<th width="80">完成</th>
<th>进度</th>
        </tr>
      </thead>
      <tbody>
<tr>
    <td>全部</td>
<?php $this->insert('tag-statistics-td', ['one' => $s_all ]) ?>
</tr>
<tr> <td colspan="11"></td> </tr>
<?php foreach ($s_department as $id => $one) {?>
<tr>
  <td><?php echo $departments[$id]->name; ?></td>
<?php $this->insert('tag-statistics-td', ['one' => $one ]) ?>
</tr>
<?php } ?>

<tr> <td colspan="11"></td> </tr>
<?php foreach ($s_pro as $id => $one) {?>
<tr>
    <td><?php echo $pros[$id]->name; ?></td>
<?php $this->insert('tag-statistics-td', ['one' => $one ]) ?>
</tr>
<?php } ?>

<tr> <td colspan="11"></td> </tr>
<?php foreach ($s_leader as $id => $one) {?>
<tr>
    <td><?php echo $users[$id]->name; ?></td>
<?php $this->insert('tag-statistics-td', ['one' => $one ]) ?>
</tr>
<?php } ?>


      </tbody>
    </table>
  </div>
</div>


<?php }?>

<?php $this->end() ?>

<?php $this->start('js') ?>

<script type="text/javascript">
var tags = <?=json_encode($tags)?>;
</script>
<?php $this->end() ?>
