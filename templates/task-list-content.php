<?php
$status = I\Setting::get('worktime', 'status');
$textcolor = array();
$a = array( 'text-normal', 'text-primary', 'text-danger', 'text-danger' );
$i = 0;
foreach ($prioritys as $key => $value) {
    $textcolor[$key] = $a[$i];
    $i++;
}
foreach ($tasks as $task) {
    if (98 == $task->status) {
        $tcolor = 'text-success';
    } elseif (99 == $task->status) {
        $tcolor = 'text-muted';
    } else {
        $tcolor = $textcolor[$task->priority];
    }
?>
<tr class="<?=$tcolor?>">
<td><input itag="val" name="ids[]" type="checkbox" value="<?=$task->id?>"></td>
<td><?=isset($pros[$task->pro]) ? $pros[$task->pro]->name : ''?></td>
<td><?=isset($tags[$task->tag]) ? $tags[$task->tag]->name : ''?></td>
<td><?=$status[$task->status]?></td>
<td><?=isset($prioritys[$task->priority]) ? $prioritys[$task->priority] : ''?></td>
<td><?=$catys[$task->caty]->name?></td>
<td class="text-left"><a class="<?=$tcolor?>" href="/task/show/<?=$task->id?>" target="_blank">#<?=$task->id?> <?=cutstr($task->title, 80)?></a></td>
<td><?=$departments[$task->department]->name?></td>
<td><?=$users[$task->leader]->name?></td>
<td><?=isset($users[$task->tester]) ? $users[$task->tester]->name : '-'?></td>
<td><?=date('Y-m-d H:i:s', $task->deadline)?></td>
<td><?=$task->updated_at?></td>
</tr>
<?php } ?>
<tr><td colspan="12" class="text-left">
<?php $this->insert('ajax-page') ?>
</td></tr>
