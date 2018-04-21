<?php foreach ($tags as $tag):?>
<tr>
<form method="POST" action="/tag/store/<?=$tag->id?>?&page=<?=$page?>&pro=<?=$pro?>">
<td><?=$tag->id?></td>
<td><input type="text" class="form-control" name="row[name]" value="<?=$tag->name?>"></td>
<td><select name="row[pro]" class="form-control">
<?php $this->insert('selection-users', ['data' => $pros, 'slt' => $tag->pro ]) ?>
</select></td>
<td><input type="text" class="form-control" name="row[t_start]" value="<?=$tag->t_start?>" onclick="showcalendar(event, this)"></td>
<td><input type="text" class="form-control" name="row[t_end]" value="<?=$tag->t_end?>" onclick="showcalendar(event, this)"></td>
<td>
<button type="submit" class="btn btn-default">修改</button>
<a href="/tag/vvv?tag=<?=$tag->id?>" class="btn btn-default">查看统计</a>
<a href="/tag/destroy/<?=$tag->id?>" class="btn btn-danger">删除</a>
    </td>
</form>
</tr>
<?php endforeach?>
<tr><td colspan="6" class="text-left">
<?php $this->insert('ajax-page') ?>
</td></tr>
