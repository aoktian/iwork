<?php $this->layout('layouts/dashboard', ['title' => '任务统计']) ?>
<?php $this->start('main') ?>


<h1><?=$tag->name?> [<?=date('Y-m-d', strtotime($tag->t_start))?>] - [<?=date('Y-m-d', strtotime($tag->t_end))?>]</h1>
<hr />

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

<?php $this->end() ?>
