<?php
$prcolor = 'progress-bar-danger';
if ($p > 80) {
    $prcolor = 'progress-bar-success';
} elseif ($p > 30) {
    $prcolor = 'progress-bar-warning';
}
?>
<div class="progress">
  <div class="progress-bar <?=$prcolor?>" role="progressbar" aria-valuenow="<?=$p?>" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em;width: <?=$p?>%">
    <?=$p?>%
  </div>
</div>